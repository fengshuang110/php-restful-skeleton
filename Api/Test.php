<?php 
namespace Api;

use Luracast\Restler\Format\HtmlFormat;
class Test  extends Base{
	/**
	 * 默认是get请求
	 * @param string arg1
	 * @param int arg2
	 * @param string arg3
	 */
	public function  index($arg1,$arg2,$arg3){
		$data = array(
				12.4,
				"有间便利店",
				"2014-10-31 19:44:51",
				"软装经典 3份",
				"¥33.0",
				"您的订单我们已经收到，配货后将尽快配送~"
		);
		return $data;
	}
	
	/**
	 * 显示指明是POST请求
	 * @url POST /index1 
	 * @param unknown $user_id
	 */
	public function  index1($user_id){
		return array("code"=>"success");
	}
	
	
	/**
	 * 默认是php原生模板 支持和html 模板是lavarel blade模板  
	 * @view template/index
	 */
	public function  index2(){
		HtmlFormat::$data['data'] = array("name"=>"fengshuang","age"=>21,"qq"=>"945558163");
		return array();
	}
	
	public function info($id){
		
		$result = $this->serviceLocator->get('User')->getOne($id);
		return $result;
	}
	
	public function pay(){
		$payAdapter = \Pay_Factory::getAdapter(1);
		var_dump($payAdapter);die;
		return $payAdapter;
	}
	
	
	
	
	
	
}
?>
