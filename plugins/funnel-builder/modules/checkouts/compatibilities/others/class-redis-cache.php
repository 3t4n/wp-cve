<?php

#[AllowDynamicProperties] 

  class WFACP_Redis_cache {
	public function __construct() {
		add_filter( 'redis_object_cache_get_value', [ $this, 'do_not_cache_path' ], 99, 3 );
	}

	public function do_not_cache_path( $value, $key, $group ) {
		if ( $group == "woocommerce" && strpos( $key, 'template-cartcart-shippingphp' ) !== false ) {
			return null;
		}

		return $value;
	}
}

new WFACP_Redis_cache();
