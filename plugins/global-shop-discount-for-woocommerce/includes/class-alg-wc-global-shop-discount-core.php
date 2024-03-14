<?php
/**
 * Global Shop Discount for WooCommerce - Core Class
 *
 * @version 1.9.6
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Global_Shop_Discount_Core' ) ) :

class Alg_WC_Global_Shop_Discount_Core {

	/**
	 * public.
	 *
	 * @version 1.9.5
	 * @since   1.7.0
	 */
	public $is_wc_version_below_3_0_0;
	public $product_get_price_filter;
	public $product_get_sale_price_filter;
	public $product_get_regular_price_filter;
	public $groups;
	public $do_stop_on_first_discount_group;
	public $shortcodes;
	public $gsd_products;
	public $product_prices;

	/**
	 * Constructor.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) fee instead of discount
	 * @todo    (feature) regular price coefficient (`$this->product_get_regular_price_filter, 'woocommerce_variation_prices_regular_price', 'woocommerce_product_variation_get_regular_price'`)
	 * @todo    (feature) "direct price"
	 * @todo    (feature) admin "Products" list: filtering by "on sale": `restrict_manage_posts` + `pre_get_posts`
	 */
	function __construct() {

		// Core
		if ( 'yes' === get_option( 'alg_wc_global_shop_discount_plugin_enabled', 'yes' ) ) {

			if ( $this->is_frontend() || 'yes' === get_option( 'alg_wc_global_shop_discount_load_in_admin', 'no' ) ) {

				$this->init();

				$this->price_hooks( PHP_INT_MAX, false );

				$this->shortcodes = require_once( 'class-alg-wc-global-shop-discount-shortcodes.php' );

			}

		}

		// Tools
		require_once( 'class-alg-wc-global-shop-discount-tools.php' );

	}

	/**
	 * get_product_discount_groups.
	 *
	 * @version 1.9.2
	 * @since   1.9.2
	 */
	function get_product_discount_groups( $product ) {
		return $this->add_global_shop_discount( $this->get_product_price_raw( $product ), $product, 'price', 'get_groups' );
	}

	/**
	 * is_gsd_product.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function is_gsd_product( $product ) {
		$price_raw = $this->get_product_price_raw( $product );
		$price_new = $this->add_global_shop_discount( $price_raw, $product, 'price' );
		return ( $price_raw != $price_new );
	}

	/**
	 * get_product_price_raw.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function get_product_price_raw( $product, $type = '' ) {

		$this->price_hooks( PHP_INT_MAX, false, 'remove_filter' );

		switch ( $type ) {

			case 'sale':
				$price = $product->get_sale_price();
				break;

			case 'regular':
				$price = $product->get_regular_price();
				break;

			default:
				$price = $product->get_price();

		}

		$this->price_hooks( PHP_INT_MAX, false );

		return $price;

	}

	/**
	 * get_gsd_product_ids.
	 *
	 * @version 1.9.0
	 * @since   1.6.0
	 *
	 * @todo    (dev) modify `wc_product_meta_lookup` instead?
	 * @todo    (dev) args: `$transient_expiration = DAY_IN_SECONDS`
	 */
	function get_gsd_product_ids( $product_query_args = array( 'limit' => -1 ), $incl_on_sale = true, $use_transient = false ) {

		$md5 = md5( serialize( array_merge( $product_query_args, array( 'alg_wc_gsd_incl_on_sale' => $incl_on_sale ) ) ) );

		if ( ! isset( $this->gsd_products[ $md5 ] ) ) {

			$transient = false;

			if ( $use_transient ) {
				$transient = get_transient( 'alg_wc_gsd_products_' . $md5 );
			}

			if ( false === $transient ) {

				$this->gsd_products[ $md5 ] = array();

				if ( ! isset( $this->groups ) ) {
					$this->init();
				}

				$all_products = wc_get_products( $product_query_args );

				foreach ( $all_products as $product ) {

					if (
						( $incl_on_sale && $product->is_on_sale() ) ||
						$this->is_gsd_product( $product )
					) {
						$this->gsd_products[ $md5 ][] = $product->get_id();
					}

				}

				if ( $use_transient ) {
					set_transient( 'alg_wc_gsd_products_' . $md5, $this->gsd_products[ $md5 ], DAY_IN_SECONDS );
				}

			} else {

				$this->gsd_products[ $md5 ] = $transient;

			}

		}

		return $this->gsd_products[ $md5 ];

	}

	/**
	 * init.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 */
	function init() {

		// WC version and price filters
		$this->is_wc_version_below_3_0_0        = version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' );
		$this->product_get_price_filter         = ( $this->is_wc_version_below_3_0_0 ? 'woocommerce_get_price'         : 'woocommerce_product_get_price' );
		$this->product_get_sale_price_filter    = ( $this->is_wc_version_below_3_0_0 ? 'woocommerce_get_sale_price'    : 'woocommerce_product_get_sale_price' );
		$this->product_get_regular_price_filter = ( $this->is_wc_version_below_3_0_0 ? 'woocommerce_get_regular_price' : 'woocommerce_product_get_regular_price' );

		// Groups
		$total_groups  = apply_filters( 'alg_wc_global_shop_discount_total_groups', 1 );
		$group_options = array(
			'enabled'          => 'yes',
			'coefficient_type' => 'percent',
			'coefficient'      => 0,
			'round_func'       => '',
			'dates_incl'       => '',
			'product_scope'    => 'all',
			'products_incl'    => array(),
			'products_excl'    => array(),
			'users_incl'       => array(),
			'users_excl'       => array(),
			'user_roles_incl'  => array(),
			'user_roles_excl'  => array(),
		);
		$taxonomies = get_option( 'alg_wc_global_shop_discount_taxonomies', array( 'product_cat', 'product_tag' ) );
		foreach ( $taxonomies as $taxonomy ) {
			$id = $this->get_taxonomy_option_id( $taxonomy );
			$group_options[ "{$id}_incl" ] = array();
			$group_options[ "{$id}_excl" ] = array();
		}
		foreach ( $group_options as $option => $default ) {
			$this->groups[ $option ] = array_slice( get_option( 'alg_wc_global_shop_discount_' . $option, array() ), 0, $total_groups, true );
			for ( $i = 1; $i <= $total_groups; $i++ ) {
				$this->groups[ $option ][ $i ] = ( isset( $this->groups[ $option ][ $i ] ) ? $this->groups[ $option ][ $i ] : $default );
			}
		}

		// Options
		$this->do_stop_on_first_discount_group = ( 'yes' === get_option( 'alg_wc_global_shop_discount_stop_on_first_discount_group', 'yes' ) );

	}

	/**
	 * is_frontend.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function is_frontend() {
		return ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) );
	}

	/**
	 * price_hooks.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) global shipping discount
	 */
	function price_hooks( $priority, $include_shipping = true, $action_func = 'add_filter' ) {

		// Prices
		$action_func( $this->product_get_price_filter,                       array( $this, 'change_price' ),              $priority, 2 );
		$action_func( $this->product_get_sale_price_filter,                  array( $this, 'change_price' ),              $priority, 2 );
		$action_func( $this->product_get_regular_price_filter,               array( $this, 'change_price' ),              $priority, 2 );

		// Variations
		$action_func( 'woocommerce_variation_prices_price',                  array( $this, 'change_price' ),              $priority, 2 );
		$action_func( 'woocommerce_variation_prices_regular_price',          array( $this, 'change_price' ),              $priority, 2 );
		$action_func( 'woocommerce_variation_prices_sale_price',             array( $this, 'change_price' ),              $priority, 2 );
		$action_func( 'woocommerce_get_variation_prices_hash',               array( $this, 'get_variation_prices_hash' ), $priority, 3 );
		if ( ! $this->is_wc_version_below_3_0_0 ) {
			$action_func( 'woocommerce_product_variation_get_price',         array( $this, 'change_price' ),              $priority, 2 );
			$action_func( 'woocommerce_product_variation_get_regular_price', array( $this, 'change_price' ),              $priority, 2 );
			$action_func( 'woocommerce_product_variation_get_sale_price',    array( $this, 'change_price' ),              $priority, 2 );
		}

		// Shipping
		if ( $include_shipping ) {
			$action_func( 'woocommerce_package_rates',                       array( $this, 'change_price_shipping' ),     $priority, 2 );
		}

		// Grouped products
		$action_func( 'woocommerce_get_price_including_tax',                 array( $this, 'change_price_grouped' ),      $priority, 3 );
		$action_func( 'woocommerce_get_price_excluding_tax',                 array( $this, 'change_price_grouped' ),      $priority, 3 );

	}

	/**
	 * get_product_display_price.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_product_display_price( $_product, $price = '', $qty = 1 ) {
		$minus_sign = '';
		if ( $price < 0 ) {
			$minus_sign = '-';
			$price *= -1;
		}
		$display_price = ( $this->is_wc_version_below_3_0_0 ?
			$_product->get_display_price( $price, $qty ) : wc_get_price_to_display( $_product, array( 'price' => $price, 'qty' => $qty ) ) );
		return $minus_sign . $display_price;
	}

	/**
	 * change_price.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function change_price( $price, $_product ) {

		$_current_filter = current_filter();

		if (
			in_array( $_current_filter, array(
				$this->product_get_price_filter,
				'woocommerce_variation_prices_price',
				'woocommerce_product_variation_get_price',
			) )
		) {

			// Price
			return $this->add_global_shop_discount( $price, $_product, 'price' );

		} elseif (
			in_array( $_current_filter, array(
				$this->product_get_sale_price_filter,
				'woocommerce_variation_prices_sale_price',
				'woocommerce_product_variation_get_sale_price',
			) )
		) {

			// Sale price
			return $this->add_global_shop_discount( $price, $_product, 'sale_price' );

		} else {

			// No changes
			return $price;

		}

	}

	/**
	 * change_price_grouped.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function change_price_grouped( $price, $qty, $_product ) {
		if ( $_product->is_type( 'grouped' ) ) {
			foreach ( $_product->get_children() as $child_id ) {
				$the_price   = get_post_meta( $child_id, '_price', true );
				$the_product = wc_get_product( $child_id );
				$the_price   = $this->get_product_display_price( $the_product, $the_price, 1 );
				if ( $the_price == $price ) {
					return $this->add_global_shop_discount( $price, $the_product, 'price' );
				}
			}
		}
		return $price;
	}

	/**
	 * calculate_price.
	 *
	 * @version 1.8.0
	 * @since   1.0.0
	 */
	function calculate_price( $price, $coefficient, $group ) {

		$price = (float) $price;

		// Coefficient
		$return_price = ( 'percent' === $this->groups['coefficient_type'][ $group ] ?
			( $price + $price * ( $coefficient / 100 ) ) : ( $price + $coefficient ) );

		// Rounding
		if ( '' != ( $round_func = $this->groups['round_func'][ $group ] ) ) {
			$return_price = $round_func( $return_price );
		}

		return ( $return_price >= 0 ? $return_price : 0 );

	}

	/**
	 * check_if_applicable.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 *
	 * @return  bool
	 */
	function check_if_applicable( $group, $product, $price, $price_type ) {
		return (
			$this->is_enabled_for_product_group( $product, $group ) &&
			$this->check_if_applicable_by_date( $group ) &&
			$this->check_if_applicable_by_product_scope( $product, $price, $price_type, $this->groups['product_scope'][ $group ] ) &&
			$this->check_if_applicable_by_user( $group ) &&
			$this->check_if_applicable_by_user_role( $group )
		);
	}

	/**
	 * is_enabled_for_product_group.
	 *
	 * @version 1.9.6
	 * @since   1.0.0
	 */
	function is_enabled_for_product_group( $product, $group ) {
		$args = array(
			'include_products' => $this->groups['products_incl'][ $group ],
			'exclude_products' => $this->groups['products_excl'][ $group ],
		);
		$taxonomies = get_option( 'alg_wc_global_shop_discount_taxonomies', array( 'product_cat', 'product_tag' ) );
		foreach ( $taxonomies as $taxonomy ) {
			$id = $this->get_taxonomy_option_id( $taxonomy );
			$args[ "include_{$id}" ] = $this->groups[ "{$id}_incl" ][ $group ];
			$args[ "exclude_{$id}" ] = $this->groups[ "{$id}_excl" ][ $group ];
		}
		return $this->is_enabled_for_product( $product, $args );
	}

	/**
	 * get_taxonomy_option_id.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function get_taxonomy_option_id( $taxonomy ) {
		return ( 'product_cat' === $taxonomy ? 'categories' : ( 'product_tag' === $taxonomy ? 'tags' : $taxonomy ) );
	}

	/**
	 * product_has_term.
	 *
	 * @version 1.9.6
	 * @since   1.0.0
	 *
	 * @see     https://developer.wordpress.org/reference/functions/has_term/
	 */
	function product_has_term( $product_ids, $term_ids, $taxonomy ) {

		// Term IDs
		$term_ids = array_map( 'intval', $this->maybe_convert_to_array( $term_ids ) );

		// Has term?
		foreach ( $product_ids as $product_id ) {
			if ( has_term( $term_ids, $taxonomy, $product_id ) ) {
				return true;
			}
		}

		// False
		return false;

	}

	/**
	 * maybe_convert_to_array.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (dev) pre-calculate this?
	 */
	function maybe_convert_to_array( $value ) {
		return ( ! is_array( $value ) ? array_map( 'trim', explode( ',', $value ) ) : $value );
	}

	/**
	 * is_enabled_for_product.
	 *
	 * @version 1.9.6
	 * @since   1.0.0
	 *
	 * @todo    (feature) by product meta, e.g., `total_sales`
	 */
	function is_enabled_for_product( $product, $args ) {

		$product_ids = ( $product->is_type( 'variation' ) ? array( $product->get_id(), $product->get_parent_id() ) : array( $product->get_id() ) );

		// Products
		if (
			( ! empty( $args['include_products'] ) &&   empty( array_intersect( $product_ids, $this->maybe_convert_to_array( $args['include_products'] ) ) ) ) ||
			( ! empty( $args['exclude_products'] ) && ! empty( array_intersect( $product_ids, $this->maybe_convert_to_array( $args['exclude_products'] ) ) ) )
		) {
			return false;
		}

		// Taxonomies
		$taxonomies = get_option( 'alg_wc_global_shop_discount_taxonomies', array( 'product_cat', 'product_tag' ) );
		foreach ( $taxonomies as $taxonomy ) {
			$id = $this->get_taxonomy_option_id( $taxonomy );
			if (
				( ! empty( $args[ "include_{$id}" ] ) && ! $this->product_has_term( $product_ids, $args[ "include_{$id}" ], $taxonomy ) ) ||
				( ! empty( $args[ "exclude_{$id}" ] ) &&   $this->product_has_term( $product_ids, $args[ "exclude_{$id}" ], $taxonomy ) )
			) {
				return false;
			}
		}

		// All passed
		return true;

	}

	/**
	 * check_if_applicable_by_user.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function check_if_applicable_by_user( $group ) {
		if ( ! empty( $this->groups['users_incl'][ $group ] ) || ! empty( $this->groups['users_excl'][ $group ] ) ) {
			$current_user_id = get_current_user_id();
			if ( ! empty( $this->groups['users_incl'][ $group ] ) ) {
				return (   in_array( $current_user_id, $this->maybe_convert_to_array( $this->groups['users_incl'][ $group ] ) ) );
			} elseif ( ! empty( $this->groups['users_excl'][ $group ] ) ) {
				return ( ! in_array( $current_user_id, $this->maybe_convert_to_array( $this->groups['users_excl'][ $group ] ) ) );
			}
		}
		return true;
	}

	/**
	 * check_if_applicable_by_user_role.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function check_if_applicable_by_user_role( $group ) {
		if ( ! empty( $this->groups['user_roles_incl'][ $group ] ) || ! empty( $this->groups['user_roles_excl'][ $group ] ) ) {
			$user_roles = (array) wp_get_current_user()->roles;
			if ( ! empty( $this->groups['user_roles_incl'][ $group ] ) ) {
				return ( ! empty( array_intersect( $user_roles, $this->groups['user_roles_incl'][ $group ] ) ) );
			} elseif ( ! empty( $this->groups['user_roles_excl'][ $group ] ) ) {
				return (   empty( array_intersect( $user_roles, $this->groups['user_roles_excl'][ $group ] ) ) );
			}
		}
		return true;
	}

	/**
	 * check_if_applicable_by_date.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function check_if_applicable_by_date( $group ) {
		if ( '' !== $this->groups['dates_incl'][ $group ] ) {
			if ( ! isset( $this->current_time ) ) {
				$this->current_time = current_time( 'timestamp' );
			}
			$value = array_map( 'trim', explode( ';', $this->groups['dates_incl'][ $group ] ) );
			foreach ( $value as $_value ) {
				$_value = array_map( 'trim', explode( '-', $_value ) );
				if ( 2 == count( $_value ) ) {
					$start_time  = strtotime( $_value[0], $this->current_time );
					$end_time    = strtotime( $_value[1], $this->current_time );
					$is_in_range = ( $this->current_time >= $start_time && $this->current_time <= $end_time );
					if ( $is_in_range ) {
						return true;
					}
				}
			}
			return false;
		}
		return true;
	}

	/**
	 * check_if_applicable_by_product_scope.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 */
	function check_if_applicable_by_product_scope( $_product, $price, $price_type, $scope ) {
		$return = true;
		if ( 'sale_price' === $price_type ) {
			if ( empty( $price ) ) {
				// The product is currently not on sale
				if ( 'only_on_sale' === $scope ) {
					$return = false;
				}
			} else {
				// The product is currently on sale
				if ( 'only_not_on_sale' === $scope ) {
					$return = false;
				}
			}
		} else { // if ( 'price' === $price_type )
			$sale_price = $this->get_product_price_raw( $_product, 'sale' );
			if ( 'only_on_sale' === $scope && empty( $sale_price ) ) {
				$return = false;
			} elseif ( 'only_not_on_sale' === $scope && ! empty( $sale_price ) ) {
				$return = false;
			}
		}
		return $return;
	}

	/**
	 * add_global_shop_discount.
	 *
	 * @version 1.9.5
	 * @since   1.0.0
	 */
	function add_global_shop_discount( $price, $product, $price_type, $action = 'get_price' ) {

		if ( 'price' === $price_type && '' === $price ) {
			return $price; // no changes
		}

		$do_cache_prices = ( 'get_price' === $action && 'yes' === get_option( 'alg_wc_global_shop_discount_cache_product_prices', 'no' ) );
		if ( $do_cache_prices ) {
			$currency = get_woocommerce_currency();
			if ( isset( $this->product_prices[ $product->get_id() ][ $price_type ][ $currency ] ) ) {
				return $this->product_prices[ $product->get_id() ][ $price_type ][ $currency ];
			}
		}

		if ( 'get_groups' === $action ) {
			$groups = array();
		}

		for ( $i = 1; $i <= apply_filters( 'alg_wc_global_shop_discount_total_groups', 1 ); $i++ ) {

			if (
				'yes' === $this->groups['enabled'][ $i ] &&
				( $coef = $this->groups['coefficient'][ $i ] ) &&
				! empty( $coef ) &&
				$this->check_if_applicable( $i, $product, $price, $price_type )
			) {

				// Discount group applied
				if ( 'get_groups' === $action ) {
					$groups[] = $i;
				} else {
					$_price = ( 'sale_price' === $price_type && empty( $price ) ? $product->get_regular_price() : $price );
					$price  = $this->calculate_price( $_price, $coef, $i );
				}

				// Maybe stop on first matching discount group
				if ( $this->do_stop_on_first_discount_group ) {
					if ( $do_cache_prices ) {
						$this->product_prices[ $product->get_id() ][ $price_type ][ $currency ] = $price;
					}
					return ( 'get_groups' === $action ? $groups : $price );
				}

			}

		}

		if ( $do_cache_prices ) {
			$this->product_prices[ $product->get_id() ][ $price_type ][ $currency ] = $price;
		}
		return ( 'get_groups' === $action ? $groups : $price );

	}

	/**
	 * get_variation_prices_hash.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_variation_prices_hash( $price_hash, $_product, $display ) {
		$price_hash['alg_wc_global_shop_discount_price_hash']['groups']                       = $this->groups;
		$price_hash['alg_wc_global_shop_discount_price_hash']['stop_on_first_discount_group'] = $this->do_stop_on_first_discount_group;
		return $price_hash;
	}

}

endif;

return new Alg_WC_Global_Shop_Discount_Core();
