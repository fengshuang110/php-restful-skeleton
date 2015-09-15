<?php 
namespace Admin;
class Login extends Base{
	
	/**
	 * @view admin/login/login
	 * @return multitype:
	 */
	public function login(){
		if(!empty($_SESSION['user'])){
			header("location: /index/index");
		}
		return "";
	}
	
	/**
	 * @url GET /logout
	 */
	public function logout(){
		session_start();
		session_unset();
		session_destroy();
		header("location: /login/login");
		exit;
	}
	
	/**
	 * @url POST /check
	 */
	public function check(){
		$request = $this->getRequest();
		if($request->isXmlHttpRequest()){
			$username = $request->getPost("username");
			$password = $request->getPost("password");
			$vcode =  $request->getPost("vcode");
			if(strtolower($vcode) !=  strtolower($_SESSION['VCODE_TYPE_login'])){
				return array("code"=>1,"msg"=>"验证码错误");
			}
			
			$user = $this->getService('Application\Service\User')->getOneByUsername($username);
			
			if(!$user){
				return array("code"=>1,"msg"=>"用户名不存在");
			}
			if($user['password']!=$password){
				return array("code"=>1,"msg"=>"用户名密码错误");
			}
		//用户key 存入cookie
		setcookie("user_name",$user['username'],time()+86400,"/","m.bqmart.cn");
		unset($user['password']);
		$_SESSION['user'] = $user;
		return array(
				"code"=> 0 ,
				"msg" => "登录成功",
				"result"=>array(
						"userkey" 	   => md5($user['user_id']),
						"username"    => $user['username'],
				)
		);
		}else{
			echo json_encode(array("code"=>1,"msg"=>"请求错误"));die;
		}
	}
}
?>