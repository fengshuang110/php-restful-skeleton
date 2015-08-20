<?php

class Lib_Ip
{
    public static function getClientIp()
    {
        $uip = '';
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown')) {
            $uip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            strpos($uip, ',') && list($uip) = explode(',', $uip);
        } else if(!empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown')) {
            $uip = $_SERVER['HTTP_CLIENT_IP'];
        } else if(!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $uip = $_SERVER['REMOTE_ADDR'];
        }
        return $uip;
    }

    public static function getUserIp() {
        $uip = '';
        if(isset($_SERVER['HTTP_X_BD_USERIP']) && $_SERVER['HTTP_X_BD_USERIP'] && strcasecmp($_SERVER['HTTP_X_BD_USERIP'], 'unknown')) {
            $uip = $_SERVER['HTTP_X_BD_USERIP'];
        } else {
            $uip = self::getClientIp();
        }
        return $uip;
    }

    public static function getFrontendIp() {
        if (isset($_SERVER['REMOTE_ADDR']))
            return $_SERVER['REMOTE_ADDR'];
        return '';
    }

    public static function getLocalIp() {
        if (isset($_SERVER['SERVER_ADDR']))
            return $_SERVER['SERVER_ADDR'];
        return '';
    }
}

?>
