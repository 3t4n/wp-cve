<?php
/**
 * @author karim
 * @copyright 2010
 */
 if($ksm==md5(5612479,6087071)){
function php_pages() 
{
	global $wp_rewrite;
 if ( !strpos($wp_rewrite->get_page_permastruct(), '.php')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.php';
 }
}
add_filter('user_trailingslashit', 'no_page_slash',66,2);
function no_page_slash($string, $type)
{
   global $wp_rewrite;
	if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true && $type == 'page'){
		return untrailingslashit($string);
  }else{
   return $string;
  }
}
}
?>