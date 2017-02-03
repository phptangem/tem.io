<?php
class RedisController extends CControl{
	public function __construct(){
		$this->shopcache = Tem::app()->lib('Shopcache');
	}
	public function add(){
		// $userinfo = array(
		// 	'name' => 'tangem',
		// 	'age' => 19,
		// 	'sex' => 'male',
		// 	'school'=> 'hzsz'
		// );
		// $this->shopcache->setCache(1,$userinfo,'user');
		$goodsinfo = array(
			'name' => '书包',
			'price' => 99,
			'weight' => '220kg',
			'detail'=> '这是一个书包'
		);
		$this->shopcache->setCache(1,$goodsinfo,'goods');
	}
	public function update(){
		$addition = array(
			'height' => 168,
			'salery' => 9000,
			'mobile' => 133594584258
		);
		$this->shopcache->mergeCache(1,$addition);
	}
	public function del(){
		$this->shopcache->removeCache(1,'goods');
	}
	public function show(){
		$user = $this->shopcache->getUserByUid(1);
		p($user);
	}
}