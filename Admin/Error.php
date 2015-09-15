<?php 
namespace Admin;
class Error extends Base{
	
	/**
	 * @view error/404
	 * @return multitype:
	 */
	public function notfind(){
		return array();
	}
}
?>