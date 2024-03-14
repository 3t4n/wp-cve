<?php
/**
 * Filters
 *
 * @author   Codection
 * @category Root
 * @package  Products Restricted Users from WooCommerce
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPRU_Filters {
	/**
	 * Constructor
	 **/
	public function __construct() {
	}

	/**
	 * Hooks declaration
	 **/
	public function hooks() {
		add_filter( 'woocommerce_product_is_visible', array( $this, 'filter_product_visibility' ), 10, 2 );
		add_action( 'template_redirect', array( $this, 'restrict_single_product' ), 10, 2 );
		add_filter( 'pre_get_posts', array( $this, 'filter_from_archives' ), 9999, 1 );
		add_filter( 'woocommerce_is_purchasable', array( $this, 'is_purchasable' ), PHP_INT_MAX, 2 );
	}

	/**
	 * Filter product visibility
	 *
	 * @param bool    $visible  If it is visible.
	 * @param integer $product_id The product id.
	 **/
	public function filter_product_visibility( $visible, $product_id ) {
		if ( $this->excluded_roles() ) {
			return $visible;
		}

		$restricted_product = new WPRU_Restricted_Product( $product_id );
		if ( $restricted_product->is_visible( $product_id ) ) {
			return $visible;
		}

		return ( in_array( get_current_user_id( $product_id ), $restricted_product->get_users() ) );
	}

	/**
	 * Action to restrict a single product.
	 **/
	public function restrict_single_product() {
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return;
		}

		if ( $this->excluded_roles() ) {
			return;
		}

		if ( is_product() ) {
			$restricted_product = new WPRU_Restricted_Product();
			if ( $restricted_product->is_visible() ) {
				return;
			}

			if ( ! is_user_logged_in() ) {
				wp_safe_redirect( home_url() );
				die;
			} else {
				if ( ! in_array( get_current_user_id(), $restricted_product->get_users() ) ) {
					wp_safe_redirect( home_url() );
					die;
				}
			}
		}
	}

	/**
	 * Get allowed products for a user
	 *
	 * @param integer $user_id The user_id, if not is used, it will be used the current user id.
	 **/
	public static function get_allowed_products( $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$allowed_products = get_user_meta( $user_id, 'wpru_allowed_products', true );

		if ( empty( $allowed_products ) ) {
			return array();
		} else {
			return $allowed_products;
		}
	}

	/**
	 * Get disallowed products for a user
	 *
	 * @param integer $user_id The user_id, if not is used, it will be used the current user id.
	 **/
	public static function get_disallowed_products( $user_id = '' ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$allowed_products = get_user_meta( $user_id, 'wpru_allowed_products', true );
		$activated_products = get_posts(
			array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
				'fields' => 'ids',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => 'wpru_enable',
						'value'   => true,
						'compare' => '=',
					),
					array(
						'key'     => 'wpru_mode',
						'value'   => 'buy',
						'compare' => '!=',
					),
				),
			),
		);

		if ( ! is_array( $allowed_products ) ) {
			$allowed_products = array();
		}

		return array_diff( $activated_products, $allowed_products );
	}

	/**
	 * Get excluded roles of restrictions.
	 **/
	public function excluded_roles() {
		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();

			if ( empty( array_intersect( (array) $user->roles, array( 'administrator', 'shop_manager' ) ) ) ) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * Filter products from archives in the query.
	 *
	 * @param WP_Query $q The query.
	 **/
	public function filter_from_archives( $q ) {
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return $q;
		}

		if ( empty( $q ) ) {
			return;
		}

		if ( is_admin() ) {
			return;
		}

		if ( ! $q->is_main_query() ) {
			return;
		}

		if ( $this->excluded_roles() ) {
			return;
		}

		if ( ! is_post_type_archive( 'product' ) && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		$q->set( 'post__not_in', self::get_disallowed_products() );
	}

	/**
	 * If is purchasable a product.
	 *
	 * @param bool       $is_purchasable if the product is purchasable.
	 * @param WC_Product $product the product.
	 **/
	public function is_purchasable( $is_purchasable, $product ) {
		if ( $this->excluded_roles() ) {
			return $is_purchasable;
		}

		$restricted_product = new WPRU_Restricted_Product( $product->get_id() );
		if ( ! $restricted_product->is_purchasable() ) {
			return false;
		}

		return $is_purchasable;
	}
}