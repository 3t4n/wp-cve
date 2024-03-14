<?php
class astound_chkwlist extends astound_module{ 
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		if (!array_key_exists('wlist',$options)) return false;
		$wlist=$options['wlist'];
		if (empty($wlist)||!is_array($wlist)) return false;
		if (in_array($ip,$wlist)) return 'White List IP';
		return false;
		
		// return false if OK, return a reason if not.
	}
}



?>
