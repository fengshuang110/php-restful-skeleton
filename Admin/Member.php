<?php 
namespace Admin;
use Luracast\Restler\Format\HtmlFormat;
use Admin\Rest;
use Application;
class  Member extends Base{
	protected $user;

	public function __construct(){
		parent::__construct();
		//加载全局视图数据
		$this->globalViewVars();
	} 
	
	public function getSessionUser(){
		if(!empty( $_SESSION['user'])){
			return $_SESSION['user'];
		}else{
			return false;
		}
	}
	
	
	protected function globalViewVars(){
		$parse_url = parse_url($_SERVER['REQUEST_URI']);
		if(empty($parse_url['query'])){
			$parse_url['query'] = "";
		}
		parse_str($parse_url['query'],$query);
		unset($query['pn']);
		
		HtmlFormat::$data['page_query'] = http_build_query($query);
		$this->user = $this->getSessionUser();
		if(empty($this->user)){
			session_unset();
			session_destroy();
			header("location: /login/login");
			exit();
		}
		HtmlFormat::$data['user'] = $this->user;
		
		
		$menus = $this->getService('Application\Service\Menu')->findAll();
		$father_menus = array();
		foreach ($menus as $menu){
			if($menu['parent_id'] == 0){
				$father_menus[$menu['menu_id']] = $menu;
			}
		}
	
		foreach ($menus as $menu){
			if(array_key_exists($menu['parent_id'], $father_menus)){
				$father_menus[$menu['parent_id']]['sub_menu'][] = $menu;
			}
		}
		
		HtmlFormat::$data['menus'] =$father_menus;
	}
}
?>