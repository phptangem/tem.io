<?php
/**
 * Description of CModel
 */
class CModel{
	private $db = NULL;
	function __construct(){
		$this->db = Ebh::app()->getDb();
	}
}