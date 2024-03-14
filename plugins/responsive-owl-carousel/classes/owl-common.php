<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class owlc_cls_common {
	public static function owlc_generate_guid($length = 30) {
		$guid = rand();
		$length = 6;
		$rand1 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand2 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand3 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand4 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand5 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand6 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$guid = $rand1."-".$rand2."-".$rand3."-".$rand4."-".$rand5;
		return $guid;
	}
	
	public static function owlc_special_letters() {
		$string = "/[\'^$%&*()}{@#~?><>,|=_+\"]/";
		return $string;
	}
	
	public static function owlc_split_settings($value) {	
		$settings = explode(",", $value);	
		$setting_items = array();
		
		foreach($settings as $setting) 
		{
			$setting = str_replace("{", "", $setting);
			$setting = str_replace("}", "", $setting);
			$setting = explode(":", $setting);
			$setting_items[$setting[0]] = trim($setting[1]);
		}

		return $setting_items;
	}
	
}

class owlc_cls_security {
	public static function owlc_check_number($value) {
		if(!is_numeric($value)) { 
			die('<p>Security check failed. Are you sure you want to do this?</p>'); 
		}
	}

	public static function owlc_check_guid($value) {
		$value_length1 = strlen($value);
		$value_noslash = str_replace("-", "", $value);
		$value_length2 = strlen($value_noslash);

		if( $value_length1 != 34 || $value_length2 != 30) {
			die('<p>Security check failed. Are you sure you want to do this?</p>'); 
		}

		if (preg_match('/[^a-z]/', $value_noslash)) {
			die('<p>Security check failed. Are you sure you want to do this?</p>'); 
		}
	}
}