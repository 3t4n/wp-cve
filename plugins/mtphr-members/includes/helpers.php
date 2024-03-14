<?php

/* --------------------------------------------------------- */
/* !Sanitize classes passed through shortcodes - 1.1.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_sanitize_class') ) {
function mtphr_members_sanitize_class( $class='' ) {
	
	$class_arr = explode(' ', $class);
	$classes = '';
	if( is_array($class_arr) && count($class_arr) > 0 ) {
		foreach( $class_arr as $i=>$cl ) {
			$classes .= sanitize_html_class($cl).' ';
		}
		$classes = substr($classes, 0, -1);
	}
	
	return $classes;	
}
}