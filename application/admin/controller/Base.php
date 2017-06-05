<?php

/**
 * 基类
 */

namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\response\Json;
use think\Request;
use think\Session;

class Base extends Controller {

    /**
     * 析构函数
     */
    function __construct() 
    {
        header("Cache-control: private");  // history.back返回后输入框值丢失问题 参考文章 http://www.tp-shop.cn/article_id_1465.html  http://blog.csdn.net/qinchaoguang123456/article/details/29852881
        parent::__construct();      
   }    
    
    /*
     * 初始化操作
     */
    public function _initialize() 
    {
        define('MODULE_NAME',$this->request->module());  // 当前模块名称是
        define('CONTROLLER_NAME',$this->request->controller()); // 当前控制器名称
        define('ACTION_NAME',$this->request->action()); // 当前操作名称是
        define('IS_GET',$this->request->isGet());
        define('IS_POST',$this->request->isPost());
        define('IS_AJAX',$this->request->isPost());
        //过滤不需要登陆的行为
        if(in_array(ACTION_NAME,array('login','logout','vertify','reg')) || in_array(CONTROLLER_NAME,array('Ueditor','Uploadify'))){
        	//return;
        }else{
        	if(is_login()){
        		//$this->check_priv();//检查管理员菜单操作权限
        	}else{
        		$this->error('请先登陆',url('Admin/Admin/login'),1);
        	}
        }
    }
    
    public function ajaxReturn($data,$type = 'json'){                        
        exit(json_encode($data));
    }    
}