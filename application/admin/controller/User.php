<?php
namespace app\admin\controller;
use think\Request;
use think\Loader;

class User extends Base
{
    /**
     * [index 用户列表]
     * @liulong
     * @DateTime 2017-06-07T17:45:01+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $page_size = Request::instance()->param('page_size');
        $page_size = $page_size ? intval($page_size) : 20;
        $search = Request::instance()->param('search');
        $search = $search ? $search : '';
        $order = Request::instance()->param('order');
        $order = $order ? $order : 'create_time desc';
        $start = Request::instance()->param('start');
        $start = $start ?  $start : 0;
        if(!empty($search)){
            $map['username|email|mobile'] = ['like','%'.$search.'%'];
        }else{
            $map['status'] = ['<>',-1];
        }
        $user = Loader::model('User','logic');
        $list = $user->getUserList($map,$order,$start,$page_size);
    	$homeUrl = url('admin/index/home');
    	$this->assign('homeUrl',$homeUrl);
        $this->assign('list',$list);
    	return $this->fetch();
    }
}
