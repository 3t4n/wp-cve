<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter image attributes to add SEO attributes
 */
add_filter(
    'wp_get_attachment_image_attributes',
    'woo_image_seo_get_image_attributes',
    PHP_INT_MAX
);
