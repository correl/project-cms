<?php
require_once('PEAR/Exception.php');

/**
* Error handling and logging utility object
*
* Overrides PHP error handlers when initialized
* Converts PHP errors to Exceptions
* Will attempt to log errors to the database, and if that fails, to a flat file.
* If debugging is enabled, it will display the error on screen
*/
class Error {
	private static $options = array();

	/**
	* Sets up error handling
	*/
	public static function init() {
		set_error_handler(array('Error', 'error_handler'), error_reporting());
		set_exception_handler(array('Error', 'exception_handler'));
		PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, array('Error', 'pear_error_handler'));
	}
	/**
	* Converts PHP errors to Exceptions
	*/
	public static function error_handler($severity, $message, $filename, $line_number) {
		throw new ErrorException($message, 0, $severity, $filename, $line_number);
	}
	/**
	* Exception handler
	*
	* Logs the exception and displays a basic error message
	*/
	public static function exception_handler($e) {
		Error::log_exception($e, false);
		echo 'An error occurred.';
	}
	/**
	* Converts PEAR errors to PEAR Exceptions
	*/
	public static function pear_error_handler($e) {
		throw new PEAR_Exception($e);
	}
	/**
	* Sets error handling options specified in an associative array
	*
	* Options;
	*        * boolean debug  Enable on-screen display of errors
	*        * string file           File to log errors to in case the database is not available
	*/
	public static function set_options($options) {
		if (!is_array($options)) return;
		foreach ($options as $key => $value) {
			Error::$options[$key] = $value;
		}
	}
	/**
	* Logs an exception
	*
	* Attempts to log to the database, otherwise will fall back to a file if one was specified in the options
	* Displays the error to the screen if debugging is enabled
	*/
	public static function log_exception($e, $caught = true) {
		global $db;
		if ($db) {
			try {
				Error::log_exception_to_db($e, $caught);
			} catch (Exception $e_db) {
				Error::log_exception_to_file($e_db);
				if (array_key_exists('debug', Error::$options) && Error::$options['debug']) Error::log_exception_to_screen($e_db);
				Error::log_exception_to_file($e, $caught);
			}
		} else {
			Error::log_exception_to_file($e, $caught);
		}
		if (array_key_exists('debug', Error::$options) && Error::$options['debug']) Error::log_exception_to_screen($e, $caught);
	}
	/**
	* Logs an exception to the database
	*/
	private static function log_exception_to_db($e, $caught = true) {
		global $db;
		$caught = $db->quote($caught ? 1 : 0);
		$severity = $db->quote($e instanceof ErrorException ? $e->getSeverity() : 0);
		$trace = $db->quote(serialize($e->getTrace()));
		$sql = "INSERT INTO errors (caught, severity, code, message, filename, line_number, trace, trace_string)
			VALUES (
				$caught,
				$severity,
				{$db->quote($e->getCode())},
				{$db->quote($e->getMessage())},
				{$db->quote($e->getFile())},
				{$db->quote($e->getLine())},
				$trace,
				{$db->quote($e->getTraceAsString())}
			)";
		$db->query($sql);
	}
	/**
	* Logs an exception to a file
	*/
	private static function log_exception_to_file($e, $caught = true) {
		$file = array_key_exists('file', Error::$options) ? Error::$options['file'] : false;
		if (!$file) return;

		$caught = $caught ? 'Caught' : 'Uncaught';
		$severity = $e instanceof ErrorException ? $e->getSeverity() : 0;
		$trace = serialize($e->getTrace());
		$line = array("{$caught} Exception:",
			$severity,
			$e->getCode(),
			$e->getFile(),
			$e->getLine(),
			date('Y-m-d H:i:s'),
			$trace,
			$e->getMessage(),
		);
		$line = implode("\t", $line);
		$line = str_replace("\n", '\n', $line);
		try {
			$output = fopen($file, 'a');
			fwrite($output, "$line\n");
			fclose($output);
		} catch (Exception $e) {}
	}
	/**
	* Logs an exception to the screen
	*/
	private static function log_exception_to_screen($e, $caught = true) {
		printf("
			<div style=\"margin: 3px; border: 3px solid #000; background-color: #DD7777; padding: 3px;\">
				<p style=\"font-size: 1.5em;\"><b>%s Exception<b></p>
				<p>%s</p>
				<pre>%s</pre>
			</div>",
			$caught ? 'Caught' : 'Uncaught',
			$e->getMessage(),
			$e->getTraceAsString()
		);
	}
}
Error::init();
?>
