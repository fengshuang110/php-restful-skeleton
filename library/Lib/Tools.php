<?php
/**
 * @author lingsiyong
 * @date 2015/07/22
 */

class Lib_Tools
{
    const IMAGE_URL_PREFIX = 'http://img.bqmart.cn';

    public static $_sortField = null;
    public static $_sortOrder = null;

    public static function completeImageUrl($url)
    {
        if (empty($url)) {
            return '';
        }
    	$offset =  stripos($url,"img.bqmart.cn");
    	if($offset){
    		$url = substr($url,$offset+13);
    	}

        $search = array('http://', 'https://');
        foreach ($search as $word) {
            $prefix = substr($url, 0, strlen($word));
            if ($prefix == $word) {
                return $url;
            }
        }
        if(substr($url, 0, 1) != '/') {
            $url = '/'.$url;
        }
        return self::IMAGE_URL_PREFIX.$url;
    }

    public static function completeImageUrlInArray($array, $fields)
    {
        array_walk($array, function(&$item, $key, $fields){
            foreach($fields as $field) {
                if(isset($item[$field])) {
                    $item[$field] = Lib_Tools::completeImageUrl($item[$field]);
                }
            }
        }, $fields);
        return $array;
    }

    public static function sortArrayByFieldWithInteger($array, $field, $sort = 'desc')
    {
        Lib_Tools::$_sortField = $field;
        Lib_Tools::$_sortOrder = $sort;
        usort($array, function($arr1, $arr2){
            $t1 = intval($arr1[Lib_Tools::$_sortField]);
            $t2 = intval($arr2[Lib_Tools::$_sortField]);
            if ($t1 > $t2) {
                return -1;
                return Lib_Tools::$_sortOrder == 'desc' ? -1 : 1;
            } elseif ($t1 < $t2) {
                return 1;
                return Lib_Tools::$_sortOrder == 'desc' ? 1 : -1;
            } else {
                return 0;
            }
        });
        return $array;
    }

    /**
     * 将时间转换为秒数
     * @param  string $time 如:08:30，08:30:21
     * @return int
     */
    public static function timeToSecs($time)
    {
        if (preg_grep('/\d+:\d+/', array($time))) {
            list($hour, $min) = explode(':', $time);
            $secs = $hour * 3600 + $min * 60;
            return $secs;
        }

        if (preg_grep('/\d+:\d+:\d+/', array($time))) {
            list($hour, $min, $sec) = explode(':', $time);
            $secs = $hour * 3600 + $min * 60 + $sec;
            return $secs;
        }

        return false;
    }

    public static function secToTime($secs, $showSecs = true)
    {
        $hours = floor($secs / 3600);
        $secs = $secs % 3600;
        $mins = floor($secs / 60);
        $secs = $secs % 60;
        $hours < 10 && $hours = '0'.$hours;
        $mins < 10 && $mins = '0'.$mins;
        $secs < 10 && $secs = '0'.$secs;
        return $showSecs ? $hours.':'.$mins.':'.$secs : $hours.':'.$mins;
    }

    public static function getWeekDayDesc($day, $returnString = true){
        $desc = array();
        $weekDays = \Conf_Config::$weekDays;
        foreach ($weekDays as $key => $text) {
            if (($day & $key) == $key) {
                $desc[] = $text;
            }
        }
        return $returnString ? implode(', ', $desc) : $desc;
    }

    public static function getWeekDaySerials($day){
        $serials = array();
        $weekDays = \Conf_Config::$weekDaysSerial;
        foreach ($weekDays as $key => $serial) {
            if (($day & $key) == $key) {
                $serials[] = $serial;
            }
        }
        return $serials;
    }

    /**
     * 根据URL地址判断是否跳NA本地页
     * @param  string $url
     * @return array
     */
    public static function judgeAppPage($url){
        $webviewToNativePages = \Conf_Config::$webviewToNativePages;
        foreach ($webviewToNativePages as $row) {
            if (preg_match($row['regex'], $url, $matches)) {
                $res['page_type'] = $row['page'];
                $params = array();
                foreach ($row['params'] as $key => $value) {
                    $params[$key] = $matches[$value];
                }
                $res['native_page_params'] = $params;
                return $res;
            }
        }

        $res = array(
            'page_type' => 'webview',
            'url' => $url,
        );
        return $res;
    }
}
