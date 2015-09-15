<?php 
namespace Admin;
use Admin\Base;
 class Vcode extends Base{
	//只允许验证的类型
    private $vcodeType = array(
        'login',
        'reg',
        'forget',
        'modifyPassword',
    );

    /**
     * 创建图片二维码
     * @param string $type  
     */
    public function create($type) {
        $vcode = new \Helper_Imgcode();
        $vcode->createImg();
        if(in_array($type, $this->vcodeType)){
            $_SESSION['VCODE_TYPE_'.$type] = $vcode->getCode();
            $vcode->outputImg();
        }
        die;
    }
   
}
?>