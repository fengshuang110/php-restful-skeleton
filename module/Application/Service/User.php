<?php
namespace Application\Service;
use Application\Service;
class User extends Service{

	public $url="http://www.baidu.com";
	private  $dbModel; //数据层实例
	public  function __construct() {
		$this->dbModel = new \Model_User();
	}
	
	//写原生sql和orm没有谁好谁坏，各有优点
	public function getOne($id){
// 		return $this->dbModel->find($id);//lavarel orm自带  优点是简单方便
	
		return $this->dbModel->getOne($id);//原生sql 一目了然 同时调试也比较方便
	}
}