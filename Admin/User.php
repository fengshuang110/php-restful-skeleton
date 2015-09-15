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
			$this->getService("Application\Service\User")->register($user);
			$this->redirect("/user/lists");
		}
		$roles = $this->getService("Application\Service\Role")->getAll();
		HtmlFormat::$data["roles"]=$roles;
		return array();
	}

	/**
	 * 编辑用户
	 * @url GET /edit
	 * @url POST /edit
	 */
	public function edit(){
		$request = $this->getRequest();
		if($request->isPost()){
			$user = $request->getPost("user");
			$where[] = array("field"=>"id","op"=>"=","value"=>$user['id']);
			\Service_Backend_User::getInstance()->editUser($where,$user);
			$this->redirect("/user/lists");
		}
		$id = $request->getQuery("id",0);
		$user = \Service_Backend_User::getInstance()->getUserById($id);
		$roles = \Service_Backend_Permissions::getInstance()->getRolelist();
		 
		if(empty($user) || empty($roles)){
			$this->redirect("/user/lists");
		}
		HtmlFormat::$data["user"]=$user;
		HtmlFormat::$data["roles"]=$roles;
		return array();		
	}
	//删除角色
	public function del(){
		
		$request =$this->getRequest();
		$id = $request->getQuery("id",0);
		//删除权限
		\Service_Backend_User::getInstance()->delUserByid($id);
		$this->redirect("/user/lists");		
		 
	}
}
?>