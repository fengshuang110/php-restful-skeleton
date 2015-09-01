<?php
namespace Application;
use Zend\Loader\AutoloaderFactory;

//模块的配置文件

class Module
{
	//获取配置文件信息
    public static function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

	//配置类自动注册机制
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



