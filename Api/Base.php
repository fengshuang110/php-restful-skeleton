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
		
		if($this->serviceLocator->has($ServiceName)){
			return $this->serviceLocator->get($ServiceName);
		}
		if(class_exists($ServiceName)){
			$this->serviceLocator->setFactory($ServiceName,$ServiceName);
			$service = $this->serviceLocator->get($ServiceName);
			if(method_exists($service,'setServiceLocator')){
			
				$service->setServiceLocator($this->serviceLocator);
		
			}
			return $service;
		}
		return false;
		
	}
}
?>
