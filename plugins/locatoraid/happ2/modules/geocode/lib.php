<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Geocode_Lib_HC_MVC extends _HC_MVC
{
	public function prepare_address( $address )
	{
		$return = $address;

		$return = strip_tags($return);
		$return = str_replace("\r\n", " ", $return);
		$return = str_replace("\n\r", " ", $return);
		$return = str_replace("\r", " ", $return);
		$return = str_replace("\n", " ", $return);
		$return = str_replace("  ", " ", $return);
		$return = str_replace("  ", " ", $return);
		$return = trim( $return );

		return $return;
	}
}
