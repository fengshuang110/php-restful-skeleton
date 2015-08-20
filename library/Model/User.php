<?php 

class  Model_User extends Model_BaseModel{
		private static $tag = 'Model_User';
	
		protected $table = 'ecm_member';
		protected $conn = NULL;
		protected $primaryKey = 'user_id';
		
		
		function __construct(){
			parent::_init('ecmall');
		}
		
		public function getOne($id){
			$sql = "select * from ecm_member where user_id=:user_id";
			$data[':user_id'] = $id;
			return $this->dao->conn()
							 ->preparedSql($sql, $data)
							 ->fetchOne();
		}
}


?>