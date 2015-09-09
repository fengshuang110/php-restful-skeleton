<?php 
namespace Api;
class Service extends Base{
   
	public  $url="www.baidu.com";

	
	public function test($id){
		$a = $this->getService("Application\Service\UserService");
		$b = $this->getService("Application\Service\UserService");
		var_dump($a === $b);
		$this->getService("Application\Service\UserService")->getOne($id);
		return $this->runtime();
	}
	
	public function test1(){
		$cache1 = $this->serviceLocator->get('Redis');
		$cache2 = $this->serviceLocator->get('Memcache');
		return $this->runtime();
		var_dump($cache1);
		var_dump($cache2);die;
	}
}
?>
