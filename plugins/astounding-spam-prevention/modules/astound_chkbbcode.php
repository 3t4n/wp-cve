<?php

if (!defined('ABSPATH')) exit;

class astound_chkbbcode extends astound_module { 
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		// searches for bbcodes in post data.
		// bbcodes are the tool of stupid spammers
		$bbcodes=array(
		'[php','[url','[link','[img','[include','[script'
		);
		foreach($post as $key=>$data) {
			foreach($bbcodes as $bb) {
				if (stripos($data,$bb)!==false) {
					return "bbcode $bb in field: $key";
				}
			}
		}
		return false;
	}
}
?>