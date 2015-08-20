<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */

class Cache_Factory{
	private $cache_config;
	private $adapter;
	
	/**
	 * 获得Factory对象
	 * @param string $cache_adapter
	 * @throws Exception
	 */
	function __construct($cache_adapter='memcache'){
		$env = getenv('RUNTIME_ENVIROMENT') ? getenv('RUNTIME_ENVIROMENT') : (defined('SHELL_VARIABLE') ? SHELL_VARIABLE : '');
		$env = empty($env)?'local':$env;
		$config = require (LIBRARY_DIR.'/Conf/cache.php');
		if(!isset($config[$env][$cache_adapter])){
			throw new Exception('can not found the cache_config env:'.$env.' cache adapter:'.$cache_adapter);
		}
		
		$this->cache_config = $config[$env][$cache_adapter];
		$this->adapter = $cache_adapter;
	}
	
	/**
	 * 
	 * @return Ambigous <NULL, TyCache_Abstract,TyCache_Interface>
	 */
	function getCacheAdapter(){
		$adapter = null;
		switch ($this->adapter){
			case 'memcache':
				$adapter = new Cache_Adapter_Memcache($this->cache_config);
				break;
			case 'memcached':
				$adapter = new Cache_Adapter_Memcached($this->cache_config);
				break;
			case 'redis':
				$adapter = new Cache_Adapter_StringRedis($this->cache_config);
				break;
			default:
		}
		return $adapter;
	}
}