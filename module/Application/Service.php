<?php
namespace Application;
//ģ��������ļ�
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class Service implements FactoryInterface{
	public function createService(ServiceLocatorInterface $serviceLocator){
			return $this;
	}
}





