<?php
/**
 *
 * CashBill Payment PHP SDK
 *
 * @author Lukasz Firek <lukasz.firek@cashbill.pl>
 * @version 1.0.0
 * @license MIT
 * @copyright CashBill S.A. 2015
 *
 * http://cashbill.pl
 *
 */
namespace CashBill\Payments\Helpers;

use Exception;
class SignatureGenerator {
	
	/**
	 * Create SHA1 Hash from input parametrs
	 *
	 * @param mixed $data
	 * @param string $key       	
	 *
	 * @return string
	 *
	 */
	
	public static function generateSHA1($data, $key) {
		if (is_array ( $data )) {
			return SHA1 ( self::createStringFromArray ( $data ) . $key );
		}
		return SHA1 ( $data . $key );
	}
	
	/**
	 * Create MD5 Hash from input parametrs 
	 *
	 * @param mixed $data
	 * @param string $key          	
	 *
	 * @return string
	 *
	 */
	
	public static function generateMD5($data, $key) {
		if (is_array ( $data )) {
			
			return md5 ( self::createStringFromArray( $data ) . $key );
		}
		return md5 ( $data . $key );
	}

	/**
	 * Create pure string from array input
	 *
	 * @param mixed $data 
	 *
	 * @return string
	 *
	 */

	public static function createStringFromArray($data){

		if(!is_array($data)){
			return $data;
		}

		$pureString = "";

		foreach($data as $element){
			$pureString .= self::createStringFromArray($element);
		}

		return $pureString;
	}

}
