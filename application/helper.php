<?php
//------------------------
// ThinkPHP 扩展助手函数 不建议使用
//-------------------------
use think\Cache;
use think\Config;
use think\Db;
use think\Loader;
use think\Url;

if (!function_exists('M')) {
    /**
     * 兼容以前3.2的单字母单数 M
     * @param string $name 表名     
     * @return DB对象
     */
    function M($name = '')
    {
        if(!empty($name))
        {          
            return Db::name($name);
        }                    
    }
}

if (!function_exists('D')) {
    /**
     * 兼容以前3.2的单字母单数 D
     * @param string $name 表名     
     * @return DB对象
     */
    function D($name = '')
    {               
        $name = Loader::parseName($name, 1); // 转换驼峰式命名
        if(file_exists(APP_PATH."/index/model/$name.php"))
            $class = '\app\index\model\\'.$name;
        elseif(file_exists(APP_PATH."/api/model/$name.php"))                 
            $class = '\app\api\model\\'.$name;     
        elseif(file_exists(APP_PATH."/admin/model/$name.php"))
            $class = '\app\admin\model\\'.$name;                      
                                                    
        if($class)
        {
            return new $class();
        }            
        elseif(!empty($name))
        {          
            return Db::name($name);
        }                    
    }
}

if (!function_exists('U')) {
    /**
     * 兼容以前3.2的单字母单数 M
     * URL组装 支持不同URL模式
     * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
     * @param string|array $vars 传入的参数，支持数组和字符串
     * @param string|boolean $suffix 伪静态后缀，默认为true表示获取配置值
     * @param boolean $domain 是否显示域名
     * @return string
     */
    function  U($url='',$vars='',$suffix=true,$domain=false) 
    {
       return Url::build($url, $vars, $suffix, $domain);
    }
}
 
if (!function_exists('S')) {
    /**
     * 兼容以前3.2的单字母单数 S 
    * @param mixed $name 缓存名称，如果为数组表示进行缓存设置
    * @param mixed $value 缓存值
    * @param mixed $options 缓存参数
    * @return mixed
    */
   function S($name,$value='',$options=null) {
       if(!empty($value))
            Cache::set($name,$value,$options);
       else
           return Cache::get($name);
   }
}

if (!function_exists('C')) {
/**
 * 兼容以前3.2的单字母单数 S 
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
    function C($name=null, $value=null,$default=null) {
        return config($name);
   }   
}
