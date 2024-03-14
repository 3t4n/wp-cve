<?PHP
/*******************************************
* Class utilities - load, search, compare.
*
*******************************************/
if (!defined('ABSPATH')) exit;

/* load and execute a astound class module. */
function astound_load_module($module,$ip="") {
	astound_errorsonoff();
	$options=astound_get_options();
	$stats=astound_get_stats();
	$post=astound_get_post();
	if (empty($ip)) $ip=astound_get_ip();
	$ansa='';
	try {
		$ansa= astound_load($module,$ip,$stats,$options,$post);
	} catch (Exception $e) {
		astound_log("Error $e");
	}
	astound_errorsonoff('off');
	return $ansa;
}
function astound_load($file,$ip,&$stats=array(),&$options=array(),&$post=array()) {
	if (empty($file)) {
		return false;
	}
	/* load the base class **/
	if (!class_exists('astound_module')) {
		astound_require('modules/astound_module.class.php');
	} 
	/* instanciate the  class */
	$ppath=ASTOUND_PLUGIN_FILE.'modules/';
	$fp=$ppath.$file.'.php';
	$fp=str_replace("/",DIRECTORY_SEPARATOR,$fp); // windows fix
	if (!astound_file_exists("$fp")) {
		return false;
	}
	$class=astound_class($file);
	$result=$class->process($ip,$stats,$options,$post);
	$class=null;
	unset($class); // doesn't do anything
	//memory_get_usage(true); // force a garage collection
	return $result;
}
	/*********************************************
	* searchList search for a string in a list.
	* 1) simple search string in string
	* 2) IP CIDR search e.g. 1.2.3.4/16
	* 3) wild card search
	* 4) partial match on any word.
	*********************************************/
	function searchList($searchName,$needle,&$haystack) { 
		// searches an array for an ip or an email 
		// simple search array no key
		if (!is_array($haystack)) return false;
		$needle=strtolower($needle);
		if (empty($needle)) return false;
		foreach ($haystack as $search) { // haystack is a list of names or emails, possibly with wildcards
			$search=trim(strtolower($search));
			$reason=$search;
			/* 1) simple string in string */
			if (empty($search)) continue; // in case there is a null in the list
			if ($needle==$search) {
				return "$searchName:$needle";
			} 
			/* 2) ip CIDR search */
			if (substr_count($needle,'.')==3 && strpos($search,'.')!==false && strpos($search,'/')!==false ) {
				// searching for an cidr in the list	
				list($subnet, $mask) = explode('/', $search);
				$x2=ip2long($needle) & ~((1 << (32 - $mask)) - 1);
				$x3=ip2long($subnet)& ~((1 << (32 - $mask)) - 1);

				if ($x2 == $x3){ 
					return "found $searchName: $needle in $search";
				}
			}
			/* 3) check for wildcard - both email and ip */
			if (strpos($search,'*')!==false || strpos($search,'?')!==false ) {
				// new wild card search
				if (wildcard_match($search,$needle))  return "$searchName:$reason:$needle";			
				continue;
			}
			// check for partial both email and ip
			if (strlen($needle)>strlen($search)) {
				$n=substr($needle,0,strlen($search));
				if ($n==$search) return "$searchName:$reason";
			}
		}	
		return false;
	}
/*********************************************************************************
	* Matches wilcards on string or array
	* $pattern in wilcarded pattern with ? counted as single character
	* and * as multiple characters
	* if $value is string, returns true/false
	* if $value is an array, returns matches strings from array
	* @param string $pattern
	* @param string $value
	* @return bool|array
	* borrowed from andrewtch at
	* https://github.com/andrewtch/phpwildcard/blob/master/wildcard_match.php
**********************************************************************************/
	function wildcard_match($pattern, $value) {
		if(is_array($value)) {
			$return = array();
			foreach($value as $string) {
				if(wildcard_match($pattern, $string)) {
					$return[] = $string;
				}
			}
			return $return;
		}
		//split patters by *? but not \* \?
		$pattern = preg_split('/((?<!\\\)\*)|((?<!\\\)\?)/', $pattern, null,
		PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
		foreach($pattern as $key => $part) {
			if($part == '?') {
				$pattern[$key] = '.';
			} elseif ($part == '*') {
				$pattern[$key] = '.*';
			} else {
				$pattern[$key] = preg_quote($part);
			}
		}
		$pattern = implode('', $pattern);
		$pattern = '/^'.$pattern.'$/';
		return preg_match($pattern, $value);
	}	
	function ip2numstr($ip) {
		if(long2ip(ip2long($ip))!=$ip) return false;
		list($b1,$b2,$b3,$b4)=explode('.',$ip);
		$b1=str_pad($b1,3,'0',STR_PAD_LEFT);
		$b2=str_pad($b2,3,'0',STR_PAD_LEFT);
		$b3=str_pad($b3,3,'0',STR_PAD_LEFT);
		$b4=str_pad($b4,3,'0',STR_PAD_LEFT);
		$s=$b1.$b2.$b3.$b4;
		return $s;
	}
	function ipListMatch($searchName,$searchlist,$ip)  {
		// does a match agains a list of ip addresses
		$ipt=ip2numstr($ip);
		foreach($searchlist as $c) {
			if (!is_array($c)) {
				// this might be a cidr
				if (substr_count($c,'.')==3) {
					if (strpos($c,'/')!==false) {
						// cidr
						$c=cidr2ip($c);
					} else {
						// single ip
						$c=array($c,$c);
					} 
				}
				if (!is_array($c)) {
					$searchname=$c;
				}
			} 
			if (is_array($c)) {
				list($ips,$ipe)=$c;
				if (strpos($ips,'.')===false&&strpos($ips,':')===false) { // new numstr format
					if ($ipt<$ips) return false;
					if ($ipt>=$ips&&$ipt<=$ipe) {
						return "$searchname: $ip in $c";
					}
				} else if (strpos($ips,':')!==false) { // IPV6
					if ($ip>=$ips && $ip<=$ipe) {
						return $searchname.': '.$ip;
					} 
				} else {
					$ips=ip2numstr($ips);
					$ipe=ip2numstr($ipe);
					if ($ipt>=$ips && $ipt<=$ipe) {
						if ( is_array($ip)) {
							$ip=$ip[0];
						}
						return $searchname.': '.$ip;
					} 
				}
			}
		}
		return false;
	}
	function getafile($f,$method='GET') {
		// try this using Wp_Http
		if( !class_exists( 'WP_Http' ) )
		include_once( ABSPATH . WPINC. '/class-http.php' );
		$request = new WP_Http;
		$parms=array();
		$parms['timeout']=10; // bump timeout a little we are timing out in google
		$parms['method']=$method;
		$result = $request->request( $f ,$parms);
		// see if there is anything there
		if (empty($result)) return '';
		
		if (is_array($result)) {
			$ansa=$result['body']; 
			return $ansa;
		}
		if (is_object($result) ) {
			$ansa='ERR: '.$result->get_error_message();
			return $ansa; // return $ansa when debugging
			//return '';
		}
		return '';
	}

	function getSname() {
		// gets the module name from the url address line
		$sname='';
		if(isset($_SERVER['REQUEST_URI'])) $sname=$_SERVER["REQUEST_URI"];	
		if (empty($sname)) {
			$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
			$sname=$_SERVER["SCRIPT_NAME"];	
			if($_SERVER['QUERY_STRING']) {
				$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
			}
		}
		if (empty($sname)) {
			$sname='';
		}
		return $sname;
	}

	function cidr2ip($cidr) { // returns numstr
		if (strpos($cidr,'/')===false) return false;
		list($ip,$bits) = explode('/', $cidr);
		$ip=fixip($ip); // incase the wrong number of dots
		if ($ip===false) return false;
		$start=$ip;
		$end = ip2long($ip);
		$end=sprintf("%u", $end);
		$end1=$end+0;
		$num = pow(2, 32 - $bits)-1;
		$end=($end+0) | $num;
		$end=$end+1;
		$end2=long2ip($end);
		$start=cidrStart2str($start,$bits);
		return array($start, $end2);
	}

	function cidr2str($ipl,$bits) {
		// finds end range for a numstr input
		$ipl=ip2long($ipl);
		$ipl=sprintf("%u", $ipl);
		$num = pow(2, 32 - $bits) -1;
		$ipl=$ipl+0;
		$ipl=$ipl | $num;
		$ipl++;
		return long2ip($ipl);
	}
	function fixip($ip) {
		// checks ip for right number of zeros
		$ip=trim($ip);
		if (empty($ip)) return false;
		if (strpos($ip,'.')===false) return false;
		if (count(explode('.',$ip))==2) $ip.='.0.0';
		if (count(explode('.',$ip))==3) $ip.='.0';
		if(long2ip(ip2long($ip))!=$ip) return false;
		return $ip;
	}
	function cidrStart2str($ipl,$bits) {
		// finds end range for a numstr input
		$ipl=ip2long($ipl);
		$ipl=sprintf("%u", $ipl);
		$num = pow(2, 32 - $bits) -1;
		//echo decbin($num).'<br>';
		$ipl=$ipl+0;
		//echo decbin($ipl).'<br>';
		$z=pow(2,33)-1;
		//echo 'z'.decbin($z).'<br>';
		$z=$num^$z; // 10000000000000000000000000000 xor 0000000000000000000011111 = 011111111111111111111111100000
		//echo 'z2'.decbin($z).'<br>';
		$ipl=$ipl & $z;
		return long2ip($ipl);
	}	
	function searchcache($searchname,$needle,&$haystack) { // array in haystack is ip=>reason
		// searches an array for an ip or an email - uses wildcards, short instances and cidrs
		// the wlist array is of the form $time->ip
		if (!is_array($haystack)) return false;
		$needle=strtolower($needle);
		foreach ($haystack as $search=>$reason) {
			$search=trim(strtolower($search));
			if (empty($search)) continue; // in case there is a null in the list
			if ($needle==$search) {
				return "$searchname:$needle";
			} 
			// four kinds of search, looking for an ip, cidr, wildcard or an email
			// check for wildcard - both email and ip
			if (strpos($search,'*')!==false||strpos($search,'?')!==false) {
				if (astound_module::wildcard_match($search,$needle)) return "$searchname:$reason:$needle";			
				//$search=substr($search,0,strpos($search,'*'));
				//if ($search=substr($needle,0,strlen($search))) return "$searchname:$reason";
			}
			// check for partial both email and ip
			if (strlen($needle)>strlen($search)) {
				$n=substr($needle,0,strlen($search));
				if ($n==$search) return "$searchname:$reason";
			}
			if (substr_count($needle,'.')==3 && strpos($search,'/')!==false ) {
				// searching for an cidr in the list
				list($subnet, $mask) = explode('/', $search);
				$x2=ip2long($needle) & ~((1 << (32 - $mask)) - 1);
				$x3=ip2long($subnet)& ~((1 << (32 - $mask)) - 1);
				if ($x2 == $x3){ 
					return "$searchname:$reason";
				}
			}
		}	
		return false;
	}

function astound_get_stats() {
	return array();
}

	function astound_getafile($f,$method='GET') {
		// try this using Wp_Http
		if( !class_exists( 'WP_Http' ) ) {
			include_once( ABSPATH . WPINC. '/class-http.php' );
		}
		$request = new WP_Http;
		$parms=array();
		$parms['timeout']=10; // bump timeout a little we are timing out in google
		$parms['method']=$method;
		// accept header
		$headers= array();
		$headers['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		$headers['Accept-Encoding']='gzip, deflate, br';
		$headers['Connection']='close';
		$headers['Length']='0';
		$headers['Accept-Language']='en-US,en;q=0.5';
		$headers['Cookie']='astound=4001';
		
		$parms['headers']=$headers;
		$parms['User-Agent']='Mozilla/5.0 (Windows NT 10.0; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0';
		$result = $request->request( $f ,$parms);
		// see if there is anything there
		if (empty($result)) return '';
		
		if (is_array($result)) {
			$ansa=$result['body']; 
			return $ansa;
		}
		if (is_object($result) ) {
			$ansa='ERR: '.$result->get_error_message();
			return $ansa; // return $ansa when debugging
			//return '';
		}
		return '';
	}

?>