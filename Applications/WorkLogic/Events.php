<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
use \think\Cache;
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        //初始化缓存数据库
        self::initCache();
        $user_data = Cache::get('user_auth');
        Gateway::bindUid($client_id, $user_data['uid']);
        file_put_contents('logfile.log', date("Y-m-d H:i:s"). " 初始化数据 " . var_export($user_data,true).PHP_EOL, FILE_APPEND | LOCK_EX);
        // 向当前client_id发送数据 
        // Gateway::sendToClient($client_id, "Hello $client_id\r\n");
        // // 向所有人发送
        // Gateway::sendToAll("$client_id login\r\n");
        Gateway::sendToClient($client_id, json_encode(array(
            'type'      => 'init',
            'client_id' => $client_id,
            'uid' => $user_data['uid']
        )));
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
        self::eventsLogic($client_id,$message);
        //聊天
        // $message_data = json_decode($message, true);
        // if($message_data['type'] == 'chatMessage'){
        //     $content = [
        //         'content' => $message_data['content'],
        //         'type' => $message_data['type'],
        //     ];
        //     if($message_data['send_to_all'] == '1'){
        //         return GateWay::sendToAll(json_encode($message_to));
        //     }
        //     if(!empty($message_data['group_id'])){

        //     }
        // }
        // 向所有人发送 
        //Gateway::sendToAll("$client_id said $message\r\n");
      // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)." onMessage:".$message."\n";
        
        // 客户端传递的是json数据

        $message_data = json_decode($message, true);
        file_put_contents('logfile.log', date("Y-m-d H:i:s"). " " . var_export($message_data,true).PHP_EOL, FILE_APPEND | LOCK_EX);
        if(!$message_data)
        {
            return GateWay::sendToAll(json_encode(['type' => 'chat','content' => '空数据']));
        }
        // $new_message = array(
        //     'type'=>'say', 
        //     'from_client_id'=>$client_id,
        //     'from_client_name' =>$client_name,
        //     'to_client_id'=>'all',
        //     'content'=>nl2br(htmlspecialchars($message_data['content'])),
        //     'time'=>date('Y-m-d H:i:s'),
        // );
        $message_data['data']['mine']['mine'] = false;
        $message_to = [
            'type' => $message_data['type'],
            'data' => $message_data['data']['mine']
        ];
        return GateWay::sendToAll(json_encode($message_to));

   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       // 向所有人发送 
       GateWay::sendToAll("$client_id logout\r\n");
   }

   /**
    * 业务逻辑
    * @Author   liulong
    * @DateTime 2017-06-12T17:11:28+0800
    * @param    [type]                   $client_id    [description]
    * @param    [type]                   $message_data [description]
    * #type
    * @return   [type]                                 [description]
    */
    public static function eventsLogic($client_id,$message){
        $message_data = json_decode($message, true);
        file_put_contents('logfile.log', date("Y-m-d H:i:s"). " " . var_export($message_data,true).PHP_EOL, FILE_APPEND | LOCK_EX);
        /*
        $message_data = [
<<<<<<< HEAD
            'type' => 'chatMessge|sendMessge', 聊天消息 推送消息
            'content' => ['text' => '','pic' => '','vodio' => ''],
            'send_type' => all|group|dgroup|personal,
            'send_id' => 0|gid|dgid|uid   
        ]
         */
        if($message_data['type'] == 'chatMessage'){
            $message_new = [
                'type' => 'chatMessage',
                'content' => $message_data['content'],
                'send_type' => $message_data['send_type'],
                'send_id' => $message_data['send_id'],
                'from_id' => $_SESSION['uid'],
                'from_username' => $_SESSION['username'],
                'time' => time(),
                'date' => date('Y-m-d H:i:s')
            ];
            switch ($message_new['send_type']) {
                case 'all':
                    return GateWay::sendToAll(json_encode($new_message));
                    break;
                case 'group':
                    return Gateway::sendToAll($message_new['send_id'] ,json_encode($new_message));
                    break;
                case 'dgroup':
                    return Gateway::sendToAll($message_new['send_id'] ,json_encode($new_message));
                    break;
                case 'personal':
                    $to_client_id = Gateway::getClientIdByUid($message_new['send_id']);
                    return Gateway::sendToClient($to_client_id, json_encode($new_message));
                    break;
                default:
                    # code...
                    break;
            }
        }
    }

    public static function initCache(){
        define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
        $options = [
            // 缓存类型为File
            'type'  =>  'redis', 
            // 缓存有效期为永久有效
            'expire'=>  0, 
            //缓存前缀
            'prefix'=>  'lmy',
            // 服务器地址
            'host'       => '127.0.0.1',
            'port'       => 6379
        ];
        Cache::connect($options);
    }
}
