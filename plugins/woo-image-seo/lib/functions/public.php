<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
	Helper variable used to count the number of images for a given product
	The goal is to have unique attributes for all images
	Array key 'id' holds the lastly affected product's id
	Array key 'image_count' holds the currently affected image's index (starts with 1)
*/
$woo_image_seo_product_info = [
    'id' => 0,
    'image_count' => 0,
];

/**
 * The main attribute modification logic
 * Hooked into the "wp_get_attachment_image_attributes" filter
 * @param array $attr
 * @return array
 */
function woo_image_seo_get_image_attributes( array $attr ): array
{
    if ( get_post_type() !== 'product' ) {
        return $attr;
    }

    // skip images with the "woo-image-seo-skip" class
    if ( ! empty( $attr['class'] ) && strpos( $attr['class'], 'woo-image-seo-skip' ) !== false ) {
        return $attr;
    }

    $product_id = get_the_ID();

    // if no product id is found, return the original attributes
    if ( empty( $product_id ) ) {
        return $attr;
    }

    // helper global to count number of images for current product
    global $woo_image_seo_product_info;

    // modify the global to either add to the image count or reset it
    if ( $woo_image_seo_product_info['id'] === $product_id ) {
        $woo_image_seo_product_info['count']++;
    } else {
        $woo_image_seo_product_info = [
            'id' => $product_id,
            'count' => 1,
        ];
    }

    // check which attributes should be handled - loops through "alt" and "title"
    foreach ( woo_image_seo_get_settings() as $attribute_name => $attribute_settings ) {
        if ( empty( $attribute_settings['enable'] ) ) {
            continue;
        }

        // "forced" attributes will override existing attributes
        if ( empty( $attribute_settings['force'] ) && ! empty( $attr[ $attribute_name ] ) ) {
            continue;
        }

        // build the attribute
        $attr[ $attribute_name ] = woo_image_seo_build_attribute( $attribute_settings, $product_id );
    }

    // return the modified attributes
    return $attr;
}

/**
 * Build the attribute string based on plugin settings
 * @param $attribute_settings
 * @param $product_id
 * @return string
 */
function woo_image_seo_build_attribute( $attribute_settings, $product_id ): string
{
    $result = '';

    // loop through the Attribute Builder texts and parse them
    foreach ( $attribute_settings['text'] as $builder_index => $builder_token ) {
        if ( empty( $builder_token ) ) {
            continue;
        }

        $parsed_token = woo_image_seo_parse_token(
            $builder_token,
            $builder_index,
            $attribute_settings['custom'],
            $product_id
        );

        if ( strlen( $parsed_token ) ) {
            $result .= ' ' . $parsed_token;
        }
    }

    // remove whitespaces from the beginning and end of the string
    $result = trim( $result );

    // optionally add number at end for products with more than one image
    global $woo_image_seo_product_info;

    if ( ! empty( $attribute_settings['count'] ) && $woo_image_seo_product_info['count'] > 1 ) {
        $result .= ' ' . $woo_image_seo_product_info['count'];
    }

    return $result;
}

/**
 * Parse each of the Attribute Builder texts
 * @param $builder_token
 * @param $builder_index
 * @param $custom_texts
 * @param $product_id
 * @return string
 */
function woo_image_seo_parse_token($builder_token, $builder_index, $custom_texts, $product_id): string
{
    $token_slug = str_replace( ['[', ']', '-'], ['', '', '_'], $builder_token );
    $callable = 'woo_image_seo_parse_token_' . $token_slug;

    if ( ! function_exists( $callable ) ) {
        return '';
    }

    return call_user_func_array(
        $callable,
        [
            $builder_token,
            $builder_index,
            $custom_texts,
            $product_id
        ]
    );
}
