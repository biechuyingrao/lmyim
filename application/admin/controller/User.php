<?php
namespace app\admin\controller;
use think\Request;
use think\Db;

class User extends Base
{
    /**
     * 用户列表
     */
    public function index()
    {
        Db::name('ucenter_member')->where('status',1)->select();
    	$homeUrl = url('admin/index/home');
    	$this->assign('homeUrl',$homeUrl);
    	return $this->fetch();
    }
}
