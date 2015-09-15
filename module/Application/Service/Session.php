<?php
namespace Application\Service;
use Application\Service;
/**
 * @author Administrator
 *
 */

class Session extends Service{
	private  $dbModel; //数据层实例
	private $cache;
	private $key;
	private $pre_key = "ACCESS_TOKEN:";
	protected $expire_time=864000;
	
	public function  distory($key){
       		$this->key = $this->pre_key.$key;
       		return	$this->serviceLocator->get('Redis')->delete($this->key);
     }
     public function read($key){
     	$this->key = $this->pre_key.$key;
     	return	$this->serviceLocator->get('Redis')->get($this->key);
     }
     
     public function write($key,$value){
     	$this->key = $this->pre_key.$key;
     	$this->serviceLocator->get('Redis')->set($this->key, $value);
     }
     
     
    
}
?>
