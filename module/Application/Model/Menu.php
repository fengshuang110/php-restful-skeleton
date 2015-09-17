<?php
namespace Application\Model;
use Zend\Db\Dao;
use Zend\Db\Sql;
class Menu extends Dao{

	private static $tag = 'Model_Menu';
	protected $table = 'sys_menu';
	protected $alias = 'sys_user';
	protected $primaryKey = 'menu_id';
	public   $adapter = null;
	
	function __construct($adapter= null){
		$this->adapter = $adapter;
		$this->sql_helper = new Sql();
	}
	
	public function findAll(){
		$sql = "select * from ".$this->table ." where 1=1";
		return $this->adapter
					->conn()
			 		->preparedSql($sql, array())
			 		->fetchAll();;
	}
	
	public function getAll($params,$is_count = false){
		return $this->select($params,$is_count);
			
	}
	
}