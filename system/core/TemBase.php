<?php

/**
*TemBase类文件
*/
//定义项目请求开始时间
defined('TEM_BEGIN_TIME') or define('TEM_BEGIN_TIME',microtime(true));
//定义项目当前时间
defined('SYSTEM') or define('SYSTEM', time());
//系统类文件路径
define('SYS_PATH', S_ROOT.'system/');
//组件类文件路径
define('COMPONENT_PATH', S_ROOT.'components/');
//配置文件路径
define('CONFIG_PATH', S_ROOT.'config/');
//helper文件路径
define('HELPER_PATH', S_ROOT.'helper/');
//视图文件路径
define('VIEW_PATH', S_ROOT.'views/');
//模型文件路径
define('MODEL_PATH', S_ROOT.'models/');
//控制器文件路径
define('CONTROL_PATH', S_ROOT.'controllers/');
//第三方类库文件路径
define('LIB_PATH', S_ROOT.'lib/');
//缓存文件路径
define('CACHE_PATH', S_ROOT.'cache/');
class TemBase{
	private static $_app;		//应用程序引用变量
	private static $_logger;	//本地日志类引用变量

	/**
     * @var array 核心类路径对应表 
     */
	private static $_coreClasses = array(
		'CApplication' => 'core/CApplication.php',
		'CControl' => 'core/CControl.php',
		'CWebApplication' => 'core/CWebApplication.php',
		'CComponent' => 'core/CComponent.php',
		'CRouter' => 'core/CRouter.php',
		'CUri' => 'core/CUri.php',
		'CInput' => 'core/CInput.php',
		'CCache'=>'cache/CCache.php',
		'CDb'=>'db/CDb.php'
	);

    /**
     * 创建对象实例
     * @param string $classname创建的对象类名称
     * @param string $config配置文件路径
     * @return object 新创建的对象实例引用
     */

	public static function createApplication($classname, $config){
		return new $classname($config);
	}

	/**
     * 创建网页Application类
     * @param string $config 配置文件路径
     * @return object Application实例引用
     */

	public static function createWebApplication($config){
		return self::createApplication('CWebApplication', $config);
	}
	/**
     * 返回当前应用实例
     * @return object 当前应用实例
     */
	public static function app(){
		return self::$_app;
	}
	/**
     * 设置当前实例
     * @param object $app 当前实例引用
     */
	public static function setApplication($app){
		self::$_app = $app;
	}

	/**
     * 自动加载类方法
     * @param string $classname类名
     */

	public static function autoload($classname){
		if(isset(self::$_coreClasses[$classname])){
			require_once SYS_PATH.self::$_coreClasses[$classname];
		}else{
			//类文件不存在,日志记录
		}
	}
	
	/**
	*错误处理方法，收集所有的错误信息并记录
	*/

	public static function tem_error_handler($error_level, $error_message, $error_file, $error_line, $error_context){
		$uri = $_SERVER['REQUEST_URI'];
		log_message( "error_level:$error_level error_message:$error_message error_file:$error_file error_line:$error_line uri:$uri");
	}
}
//注册类自动加载方法
spl_autoload_register(array('TemBase','autoload'));