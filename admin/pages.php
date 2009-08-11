<?php
define('ADMIN', true);
require_once('../common.php');

$project = isset($_REQUEST['project']) ? intval($_REQUEST['project']) : 0;

$pages = get_pages(array('project' => $project));
$template->assign('pages', $pages);
$template->display('Gemstone/admin/pages.tpl');
?>
