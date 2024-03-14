<?php
/*
 * Definition of pluggable functions
 *
 * @since 4.4
 *
 * */
defined( 'ABSPATH' ) || die();

if ( ! function_exists( 'woo_feed_get_category_mapping_value' ) ) {
    /**
     * Return Category Mapping Values by Product Id [Parent Product for variation]
     *
     * @param string $cmappingName Category Mapping Name
     * @param int    $product_id Product ID / Parent Product ID for variation product
     *
     * @return mixed
     */
    function woo_feed_get_category_mapping_value( $cmappingName, $product_id ) {

        $getValue       = maybe_unserialize( get_option( $cmappingName ) );
        $cat_map_value  = '';
        $suggestive_category_list_merchants = array( 'google', 'facebook', 'pinterest', 'bing', 'bing_local_inventory', 'snapchat' );

        if ( ! isset( $getValue['cmapping'] ) && ! isset( $getValue['gcl-cmapping'] ) ) {
            return '';
        }

        //get product terms
        $categories = get_the_terms( $product_id, 'product_cat' );

        //get cmapping value
        if ( in_array( $getValue['mappingprovider'], $suggestive_category_list_merchants ) && isset( $getValue['gcl-cmapping'] ) ) {
            $cmapping = is_array( $getValue['gcl-cmapping'] ) ? array_reverse( $getValue['gcl-cmapping'], true ) : $getValue['gcl-cmapping'];
        } else {
            $cmapping = is_array( $getValue['cmapping'] ) ? array_reverse( $getValue['cmapping'], true ) : $getValue['cmapping'];
        }

        // Fixes empty mapped category issue
        if ( ! empty( $categories ) && is_array( $categories ) && count( $categories ) ) {
            $categories = array_reverse($categories);
            foreach ( $categories as $category ) {
                if ( isset( $cmapping[ $category->term_id ] ) && ! empty( $cmapping[ $category->term_id ] ) ) {
                    $cat_map_value = $cmapping[ $category->term_id ];
                    break;
                }
            }
        }

        return $cat_map_value;

    }
}