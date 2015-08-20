<?php 

class Helper_String{
	const earthRadius = 6378138; //approximate radius of earth in meters
	/**
	 * 随机密码
	 * @param number $str
	 * @return string
	 */
	public static function  getRandomPwd($length=8){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for($i = 0; $i < $length; $i++){
			$str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		return $str;
	}
	
	public static function getCouponSn($length = 12){
		$chars = "0123456789";
		$str = "";
		for($i = 0; $i < $length; $i++){
			$str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		return $str;
	}
	
	/**
	 * //解密分享的字符串
	 * @param unknown $share_id
	 * return $uid 用户的id
	 */
	public  static  function de_share_id($share_id){
		$reg = '/^[0-9a-f]+$/';
		$reg_share_id = preg_match($reg,$share_id);
		if (!$reg_share_id) {
			return 0;
		}
		return hexdec($share_id);
	}
	/**
	 * //加密分享的字符串
	 * @param unknown $uid
	 * return $share_id 分享的字符串
	 */
	public static function en_share_id($uid){
		$uid = intval($uid);
		if ($uid <= 0) {
			return false;
		}
		return dechex($uid);
	}
	
	/**
	 * @desc 根据两点间的经纬度计算距离
	 * @param float $lat 纬度值
	 * @param float $lng 经度值
	 */
	public static function getDistance($lat1, $lng1, $lat2, $lng2){
		
		$lat1 = ($lat1 * pi() ) / 180;
		$lng1 = ($lng1 * pi() ) / 180;
		$lat2 = ($lat2 * pi() ) / 180;
		$lng2 = ($lng2 * pi() ) / 180;
	
	
		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;
		$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
		$stepTwo = 2 * asin(min(1, sqrt($stepOne)));
		$calculatedDistance = self::earthRadius * $stepTwo;
	
		return round($calculatedDistance);
	}
	
	//获取周围坐标
	public static function returnSquarePoint($lng, $lat,$distance = 1000){
		$dlng =  2 * asin(sin($distance / (2 *  self::earthRadius)) / cos(deg2rad($lat)));
		$dlng = rad2deg($dlng);
		$dlat = $distance/ self::earthRadius;
		$dlat = rad2deg($dlat);
		$arr = array(
				'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
				'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
				'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
				'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
		);
		return array("min-lat"=>$arr['left-bottom']['lat'],
					 "max-lat"=>$arr['right-top']['lat'],
					 "min-lng"=>$arr['right-bottom']['lng'],
					 "max-lng"=>$arr['left-top']['lng']
			);
	}
}
?>