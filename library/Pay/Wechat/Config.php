<?php 
if(empty($_SERVER['ENV'])){
	require 'Config.test.php';
}else{
	require 'Config.'.$_SERVER['ENV'].'.php';
}
?>