<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function woo_image_seo_parse_token_name(): string
{
    return get_the_title();
}

function woo_image_seo_parse_token_category( $builder_token, $builder_index, $custom_texts, $product_id ): string
{
    // get product categories
    $product_categories = get_the_terms( $product_id, 'product_cat' );

    // go through the first 2 categories and try to use them
    foreach ( [0, 1] as $index ) {
        $is_valid_category_name = ! empty( $product_categories[ $index ]->name ) && $product_categories[ $index ]->name !== 'Uncategorized';
        if ( $is_valid_category_name ) {
            return $product_categories[ $index ]->name;
        }
    }

    return '';
}

function woo_image_seo_parse_token_tag( $builder_token, $builder_index, $custom_texts, $product_id ): string
{
    // get product tags
    $product_tags = get_the_terms( $product_id, 'product_tag' );

    return empty( $product_tags[0]->name ) ? '' : $product_tags[0]->name;
}

function woo_image_seo_parse_token_custom( $builder_token, $builder_index, $custom_texts ): string
{
    // custom text
    return ! isset( $custom_texts[ $builder_index ] ) ? '' : (string) $custom_texts[ $builder_index ];
}

function woo_image_seo_parse_token_site_name(): string
{
    // site name
    return wp_strip_all_tags( get_bloginfo( 'name' ), true );
}

function woo_image_seo_parse_token_site_description(): string
{
    // site description
    return wp_strip_all_tags( get_bloginfo( 'description' ) );
}

function woo_image_seo_parse_token_site_domain(): string
{
    // site domain
    return empty( $_SERVER['HTTP_HOST'] ) ? '' : $_SERVER['HTTP_HOST'];
}

function woo_image_seo_parse_token_current_date(): string
{
    // current date
    return current_time( 'Y-m-d' );
}
