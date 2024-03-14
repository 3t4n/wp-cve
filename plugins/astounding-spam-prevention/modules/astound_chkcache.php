<?php
// chk the cache

if (!defined('ABSPATH')) exit;

class astound_chkcache  extends astound_module  {
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		// get the cache
		$cache=get_option('astound_cache');
		if (empty($cache) || !is_array($cache)) {
			return false;
		}
		if (!array_key_exists($ip,$cache)) {
			return false;
		}
		// have a hit.
		$hit=$cache[$ip];
		if (empty($hit) || !is_array($hit)) {
			return false;
		}
		$reason=$hit['reason'];
		if (substr($reason,0,1)=='*') return $reason;
		return '*'.$reason;
	}
}

?>
