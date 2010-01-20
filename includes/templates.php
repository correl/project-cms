<?php
require_once('Smarty.class.php');

class Projects_Smarty extends Smarty {
	public function __construct() {
		parent::__construct();
		$this->template_dir = APP_PATH . 'templates';
		$this->compile_dir = APP_PATH . 'templates_c';
		$this->config_dir = APP_PATH . 'configs';
		$this->cache_dir = APP_PATH . 'cache';
		$this->caching = defined('ADMIN') && true === ADMIN ? 0 : 1;
		if (true === DEBUG) {
			$this->force_compile = true;
			$this->debugging = true;
		}
		$this->register_function('link', array('Projects_Smarty', '_tpl_link'));
		$this->register_function('captcha', array('Projects_Smarty', '_recaptcha'), false);
	}
	static function _tpl_link($params, &$smarty) {
		$type = isset($params['type']) ? strtolower($params['type']) : '';
		if (isset($params['resource'])) $resource = $params['resource']; else return false;

		switch ($type) {
		case 'project':
			$link = WEB_PATH . "project/{$resource}/";
			break;
		case 'page':
			$project = isset($params['project']) ? "project/{$params['project']}" : 'pages';
			$link = WEB_PATH . "{$project}/{$resource}";
			break;
		case 'post':
			$project = isset($params['project']) ? $params['project'] : false;
			$link = WEB_PATH . ($project ? "project/{$project}/" : '') . $resource;
			break;
		case 'feed':
			$project = isset($params['project']) ? $params['project'] : false;
			$link = WEB_PATH . ($project ? "project/{$project}/feeds/" : 'feeds/') . $resource;
			break;
		case 'file':
			$link = WEB_PATH . "files/{$resource}";
			break;
		default:
			$link = WEB_PATH . $resource;
		}
		if (isset($params['external']) && $params['external']) $link = "http://{$_SERVER['HTTP_HOST']}{$link}";
		if (isset($params['anchor'])) $link .= "#{$params['anchor']}";
		return $link;
	}
	static function _recaptcha($params, &$smarty) {
		global $config;
		$host = $_SERVER['HTTP_HOST'];
		$public_key = isset($config['recaptcha'][$host]) ? $config['recaptcha'][$host]['public_key'] : '';
		$error = isset($params['error']) ? $params['error'] : null;
		if (!$public_key) return null;
		return recaptcha_get_html($public_key, $error);
	}
}
?>
