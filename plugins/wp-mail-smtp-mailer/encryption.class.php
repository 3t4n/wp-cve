<?php 

if (!defined( 'ABSPATH')) exit;

/**
*  Encryption 
*/
class WPMSM_encryption{



	public static function encrypt( $data = '' ) {
	
		// bail ealry if no encrypt function
		if( !function_exists('openssl_encrypt') ) return base64_encode($data);
		
		
		// generate a key
		$key = wp_hash('acf_encrypt');
		
		
		// Generate an initialization vector
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		
		
		// Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
		$encrypted_data = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
		
		
		// The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
		return base64_encode($encrypted_data . '::' . $iv);
		
	}
	
	

	/**
	 * Decrypt data
	 */
	
	public static function decrypt( $data = '' ) {
		
		// bail ealry if no decrypt function
		if( !function_exists('openssl_decrypt') ) return base64_decode($data);
		
		
		// generate a key
		$key = wp_hash('acf_encrypt');
		
		
		// To decrypt, split the encrypted data from our IV - our unique separator used was "::"
		list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
		
		
		// decrypt
		return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
		
	}
	





}