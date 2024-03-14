<?php

namespace CTXFeed\V5\Override;

class Heureka_skTemplate
{
	public function __construct()
	{
		add_filter( 'woo_feed_product_item_wrapper', [$this, 'woo_feed_product_item_wrapper_callback'] );
	}

	public function woo_feed_product_item_wrapper_callback( $wrapper ){
		return 'SHOPITEM';
	}
}
