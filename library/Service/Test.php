<?php 

class Service_Test {
	private  $dbModel; //数据层实例
	private   static  $instance;//业务层实例
	private function __construct() {
		$this->dbModel = new Model_User();
	}

	public static function  getInstance(){
		if(self::$instance == null){
			self::$instance = new Service_Test();
		}
		return self::$instance;
	}
//写原生sql和orm没有谁好谁坏，各有优点 	
	public function getOne($id){
// 		$this->dbModel->find($id);//lavarel orm自带  优点是简单方便

		return $this->dbModel->getOne($id);//原生sql 一目了然 同时调试也比较方便
	}
}

?>