<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use think\Cache;

class Index extends Controller
{
	public function _initialize()
    {
    	if(!is_login()){
    		$this->error('请先登陆',url('Admin/Admin/login'),1);
    	}
    }
	/**
	 * 聊天界面首页
	 */
    public function index()
    {
    	$id = Request::instance()->param('id');
    	$jsonUrl = url('index/index/getListJson','id='.$id);			//拉取好友关系
    	$bindUrl = url('index/index/bindClientid','id='.$id);			//提交数据绑定
    	$this->assign('bindUrl',$bindUrl);
    	$this->assign('jsonUrl',$jsonUrl);
    	return $this->fetch('index');
    }

    
    /**
     * [sendMessage 向好友发送消息]
     * @Author   liulong
     * @DateTime 2017-06-08T14:22:54+0800
     * @return   [type]                   [description]
     */
    public function sendMessage(){
    	$uid = Request::instance()->param('id');
    	Gateway::$registerAddress = '127.0.0.1:1238';
		// 向任意uid的网站页面发送数据
		Gateway::sendToUid($uid, $message);
    }
    /**
     * [getListJson json数据获取]
     * @liulong
     * @DateTime 2017-06-08T13:47:21+0800
     * @return   [type]                   [description]
     */
    public function getListJson(){
    	$map['status'] = ['<>',-1];
    	$map['id'] = ['<>',Session::get('user_auth.uid')];
    	$list = Db::name('ucenter_member')->where($map)
    			->field("username,id,'' as avatar,'测试一下' as sign")
    			->select();
    	$data['mine'] = [
    		"username" => Session::get('user_auth.username'),
			"id" => Session::get('user_auth.uid'),
			"status" => "online",
			"sign" => "别问我是谁",
			"avatar" => "http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg"
    	];
    	$data['friend'][1] = [
    		"groupname" => "好友",
			"id" => 1,
			"online" => 2,
			'list' => $list
    	];
		echo jsonReturn(0,'获取成功',$data);
    }
}
