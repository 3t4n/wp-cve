<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Http_Wordpress_Lib_Client_HC_MVC
{
	public function get( $url )
	{
		$return = NULL;
		$response = wp_remote_get( $url );
		if( is_array($response) ){
			$header = $response['headers']; // array of http header lines
			$return = $response['body']; // use the content
		}
		return $return;
	}
}