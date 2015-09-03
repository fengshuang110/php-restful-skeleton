<?php
/**
 * 
 * @author fengshuang
 * 读取数据库pei'z
 */
class Conf_Database
{
    public static $databases = array(
        'ecmall' => array(
            'local' => array(
                'writer'=>array(
                    'host'=>'127.0.0.1',
                    'dbname'=>'ecmall',
                    'port'=>3306,
                    'user'=>'root',
                    'password'=>'',
                    'charset'=>'utf8'
                ),
                'reader'=>array(
                		0=>array(
                				'host'=>'127.0.0.1',
                				'dbname'=>'ecmall',
                				'port'=>3306,
                				'user'=>'root',
                				'password'=>'',
                				'charset'=>'utf8'
                		)
            ),
           )
        ),
    );

    public static function getConf($database)
    {
        $env = getenv('RUNTIME_ENVIROMENT') ? getenv('RUNTIME_ENVIROMENT') : (defined('SHELL_VARIABLE') ? SHELL_VARIABLE : '');
        $env = empty($env)?'local':$env;
        if (empty(self::$databases[$database][$env])) {
            throw new Exception('can not found the db_config env:'.$env.' db_tag:'.$db_tag);
        }
        return self::$databases[$database][$env];
    }

    public static function getConfForEloquent($database)
    {
        $conf              = self::getConf($database);
        $conf              = $conf['writer'];
        $conf['driver']    = 'mysql';
        $conf['database']  = $conf['dbname'];
        $conf['username']  = $conf['user'];
        $conf['collation'] = 'utf8_general_ci';
        $conf['prefix']    = '';
        return $conf;
    }

    public static function getConfForMedoo($database)
    {
        $conf                  = self::getConf($database);
        $conf                  = $conf['writer'];
        $conf['database_type'] = 'mysql';
        $conf['database_name'] = $conf['dbname'];
        $conf['server']        = $conf['host'];
        $conf['username']      = $conf['user'];
        $conf['password']      = $conf['password'];
        $conf['port']          = $conf['port'];
        $conf['charset']       = $conf['charset'];
        return $conf;
    }
}
