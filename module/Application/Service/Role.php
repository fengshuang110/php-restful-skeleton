<?php
namespace Application\Service;
use Application\Service;
class Role extends Service {

	public function getAll($params=array(),$iscount = false){
		return $this->getModel('Role')->getAll($params,$iscount);
	}
	
	public function save($role){
		return $this->getModel('Role')->save($role);
	}
	 
	public function del($id){
		return $this->getModel('Role')->del($id);
	}
	
	public function get($id){
		return $this->getModel('Role')->get($id);
	}
}