<?php
/** Model */
class Model{
	/** @var hold the database connection */
	protected $_db;
	
	/**  __construct */
	function __construct(){
		$this->_db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
		$this->_db->exec("SET NAMES 'utf8';");
	}
}
?>