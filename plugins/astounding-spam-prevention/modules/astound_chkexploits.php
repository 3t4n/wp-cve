<?PHP

if (!defined('ABSPATH')) exit;

class astound_chkexploits {
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		// search the request for eval and sql statements
		$rpost=$_REQUEST;
		if (empty($rpost)||!is_array($rpost)) {
			return false;
		}
		foreach($rpost as $req) {
			if (is_array($req)) {
				$req=print_r($req,true);
			}
			$req=urldecode($req);
			if (stripos($req,'EV' .
			'AL'.
			'(BAS'.
			'E64'.'_DEC'.'
			ODE(')!==false) { // dotting the search to not kick off updates, etc.
				if (strlen($req)>24) $req=substr($req,24);
				$req=htmlentities($req);
				return "eval attack $req";
			}
			if (stripos($req,'document.write(string.fromcharcode')!==false) {
				if (strlen($req)>24) $req=substr($req,24);
				$req=htmlentities($req);
				return "offset string attack  $req";
			}
			//'document.write(Stringfromcharcode'
			// union all select - this is a common sql injection string
			if (stripos($req,'uni'.'on all se'.'lect')!==false) { 
				if (strlen($req)>24) $req=substr($req,24);
				$req=htmlentities($req);
				return "sql inject attack $req";
			}
		}
		return false;
	}
}
?>