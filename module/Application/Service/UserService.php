<?php
namespace Application\Service;
use Application\Model\UserModel;
class UserService {


	public $url="http://www.baidu.com";
	private  $dbModel; //数据层实例
	public  function __construct($adapter) {
		$this->userModel = new UserModel($adapter);
	}

	
	
	//写原生sql和orm没有谁好谁坏，各有优点
	public function getOne($id){
// 		return $this->userModel->find($id);//lavarel orm自带  优点是简单方便
		return $this->userModel->getOne($id);//原生sql 一目了然 同时调试也比较方便
	}
}