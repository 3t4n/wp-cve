<?php
/**
 * WooCommerce Custom Price Label
 *
 * @version 2.4.0
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Custom_Price_Label' ) ) :

class WC_Custom_Price_Label {

	/**
	 * Constructor.
	 *
	 * @version 2.3.0
	 */
	function __construct() {
		if ( 'yes' === get_option( 'woocommerce_custom_price_label_enabled', 'yes' ) ) {
			add_filter( 'woocommerce_cart_product_price',        array( $this, 'custom_price' ), PHP_INT_MAX, 2 );
			add_filter( 'woocommerce_get_price_html',            array( $this, 'custom_price' ), PHP_INT_MAX, 2 );
			add_filter( 'woocommerce_get_variation_price_html',  array( $this, 'custom_price' ), PHP_INT_MAX, 2 ); // used only in below WooCommerce v3.0.0
		}
	}

	/**
	 * is_bot.
	 *
	 * @version 2.3.0
	 * @since   2.2.0
	 */
	function is_bot() {
		$bots = '/Google-Structured-Data-Testing-Tool|bot|crawl|slurp|spider/i';
		return ( isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( $bots, $_SERVER['HTTP_USER_AGENT'] ) );
	}

	/*
	 * front end.
	 *
	 * @version 2.3.0
	 */
	function custom_price( $price, $product ) {
		if ( is_admin() ) {
			return $price;
		}
		if ( 'yes' === get_option( 'woocommerce_custom_price_label_disable_for_bots', 'no' ) && $this->is_bot() ) {
			return $price;
		}
		$do_override_global_with_local = get_option( 'woocommerce_custom_price_label_override_global_with_local', 'no' );
		if ( 'yes' === $do_override_global_with_local ) {
			$original_price = $price;
		}
		$price = $this->apply_global_price_labels( $price, $product );
		if ( 'yes' === $do_override_global_with_local ) {
			$global_price = $price;
			$local_price = $this->apply_local_price_labels( $original_price, $product );
			$price = ( $local_price != $original_price ) ? $local_price : $global_price;
		} else {
			$price = $this->apply_local_price_labels( $price, $product );
		}
		return do_shortcode( $price );
	}

	/**
	 * get_current_user_first_role.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 * @todo    check all user roles instead of first one?
	 */
	function get_current_user_first_role() {
		$current_user = wp_get_current_user();
		return ( isset( $current_user->roles[0] ) && '' != $current_user->roles[0] ) ? $current_user->roles[0] : 'guest';
	}

	/*
	 * check_by_page_visibility.
	 *
	 * @version 2.4.0
	 * @since   2.3.0
	 * @todo    replace `is_single()` with `is_product()`
	 */
	function check_by_page_visibility( $product, $current_filter_name, $visibilities_array ) {
		if (
			( in_array( 'home',      $visibilities_array ) && is_front_page() ) ||
			( in_array( 'products',  $visibilities_array ) && is_archive() ) ||
			( in_array( 'single',    $visibilities_array ) && is_single() ) ||
			( in_array( 'related',   $visibilities_array ) && is_single() && ! is_single( get_the_ID() ) ) ||
			( in_array( 'page',      $visibilities_array ) && is_page() && ! is_front_page() ) ||
			( in_array( 'cart',      $visibilities_array ) && 'woocommerce_cart_product_price' === $current_filter_name ) ||
			( in_array( 'variation', $visibilities_array ) && 'woocommerce_get_price_html'     === $current_filter_name && $product->is_type( 'variation' ) || 'woocommerce_get_variation_price_html' === $current_filter_name ) ||
			( in_array( 'variable',  $visibilities_array ) && 'woocommerce_get_price_html'     === $current_filter_name && $product->is_type( 'variable' ) )
		) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 * get_array_from_checkboxes_hide_on - for deprecated checkboxes.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_array_from_checkboxes_hide_on( $labels_array ) {
		$result_array = array();
		if ( 'on' === $labels_array['variation_home'] ) {
			$result_array[] = 'home';
		}
		if ( 'on' === $labels_array['variation_products'] ) {
			$result_array[] = 'products';
		}
		if ( 'on' === $labels_array['variation_single'] ) {
			$result_array[] = 'single';
		}
		if ( 'on' === $labels_array['variation_page'] ) {
			$result_array[] = 'page';
		}
		if ( 'on' === $labels_array['variation_cart'] ) {
			$result_array[] = 'cart';
		}
		if ( 'on' === $labels_array['variation_variation'] ) {
			$result_array[] = 'variation';
		}
		if ( 'on' === $labels_array['variation_variable'] ) {
			$result_array[] = 'variable';
		}
		return $result_array;
	}

	/*
	 * do_apply_price_labels_by_page_visibilities.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function do_apply_price_labels_by_page_visibilities( $product, $scope, $labels_array = array() ) {
		$current_filter_name = current_filter();
		if ( 'local' === $scope ) {
			$disabled_options = get_option( 'woocommerce_custom_price_labels_disabled_options', '' );
		}
		// Hide on...
		$hide_on = array();
		if ( 'global' === $scope ) {
			$hide_on = get_option( 'alg_woocommerce_global_price_labels_hide_on', '' );
		} elseif ( empty( $disabled_options ) || ! in_array( '_hide_on', $disabled_options ) ) { // 'local'
			$hide_on = $labels_array['variation_hide_on'];
			if ( empty( $hide_on ) && version_compare( $this->local_price_labels_version, '2.3.0', '<' ) ) {
				$hide_on = $this->get_array_from_checkboxes_hide_on( $labels_array );
			}
		}
		if ( ! empty( $hide_on ) ) {
			if ( $this->check_by_page_visibility( $product, $current_filter_name, $hide_on ) ) {
				return false;
			}
		}
		// Show on...
		$show_on = array();
		if ( 'global' === $scope ) {
			$show_on = get_option( 'alg_woocommerce_global_price_labels_show_on', '' );
		} elseif ( empty( $disabled_options ) || ! in_array( '_show_on', $disabled_options ) ) { // 'local'
			$show_on = $labels_array['variation_show_on'];
		}
		if ( ! empty( $show_on ) ) {
			return $this->check_by_page_visibility( $product, $current_filter_name, $show_on );
		}
		return true;
	}

	/*
	 * apply_global_price_labels.
	 *
	 * @version 2.4.0
	 */
	function apply_global_price_labels( $price, $product ) {
		// Check if global price labels are enabled
		if ( 'no' === get_option( 'woocommerce_global_price_labels_enabled', 'yes') ) {
			return $price;
		}
		// Check Roles
		$current_user_role = $this->get_current_user_first_role();
		$roles_to_hide = get_option( 'alg_woocommerce_global_price_labels_roles_to_hide', '' );
		$roles_to_show = get_option( 'alg_woocommerce_global_price_labels_roles_to_show', '' );
		if ( ! empty( $roles_to_hide ) && in_array( $current_user_role, $roles_to_hide ) ) {
			return $price;
		}
		if ( ! empty( $roles_to_show ) && ! in_array( $current_user_role, $roles_to_show ) ) {
			return $price;
		}
		// Check Page Visibilities
		if ( ! $this->do_apply_price_labels_by_page_visibilities( $product, 'global' ) ) {
			return $price;
		}
		// Apply labels
		if ( '' != ( $label = get_option( 'woocommerce_global_price_labels_add_before_text', '' ) ) ) {
			$price = $label . $price;
		}
		if ( '' != ( $label = get_option( 'woocommerce_global_price_labels_add_after_text', '' ) ) ) {
			$price = $price . $label;
		}
		if ( 'custom_price_labels' === apply_filters( 'alg_wc_custom_price_labels', 'custom_price_labels', 'settings' ) ) {
			return $price;
		}
		if ( '' != ( $label = get_option( 'woocommerce_global_price_labels_between_regular_and_sale_text', '' ) ) ) {
			$price = str_replace( '</del> <ins>', '</del>' . $label . '<ins>', $price );
		}
		if ( '' != ( $text_to_remove = get_option( 'woocommerce_global_price_labels_remove_text', '' ) ) ) {
			$price = str_replace( $text_to_remove, '', $price );
		}
		if ( '' != ( $text_to_replace = get_option( 'woocommerce_global_price_labels_replace_text', '' ) ) &&
		     '' != ( $text_to_replace_with = get_option( 'woocommerce_global_price_labels_replace_with_text', '' ) ) ) {
			$price = str_replace( $text_to_replace, $text_to_replace_with, $price );
		}
		return $price;
	}

	/*
	 * apply_local_price_labels.
	 *
	 * @version 2.4.0
	 */
	function apply_local_price_labels( $price, $product ) {
		// Check if local price labels are enabled
		if ( 'no' === get_option( 'woocommerce_local_price_labels_enabled', 'yes' ) ) {
			return $price;
		}
		$disabled_options = get_option( 'woocommerce_custom_price_labels_disabled_options', '' );
		$product_id = ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ?
			$product->id :
			( $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id() )
		);
		$this->local_price_labels_version = get_post_meta( $product_id, '_' . 'woocommerce_custom_price_labels_version', true );
		// Local price labels
		$options_section_variations = array_merge( alg_get_options_section_variations(), alg_get_options_section_variations_deprecated() );
		foreach ( alg_get_options_sections() as $options_section => $options_section_title ) {
			$labels_array = array();
			foreach ( $options_section_variations as $options_section_variation => $options_section_variation_title ) {
				$option_name = alg_get_options_group_name() . $options_section . $options_section_variation;
				$labels_array[ 'variation' . $options_section_variation ] = get_post_meta( $product_id, '_' . $option_name, true );
			}
			if ( 'on' === $labels_array[ 'variation' ] ) {
				$current_user_role = $this->get_current_user_first_role();
				if (
					! $this->do_apply_price_labels_by_page_visibilities( $product, 'local', $labels_array ) ||
					( ( empty( $disabled_options ) || ! in_array( '_roles_to_hide', $disabled_options ) ) && ! empty( $labels_array['variation_roles_to_hide'] ) &&   in_array( $current_user_role, $labels_array['variation_roles_to_hide'] ) ) ||
					( ( empty( $disabled_options ) || ! in_array( '_roles_to_show', $disabled_options ) ) && ! empty( $labels_array['variation_roles_to_show'] ) && ! in_array( $current_user_role, $labels_array['variation_roles_to_show'] ) )
				) {
					continue;
				}
				$custom_label = $this->maybe_wrap_local_price_label( $labels_array['variation_text'], $options_section );
				$price = $this->customize_price( $price, $options_section, $custom_label );
			}
		}
		return $price;
	}

	/*
	 * maybe_wrap_local_price_label
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function maybe_wrap_local_price_label( $custom_label, $options_section ) {
		$options_sections_ids = alg_get_options_sections_ids();
		if ( '' != $custom_label && 'yes' === get_option( 'woocommerce_local_price_labels_wrap_enabled', 'no' ) ) {
			$section_id = $options_sections_ids[ $options_section ];
			$custom_label =
				get_option( 'woocommerce_local_price_labels_wrap_' . $section_id . '_prepend', '<span class="alg-price-label-' . $section_id . '">' ) .
				$custom_label .
				get_option( 'woocommerce_local_price_labels_wrap_' . $section_id . '_append',  '</span>' );
		}
		return $custom_label;
	}

	/*
	 * customize_price - per product.
	 *
	 * @version 2.4.0
	 * @todo    remove `str_replace( 'From: ', '', $price );`
	 */
	function customize_price( $price, $options_section, $custom_label ) {
		switch ( $options_section ) {
			case '':
				$price = $custom_label;
				break;
			case '_before':
				$price = $custom_label . $price;
				break;
			case '_between':
				$price = apply_filters( 'alg_wc_custom_price_labels', $price, 'per_product_between', array( 'custom_label' => $custom_label ) );
				break;
			case '_after':
				$price = apply_filters( 'alg_wc_custom_price_labels', $price, 'per_product_after', array( 'custom_label' => $custom_label ) );
				break;
		}
		return str_replace( 'From: ', '', $price );
	}
}

endif;

return new WC_Custom_Price_Label();
