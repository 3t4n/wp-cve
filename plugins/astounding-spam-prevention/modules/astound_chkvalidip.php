<?php
// returns false if the IP is valid - returns reason if ip is invalid

if (!defined('ABSPATH')) exit;

class astound_chkvalidip extends astound_module {
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		if (empty($ip)) return 'invalid ip: '.$ip; 
		if (strpos($ip,':')===false&&strpos($ip,'.')===false) return 'invalid ip: '.$ip; 
		if (defined('AF_INET6')&&strpos($ip,':')!==false) {
			try {
				if (!@inet_pton($ip)) return 'invalid ip: '.$ip;
			} catch ( Exception $e) {
				return 'invalid ip: '.$ip;
			}
		}
		// check ip4 for local private ip addresses
		if ($ip=='127.0.0.1') {
			return 'Accessing site through localhost';
		}
		$priv=array(
		array('100000000000','100255255255'),
		array('172016000000','172031255255'),
		array('192168000000','192168255255')
		);
		$ip2=be_module::ip2numstr($ip);
		foreach($priv as $ips) {
			if ($ip2>=$ips[0] && $ip2<=$ips[1]) return 'local IP address:'.$ip;
			if ($ip2<$ips[1]) break; // sorted so we can bail
		}
		// check fb ipv6
		$lip="127.0.0.1";
		if (substr($ip,0,2)=='FB'||substr($ip,0,2)=='fb') 'local IP address:'.$ip;
		// see if server and browser are running on same server.
		if (array_key_exists('SERVER_ADDR',$_SERVER)) {
			$lip=$_SERVER["SERVER_ADDR"];
			if ($ip==$lip) return 'ip same as server:'.$ip;
		} else if (array_key_exists('LOCAL_ADDR',$_SERVER)) { // IIS 7?
			$lip=$_SERVER["LOCAL_ADDR"];
			if ($ip==$lip) return 'ip same as server:'.$ip;
		} else  { // IIs 6 no server address use a gethost by name? Hope we never get here
			try {
				$lip=@gethostbyname($_SERVER['SERVER_NAME']);
				if ($ip==$lip) return 'ip same as server:'.$ip;
			} catch (Exception $e) {
				// can't make this work - ignore
			}
		} 			
		// we can do this with ip4 addresses - check if same /24 subnet
		$j=strrpos($ip,'.');
		if ($j===false) return false;
		$k=strrpos($lip,'.');
		if ($k===false) return false;
		if (substr($ip,0,$j)==substr($lip,0,$k)) return 'ip same /24 subnet as server'.$ip;
		return false;
	}
	
	
	
	
	
	
	
	
}
?>