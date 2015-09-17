<?php
namespace Application\Service;
use Application\Service;
class Menu extends Service {

	public function findAll(){
		$menus =  $this->getModel('Menu')->findAll();//lavarel orm自带  优点是简单方便
		foreach ($menus as $key=>$menu){
			$menus[$key]['url'] = "/".$menu['controller']."/".$menu['action'];
		}
		return $menus;
	}
	
	public function getAll($params,$iscount = false){
		$menus = $this->getModel('Menu')->getAll($params,$iscount);
		return $menus;
	}
	
	public function save($menu){
		return $this->getModel('Menu')->save($menu);
	}
	
	public function get($id){
		return $this->getModel('Menu')->get($id);
	}
	
	public function del($id){
		$this->getModel('Menu')->del($id);
	}
	
	public function getParent(){
		$params['where'] = array(
				array("table"=>'sys_menu',
					  "field"=>"parent_id",
					   "op"=>"=",
					   "value"=>0),
		);
		return $this->getModel('Menu')->getAll($params);
	}
	
}