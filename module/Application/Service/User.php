<?php
namespace Application\Service;
use Application\Service;
class User extends Service {

	
	public $url="http://www.baidu.com";
	private  $userModel; //数据层实例
	
	public function getOne($id){
		return $this->getModel('User')->get($id);//lavarel orm自带  优点是简单方便
	}
	
	public function getOneByUsername($username){
		return $this->getModel('User')->getOneByUsername($username);
	}
	
	
	public function getAll($params,$iscount = false){
		return $this->getModel('User')->getAll($params,$iscount);
	}
	
	public function save($user){
		$user['password'] = \Helper_String::getRandomPwd();
		return $this->getModel('User')->save($user);
	}
	
	
	
	
	
}