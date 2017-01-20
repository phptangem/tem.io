<?php
function p(){
	$params = func_get_args();
	echo "<pre>";
	foreach ($params as $param) {
		print_r($param);
		echo "<br>";
	}
	die;
}
function show_404(){
	$view = 'common/error404';
	$viewpath = VIEW_PATH.$view.'.php';
	include $viewpath;
}
function log_message($msg, $level = 'error', $php_error = false){
	Ebh::app()->getLog()->log($msg, $level, $php_error);
}
//数据安全过滤
function safefilter($datas){
	if(empty($datas)){
		return $datas;
	}
	if(is_array($datas)){
		foreach ($datas as &$data) {
			$data = safefilter($data);
		}
	}else{
		$datas = h($datas);
	}
	return $datas;
}
//获取安全html
function h($text, $tags = null) {
    $text   =   trim($text);
    //完全过滤注释
    $text   =   preg_replace('/<!--?.*-->/','',$text);
    //完全过滤动态代码
    $text   =   preg_replace('/<\?|\?'.'>/','',$text);
    //完全过滤js
    $text   =   preg_replace('/<script?.*\/script>/','',$text);

    $text   =   str_replace('[','&#091;',$text);
    $text   =   str_replace(']','&#093;',$text);
    $text   =   str_replace('|','&#124;',$text);
    //过滤换行符
    $text   =   preg_replace('/\r?\n/','',$text);
    //br
    $text   =   preg_replace('/<br(\s*\/)?'.'>/i','[br]',$text);
    $text   =   preg_replace('/<p(\s*\/)?'.'>/i','[p]',$text);
    $text   =   preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
	$text   =   str_replace('font','{f{o{n{t{',$text);
	$text   =   str_replace('decoration','{d{e{c{o{r{a{t{i{o{n{',$text);
	$text   =   str_replace('<strong>','{s{t{r{o{n{g{',$text);
	$text   =   str_replace('</strong>','}s{t{r{o{n{g{',$text);
	$text   =   str_replace('background-color','{b{a{c{k{g{r{o{u{n{d{-{c{o{l{o{r',$text);
	

    //过滤危险的属性，如：过滤on事件lang js
    while(preg_match('/(<[^><]+)(on(?=[a-zA-Z])|lang|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1],$text);
    }
    while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1].$mat[3],$text);
    }
    if(empty($tags)) {
        $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a|span|input|h1|h2|h3|h4|h5';
    }
    //允许的HTML标签
    $text   =   preg_replace('/<('.$tags.')( [^><\[\]]*)?>/i','[\1\2]',$text);
    $text = preg_replace('/<\/('.$tags.')>/Ui','[/\1]',$text);
    //过滤多余html
    $text   =   preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|pre)[^><]*>/i','',$text);
    //过滤合法的html标签
    while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
        $text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
    }
    //转换引号
    while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
    }
    //过滤错误的单个引号
    while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
        $text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
    }
    //转换其它所有不合法的 < >
    $text   =   str_replace('<','&lt;',$text);
    $text   =   str_replace('>','&gt;',$text);
    $text   =   str_replace('"','&quot;',$text);
     //反转换
    $text   =   str_replace('[','<',$text);
    $text   =   str_replace(']','>',$text);
    $text   =   str_replace('&#091;','[',$text);
    $text   =   str_replace('&#093;',']',$text);
    $text   =   str_replace('|','"',$text);
    //过滤多余空格
    $text   =   str_replace('  ',' ',$text);
	$text   =   str_replace('{f{o{n{t{','font',$text);
	$text   =   str_replace('{s{t{r{o{n{g{','<strong>',$text);
	$text   =   str_replace('}s{t{r{o{n{g{','</strong>',$text);
	$text   =   str_replace('{d{e{c{o{r{a{t{i{o{n{','decoration',$text);
	$text   =   str_replace('{b{a{c{k{g{r{o{u{n{d{-{c{o{l{o{r','background-color',$text);
    //剔除class标签属性
    $text = preg_replace_callback('/<.*?(class\=([\'|\"])(.*?)(\2)).*?>/is', function($grp){
        return str_ireplace($grp[1], '', $grp[0]);
    }, $text);
    //抹去所有外链接
    $text = replace_Links($text);
    return $text;
}