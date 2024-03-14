<?php

namespace CTXFeed\V5\Compatibility;

/**
 * Class WPOptions
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Compatibility
 * @author     Nashir Uddin <nashirbabu@gmail.com>
 */

class JWTAuth {

	/**
	 * The REST API slug.
	 *
	 * @var string
	 */
	private $rest_api_slug = 'wp-json';

	public function __construct() {
		add_filter( 'jwt_auth_default_whitelist', [$this,'woo_feed_jwt_auth_default_whitelist'], 10, 1 );
	}

	// Adjust this default whitelist for jwt-auth plugin.
    public function woo_feed_jwt_auth_default_whitelist($default_whitelist){

	    $this->rest_api_slug = get_option( 'permalink_structure' ) ? rest_get_url_prefix() : '?rest_route=/';

	    array_push($default_whitelist,"/".$this->rest_api_slug."/ctxfeed/v1/");
	    return $default_whitelist;
   }
}
