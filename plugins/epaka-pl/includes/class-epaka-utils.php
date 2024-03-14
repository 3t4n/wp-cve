<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Epaka_Utils{
    static function prepareAddress($address)
	{
		if(!is_string($address))
		{
			return false;
		}
		$address = trim($address);
		$addressDivided = array();
		$pattern = '/([ \p{L}\d]+)(m\.|lokal|lok\.|lok|pokój|pok.|pok)([ \p{L}\d]+)$/iu';
		$replacement = '$1/$3';
		$address = preg_replace($pattern, $replacement, $address);

		$pattern = '/^([\p{L}\d\s.,]+) ([ \p{L}\d]*[\p{L}\d]+)( *(\/|\\\|m\.?) *([\p{L}\d]+[ \p{L}\d]*))?$/iu';
		preg_match($pattern, $address, $addressDivided);

		return $addressDivided;
    } 
}