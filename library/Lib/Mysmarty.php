<?php

class Lib_Mysmarty
{
	static $smarty = null;
	
	static function getInstance($config = null)
	{
		if(self::$smarty == null)
		{	
			$current_path = dirname(dirname(__FILE__));	
			require_once(SMARTY.'/libs/Smarty.class.php');
			self::$smarty = new Smarty(); 
			self::$smarty->template_dir= empty($config['template_dir']) ? './views/' : $config['template_dir'];
			self::$smarty->cache_dir = empty($config['cache_dir']) ? $current_path.'./cache' : $config['cache_dir'];
		}
		return self::$smarty;
	}
}

