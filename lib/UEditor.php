<?php
//百度UEditor封装类
class UEditor{
	public function createUEditor(){
		$imgUploadApi = "/uploadimage/index.html";
		$str  = '<form action="server.php" method="post">';
		$str .= '<script id="container" name="content" type="text/plain">';
		$str .= '这里写你的初始化内容';
		$str .= '</script>';
		$str .= ' </form>';
		$str .= '<script type="text/javascript" src="/lib/UEditor/ueditor.config.js"></script>';
		$str .= '<script type="text/javascript" src="/lib/UEditor/ueditor.all.js"></script>';
		$str .= '<script type="text/javascript">';
		$str .= 'var editor = UE.getEditor("container",{serverUrl:"'.$imgUploadApi.'"});';
		$str .= '</script>';
		echo $str;
	}
}