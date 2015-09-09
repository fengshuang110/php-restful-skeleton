<?php
/**
 * @author fengshuang
 * 2015/6/1
 */

// //正式环境微信的配置 
$config['weixin']=array(
 		'token'=>'hmdami',
 		'encodingaeskey'=>'',
 		'appid'=>'',
 		'appsecret'=>'',
 		'partnerid'=>'',
 		'partnerkey'=>'',
 		'paysignkey'=>''
		
);
//云通讯的配置
$config['yuntongxun']=array(
		'ServerIP'=>'',
		'ServerPort'=>'',
		'SoftVersion'=>'',
		'AccountSid'=>'',
		'AccountToken'=>'',
		'AppId'=>'',
);

return $config;