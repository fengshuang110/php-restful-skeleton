<?php 
namespace Admin;
use Luracast\Restler\Format\HtmlFormat;
use Admin\Base;

class Permission extends Member{
	
	/**
	 * @view admin/permission/lists
	 * @return multitype:
	 */
	public function lists(){
		$page = empty($_REQUEST['pn'])?"1":$_REQUEST['pn'];
		$limit = empty($_REQUEST['limit'])?100:intval($_REQUEST['limit']);
		$param['start'] = ($page-1)*$limit;
		$param['limit'] = $limit;
		$permissions_list = $this->getService("Application\Service\Menu")->getAll($param);
		$count =   $this->getService("Application\Service\Menu")->getAll($param,true);
		HtmlFormat::$data['page']=$page;
		HtmlFormat::$data['lists']=$permissions_list;
		HtmlFormat::$data['totalPage']=ceil($count/$limit);
		return array();
	}
	
	/**
	 * @url GET /add
	 * @url POST /add
	 * @return multitype:
	 */
	public function add(){
		$request = $this->getRequest();
		if($request->isPost()){
			$post = $request->getPost();
			$parent_menus = \Service_Backend_Permissions::getInstance()->addPermission($post);
			$this->redirect("/permission/lists");
		}
		$parent_menus = \Service_Backend_Permissions::getInstance()->getParentMenu();
		HtmlFormat::$data['parent_menus'] = $parent_menus;
		return array();
	}
	
	/**
	 * 编辑资源
	 * @url GET /edit
	 * @url POST /edit
	 * @return multitype:
	 */
	public function edit(){
		$request = $this->getRequest();
		if($request->isPost()){
			$post = $request->getPost();
			$where[] =array("field"=>"id","op"=>"=","value"=>$post['id']);
			$parent_menus = \Service_Backend_Permissions::getInstance()->updatePermission($where,$post);
			$this->redirect("/permission/lists");
		}
		$id = $request->getQuery("id",0);
		$permission = \Service_Backend_Permissions::getInstance()->getPermission($id);
		if(empty($permission)){
			$this->redirect("/permission/lists");
		}
		$parent_menus = \Service_Backend_Permissions::getInstance()->getParentMenu();
		HtmlFormat::$data['parent_menus'] = $parent_menus;
		HtmlFormat::$data['permission'] = $permission;
		return array();
	}
	
	/**
	 * 编辑资源
	 * @url GET /del
	 * @return multitype:
	 */
	public function del(){
		$request = $this->getRequest();
		$id = $request->getQuery("id",0);
		//删除权限
		\Service_Backend_Permissions::getInstance()->delPermission($id);
		$this->redirect("/permission/lists");
	}
	
}
?>