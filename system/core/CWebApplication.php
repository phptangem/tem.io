<?php

/**
 * Web应用类，从Web请求进来主要有此类负责调用相关程序
 */
class CWebApplication extends CApplication{

	/**
     * 处理应用请求
     */

	public function processRequest(){
		$router = $this->getRouter();
		$uri = $this->getUri();
		$router->setUri($uri);
		$router->parse();
		echo 1;die;
		$controller = $router->createController();
		$method = $uri->uri_method();
		if(! method_exists($controller, $method)){
			$controller->$method();
		}else{
			echo "$controller/$method does not exists!!!";
		}
	}

	/**
	 * 加载特定的控制器
	 * @param $control string ，CONTROL_PATH开始的相对路径
	 * @param $method string 指定控制器要执行的方法
	 */

	public function processAction($control, $method = 'index'){

	}

	/**
     * 注册核心组件类
     */

	public function registerCoreComponents(){
		parent::registerCoreComponents();
	}
}