<?php
/**
 * 用户数据层
 */
namespace app\admin\model;

use think\Model;
use think\Session;
use think\Cache;

class User extends Model
{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'lmy_ucenter_member';
    protected $autoWriteTimestamp = true;

    protected $auto = [];
    protected $insert = ['status' => 1,'reg_time','reg_ip'];  
    protected $update = ['last_login_time','last_login_ip'];  


    protected function setRegTimeAttr()
    {
        return time();
    }
    protected function setRegIpAttr()
    {
        return request()->ip();
    }
    protected function setLastLoginTimeAttr()
    {
        return time();
    }
    protected function setLastLoginIpAttr()
    {
        return request()->ip();
    }
	/**
	 * 注册一个新用户
	 * @param  string $email    用户邮箱
	 * @param  string $username 用户名
	 * @param  string $password 用户密码
	 * @param  string $mobile   用户手机号码
	 * @return array          注册成功-用户信息，注册失败-错误编号
	 */
	public function register($email,$username, $password, $mobile){
		$data = array(
			'username' => $username,
			'password' => $password,
			'email'    => $email,
			'mobile'   => $mobile,
		);

		//验证手机
		if(empty($data['mobile'])) unset($data['mobile']);

		/* 添加用户 */
		$uid = $this->validate(true)->save($data);
		if(false === $uid){
		    // 验证失败 输出错误信息
		    return return_msg('1003',$this->getError());
		}else{
			return return_msg('success',$uid);
		}
	}

	/**
	 * 用户登录认证
	 * @param  string  $username 用户名
	 * @param  string  $password 用户密码
	 * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
	 * @return integer           登录成功-用户ID，登录失败-错误编号
	 */
	public function doLogin($username, $password, $type = 1){
		$map = array();
		switch ($type) {
			case 1:
				$map['username'] = $username;
				break;
			case 2:
				$map['email'] = $username;
				break;
			case 3:
				$map['mobile'] = $username;
				break;
			case 4:
				$map['id'] = $username;
				break;
			default:
				return 0; //参数错误
		}

		/* 获取用户数据 */
		$user = $this->where($map)->find()->toArray();
		if(is_array($user) && $user['status']){
			/* 验证用户密码 */
			if($password === $user['password']){
				$this->updateLogin($user['id']); //更新用户登录信息
				$this->autoLogin($user);		//自动登录
				return return_msg('success','登录成功',$user); //登录成功，返回用户ID
			} else {
				return return_msg('1004','密码错误'); //密码错误
			}
		} else {
			return return_msg('1005','用户不存在或被禁用'); //用户不存在或被禁用
		}
	}

	/**
	 * 更新用户登录信息
	 * @param  integer $uid 用户ID
	 */
	protected function updateLogin($uid){
		$data = [
			'login' => ['exp','login+1']
		];
		$this->save($data,['id' => $uid]);
	}

	 /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        Session::clear();
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['id'],
            'username'        => $user['username'],
            'email'			  => $user['email'],
            'last_login_time' => $user['last_login_time'],
        );

        Cache::set('user_auth', $auth);
        Session::set('user_auth', $auth);
        Session::set('user_auth_sign', data_auth_sign($auth));

    }

}