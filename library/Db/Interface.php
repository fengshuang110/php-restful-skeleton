<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */
interface Db_Interface{
	
	/**
	 * 获得链接
	 * @param unknown $isReader
	 */
	function getConn($isReader);
	
	/**
	 * 设置预加载sql
	 * @param unknown $sql
	 * @param unknown $data
	 */
	function setData($sql,$data);
	
	/**
	 * 执行
	 */
	function excute();
	
	/**
	 * 开启事物
	 */
	function beginTransaction();
	
	/**
	 * 关闭事物
	 */
	function commit();
	
	/**
	 * 回滚事务
	 */
	function rollback();
	
	/**
	 * 获得全部查询结果
	 * @param unknown $isObj
	 */
	function fetchAll();
	
	/**
	 * 获得最后一个插入的id
	 */
	function lastInsertId();
	
	/**
	 * 获得受影响的行数
	 */
	function affectedCount();
	
	/**
	 * 获取单行记录
	 */
	function fetchOne();
	
	/**
	 * 获取单列记录
	 */
	function fetchColumn($column_num);
}