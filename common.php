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

set_error_handler(create_function('$a, $b, $c, $d', 'throw new ErrorException($b, 0, $a, $c, $d);'), E_ALL);

require_once('includes/functions.php');
require_once('includes/templates.php');
require_once('includes/database.php');

if (is_file(APP_PATH . 'config.php') && is_readable(APP_PATH . 'config.php')) {
	include_once(APP_PATH . 'config.php');
} else {
	die('Configuration does not exist or is not readable');
}

$db = new Database($config['dsn']);
$template = new Projects_Smarty();
?>
