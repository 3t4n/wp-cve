<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wccal_Product_External extends WC_Product_External {
	public function get_product_url( $context = 'view' ) {

		$base = Wccal::get_affiliate_base();

		if ( get_option( 'permalink_structure' ) ) {
			return apply_filters(
				'wccal_product_url_permalink',
				user_trailingslashit( home_url() . '/' . $base . '/' . $this->id ),
				$this
			);
		}

		return add_query_arg( apply_filters( 'wccal_product_url_qs', [
			$base => $this->id,
		], $this ), home_url() . '/index.php' );
	}
}
