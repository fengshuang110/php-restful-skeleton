<?php
namespace Application;
use Zend\Loader\AutoloaderFactory;

//ģ��������ļ�

class Module
{
	//��ȡ�����ļ���Ϣ
    public static function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

	//�������Զ�ע�����
    public static function getAutoloaderConfig()
    {
		AutoloaderFactory::factory(array(
				"Zend\Loader\StandardAutoloader"=>array(
				"namespaces"=>array(
						 __NAMESPACE__ => __DIR__ . '/',
                ),
			)
		));
    }
}

Module::getAutoloaderConfig();



