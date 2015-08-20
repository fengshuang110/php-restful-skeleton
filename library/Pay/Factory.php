<?php
/**
 * @name Pay_Factory
 * @desc 第三方支付渠道代理工厂类
 * @author lingsiyong
 */
class Pay_Factory{
	/**
	 * 获得适配器
	 * @param $config_tag 1.企信通  2云通讯
	 * @return Lib_Duanxin_Adapter_Qxt|Lib_Duanxin_Adapter_Ytx
	 */
	public static function getAdapter($config_tag=''){
		$config = Pay_Config::getConfig($config_tag);
	
		if(empty($config)){
			throw new Exception('支付配置不存在');
		}
		$tmpAdapter = new $config['class_name']();
		$tmpAdapter->setProp($config['config']);
		return $tmpAdapter;
	}

}

