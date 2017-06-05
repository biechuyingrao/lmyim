<?php
/**
 * 用户逻辑层
 */
namespace app\admin\logic;

use think\Model;
use think\Loader;

class User extends Model
{
	/**
	 * 注册新用户数据检测
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
}