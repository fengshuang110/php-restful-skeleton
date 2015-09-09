<?php
//-----------------------------------------------------------------
/**
 * 装饰模式，目的是为了让云通讯这个第三可以做呼叫之外，还可以作为短信验证码来使用
 * 加入了缓存机制
 * @author    fengshuang
 */
class Third_Decorator_Yuntongxun {
	protected  static $obj=null;
	protected  $mem = null;
	private  $decorator = null;
	public static function getInstance(){
		if(is_null(self::$obj)){
			self::$obj = new Third_Decorator_Yuntongxun();
		}
		return self::$obj;
	}
	private function __construct(){
		$cache_factory = new Cache_Factory();
		$this->mem = $cache_factory->getCacheAdapter();
		$this->decorator = Third_Yuntongxun::getInstance();
	}
	/**
	 * 发送短信信息的配置文件
	 */
	public static  function getTypeConfig($code){
		$types =  array(
				'register'=>array('limit_time'=>5,"expire"=>600),//一天单个手机号注册不能超过3次
				'ios_register'=>array('limit_time'=>5,"expire"=>600),//一天IOS单个手机号注册不能超过3次
				'andriod_register'=>array('limit_time'=>5,"expire"=>600),//一天Android单个手机号注册不能超过3次
				'retrieve_pwd'=>array('limit_time'=>5,"expire"=>600),//一天单个手机号找回密码不能超过3次
				'bindopenid'=>array('limit_time'=>5,"expire"=>600),//一天绑定手机号超过5次
				'verifiycode'=>array('limit_time'=>10,"expire"=>600),//验证码登录
				
			);
		if(in_array($code, array_keys($types))){
			return $types[$code];
		}
		return 0;
	}
	
	
	
	//模板发送短信验证码
	public function  sendTemplateSMS($to,$type,$tempId){
		$message = self::checkSpam($to, $type);
		if(!$message['code']){
			return $message;
		}
		$code = $this->getRandomNum($to,$type);
		
		$datas[] = $code;
		$data = $this->decorator->sendTemplateSMS($to, $datas, $tempId);
// 		var_dump($data);
		if($data->statusCode=='000000'){
			self::addSpam($to, $type);
			$this->setCache($to, $type,$code);
			return array("code"=>true,"msg"=>"发送成功");
		}
		return array("code"=>false,"msg"=>"服务器商限制") ;
	}
	//语音短信验证码
	public function  voiceVerify($to,$type){
		$message = self::checkSpam($to, $type);
		if(!$message['code']){
			return $message;
		}
		$code = $this->getRandomNum($to,$type);
		$data = $this->decorator->voiceVerify($code, $to);
		if($data->statusCode=='000000'){
			self::addSpam($to, $type);
			$this->setCache($to,$type,$code);
			return array("code"=>true,"msg"=>"发送成功");
		}
		return array("code"=>false,"msg"=>"服务器商限制") ;
	}
	
	public function  sendSMS($to,$type,$content){
		$message = self::checkSpam($to, $type);
		if(!$message['code']){
			return $message;
		}
		$code = $this->getRandomNum($to,$type);
		$content = str_replace("%s", $code, $content);
		$data = $this->decorator->sendSMS($to, $content);
		if($data->statusCode=='000000'){
			self::addSpam($to, $type);
			$this->setCache($to,$type,$code);
			return array("code"=>true,"msg"=>"发送成功");
		}
		return array("code"=>false,"msg"=>"服务器商限制") ;
	}
	
	public function getCache($to,$type){
		$to_arr = explode(',', $to);
		$type_config = $this->getTypeConfig($type);
		if( $type_config && count($to_arr)==1){
			$key = '__spam_'.$type.'_time_'.$to_arr[0];
			return $this->mem->get($key);
		}
		return false;
	}
	public function deleteCache($to,$type){
		$to_arr = explode(',', $to);
		$type_config = $this->getTypeConfig($type);
		if( $type_config && count($to_arr)==1){
			$key = '__spam_'.$type.'_time_'.$to_arr[0];
			return $this->mem->delete($key);
		}
		return false;
	}
	
	//验证码缓存
	public function setCache($to,$type,$code){
		$to_arr = explode(',', $to);
		$type_config = $this->getTypeConfig($type);
		//如果是有发送配置项切只是单个用户的情况，那么才做缓存
		if( $type_config && count($to_arr)==1){
			$key = '__spam_'.$type.'_time_'.$to_arr[0];
			$value = $this->mem->get($key);
			$code=strtolower($code.",".$value);
			$this->mem->set($key, $code,$type_config['expire']);
		}
	}
	
	public function verifyCode($to,$type,$code){
		$to_arr = explode(',', $to);
		$type_config = $this->getTypeConfig($type);
		//如果是有发送配置项切只是单个用户的情况，那么才做缓存
		if( $type_config && count($to_arr)==1){
			$key = '__spam_'.$type.'_time_'.$to_arr[0];
			$temp_code = $this->mem->get($key);
			if(strtolower($code) == strtolower($temp_code)){
				$this->mem->delete($key);
				return true;
			}
			if(in_array(strtolower($code), explode(',', $temp_code))){
				$this->mem->delete($key);
				return true;
			}
			
		}
		return false;
	}
	
	public function checkSpam($mobile,$type){
	  $num = Lib_Spam::mobileMessage($mobile, $type);
	  $type_config = $this->getTypeConfig($type);
	  if(empty($type_config)){
	  	return array("code"=>false,"msg"=>"无效的参数");
	  }
	  if($num>=$type_config['limit_time']){
	  	return array("code"=>false,"msg"=>"每日发送次数超过限制");
	  }
	  return  array("code"=>true,"msg"=>"允许发送");
	}
	
	public function addSpam($mobile,$type){
		$type_config = $this->getTypeConfig($type);
		if(empty($type_config)){
			return array("code"=>false,"msg"=>"无效的参数");
		}
		Lib_Spam::addSpam($type, $mobile);
		return array("code"=>true,"msg"=>"发送次数+1");
	}
	
	
	public function getRandomNum($to,$type,$length = 4){
		$temp_code = $this->getCache($to, $type);
		if(!empty($temp_code)){
			$temp_code = explode(',', $temp_code);
			$code = end($temp_code);
			if(!empty($code)){
				return $code;
			}
		}
		
		$chars = "0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	
	
	
	
	
  
    
}
?>