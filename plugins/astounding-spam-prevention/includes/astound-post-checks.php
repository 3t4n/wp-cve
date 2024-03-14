<?PHP
/*********************************
* Post check handler
* Most spam prevention goes here
**********************************/
if (!defined('ABSPATH')) exit;

function astound_post_checks() {
		/*
		check for named fields used by wordpress
	*/
	 /* comment form {'author', 'email', 'url', 'comment'} */
	$uri=$_SERVER["REQUEST_URI"];	
	if (empty($uri)) {
		$uri=$_SERVER["SCRIPT_NAME"];	
	}
	$post=astound_get_post();
	$rurl=wp_registration_url();
	$rurl=substr($rurl,strpos($rurl,'/')+1);
	$rurl=substr($rurl,strpos($rurl,'/')+1);
	$rurl=substr($rurl,strpos($rurl,'/'));
	$ip=astound_get_ip();
	// for bbpress comments try checking post fields for bbp_reply_content - future enhancement?
	if ($rurl==$uri || (array_key_exists('action',$post) && $post['action']=='register') ) {
		/*
			in registration url. Need to check stuff
		*/
		/* get the post params */
		/*
			valid registration form submission
			user_login, email, action=register
			2) check values
			3) if fails redirect back to the regration page.
		*/
		$user_login=sanitize_text_field($post['user_login']);
		$user_email=sanitize_text_field($post['user_email']);
		if (empty($user_login)||empty($user_email)) {
			return;
		}
		astound_clean_cache();
		astound_require('includes/astound-check.php');	
		
		$reason=astound_check_allow();
		if ($reason!==false) {
			$reason=sanitize_text_field($reason);
			astound_log("allowed registration $ip $reason");
			return;
		}

		$reason=astound_register_check();
		if ($reason!==false) { 
			if (substr($reason,0,1)=='*') {
				$reason=str_replace('~~~~','<br>',$reason);
				wp_die( "denied registration $reason",403 );
				exit;
			}
			$reason=sanitize_textarea_field($reason);
			astound_log("denied registration $ip $reason");
			$options=astound_get_options();
			// add all to cache, even if display all is on.
			//if ($options['astound_displayall']!='Y') {
				astound_add_to_cache($ip,$reason); // don't cache with we are using the display all option
			//} 
			$reason=str_replace('~~~~','<br>',$reason);
			wp_die( "denied registration $reason",403 );
			exit;
		}
		astound_log("allowed registration $ip, $user_login, $user_email");
	} else if ( array_key_exists('comment',$post) && 
	     array_key_exists('email',$post) &&
		 !empty($post['comment']) && 
		 !empty($post['email']) 
		) {
		/*
			valid email and comment - probably a valid comment comment form submit
			1) check that the nonce exists so we can then check values.
			2) check the values for spamminess
			3) if fails send back to comment page.
		*/
		//$comment=$post['comment'];	
		$email=sanitize_text_field($post['email']);	
		astound_clean_cache();
		astound_require('includes/astound-check.php');
		
		$reason=astound_check_allow();
		if ($reason!==false) {
			$reason=sanitize_text_field($reason);
			astound_log("allowed comment $ip $reason");
			return;
		}

		$reason=astound_comment_check();
		if ($reason!==false) { 
			if (substr($reason,0,1)=='*') {
				$reason=str_replace('~~~~','<br>',$reason);
				wp_die( "denied comment spammer $reason",403 );
				exit;
			}
			$reason=sanitize_text_field($reason);
			astound_log("denied comment spammer $ip $reason");
			// add the reason to the cache.
			astound_add_to_cache($ip,$reason);
			$reason=str_replace('~~~~','<br>',$reason);
			wp_die("denied comment spammer $reason",403 );
			exit;
		}
		astound_log("allowed comment $ip, $email ");
		
	} 
	return;
}
function astound_clean_cache() {
	$cache=get_option('astound_cache');
	if (empty($cache) || !is_array($cache)) {
		return;
	}
	foreach($cache as $key=>$val) {
		if ($val['time']<(time()-(60*15))) { // keep cache for 15 minutes
			unset($cache[$key]);
		}
	}
	update_option('astound_cache',$cache);
	
}
function astound_add_to_cache($ip,$reason) {
	$cache=get_option('astound_cache');
	if (empty($cache) || !is_array($cache)) {
		$cache=array();
	}
	$hit=array();
	$hit['time']=time();
	$hit['reason']=$reason;
	$cache[$ip]=$hit;
	update_option('astound_cache',$cache);
}
?>