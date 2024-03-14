<?php
/**
 * AffiliateX Helper Functions.
 *
 * General helper functions avaiable on both the front-end and backend.
 *
 * @package AffiliateX\Functions
 *
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if AffiliateX Pro is activated?
 *
 * @return void
 */
function affx_is_pro_activated() {

	$pro_activated = class_exists( 'AffiliateX_Pro\AffiliateX_Pro' );

	return $pro_activated;
}

/**
 * Check if AffiliateX Pro license is activated?
 *
 * @return void
 */
function affx_is_pro_license_activated() {

	$pro_activated = affx_is_pro_activated();

	if ( $pro_activated ) {
		$updater        = new AffiliateX_Pro\AffiliateX_Pro_Updater();
		$license_status = $updater->check_license();

		$pro_license_activated = isset( $license_status['status'] ) && ( $license_status['status'] == 'valid' 
		|| $license_status['status'] == 'expired' ) ? true : false;
	} else {
		$pro_license_activated = false;
	}

	return $pro_license_activated;
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function affx_clean_vars( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'affx_clean_vars', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * Merge user defined arguments into defaults array.
 * Similar to wp_parse_args() just a bit extended to work with multidimensional arrays.
 *
 * @since 1.0.0
 *
 * @param array $args      (Required) Value to merge with $defaults.
 * @param array $defaults  Array that serves as the defaults. Default value: ''
 *
 * @return void
 */
function affx_wp_parse_args( &$args, $defaults = '' ) {
	$args     = (array) $args;
	$defaults = (array) $defaults;
	$result   = $defaults;

	foreach ( $args as $key => &$value ) {
		if ( is_array( $value ) && ! empty( $value ) && isset( $result[ $key ] ) ) {
			$result[ $key ] = affx_wp_parse_args( $value, $result[ $key ] );
		} else {
			$result[ $key ] = $value;
		}
	}
	return $result;
}

/**
 * AffiliateX get block settings.
 *
 * @return void
 */
function affx_get_block_settings( $encode = false ) {

	$settings = get_option( 'affiliatex_block_settings' ) ? json_decode( get_option( 'affiliatex_block_settings' ), true ) : array();

	$block_defaults = apply_filters(
		'affiliatex_block_settings_defaults',
		array(
			'buttons'                  => true,
			'prosAndCons'              => true,
			'cta'                      => true,
			'notice'                   => true,
			'verdict'                  => true,
			'singleProduct'            => true,
			'specifications'           => true,
			'versusLine'               => true,
			'singleProductProsAndCons' => true,
			'productImageButton'       => true,
			'singleCoupon'             => true,
			'couponGrid'               => true,
			'productTabs'              => true,
			'couponListing'            => true,
			'topProducts'              => true,
			'versus'                   => true,
			'productTable'             => true,
			'productComparison'        => true
		)
	);

	$settings = affx_wp_parse_args( $settings, $block_defaults );

	return $encode ? json_encode( $settings ) : $settings;
}

/**
 * AffiliateX get customization settings.
 *
 * @return void
 */
function affx_get_customization_settings( $encode = false ) {

	$settings = get_option( 'affiliatex_customization_settings' ) ? json_decode( get_option( 'affiliatex_customization_settings' ), true ) : array();

	$customization_defaults = apply_filters(
		'affiliatex_customization_settings_defaults',
		array(
			'typography'               => array(
				'family'          => 'Default',
				'variation'       => 'n4',

				'size'            => array(
					'desktop' => '18px',
					'mobile'  => '18px',
					'tablet'  => '18px',
				),
				'line-height'     => array(
					'desktop' => '1.65',
					'mobile'  => '1.65',
					'tablet'  => '1.65',
				),
				'letter-spacing'  => array(
					'desktop' => '0em',
					'mobile'  => '0em',
					'tablet'  => '0em',
				),
				'text-transform'  => 'none',
				'text-decoration' => 'none',
			),
			'fontColor'                => '#292929',
			'btnColor'                 => '#2670FF',
			'btnHoverColor'            => '#084ACA',
			'editorWidth'              => 'inherit',
			'editorCustomWidth'        => '1170',
			'editorCustomSidebarWidth' => '330',
			'editorSidebarWidth'       => 'inherit',
			'disableFontAwesome'       => false,
		)
	);

	$settings = affx_wp_parse_args( $settings, $customization_defaults );

	return $encode ? json_encode( $settings ) : $settings;
}

/**
 * Has block function which searches as well in reusable blocks.
 *
 * @param mixed $block_name Full Block type to look for.
 * @return bool
 */
function affx_has_block( $block_name ) {

	if ( has_block( $block_name ) ) {
		return true;
	}

	if ( has_block( 'core/block' ) ) {
		$content = get_post_field( 'post_content' );
		$blocks  = parse_blocks( $content );
		return affx_search_reusable_blocks_within_innerblocks( $blocks, $block_name );
	}

	return false;
}

/**
 * Search for the selected block within inner blocks.
 *
 * The helper function for affx_has_block() function.
 *
 * @param array  $blocks Blocks to loop through.
 * @param string $block_name Full Block type to look for.
 * @return bool
 */
function affx_search_reusable_blocks_within_innerblocks( $blocks, $block_name ) {
	foreach ( $blocks as $block ) {
		if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) ) {
			affx_search_reusable_blocks_within_innerblocks( $block['innerBlocks'], $block_name );
		} elseif ( 'core/block' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) && has_block( $block_name, $block['attrs']['ref'] ) ) {
			return true;
		}
	}

	return false;
}

/**
 * AffiliateX get disabled blocks.
 *
 * @return void
 */
function affx_get_disabled_blocks() {

	$blocks = array(
		'buttons'                  => 'affiliatex/buttons',
		'prosAndCons'              => 'affiliatex/pros-and-cons',
		'cta'                      => 'affiliatex/cta',
		'notice'                   => 'affiliatex/notice',
		'verdict'                  => 'affiliatex/verdict',
		'singleProduct'            => 'affiliatex/single-product',
		'specifications'           => 'affiliatex/specifications',
		'versusLine'               => 'affiliatex/versus-line',
		'singleProductProsAndCons' => 'affiliatex/single-product-pros-and-cons',
		'productImageButton'       => 'affiliatex/product-image-button',
		'singleCoupon'             => 'affiliatex/single-coupon',
		'couponGrid'               => 'affiliatex/coupon-grid',
		'productTabs'              => 'affiliatex/product-tabs',
		'couponListing'            => 'affiliatex/coupon-listing',
		'topProducts'              => 'affiliatex/top-products',
		'versus'                   => 'affiliatex/versus',
		'productTable'             => 'affiliatex/product-table',
		'productComparison'        => 'affiliatex/product-comparison'
	);

	$pro_blocks = array( 'singleProductProsAndCons', 'productImageButton', 'singleCoupon', 'couponGrid', 'productTabs', 'couponListing', 'topProducts', 'versus' );

	$license_activated = affx_is_pro_license_activated();

	$block_settings = affx_get_block_settings();

	$disabled_blocks = array();

	foreach ( $block_settings as $key => $value ) {
		if ( ! $value || ( ! $license_activated && in_array( $key, $pro_blocks ) ) ) {
			if ( isset( $blocks[ $key ] ) && ! affx_has_block( $blocks[ $key ] ) ) {
				$disabled_blocks[] = $blocks[ $key ];
			}
		}
	}

	return $disabled_blocks;
}

/**
 * Is_affiliatex_block - Returns true when viewing a recipe block page.
 *
 * @return bool
 */
function is_affiliatex_block() {
	$affx_block =
		has_block( 'affiliatex/buttons' ) ||
		has_block( 'affiliatex/pros-and-cons' ) ||
		has_block( 'affiliatex/cta' ) ||
		has_block( 'affiliatex/notice' ) ||
		has_block( 'affiliatex/verdict' ) ||
		has_block( 'affiliatex/single-product' ) ||
		has_block( 'affiliatex/specifications' ) ||
		has_block( 'affiliatex/versus-line' ) ||
		has_block( 'affiliatex/single-product-pros-and-cons' ) ||
		has_block( 'affiliatex/product-image-button' ) ||
		has_block( 'affiliatex/single-coupon' ) ||
		has_block( 'affiliatex/coupon-grid' ) ||
		has_block( 'affiliatex/product-tabs' ) ||
		has_block( 'affiliatex/coupon-listing' ) ||
		has_block( 'affiliatex/top-products' ) ||
		has_block( 'affiliatex/versus' ) ||
		has_block( 'affiliatex/product-table' ) ||
		has_block( 'affiliatex/product-comparison' );

	return apply_filters( 'is_affiliatex_block', $affx_block );
}
