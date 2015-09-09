<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Dao;
use Illuminate\Database\Capsule\Manager as Capsule;
/**
 * Cache
 */
class DbAdapterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	
    	
    	
    	$capsule = new Capsule;
    	//初始化数据库连接
    	
    	 
    	$config = $serviceLocator->get('config');
    	$dbconfig = $config['db'];
    	$conf = $dbconfig->toArray();
    	$capsule = new Capsule;
    	$conf              = $conf['writer'];
    	$conf['driver']    = 'mysql';
    	$conf['database']  = $conf['dbname'];
    	$conf['username']  = $conf['user'];
    	$conf['collation'] = 'utf8_general_ci';
    	$conf['prefix']    = '';
    	//初始化数据库连接
    	$capsule->addConnection($conf);
    	$capsule->bootEloquent();
        return  new Dao($serviceLocator);
    }
}
