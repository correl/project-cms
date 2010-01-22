<?php
/**
* Generates a news feed using either all updates, or updates pertaining to a particular project
*
* Templates are available for generating both RSS and ATOM feeds
*/
require_once('common.php');

$date_rfc2822 = 'D, d M Y H:i:s O';
$date_iso8601 = 'Y-m-d\TH:i:sP';

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'rss2';
switch( $mode ) {
	case 'atom':
		$feed_template = 'atom.xml';
		$content_type = 'application/atom+xml';
		break;
	case 'rss':
	case 'rss2':
	default:
		$feed_template = 'rss.xml';
		$content_type = 'application/rss+xml';
	break;
}
$active_project_id = 0;
$active_project = false;
if (isset($_GET['project'])) {
	$projects = get_projects($_GET['project']);
	if (count($projects) > 0) {
		$active_project = $projects[0];
		$active_project_id = $active_project['project_id'];
	}
}

if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header ('Content-Type: ' . $content_type);

if (!$template->is_cached($feed_template, $active_project_id)) {
	$posts = get_posts(array('project' => $active_project_id, 'limit' => 15));
	$template->assign(array(
		'posts' => $posts,
		'active_project' => $active_project
	));
}
$template->display($feed_template, $active_project_id);
?>
