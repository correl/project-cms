<?php
define('ADMIN', true);
require_once('../common.php');

$db->setLimit(50, 0);
$errors = $db->queryAll("SELECT * FROM {$db->table('errors')} ORDER BY id DESC");
$template->assign('errors', $errors);

$template->display('Gemstone/admin/errors.tpl');
?>
