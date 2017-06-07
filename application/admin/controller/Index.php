<?php
namespace app\admin\controller;
use think\Request;

class Index extends Base
{
    public function index()
    {
    	$homeUrl = url('admin/index/home');
        $userUrl = url('admin/user/index');
        $logoutUrl = url('admin/admin/logout');
        $lmyimUrl = url('index/index/index');
        $this->assign('logoutUrl',$logoutUrl);
    	$this->assign('homeUrl',$homeUrl);
        $this->assign('userUrl',$userUrl);
        $this->assign('lmyimUrl',$lmyimUrl);
    	return $this->fetch();
    }

    /**
     * 后台首页
     */
    public function home()
    {
    	//获取服务器信息
    	$server = Request::instance()->server();
    	$sinfo = [
            '操作系统'=>PHP_OS,
            '运行环境'=>$server["SERVER_SOFTWARE"],
            '主机名'=>$server['SERVER_NAME'],
            'WEB服务端口'=>$server['SERVER_PORT'],
            '网站文档目录'=>$server["DOCUMENT_ROOT"],
            '浏览器信息'=>substr($server['HTTP_USER_AGENT'], 0, 40),
            '通信协议'=>$server['SERVER_PROTOCOL'],
            '请求方法'=>$server['REQUEST_METHOD'],
            'ThinkPHP版本'=>THINK_VERSION,
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$server['SERVER_NAME'].' [ '.gethostbyname($server['SERVER_NAME']).' ]',
            '用户的IP地址'=>$server['REMOTE_ADDR'],
            '剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
        ];
    	$homeUrl = url('index/index/home');
    	$this->assign('sinfo',$sinfo);
    	return $this->fetch();
    }
}
