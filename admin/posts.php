<?php
define('ADMIN', true);
require_once('../common.php');

$project = isset($_REQUEST['project']) ? intval($_REQUEST['project']) : 0;

$pages = get_posts(array('project' => $project));
$template->assign('posts', $pages);
$template->display('Gemstone/admin/posts.tpl');
?>
