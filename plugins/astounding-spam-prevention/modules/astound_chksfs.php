<?php
if (!defined('ABSPATH')) exit;
class astound_chksfs extends astound_module {
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		// only do this with posts that have an email or login
		$query="https://www.stopforumspam.com/api?ip=$ip";
		$check='';
		$check=$this->getafile($query,'GET');
		//astound_log($query);
		//astound_log($check);
		if (empty($check)) return false;
		if ($check=='') return false;
		if (strpos($check,'<appears>yes</appears>')===false) return false;
		$lastseen='';
		$frequency='';
		$n=0;
		if (strpos($check,'<lastseen>',$n)!==false) {
			$k=strpos($check,'<lastseen>',$n);
			$k+=10;
			$j=strpos($check,'</lastseen>',$k);
			$lastseen=date('Y-m-d',time());
			if (($j-$k)>12&&($j-$k)<24) $lastseen=substr($check,$k,$j-$k); // should be about 20 characters
			if (strpos($lastseen,' ')) $lastseen=substr($lastseen,0,strpos($lastseen,' ')); // trim out the time to save room
			if (strpos($check,'<frequency>',$n)!==false) {
				$k=strpos($check,'<frequency>',$n);
				$k+=11;
				$j=strpos($check,'</frequency',$k);
				$frequency='9999';			
				if (($j-$k)&&($j-$k)<7) $frequency=substr($check,$k,$j-$k); // should be a number greater than 0 and probably no more than a few thousand
			}
		}
		// check freq and age - min freq=2 max age = 99
		$sfsfreq=2;
		$sfsage=99;
		// if (!empty($frequency) && !empty($lastseen) && ($frequency!=255) && ($frequency>=$freq) && (strtotime($lastseen)>(time()-(60*60*24*$maxtime))) ) { 
		if ( ($frequency>=$sfsfreq) && (strtotime($lastseen)>(time()-(60*60*24*$sfsage))) ) { 
			// frequency we got from the db, sfsfreq is the min we'll accept (default 0)
			// sfsage is the age in days - we get lastscene from
			return "SFS last seen=$lastseen, frequency=$frequency";
		}
		return false;
	}
	public static function getafile($f,$method='GET') {
		// try this using Wp_Http
		if( !class_exists( 'WP_Http' ) )
		include_once( ABSPATH . WPINC. '/class-http.php' );
		$request = new WP_Http;
		$parms=array();
		$parms['timeout']=10; // bump timeout a little we are timing out in Google
		$parms['method']=$method;
		$result = $request->request( $f ,$parms);
		// see if there is anything there
		if (empty($result)) return '';
		if (is_array($result)) {
			$ansa=$result['body']; 
			return $ansa;
		}
		if (is_object($result) ) {
			//$ansa='ERR: '.$result->get_error_message();
			//return $ansa; // return $ansa when debugging
			return '';
		} 
		return '';

		
	}

}
?>