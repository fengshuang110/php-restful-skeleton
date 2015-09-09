<?php
/**
 * @author fengshuang
 * 2015/6/1
 */

// //测试环境微信的配置 
$config['weixin']=array(
 		'token'=>'hmdami',
 		'encodingaeskey'=>'',
 		'appid'=>'',
 		'appsecret'=>'',
 		'partnerid'=>'',
 		'partnerkey'=>'',
 		'paysignkey'=>''
		
);
//测试环境云通讯的配置
$config['yuntongxun']=array(
		'ServerIP'=>'',
		'ServerPort'=>'',
		'SoftVersion'=>'',
		'AccountSid'=>'',
		'AccountToken'=>'',
		'AppId'=>'',
);

return $config;