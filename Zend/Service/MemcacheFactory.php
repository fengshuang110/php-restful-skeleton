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
use Zend\Cache\CacheAdapter;

/**
 * Cache
 */
class MemcacheFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $cacheConfig = isset($config['cache']['memcache']) ? $config['cache']['memcache'] : array();
		$cacheConfig = $cacheConfig->toArray();
        $cacheFactory = new CacheAdapter('memcache',$cacheConfig);
        $adapter =  $cacheFactory->getCacheAdapter();
        return $adapter;
    }
}
