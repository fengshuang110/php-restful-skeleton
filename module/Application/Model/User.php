<?php
namespace Application\Model;
use Zend\Db\Dao;
use Zend\Db\Sql;
class User extends Dao{

	private static $tag = 'Model_User';
	protected $table = 'sys_user';
	protected $alias = 'sys_user';
	protected $primaryKey = 'user_id';
		
	protected $foreignKey = 'role';
	protected $foreign = array(
			"table"=>"sys_role",
			"key"=>"role_id",
			"field"=>array("role_name")
	);
	
	public   $adapter = null;
	
	function __construct($adapter= null){
	
		$this->adapter = $adapter;
		$this->sql_helper = new Sql();
	}
	
	
	public function getAll($params,$is_count = true){
		 return $this->select($params,$is_count);
		 
	}
	
	public function getOneByUsername($username){
		$sql = "select * from sys_user where username=:username";
		$data[':username'] = $username;
		return $this->adapter->conn()
					->preparedSql($sql, $data)
					->fetchOne();
	}

	
}