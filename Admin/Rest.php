<?php 
namespace Admin;
  class Rest{
  	
  	protected  $request;
  	public function __construct(){
  		$this->request = $this->setRequest();
  	}
  	/**
  	 * @access private
  	 */
  	public function setRequest(){
  		$this->request = new Request();
  	}
  	/**
  	 * @access private
  	 */
	public function getRequest(){
		if(empty($this->request)){
			$this->setRequest();
		}
		return $this->request;
	}
	/**
	 * @access private
	 */
	public function redirect($url){
		header("location: ". $url);exit();
	}
	
} 

class Request{
	public $params;
	public function __construct(){
		$this->params = (Object)$_SERVER;
	}
	
	public function isPost(){
		if(strtolower($this->params->REQUEST_METHOD) == 'post'){
			return true;
		}
		return false;
 	}
 	
 	public function isXmlHttpRequest(){
 		if($this->params->HTTP_X_REQUESTED_WITH == 'XMLHttpRequest'){
 			return true;
 		}
 		return false;
 	}
 	
 	public function getPost($name="",$defalut=""){
		if(empty($name)){
			return $_POST;
		}
		if(!empty($_POST[$name])){
			return $_POST[$name];
		} 
		return $defalut;		
 	}
 	
 	public function getQuery($name,$defalut=""){
 		if(empty($name)){
 			return $_GET;
 		}
 		if(!empty($_GET[$name])){
 			return $_GET[$name];
 		}
 		return $defalut;	
 	}
 	
 	public function getFile($name){
 		if(empty($name)){
 			return $_FILES;
 		}
 		if(!empty($_FILES[$name])){
 			return $_FILES[$name];
 		}
 		return ;
 	}
	
}
?>