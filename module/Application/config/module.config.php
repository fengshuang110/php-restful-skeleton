<?php
return array(
	"service_manager"=>array(
			"factories"=>array(
					"Memcache"  =>"Zend\Service\MemcacheFactory",
					"Redis"  	=>"Zend\Service\RedisFactory",
					"Db"  	=>"Zend\Service\DbAdapterFactory"
			),
			'abstract_factories'=>array(
					
			)	
	),
	'db'=>array(
             
                'writer'=>array(
                    'host'=>'127.0.0.1',
                    'dbname'=>'ecmall',
                    'port'=>3306,
                    'user'=>'root',
                    'password'=>'',
                    'charset'=>'utf8'
                ),
                'reader'=>array(
                    "0"=>array(
                        'host'=>'rdsvelr6lvqrljlrwnzhv.mysql.rds.aliyuncs.com',
                        'dbname'=>'ecmall',
                        'port'=>3306,
                        'user'=>'bqmart',
                        'password'=>'beiquan8780',
                        'charset'=>'utf8'),
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
