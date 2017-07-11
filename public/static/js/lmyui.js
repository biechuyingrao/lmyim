/*!

 @Title: lmy照片墙
 @Description：lmy照片墙
 @Site: xxx
 @Author: illmy

 */

;!function(win){

"use strict";
//全局配置，如果采用默认均不需要改动
var config =  {
	elem: ''	//选择元素
	path: '',	//图片地址
	auto: true,	//是否自动更新
	sec: 3		//更新时间默认为3秒
};

var Lmy = function(options){
	this.v = "1.0.0_r";		//版本号
	if(!options.auto && options.auto != false){
		options.auto = config.auto;
	}
	options.sec = options.sec || config.sec;
	this.options = options;
}
Lmy.fn = Lmy.prototype;

var doc = document,Lmy.fn.cache = {},

//获取lmy.js所在目录
getPath = function(){
    var js = doc.scripts, jsPath = js[js.length - 1].src;
    console.log(jsPath);
    return jsPath.substring(0, jsPath.lastIndexOf('/') + 1);
}(),

//异常提示
error = function(msg){
  win.console && console.error && console.error('Lmy hint: ' + msg);
};

Lmy.fn.init = function(elem){
	var $ = this,options = $.options;		//防干扰
	$.show(elem);
	$.click(elem);
};

//照片展示
Lmy.fn.show = function(elem){
	var $ = this,options = $.options;
	var data = $.ajax(options.path,options.method);
	console.log(data);
	
}

//点击放大
Lmy.fn.click = function(){

}

//ajax 请求
Lmy.fn.ajax = function(url,method){
	var xmlhttp = null;
	if (window.XMLHttpRequest){
	    //  IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
	    xmlhttp = new XMLHttpRequest();
	}
	else{
	    // IE6, IE5 浏览器执行代码
	    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function(){
  		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
    		var result = JSON.parse(xmlhttp.responseText);
    	}else{
    		error(xmlhttp.status);
    		var result = {xmlhttp.status};
    	}
  	}
	method = method || "GET";
	xmlhttp.open(method,url,true);
	if(method == "POST"){
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	}
	xmlhttp.send();
	return result;
}

//暴露接口
win.lmyshow = function(options){
	options = options || {};
	lmy = new Lmy(options);
	var elem = doc.getElementById(options.elem);
	lmy.init(elem); 
};

}(window);