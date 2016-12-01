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
	
}