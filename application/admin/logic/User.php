<?php
/**
 * 用户逻辑层
 */
namespace app\admin\logic;

use think\Model;
use think\Loader;
use think\Db;
use think\Paginator;

class User extends Model
{
	/**
	 * [$table 当前模型数据表]
	 * @var string
	 */
    protected $table = 'lmy_ucenter_member';

	/**
	 * [doReg 注册新用户数据检测]
	 * @liulong
	 * @DateTime 2017-06-07T15:16:42+0800
	 * @param    [type]                   $email    [description]
	 * @param    [type]                   $username [description]
	 * @param    [type]                   $password [description]
	 * @param    [type]                   $repass   [description]
	 * @param    [type]                   $vercode  [description]
	 * @param    [type]                   $mobile   [description]
	 * @return   [type]                             [description]
	 */
	public function doReg($email,$username,$password,$repass,$vercode = null,$mobile = null){
		$info = [
			'password' => $password,
			'repass' => $repass,
			'vercode' => $vercode
		];
		$info = $this->check($info);
		if($info['status'] == 1){
			$user = Loader::model('User');
			$uid = $user->register($email,$username, $password, $mobile);
			if($uid['status'] == 1){
				return return_msg('success','注册成功');
			}else{
				return return_msg('1003',$uid['msg'],$uid);
			}
		}else{
			return $info;
		}
	}

	/**
	 * 验证方法
	 */
	private function check($info = array()){
		$config = [
			'iscode' => ['isdo' => 0,'rule' => 'checkVerify'],
			'isrepass' => ['isdo' => 1,'rule' => 'checkRepass'],
		];
		foreach ($config as $key => $value) {
			if($value['isdo']) return call_user_func(array($this,$value['rule']),$info);
		}
		return return_msg('success','验证通过');
	}
	/**
	 * 验证数字验证码
	 */
	private function checkVerify(){
		if(!captcha_check($captcha)){
		 	return return_msg('1001','数字验证码错误');
		}else{
			return return_msg('success','验证通过');
		}
	}

	/**
	 * 检测密码是否相等
	 */
	private function checkRepass($info){
		if($info['password'] != $info['repass']){
			return return_msg('1002','重复密码不同');
		}else{
			return return_msg('success','验证通过');
		}
	}

    /**
     * [getUserList 获取用户列表]
     * @liulong
     * @DateTime 2017-06-07T15:21:18+0800
     * @param    [array]                  $condition [搜索条件]
     * @param    string                   $order     [排序方式]
     * @param    integer                  $start     [limit开始行]
     * @param    integer                  $page_size [获取数量]
     * @param    integer                  $is_page   [是否加载分页类 1加载]
     * @return   [array]                             [description]
     */
    public function getUserList($condition = [],$order = '',$start = 0,$page_size = 20,$is_page = 1){
    	if($is_page == 1){
    		$res = $this->where($condition)->order($order)->paginate($page_size);
    	}else{
    		$res = $this->where($condition)->order($order)->limit($start,$page_size)->select();
    	}
        return $res;
    }
}