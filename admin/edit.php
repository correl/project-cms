<?php
define('ADMIN', true);
require_once('../common.php');

if (isset($_POST['save'])) {
	try {
		if (!$auth->has_perm('post_edit')) throw new AuthException("Unauthorized attempt to modify post by user {$auth->user('id')}");
		
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
			'user_id' => $auth->user('id'),
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
		
		$post_id = save_post($post_options);
		
		if ($post_id) {
			header('Location: pages.php');
			exit;
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

$post = !empty($posts) ? $posts[0] : array(
	'post_id' => 0,
	'page_id' => 0,
	'page_name' => '',
	'post_title' => '',
	'text' => '',
	'additional_text' => '',
);

$template->assign(array(
	'page' => isset($page),
	'post' => $post
));
$template->display('Gemstone/admin/edit.tpl');
?>
