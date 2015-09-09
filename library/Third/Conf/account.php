<?php
/**
 * @author fengshuang
 * 2015/6/1
 */
if(!empty($_SERVER['ENV'])){
	return require 'account.'.$_SERVER['ENV'].'.php';
}else{
	return require 'account.test.php';
}

?>