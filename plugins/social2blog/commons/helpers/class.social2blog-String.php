<?php
if( !defined( 'ABSPATH' ) ) exit;

class Social2blog_String {
	
/**
 * startsWith("abcdef", "ab") -> true
 * @param unknown $haystack
 * @param unknown $needle
 * @return boolean
 */
	public static function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
	/**
	 * endsWith("abcdef", "ab") -> false
	 * @param unknown $haystack
	 * @param unknown $needle
	 * @return boolean
	 */
	public static function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}
	
		return (substr($haystack, -$length) === $needle);
	}
	
	/**
	 * sostituisce la prima occorrenza di $needle in una stringa.
	 * @param unknown $haystack
	 * @param unknown $needle
	 * @return mixed
	 */
	public static function replaceFirst($haystack,$needle){
		$pos = strpos($haystack,$needle);
		if ($pos !== false) {
			$haystack = substr_replace($haystack,$replace,$pos,strlen($needle));
		}
		return $haystack;
	}
	
}