<?php
/**
 * 
 * @author fengshuang
 * 2015/6/1
 * UTF-8
 */

class Model_BaseModel extends Illuminate\Database\Eloquent\Model{
    /**
     * @param Lib_Dao
     */

	protected $table = 'ecm_member';
	protected $conn = NULL;
	
    protected $dao = null;
    protected $conds = array();//sql语句的组装参数
    protected $data = array();
	protected function _init($db_tag){
		$this->dao = new Lib_Dao($db_tag);
	}
	/**
	 * 组装sql语句的set方法
	 * @param unknown $conds
	 */
	public function setConds($conds){
		$this->conds = $conds;
	}
	
	/**
	 * 查询sql组装方法
	 * @param unknown $conds
	 * @param string $iscount
	 */
	public function selectSql($table,$conds = array(),$iscount=false){
		$this->data=array();
		//组装查询字段
		$fields = empty($conds['field'])?"*":$conds['field'];
		if($iscount ==true){
			$fields  = "count(*) as total ";
		}else{
			if(is_array($fields)){
				$index = 1;
				foreach($fields as $key=>$value){
					if(is_string($key) && !is_numeric($key))
						$value = $value.' as '.$key;
					if($index!=1){
						$field .= ','.$value;
					}else{
						$field .= $value;
					}
					$index++;
				}
				$fields = $field;
			}
		}
		$sql = "SELECT " . $fields . " FROM " . $table ." ";
		if(!empty($conds['join'])){
			$join ="";
			if(is_array($conds['join'])){
				foreach ($conds['join'] as $item){
					$join .=$item['type']." ".$item['table']." as ".$item['table']. " on ".$item['on'];
				}
				$sql .= $join;
			}
		}
		$sql .=" WHERE 1=1 ";
		
		if(!empty($conds['where'])){
			$where ="";
			if(is_array($conds['where'])){
				foreach ($conds['where'] as $item){
					$where .=" and ".$item['field']." ".$item['op']." :".$item['field'];
					$this->data[":".$item['field']] = $item['value'];
				}
				$sql .= $where;
			}
		}
		
		if(!empty($conds['order'])){
			$order ="";
			if(is_array($conds['order'])){
				foreach ($conds['order'] as $item){
					if(empty($item['sort'])){
						$item['sort'] = "asc";
					}else{
						$item['sort'] = "desc";
					}
					$order.=" order by `".$item['field']."` ".$item['sort'];
				}
				$sql .= $order;
			}
		}
		$sql .=" limit :start,:limit";
		if($iscount != true){
			$this->data[":start"] = empty($conds['start'])?0:intval($conds['start']);
			$this->data[":limit"] = empty($conds['limit'])?20:intval($conds['limit']);
		}else{
			$this->data[":start"] = 0;
			$this->data[":limit"] = 1;
		}
		return $sql;
		
	}
	
	public function delSql($table,$where){
		$this->data=array();
	}
	public function updateSql($table,$where,$data){
		
		$arrFields = array();
		$arrValues = array();
		$this->data=array();
		foreach ($data as $key => $value) {
			if(!empty($value)){
				$arrFields[] =  '`'.$key.'`= :'.$key;
				$this->data[':'.$key] = $value;
			}
		}
		$whereFields =  array();
		$wheresql=" where  1=1 ";
		foreach ($where as $key=>$value){
			$wheresql .=" and ".$value['field'].$value['op'].":where".$value['field'];
			$this->data[':where'.$value['field']] = $value['value'];
		}
		
		$sql = 'UPDATE `'.$table.'` SET ' . implode(',', $arrFields) . $wheresql;
		return $sql;
	}
	
	/**
	 * 单表插入记录sql组装
	 * @param unknown $user
	 */
	public function saveSql($table,$data){
		$arrFields = array();
		$arrValues = array();
		$this->data=array();
		foreach ($data as $key => $value) {
			if(!empty($value)){
				$arrFields[] = '`' . $key . '`';
				$arrValues[] = ':'.$key;
				$this->data[':'.$key] = $value;
			}
		}
		
		$sql = 'INSERT INTO `'.$table.'` (' . implode(',', $arrFields) . ') VALUES (' . implode(',', $arrValues) . ')';
		return $sql;
	}
	
}