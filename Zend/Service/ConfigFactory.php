<?php 
namespace Zend\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Config\Config;
class ConfigFactory implements FactoryInterface{
	public function createService(ServiceLocatorInterface $serviceLocator){
		$Config = new Config(require 'config/application.config.php');
		return $Config;		
	}
}

?>