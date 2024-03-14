<?php

namespace CTXFeed\V5\Override;

use CTXFeed\V5\Compatibility\ExcludeCaching;

/**
 * Class OverrideFactory
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Override
 */
class OverrideFactory {

	/**
	 * Load Merchant Template Override File.
	 *
	 * Based current feed config all filters in the "ProductInfo" class will be added to respective class
	 * Example: If template is "google" then "Override\GoogleTemplate" class will be initialized
	 * and all filter from "ProductInfo" class will be applied for Google merchant specific requirement.
	 *
	 * @param \CTXFeed\V5\Utility\Config $config Configuration object.
     * @return bool|object
	 */
	public static function TemplateOverride( $config ) {//phpcs:ignore
		$class = '\CTXFeed\V5\Override\\' . ucfirst( $config->get_feed_template() ) . 'Template';

		if ( class_exists( $class ) ) {
			return new $class;
		}

		return false;
	}

	/**
	 * Exclude Feed URL from Caching.
	 *
	 * @return \CTXFeed\V5\Compatibility\ExcludeCaching
	 */
	public static function excludeCache() {//phpcs:ignore
		return new ExcludeCaching;
	}

	/**
	 * Exclude Feed URL from Caching.
	 *
	 * @return \CTXFeed\V5\Override\Advance
	 */
	public static function Advance() {//phpcs:ignore
		return new Advance;
	}

}
