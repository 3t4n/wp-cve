<?php

if (!defined('ABSPATH')) exit;

class astound_chkaccept extends astound_module  {
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
	if (array_key_exists('HTTP_ACCEPT',$_SERVER)) return false; // real browsers send HTTP_ACCEPT
		return 'No Accept header;';
	}
}
?>