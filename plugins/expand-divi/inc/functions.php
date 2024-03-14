<?php 

if ( ! function_exists( 'expand_divi_is_mobile' ) ) {
	function expand_divi_is_mobile() {
		return preg_match( "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"] );
	}
}