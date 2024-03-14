<?php

if (!defined('ABSPATH')) exit;

class astound_chkshort extends astound_module { // change name
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		$this->searchname='email/author too short';
		if (array_key_exists('email',$post)) {
			$email=$post['email'];
			if (!empty($email)) {
				if (strlen($email)<5) {
					return "email too short:$email";
				}
			}
		}
		if (array_key_exists('user_email',$post)) {
			$email=$post['user_email'];
			if (!empty($email)) {
				if (strlen($email)<5) {
					return "email too short:$email";
				}
			}
		}
		if (array_key_exists('author',$post)) {
			if (!empty($post['author'])) {
				$author=$post['author'];
				// short author is OK?.
				if (strlen($post['author'])<3) {
					return "author too short:$author";
				}
			}
		}
		return false;
	}
}
?>