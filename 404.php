<?php
require_once('common.php');

if (!$template->is_cached('Gemstone/404.tpl')) {
	$projects = get_projects();
	$pages = get_pages(array('project' => 0));
	$template->assign(array(
		'error' => true,
		'pages' => $pages,
		'projects' => $projects,
		'active_page' => false,
		'active_project' => false,
	));
}
$template->display('Gemstone/404.tpl');
?>
