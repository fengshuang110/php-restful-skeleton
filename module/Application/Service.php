<?php
namespace Application;
//模块的配置文件
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class Service implements FactoryInterface{
	public function createService(ServiceLocatorInterface $serviceLocator){
			return $this;
	}
}





