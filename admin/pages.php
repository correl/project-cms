<?php
define('ADMIN', true);
require_once('../common.php');

$project = intval($_REQUEST['project']);

$pages = get_pages(array('project' => $project));
$template->assign('pages', $pages);
$template->display('Gemstone/admin/pages.tpl');
?>
