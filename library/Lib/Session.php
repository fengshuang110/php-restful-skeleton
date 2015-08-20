<?php 

class Lib_Session{
	
	private $savePath;
	public function __construct(){
		 $cache = new Cache_Factory("redis");
		 $this->savePath = $cache->getCacheAdapter();
	}
	
	function read($id)
	{
		return $this->savePath->get($id);
	}
	
	function write($id, $data)
	{
		return $this->savePath->set($id,$data);
	}
	
	function destroy($id)
	{
		return $this->savePath->del($id);
		return true;
	}
	
} 
?>