<?php
namespace Application\Model;
use Zend\Db\Dao;
use Zend\Db\Sql;
class Role extends Dao{

	private static $tag = 'Model_Role';
	protected $table = 'sys_role';
	protected $alias = 'sys_role';
	protected $conn = NULL;
	protected $primaryKey = 'role_id';
	public   $adapter = null;
	
	function __construct($adapter= null){
		$this->adapter = $adapter;
		$this->sql_helper = new Sql();
	}
	
	public function getAll($params = array(),$is_count = true){
		return $this->select($params,$is_count);
	}
	
}