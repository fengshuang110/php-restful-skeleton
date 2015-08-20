<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
class Lib_Duanxin_Config {
	private static $duanxin_config = array (
			1 => array (
				'class_name' => 'Lib_Duanxin_Adapter_qxt',//企信通短信通道
				'config' => array (
					'url' => 'http://www.lcqxt.com/sms.aspx',
					'action' => 'send',
					'userid' => '',          //企业ID
					'account' => '',
					'password' => '',
					'code_expire' => 600, // 验证码失效时间
				),
			),
			2=>array(
					'class_name' => 'Lib_Duanxin_Adapter_ytx',//云通讯企业通道
					'config'=>array(
						'ServerIP'=>'sandboxapp.cloopen.com',
						'ServerPort'=>'8883',
						'SoftVersion'=>'2013-12-26',
						'AccountSid'=>'',
						'AccountToken'=>'',
						'AppId'=>'',
					)
				),
			//更多的通道W
	);
	
	/**
	 * 封闭构造
	 */
	private function __construct() {
	}
	
	/**
	 * 获得配置文件
	 *
	 * @param unknown $config_key        	
	 * @throws Yaf_Exception
	 */
	public static function getConfig($config_key) {
		if (! isset ( self::$duanxin_config [$config_key] )) {
			return null;
		}
		return self::$duanxin_config [$config_key];
	}
}