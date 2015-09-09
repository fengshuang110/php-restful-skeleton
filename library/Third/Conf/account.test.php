<?php
/**
 * @author fengshuang
 * 2015/6/1
 */

//预上线微信环境配置
$config['weixin']=array(
		'token'=>'hmdami',
		'encodingaeskey'=>'DFJYKMFNdEZBXyV1OQix6iisX8dmDPmb4G1T2gvI7kv',
		'appid'=>'wxb57b25c24c4e7fe8',
		'appsecret'=>'67113c87615c87a3c5245766ce454dfd',
		'partnerid'=>'',
		'partnerkey'=>'',
		'paysignkey'=>''
);
//云通讯的配置
$config['yuntongxun']=array(
		'ServerIP'=>'app.cloopen.com',
		'ServerPort'=>'8883',
		'SoftVersion'=>'2013-12-26',
		'AccountSid'=>'8a48b5514d32a2a8014d946b2e2346d0',
		'AccountToken'=>'a728f6a926554c508d99cb9784f6f5fc',
		'AppId'=>'aaf98f894d7439d8014d947cb65f17e8',
);
 return $config;
?>