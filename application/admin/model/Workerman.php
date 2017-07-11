<?php
/**
 * workerman接口实现
 */
namespace app\admin\model;

use think\Model;
use think\Session;
use think\Cache;
use GatewayClient\Gateway;

class Workerman extends Model
{
	public $registerAddress = '127.0.0.1';
	public $port = '1238';
	//静态变量保存全局实例
    private static $_instance = null;

    //私有构造函数，防止外界实例化对象
    private function __construct() {
    	// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
		Gateway::$registerAddress = $this->registerAddress.':'.$this->port;
    	parent::__construct();
    }

    //私有克隆函数，防止外办克隆对象
    private function __clone() {
    }
    //静态方法，单例统一访问入口
    static public function getInstance() {
        if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }
	/**
	 * 绑定client_id
	 * @Author   liulong
	 * @DateTime 2017-06-16T11:07:57+0800
	 * @param    [type]                   $clientid [description]
	 * @return   [type]                             [description]
	 */
    public function bindClientid($client_id){
		$user_data = Cache::get('user_auth');
		// client_id与uid绑定
		Gateway::bindUid($client_id, $user_data['uid']);
		return true;
    }
    /**
     * 通过用户ID获取client_id
     * @Author   liulong
     * @DateTime 2017-06-16T11:47:48+0800
     * @param    [type]                   $uid [description]
     * @return   array                        [description]
     */
    public function getClientIdByUid($uid){
    	$client_arr = Gateway::getClientIdByUid($uid);
    	return $client_arr;
    }
    /**
     * 统一发送数据
     * @Author   liulong
     * @DateTime 2017-06-16T14:14:09+0800
     * @param    array                    $send_data [description]
     */
    public static function setSendData($send_data = []){
    	// $message_data = [
     //        'type' => 'chatMessge|sendMessge', 聊天消息 推送消息
     //        'content' => ['text' => '','pic' => '','vodio' => ''],
     //        'send_type' => all|group|dgroup|personal,
     //        'send_id' => 0|gid|dgid|uid   
     //    ];
        $message_data = [
        	'type' => $send_data['type'],
        	'content' => $send_data['content'],
        ];
        return json_encode($message_data);
    }
    /**
     * 向所有客户端或者client_id_array指定的客户端发送$send_data数据。
     * 如果指定的$client_id_array中的client_id不存在则自动丢弃
     * @Author   liulong
     * @DateTime 2017-06-16T14:01:43+0800
     * @param    [type]                   $send_data         要发送的数据（字符串类型），此数据会被Gateway所使用协议的encode方法打包后发送给客户端
     * @param    [type]                   $client_id_array   指定向哪些client_id发送，如果不传递该参数，则是向所有在线客户端发送 $send_data 数据
     * @param    [type]                   $exclude_client_id client_id组成的数组。$exclude_client_id数组中指定的client_id将被排除在外，不会收到本次发的消息
     * @param    [type]                   $raw               是否发送原始数据，也就是绕过gateway协议打包过程，gateway对数据不再做任何处理，直接发给客户端。
     * @return   [type]                                      [description]
     */
    public function sendToAll($send_data = [],$client_id_array  = [],$exclude_client_id = [],$raw = false){
    	Gateway::sendToAll(self::setSendData($send_data),$client_id_array,$exclude_client_id,$raw);
    }
    /**
     * 通过uid发送数据
     * @Author   liulong
     * @DateTime 2017-06-16T13:53:45+0800
     * @param    [type]                   $uid     [description]
     * @param    array                    $message [description]
     * @return   [type]                            [description]
     */
    public function sendToUid($uid,$message = []){
    	$message = self::setSendData($send_data);
        Gateway::sendToUid($uid, $message);
    }
	
	/**
	 * 向某个分组的所有在线client_id发送数据。
	 * @Author   liulong
	 * @DateTime 2017-06-17T17:30:01+0800
	 * @param    [type]                   $group             [description]
	 * @param    array                    $message           [description]
	 * @param    array                    $exclude_client_id [description]
	 * @param    boolean                  $raw               [description]
	 * @return   [type]                                      [description]
	 */
	public function sendToGroup($group, $message = [],$exclude_client_id = [],$raw = false){
		Gateway::sendToGroup($group, $message,$exclude_client_id,$raw);
	}
	/**
	 * 判断$uid是否在线，此方法需要配合bindUid($client_uid, $uid)使用。
	 * @Author   liulong
	 * @DateTime 2017-06-16T14:32:24+0800
	 * @param    [type]                   $uid [description]
	 * @return   boolean                       [description]
	 */
	public function isUidOnline($uid){
		return Gateway::isUidOnline($uid);
	}

	public function getClientIdByUid($uid){
		$client_id = Gateway::getClientIdByUid($uid);
		if(count($client_id) > 0){
			return $client_id[0];
		}else{
			return false;
		}
	}
	/**
	 * 加入分组
	 * @Author   liulong
	 * @DateTime 2017-06-16T14:43:29+0800
	 * @param    [type]                   $uid   [description]
	 * @param    array                    $group [description]
	 * @return   [type]                          [description]
	 */
	public function joinGroup($uid, $group){
		$client_id = $this->getClientIdByUid($uid);
		if($client_id){
			if(is_array($group)){
				foreach ($group as  $value) {
					Gateway::joinGroup($client_id, $value);
				}	
			}else{
				Gateway::joinGroup($client_id, $group);
			}
		}else{
			return false;
		}	
	}

}