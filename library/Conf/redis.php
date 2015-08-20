<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
//本地环境
$config['local'] = array(
		'host'=>'127.0.0.1',
		'port'=>'6379',
		'auth'=>'123456'
);
//测试环境
$config['test'] = array(
		'host'=>'127.0.0.1',
		'port'=>'6379',
		'auth'=>''
);

//线上环境
$config['rls'] = array(
		'host'=>'127.0.0.1',
		'port'=>'6379',
		'auth'=>''
);
return $config;