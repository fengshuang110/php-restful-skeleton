<?php
namespace Application;

//ģ��������ļ�
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class Service implements FactoryInterface{
	protected $serviceLocator;
	protected $modles = array();
	public function createService(ServiceLocatorInterface $serviceLocator){
			return $this;
	}
	public function setServiceLocator($serviceLocator){
		$this->serviceLocator = $serviceLocator;
	}
	
	public function loadModel($model){
		if(in_array($model,array_keys($this->modles))){
			echo 1;
			return $this->modles[$model];
		}
		$adapter = $this->serviceLocator->get('Db');
		$model =  __NAMESPACE__."\\Model\\".$model;
		$this->modles[$model] = new $model($adapter);
		return $this->modles[$model];
	}
	
}





