<?php
/**
 * 商城缓存操作类
 * @author echo-Huang
 *
 */
 class Shopcache{
 	private $cache = NULL;
 	private $cachelist = array();
 	private $cahceType = 'redis';

 	const _USER_KEY_ = '_user_key_uid';	//用户hashkey
 	const _GOODS_KEY_ = '_goods_key_gid';	//商品hashkey
 	const _CACHE_EXPIRE = 604800;	//默认缓存信息7d天 单位秒s

 	public function __construct(){
 		$this->cache = Tem::app()->getCache();
 	}

 	public function getUserByUid($uid, $fields = array()){
 		$row = $this->getCache($uid, $fields, "user");
 		return $row;
 	}
 	public function getGoodsByGid($gid, $fields = array()){
 		$row = $this->getCache($gid, $fields, 'goods');
 		return $row;
 	}
 	/**
	 * 更新缓存
	 */
 	public function setCache($toid, $param = array(), $type = 'user'){
 		if(empty($param) || empty($toid) || !is_numeric($toid)){
	        return false;
	    }
	    $cacheKey = $this->getCacheKey($toid,$type);
	    $ret = $this->cache->hMset($cacheKey,$param);
	    if($ret){
	        $this->cache->expire($cacheKey, self::_CACHE_EXPIRE);
	    }
	    return $ret;
 	}
 	private function getCache($toid, $fields = array(), $type = 'user'){
 		if(empty($toid)){
 			return false;
 		}
 		$cacheKey = $this->getCacheKey($toid, $type);
 		if(empty($cacheKey)){
 			return false;
 		}
 		if(!empty($fields)){
 			$row = $this->cache->hMget($cacheKey,$fields);
 		}else{
 			$row = $this->cache->hGetAll($cacheKey);
 		}
 		return $row;
 	}	

	/**
	 * 获取缓存key
	 */
 	private function getCacheKey($toid, $type){
 		$key = '';
 		if($type == 'user'){
 			$key = str_replace('uid', $toid, self::_USER_KEY_);
 		}elseif($type == 'goods'){
 			$key = str_replace('gid', $toid, self::_GOODS_KEY_);
 		}
 		return md5($key);
 	}
 	/**
	 * 清空缓存
	 */
	public function removeCache($toid, $type = "user"){
		if(empty($toid) || !is_numeric($toid)){
	        return false;
	    }
	    $cacheKey = $this->getCacheKey($toid,$type);
	    $ret = $this->cache->del($cacheKey);
	    return $ret;
	}
	/**
	 * 追加缓存字段信息到缓存
	 * @param unknown $toid
	 * @param array $param @example array('username'=>'xiaoxue','age'=>20)
	 * @param string $type
	 * @return boolean
	 */
	 public function mergeCache($toid,$param=array(),$type='user'){
	 	 if(empty($toid) || empty($param)){
	        return false;
	    }
	    $cacheKey =  $this->getCacheKey($toid,$type);
	    $ret =  $this->cache->hMset($cacheKey,$param);
	    if($ret){
	        $this->cache->expire($cacheKey, self::_CACHE_EXPIRE);
	    }
	    return $ret;
	 }	
 }