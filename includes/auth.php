<?php
class Auth {
	private $acl;
	private $user;
	
	private static $permissions = false;
	
	public function __construct() {
		$id = isset($_SESSION['userid']) && is_numeric($_SESSION['userid']) ? $_SESSION['userid'] : false;
		$this->user = $id ? Auth::get_user($id) : false;
	}
	public function user($key = false) {
		if ($key) {
			return is_array($this->user) && array_key_exists($key, $this->user) ? $this->user[$key] : false;
		} else {
			return $this->user;
		}
	}
	public static function get_users() {
		global $db;
		$sql = "SELECT * FROM {$db->table('users')} ORDER BY active DESC, id";
		return $db->queryAll($sql);
	}
	public static function get_user_names($include_inactive = false) {
		global $db;
		$where = !$include_inactive ? 'WHERE active = 1' : '';
		$sql = "SELECT id, login FROM {$db->table('users')} $where ORDER BY name DESC";
		return $db->extended->getAssoc($sql);
	}
	public static function get_groups() {
		global $db;
		$sql = "SELECT * FROM {$db->table('groups')} ORDER BY name";
		return $db->queryAll($sql);
	}
	public static function get_user($id) {
		global $db;
		$id = intval($id);
		$sql = "SELECT * FROM {$db->table('users')} WHERE id = $id";
		$users = $db->queryAll($sql);
		if (empty($users)) return false;
		$user = $users[0];
		$sql = "SELECT
					g.*
				FROM
					{$db->table('user_groups')} ug,
					{$db->table('groups')} g
				WHERE
					ug.user = $id
					AND g.id = ug.group";
		$user['groups'] = $db->queryAll($sql);
		$user['permissions'] = Auth::get_user_permissions($id);
		return $user;
	}
	public function proxy_users($user_id = false) {
		$user_id = (false === $user_id && $this->user ? $this->user['id'] : $user_id);
		if (!is_numeric($user_id)) return false;
		if ($this->has_perm('proxy_all', $user_id)) {
			$users = Auth::get_users();
			$proxies = array();
			foreach ($users as $user) $proxies[$user['id']] = $user['name'];
			return $proxies;
		} else {
			// TODO: Allow certain users to serve as proxies for other specific authors when posting articles?
			$u = Auth::get_user($user_id);
			return array($u['id'] => $u['name']);
		}
	}
	public function can_proxy($proxy_id) {
		$proxies = $this->proxy_users();
		return in_array($proxy_id, array_keys($proxies));
	}
	public function has_perm($permission, $user_id = false) {
		$cache = ($user_id === false);
		$user_id = (false === $user_id && $this->user ? $this->user['id'] : $user_id);
		if (!is_numeric($user_id)) return false;
		if ($cache) {
			if (empty($this->acl)) $this->acl = Auth::get_user_permissions($user_id);
			$acl = $this->acl;
		} else {
			$acl = Auth::get_user_permissions($user_id);
		}
		return array_key_exists($permission, $this->acl) ? ('YES' == $this->acl[$permission]) : false;
	}
	public static function get_user_permissions($user_id) {
		global $db;
		
		// Get the full list of permissions from the database
		if (!is_array(Auth::$permissions)) {
			$sql = "SELECT name FROM {$db->table('permissions')} ORDER BY name";
			Auth::$permissions = $db->queryCol($sql);
		}
		$permissions = array();
		if (1 == $user_id) {
			foreach (Auth::$permissions as $perm) $permissions[$perm] = 'YES';
			return $permissions;
		}
		foreach (Auth::$permissions as $perm) $permissions[$perm] = 'NO';
		
		$sql =	"SELECT
					p.name permission,
					acl.access
				FROM
					{$db->table('user_groups')} ug,
					{$db->table('acl')} acl,
					{$db->table('permissions')} p
				WHERE
					ug.group = acl.group
					AND p.id = acl.permission
					AND ug.user = $user_id";
		$rs = $db->queryAll($sql);
		foreach ($rs as $perm) {
			$access = &$permissions[$perm['permission']];
			if ('NEVER' == $access) continue;
			if ('NO' != $perm['access']) $access = $perm['access'];
		}
		return $permissions;
	}
	public static function get_group_permissions($group_id) {
		global $db;
		// Get the full list of permissions from the database
		if (!is_array(Auth::$permissions)) {
			$sql = "SELECT name FROM {$db->table('permissions')} ORDER BY name";
			Auth::$permissions = $db->queryCol($sql);
		}
		$sql =	"SELECT
					p.name permission,
					acl.access
				FROM
					{$db->table('permissions')} p,
					{$db->table('acl')} acl
				WHERE
					p.id = acl.permission
					AND acl.group = $group_id";
		$rs = $db->queryAll($sql);
		$permissions = array();
		foreach (Auth::$permissions as $perm) $permissions[$perm] = 'NO';
		foreach ($rs as $perm) {
			$permissions[$perm['permission']] = $perm['access'];
		}
		return $permissions;
	}
	public static function update_acl($group, $permission, $access) {
		global $db;
		if (is_string($permission)) {
			$sql = "SELECT `id` FROM {$db->table('permissions')} WHERE `name` = {$db->quote($permission)}";
			$permission = $db->queryOne($sql);
		}
		$access = $db->quote($access);
		$sql = "SELECT `id` FROM {$db->table('acl')} WHERE `group` = $group AND `permission` = $permission";
		$acl_id = $db->queryOne($sql);
		if ($acl_id > 0) {
			$sql = "UPDATE {$db->table('acl')} SET `access` = $access WHERE `id` = $acl_id";
		} else {
			$sql = "INSERT INTO {$db->table('acl')} (`group`, `permission`, `access`)
					VALUES ($group, $permission, $access)";
		}
		$db->query($sql);
	}
	public static function reset_password($user, $subject = null, $message = null) {
		global $db;
		if (!$subject) $subject = l('email_reset_password_subject');
		if (!$message) $message = l('email_reset_password');
		$sql = "SELECT `id`, `name`, `email` FROM {$db->table('users')} WHERE `id` = $user";
		$user = $db->queryRow($sql);
		if (!$user['email']) return false;
		
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$password = '';
		while (strlen($password) < 8) {
			$ch = $chars[rand(0, strlen($chars))];
			$password .= $ch;
		}
		$pw = $db->quote(sha1($password));
		$sql = "UPDATE {$db->table('users')} SET `password` = $pw, `force_pass_change` = 1, `active` = 1 WHERE `id` = {$user['id']}";
		$db->query($sql);
		
		$url = "http://{$_SERVER['HTTP_HOST']}" . dirname($_SERVER['REQUEST_URI']) . "/";
		$subject = sprintf($subject, $user['name']);
		$message = sprintf($message, $user['name'], $url, $password);
		mail($user['email'], $subject, $message);
		return true;
	}
	public static function deactivate($user) {
		global $db;
		$user = intval($user);
		if (!$user) return false;
		$sql = "UPDATE {$db->table('users')} SET `active` = 0 WHERE `id` = $user";
		$db->query($sql);
		return true;
	}
	public function ok() {
		return isset($_SESSION['userid']) && is_numeric($_SESSION['userid']) && $this->user && $this->user['active'];
	}
	public function log_in($username, $password) {
		global $db;

		$sql =	"SELECT *
			FROM {$db->table('users')}
			WHERE
				login = {$db->quote($username)}
				AND active = 1";
			
		$result = $db->queryAll($sql);
		
		if( sizeOf( $result ) > 0 ) {
			$result = $result[0];
			if( sha1($password) == $result['password'] ) {
				session_regenerate_id();
				$_SESSION['username'] = $result['login'];
				$_SESSION['userid'] = $result['id'];
				//log_event('admin', 'EVENT_LOGIN_SUCCESS');
				$this->user = Auth::get_user($result['id']);
			}
		} else {
			//log_event('admin', 'EVENT_LOGIN_FAILURE', $username);
		}
	}
	public function log_out() {
		session_destroy();
		if (function_exists('db_session_setup')) db_session_setup();
		session_start();
		session_regenerate_id();
		$this->user = false;
	}
	public function update_password($password) {
		global $db;
		$password = $db->quote(sha1($password));
		$sql = "UPDATE `{$db->table('users')}` SET `password` = $password, `force_pass_change` = 0 WHERE `id` = {$this->user['id']}";
		$db->query($sql);
	}
}

class AuthException extends Exception {}

$auth = new Auth();
?>
