<?php
if (!defined('ABSPATH')) exit;
class astound_chktld extends astound_module { // change name
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		// this checks the .xxx or .ru, etc in emails - only works if there is an email
		$validTLD=$options['tldlist'];
		if (array_key_exists('email',$post)) {
			$email=strtolower($post['email']);
			if (empty($email) || strlen($email)<4) return false;
			if (strpos($email,'.')===false) return false;
			$end=substr($email,strrpos($email,'.'));
			foreach ($validTLD as $tld) {
				if ($end==$tld) return false;
			}
			return "non-generic tld $end in $email";
		}
		if (array_key_exists('user_email',$post)) {
			$email=strtolower($post['user_email']);
			if (empty($email) || strlen($email)<4) return false;
			if (strpos($email,'.')===false) return false;
			$end=substr($email,strrpos($email,'.'));
			foreach ($validTLD as $tld) {
				if ($end==$tld) return false;
			}
			return "non-generic tld $end in $email";
		}
		return false;
	}
}
?>