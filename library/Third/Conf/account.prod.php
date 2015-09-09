<?php
/**
 * @author fengshuang
 * 2015/6/1
 */

// //正式环境微信的配置 
$config['weixin']=array(
 		'token'=>'hmdami',
 		'encodingaeskey'=>'DFJYKMFNdEZBXyV1OQix6iisX8dmDPmb4G1T2gvI7kv',
 		'appid'=>'wx64794ef985549154',
 		'appsecret'=>'5dcdf5cdbc6e9e1ff23c39836df9e236',
 		'partnerid'=>'',
 		'partnerkey'=>'',
 		'paysignkey'=>''
		
);
//云通讯的配置
$config['yuntongxun']=array(
		'ServerIP'=>'sandboxapp.cloopen.com',
		'ServerPort'=>'8883',
		'SoftVersion'=>'2013-12-26',
		'AccountSid'=>'8a48b5514d32a2a8014d946b2e2346d0',
		'AccountToken'=>'a728f6a926554c508d99cb9784f6f5fc',
		'AppId'=>'aaf98f894d7439d8014d947cb65f17e8',
);

return $config;