<?php
namespace Application;

//ģ��������ļ�
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Symfony\Component\Finder\Expression\Expression;


class Service implements FactoryInterface{
	protected $serviceLocator;
	protected $dbadapter;
	protected $modles = array();
	public function createService(ServiceLocatorInterface $serviceLocator){
		$this->dbadapter = $serviceLocator->get('Db');
		return $this;
	}
	public function setServiceLocator($serviceLocator){
		$this->serviceLocator = $serviceLocator;
	}
	
	public function getModel($model){
		if(in_array($model,array_keys($this->modles))){
			return $this->modles[$model];
		}
		
		$model =  __NAMESPACE__."\\Model\\".$model;
		if (class_exists($model)) {
			$this->modles[$model] = new $model($this->dbadapter);
			return $this->modles[$model];
		}else{
			throw new \Exception($model." class isnot exists online ".__LINE__ );
		}
		
	}
	
}





