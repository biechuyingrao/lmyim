<?php
/**
 * 后台用户管理
 */
namespace app\admin\controller;
use think\Request;
use think\Session;
use think\Loader;

class Admin extends Base
{
    public function index()
    {
    	$homeUrl = url('admin/index/home');
    	$this->assign('homeUrl',$homeUrl);
    	return $this->fetch();
    }

    /**
     * 后台登录
     */
    public function login(){
        if(Session::has('name') && Session::get('admin_id') > 0){
            $this->error("您已登录",U('Admin/Index/index'),1);
        }
        if(IS_POST){
            $email = Request::instance()->param('email');
            $password = Request::instance()->param('password');
            $vercode = Request::instance()->param('vercode');
            $user = Loader::model('User');
            $uid = $user->doLogin($email,$password,2);
            if($uid['status'] == '1'){
                $this->success("登录成功",url('admin/index/index'),1);
            }else{
                $this->error($uid['msg']);
            }
        }else{
            $loginUrl = url('admin/admin/login');
            $regUrl = url('admin/admin/reg');
            $doLogin = url('admin/admin/login');
            $this->assign('loginUrl',$loginUrl); 
            $this->assign('regUrl',$regUrl); 
            $this->assign('doLogin',$doLogin); 
            return $this->fetch();
        }

    }

    /**
     * 后台注册
     */
    public function reg(){
        if(Session::has('name') && Session::get('admin_id') > 0){
            $this->error("您已登录",U('Admin/Index/index'),1);
        }
        if(IS_POST){
            $email = Request::instance()->param('email');
            $username = Request::instance()->param('username');
            $password = Request::instance()->param('password');
            $repass = Request::instance()->param('repass');
            $vercode = Request::instance()->param('repass');
            $vercode = $vercode ? $vercode : '';
            $user = Loader::model('User','logic');
            $uid = $user->doReg($email,$username,$password,$repass,$vercode);
            if($uid['status'] == 1){
                $this->success("注册成功",url('admin/admin/login'));
            }else{
                $this->error($uid['msg']);
            }
        }else{
            $loginUrl = url('admin/admin/login');
            $regUrl = url('admin/admin/reg');
            $doReg = url('admin/admin/reg');
            $this->assign('loginUrl',$loginUrl); 
            $this->assign('regUrl',$regUrl);
            $this->assign('doReg',$doReg);
            return $this->fetch();
        }
    }

}
