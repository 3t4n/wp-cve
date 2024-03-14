<?php
namespace SnazzyMaps;

defined( 'ABSPATH' ) OR exit;

class SnazzyMaps_Helpers {
	public static function esc_rel_url($url){
		return preg_replace('/^http:\/\//', '', esc_url($url));
	}
}
?>