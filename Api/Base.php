<?php 
namespace Api;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Module;
Abstract class Base  {	
	private $module;
	protected $env;
	use ServiceLocatorAwareTrait;
	public function __construct(){
		$this->env['runtime']['start'] = microtime(true);
		$this->env['runtime']['mem'] = memory_get_usage();
		if(is_null($this->module)){
			$this->module = Module::init(array());
			$this->module->run();
		}
		$this->setServiceLocator($this->module->getServiceManager());
	}
	
	/**
	 * 获取资源消耗
	 * @access private
	 * @return array
	 */
	function runtime(){
		// 显示运行时间
		$return['time'] = number_format((microtime(true)-$this->env['runtime']['start']),4).'s';
		$startMem =  array_sum(explode(' ',$this->env['runtime']['mem']));
		$endMem   =  array_sum(explode(' ',memory_get_usage()));
		$return['memory'] = number_format(($endMem - $startMem)/1024).'kb';
		return $return;
	}
	
	/**
	 *@access private
	 */
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
