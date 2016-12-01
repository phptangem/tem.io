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
		'domain' => 'ebanhui.com', //网站主域名
		'suffix' => '.html', //路径后缀
		'default' => 'default', //默认控制器
		'directory' => 'portal', //非www子域名模式下的默认控制器所在文件夹
		'alonedomain' => TRUE
	)
);