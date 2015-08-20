<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
class Lib_Duanxin_Main{

    public function __construct(){
    	
    }
    
    /**
     * 发送短信信息的配置文件
     */
    public static  function getTypeConfig($code){
    	$types =  array(
    			'register'=>3,//一天单个手机号注册不能超过3次
    			'ios_register'=>3,//一天IOS单个手机号注册不能超过3次
    			'andriod_register'=>3,//一天Android单个手机号注册不能超过3次
    			'retrieve_pwd'=>3//一天单个手机号找回密码不能超过3次
    	);
    	if(in_array($code, array_keys($types))){
    		return $types[$code];
    	}
    		return 0;
    }
    /**
     * 验证手机号和type 验证次数是否可以
     * @param unknown $mobile
     * @param unknown $type
     */
    private static function spam($mobile,$type){
    	$factory= new Cache_Factory();
    	$mem = $factory->getCacheAdapter();
    	$mem_type_num = $mem->get('__spam_'.$type.'_time__'.$mobile);
    	var_dump(intval($mem_type_num));
    	$type_num = self::getTypeConfig($type);
    	if($mem_type_num<$type){
    		return true;
    	}
    	return false;
    }
    /**
     * 获得适配器
     * @param $config_tag 1.企信通  2云通讯
     * @return Lib_Duanxin_Adapter_Qxt|Lib_Duanxin_Adapter_Ytx
     */
    public static function getAdapter($config_tag=''){
        $config = Lib_Duanxin_Config::getConfig($config_tag);

        if(empty($config)){
            throw new Exception('短信通道不合法');
        }
        $tmpAdapter = new $config['class_name']();
        $tmpAdapter->setProp($config['config']);
        return $tmpAdapter;
    }

    /**
     * 发送短信验证码
     * @return array("code"=>0,'other'=>)
     */
    public static function sendCode($mobile, $type,$adapterTag=1,$datas=array(),$templateId=1)
    {
    	//发送短信之前先验证是否可以发送
    	$flag = self::spam($mobile, $type);
    	if($flag !== true){
    		return $flag;
    	}
//     	var_dump($flag);die;
        $adapter = self::getAdapter($adapterTag);
        if($adapter instanceof Lib_Duanxin_Adapter_Ytx){
        	
        	$res = $adapter->sendCode($mobile, $type,$datas,$templateId);
        	if($res->statusCode == '000000'){
        		$res->code = '0';
        		Lib_Spam::addRegister($type,$mobile);
        	}else{
        		$res->code = '9999';
        	}
        	return $res;
        }
        if($adapter instanceof Lib_Duanxin_Adapter_Qxt){
        	$res = $adapter->sendCode($mobile, $type);
        	return $res;
        }        
        
    }

    /**
     * 发送短信
     */
    private static function sendMsg($mobile, $uid, $content)
    {
        // 随机选择短信适配器
        $adapter = self::getAdapter(rand(1,2));
        return $adapter->sendMsg($mobile, $uid, $content);
    }

    /**
     * 校验短信验证码
     */
    public static function verifyCode($mobile,$type,$code)
    {
       	$mem = new  Cache_Adapter_Memcache();
        $memKey = '__MobileVerify_KEY_' . $mobile. $type;
        $exist_code = $mem->get($memKey);
        if($exist_code === false)
        {
            return null;
        }
        $exist_code = unserialize($exist_code);
        $exist_code = $exist_code['code'];
        if(!$exist_code || $exist_code != $code)
        {
            return false;
        }
        //验证通过后失效
        $mem->delete($memKey);
        return true;
    }
    
}