<?php

namespace Zend\Db;
// from() move to select2/insert2/delete2

/**
 * 构建查询语句
 * @author
 */
class Sql{
	const INF		= "18446744073709551615";
	const EQ 		= "=";
	const NE 		= "!=";
	const GT 		= ">";
	const LT 		= "<";
	const GEQ 		= ">=";
	const LEQ 		= "<=";
	const ISNULL	= "is null";
	const ISNOTNULL	= "is not null";
	const NOTIN 	= "not in";
	const NOT_LIKE   = "not like";
	const IN 		= "in";
	const BETWEEN 	= "between";
	const LIKE 		= "like";
	const BEFORE_LIKE 	= "like before";
	const END_LIKE 		= "like end";
	const DESC		= "desc";
	const ASC		= "asc";
	const FIND_IN_SET		= "find_in_set";
	const LIKE_IN_SET	= "like_in_set";
	/**
	 * like:array(array("alias"	=> $table_alias,"field"	=> $column,"op"	=> $op,"value"	=> $value))
	 * @var array
	 */
	private $where = array();
	private $andWhereGroup = array();
	private $orWhereGroup = array();
	private $where_tree = array();
	private $where_tree_andor = "AND"; //TODO: can be set
	
	public  $bind = array();
	/**
	 * like:array('alias1'=>'select column')
	 * 
	 * @var array
	 */
	private $select = array();
	private $distinct = null;
	private $count = array();
	private $sum = array();
	private $min = array();
	private $max = array();
	/**
	 * like:array('alias1'=>array('column'=>'select column'))
	 * 
	 * @var array
	 */
	private $update = array();
	
	/**
	 * like:array('alias1'=>array('column'=>'select column'))
	 * 
	 * @var array
	 */
	private $insert = array();
	
	/**
	 * Insert mult-row.
	 * Format: array("1,zhao","2,qian")
	 * 
	 * @var array
	 */
	private $insert2 = array();
	
	/**
	 * like:array('alias'=>'tablename')
	 * @var array
	 */
	private $from = array();
	private $limit_start;
	private $limit_end;
	private $orderBy = array();
	private $groupBy = array();
	private $action = "select";
	private $tables = array();
	/**
	 * 
	 * @var array $subquerys[]=array($alias, $field, $op, $subquery, $andor) 
	 * 		=> {$alias}.{$fk} op ({$subquery})
	 */
	private $subquerys = array();

	/**
	 * Add subquery
	 * 
	 * @param string $alias
	 * @param string $field
	 * @param string $op SqlHelper::**
	 * @param string $subquery select query as sub query
	 * 		=> {$alias}.{$fk} op ({$subquery})
	 */
	public function subquery($alias, $field, $op, $subquery, $andor = "AND")
	{
		$this->subquerys[] = array(
			"alias"	=> $alias,
			"field"	=> $field,
			"op"	=> $op,
			"value"	=> $subquery,
			"andor"	=> $andor,
		);
		return $this;
	}
	/**
	 * 构建where条件段，e.g. where('item','part_no',SqlHelper::LIKE,'%Good%')
	 * @param unknown_type $table
	 * @param unknown_type $column
	 * @param unknown_type $value
	 * @return SqlHelper
	 */
	public function where($table_alias,$column,$op,$value=null){
		$this->where[] = array(
			"alias"	=> $table_alias,
			"field"	=> $column,
			"op"	=> $op,
			"value"	=> $value,
			"andor"	=> "AND",
		);
		return $this;
	}
	/**
	 * 构建OR where条件段，e.g. where('item','part_no',SqlHelper::LIKE,'%Good%')
	 * @param unknown_type $table
	 * @param unknown_type $column
	 * @param unknown_type $value
	 * @return SqlHelper
	 */
	public function orWhere($table_alias,$column,$op,$value=null){
		$this->where[] = array(
			"alias"	=> $table_alias,
			"field"	=> $column,
			"op"	=> $op,
			"value"	=> $value,
			"andor"	=> "OR",
		);
		return $this;
	}
	/**
	 * eg.构建分组查询，如( ... AND ...)，分组由()包含，其中的条件都是and
	 * e.g andGroupWhere(array(new Where('o','status',SqlHelper::EQ,'completed'),new Where('o','order_time',SqlHelper::EQ,'2010-9-6')))
	 * @param array $where
	 * @return SqlHelper
	 */
	public function whereGroup(array $where){
		$this->andWhereGroup[] = $where;
		return $this;
	}
	/**
	 * eg.构建分组查询，如( ... and ...) OR (...)，分组由()包含，其中的条件都是and
	 * e.g andGroupWhere(array(new Where('o','status',SqlHelper::EQ,'completed'),new Where('o','order_time',SqlHelper::EQ,'2010-9-6')))
	 * @param array $where
	 * @return SqlHelper
	 */
	public function orWhereGroup(array $where){
		$this->orWhereGroup[] = $where;
		return $this;
	}
	public function whereTree($alias, array $wtree, $andor = "and")
	{
		$this->where_tree[] = array("alias"=>$alias, "node"=>$wtree, "andor"=>$andor);
		return $this;
	}
	/**
	 * 查询字段，e.g. select('item',array('part_no','quote_id'))->select('o',array('order_time'))
	 * @param string $alias 查询的表别名
	 * @param array $select 查询的字段
	 * @return SqlHelper
	 */
	public function select($alias,array $select=array("*")){
		$this->action = "select";
//		if($select){
//			$this->select[$alias] = $select;
//		}
		if($alias!="*"){
			$this->select[$alias] = $select;
		}
		return $this;
	}

	/**
	 * Select
	 * 
	 * @param string $table
	 * @param string $alias table alias
	 * @param mix $fields 
	 * 		string 'part_no', 
	 * 		or array of fields name example array('part_no','quote_id'), 
	 * 		or array of fields name&alias example array('pn_alias'=>'part_no','quote_id'), in which (alias=>field)
	 */
	public function select2($table, $alias=null, $fields="*"){
		$this->action = "select";
		if (empty($alias)) {
			$alias = $table;
		}
		if (! empty($fields)) {
			$this->select[$alias] = (array)$fields;
		}
		
		$this->from[($alias ? $alias : $table)] = array(
			'table'=>$table,
			'fields'=>(array)$fields,
			'join'=>array()
		);
		$this->tables[($alias ? $alias : $table)] = $table;

		return $this;
	}

	/**
	 *
	 * 查询时distinct那个字段.e.g distinct('item','qo_item_id')
	 * @param string $alias 表别名
	 * @param string $field 查询时要distinct的字段名
	 */
	public function distinct($alias,$field){
		$this->action = "select";
		$this->distinct = array('alias'=>$alias,'field'=>$field);
		return $this;
	}

	public function distinct2($table, $alias, $field, $f_alias=""){
		$this->action = "select";
		$this->distinct = array('alias'=>$alias, 'field'=>$field, 'f_alias'=>$f_alias);
		
		$this->from[($alias ? $alias : $table)] = array(
			'table'=>$table,
			'fields'=>array(),
			'join'=>array()
		);
		$this->tables[($alias ? $alias : $table)] = $table;
		
		return $this;
	}

	/**
	 * 查询count($alias.$field)，e.g. count('item','*')
	 * @param string $table_alias 查询的表别名
	 * @param string $field要count的字段
	 * @param string $count_alias要count的字段取值别名
	 * @return SqlHelper
	 */
	public function count($table_alias,$field,$count_alias){
		$this->action = "select";
		$this->count[$table_alias]['field'] = $field;
		$this->count[$table_alias]['alias'] = $count_alias;
		return $this;
	}
	/**
	 * 查询count($alias.$field)，e.g. sum('item','qo_item_id')
	 * @param string $table_alias 查询的表别名
	 * @param string $field 要count的字段
	 * @param string $count_alias 要sum的字段取值别名
	 * @return SqlHelper
	 */
	public function sum($table_alias,$field,$count_alias){
		$this->action = "select";
		$this->sum[$table_alias]['field'] = $field;
		$this->sum[$table_alias]['alias'] = $count_alias;
		return $this;
	}
	/**
	 * 查询max($alias.$field)，e.g. max('item','qo_item_id')
	 * @param string $table_alias 查询的表别名
	 * @param string $field 要max的字段
	 * @param string $count_alias 要max的字段取值别名
	 * @return SqlHelper
	 */
	public function max($table_alias,$field,$count_alias){
		$this->action = "select";
		$this->max[$table_alias]['field'] = $field;
		$this->max[$table_alias]['alias'] = $count_alias;
		return $this;
	}
	/**
	 * 查询min($alias.$field)，e.g. min('item','qo_item_id')
	 * @param string $table_alias 查询的表别名
	 * @param string $field 要min的字段
	 * @param string $count_alias 要min的字段取值别名
	 * @return SqlHelper
	 */
	public function min($table_alias,$field,$count_alias){
		$this->action = "select";
		$this->min[$table_alias]['field'] = $field;
		$this->min[$table_alias]['alias'] = $count_alias;
		return $this;
	}
	/**
	 * 构建删除sql,e.g. delete()
	 * @return SqlHelper
	 */
	public function delete($table){
		$this->action = "delete";
		$this->from($table);
		return $this;
	}
	
	public function delete2($table, $alias=null){
		$this->action = "delete";
		
		$this->from($table, $alias);
		
		return $this;
	}
	
	/**
	 * 构建更新sql,e.g. update('item',array('part_no'=>'value','quote_id'=>'34343'));
	 * @param array $datas 要更新的字段（键）与值
	 * @param string $alias 更新的表别名
	 * @return SqlHelper
	 */
	public function update($alias,array $datas){
		$this->action = "update";
		$this->update[$alias] = $datas;
		return $this;
	}

	/**
	 * 
	 * Enter description here ...
	 * @param string $table
	 * @param string $alias
	 * @param array $datas array('part_no'=>'value','quote_id'=>'34343')
	 */
	public function update2($table, $alias, array $datas){
		$this->action = "update";
		$this->update[$alias] = $datas;
	
		$this->from($table, $alias);
		
		return $this;
	}
	
	/**
	 * 构建插入sql,e.g. insert('item',array('part_no'=>'value','quote_id'=>'34343'));
	 * @param array $datas 要插入的字段（键）与值
	 * @param string $alias 插入的表别名
	 * @return SqlHelper
	 */
	public function insert($table,$alias,array $datas){
		$this->action = "insert";
		$this->insert[$alias] = $datas;
		$this->from($table, $alias);
		return $this;
	}
	
	/**
	 * Insert several records in batch
	 * 
	 * @param unknown_type $alias
	 * @param unknown_type $fields "id,name" or array("id","name")
	 * @param array $datas array(array($id1,$name1),array($id2,$name2),...)
	 */
	public function insert2($table,$alias, $fields, array $datas)
	{
		if (! is_array($fields))
		{
			$fields = explode($fields, ",");
		}
		
		$this->action = "insert2";
		$this->insert2[$alias]["fields"] = $fields;
		$this->insert2[$alias]["data"] = $datas;
		
		$this->from($table, $alias);
		
		
		return $this;
	}
	
	/**
	 * 构建查询的from段,e.g. from('LineItem','item')->from('Order','o')
	 * @param string $class_name 对象名
	 * @param string $alias 别名
	 * @return SqlHelper
	 */
	public function from($class_name,$alias=null){
		$this->from[($alias ? $alias : $class_name)] = array(
			'table'=>$class_name,
			'fields'=>array(),
			'join'=>array()
		);
		$this->tables[($alias ? $alias : $class_name)] = $class_name;
		return $this;
	}
	/**
	 * leftjoin查询段,e.g. leftJoin('item','item.order_id = o.order_id')
	 * @param $table 对象名
	 * @param $alias 别名
	 * @param mix $fields 
	 * 		string 'part_no', 
	 * 		or array of fields name example array('part_no','quote_id'), 
	 * 		or array of fields name&alias example array('pn_alias'=>'part_no','quote_id'), in which (alias=>field)
	 * @param $join_on
	 * @return SqlHelper
	 */
	public function leftJoin($table,$alias,$fields,$join_on){
		if (empty($alias)) {
			$alias = $table;
		}
		if (! empty($fields)) {
			$this->select[$alias] = (array)$fields;
		}
		
		$this->from[($alias ? $alias : $table)]= array(
			'table' => $table,
			'fields'=>(array)$fields,
			'join' =>array(
				'type'=>'left',
				'on'=>$join_on
			)
		);
		$this->tables[($alias ? $alias : $table)] = $table;
		
		return $this;
	}

	public function rightJoin($table,$alias,$fields,$join_on){
		if (empty($alias)) {
			$alias = $table;
		}
		if (! empty($fields)) {
			$this->select[$alias] = (array)$fields;
		}
		
		$this->from[($alias ? $alias : $table)]= array(
			'table' => $table,
			'fields'=>(array)$fields,
			'join' =>array(
				'type'=>'right',
				'on'=>$join_on
			)
		);
		$this->tables[($alias ? $alias : $table)] = $table;
		
		return $this;
	}
	
	/**
	 * inner join查询段,e.g. leftJoin('item','item.order_id = o.order_id')
	 * @param $table 对象名
	 * @param string$alias 别名
	 * @param string $join_on
	 * @return SqlHelper
	 */
	public function join($table,$alias,$fields,$join_on){
		if (empty($alias)) {
			$alias = $table;
		}
		if (! empty($fields)) {
			$this->select[$alias] = (array)$fields;
		}
		
		$this->from[($alias ? $alias : $table)]= array(
			'table' => $table,
			'fields'=>(array)$fields,
			'join'=>array(
				'type'=>'inner',
				'on'=>$join_on
			)
		);
		$this->tables[($alias ? $alias : $table)] = $table;
		return $this;
	}
	public function getAlias($table_name){
		foreach($this->from as $alias => $from_table){
			if($from_table['table']==$table_name){
				return $alias;
			}
		}
		return false;
	}
	/**
	 * sql 的limit限制，e.g. limit(0,10),limit(20)
	 * @param unknown_type $start
	 * @param unknown_type $end
	 * @return SqlHelper
	 */
	public function limit($start_or_count,$count=null){
		if ($start_or_count !== null && $count !== null) 
		{
			$this->limit_start = ':limit_start';
			$this->bind[':limit_start'] = $start_or_count;
			$this->limit_end = ':limit_end';
			$this->bind[':limit_end'] = $count;
		}
		else
		{
			$this->limit_end =':limit_end';
			$this->bind[':limit_end'] = $start_or_count ? $start_or_count : self::INF;
		}

		return $this;
	}
	
	/**
	 * 构建查询的orderby。e.g. orderby('item','order_id','desc')
	 * @param unknown_type $table_alias
	 * @param unknown_type $orderby
	 * @param unknown_type $sort
	 * @return SqlHelper
	 */
	public function orderBy($table_alias,$orderby,$sort = "desc"){
		$this->orderBy[] = array(
			'alias'		=> $table_alias,
			'orderby'	=> $orderby,
			'sort'		=> $sort,
		);
		return $this;
	}
	/**
	 * 构建查询的groupBy。e.g. groupBy('item','order_id')
	 * @param unknown_type $table_alias
	 * @param unknown_type $groupby
	 * @return SqlHelper
	 */
	public function groupBy($table_alias,$groupby){
		$this->groupBy[] = array(
			'alias'		=> $table_alias,
			'groupby'	=> $groupby
		);
		return $this;
	}
	public function clean(){
		$this->where	= array();
		$this->select 	= array();
		$this->from 	= array();
		$this->limit_end= null;
		$this->limit_start= null;
		$this->orderBy 	= array();

		$this->andWhereGroup = array();
		$this->orWhereGroup = array();
		$this->update = array();
		$this->insert = array();
		$this->insert2 = array();
		$this->groupBy = array();
		$this->distinct = null;
		$this->action = "select";
		$this->entities = array();
		$this->tables = array();
		$this->count = array();
		$this->max = array();
		$this->min = array();
		$this->sum = array();
		return $this;
	}
	/**
	 * 返回要查询的对象类名(包含join的对象)，键为查询的别名
	 * @return array
	 */
	public function getSelectClasses($just_select=false){
		if(empty($this->select) || !$just_select){
			return $this->tables;
		}
		foreach($this->select as $alias=>$other){
			$tables[$alias] = $this->tables[$alias];
		}
		return $tables;
	}
	public function __toString(){
		switch($this->action){
			case "select":
				$sql = $this->_select();
				break;
			case "delete":
				$sql = $this->_delete();
				break;
			case "update":
				$sql = $this->_update();
				break;
			case "insert":
				$sql = $this->_insert();
				break;
			case "insert2":
				$sql = $this->_insert2();
			default: return "";
		}
		
		return $sql;
	}
	
	/**
	 * 返回sql中的所有表名.e.g array(alias=>table)
	 * @return array
	 */
	public function getSelectTable(){
		foreach($this->from as $alias => $from_table){
			$from[$alias] = $from_table['table'];
		}
		return $from;
	}
	
	private function _whereGroup($groupWhere){
		$where = "";
		$not_first = false;

		#分组查询
		if($groupWhere){
			foreach($groupWhere as $wheres){
				foreach($wheres as $w){
					if($not_first){#第一个where前不需要and or
						$where .= " ".$w->getAndOr()." ".$this->_buildWhere($w->getWhereArray());
					}else{
						$where .= " ".$this->_buildWhere($w->getWhereArray());
						$not_first = true;
					}
				}
			}
		}
		return $where;
	}

	private function _whereTree($treeroot, & $where)
	{
		if (empty($treeroot)) return;

		$where = "";
		foreach ($treeroot as $subroot)
		{
			$alias = $subroot["alias"];
			$andor = $subroot["andor"];
			$subnode = $subroot["node"];
			$subwhere = "";
			$this->_whereTreeRecursor($subnode, $alias, $subwhere, $andor, $level);
			if (!empty($subwhere))
			{
				// Need no bracket if only one subnode or lowest level.
				if ($level > 1 && count($treeroot) > 1)
				{
					$where .= ($where == "" ? "" : " {$andor} ") . "({$subwhere})";
				}
				else 
				{
					$where .= ($where == "" ? "" : " {$andor} ") . "{$subwhere}";
				}
			}
		}
		return $andor;
	}
	
	private function _whereTreeRecursor($treenode, $alias, & $where, $andor = "AND", & $level)
	{
		if (empty($treenode)) return;

		if (array_key_exists("andor", $treenode))
		{
			$andor_sub = $treenode["andor"];
			unset($treenode["andor"]);
			foreach ($treenode as $node)
			{
				$subwhere = "";
				$this->_whereTreeRecursor($node, $alias, $subwhere, $andor_sub, $level);
				if (!empty($subwhere))
				{
					// Need no bracket if only one subnode or lowest level.
					if ($level > 1 && count($treenode) > 1)
					{
						$where .= ($where == "" ? "" : " {$andor_sub} ") . "({$subwhere})";
					}
					else
					{
						$where .= ($where == "" ? "" : " {$andor_sub} ") . "{$subwhere}";
					}
				}
			}
		}
		else
		{
			$treenode["alias"] = $alias;
			$where .= (($where == "") ? "" : " {$andor} " ) . $this->_buildWhere($treenode);
			$level = 0;
		}

		$level++;
		return $andor;
	}
	
	private function _where(){
		$where = "";
		$not_first = false;
		
		foreach((array)$this->where as $wheres){
			if($not_first){#第一个where前不需要and or
				$where .= " ".$wheres['andor']." ".$this->_buildWhere($wheres);
			}else{
				$where .= $this->_buildWhere($wheres);
				$not_first = true;
			}
		}
		$groupWhere = $this->_whereGroup($this->andWhereGroup);
		if($groupWhere){
			$where = ($where ? $where." AND " : "")."(".$groupWhere.")";
		}
		$orGroupWhere = $this->_whereGroup($this->orWhereGroup);
		if($orGroupWhere){
			$where = ($where ? $where." OR " : "")."(".$orGroupWhere.")";
		}
		//$treeWhere = "";
		$treeAndor = $this->_whereTree($this->where_tree, $treeWhere);
		if (!empty($treeWhere)) {
			$where = ($where ? $where." {$treeAndor} " : "")."(".$treeWhere.")";
		}
		return $where;
	}
	
	private function _from(){
		$no_alias = $this->_isinsert() || $this->_isdelete();//不要别名
		foreach($this->from as $alias => $from_table){
			if($from_table['join']){
				switch(strtoupper($from_table['join']['type'])){
					case 'LEFT':
						$from[] = " LEFT JOIN ".(
							$no_alias ? 
							$from_table['table'] : 
							$from_table['table']." AS ".$alias)
							." ON ".$from_table['join']['on'];
						break;
					case 'INNER':
						$from[] = " INNER JOIN ".(
							$no_alias ? 
							$from_table['table'] : 
							$from_table['table']." AS ".$alias)
							." ON ".$from_table['join']['on'];
						break;
					case 'RIGHT':
						$from[] = " RIGHT JOIN ".(
							$no_alias ? 
							$from_table['table'] : 
							$from_table['table']." AS ".$alias)
							." ON ".$from_table['join']['on'];
						break;
					//TODO
					default:
				}
			}else{
				$from[] = $no_alias ? $from_table['table'] : $from_table['table']." AS ".$alias;
			}
		}
		return join(" ",$from);
	}
	
	private function _select(){
		#处理distinct查询字段
		if($this->distinct){
			$alias = $this->distinct['alias'];
			$column = $this->distinct['field'];
			if (array_key_exists('f_alias', $this->distinct)) 
			{
				$falias = empty($this->distinct['f_alias']) ? $column : $this->distinct['f_alias'];
			}
			else 
			{
				$falias = "{$alias}_{$column}";
			}
			
			$select[] = "distinct {$alias}.{$column} AS $falias";
		}
		
		if($this->select){#指定了要查询什么
			foreach($this->select as $alias => $columns){
				foreach((array)$columns as $col_alias=>$column){
					
					if (is_numeric($col_alias))
					{
						$select[] = "{$alias}.{$column}";
					}
					else
					{
						$select[] = "{$alias}.{$column} AS {$col_alias}";
					}
				}
			}
		}else if(
			!$this->count &&
			!$this->max &&
			!$this->min &&
			!$this->sum &&
			!$this->distinct
		){#没有指定查询，并且没有聚合函数则查询所有
			foreach($this->tables as $alias => $cls){
				/*$entities = new $cls;
				foreach($entities->getColumns() as $column => $define){
					$select[] = "{$alias}.{$column} AS {$alias}_{$column}";
				}
				unset($entities);*/
				$select[] = "{$alias}.*";
			}
		}
		#处理count，sum，max，min这些函数查询
		foreach($this->count as $alias => $count){
			$select[] = $count['field']=="*" 
				? "count(*) AS {$alias}_".$count['alias'] 
				: "count(DISTINCT {$alias}.".$count['field'].") AS {$alias}_".$count['alias'];
		}
		foreach($this->max as $alias => $max){
			$select[] = "max({$alias}.".$max['field'].") AS {$alias}_".$max['alias'];
		}
		foreach($this->min as $alias => $min){
			$select[] = "min({$alias}.".$min['field'].") AS {$alias}_".$min['alias'];
		}
		foreach($this->sum as $alias => $sum){
			$select[] = "sum({$alias}.".$sum['field'].") AS {$alias}_".$sum['alias'];
		}
		
		$where = $this->_where();

		$subquery_str = "";
		if ($this->subquerys)
		{
			foreach ($this->subquerys as $subquery)
			{
				$subquery_andor = $subquery['andor'];
				$subquery_str =  "{$subquery["alias"]}.{$subquery["field"]}" . 
				" {$subquery["op"]} ({$subquery["value"]})";
				
				$where .= empty($where) ? $subquery_str : 
				(empty($subquery_str) ? "" : " \r\n{$subquery_andor} $subquery_str");
			}
		}

		return "SELECT "
				.($select ? join(",",$select) : "*")." \r\nFROM ".$this->_from()
				.($where  ? " \r\nWHERE ".$where : "")
				.$this->_groupBy()
				.$this->_orderBy()
				.$this->_limit();
	}
	private function _delete(){
		$where = $this->_where();
		return "DELETE FROM ".$this->_from()
				.($where  ? " \r\nWHERE ".$where : "");
	}
	private function _insert(){
		$this->bind = array();
		foreach($this->insert as $alias => $insertDatas){
			foreach((array)$insertDatas as $field => $value){
				$filtered = array(
					"table"=>$this->from[$alias]['table'],
					"field"=>$field,
					"value"=>$value);
				$insert_column[] = "`".$filtered['field']."`";
				
				$insert_value[] = ":".$filtered['field'];
				
				$this->bind[":".$filtered['field']] = $this->_quoteValue($filtered['value']);
			}
		}
		$where = $this->_where();
		return "INSERT INTO ".$this->_from()
				." (".join(",",$insert_column).") \r\nVALUES("
				.join(",",$insert_value).")"
				.($where  ? " \r\nWHERE ".$where : "");
	}
	
	private function _insert2(){
		foreach($this->insert2 as $alias => $insertDatas){
			$fields = $insertDatas["fields"];
			foreach ((array)$insertDatas["data"] as $row)
			{
				$insert_value_str = "";
				foreach((array)$row as $value)
				{
					$insert_value_str .= ($insert_value_str == "" ? "" : ",") 
										. $this->_quoteValue($value);
					
				}
				$insert[] = "(". $insert_value_str .")";
			}

			/*
			$fields = $insertDatas["fields"];
			$data = $insertDatas["data"];
			$table = $alias;
			foreach((array)$data as $value){
				$filtered = Plugin::getPlugin()->doFilter("set_value_to_db",array(
					"table"=>$this->from[$alias]['table'],
					"field"=>$field,
					"value"=>$value
				));
				$insert_value_str .= ($insert_value_str == "" ? "" : ",") . "({$value})";
			}*/
		}
		$where = $this->_where();
		return "INSERT INTO ". $this->_from()
				." (".join(",",$fields).") \r\nVALUES "
				. join(",",$insert)
				.($where  ? " \r\nWHERE ".$where : "");
	}

	private function _update(){
		foreach($this->update as $alias => $updateDatas){
			foreach((array)$updateDatas as $field => $value){
				$filtered = array(
					"table"=>$this->from[$alias]['table'],
					"field"=>$field,
					"value"=>$value);
				
				$update[] = $alias.".".$field."=:".$field ;
				$this->bind[":".$field] = $this->_quoteValue($filtered['value']);
			}
		}
		$where = $this->_where();
		return "UPDATE ".$this->_from()." \r\nSET "
				.join(",",$update)
				.($where  ? " \r\nWHERE ".$where : "");
	}

	private function _groupby(){
		$by = null;
		foreach ($this->groupBy as $groupby){
			$by[] = $groupby['alias'].".".$groupby['groupby'];
		}
		return $by ? " GROUP BY ".join(',',$by) : "";
	}
	private function _orderby(){
		$by = null;
		foreach ($this->orderBy as $orderby){
			if(empty($orderby['alias'])){
				$by[] = $orderby['orderby']." ".strtoupper($orderby['sort']);
			}else {
				$by[] = $orderby['alias'].".".$orderby['orderby']." ".strtoupper($orderby['sort']);
			}
		}
		return $by ? " ORDER BY ".join(',',$by) : "";
	}
	private function _limit(){
		if($this->limit_start && $this->limit_end){
			return " LIMIT ".$this->limit_start." , ".$this->limit_end;
		}elseif($this->limit_start){
			return " LIMIT ".$this->limit_start . "," . self::INF;
		}if($this->limit_end){
			return " LIMIT 0 , ".$this->limit_end;
		}else{
			return "";
		}
	}
	
	private function _isinsert(){
		$flag = (strcasecmp($this->action,"insert") == 0 
		|| strcasecmp($this->action,"insert2") == 0);
		
		return $flag;
	}
	private function _isdelete(){
		return strcasecmp($this->action,"delete")==0;
	}
	private function _quoteValue($value){
		if(is_array($value)){
			foreach($value as $index => $v){
				$retval[$index] = $this->_quoteValue($v);
			}
			return $retval;
		}else{
			if (is_numeric($value))
			{
				$retval = $value;
			}
			elseif ($value === null
			|| empty($value)
			|| strcasecmp($value, "null") == 0)
			{
				$retval = "null";
			}
			elseif (strcasecmp($value, "\'null\'") == 0
			|| strcasecmp($value, "'null'") == 0)
			{
				$retval = "'null'";
			}
			else
			{
				$retval = addslashes($value) ;
			}
	
			return $retval;
		}
	}

	public function buildWhere($wheres){
		return $this->_buildWhere($wheres);
	}

	/**
	 * TODO: $field1 >= $field2
	 */
	private function _buildWhere($wheres){
		$column = $this->_isinsert() || $this->_isdelete() 
			? "`".$wheres['field']."`" 
			: ( ($wheres['alias'] ? $wheres['alias'] ."." : "") . $wheres['field']);
		#处理where中有子查询的情况
		if($wheres['value'] instanceof Sql){
			switch($wheres['op']){
				case self::NOTIN:	
					$cond = " NOT IN (".$wheres['value']->__toString().")";break;
				case self::IN:		
					$cond = " IN (".$wheres['value']->__toString().")";break;
				case self::BETWEEN:
				default:				$cond = " IS NOT NULL";break;
			}
			return $column.$cond;
		}
		
		
		$conds = array(
			"table"=>$this->from[$wheres['alias']],
			"field"=>$wheres['field'],
			"value"=>$wheres['value']
		);
		
		switch($wheres['op']){
			case self::LIKE:		
				$cond = " LIKE '%".$conds['value']."%'";break;
			case self::NOT_LIKE:		
				$cond = " NOT LIKE '".$conds['value']."'";break;
			case self::BEFORE_LIKE:	
				$cond = " LIKE '%".$conds['value']."'";break;
			case self::END_LIKE:	
				$cond = " LIKE '".$conds['value']."%'";break;
			case self::EQ:	
				// make "=null" as "is null"
				if ($conds['value'] === null 
				|| strcasecmp($conds['value'], "null") == 0)
				{
					$cond = " IS NULL";break;
				}
				else
				{	
					$cond = " = ".$this->_quoteValue($conds['value']);break;
				}
			case self::NOTIN:	
				$values = $this->_quoteValue($conds['value']);
				$cond = " NOT IN (".join(",",(array)$values).")";break;
			case self::IN:
				$cond = "";
				$values = $this->_quoteValue($conds['value']);
				if (empty($values)) {
					$cond .= " is null";
					break;
				}
				// If $values contains null, make "in null" as "is null".
				$iscontainsnull = false;
				foreach ($values as $index=>$val)
				{
					if ($val == "null") 
					{
						$iscontainsnull = true;
						unset($values[$index]);
					}
				}
				if (! empty($values)) $cond .= " IN (".join(",",(array)$values).")";
				if ($iscontainsnull) $cond .= empty($cond) ? " is null" : " OR $column is null";	
				break;
			case self::BETWEEN:
				$values = $conds['value'];
				$cond = " BETWEEN ".$this->_quoteValue(array_shift($values))." AND ".$this->_quoteValue(array_shift($values));
				break;
			case self::NE:			$cond = " != ".$this->_quoteValue($conds['value']);break;
			case self::GT:			$cond = " > ".$this->_quoteValue($conds['value']);break;
			case self::LT:			$cond = " < ".$this->_quoteValue($conds['value']);break;
			case self::GEQ:			$cond = " >= ".$this->_quoteValue($conds['value']);break;
			case self::LEQ:			$cond = " <= ".$this->_quoteValue($conds['value']);break;
			case self::ISNULL:		$cond = " IS NULL";break;
			case self::FIND_IN_SET:	
				$cond = " FIND_IN_SET(". $this->_quoteValue($conds['value']) . "," . $column . ")";
				return $cond;
				break;
			case self::LIKE_IN_SET:
				unset($t_cond);
				foreach ((array)$conds['value'] as $t_value) {
					$t_cond .= (empty($t_cond) ? "" : " OR") . " {$column} LIKE '%{$t_value}%'";
				}
				$cond .= "({$t_cond})";
				return $cond;
				break;
			case self::ISNOTNULL:	
			default:				$cond = " IS NOT NULL";break;
		}
		return $column.$cond;
	}
	
	/**
	 * Map operator string to operator code.
	 *
	 * @param string $opstr
	 */
	public function OpStr2Code($opstr)
	{
		// TODO: not all
		switch ($opstr)
		{
			case '<':
				$opcode = SqlHelper::LT;
				break;
			case '>':
				$opcode = SqlHelper::GT;
				break;
			case '!=':
			case 'ne':
				$opcode = SqlHelper::NE;
				break;
			case 'eq':
			case '=':
				$opcode = SqlHelper::EQ;
			default:
				$opcode = $opstr;
				break;
		}
		
		return $opcode;
	}

}


class Where{
	private $alias;
	private $field;
	private $op;
	private $value;
	private $andor;
	public function __construct($alias,$field,$op,$value,$andor="AND"){
		$this->alias 	= $alias;
		$this->field 	= $field;
		$this->op 		= $op;
		$this->value 	= $value;
		$this->andor 	= $andor;
	}
	public function getWhereArray(){
		return array(
			"alias"	=> $this->alias,
			"field"	=> $this->field,
			"op"	=> $this->op,
			"value"	=> $this->value,
			"andor"	=> $this->andor
		);
	}
	public function getAlias(){
		return $this->alias;
	}
	public function getField(){
		return $this->field;
	}
	public function getOp(){
		return $this->op;
	}
	public function getValue(){
		return $this->value;
	}
	public function getAndOr(){
		return $this->andor;
	}
}
?>
