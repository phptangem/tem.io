<?php

defined('IN_TEM') or exit();

/**
*
*URI资源解析类
*
*/
class CUri{
	private $segments = array(); //地址请求分组字段
	private $default_url_mode = 'AUTO'; //默认url解析模式
	private $route = array(); //路由配置数组
	private $domain = ''; //访问域
	private $directory = ''; // 访问控制器文件夹
	private $control = ''; //访问控制器
	private $method = ''; //访问控制器方法名
	private $query_string = ''; //请求QUERY_STRING ，由于涉及到伪静态，所以要自行构造，如http://www.ebanhui.com/login.html?return_url=http://www.ebanhui.com&type=inajx 则query_string为return_url=http://www.ebanhui.com&type=inajx
	private $curdomain = '';	//当前访问的主域名,如 xiaoxue.ebh.net 则为 ebh.net
    private $itemid = 0;    //详情页ID
    private $page = 0;  //请求分页
    private $sortmode = 0;  //排序方式
    private $viewmode = 0;  //显示方式  
    private $attribarr = array();   //其他请求数组
    private $codepath = ''; //代码路径，如http://www.ebanhui.com/troom/setting-0-0-0-1.html 那么代码路径则为troom/setting

    function __construct(){
    	if(isset(Tem::app()->route)){
    		$this->route = Tem::app()->route;
    	}else{
    		$this->route = array(
    			'url_mode' => $this->default_url_mode,
    			'domain' => ''
    		);
    	}
    }

    /**
     * 返回控制器名称
     */
    function uri_control(){
    	return $this->control;
    }

    /**
     * 返回控制器方法
     */
    function uri_method(){
    	return $this->method;
    }

    /**
    * 解析uri分段信息，将uri字符分成segments数组
    */
    function parse_uri(){
    	if(!isset($this->path)){
    		$this->detect_uri();
    	}
    	return $this->_parse_uri($this->path);
    }

    /**
    * 检测uri参数
    */
    function detect_uri(){
    	$path = '';
    	if($this->route['url_mode'] == 'AUTO'){//自动检测
    		$path = $this->_auto_detect_uri();
    	}elseif($this->route['url_mode'] == 'QUERY_STRING'){
    		$path = $_SERVER['REQUEST_URI'];
    	}elseif($this->route['url_mode'] == 'PATH_INFO'){
    		$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
    	}
    	if(substr($path, 0, 1) == '/'){
    		$path = substr($path, 1);
    	}
    	if(isset($this->route['suffix']) && ($pathi = stripos($path, $this->route['suffix'].'?')) !== false){
    		$spath = $path;
    		$path = substr($spath,0,$pathi);
    	}
    }
}