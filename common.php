<?php
define('APP_PATH', realpath(dirname(__FILE__)) . '/');
if (is_file(APP_PATH . 'config.php') && is_readable(APP_PATH . 'config.php')) {
	include_once(APP_PATH . 'config.php');
} else {
	die('Configuration does not exist or is not readable');
}

set_include_path(get_include_path() . PATH_SEPARATOR . APP_PATH);
if (defined('SMARTY_PATH')) set_include_path(get_include_path() . PATH_SEPARATOR . SMARTY_PATH);
if (defined('PEAR_PATH')) set_include_path(get_include_path() . PATH_SEPARATOR . PEAR_PATH . 'lib/');

require_once('PEAR.php');
require_once('includes/error.php');
require_once('includes/functions.php');
require_once('includes/templates.php');
require_once('includes/database.php');

if (is_file(APP_PATH . 'config.php') && is_readable(APP_PATH . 'config.php')) {
	include_once(APP_PATH . 'config.php');
} else {
	die('Configuration does not exist or is not readable');
}

Error::set_options(array(
	'file' => APP_PATH . 'files/error_log',
	'debug' => DEBUG,
));

$db = new Database($config['dsn']);
$template = new Projects_Smarty();

session_set_cookie_params(1800, WEB_PATH, $_SERVER['HTTP_HOST']);
session_start();
if (defined('ADMIN') && true === ADMIN) {
	require_once('includes/auth.php');
	if( isset($_GET['logout'] ) ) $auth->log_out();
	if (!$auth->ok() && basename($_SERVER['SCRIPT_NAME']) != 'login.php')
	{
		header('Location: login.php');
		exit;
	}
	/*
	if ($auth->ok() && $auth->user('force_pass_change') && basename($_SERVER['SCRIPT_NAME']) != 'password.php') {
		header('Location: password.php');
		exit;
	}
	*/
	$template->assign_by_ref('auth', $auth);
}
?>
