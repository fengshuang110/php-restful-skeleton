<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
class Db_Factory{
	private $adapter = null;
	private $db_config = null;
	
	/**
	 * 获得 DBFactory
	 * @param unknown $db_tag
	 * @param string $db_adapter
	 * @throws Exception
	 */
	function __construct($db_tag,$db_adapter='mysql'){
		$config = Conf_Database::getConf($db_tag);
		$this->db_config = $config;
		$this->adapter = $db_adapter;
	}
	
	/**
	 * 
	 * @return Ambigous <NULL,TyqDB_Abstract,TyqDB_Interface>
	 */
	public function getDBAdapter(){
		$adapter = null;
		
		switch ($this->adapter){
			case 'mysql':
				$adapter = new Db_Adapter_Mysql($this->db_config);
				break;
			default:
		}
		return $adapter;
	}
}