<?php
namespace Application;
use Zend\Loader\AutoloaderFactory;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Config\Config;
use Zend\ServiceManager\ServiceManager;
//ģ��������ļ�

class Module
{
	public $conig;
	private static $application;
	protected   $serviceManager;
	
	public static function init($configuration){
		
		if(is_null(self::$application)){
			self::$application = new Module();
		}
		
		$smConfig = isset($configuration['service_manager']) ? $configuration['service_manager'] : array();
		$listeners = isset($configuration['listeners']) ? $configuration['listeners'] : array();
		$serviceManager = new ServiceManager();
		$serviceManager->setService('ApplicationConfig', $configuration);
		$serviceManager->setFactory('config', 'Zend\Service\ConfigFactory');
		self::$application->serviceManager = $serviceManager;
		return self::$application;
	} 
	public  function run(){
		$this->config = $this->getConfig();
		$config = new Config($this->config);
		$this->serviceManager->get('config')->merge($config);
		$this->AutoloaderConfig();
		$this->ServiceConfig();
	}
	//��ȡ�����ļ���Ϣ
    public static function getConfig(){
        return include __DIR__ . '/config/module.config.php';
    }

	//�������Զ�ע�����
    public static function AutoloaderConfig()
    {
    	
		AutoloaderFactory::factory(array(
				"Zend\Loader\StandardAutoloader"=>array(
				"namespaces"=>array(
						 __NAMESPACE__ => __DIR__ . '/',
                ),
			)
		));
    }
    
    public function ServiceConfig(){
    	
    	$config = $this->serviceManager->get('config');
    	$service_manager = $config['service_manager'];
    	$config_manager = $service_manager->toArray();
    	foreach ($config_manager as $key=>$classes){
    		switch ($key){
    			case 'factories':
    				foreach ($classes as $service_name=>$class){
    					$this->serviceManager->setFactory($service_name,$class);
    					$class = $this->serviceManager->get($service_name);
    					if(method_exists($class,'setServiceLocator')){
    						$class->setServiceLocator($this->serviceManager);
    					}
    				}
    				break;
    			case 'abstract_factories':
    				foreach ($classes as $class){
    					$this->serviceManager->addAbstractFactory($class);
    				}
    				break;
    			
    		}
    	}
    	
    }
    public function getServiceManager(){
    	 return $this->serviceManager;
    }
    
}




