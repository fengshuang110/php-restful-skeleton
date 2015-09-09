<?php
//-----------------------------------------------------------------
/**
 * 环信-服务器端REST API
 * @author    fengshuang
 */
class Third_Easemob {
	private $client_id;
	private $client_secret;
	private $org_name;
	private $app_name;
	private $url;
	
	/**
	 * 初始化参数
	 *
	 * @param array $options   
	 * @param $options['client_id']    	
	 * @param $options['client_secret'] 
	 * @param $options['org_name']    	
	 * @param $options['app_name']   		
	 */
	public function __construct($options) {
		$this->client_id = isset ( $options ['client_id'] ) ? $options ['client_id'] : '';
		$this->client_secret = isset ( $options ['client_secret'] ) ? $options ['client_secret'] : '';
		$this->org_name = isset ( $options ['org_name'] ) ? $options ['org_name'] : '';
		$this->app_name = isset ( $options ['app_name'] ) ? $options ['app_name'] : '';
		if (! empty ( $this->org_name ) && ! empty ( $this->app_name )) {
			$this->url = 'https://a1.easemob.com/' . $this->org_name . '/' . $this->app_name . '/';
		}
	}
		
	/**
	 * 
	 * $users[]=array(
			"username"=>$param['username'],
			"password"=>$param['password'],
			'nickname'=>$param['nickname']
	);
	*/
	public function registerUsers($users)
	{
		$url=$this->url."users";
		return $this->requestExecute($url,'POST',$users);
	
	}
	/**
	 * array("username"=>,"password"=>);
	 */
	//授权注册模式 POST /{org_name}/{app_name}/users
	public function registerUser($user)
	{
		$url=$this->url."users";
		
		return $this->requestExecute($url,'GET',$user);
		
	}
	/**
	 * 单个用户信息
	 * @param unknown $username
	 * @return mixed
	 */
	public  function getUser($username){
	    $url=$this->url."users/".$username;
	    return $this->requestExecute($url,'GET');
	}
	/**
	 * 批量用户信息
	 * @param string $cursor
	 * @param number $limit
	 * @return mixed
	 */
	public  function getUsers($cursor="",$limit=10){
	    if(empty($cursor) || $cursor == ""){
	        $url=$this->url."users?"."limit=$limit";
	    }else{
	        $url=$this->url."users?"."limit=$limit"."&cursor=$cursor";
	    }
	    return $this->requestExecute($url,'GET');
	   
	}
	/**
	 * 重置用户密码
	 *
	 * @param $options['username'] 用户名
	 * @param $options['password'] 密码
	 * @param $options['newpassword'] 新密码
	 */
	public function editPassword($options) {
		$url = $this->url . "users/" . $options ['username'] . "/password";
		$param['newpassword'] = $options['newpasswod'];
		return $this->requestExecute($url,'PUT',$param);
		
	}
	/**
	 * 删除用户
	 * @param unknown $username
	 * @return Ambigous <string, mixed>
	 */
	public function deleteUser($username){
	    $url=$this->url."users/".$username;
	    return $this->requestExecute($url,'DELETE');
	
	}
	/**
	 * 修改用户昵称
	 * @param unknown array("username"=>"11","nickname"=>"xiaoshuang")
	 * @return Ambigous <string, mixed>
	 */
	public function editUserNickname($options){
		$url=$this->url."users/".$options['username'];
		$param = array("nickname"=>$options['nickname']);
		return $this->requestExecute($url,'DELETE',$param);
	
	}
	
	public function batchDeleteUser($limit = "300", $ql = '') {
		$url = $this->url . "users?limit=" . $limit;
		if (! empty ( $ql )) {
			$url = $this->url . "users?ql=" . $ql . "&limit=" . $limit;
		}
		return $this->requestExecute($url,'DELETE');
	
	    
	}
	
	/**
	 * 给一个用户添加一个好友
	 *
	 * @param
	 *        	$owner_username
	 * @param
	 *        	$friend_username
	 */
	public function addFriend($owner_username, $friend_username) {
		$url = $this->url . "users/" . $owner_username . "/contacts/users/" . $friend_username;
	    return $this->requestExecute($url);
	}
	
   /**
	* 删除好友
	*
	* @param
	*        	$owner_username
	* @param
	*        	$friend_username
	*/
	public function deleteFriend($owner_username, $friend_username) {
		$url = $this->url . "users/" . $owner_username . "/contacts/users/" . $friend_username;
		return $this->requestExecute($url,"DELETE");
	
	}
	
	/**
	 * 查看用户的好友
	 *
	 * @param
	 *        	$owner_username
	 */
	public function showFriend($owner_username) {
		$url = $this->url . "users/" . $owner_username . "/contacts/users/";
		return $this->requestExecute($url,"GET");
		
	}

	/**
	 * 获取IM用户的黑名单
	 *
	 * @param
	 *   
	 */
	public function showBlocks($owner_username) {
		$url = $this->url . "users/" . $owner_username . "/blocks/users/";
		return $this->requestExecute($url,"GET");
	
	}
	
	/**
	 * 用户的添加黑名单
	 *
	 * @param
	 *$userlist = array("username1","username2")
	 */
	public function addBlocks($owner_username,$userlist=array()) {
		$url = $this->url . "users/" . $owner_username . "/blocks/users/";
		$param = array("usernames"=>$userlist);
		return $this->requestExecute($url,"POST",$param);
	
	}
	
	/**
	 * 从一个IM用户的黑名单中减人
	 *
	 * @param $owner_username
	 *  @param $owner_username
	 */
	public function deleteBlocks($owner_username,$blocked_username) {
		$url = $this->url . "users/" . $owner_username . "/blocks/users/".$blocked_username;
		
		return $this->requestExecute($url,"DELETE");
	
	}
	
	
	// +----------------------------------------------------------------------
	// | 聊天相关的方法
	// +----------------------------------------------------------------------
	/**
	 * 查看用户是否在线
	 *
	 * @param
	 *        	$username
	 */
	public function isOnline($username) {
		$url = $this->url . "users/" . $username . "/status";
		return $this->requestExecute($url,"GET");
		
	}
	

	
	
	/**
	 * 发送消息
	 *
	 * @param string $from_user
	 *        	发送方用户名
	 * @param array $username
	 *        	array('1','2')
	 * @param string $target_type
	 *        	默认为：users 描述：给一个或者多个用户(users)或者群组发送消息(chatgroups)
	 * @param string $content
	 * @param array $ext
	 *        	自定义参数
	 */
	function sendText($from_user = "admin", $username, $content, $target_type = "users", $ext) {
		$option ['target_type'] = $target_type;
		$option ['target'] = $username;
		$params ['type'] = "txt";
		$params ['msg'] = $content;
		$option ['msg'] = $params;
		$option ['from'] = $from_user;
		$option ['ext'] = $ext;
		$url = $this->url . "messages";
		return $this->requestExecute($url,"post",$option);
		
	}
	
/**
	 * 发送图片消息
	 *
	 * @param string $from_user
	 *        	发送方用户名
	 * @param array $username 
	 *        $target_type为users 那么为给用户发
	 *        $target_type为chatgroups 那么为给群组发	
	 *        array('1','2')  用户username  或者群组id
	 * @param string $target_type
	 *        	默认为：users 描述：给一个或者多个用户(users)或者群组发送消息(chatgroups)
	 * @param string $content
	 * @param array $ext
	 *        	自定义参数
	 */
	function sendImage($from_user = "admin", $username, $content, $target_type = "users", $ext) {
		$option ['target_type'] = $target_type;
		$option ['target'] = $username;
		$params ['type'] = "img";
		$params= array_merge($params,$content);
		$option ['msg'] = $params;
		$option ['from'] = $from_user;
		$option ['ext'] = $ext;
		$url = $this->url . "messages";
		return $this->requestExecute($url,"post",$option);
		
	}
	
	/**
	 * 
	 *发送图片消息
	 * @param string $from_user
	 *        	发送方用户名
	 * @param array $username
	 *        $target_type为users 那么为给用户发
	 *        $target_type为chatgroups 那么为给群组发
	 *        array('1','2')  用户username  或者群组id
	 * @param string $target_type
	 *        	默认为：users 描述：给一个或者多个用户(users)或者群组发送消息(chatgroups)
	 * @param string $content
	 * @param array $ext
	 *        	自定义参数
	 */
	function sendVoice($from_user = "admin", $username, $content, $target_type = "users", $ext) {
		$option ['target_type'] = $target_type;
		$option ['target'] = $username;
		$params ['type'] = "img";
		$params= array_merge($params,$content);
		$option ['msg'] = $params;
		$option ['from'] = $from_user;
		$option ['ext'] = $ext;
		$url = $this->url . "messages";
		return $this->requestExecute($url,"post",$option);
	
	}
	
	/**
	 *
	 *发送语音消息
	 * @param string $from_user
	 *        	发送方用户名
	 * @param array $username
	 *        $target_type为users 那么为给用户发
	 *        $target_type为chatgroups 那么为给群组发
	 *        array('1','2')  用户username  或者群组id
	 * @param string $target_type
	 *        	默认为：users 描述：给一个或者多个用户(users)或者群组发送消息(chatgroups)
	 * @param string $content
	 * @param array $ext
	 *        	自定义参数
	 */
	function sendVideo($from_user = "admin", $username, $content, $target_type = "users", $ext) {
		$option ['target_type'] = $target_type;
		$option ['target'] = $username;
		$params ['type'] = "video";
		$params= array_merge($params,$content);
		$option ['msg'] = $params;
		$option ['from'] = $from_user;
		$option ['ext'] = $ext;
		$url = $this->url . "messages";
		return $this->requestExecute($url,"post",$option);
	
	}
	
	
	
	/**
	 * 获取app中所有的群组
	 */
	public function chatGroups() {
		$url = $this->url . "chatgroups";
		return $this->requestExecute($url,"GET");
		
	}
	
	/**
	 * 创建群组
	 *
	 * @param $option['groupname'] //群组名称,
	 *        	此属性为必须的
	 * @param $option['desc'] //群组描述,
	 *        	此属性为必须的
	 * @param $option['public'] //是否是公开群,
	 *        	此属性为必须的 true or false
	 * @param $option['approval'] //加入公开群是否需要批准,
	 *        	没有这个属性的话默认是true, 此属性为可选的
	 * @param $option['owner'] //群组的管理员,
	 *        	此属性为必须的
	 * @param $option['members'] //群组成员,此属性为可选的
	 */
	public function createGroups($option) {
		$url = $this->url . "chatgroups";
		return $this->requestExecute($url,'post',$option);
	}
	
	/**
	 * 获取群组详情
	 *
	 * @param
	 *        	$group_id
	 */
	public function chatGroupsDetails($group_id) {
		$url = $this->url . "chatgroups/" . $group_id;
		return $this->requestExecute($url,"get");
		
	}
	/**
	 * 修改群组详情
	 *
	 * @param
	 * $options = array(groupname=>，description=>，maxusers=>);
	 *        	$group_id
	 */
	public function editchatGroupsDetails($group_id,$options) {
		$url = $this->url . "chatgroups/" . $group_id;
		$param = $options;
		return $this->requestExecute($url,"put",$param);
	}
	
	/**
	 * 删除群组
	 *
	 * @param
	 *        	$group_id
	 */
	public function deleteGroups($group_id) {
		$url = $this->url . "chatgroups/" . $group_id;
		return $this->requestExecute($url,"delete");
	}
	
	
	/**
	 * 获取群组成员
	 *
	 * @param
	 *        	$group_id
	 */
	public function groupsUser($group_id) {
		$url = $this->url . "chatgroups/" . $group_id . "/users";
		return $this->requestExecute($url,"get");
	}
	
	/**
	 * 群组添加成员
	 *
	 * @param
	 *        	$group_id
	 * @param
	 *        	$username
	 */
	public function addGroupsUser($group_id, $username) {
		$url = $this->url . "chatgroups/" . $group_id . "/users/" . $username;

		return $this->requestExecute($url);
		
	}
	
	/**
	 * 群组删除成员
	 *
	 * @param
	 *        	$group_id
	 * @param
	 *        	$username
	 */
	public function delGroupsUser($group_id, $username) {
		$url = $this->url . "chatgroups/" . $group_id . "/users/" . $username;
		
		return $this->requestExecute($url,"DELETE");
	}
	
	
	/**
	 * 获取一个用户参与的所有群组
	 *
	 * @param
	 *        	$group_id
	 * @param
	 *        	$username
	 */
	public function groupUserJoin($username) {
		$url = $this->url."users/" . $username."/joined_chatgroups";
	
		return $this->requestExecute($url,"GET");
	}
	
	/**
	 * 群组批量添加成员
	 *
	 * @param
	 *        	$group_id
	 * @param
	 *        	$userlist=array("user1","user2");
	 */
	public function addGroupUsers($groupid,$userlist) {
		$url = $this->url."chatgroups/" . $groupid."/users";
	    $param =array("usernames"=>$userlist);
		return $this->requestExecute($url,"post",$param);
	}
	

	
	/**
	 * 聊天消息记录
	 *
	 * @param $ql 查询条件如：$ql
	 *        	= "select+*+where+from='" . $uid . "'+or+to='". $uid ."'+order+by+timestamp+desc&limit=" . $limit . $cursor;
	 *        	默认为order by timestamp desc
	 * @param $cursor 分页参数
	 *        	默认为空
	 * @param $limit 条数
	 *        	默认20
	 */
	public function chatRecord($ql = '', $cursor = '', $limit = 20) {
		$ql = ! empty ( $ql ) ? "ql=" . $ql : "order+by+timestamp+desc";
		$cursor = ! empty ( $cursor ) ? "&cursor=" . $cursor : '';
		$url = $this->url . "chatmessages?" . $ql . "&limit=" . $limit . $cursor;
		return $this->requestExecute($url,"GET");
	}
	
	public function requestExecute($url,$type = "post",$param =array()){
	    $param=json_encode($param);
// 	    print_r($param);die;
	    $header = array($this->_get_token());
	    $type = strtolower($type);
	    $res = $this->_curl_request($url,$param,$header,$type);
	    return $res;
	}
	
	
	//先获取app管理员token POST /{org_name}/{app_name}/token
	public function _get_token()
	{
		$formgettoken="https://a1.easemob.com/huzhuwang/huzhuapp/token";
		$body=array(
				"grant_type"=>"client_credentials",
				"client_id"=>$this->client_id,
				"client_secret"=>$this->client_secret
		);
		$patoken=json_encode($body);
		$filestr= file_get_contents("library/conf/easemob.json");
		$arr = json_decode($filestr);
		if(!empty($arr)){
			if ($arr->expires_in < time ()) {
				$result = $this->_curl_request ( $formgettoken, $patoken );
				$result= json_decode($result);
				$result->expires_in = $result->expires_in + time ();
			    $fp = @fopen ( "library/conf/easemob.json", 'w+' );
				@fwrite ( $fp, json_encode($result) );
				fclose ( $fp );
				return "Authorization: Bearer ". $result->access_token;
				exit ();
			}else{
				return "Authorization: Bearer ". $arr->access_token;
			}
		}
		$result = $this->_curl_request ( $formgettoken, $patoken );
		$result= json_decode($result);
		$result->expires_in = $result->expires_in + time ();
		$fp = @fopen ( "library/conf/easemob.json", 'w+' );
		@fwrite ( $fp, json_encode($result) );
		fclose ( $fp );
		return "Authorization: Bearer ". $result->access_token;
		exit ();
		
		
	}
	public function _curl_request($url, $body, $header = array(), $method = "POST")
	{
		array_push($header, 'Accept:application/json');
		array_push($header, 'Content-Type:application/json');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, $method, 1);
		$method = strtolower($method);
		switch ($method){
			case "get" :
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				break;
			case "post":
				curl_setopt($ch, CURLOPT_POST,true);
				break;
			case "put" :
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				break;
			case "delete":
				curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
		}
	
		curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
		curl_setopt($ch, CURLOPT_ENCODING,'gzip');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		if (isset($body{3}) > 0) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}
		if (count($header) > 0) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		$ret = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		//clear_object($ch);
		//clear_object($body);
		//clear_object($header);
		if ($err) {
			return $err;
		}
		return $ret;
	}
}