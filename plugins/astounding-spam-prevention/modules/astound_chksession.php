<?PHP

if (!defined('ABSPATH')) exit;

class astound_chksession extends astound_module {
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		// this uses cookies. It may break programs that need to get to cookies first.
		// move this to main line
		// post is set - check the timeout
		$sesstime=3; // nobody can do it in less than 3 seconds
		if (!defined("WP_CACHE")||(!WP_CACHE)) { 
			if (isset($_COOKIE['astound_prevention_time'])) {
				$stime=$_COOKIE['astound_prevention_time'];
				$tm=strtotime("now")-$stime;
				if ($tm>0&&$tm<=$sesstime) { // zero seconds is wrong, too. it means that session was set somewhere.
					// takes longer than 2 seconds to really type a comment
					return "session speed - $tm seconds";
				} 
			}
		}
		return false;
	}
}
?>