<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
class Lib_Duanxin_Adapter_Qxt extends Lib_Duanxin_Base_Abstract {

	/**
     * 发送验证码
     */
    public function sendCode($mobile, $type)
    {
        $code = self::getCode(6,true);
        
        $content = sprintf('提醒您：您本次获取的手机验证码为%s，请正确填写此验证码完成操作。【】', $code);
        //接口发送
        
        $get_data = 'action=' . $this->config['action'] 
        			. '&userid=' . $this->config['userid'] 
        			. '&account=' . $this->config['account']  
        			. '&password=' . $this->config['password']
        			. '&mobile=' . $mobile 
        			. '&content=' . urlencode($content) 
        			. '&sendTime='
        			. '&checkcontent=1';
        
        $resData = $this->curlGet($this->config['url'], $get_data);
        return $resData;

    }


}