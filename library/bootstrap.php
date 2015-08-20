<?php
/**
 * 注册Library
 * @author fengshuang
 * UTF-8
 */
// 

define("LIBRARY_DIR",dirname(__FILE__));
require_once LIBRARY_DIR.'/Loader/auto_loader.php';

use Illuminate\Database\Capsule\Manager as Capsule;


$capsule = new Capsule;
//初始化数据库连接
$capsule->addConnection(Conf_Database::getConfForEloquent("ecmall"));
$capsule->bootEloquent();


