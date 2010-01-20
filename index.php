<?php
require_once('common.php');

$active_project = null;
$active_project_id = 0;
$active_page = null;
$active_page_id = 0;
$active_post = null;
$active_post_id = 0;

if (isset($_GET['project'])) {
	$projects = get_projects($_GET['project']);
	if (count($projects) > 0) {
		$active_project = $projects[0];
		$active_project_id = $active_project['project_id'];
	} else {
		// Invalid project
		require_once('404.php');
		exit;
	}
}
if (isset($_GET['page'])) {
	$pages = get_pages(array('project' => $active_project_id, 'page' => $_GET['page']));
	if (count($pages) > 0) {
		$active_page = $pages[0];
		$active_page_id = $active_page['page_id'];
		$active_post_id = $active_page['post_id'];
	} else {
		// Invalid page
		require_once('404.php');
		exit;
	}
}
if (isset($_GET['post'])) {
	$posts = get_posts(array('post' => $_GET['post']));
	if (count($posts) > 0) {
		$active_post = $posts[0];
		$active_post_id = $active_post['post_id'];
	} else {
		// Invalid post
		require_once('404.php');
		exit;
	}
}
$cache_id = "{$active_project_id}:{$active_page_id}:{$active_post_id}";

if (isset($_POST['comment'])) {
	$template->caching = 0;
	$text = trim($_POST['text']);
	$text = purify_html($text);
	$host = $_SERVER['HTTP_HOST'];
	if (!isset($config['recaptcha'][$host])) {
		$captcha_ok = true;
	} else {
		$private_key = $config['recaptcha'][$host]['private_key'];
		$response = recaptcha_check_answer ($private_key,
			$_SERVER['REMOTE_ADDR'],
			$_POST['recaptcha_challenge_field'],
			$_POST['recaptcha_response_field']);
		$captcha_ok = $response->is_valid;
		if (!$captcha_ok) {
			$captcha_error = $response->error;
			$template->assign('captcha_error', $captcha_error);
		}
	}
	if (!empty($text)
		&& $captcha_ok
	) {
		save_comment(array(
			'post_id' => $active_post_id,
			'name' => $_POST['name'],
			'website' => $_POST['website'],
			'text' => $text
		));
		try {
			$template->clear_cache('Gemstone/index.tpl', $cache_id);
		} catch (Exception $e) {
			Error::log_exception($e);
		}
	}
}
if (!$template->is_cached('Gemstone/index.tpl', $cache_id)) {
	$posts = get_posts(array('post' => $active_post_id, 'project' => $active_project_id, 'limit' => 8));
	$main_pages = get_pages(array('project' => 0));
	$project_pages = $active_project_id > 0 ? get_pages(array('project' => $active_project_id)) : array();
	if ($active_project_id > 0 && $active_project['project_main_page'] > 0 && $active_page_id == 0) {
		$pages = get_pages(array('project' => $active_project_id, 'page' => $active_project['project_main_page']));
		if (count($pages) > 0) {
			$active_project['project_main_page'] = $pages[0];
		} else {
			$active_project['project_main_page'] = 0;
		}
	}
	if ($active_page) $active_page = &$posts[0];
	if ($active_post) $active_post = &$posts[0];
	if ($active_post) $active_post['comments'] = get_comments($active_post_id);
	$projects = get_projects();
	$template->assign(array(
		'pages' => $main_pages,
		'posts' => $posts,
		'projects' => $projects,
		'project_pages' => $project_pages,
		'active_project' => $active_project,
		'active_page' => $active_page,
		'active_post' => $active_post,
		'captcha_error' => isset($captcha_error) ? $captcha_error : null,
	));
}
$template->display('Gemstone/index.tpl', $cache_id);
?>
