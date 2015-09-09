<?php
/**
 * 注册Library
 * @author fengshuang
 * UTF-8
 */
// echo 1;die;

spl_autoload_register( 'loader');
function loader($class) {
	
//Smarty本身的对象不进行解析 new Smarty();  防止意外渲染smarty模板 如果系统有用到的话
	if(strpos($class,'Smarty')===false)
	{
		if (@class_exists($class, false)) {
			return;
		}
		$class_path = str_replace('_', '/', $class);
		$file = dirname(__FILE__). '/'.$class_path . '.php';
		echo $file;
		if(file_exists($file)){		
			require_once($file);
		}else{
			return ;
		}
		
	}
}