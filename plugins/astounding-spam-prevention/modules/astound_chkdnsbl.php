<?php
if (!defined('ABSPATH')) exit;
class astound_chkdnsbl extends astound_module {
	// zen.spamhaus.org dns list doesn't work on many machines.
	// This is going to cause lookup failures on some machines.
	// it will fill php logs with errors.
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		if (strpos($ip,'.')===false) return false; // no ipv6
		$blacklist = "zen.spamhaus.org";
		$url = implode(".", array_reverse(explode(".", $ip))) . ".". $blacklist;
		if (!function_exists('dns_get_record')) {
			astound_log('dns_get_record does not exist');
			return false;
		}
		$record = dns_get_record($url);
		// look at the record
		if (empty($record)||!is_array($record)) {
			return false; // lookup failed
		}
		$rec=$record[0];
		if (empty($rec)||!is_array($rec)) {
			return false; // lookup failed
		}
		if (array_key_exists('ip',$rec) ) {
			$rip=$rec['ip'];
			// check to see if it is there
			// 127.0.0.2+ is spam
			if (empty($rip)) return false;
			if ($rip<'127.0.0.2') return false;
			return "zen.spamhaus.org reports spam $rip:$ip";
		} else { 
			$rec=$record[1];
			if (array_key_exists('ip',$rec) ) {
				$rip=$rec['ip'];
				// check to see if it is there
				// 127.0.0.2+ is spam
				if (empty($rip)) return false;
				if ($rip<'127.0.0.2') return false;
				return "zen.spamhaus.org reports spam $rip:$ip";
			}
		}
		return false;		
	}
}
?>