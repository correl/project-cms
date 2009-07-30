<?php
function get_posts($options = false) {
	global $db;
	
	if (!is_array($options)) $options = array();
	$constraints = array('pg.page_id IS NULL');
	$where_sql = $limit_sql = '';
	if (isset($options['post']) && $options['post'] > 0) $constraints[] = 'p.post_id = ' . intval($options['post']);
	if (isset($options['project']) && $options['project'] > 0) $constraints[] = 'pr.project_id = ' . intval($options['project']);
	if (isset($options['user']) && $options['user'] > 0) $constraints[] = 'u.user_id = ' . intval($options['user']);
	if (isset($options['limit']) && $options['limit'] > 0) $limit_sql = 'LIMIT ' . intval($options['limit']);
	if (!empty($constraints)) $where_sql = 'WHERE ' . implode(' AND ', $constraints);
	$sql = "SELECT
			p.post_id,
			p.post_title,
			p.post_date,
			t.text_id,
			t.text text,
			at.text_id additional_text_id,
			at.text additional_text,
			u.name user_name,
			pr.project_id,
			pr.project_name,
			pr.project_short_name
		FROM
			posts p
			LEFT OUTER JOIN {$db->table('projects')} pr ON p.project_id = pr.project_id
			JOIN {$db->table('users')} u ON p.user_id = u.id
			JOIN {$db->table('text')} t ON p.post_text = t.text_id
			LEFT OUTER JOIN {$db->table('text')} at ON p.post_additional_text = at.text_id
			LEFT OUTER JOIN {$db->table('pages')} pg ON p.post_id = pg.post_id
		$where_sql
		ORDER BY p.post_date DESC
		$limit_sql";
	try {
		$posts = $db->queryAll($sql);
	} catch (Exception $e) {
		Error::log_exception($e);
		$posts = array();
	}
	return $posts;
}
function get_pages($options = false) {
	global $db;
	
	if (!is_array($options)) $options = array();
	$constraints = array();
	$where_sql = $limit_sql = '';
	if (isset($options['page'])) {
		if (is_numeric($options['page'])) {
			$constraints[] = 'pg.page_id = ' . intval($options['page']);
		} else {
			$constraints[] = "pg.page_name = " . $db->quote($options['page']);
		}
	}
	if (isset($options['project'])) $constraints[] = 'p.project_id = ' . intval($options['project']);
	if (isset($options['user']) && $options['user'] > 0) $constraints[] = 'u.user_id = ' . intval($options['user']);
	if (isset($options['limit']) && $options['limit'] > 0) $limit_sql = 'LIMIT ' . intval($options['limit']);
	if (!empty($constraints)) $where_sql = 'WHERE ' . implode(' AND ', $constraints);
	$sql = "SELECT
			pg.page_id,
			pg.page_name,
			p.post_id,
			p.post_title,
			p.post_date,
			t.text_id,
			t.text,
			at.text_id additional_text_id,
			at.text additional_text,
			u.name user_name,
			pr.project_id,
			pr.project_name,
			pr.project_short_name
		FROM
			pages pg
			JOIN {$db->table('posts')} p ON pg.post_id = p.post_id
			LEFT OUTER JOIN {$db->table('projects')} pr ON p.project_id = pr.project_id
			JOIN {$db->table('users')} u ON p.user_id = u.id
			JOIN {$db->table('text')} t ON p.post_text = t.text_id
			LEFT OUTER JOIN {$db->table('text')} at ON p.post_additional_text = at.text_id
		$where_sql
		ORDER BY pg.page_name
		$limit_sql";
	try {
		$pages = $db->queryAll($sql);
	} catch (Exception $e) {
		Error::log_exception($e);
		$pages = array();
	}
	return $pages;
}
function get_projects($project_id = false) {
	global $db;
	
	$sql = "SELECT
			project_id,
			project_name,
			project_short_name,
			project_main_page
			FROM {$db->table('projects')}";
	if (is_numeric($project_id) && $project_id > 0) {
		$sql .= " WHERE project_id = " . intval($project_id);
	} elseif (!empty($project_id)) {
		$sql .=" WHERE project_short_name = " . $db->quote($project_id);
	}
	try {
		$projects = $db->queryAll($sql);
	} catch (Exception $e) {
		Error::log_exception($e);
		$projects = array();
	}
	return $projects;
}
function save_post($options) {
	global $db, $auth;
	
	if (!isset($options['post_id'])
		|| !isset($options['user_id'])
		|| !isset($options['project_id'])
		|| !isset($options['post_title'])
		|| !isset($options['post_text_id'])
		|| !isset($options['post_text'])
		|| !isset($options['post_additional_text_id'])
		|| !isset($options['post_additional_text'])
	) {
		throw new Exception('Missing required fields attempting to save post data; Received: ' . var_export($options));
	}
	
	$post_id = intval($options['post_id']);
	$user_id = intval($options['user_id']);
	$project_id = intval($options['project_id']);
	$page_id = isset($options['page_id']) ? intval($options['page_id']) : false;
	$page_name = isset($options['page_name']) ? trim($options['page_name']) : false;
	$post_title = trim($options['post_title']);
	$post_text_id = intval($options['post_text_id']);
	$post_text = trim($options['post_text']);
	$post_additional_text_id = intval($options['post_additional_text_id']);
	$post_additional_text = trim($options['post_additional_text']);
	
	if ($db->supports('transactions')) $db->beginTransaction();
	
	try {
		if ($post_text_id) {
			$sql = "UPDATE {$db->table('text')} SET text = {$db->quote($post_text)} WHERE text_id = {$db->quote($post_text_id)}";
			$db->query($sql);
		} else {
			$id = $db->extended->getBeforeId($db->table('text'));
			$sql = "INSERT INTO {$db->table('text')} (text_id, text) VALUES ({$db->quote($id)}, {$db->quote($post_text)})";
			$db->query($sql);
			$post_text_id = $db->extended->getAfterId($id, $db->table('text'));
		}
		if ($post_additional_text_id) {
			if (!empty($post_additional_text)) {
				$sql = "UPDATE {$db->table('text')} SET text = {$db->quote($post_additional_text)} WHERE text_id = {$db->quote($post_additional_text_id)}";
			} else {
				$sql = "DELETE FROM {$db->table('text')} WHERE text_id = {$db->quote($post_additional_text_id)}";
				$post_additional_text_id = null;
			}
			$db->query($sql);
		} elseif (!empty($post_additional_text)) {
			$id = $db->extended->getBeforeId($db->table('text'));
			$sql = "INSERT INTO {$db->table('text')} (text_id, text) VALUES ({$db->quote($id)}, {$db->quote($post_additional_text)})";
			$db->query($sql);
			$post_additional_text_id = $db->extended->getAfterId($id, $db->table('text'));
		} else {
			$post_additional_text_id = null;
		}
		if ($post_id) {
			$sql = "UPDATE {$db->table('posts')}
				SET
					user_id = {$db->quote($user_id)},
					project_id = {$db->quote($project_id)},
					post_title = {$db->quote($post_title)},
					post_text = {$db->quote($post_text_id)},
					post_additional_text = {$db->quote($post_additional_text_id)}
				WHERE post_id = {$db->quote($post_id)}";
			$db->query($sql);
		} else {
			$id = $db->extended->getBeforeId($db->table('posts'));
			$sql = "INSERT INTO {$db->table('posts')} (post_id, user_id, project_id, post_title, post_text, post_additional_text)
				VALUES (
					{$db->quote($id)},
					{$db->quote($auth->user('id'))},
					{$db->quote($project_id)},
					{$db->quote($post_title)},
					{$db->quote($post_text_id)},
					{$db->quote($post_additional_text_id)}
				)";
			$db->query($sql);
			$post_id = $db->extended->getAfterId($id, $db->table('posts'));
		}
		if (false !== $page_id) {
			if ($page_id) {
				$sql = "UPDATE {$db->table('pages')}
					SET
						page_name = {$db->quote($page_name)},
						post_id = {$db->quote($post_id)}
					WHERE page_id = {$db->quote($page_id)}";
				$db->query($sql);
			} else {
				$id = $db->extended->getBeforeId($db->table('pages'));
				$sql = "INSERT INTO {$db->table('pages')} (page_id, page_name, post_id)
					VALUES (
						{$db->quote($id)},
						{$db->quote($page_name)},
						{$db->quote($post_id)}
					)";
				$db->query($sql);
				$page_id = $db->extended->getAfterId($id, $db->table('pages'));
			}
		}
	} catch (Exception $e) {
		if ($db->in_transaction) $db->rollback();
		Error::log_exception($e);
		return false;
	}
	if ($db->in_transaction) $db->commit();
	return $page_id;
}
function is_vector( &$array ) {
	$next = 0;
	foreach( $array as $k=>$v ) {
		if( $k != $next ) {
			return false;
		}
		$next++;
	}
	return true;
}
function json_escape( $string ) {
	$chars = array(
		'\\',
		'"',
		'\'',
		'/',
		"\r",
		"\n",
		"\t"
	);
	$esc = array(
		'\\\\',
		'\\"',
		'\\\'',
		'\\/',
		'\\r',
		'\\n',
		'\\t'
	);
	$string = str_replace( $chars, $esc, $string );
	return "\"$string\"";
}

function json( $array ) {
	if( !is_array( $array ) ) { return json_escape( $array ); }
	
	$items = array();
	if( is_vector( $array ) ) {
		foreach( $array as $key => $value ) {
			$value = ( is_array( $value ) ? json( $value ) : ( is_numeric( $value ) ? $value : json_escape( $value ) ) );
			$items[] = $value;
		}
		$json = '[' . implode( ',', $items ) . ']';
	} else {
		foreach( $array as $key => $value ) {
			$value = ( is_array( $value ) ? json( $value ) : json_escape( $value ) );
			$items[] = "'$key': $value";
		}
		$json = '{' . implode( ',', $items ) . '}';
	}
	return $json;
}
?>
