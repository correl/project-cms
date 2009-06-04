<?php
require_once('MDB2.php');

class Database {
	private $_dsn;
	private $_mdb2;
	private $_table_prefix;
	private $_tables = array();
	
	public function __construct($dsn) {
		$this->_dsn = $dsn;
	}
	public function connection() {
		if (!$this->_mdb2) {
			$this->_mdb2 =& MDB2::factory($this->_dsn);
			if (PEAR::isError($this->_mdb2)) die($this->_mdb2->getMessage());
			$this->_mdb2->loadModule('Extended', null, false);
			$this->_mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
		}
		return $this->_mdb2;
	}
	public function table_prefix($prefix = false) {
		if ($prefix) $this->_table_prefix = $prefix;
		return $this->_table_prefix;
	}
	public function tables($tables = false) {
		if (is_array($tables)) $this->_tables = $tables;
		return $this->_tables;
	}
	public function table($table) {
		$table = strtolower($table);
		$mapped_table = $table;
		if (array_key_exists($table, $this->_tables)) $mapped_table = $this->_tables[$table];
		return $this->_table_prefix . $mapped_table;
	}
	public function __call($name, $arguments) {
		// Pass unknown calls through to the MDB2 object if they exist, otherwise trigger an error
		$conn =& $this->connection();
		if (!is_object($conn)) {
			return PEAR::raiseError('Database connection failure');
		}
		if (!method_exists($conn, $name)) return PEAR::raiseError("Method '$name' does not exist in class " . get_class($conn));
		return call_user_func_array(array($conn, $name), $arguments);
	}
}
?>
