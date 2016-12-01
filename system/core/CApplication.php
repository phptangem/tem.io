<?php

/**
 * 应用类基类，集成此类的有 CWebApplication和CConsoleApplication等
 */
abstract class CApplication{
	private $_helpers = array(); 	//已加载辅助方法库
	private $_components = array();	//已加载组件类
	private $_classes = array();	//已加载class类
	private $_models = array();		//已加载model类

	/**
     * 处理请求抽象方法
     */

	abstract public function processRequest();

	/**
	*运行访问控制器
	* @param $control string 控制器
	* @param $action string 控制器方法
	*/

	abstract public function processAction($control, $action = 'index');

	public function __construct($config = null){
		Tem::setApplication($this);
		if(is_string($config)){
			$config = require $config;
		}
		$this->configure($config);
		$this->init();
	}
	/**
    * 启动应用程序
    */
    public function run(){
        $this->processRequest();
    }
	 /**
     * 初始化应用
     */
	public function init(){
		//加载helper
		// $r = new ReflectionClass($this);
		// print_r($r->getProperties());die;
		foreach ($this->auto_helper as $helper) {
			$this->helper($helper);
		}
		//加载数据库应用
		if($this->db['autoload']){
			$this->getDb();
		}
		$this->registerCoreComponents();
	}
	/**
    * 将配置数组分解成key value形式
    * @param array $config 配置数组
    */
     public function configure($config){
     	if(is_array($config)){
     		foreach ($config as $key => $value) {
     			$this->$key = $value;
     		}
     	}
     }

    /**
    * 加载辅助方法
    * @param string $helpername 辅助方法库方法
    */
    public function helper($helpername){
    	if(!isset($this->_helpers[$helpername])){
    		require HELPER_PATH.$helpername.'.php';
    		$this->_helpers[$helpername] = TRUE;
    	}
    }
    /**
     * 返回DB类
     */

    public function getDb(){
    	if(isset($this->_classes['db'])){
    		return $this->_classes['db'];
    	}
    	$db = new CDb($this->db);
    	$this->_classes['db'] = $db;
    	return $db;
    }
    /**
     * 注册核心组件类
     */
    public function registerCoreComponents(){
    	$components = array(
    		'user' => 'CUser'
    	);
    	$this->setComponents($components);
    }
    /**
    * 批量注册组件类
    */
    public function setComponents($components){
    	foreach ($components as $key => $component) {
    		$this->setComponent($key, $component);
    	}
    }
    /**
    * 注册单个组件类
    */
    public function setComponent($key, $component){
    	if(isset($this->_components[$key]))
    		return $this->_components[$key];
    	$componentpath = COMPONENT_PATH.$component.'.php';
    	if(!file_exists($componentpath)){
    		echo "component ".$component." is not exists";
    		exit();
    	}
    	require $componentpath;
    	$this->_components[$key] = new $component;
    	return $this->_components[$key];
    }
	
	/**
     * 加载model类
     * @param string $modelname 模板名称
     * @return object model对象
     */
	public function model($modelname){
		$modelname = ucfirst(strtolower($modelname));
		if(isset($this->_models[$modelname]))
			return $this->_models[$modelname];
		$modelclass = $modelname.'Model';
		$modelpath = MODEL_PATH.$modelclass.'.php';
		if(!file_exists($modelpath)){
			echo "error:model file not exists:".$modelpath;
		}
		require $modelpath;
		$this->_models[$modelname] = new $modelclass;
		return $this->_models[$modelname];
	}

	/**
    * 返回CRouter路由类
    * @return object 
    */
    public function getRouter(){
    	if(isset($this->_classes['CRouter'])){
    		return $this->_classes['CRouter'];
    	}
    	$router = new CRouter;
    	$this->_classes['CRouter'] = $router;
    	return $router;
    }

    /**
     * 返回CUri类
     * @return object 
     */
    public function getUri(){
    	if(isset($this->_classes['CUri']))
    		return $this->_classes['CUri'];
    	$curi = new CUri;
    	$this->_classes['CUri'] = $curi;
    	return $curi;
    }
}