<?php
return  array(
	"modules"=>array(
		"Api" //接口命名空间
	),
	"document"=>true,
	'template'=>'php',//原生是php原生模板引擎php|blade
	"access"=>true,//是否支持跨域
	'format'=>array("JsonFormat"),//接口输出格式支持
	"viewPath"=>"./views",//如果输出的是html 那么模板的路径
);
