<?php 
namespace Api;

class Service extends Base{
   
	public  $url="www.baidu.com";

	
	public function test(){
		
		//$serviceManager->setFactory("newservice","MVC\NewService");
		echo 	$this->serviceLocator->get('User')->url;die;
		
	}
	
	public function test1(){
		$cache1 = $this->serviceLocator->get('Redis');
		$cache2 = $this->serviceLocator->get('Memcache');
		var_dump($cache1);
		var_dump($cache2);die;
	}
}
?>
