<?php
/**
 * 数据库结果集类
 */
class CResult{
	private $resultobj = null;
	public function __construct($obj){
		$this->resultobj = $obj;
	}
	public function row_array(){
		return $thsi->_row_array();
	}
	public function list_array($key = ''){
		return $this->_list_array($key);
	}
	public function list_field($field = ''){
		return $this->_list_field($field);
	}
	public function __destruct(){
		$this->close();
	}
}