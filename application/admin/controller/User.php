<?php
namespace app\admin\controller;
use think\Request;

class User extends Base
{
    /**
     * 用户列表
     */
    public function index()
    {
    	$homeUrl = url('admin/index/home');
    	$this->assign('homeUrl',$homeUrl);
    	return $this->fetch();
    }
}
