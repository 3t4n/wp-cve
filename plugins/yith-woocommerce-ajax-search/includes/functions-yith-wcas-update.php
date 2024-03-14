<?php
/**
 * Functions
 *
 * @author  YITH
 * @package YITH/Search
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'yith_wcas_update_200_search_fields' ) ) {
	/**
	 * Update the search field option from version 1.x to 2.0
	 *
	 * @since 2.0.0
	 */
	function yith_wcas_update_200_search_fields() {
		$search_fields      = array();
		$priority           = 1;
		$fields             = array(
			'title'              => array(),
			'excerpt'            => array(),
			'content'            => array(),
			'product_categories' => array( 'product_category_condition' => 'all' ),
			'product_tags'       => array( 'product_tag_condition' => 'all' ),
		);
		$map_new_field_type = array(
			'title'                => 'name',
			'excerpt'              => 'summary',
			'content'              => 'description',
			'product_categories'   => 'product_categories',
			'product_tags'         => 'product_tags',
			'sku'                  => 'sku',
			'product_custom_field' => 'custom_fields',
		);
		foreach ( $fields as $key => $field ) {
			$option = get_option( 'yith_wcas_search_in_' . $key );
			if ( $option && 'yes' === $option ) {

				$search_fields[] = array_merge(
					array(
						'type'     => $map_new_field_type[ $key ],
						'priority' => $priority ++,
					),
					$field
				);

			}
		}
		$search_by_sku = get_option( 'yith_wcas_search_by_sku' );
		if ( $search_by_sku ) {
			$search_fields[] = array(
				'type'     => $map_new_field_type['sku'],
				'priority' => $priority ++,
			);

		}

		$cf = get_option( 'yith_wcas_cf_name' );
		if ( ! empty( $cf ) ) {
			$search_fields[] = array(
				'type'              => $map_new_field_type['product_custom_field'],
				'priority'          => $priority ++,
				'custom_field_list' => array_map( 'trim', explode( ',', $cf ) ),
			);
		}
		if ( ! empty( $search_fields ) ) {
			update_option( 'yith_wcas_search_fields', $search_fields );
		}
	}
}

if ( ! function_exists( 'yith_wcas_update_200_get_fields_to_show' ) ) {
	/**
	 * Get the oldest the search field option
	 */
	function yith_wcas_update_200_get_fields_to_show() {

		$fields          = array( 'excerpt', 'categories', 'price' );
		$details_to_show = array( 'name' );
		foreach ( $fields as $field ) {
			$option = get_option( 'yith_wcas_show_' . $field );
			if ( $option && 'yes' === $option ) {
				$details_to_show[] = $field;
			}
		}

		$show_image = get_option( 'yith_wcas_show_thumbnail' );
		if ( 'none' !== $show_image ) {
			$details_to_show[] = 'image';
		}

		return $details_to_show;
	}
}

if ( ! function_exists( 'yith_wcas_is_fresh_block_installation' ) ) {
	/**
	 * Check if is a fresh installation or the plugin is an update from 1.x to 2.0.0
	 *
	 * @return bool
	 */
	function yith_wcas_is_fresh_block_installation() {
		return ! get_option( 'ywcas_updated_to_v2', false );
	}
}

if ( ! function_exists( 'yith_wcas_user_switch_to_block' ) ) {
	/**
	 * Check if the customer switch from old shortcode to block
	 *
	 * @return bool
	 */
	function yith_wcas_user_switch_to_block() {
		return  apply_filters( 'ywcas_user_switch_block', get_option( 'ywcas_user_switch_to_block', false ) );
	}
}


if ( ! function_exists( 'yith_wcas_save_default_shortcode_options_premium' ) ) {
	/**
	 * Change the default values to create shortcodes
	 *
	 * @return void
	 */
	function yith_wcas_save_default_shortcode_options_premium() {
		$shortcodes = YITH_WCAS_Settings::get_instance()->get_shortcodes_list();
		if ( isset( $shortcodes['default'] ) ) {
			$show_image        = get_option( 'yith_wcas_show_thumbnail' );
			$image_position    = 'none' !== $show_image ? $show_image : $shortcodes['default']['options']['search-results']['image-position'];
			$default           = $shortcodes['default']['options'];
			$show_on_sale      = get_option( 'yith_wcas_show_sale_badge', '' );
			$show_out_of_stock = get_option( 'yith_wcas_show_outofstock_badge', '' );
			$show_featured     = get_option( 'yith_wcas_show_featured_badge', '' );
			$show_badges       = array();
			if ( ! empty( $show_on_sale ) && 'yes' === $show_on_sale ) {
				$show_badges[] = 'sale';
			}

			if ( ! empty( $show_out_of_stock ) && 'yes' === $show_out_of_stock ) {
				$show_badges[] = 'out-of-stock';
			}

			if ( ! empty( $show_featured ) && 'yes' === $show_featured ) {
				$show_badges[] = 'featured';
			}

			$shortcodes['default']['options']['search-input']['placeholder']                     = get_option( 'yith_wcas_search_input_label', $default['search-input']['placeholder'] );
			$shortcodes['default']['options']['submit-button']['button-label']                   = get_option( 'yith_wcas_search_submit_label', $default['submit-button']['button-label'] );
			$shortcodes['default']['options']['search-results']['max-results']                   = get_option( 'yith_wcas_posts_per_page', $default['search-results']['max-results'] );
			$shortcodes['default']['options']['search-results']['price-label']                   = get_option( 'yith_wcas_search_price_label', $default['search-results']['price-label'] );
			$shortcodes['default']['options']['search-results']['image-size']                    = get_option( 'yith_wcas_search_show_thumbnail_dim', $default['search-results']['image-size'] );
			$shortcodes['default']['options']['search-results']['results-layout']                = 'list';
			$shortcodes['default']['options']['search-results']['info-to-show']                  = yith_wcas_update_200_get_fields_to_show();
			$shortcodes['default']['options']['search-results']['image-position']                = $image_position;
			$shortcodes['default']['options']['search-results']['name-color']                    = get_option( 'yith_wcas_search_title_color', $default['search-results']['name-color'] );
			$shortcodes['default']['options']['search-results']['max-summary']                   = get_option( 'yith_wcas_show_excerpt_num_words', $default['search-results']['max-summary'] );
			$shortcodes['default']['options']['search-results']['show-view-all']                 = get_option( 'yith_wcas_search_show_view_all', $default['search-results']['show-view-all'] );
			$shortcodes['default']['options']['search-results']['view-all-label']                = get_option( 'yith_wcas_search_show_view_all_text', $default['search-results']['view-all-label'] );
			$shortcodes['default']['options']['search-results']['no-results-label']              = get_option( 'yith_wcas_search_show_no_results_text', $default['search-results']['no-results-label'] );
			$shortcodes['default']['options']['search-results']['badges-to-show']                = $show_badges;
			$shortcodes['default']['options']['search-results']['show-hide-featured-if-on-sale'] = get_option( 'yith_wcas_hide_feature_if_on_sale', $default['search-results']['show-hide-featured-if-on-sale'] );
		}

		YITH_WCAS_Settings::get_instance()->update_shortcodes_list( $shortcodes );
	}
}

if ( ! function_exists( 'yith_wcas_save_default_shortcode_options' ) ) {
	/**
	 * Change the default values to create shortcodes
	 *
	 * @return void
	 */
	function yith_wcas_save_default_shortcode_options() {
		$shortcodes = YITH_WCAS_Settings::get_instance()->get_shortcodes_list();
		if ( isset( $shortcodes['default'] ) ) {

			$default           = $shortcodes['default']['options'];
			$shortcodes['default']['options']['search-input']['placeholder']                     = get_option( 'yith_wcas_search_input_label', $default['search-input']['placeholder'] );
			$shortcodes['default']['options']['search-results']['max-results']                   = get_option( 'yith_wcas_posts_per_page', $default['search-results']['max-results'] );

		}

		YITH_WCAS_Settings::get_instance()->update_shortcodes_list( $shortcodes );
	}
}