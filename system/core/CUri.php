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
    function _parse_uri($uri){
        if(!empty($uri)){
            $this->segments = explode('/', $uri);
        }
        $segcount = count($this->segments);
        if($segcount < 1){ //default controller 
            if($this->domain == 'www' || $this->domain == ''){
                $this->directory = $this->route['directory'];
            }
            $this->control = empty($this->route['default']) ? 'index':$this->route['default'];
            $this->method = 'index';
            $this->codepath = '';
        }else{
            $lastseg = $this->segments[$segcount - 1];
            $firstseg = $this->segments[0];
            if($segcount == 1){
                $firstseg = $this->_parse_uri_attr($seg);
                if(is_numeric($firstseg)){
                    $this->control = empty($this->route['default']) ? 'index' : $this->route['default'];
                    $this->method = 'view';
                }else{
                    if($firstseg == 'index'){
                        if($this->domain == 'www' || $this->domain == '') {
                            $this->directory = $this->route['directory'];
                        }
                        $this->control = empty($this->route['default']) ? 'index' : $this->route['default'];
                    }else{//如http://ss.ebanhui.com/troom.html 形式
                        if(is_dir(CONTROL_PATH.$firstseg)){
                            $this->directory = $firstseg;
                            $this->control = empty($this->route['default']) ? 'index' : $this->route['default'];
                        }else{
                            $this->control = $firstseg;
                        }
                    }   
                    $this->method = 'index';
                }
                $this->codepath = $firstseg;
            }else{
                for($i = 0; $i < $segcount-1; $i++){
                    if($i == 0 && file_exists(CONTROL_PATH.$firstseg)){
                        $this->directory = $firstseg;
                    }else{
                        if(empty($this->control))
                            $this->control = $this->segments[$i];
                        else
                            $this->method .= $this->segments[$i].'_';
                    }
                    $this->codepath .= (empty($this->codepath) ? $this->segments[$i] : '/'.$this->segments[$i]);
                }
                if(empty($this->control)){
                     $this->control = $this->_parse_uri_attr($this->segments[$segcount - 1]);   //处理最后列表属性等
                     $this->codepath .= '/'.$this->control;
                     $this->method = 'index';
                }else{
                    $lastseg = $this->_parse_uri_attr($lastseg);
                    if (is_numeric($lastseg)) {
                        $this->method = $this->method . 'view';
                        $this->itemid = $lastseg;
                    } else {
                        $this->method = $this->method . $lastseg;
                        $this->codepath .= '/'.$lastseg;
                    }
                }
            }
        }
        return $this->segments;
    }
    /**
     * 解析uri段成为uri属性
     * @param string $seg uri段
     */
    function _parse_uri_attr($seg){
        $attarr = explode('-', $seg);
        $attcount = count($attarr);
        if($attcount <=1){
            return $seg;
        }
        if($attcount > 1){
            $this->page = $attarr[1];
        }
        if($attcount > 2){
            $this->sortmode = $attarr[2];
        }
        if($attcount > 3){
            $this->viewmode = $attarr[3];
        }
        $this->attribarr = array_slice($attarr, 4);
        return $attarr[0];
    }
    /**
    * 检测uri参数
    */
    function detect_uri(){
    	$path = '';
        if ($this->route['url_mode'] == 'AUTO') {    //自动检测
            $path = $this->_auto_detect_uri();
        } else if ($this->route['url_mode'] == 'QUERY_STRING') {
//            $path = $_SERVER['QUERY_STRING'];
            $path = $_SERVER['REQUEST_URI'];
        } else if ($this->route['url_mode'] == 'PATH_INFO') {
            $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
        }
        if (substr($path, 0, 1) == '/') {
            $path = substr($path, 1);
        }
        if (isset($this->route['suffix']) && ($pathi = stripos($path,$this->route['suffix'].'?')) !== FALSE) {
            $spath = $path;
            $path = substr($spath, 0,$pathi);
            $this->query_string = substr($spath, $pathi + strlen($this->route['suffix']) + 1);
        }
        if (isset($this->route['suffix']) && substr($path, strlen($path) - strlen($this->route['suffix'])) == $this->route['suffix']) {
            $path = substr($path, 0, strlen($path) - strlen($this->route['suffix']));
        }
        if (substr($path, 0, 1) == '?') {
            $this->query_string = substr($path,1);
            $path = '';
        }
        $this->path = $path;
        $domain = $this->_detect_domain();
        $this->domain = $domain;
        return $path;
    }
    //自动检测uri参数
    function _auto_detect_uri(){
        $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
        IF(empty($path)){
            $path = $_SERVER['QUERY_STRING'];
        }
        return $path;
    }
    /**
    *获取当前二级域名主机信息，如 wl.sy.ebanhui.com 那就为 wl.sy
    */
    function _detect_domain(){
        $SERVER_NAME = empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
        $host = $this->getHostDomainByServer($SERVER_NAME);
        if(substr($SERVER_NAME,-6) == 'tem.io'){
            $domain = substr($SERVER_NAME, 0, strlen($SERVER_NAME) - strlen($host));
            if(!empty($domain)){
                $domain = substr($domain, 0, strlen($domain) - 1);
            }
        }elseif(! empty($this->route['alonedomain'])){//自定义域名的处理
            $this->curdomain = $SERVER_NAME;
            $host = $SERVER_NAME;
            $domain = Tem::app()->lib('Classroom')->getDomainByFullDomain($SERVER_NAME);
        }
        $domain = strtolower($domain);
        $this->curdomain = $host;
        return $domain;
    }
    /*
    *获取当前的以及域名，如 wl.sy.ebanhui.com 那就为 ebanhui.com sy.ebh.net 则为ebh.net
    */
    function getHostDomainByServer($server_name){
        $slist = explode('.', $server_name);
        if(empty($slist) || count($slist) < 2){
            return "";
        }
        $seglen = count($slist);
        if(is_numeric($slist[$seglen-1])){
            return "";
        }
        $host = $slist[$seglen - 2].'.'.$slist[$seglen-1];
        return strtolower($host);
    }
    /**
     * 返回控制器所在文件夹
     */
    function uri_directory(){
        return $this->directory;
    }
    /**
     * 获取请求对应
     * @return string 
     */
    public function uri_query_string(){
        return $this->query_string;
    }
     /**
     * 返回解析后的域名信息
     */
    function uri_domain() {
        return $this->domain;
    }
}