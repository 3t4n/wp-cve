<?php
declare(strict_types=1);

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Source_Woocommerce_Fields extends GJMAA_Source
{

    public function getOptions($param = null)
    {
        return apply_filters('gjmaa_source_woocommerce_fields_filter', [
            'post_title' => __('Title', GJMAA_TEXT_DOMAIN),
            'post_content' => __('Description', GJMAA_TEXT_DOMAIN),
            'post_attributes' => __('Attributes', GJMAA_TEXT_DOMAIN),
            'post_categories' => __('Categories', GJMAA_TEXT_DOMAIN),
            'post_thumbnail' => __('Thumbnail', GJMAA_TEXT_DOMAIN),
            'post_media' => __('Media', GJMAA_TEXT_DOMAIN)
        ]);
    }
}