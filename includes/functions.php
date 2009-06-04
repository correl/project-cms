<?php
function get_posts($options = false) {
	global $mysql;
	
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
			t.text text,
			at.text additional_text,
			u.user_name,
			pr.project_id,
			pr.project_name,
			pr.project_short_name
		FROM
			posts p
			LEFT OUTER JOIN projects pr ON p.project_id = pr.project_id
			JOIN users u ON p.user_id = u.user_id
			JOIN text t ON p.post_text = t.text_id
			LEFT OUTER JOIN text at ON p.post_additional_text = at.text_id
			LEFT OUTER JOIN pages pg ON p.post_id = pg.post_id
		$where_sql
		ORDER BY p.post_date DESC
		$limit_sql";
	$result = mysql_query($sql, $mysql);
	if (!$result) die(mysql_error($mysql));
	$posts = array();
	while ($row = mysql_fetch_assoc($result)) $posts[] = $row;
	return $posts;
}
function get_pages($options = false) {
	global $mysql;
	
	if (!is_array($options)) $options = array();
	$constraints = array();
	$where_sql = $limit_sql = '';
	if (isset($options['page'])) {
		if (is_numeric($options['page'])) {
			$constraints[] = 'pg.page_id = ' . intval($options['page']);
		} else {
			$constraints[] = "pg.page_name = '" . mysql_escape_string($options['page']) . "'";
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
			t.text,
			at.text additional_text,
			u.user_name,
			pr.project_id,
			pr.project_name,
			pr.project_short_name
		FROM
			pages pg
			JOIN posts p ON pg.post_id = p.post_id
			LEFT OUTER JOIN projects pr ON p.project_id = pr.project_id
			JOIN users u ON p.user_id = u.user_id
			JOIN text t ON p.post_text = t.text_id
			LEFT OUTER JOIN text at ON p.post_additional_text = at.text_id
		$where_sql
		ORDER BY pg.page_name
		$limit_sql";
	$result = mysql_query($sql, $mysql);
	if (!$result) die(mysql_error($mysql));
	$pages = array();
	while ($row = mysql_fetch_assoc($result)) $pages[] = $row;
	return $pages;
}
function get_projects($project_id = false) {
	global $mysql;
	
	$sql = "SELECT
			project_id,
			project_name,
			project_short_name,
			project_main_page
		FROM projects";
	if (is_numeric($project_id) && $project_id > 0) {
		$sql .= " WHERE project_id = " . intval($project_id);
	} elseif (!empty($project_id)) {
		$sql .=" WHERE project_short_name = '" . mysql_escape_string($project_id) . "'";
	}
	$result = mysql_query($sql, $mysql);
	if (!$result) die(mysql_error($mysql));
	$projects = array();
	while ($row = mysql_fetch_assoc($result)) $projects[] = $row;
	return $projects;
}
?>
