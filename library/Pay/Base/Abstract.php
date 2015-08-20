<?php
/**
 * @author fengshuang
 * 2015-7-7
 * UTF-8
 */
abstract class Pay_Base_Abstract{
	/**
	 * 支付产品buy_goods：商品购买； account：账户
	 */
	protected $pay_product = "buy_goods";
	
	/**
	 * 获得订单
	 * @param unknown $order_id
	 * @throws Exception
	 * @return Ambigous <Ambigous, NULL>
	 */
	protected function getOrder($order_id){
		if (empty ( $order_id )) {
			throw new Exception ( 'order_id 不能为空' );
		}
		if ($this->pay_product == 'buy_goods') {
			$model = new Model_Beiquan_Order() ;
		} 
		$order = $model->getOrder($order_id);
		if (empty ( $order )) {
			throw new Exception ( '找不到该订单' );
		}
		
		return $order;
	}
	
	
	/**
	 * 获得成员实体
	 * @param unknown $uid
	 * @return Ambigous <Ambigous, NULL>
	 */
	protected function getMember($uid){
		
		if($this->pay_product == "buy_goods"){
			$model = new Model_Beiquan_Member();
			$member = $model->getUserById($uid);
			return $member;
		}
		return false;
		
	}
	
	
}
	
	