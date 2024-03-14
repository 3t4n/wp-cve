<?php
/**
 * Booster for WooCommerce - Settings - Product Info
 *
 * @version 7.0.0
 * @since   2.8.0
 * @author  Pluggabl LLC.
 * @package Booster_For_WooCommerce/settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$is_multiselect_products = ( 'yes' === wcj_get_option( 'wcj_list_for_products', 'yes' ) );
$is_multiselect_cats     = ( 'yes' === wcj_get_option( 'wcj_list_for_products_cats', 'yes' ) );
$is_multiselect_tags     = ( 'yes' === wcj_get_option( 'wcj_list_for_products_tags', 'yes' ) );
$product_cats            = ( $is_multiselect_cats ? wcj_get_terms( 'product_cat' ) : false );
$product_tags            = ( $is_multiselect_tags ? wcj_get_terms( 'product_tag' ) : false );
$settings                = array();
$single_or_archive_array = array( 'single', 'archive' );
$products                = wcj_get_products();

$settings = array_merge(
	$settings,
	array(
		array(
			'id'   => 'wcj_product_info_general_options',
			'type' => 'sectionend',
		),
		array(
			'id'      => 'wcj_product_info_general_options',
			'type'    => 'tab_ids',
			'tab_ids' => array(
				'wcj_product_info_single'           => __( 'Single Product Pages', 'woocommerce-jetpack' ),
				'wcj_product_info_single_advanced'  => __( 'Single Product Advanced Options', 'woocommerce-jetpack' ),
				'wcj_product_info_archive'          => __( 'Archives', 'woocommerce-jetpack' ),
				'wcj_product_info_archive_advanced' => __( 'Archives Advanced Options', 'woocommerce-jetpack' ),
			),
		),
	)
);
foreach ( $single_or_archive_array as $single_or_archive ) {
	$extra_filters = wcj_get_option( 'wcj_product_custom_info_extra_filters_' . $single_or_archive, '' );
	if ( '' === ( $extra_filters ) ) {
		$extra_filters = array();
	} else {
		$extra_filters        = explode( PHP_EOL, $extra_filters );
		$extra_filters_result = array();
		if ( ! empty( $extra_filters ) ) {
			foreach ( $extra_filters as $extra_filter ) {
				$extra_filter = trim( $extra_filter );
				if ( '' !== ( $extra_filter ) ) {
					$extra_filter = explode( '|', $extra_filter, 2 );

					if ( 2 === count( $extra_filter ) ) {
						$extra_filter_id    = trim( $extra_filter[0] );
						$extra_filter_title = trim( $extra_filter[1] );
						if ( '' !== $extra_filter_id && '' !== $extra_filter_title ) {
							$extra_filters_result[ $extra_filter_id ] = $extra_filter_title;
						}
					}
				}
			}
		}
		$extra_filters = $extra_filters_result;
	}

	$single_or_archive_desc  = ( 'single' === $single_or_archive ? __( 'Single Product Pages', 'woocommerce-jetpack' ) : __( 'Archives', 'woocommerce-jetpack' ) );
	$settings                = array_merge(
		$settings,
		array(
			array(
				'id'   => 'wcj_product_info_' . $single_or_archive,
				'type' => 'tab_start',
			),
			array(
				'title' => $single_or_archive_desc,
				'type'  => 'title',
				'id'    => 'wcj_product_custom_info_options_' . $single_or_archive,
			),
			array(
				'title'             => __( 'Total Blocks', 'woocommerce-jetpack' ),
				'id'                => 'wcj_product_custom_info_total_number_' . $single_or_archive,
				'default'           => 1,
				'type'              => 'custom_number',
				'desc'              => apply_filters( 'booster_message', '', 'desc' ),
				'custom_attributes' => apply_filters( 'booster_message', '', 'readonly' ),
			),
			array(
				'type' => 'sectionend',
				'id'   => 'wcj_product_custom_info_options_' . $single_or_archive,
			),
		)
	);
	$product_custom_info_num = apply_filters( 'booster_option', 1, wcj_get_option( 'wcj_product_custom_info_total_number_' . $single_or_archive, 1 ) );
	for ( $i = 1; $i <= $product_custom_info_num; $i++ ) {

		wcj_maybe_convert_and_update_option_value(
			array(
				array(
					'id'      => 'wcj_product_custom_info_products_to_include_' . $single_or_archive . '_' . $i,
					'default' => '',
				),
				array(
					'id'      => 'wcj_product_custom_info_products_to_exclude_' . $single_or_archive . '_' . $i,
					'default' => '',
				),
			),
			$is_multiselect_products
		);

		wcj_maybe_convert_and_update_option_value(
			array(
				array(
					'id'      => 'wcj_product_custom_info_product_cats_to_include_' . $single_or_archive . '_' . $i,
					'default' => '',
				),
				array(
					'id'      => 'wcj_product_custom_info_product_cats_to_exclude_' . $single_or_archive . '_' . $i,
					'default' => '',
				),
			),
			$is_multiselect_cats
		);

		wcj_maybe_convert_and_update_option_value(
			array(
				array(
					'id'      => 'wcj_product_custom_info_product_tags_to_include_' . $single_or_archive . '_' . $i,
					'default' => '',
				),
				array(
					'id'      => 'wcj_product_custom_info_product_tags_to_exclude_' . $single_or_archive . '_' . $i,
					'default' => '',
				),
			),
			$is_multiselect_tags
		);

		$settings = array_merge(
			$settings,
			array(
				array(
					/* translators: %s: translators Added */
					'title' => sprintf( __( 'Block #%s', 'woocommerce-jetpack' ), $i ),
					'type'  => 'title',
					'id'    => 'wcj_product_custom_info_options_' . $single_or_archive . '_' . $i,
				),
				array(
					'title'    => __( 'Content', 'woocommerce-jetpack' ),
					'id'       => 'wcj_product_custom_info_content_' . $single_or_archive . '_' . $i,
					'default'  => '[wcj_product_total_sales before="Total sales: " after=" pcs."]',
					'type'     => 'textarea',
					'desc_tip' => __( 'You can use shortcodes here.', 'woocommerce-jetpack' ),
					'css'      => 'width:100%;height:200px;',
				),
				array(
					'title'   => __( 'Position', 'woocommerce-jetpack' ),
					'id'      => 'wcj_product_custom_info_hook_' . $single_or_archive . '_' . $i,
					'default' => ( 'single' === $single_or_archive ) ? 'woocommerce_after_single_product_summary' : 'woocommerce_after_shop_loop_item_title',
					'type'    => 'select',
					'options' => array_merge(
						( 'single' === $single_or_archive ?
						array(
							'woocommerce_before_single_product' => __( 'Before single product', 'woocommerce-jetpack' ),
							'woocommerce_before_single_product_summary' => __( 'Before single product summary', 'woocommerce-jetpack' ),
							'woocommerce_single_product_summary' => __( 'Inside single product summary', 'woocommerce-jetpack' ),
							'woocommerce_after_single_product_summary' => __( 'After single product summary', 'woocommerce-jetpack' ),
							'woocommerce_after_single_product' => __( 'After single product', 'woocommerce-jetpack' ),
							'woocommerce_before_add_to_cart_form' => __( 'Before add to cart form', 'woocommerce-jetpack' ),
							'woocommerce_before_add_to_cart_button' => __( 'Before add to cart button', 'woocommerce-jetpack' ),
							'woocommerce_after_add_to_cart_button' => __( 'After add to cart button', 'woocommerce-jetpack' ),
							'woocommerce_after_add_to_cart_form' => __( 'After add to cart form', 'woocommerce-jetpack' ),
							'woocommerce_product_meta_start' => __( 'Product meta start', 'woocommerce-jetpack' ),
							'woocommerce_product_meta_end' => __( 'Product meta end', 'woocommerce-jetpack' ),
						) :
						array(
							'woocommerce_before_shop_loop_item' => __( 'Before product', 'woocommerce-jetpack' ),
							'woocommerce_before_shop_loop_item_title' => __( 'Before product title', 'woocommerce-jetpack' ),
							'woocommerce_shop_loop_item_title' => __( 'Inside product title', 'woocommerce-jetpack' ),
							'woocommerce_after_shop_loop_item_title' => __( 'After product title', 'woocommerce-jetpack' ),
							'woocommerce_after_shop_loop_item' => __( 'After product', 'woocommerce-jetpack' ),
						) ),
						$extra_filters
					),
				),
				array(
					'title'   => __( 'Position Order (i.e. Priority)', 'woocommerce-jetpack' ),
					'id'      => 'wcj_product_custom_info_priority_' . $single_or_archive . '_' . $i,
					'default' => 10,
					'type'    => 'number',
				),
				wcj_get_settings_as_multiselect_or_text(
					array(
						'title'    => __( 'Product Categories to Include', 'woocommerce-jetpack' ),
						'desc_tip' => __( 'Leave blank to disable the option.', 'woocommerce-jetpack' ),
						'id'       => 'wcj_product_custom_info_product_cats_to_include_' . $single_or_archive . '_' . $i,
						'default'  => '',
					),
					$product_cats,
					$is_multiselect_cats
				),
				wcj_get_settings_as_multiselect_or_text(
					array(
						'title'    => __( 'Product Categories to Exclude', 'woocommerce-jetpack' ),
						'desc_tip' => __( 'Leave blank to disable the option.', 'woocommerce-jetpack' ),
						'id'       => 'wcj_product_custom_info_product_cats_to_exclude_' . $single_or_archive . '_' . $i,
						'default'  => '',
					),
					$product_cats,
					$is_multiselect_cats
				),
				wcj_get_settings_as_multiselect_or_text(
					array(
						'title'    => __( 'Product Tags to Include', 'woocommerce-jetpack' ),
						'desc_tip' => __( 'Leave blank to disable the option.', 'woocommerce-jetpack' ),
						'id'       => 'wcj_product_custom_info_product_tags_to_include_' . $single_or_archive . '_' . $i,
						'default'  => '',
					),
					$product_tags,
					$is_multiselect_tags
				),
				wcj_get_settings_as_multiselect_or_text(
					array(
						'title'    => __( 'Product Tags to Exclude', 'woocommerce-jetpack' ),
						'desc_tip' => __( 'Leave blank to disable the option.', 'woocommerce-jetpack' ),
						'id'       => 'wcj_product_custom_info_product_tags_to_exclude_' . $single_or_archive . '_' . $i,
						'default'  => '',
					),
					$product_tags,
					$is_multiselect_tags
				),
				wcj_get_settings_as_multiselect_or_text(
					array(
						'title'    => __( 'Products to Include', 'woocommerce-jetpack' ),
						'desc_tip' => __( 'Leave blank to disable the option.', 'woocommerce-jetpack' ),
						'id'       => 'wcj_product_custom_info_products_to_include_' . $single_or_archive . '_' . $i,
						'default'  => '',
					),
					'',
					$is_multiselect_products
				),
				wcj_get_settings_as_multiselect_or_text(
					array(
						'title'    => __( 'Products to Exclude', 'woocommerce-jetpack' ),
						'desc_tip' => __( 'Leave blank to disable the option.', 'woocommerce-jetpack' ),
						'id'       => 'wcj_product_custom_info_products_to_exclude_' . $single_or_archive . '_' . $i,
						'default'  => '',
					),
					'',
					$is_multiselect_products
				),
				array(
					'type' => 'sectionend',
					'id'   => 'wcj_product_custom_info_options_' . $single_or_archive . '_' . $i,
				),
			)
		);
	}
	$settings = array_merge(
		$settings,
		array(
			array(
				'id'   => 'wcj_product_info_' . $single_or_archive,
				'type' => 'tab_end',
			),
			array(
				'id'   => 'wcj_product_info_' . $single_or_archive . '_advanced',
				'type' => 'tab_start',
			),
			array(
				'title' => $single_or_archive_desc . ': ' . __( 'Advanced Options', 'woocommerce-jetpack' ),
				'type'  => 'title',
				'id'    => 'wcj_product_custom_info_advanced_options_' . $single_or_archive,
			),
			array(
				'title'    => __( 'Extra Filters', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'Leave blank to disable.', 'woocommerce-jetpack' ),
				'desc'     => __( 'You can add custom filters here (one per line, in filter|title format).', 'woocommerce-jetpack' ) . '<br>' .
								/* translators: %s: translators Added */
								sprintf( __( 'E.g.: %s.', 'woocommerce-jetpack' ), '<code>rehub_woo_after_compact_grid_title|Rehub: After title</code>' ),
				'id'       => 'wcj_product_custom_info_extra_filters_' . $single_or_archive,
				'default'  => '',
				'type'     => 'textarea',
				'css'      => 'height:100px',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'wcj_product_custom_info_advanced_options_' . $single_or_archive,
			),
			array(
				'id'   => 'wcj_product_info_' . $single_or_archive . '_advanced',
				'type' => 'tab_end',
			),
		)
	);
}
return $settings;
