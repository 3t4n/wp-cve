<?php

if( !defined( 'ABSPATH' ) ) exit;
/**
 * Helper per il login
 * @author bauhausk
 *
 */
class Social2blog_Login {
	
	/**
	 * Genera un token per un user id
	 * @param unknown $user_id
	 */
	public static function generateToken($user_id) {
		
		$time = time();
		$rand = rand(1, 10000);
		return md5 ($user_id.$time.$rand);
	}
}