<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
//本地环境
$cache ['local'] = array (
		'memcache' => array (
				array (
						'host' => '127.0.0.1',
						'port' => '11211' ,
						'weight' => '1'
				) 
		) ,
		'memcached' => array (
				array (
						'host' => '127.0.0.1',
						'port' => '12306',
						'weight' => '1'
				)
		),
		'redis'=>array(
				'host' => '127.0.0.1',
				'port' => '6379',
		)
);

//测试环境
$cache ['test'] = array (
		'memcache' => array (
				array (
						'host' => '127.0.0.1',
						'port' => '11211',
						'weight' => '1'
				) 
		) ,
		'redis'=>array(
				'host' => '127.0.0.1',
				'port' => '6379',
		)
);

//线上环境
$cache ['rls'] = array (
		'memcache' => array (
				array (
						'host' => '127.0.0.1',
						'port' => '11211',
						'weight' => '1'
				),
		) ,
		'redis'=>array(
				'host' => '127.0.0.1',
				'port' => '6379',
		)
);

return $cache;