<?php
return array(
	"service_manager"=>array(
			"factories"=>array(
					"Memcache"  =>"Zend\Service\MemcacheFactory",
					"Redis"  	=>"Zend\Service\RedisFactory"
			),
			'abstract_factories'=>array(
					
			)	
	),
	"cache"=>array(
			
		'memcache' => array (
			"local"=>array(
						"0"=>array (
							'host' => '127.0.0.1',
							'port' => '11211' ,
							'weight' => '1'
							),
						),
			'rls' => array (
						"0"=>array (
							'host' => '127.0.0.1',
							'port' => '11211' ,
							'weight' => '1'
							),
						)					
  		),
			
		'redis' => array (
	
			"local"=>array(
						
						'host' => '127.0.0.1',
							
						'port' => '11211' ,
						
						'weight' => '1'
						
					
						),
	
			),
		)
);
