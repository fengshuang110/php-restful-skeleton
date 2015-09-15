<?php

//验证码相关的所有操作
class Helper_Imgcode {
      private  $charset="abcdefghzkmnpqrstuvwxyABCDEFGHZKMNPQRSTUVWXY23456789"; //随机因子  
      private  $code;     //验证码文字
      private  $codelen=4;    //验证码显示几个文字
      private  $width=115;   //验证码宽度
      private  $height=35;   //验证码高度
      private  $img;       //验证码资源句柄
      private  $font;     //指定的字体
      private  $fontsize=25;  //指定的字体大小
      private  $fontcolor;     //字体颜色  随机
      private  $line_num=18; //图像中的干扰线
      private  $snow_num=20; //干扰雪花数
      //构造类  编写字体
      
      public  function __construct(){
         $this->font=dirname(dirname(__FILE__)).'/font/elephant.ttf';
      }
      
      //创建4个随机码
      private function createCode(){
         $_leng=strlen($this->charset)-1;
         for($i=1;$i<=$this->codelen;$i++){
            $this->code.=$this->charset[mt_rand(0,$_leng)];
         }
         return $this->code;
      }
      
     
      
      //创建背景
      private function createBg(){
         //创建画布 给一个资源jubing
         $this->img=imagecreatetruecolor($this->width,$this->height);
         //背景颜色
         //$color=imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
         $color=imagecolorallocate($this->img,221,221,221);
         //画出一个矩形
         imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
      }
    
      //创建字体
      private  function createFont(){
         $_x=(($this->width-30) / $this->codelen);   //字体长度
         for ($i=0;$i<strlen($this->code);$i++){
            //文字颜色
            $color=imagecolorallocate($this->img,mt_rand(0,80),mt_rand(0,80),mt_rand(0,80));
            //$color=imagecolorallocate($this->img,40,80,160);
            //资源句柄 字体大小 倾斜度 字体长度  字体高度  字体颜色  字体  具体文本
            imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(5,10),$this->height/1.4,$color,$this->font,$this->code[$i]);
			//imagettftext($this->img,$this->fontsize,0,$_x*$i+15,$this->height/1.4,$color,$this->font,$this->code[$i]);
         }
       }
       
      //随机线条
      private function createLine(){
         //随机线条
         for ($i=0;$i<$this->line_num;$i++){
            $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
            //imageline($this->img,mt_rand(0,10),mt_rand(0,10),mt_rand(0,10),mt_rand(0,10),$color);

         }
         
         //随机雪花
         for ($i=0;$i<$this->snow_num;$i++){
           $color = imagecolorallocate($this->img,mt_rand(220,255),mt_rand(220,255),mt_rand(220,255));
           imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
         }
      }
      
      //输出背景
      public  function outputImg(){
         //生成标头
         header('Content-Type:image/png');
         //输出图片
         imagepng($this->img);
         //销毁结果集
         imagedestroy($this->img);
      }
      
      //对外输出
      public  function createImg($type=""){
         //加载背景
         $this->createBg();
         switch ($type){
         	case 'number':
         		$this->createNum();
         		break;
         	default:
         		$this->createCode();
         		break;
         }
         //加载线条
         $this->createLine();
         //加载字体
         $this->createFont();
         //加载背景
         //$this->outPut();
      }
     
      //获取验证码
      public  function getCode(){
         return strtolower($this->code);
      }
      
      public function createNum(){
      	$num1 = rand(1,99);
      	$num2 = rand(1,99);
      	$this->code =$num1. $this->getRand() .$num2 ."=?";
      	$this->codelen = strlen($this->code);
    
      }
      
    function getRand(){
  		$code = rand(0,1);
 		switch ($code) {
    	case 0:
      		return "+";
      		break;
    	case 1:
     		return "-";
      		break;
    	default:
      		break;
  }
}
 
      
      
}

