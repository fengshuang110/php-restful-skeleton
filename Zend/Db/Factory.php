<?php
namespace Zend\Db;
use Zend\Db\Adapter\Mysql;
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
class Factory{
	private $adapter = null;
	private $db_config = null;
	
	/**
	 * 获得 DBFactory
	 * @param unknown $db_tag
	 * @param string $db_adapter
	 * @throws Exception
	 */
	function __construct($config,$db_adapter='mysql'){
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
				$adapter = new Mysql($this->db_config);
				break;
			default:
		}
		return $adapter;
	}
}