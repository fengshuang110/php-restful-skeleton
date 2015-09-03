<?php

 namespace Zend\Cache\Adapter;
use Zend\Cache\CacheAbstract;
use Zend\Cache\CacheInterface;
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
class MemcacheAdapter extends CacheAbstract implements CacheInterface {
	private $mem = null;
	
	function __construct($cache_config) {
		$this->mem = new \Memcache ();
		foreach ( $cache_config as $config ) {
			$this->mem->addserver ( $config ['host'], $config ['port'],false, $config ['weight'] );
		}
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TyCache_Interface::set()
	 */
	public function set($key, $value, $expire=600) {
		$obj = '';
		if (is_array ( $value )) {
			$obj = json_encode ( $value );
		} else if (is_string ( $obj )) {
			$obj = $value;
		} else {
			throw new Exception ( 'Value must be a string or array.' );
		}
		$result = $this->mem->replace ( $key, $obj, false, $expire );
		
		if (! $result) {
			$result = $this->mem->set ( $key, $obj, false, $expire );
			
		}
		
		return $result;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TyCache_Interface::get()
	 */
	public function get($key) {
		$result = $this->mem->get($key);
		$res = json_decode($result,true);
		if(empty($result)||empty($res)){
			return $result;
		}
		
		return $res;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TyCache_Interface::delete()
	 */
	public function delete($key){
		$result = $this->mem->delete($key);
		return $result;
	}
}