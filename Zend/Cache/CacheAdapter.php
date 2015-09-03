<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
 namespace Zend\Cache;
use Zend\Cache\Adapter\MemcacheAdapter;
use Zend\Cache\Adapter\StringRedisAdapter;

class CacheAdapter{
	private $cache_config;
	private $adapter;
	
	/**
	 * 获得Factory对象
	 * @param string $cache_adapter
	 * @throws Exception
	 */
	function __construct($name,$config){
		$env = getenv('RUNTIME_ENVIROMENT') ? getenv('RUNTIME_ENVIROMENT') : (defined('SHELL_VARIABLE') ? SHELL_VARIABLE : '');
		$env = empty($env)?'local':$env;
		if(!is_array($config)){
			throw new Exception('缓存配置错误');
		}
		$this->cache_config = $config[$env];
		$this->adapter = $name;
	}
	
	/**
	 * 
	 * @return Ambigous <NULL, TyCache_Abstract,TyCache_Interface>
	 */
	function getCacheAdapter(){
		$adapter = null;
		switch ($this->adapter){
			case 'memcache':
				$adapter = new MemcacheAdapter($this->cache_config);
				break;
			case 'redis':
				$adapter = new StringRedisAdapter($this->cache_config);
				break;
			default:
		}
		return $adapter;
	}
}