<?php 
namespace Admin;
use Luracast\Restler\Format\HtmlFormat;
use Admin\Base;
use Application;

class User extends Member{
	
	/**
	 * 用户列表
	 * @view admin/user/lists
	 * @return multitype:
	 */
	public function lists(){
		$page = empty($_REQUEST['pn'])?"1":$_REQUEST['pn'];
		$limit = empty($_REQUEST['limit'])?10:intval($_REQUEST['limit']);
		$param['start'] = ($page-1)*$limit;
		$param['limit'] = $limit;
		$user_list = $this->getService("Application\Service\User")->getAll($param);
		$count =   $this->getService("Application\Service\User")->getAll($param,true);
		HtmlFormat::$data['page']=$page;
		HtmlFormat::$data['lists']=$user_list;
		HtmlFormat::$data['totalPage']=ceil($count/$limit);
		return array();
	}
	/**
	 * 添加角色
	 * @view admin/user/add
	 * @url GET /add
	 * @url POST /add
	 * @return multitype:
	 */
	public function add(){
		
		$request = $this->getRequest();
		if($request->isPost()){
			$user = $request->getPost("user");
			$this->getService("Application\Service\User")->save($user);
			$this->redirect("/user/lists");
			
		}
		$roles = $this->getService("Application\Service\Role")->getAll();
		HtmlFormat::$data["roles"]=$roles;
		return array();
	}

	/**
	 * 编辑用户
	 * @view admin/user/edit
	 * @url GET /edit
	 */
	public function edit($id){
		$user = $this->getService("Application\Service\User")->getOne($id);
		$roles = $this->getService("Application\Service\Role")->getAll();
		if(empty($user) || empty($roles)){
			$this->redirect("/user/lists");
		}
		HtmlFormat::$data["user"]=$user;
		HtmlFormat::$data["roles"]=$roles;
		return array();		
	}
	//删除角色
	public function del($id){
		//删除权限
		\Service_Backend_User::getInstance()->delUserByid($id);
		$this->redirect("/user/lists");		
		 
	}
}
?>