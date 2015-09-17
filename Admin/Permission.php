<?php 
namespace Admin;
use Luracast\Restler\Format\HtmlFormat;
use Admin\Base;

class Permission extends Member{
	
	/**
	 * @view admin/permission/lists
	 * @return multitype:
	 */
	public function lists($pn=1,$limit=10){
		$param['start'] = ($pn-1)*$limit;
		$param['limit'] = $limit;
		$permissions_list = $this->getService("Application\Service\Menu")->getAll($param);
		$count =   $this->getService("Application\Service\Menu")->getAll($param,true);
		HtmlFormat::$data['page']=$pn;
		HtmlFormat::$data['lists']=$permissions_list;
		HtmlFormat::$data['totalPage']=ceil($count/$limit);
		return array();
	}
	
	/**
	 * @view admin/permission/add
	 * @url GET /add
	 * @url POST /add
	 * @return multitype:
	 */
	public function add(){
		$request = $this->getRequest();
		if($request->isPost()){
			$post = $request->getPost();
			$parent_menus =$this->getService("Application\Service\Menu")->save($post);
			$this->redirect("/permission/lists");
		}
		$parent_menus = $this->getService("Application\Service\Menu")->getParent();
		HtmlFormat::$data['parent_menus'] = $parent_menus;
		return array();
		
	}
	
	/**
	 * 编辑资源
	 * @view admin/permission/edit
	 * @url GET /edit
	 * @return multitype:
	 */
	public function edit($id=0){
		$menu = $this->getService("Application\Service\Menu")->get($id);
		if(empty($menu)){
			$this->redirect("/permission/lists");
		}
		$parent_menus =  $this->getService("Application\Service\Menu")->getParent();
		HtmlFormat::$data['parent_menus'] = $parent_menus;
		HtmlFormat::$data['menu'] = $menu;
		return array();
	}
	
	/**
	 * 编辑资源
	 * @url GET /del
	 * @return multitype:
	 */
	public function del($id = 0){
		//删除权限
		$this->getService("Application\Service\Menu")->del($id);
		$this->redirect("/permission/lists");
	}
	
}
?>