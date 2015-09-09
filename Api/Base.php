<?php 
namespace Api;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Module;
Abstract class Base  {	
	private $module;
	use ServiceLocatorAwareTrait;
	public function __construct(){
		
		if(is_null($this->module)){
			$this->module = Module::init(array());
			$this->module->run();
		}
		$this->setServiceLocator($this->module->getServiceManager());
	}
	
	public function getService($ServiceName){
		$adapter = $this->serviceLocator->get('Db');
		if($this->serviceLocator->has($ServiceName)){
			return $this->serviceLocator->get($ServiceName);
		}
		if(class_exists($ServiceName)){
			$service = new $ServiceName($adapter);
			$this->serviceLocator->setService($ServiceName,$service);
			return $service;
		}
		return false;
		
	}
}
?>
