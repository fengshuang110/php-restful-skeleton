<?php
namespace Zend\Db;


/**
 * 
 * @author fengshuang
 * 2015-6-1
 * UTF-8
 */

abstract class DbAbstract{
	protected $_writer;
	protected $_reader;
	protected $_isReader = true;
	
	/**
	 * 初始化数据库
	 *
	 * @throws Exception
	 */
	protected function _init($db_config) {
		
		if (empty ( $db_config ['writer'] )) {
			throw new \Exception ( 'Can not found the database writer config.' );
		}
		if (empty ( $db_config ['reader'] ) || ! is_array ( $db_config ['reader'] )) {
			throw new \Exception ( 'Can not found the database reader config.' );
		}
		$this->_writer = $db_config ['writer'];
		$readerIndex = rand ( 0, count ( $db_config ['reader'] ) - 1 );
		$this->_reader = $db_config ['reader'] [$readerIndex];
		
	}
	
	/**
	 * 连接
	 */
	abstract protected function _connect($isReader = true);
	
	/**
	 * 断开连接
	 */
	abstract protected function _close();
}