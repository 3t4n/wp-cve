<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get plugin settings
 * If not found, saves the default settings first
 * @return array Array containing the plugin settings
 */
function woo_image_seo_get_settings(): array
{
    $settings = get_option( WOO_IMAGE_SEO['option_name'] );

    if ( empty( $settings ) ) {
        $settings = WOO_IMAGE_SEO['default_settings'];

        update_option( 'woo_image_seo', $settings );
    }

    return json_decode( $settings, true );
}
