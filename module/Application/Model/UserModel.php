<?php
namespace Application\Model;
use Zend\Db\Dao;
class UserModel extends Dao{

	private static $tag = 'Model_User';
	protected $table = 'ecm_member';
	protected $conn = NULL;
	protected $primaryKey = 'user_id';
	public   $adapter = null;
	
	function __construct($adapter= null){
		$this->adapter = $adapter;
	}
	
	public function getOne($id){
		
		$sql = "select * from ecm_member where user_id=:user_id";
		$data[':user_id'] = $id;
		return $this->adapter->conn()
					->preparedSql($sql, $data)
					->fetchOne();
	}

	
}