<?php
define('ADMIN', true);
require_once('../common.php');

if ((isset($_POST['login']) === TRUE) && (isset($_POST['password']) === TRUE)) {
	$auth->log_in($_POST['login'], $_POST['password']);
	session_write_close();
	header( 'Location: index.php' );
	exit();
}

$template->display('Gemstone/admin/login.tpl');
?>
