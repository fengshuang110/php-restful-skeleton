<?php 

/**
 * @author fengshuang
 * 消息发送检查类
 */

class Lib_Spam{

	private static $mem;
	
	private function getIp(){
		return $_SERVER["REMOTE_ADDR"];
	}
	public static function getMem(){
		if(is_null(self::$mem)){
			$cache_factory = new Cache_Factory();
			self::$mem = $cache_factory->getCacheAdapter();
		}
		return self::$mem;
	}
	//用户注册的时候手机号短信发送次数设置
	public static function addRegister($type,$mobile){
		$mem = self::getMem();
		$key = '__spam_'.$type.'_time__'.$mobile;
		$currentNum = $mem->get($key);
		if(!$currentNum)
			$currentNum = 0;
		
		$currentNum++;
		//大于3次不更新
		if($currentNum<=3)
			$mem->set($key,$currentNum,86400);
	} 
	
	//用户注册的时候手机号短信发送次数设置
	public static function retrievePwd($type,$mobile){
		$mem = self::getMem();
		$key = '__spam_'.$type.'_time__'.$mobile;
		$currentNum = $mem->get($key);
		if(!$currentNum)
			$currentNum = 0;
		$currentNum++;
		//大于3次不更新
		if($currentNum<=3)
			$mem->set($key,$currentNum,86400);
	} 
	
	
	public static  function mobileMessage($mobile,$type){
	  $mem = self::getMem();
	  $key = '__spam_'.$type.'_time__'.$mobile;
	  $num =$mem->get($key);
	  if(empty($num)){
	  	return 0;
	  }
	  return $num;
	}
	
	//用户注册的时候手机号短信发送次数设置
	public static function addSpam($type,$mobile){
		$mem = self::getMem();
		$key = '__spam_'.$type.'_time__'.$mobile;
		$currentNum = intval($mem->get($key));
		$currentNum++;
		$mem->set($key,$currentNum,86400);
	}
	
	
	
	
	
	
	
	
	
}

?>