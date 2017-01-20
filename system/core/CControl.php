<?php
/**
 * 控制器基类
 */
class CControl{
	private $_vars = array();
	public $cache = NULL;
	public $input = NULL;
	public function __construct(){
		$this->cache = Tem::app()->getCache();
		$this->input = Tem::app()->getInput();
		$this->uri = Tem::app()->getUri();
	}
	/**
     * 加载model类
     * @param string $modelname 模板名称
     * @return object model对象
     */
	public function model($modelname){
		return Tem::app()->model($modelname);
	}
	/**
     * 给控制器设置对象变量
     * @param string $username 对象名
     * @param object $varobj 对象值
     */
	public function assign($username, $varobj){
		$this->_vars[$username] = $varobj;
	}
	/**
     * 显示模板
     * @param string $view 模板名称
     */
	public function display($view){
		$viewpath = VIEW_PATH.$view.'.php';
		if(!file_exists($viewpath)){
			echo "error view not exists:".$viewpath;
			return ;
		}
		ob_start();
		extract($this->_vars);
		include $viewpath;
		$outputstr = ob_get_contents();
		@ob_end_clean();
		echo $outputstr;
	}
	/**
     * 加载窗口部件
     * @param string $widgetname 部件名称
     * @param mixed $data 传输的数据
     * @param mixed $property 部件属性
     */
	public function widget($widgetname, $data = array(),$property = array()){
		$widgetpath = VIEW_PATH.'widget/'.$widgetname.'.php';
		if(!file_exists($widgetpath)) {
            echo 'error widget not exists:'.$widgetpath;
            return;
        }
        include $widgetpath;
	}
	/**
     * 局部页
     * @param $view view文件位置
     * @param bool $isload　是否直接加载
     * @param null $varpool 导入局部页变量池
     * @return bool|string|void
     */
	public function partial($view, $isload = true, $varpool = null){
		$viewpath = VIEW_PATH.$view.'.php';
		if(!file_exists($viewpath)){
			echo 'error view not exists:'.$viewpath;
			return ;
		}
		if ($isload) {
            include $viewpath;
            return true;
        }
        ob_start();
        include $viewpath;
        $outputstr = ob_get_contents();
        @ob_end_clean();
        return $outputstr;
	}
	 public function get_vars($username) {
        if (!isset($this->_vars[$username])) {
            return null;
        }
        return $this->_vars[$username];
    }
    public function get_title() {
        return Ebh::app()->title;
    }
    public function get_keywords() {
        return Ebh::app()->keywords;
    }
    public function get_description() {
        return Ebh::app()->description;
    }

    public function isPost() {
        if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
            return true;
        }
        return false;
    }
}