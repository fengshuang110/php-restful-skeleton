<?php 

use Luracast\Restler\Restler;
use Luracast\Restler\Format\HtmlFormat;
use Luracast\Restler\Defaults;
use Luracast\Restler\Format\UploadFormat;

class Application {
	public $moduleRoutes;
	public static $config; 
	public static $r;
	public static   $instance;
	private function __construct(){
		
	}
	
	public  static function Init($config){

		if(is_null(self::$instance)){
			self::$instance  = new Application();
		}
		
		$moduleConfig = isset($config['modules']) ? $config['modules'] : array();
		self::$instance->document = isset($config['document']) ? $config['document'] : false;
		self::$instance->moduleRoutes = $moduleConfig;
		self::$instance->access = isset($config['access']) ? $config['access'] : false;
		self::$instance->format = isset($config['format']) ? $config['format'] : 'JsonFormat';
		self::$instance->viewPath = isset($config['viewPath']) ? $config['viewPath'] : '';
		HtmlFormat::$template = isset($config['template']) ? $config['template'] : 'php';
		return self::$instance;	    
	}
	
	public  function run(){
		
		if(is_null(self::$r)){
			self::$r = new Restler();
		}
		foreach ($this->moduleRoutes as $module){
			$moduleName = $module['name'];
			if(isset($module['is_api']) && $module['is_api'] == true){	
			
				$files=scandir($moduleName);
			
				foreach ($files as $file){
					$fileinfo = pathinfo($file);
					if(strtolower($fileinfo['extension']) == "php"){
						self::$r->addAPIClass($moduleName."\\".$fileinfo['filename']);
					}
				}
			}else{
				 include __DIR__ . '/module/'.$moduleName.'/module.php';
			}
		}
		if($this->document){
			self::$r->addAPIClass("Resources");
		}
		
		if($this->access){
			$this->cors();
		}
		
		if(!empty( $this->viewPath) && is_dir($this->viewPath)){
			HtmlFormat::$viewPath = $this->viewPath;
		}
		HtmlFormat::$template = "blade";
		Defaults::$crossOriginResourceSharing = true;//是否允许跨域
		UploadFormat::$allowedMimeTypes = array('image/jpeg', 'image/png', 'application/macbinary', 'application/octet-stream');
		self::$r->setSupportedFormats('JsonFormat', 'HtmlFormat', 'UploadFormat', 'XmlFormat');
		
		self::$r->handle();
	}
	
	public function cors() {
		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}
	
		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	
			exit(0);
		}
	}
}


?>