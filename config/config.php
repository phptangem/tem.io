<?php

return array(
	'auto_helper' => array(
		'common'
	),
	'db' => array(
		'dbtype' => 'mysql',
		'dbdriver' => 'mysqli',
		'tablepre' => 'ebh_',
		'pconnect' => false,
		'dbcharset' => 'utf8',
		'autoload' => false,
		'dbhost' => '192.168.0.24',
		'dbuser' => 'root',
		'dbport' => '3306',
		'dbpw'	=> '123456',
		'dbname' => 'ebh2',
		'slave' => array()
	),
	'route' => array(
		'url_mode' => 'QUERY_STRING', //路由模式
		'domain' => 'tem.io', //网站主域名
		'suffix' => '.html', //路径后缀
		'default' => 'default', //默认控制器
		'directory' => 'portal', //非www子域名模式下的默认控制器所在文件夹
		'alonedomain' => TRUE
	),
	'log'=>array(
		'log_path'=>'', //日志路径，为空为网站log目录
		'enable'=>true, //启用日志
		'loglevel'=>1 //记录日志级别，大于此级别的日志不予记录
	),
	//cookie设置
	'cookie'=>array(
		'prefix'=>'ebh_',
		'domain'=>'tem.io',
		'alldomain'=>1,//设置此选项代表当前的主域名，级别高于domain
		'path'=>'/'
	),
	'cache'=>array(
		'driver'=>'redis',
        'servers'=>array(
            array('host'=>'127.0.0.1','port'=>6379)
        )
	),
);