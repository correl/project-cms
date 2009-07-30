<?php
require_once('PEAR/Exception.php');

class Error {
	private static $options = array();
	
	public static function init() {
		set_error_handler(array('Error', 'error_handler'), error_reporting());
		set_exception_handler(array('Error', 'exception_handler'));
		PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, array('Error', 'pear_error_handler'));
	}
	public static function error_handler($severity, $message, $filename, $line_number) {
		throw new ErrorException($message, 0, $severity, $filename, $line_number);
	}
	public static function exception_handler($e) {
		Error::log_exception($e, false);
		echo 'An error occurred.';
	}
	public static function pear_error_handler($e) {
		throw new PEAR_Exception($e);
	}
	public static function set_options($options) {
		if (!is_array($options)) return;
		foreach ($options as $key => $value) {
			Error::$options[$key] = $value;
		}
	}
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
