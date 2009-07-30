<?php
define('ADMIN', true);
require_once('../common.php');

if (isset($_POST['save'])) {
	try {
		if (
			!isset($_POST['post_id'])
			|| !isset($_POST['project_id'])
			|| !isset($_POST['post_title'])
			|| !isset($_POST['post_text_id'])
			|| !isset($_POST['post_text'])
			|| !isset($_POST['post_additional_text_id'])
			|| !isset($_POST['post_additional_text'])
		) {
			throw new Exception('Missing posted fields attempting to save post data; Received: ' . implode(', ', array_keys($_POST)));
		}
		$post_options = array(
			'post_id' => intval($_POST['post_id']),
			'user_id' => isset($_POST['user_id']) && $auth->has_perm('proxy_all') ? intval($_POST['user_id']) : $auth->user('id'),
			'project_id' => intval($_POST['project_id']),
			'post_title' => trim($_POST['post_title']),
			'post_text_id' => intval($_POST['post_text_id']),
			'post_text' => trim($_POST['post_text']),
			'post_additional_text_id' => intval($_POST['post_additional_text_id']),
			'post_additional_text' => trim($_POST['post_additional_text'])
		);
		if (isset($_POST['page_id']) && isset($_POST['page_name'])) {
			$post_options['page_id'] = intval($_POST['page_id']);
			$post_options['page_name'] = trim($_POST['page_name']);
		}
		
		$errors = array();
		if (isset($post_options['page_name']) && empty($post_options['page_name'])) $errors['page_name'] = 'Page name must not be blank';
		if (empty($post_options['post_title'])) $errors['post_title'] = 'Post title must not be blank';
		if (empty($post_options['post_text'])) $errors['post_text'] = 'Post text must not be blank';
		
		if (empty($errors)) {
			$post_id = save_post($post_options);
			if ($post_id) {
				if ($post_options['project_id'] > 0) {
					header("Location: projects.php?project={$post_options['project_id']}");
				} else {
					header('Location: pages.php');
				}
				exit;
			}
		}
	} catch (AuthException $e) {
		/*
			TODO: Use an activity log rather than the exception log
			i.e. app_log( [WARNING | INFO], message )
		*/
		Error::log_exception($e);
		$auth->log_out();
		header('Location: login.php');
		exit;
	} catch (Exception $e) {
		Error::log_exception($e);
	}
}

$project_id = isset($_REQUEST['project']) ? intval($_REQUEST['project']) : 0;

if (isset($_REQUEST['cancel'])) {
	if ($project_id > 0) {
		header("Location: projects.php?project=$project_id");
	} else {
		header('Location: pages.php');
	}
	exit;
} elseif (isset($_REQUEST['page'])) {
	$page = intval($_REQUEST['page']);
	$posts = get_pages(array('page' => $page));
} elseif (isset($_REQUEST['post'])) {
	$post = intval($_REQUEST['post']);
	$posts = get_posts(array('post' => $post));
} else {
	header('Location: index.php');
	exit;
}

$all_projects = get_projects();
$projects = array();
foreach ($all_projects as $project) {
	$projects[$project['project_id']] = $project['project_name'];
}

if (isset($post_options)) {
	$post = array(
		'post_id' => $post_options['post_id'],
		'project_id' => $post_options['project_id'],
		'post_title' => $post_options['post_title'],
		'text_id' => $post_options['post_text_id'],
		'text' => $post_options['post_text'],
		'additional_text_id' => $post_options['post_additional_text_id'],
		'additional_text' => $post_options['post_additional_text'],
	);
	if (isset($post_options['page_id'])) {
		$post['page_id'] = $post_options['page_id'];
		$post['page_name'] = $post_options['page_name'];
	}
} else {
	$post = !empty($posts) ? $posts[0] : array(
		'post_id' => 0,
		'project_id' => $project_id,
		'page_id' => 0,
		'page_name' => '',
		'post_title' => '',
		'text_id' => 0,
		'text' => '',
		'additional_text_id' => 0,
		'additional_text' => '',
	);
}

$template->assign(array(
	'page' => isset($page),
	'post' => $post,
	'projects' => $projects,
	'errors' => isset($errors) ? $errors : array()
));
$template->display('Gemstone/admin/edit.tpl');
?>
