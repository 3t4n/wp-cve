<?php
/**********************************************
* Modified be_module class from stop spammers
* Used to load the checks
***********************************************/
if (!defined('ABSPATH')) exit;

class astound_module { 
	public $searchname='Class default';
	public $searchlist=array();
	public function process($ip,&$stats=array(),&$options=array(),&$post=array())  {
		return searchList($this->searchName,$ip,$this->searchlist,$ip);
	}	
}

?>