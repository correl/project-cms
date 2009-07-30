<?php
define('ADMIN', true);
require_once('../common.php');

if (!isset($_REQUEST['project'])) {
	$template->assign(array(
		'projects' => get_projects()
	));
	$template->display('Gemstone/admin/projects.tpl');
	exit;
}
$project_id = intval($_REQUEST['project']);
if (!$project_id) {
	
} else {
	$projects = get_projects($project_id);
	if (empty($projects)) {
		header('Location: projects.php');
		exit;
	}
	$project = $projects[0];
	$pages = get_pages(array('project' => $project_id));
	$posts = get_posts(array('project' => $project_id, 'limit' => 5));
	
	$template->assign(array(
		'project' => $project,
		'pages' => $pages,
		'posts' => $posts,
	));
	$template->display('Gemstone/admin/project.tpl');
}
?>
