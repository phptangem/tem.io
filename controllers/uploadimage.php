<?php
/**
 * 上传图片控制器
 */
 class UploadimageController extends CControl{
 	public function index(){
 		echo 22222222;die;
 		$uptype = $this->input->get('uptype');
        // $type = empty($_REQUEST['type']) ? '' : $_REQUEST['type'];
        $type = $this->input->get('type');
        $editorid= $this->input->get('editorid');
        if(empty($editorid)){
        	$editorid = 'message';
        }
        $upfield = 'upfile';
        if($uptype == 'pic' || $uptype == 'askimage' || $uptype == 'thteam' || $uptype == 'iroom')
            $upfield = 'Filedata';
        $file = $_FILES[ $upfield ];
        $data = array(
        	'uptype'=>$uptype,
        	'type'=>$type,
        	'editorid'=>$editorid
        );
        $cfile = curl_file_create(realpath($file['tmp_name']),$file['type'],$file['name']);
        if($upfield == 'upfile'){
        	$data['upfile'] = $cfile;
        }else{
        	$data['Filedata'] = $cfile;
        }
		$post_url = 'http://up.ebh.net/uploadimage.html?editorid='.$editorid;
		$res = do_post($post_url,$data);
		echo $res;
 	}
 }