<?php 
class Pay_Adapter_Wechat extends Pay_Base_Abstract{
    protected $wechat;
    protected $unifiedOrder;
    protected $jsApi;
    public  $order = null;
    
   
    
    public function __construct($options=array()){
        $this->unifiedOrder = Pay_Wechat_Helper::getAdapter("UnifiedOrder_pub");
        $this->jsApi = Pay_Wechat_Helper::getAdapter("JsApi_pub");
    }
    //设置支付配置参数
    public function setProp($config){
    	 
    }
    
    
    //设置参数
    public function setPayRequestData($order_id,$openid){
        
        $this->order = $this->getOrder($order_id);
        $member = $this->getMember($this->order['buyer_id']);
        $this->unifiedOrder->setParameter("openid","$openid");//商品描述
        $this->unifiedOrder->setParameter("body",$this->order['seller_name']."店铺订单");//商品描述
        $this->unifiedOrder->setParameter("out_trade_no",$this->order['order_id']);//商户订单号
        $this->unifiedOrder->setParameter("total_fee",intval($this->order['order_amount']*100));//总金额
//      $this->unifiedOrder->setParameter("total_fee",1);//总金额
        $this->unifiedOrder->setParameter("notify_url",Pay_Wechat_Config::NOTIFY_URL);//通知地址
        $this->unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
//      非必填参数，商户可根据实际情况选填
//      $unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
//      $unifiedOrder->setParameter("device_info","XXXX");//设备号
//      $unifiedOrder->setParameter("attach","XXXX");//附加数据
//      $unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
//      $unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
//      $unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
//      $unifiedOrder->setParameter("openid","XXXX");//用户标识
//      $unifiedOrder->setParameter("product_id","XXXX");//商品ID
    }
    /**
     * jsapi 支付签名字符串返回
     * @return unknown
     */
    public function JsApi(){

        $prepay_id = $this->unifiedOrder->getPrepayId();
        $this->jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $this->jsApi->getParameters();
        return $jsApiParameters;
    }
    
    public function checkPaysign($obj){
        return  $this->jsApi->getSign($obj);
    }
    
    
}



?>