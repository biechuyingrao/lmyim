<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use GatewayClient\Gateway;

class Index extends Controller
{
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
     * workerman clientid与用户id绑定 
     * @param
     */
    public function bindClientid(){
    	$client_id = Request::instance()->param('client_id');
    	// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
		Gateway::$registerAddress = '127.0.0.1:1238';
		// client_id与uid绑定
		Gateway::bindUid($client_id, $uid);
    }
    /**
     * json数据获取
     */
    public function getListJson(){
    	$arrList = [
    		"code" => 0,
    		"msg" => '',
    		"data" => [
    			"mine" => [
    				"username" => "小白菜思密达",
    				"id" => "100000",
    				"status" => "online",
    				"sign" => "在深邃的编码世界，做一枚轻盈的纸飞机",
    				"avatar" => "http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg"
    			]
    		]
    	];
    	$id = Request::instance()->param('id');
    	if(!empty($id)){
    		$jsonList = '{
			  "code": 0
			  ,"msg": ""
			  ,"data": {
			    "mine": {
			      "username": "大白菜"
			      ,"id": "'.$id.'"
			      ,"status": "online"
			      ,"sign": "在深邃的编码世界，做一枚轻盈的纸飞机"
			      ,"avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1"
			    }
			    ,"friend": [{
			      "groupname": "好友"
			      ,"id": 1
			      ,"online": 2
			      ,"list": [{
			        "username": "黎梦梦"
			        ,"id": "100000"
			        ,"avatar": "http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg"
			        ,"sign": "这些都是测试数据，实际使用请严格按照该格式返回"
			      }]
			  	}]
			  }
			}';
    	}else{
    	$jsonList = '{
		  "code": 0
		  ,"msg": ""
		  ,"data": {
		    "mine": {
		      "username": "纸飞机"
		      ,"id": "100000"
		      ,"status": "online"
		      ,"sign": "在深邃的编码世界，做一枚轻盈的纸飞机"
		      ,"avatar": "http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg"
		    }
		    ,"friend": [{
		      "groupname": "前端码屌"
		      ,"id": 1
		      ,"online": 2
		      ,"list": [{
		        "username": "贤心"
		        ,"id": "100001"
		        ,"avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1"
		        ,"sign": "这些都是测试数据，实际使用请严格按照该格式返回"
		      },{
		        "username": "Z_子晴"
		        ,"id": "108101"
		        ,"avatar": "http://tva3.sinaimg.cn/crop.0.0.512.512.180/8693225ajw8f2rt20ptykj20e80e8weu.jpg"
		        ,"sign": "微电商达人"
		      },{
		        "username": "Lemon_CC"
		        ,"id": "102101"
		        ,"avatar": "http://tp2.sinaimg.cn/1833062053/180/5643591594/0"
		        ,"sign": ""
		      },{
		        "username": "马小云"
		        ,"id": "168168"
		        ,"avatar": "http://tp4.sinaimg.cn/2145291155/180/5601307179/1"
		        ,"sign": "让天下没有难写的代码"
		        ,"status": "offline"
		      },{
		        "username": "徐小峥"
		        ,"id": "666666"
		        ,"avatar": "http://tp2.sinaimg.cn/1783286485/180/5677568891/1"
		        ,"sign": "代码在囧途，也要写到底"
		      }]
		    },{
		      "groupname": "网红"
		      ,"id": 2
		      ,"online": 3
		      ,"list": [{
		        "username": "罗玉凤"
		        ,"id": "121286"
		        ,"avatar": "http://tp1.sinaimg.cn/1241679004/180/5743814375/0"
		        ,"sign": "在自己实力不济的时候，不要去相信什么媒体和记者。他们不是善良的人，有时候候他们的采访对当事人而言就是陷阱"
		      },{
		        "username": "长泽梓Azusa"
		        ,"id": "100001222"
		        ,"sign": "我是日本女艺人长泽あずさ"
		        ,"avatar": "http://tva1.sinaimg.cn/crop.0.0.180.180.180/86b15b6cjw1e8qgp5bmzyj2050050aa8.jpg"
		      },{
		        "username": "大鱼_MsYuyu"
		        ,"id": "12123454"
		        ,"avatar": "http://tp1.sinaimg.cn/5286730964/50/5745125631/0"
		        ,"sign": "我瘋了！這也太準了吧  超級笑點低"
		      },{
		        "username": "谢楠"
		        ,"id": "10034001"
		        ,"avatar": "http://tp4.sinaimg.cn/1665074831/180/5617130952/0"
		        ,"sign": ""
		      },{
		        "username": "柏雪近在它香"
		        ,"id": "3435343"
		        ,"avatar": "http://tp2.sinaimg.cn/2518326245/180/5636099025/0"
		        ,"sign": ""
		      }]
		    },{
		      "groupname": "我心中的女神"
		      ,"id": 3
		      ,"online": 1
		      ,"list": [{
		        "username": "林心如"
		        ,"id": "76543"
		        ,"avatar": "http://tp3.sinaimg.cn/1223762662/180/5741707953/0"
		        ,"sign": "我爱贤心"
		      },{
		        "username": "佟丽娅"
		        ,"id": "4803920"
		        ,"avatar": "http://tp4.sinaimg.cn/1345566427/180/5730976522/0"
		        ,"sign": "我也爱贤心吖吖啊"
		      }]
		    }]
		    ,"group": [{
		      "groupname": "前端群"
		      ,"id": "101"
		      ,"avatar": "http://tp2.sinaimg.cn/2211874245/180/40050524279/0"
		    },{
		      "groupname": "Fly社区官方群"
		      ,"id": "102"
		      ,"avatar": "http://tp2.sinaimg.cn/5488749285/50/5719808192/1"
		    }]
		  }
		}';
		}
		$jsonList = json_decode($jsonList, true);
		echo json_encode($jsonList);
    }
}
