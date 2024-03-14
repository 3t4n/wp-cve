<?php

/**
 * Cache helper.
 *
 * @package WPDesk\Library\Marketing\Abstracts
 */
namespace FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\Helpers;

use FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\MarketingBoxes;
class Cache
{
    const MARKETING_SLUG = '_wpdesk_marketing_';
    /**
     * @param string $plugin_slug
     * @param string $lang
     */
    public static function create_slug($plugin_slug, $lang) : string
    {
        return self::MARKETING_SLUG . \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\MarketingBoxes::VERSION . '_' . $plugin_slug . '_' . $lang;
    }
}
