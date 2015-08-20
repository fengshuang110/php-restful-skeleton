<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
class Pay_Config {
	private static $pay_config = array (
			1 => array (
				'class_name' => 'Pay_Adapter_Wechat',//企信通短信通道
				'config' => array (
					'param1' => 'xxx',
					'param2' => 'xxx',
					'param3' => 'xx',          
					'param4' => 'xx',
					'param5' => 'xxx',
					'param6' => 600, 
				),
			)
			
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
		if (! isset ( self::$pay_config [$config_key] )) {
			return null;
		}
		return self::$pay_config [$config_key];
	}
}