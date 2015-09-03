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
}
?>
