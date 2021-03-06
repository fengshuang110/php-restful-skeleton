<?php
namespace Zend\Db;
use Illuminate\Database\Eloquent\Model;
/**
 * 数据库调用层 链式写法 
 * @author fengshuang
 *2015/6/1
 * UTF-8
 */
class Dao{
	private $db_adapter = null;
	protected $cache_adapter = null;
	public $sql_helper;
	protected $bind;
	private $cache = false;
	private $tag = '';
	private $key = '';
	private $is_set_key = false;
	private $expire = 300;
	private $cache_prefix = 'beiquan_';
	private $db_tag = '';
	public function __construct($serviceLocator) {
		$config = $serviceLocator->get('config');
		$dbConfig = $config['db'];
		$dbConfig = $dbConfig->toArray();
		$db_factory = new Factory($dbConfig);
		$this->db_adapter = $db_factory->getDBAdapter ();
		$this->cache_adapter = $serviceLocator->get('Memcache');
	}

    /**
     * 不使用cache
     * @return $this
     */
    public function noCache() {
		$this->cache = false;
		return $this;
	}
	
	/**
	 * 设定缓存过期时间
	 *
	 * @param unknown $sec        	
	 * @return TyqLib_Dao
	 */
	public function expire($sec) {
		$this->expire = $sec;
		return $this;
	}

    /**
     * 设置缓存tag
     * @param $cache_tag
     * @return $this
     */
    public function setTag($cache_tag) {
		$this->tag = $this->cache_prefix . $cache_tag;
		return $this;
	}

    /**
     * 设置单独查询key
     * 适合按照UID ID 等进行查询的key 不纳入tag缓存管理体系
     * @param $key_str
     * @return $this
     */
    public function setKey($key_str){
		$this->key = $key_str;
		$this->is_set_key = true;
		return $this;
	}
	
	/**
	 * 获得链接 默认为读库
	 *
	 * @param boolean $is_reader
	 *        	是否使用读库 默认true
	 * @return $this
	 */
	public function conn($is_reader = TRUE) {
		$this->db_adapter->getConn ( $is_reader );
		return $this;
	}
	
	/**
	 * 装载加载sql
	 *
	 * @param string $sql
	 *        	sql语句
	 * @param array $data
	 *        	预编译参数
	 * @return $this
	 */
	public function preparedSql($sql, $data) {
		// 设定当前执行语句缓存key
		$this->buildKey ( $sql, $data );
		$this->db_adapter->setData ( $sql, $data );
		return $this;
	}
	
	/**
	 * 获得受影响的行数
	 *
	 * @return number
	 */
	public function affectedCount() {
		$res = $this->db_adapter->affectedCount ();
		$this->afterChange(!empty($res));
		return $res;
	}
	
	/**
	 * 获得最后一条插入的id
	 *
	 * @return string
	 */
	public function lastInsertId() {
		$res = $this->db_adapter->lastInsertId ();
		$this->afterChange(!empty($res));
		return $res;
	}
	
	/**
	 * 获得一条记录
	 *
	 * @return mixed
	 */
	public function fetchOne() {
		$cache_res = $this->beginQuery ();
		if (! empty ( $cache_res )) {
			return $cache_res;
		}
		$res = $this->db_adapter->fetchOne ();
		$this->afterQuery ( $res );
		return $res;
	}
	
	/**
	 * 获得全部数据
	 *
	 * @return multitype:
	 */
	public function fetchAll() {
		$cache_res = $this->beginQuery ();
		
		if (! empty ( $cache_res )) {
			return $cache_res;
		}

		$res = $this->db_adapter->fetchAll ();
		$this->afterQuery ( $res );
		return $res;
	}
	
	/**
	 * 获得指定列号的数据
	 *
	 * @param unknown $column_num        	
	 * @return string
	 */
	public function fetchColumn($column_num) {
		$cache_res = $this->beginQuery ();
		if (! empty ( $cache_res )) {
			return $cache_res;
		}
		$res = $this->db_adapter->fetchColumn ( $column_num );
		$this->afterQuery ( $res );
		return $res;
	}
	
	/**
	 * 执行一条sql语句
	 *
	 * @return boolean
	 */
	public function excute() {
		$res = $this->db_adapter->excute ();
		return $res;
	}
	
	/**
	 * 开启事务
	 *
	 * @return $this
	 */
	public function beginTransaction() {
		$this->db_adapter->beginTransaction ();
		return $this;
	}
	
	/**
	 * 回滚事务
	 *
	 * @return mixed
	 */
	public function rollback() {
		$res = $this->db_adapter->rollback ();
		return $res;
	}
	
	/**
	 * 提交事务
	 *
	 * @return mixed
	 */
	public function commit() {
		$res = $this->db_adapter->commit ();
		return $res;
	}
	
	/**
	 * 构造key
	 *
	 * @param unknown $sql        	
	 * @param unknown $data        	
	 */
	private function buildKey($sql, $data) {
	
		if (! $this->cache) {
			return;
		}
		
		if ($this->cache && empty ( $this->tag )) {
			throw new \Exception ( 'The cache tag is null.' );
		}
		if($this->is_set_key){
			$this->key = $this->tag.$this->key;
			return;
		}
		
		
		// 取出Tag随机码
		$tag_val = $this->cache_adapter->get ( $this->tag );
		
		// 创建Tag随机码 或 重置tag存活时间
		$tag_val = $this->buildTagVal ( $tag_val );
		$key = $tag_val . md5 ( $sql . json_encode ( $data ) );
		$this->key = $key;
	}
	
	/**
	 * 设定Tag随机码
     * @param $tag_val
     * @return string
	 */
	private function buildTagVal($tag_val) {
		if (empty ( $tag_val )) {
			$tag_val = time () . rand ( 1, 99999 );
		}
		$this->cache_adapter->set ( $this->tag, $tag_val, $this->expire );
		return $tag_val;
	}
	
	/**
	 * 查询前
	 *
	 * @return multitype: Ambigous string>
	 */
	private function beginQuery() {
		if (! $this->cache) {
			return array ();
		}
		$res = $this->cache_adapter->get ( $this->key );
		//若存在缓存数据 则清除配置信息并返回缓存结果集
		if(!empty($res)){
			$this->_clearCacheSetting ();
		}
		return $res;
	}
	
	/**
	 * 查询后
	 *
	 * @param unknown $data        	
	 */
	private function afterQuery($data) {
		if (! $this->cache || empty ( $data )) {
			return;
		}
		
		if(empty($this->key)){
			return;
		}
	
		
		$this->cache_adapter->set ( $this->key, $data );
		//清除当前配置信息
		$this->_clearCacheSetting ();
	}
	
	/**
	 * 改变之后
	 */
	private function afterChange($clear_cache=false){
		if(empty($this->tag)){
			return;
		}
		if($clear_cache){
			$this->cache_adapter->delete($this->tag);
			//如果是自定义key 则删除自定义单独存贮的KEY
			if($this->is_set_key){
				$this->cache_adapter->delete($this->key);
			}
		}
	}
	
	
	/**
	 * 还原当前配置信息
	 */
	private function _clearCacheSetting() {
		$this->cache = true;
		$this->is_set_key = false;
		$this->tag = '';
		$this->key = '';
		$this->expire = 300;
	}
	
	
	/**
	 * @param unknown $params
	 * @param string $is_count
	 * @return unknown
	 * $params = array(
	 * 		"field"=>array(),
	 * 		"limit"=>10
	 * 		"start"=>0,
	 * 		orderby = array(array("field"=>"id","sort"=>"desc"))
	 * )
	 */
	public function select($params,$is_count = false){
		$this->bind = array();
		$params['field'] = empty($params['field']) ? array("*") :$params['field'];
		 if($is_count){
		 	$this->sql_helper
		 			->from($this->table)
		 			->count($this->table, $this->primaryKey, "count");
		 }else{
		 	$params['start'] =empty($params['start']) ? 0:$params['start'];
		 	$params['limit'] =empty($params['limit']) ? 15:$params['limit'];
		 	$this->sql_helper->from($this->table)
		 			->select($this->table,$params['field'])
		 			->limit($params['start'],$params['limit']);
		 	if(!empty($params['groupby'])){
		 		foreach ($params['groupby'] as $groupby){
		 			$this->sql_helper->groupBy($this->table, $groupby);
		 		}
		 	}
		 	if(!empty($params['orderby'])){
		 		foreach ($params['orderby'] as $orderby){
		 			$orderby["sort"] = empty($orderby["sort"]) ? "desc" : $orderby["sort"];
		 			$this->sql_helper->orderBy($this->table, $orderby['field'],$orderby["sort"]);
		 		}
		 	}
		 	if(!empty($this->foreign)){
		 		$this->sql_helper->leftJoin(
		 					$this->foreign['table'], 
		 					$this->foreign['table'], 
		 					$this->foreign['field'],
		 					$this->table.".".$this->foreignKey."=".$this->foreign['table'].".".$this->foreign['key']);
		 	}
		 	
		 	if(!empty($params['where'])){
		 		foreach($params['where'] as $where){
		 			$this->sql_helper->where($where['table'], $where['field'],$where['op'],$where['value']);
		 		}
		 	}
		 	
		 }
		 
		
		 $sql = $this->sql_helper->__toString();
		
		 
		 $adapter = $this->adapter->conn()->preparedSql($sql, $this->sql_helper->bind);
		 
		 if($is_count){
		 	$result = $adapter->fetchOne();
		 	return $result["{$this->table}_count"];
		 }
		 return $adapter->fetchAll();
 	}
 	
 	
 	public function save($data){
 		$this->bind = array(); 
 		$this->sql_helper = new Sql();
 		if(array_key_exists($this->primaryKey,$data)){
 			//更新方法
 			
 			$this->sql_helper->from($this->table)
 							 ->update($this->table, $data)
 							 ->where($this->table, $this->primaryKey, "=",$data[$this->primaryKey]);
 			$sql = $this->sql_helper->__toString();
 			return $this->adapter->conn()->preparedSql($sql, $this->sql_helper->bind)->affectedCount();
 		
 		}else{
 			//保存方法
 			$this->sql_helper->insert($this->table, $this->table, $role);
 			$sql = $this->sql_helper->__toString();
 			return $this->adapter->conn()->preparedSql($sql, $this->sql_helper->bind)->lastInsertId();
 		}
 	}
 	
 	public function del($id){
 		if(!empty($id)){
 			$this->sql_helper
 					->delete($this->table)
 			 		->where($this->table, $this->primaryKey, "=",$id);
 			$sql = $this->sql_helper->__toString();
 			return $this->adapter->conn()->preparedSql($sql, $this->sql_helper->bind)->lastInsertId();
 		}
 		return false;
 	}
 	
 	
 	public function get($id){
 		if(!empty($id)){
 			$this->sql_helper = new Sql();
 			$this->sql_helper
 			->from($this->table)
 			->select($this->table)
 			->where($this->table, $this->primaryKey, "=",$id);
 			$sql = $this->sql_helper->__toString();
 			return $this->adapter->conn()->preparedSql($sql, $this->sql_helper->bind)->fetchOne();
 		}
 		return false;
 	}
	
}