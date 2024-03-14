<?php

namespace CTXFeed\V5\Common;

use CTXFeed\V5\Feed\Feed;
use CTXFeed\V5\Query\QueryFactory;
use CTXFeed\V5\Utility\Config;

class Factory {
	/**
	 * Get Product Ids by Query Type
	 *
	 * @param $config
	 *
	 * @return array
	 */
	public static function get_product_ids( $config ) {
		return QueryFactory::get_ids( $config );
	}

	/**
	 * @param string $name Feed Name
	 *
	 * @return \CTXFeed\V5\Utility\Config
	 */
	public static function get_feed_config( $name ) {

		$feedOption = Feed::get_single_feed($name);

		return new Config( $feedOption[0] );
	}

	/**
	 * @param string $name Feed Name
	 *
	 * @return \CTXFeed\V5\Utility\Config
	 */
	public static function get_feed_info( $name ) {

		$feedOption = Feed::get_single_feed($name);

		return new Config( $feedOption[0] );
	}
}
