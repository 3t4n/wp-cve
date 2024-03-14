<?php
/**
 * Product Price by Formula for WooCommerce - Core Class
 *
 * @version 2.3.0
 * @since   1.0.0
 * @author  ProWCPlugins
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ProWC_PPBF_Core' ) ) :

class ProWC_PPBF_Core {

	/**
	 * Constructor.
	 *
	 * @version 2.3.0
	 * @since   1.0.0
	 * @todo    [dev] store settings and meta as serialized values (i.e. `prowc_ppbf_params[$i]` instead of `prowc_ppbf_param_ . $i`)
	 * @todo    [dev] maybe use WC math parser
	 * @todo    [feature] formula presets
	 * @todo    [feature] maybe add option to apply formula to sale or regular price only
	 * @todo    [feature] maybe formula per product category/tag (however we already have `[if_product_taxonomy]` shortcode for this)
	 * @todo    [feature] maybe add formula per variation for variable products
	 * @todo    [feature] maybe add option to calculate price by formula in admin also (i.e. `is_admin ...` as option)
	 * @todo    [feature] maybe add option to choose price filters (e.g. "not apply to regular price")
	 */
	public $is_wc_version_below_3;
	public $admin;
	public $shortcodes;
	public function __construct() {
		$this->is_wc_version_below_3 = version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' );
		if ( 'yes' === get_option( 'prowc_ppbf_enabled', 'yes' ) ) {
			if ( ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) || $this->do_enable_plugin_on_url() ) {
				if ( 0 == ( $priority = get_option( 'prowc_ppbf_filters_priority', 0 ) ) ) {
					$priority = PHP_INT_MAX;
				}
				// Prices
				add_filter( $this->get_price_filter( 'price' ),                    array( $this, 'price_by_formula' ),              $priority, 2 );
				add_filter( $this->get_price_filter( 'sale_price' ),               array( $this, 'price_by_formula' ),              $priority, 2 );
				add_filter( $this->get_price_filter( 'regular_price' ),            array( $this, 'price_by_formula' ),              $priority, 2 );
				// Variations
				add_filter( 'woocommerce_variation_prices_price',                  array( $this, 'price_by_formula' ),              $priority, 2 );
				add_filter( 'woocommerce_variation_prices_regular_price',          array( $this, 'price_by_formula' ),              $priority, 2 );
				add_filter( 'woocommerce_variation_prices_sale_price',             array( $this, 'price_by_formula' ),              $priority, 2 );
				add_filter( 'woocommerce_get_variation_prices_hash',               array( $this, 'get_variation_prices_hash' ),     $priority, 3 );
				if ( ! $this->is_wc_version_below_3 ) {
					add_filter( 'woocommerce_product_variation_get_price',         array( $this, 'price_by_formula' ),              $priority, 2 );
					add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'price_by_formula' ),              $priority, 2 );
					add_filter( 'woocommerce_product_variation_get_sale_price',    array( $this, 'price_by_formula' ),              $priority, 2 );
				}
			}
		}
		if ( is_admin() ) {
			$this->admin = require_once( 'class-prowc-ppbf-admin.php' );
		}
		// Shortcodes
		$this->shortcodes = require_once( 'class-prowc-ppbf-shortcodes.php' );
	}

	/**
	 * do_enable_plugin_on_url.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function do_enable_plugin_on_url() {
		if ( '' != ( $urls = get_option( 'prowc_ppbf_enable_plugin_urls', '' ) ) ) {
			$url  = ( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' );
			$urls = explode( PHP_EOL, $urls );
			return in_array( $url, $urls );
		}
		return false;
	}

	/**
	 * get_price_filter.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_price_filter( $price_type ) {
		switch ( $price_type ) {
			case 'price':
				return ( $this->is_wc_version_below_3 ? 'woocommerce_get_price'         : 'woocommerce_product_get_price' );
			case 'sale_price':
				return ( $this->is_wc_version_below_3 ? 'woocommerce_get_sale_price'    : 'woocommerce_product_get_sale_price' );
			case 'regular_price':
				return ( $this->is_wc_version_below_3 ? 'woocommerce_get_regular_price' : 'woocommerce_product_get_regular_price' );
		}
	}

	/**
	 * get_product_id_or_variation_parent_id.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_product_id_or_variation_parent_id( $_product ) {
		if ( ! $_product || ! is_object( $_product ) ) {
			return 0;
		}
		if ( $this->is_wc_version_below_3 ) {
			return $_product->id;
		} else {
			return ( $_product->is_type( 'variation' ) ) ? $_product->get_parent_id() : $_product->get_id();
		}
	}

	/**
	 * get_product_id.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_product_id( $_product ) {
		if ( ! $_product || ! is_object( $_product ) ) {
			return 0;
		}
		if ( $this->is_wc_version_below_3 ) {
			return ( ! empty( $_product->variation_id ) ? $_product->variation_id : $_product->id );
		} else {
			return $_product->get_id();
		}
	}

	/**
	 * get_product_display_price.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_product_display_price( $_product, $price = '', $qty = 1 ) {
		if ( $this->is_wc_version_below_3 ) {
			return $_product->get_display_price( $price, $qty );
		} else {
			return wc_get_price_to_display( $_product, array( 'price' => $price, 'qty' => $qty ) );
		}
	}

	/**
	 * final_price.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @deprecated 2.0.0
	 */
	function final_price( $price ) {
		switch ( get_option( 'prowc_ppbf_rounding_enabled', 'no' ) ) {
			case 'floor':
				return floor( $price );
			case 'round':
				return round( $price, get_option( 'prowc_ppbf_rounding_precision', get_option( 'woocommerce_price_num_decimals' ) ) );
			case 'ceil':
				return ceil( $price );
			default: // case 'no':
				return $price;
		}
	}

	/**
	 * is_formula_per_product.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function is_formula_per_product( $product_id ) {
		return (
			'no' == apply_filters( 'prowc_ppbf', 'no', 'value_same_formula_for_all_products' ) &&
			( 'per_product' === get_post_meta( $product_id, '_' . 'prowc_ppbf_calculation', true ) || '' === get_post_meta( $product_id, '_' . 'prowc_ppbf_calculation', true ) )
		);
	}

	/**
	 * get_product_formula.
	 *
	 * @version 2.2.0
	 * @since   2.0.0
	 */
	function get_product_formula( $_product ) {
		$product_id = $this->get_product_id_or_variation_parent_id( $_product );
		$formula = ( $this->is_formula_per_product( $product_id ) ? get_post_meta( $product_id, '_' . 'prowc_ppbf_eval', true ) : get_option( 'prowc_ppbf_eval', '' ) );
		$formula = str_replace( array( "\t", "\n", "\r" ), '', $formula );
		return $formula;
	}

	/**
	 * is_params_per_product.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function is_params_per_product( $product_id ) {
		return ( (
				'no' == apply_filters( 'prowc_ppbf', 'no', 'value_same_formula_for_all_products' ) &&
				'global' != get_post_meta( $product_id, '_' . 'prowc_ppbf_calculation', true ) ) ||
			( 'yes' == apply_filters( 'prowc_ppbf', 'no', 'value_same_formula_for_all_products' ) )
		);
	}

	/**
	 * get_product_params.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
	 */
	function get_product_params( $_product ) {
		$product_id             = $this->get_product_id_or_variation_parent_id( $_product );
		$is_formula_per_product = $this->is_formula_per_product( $product_id );
		$is_params_per_product  = $this->is_params_per_product( $product_id );
		$params                 = array();
		$total_params           = ( $is_formula_per_product ? get_post_meta( $product_id, '_' . 'prowc_ppbf_total_params', true ) : get_option( 'prowc_ppbf_total_params', 1 ) );
		for ( $i = 1; $i <= $total_params; $i++ ) {
			$param = ( $is_params_per_product ? get_post_meta( $product_id, '_' . 'prowc_ppbf_param_' . $i, true ) : get_option( 'prowc_ppbf_param_' . $i, '' ) );
			$params[ 'p' . $i ] = $this->do_shortcode( $param );
		}
		return $params;
	}

	/**
	 * evaluate_formula.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function evaluate_formula( $_formula, $output_errors = false ) {
		$price    = ( isset( $this->current_product_price )  ? $this->current_product_price  : false );
		$params   = ( isset( $this->current_product_params ) ? $this->current_product_params : array() );
		$_formula = str_replace( array( 'x', 'p' ), array( '$x', '$p' ), $_formula );
		$math     = new prowc_PPBF_Math();
		if ( false !== $price ) {
			$math->registerVariable( 'x', $price );
		}
		foreach ( $params as $param_id => $param_value ) {
			if ( '' != $param_value ) {
				$math->registerVariable( $param_id, $param_value );
			}
		}
		try {
			return $math->evaluate( $_formula );
		} catch ( Exception $e ) {
			if ( $output_errors ) {
				echo '<p style="color: red; border: 1px solid gray; padding: 10px;">' .
					sprintf( __( '<strong>Error in %s formula:</strong> %s', PPBF_TEXTDOMAIN ),
						'<code>' . $_formula . '</code>', $e->getMessage() ) . '</p>';
			}
		}
	}

	/**
	 * get_current_product_data.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $current_product;
	public $current_product_price;
	public $current_product_id;
	public $current_product_params;
	function get_current_product_data( $price, $product ) {
		$this->current_product          = $product;
		$this->current_product_price    = $price;
		$this->current_product_id       = $this->get_product_id( $product );
		$this->current_product_params   = $this->get_product_params( $product );
	}

	/**
	 * price_by_formula.
	 *
	 * @version 2.3.0
	 * @since   1.0.0
	 * @todo    [dev] store saved products' prices (i.e. optimization, to avoid multiple price recalculations)
	 */
	function price_by_formula( $price, $product, $output_errors = false ) {
		if ( ( '' != $price || 'no' === get_option( 'prowc_ppbf_disable_for_empty_price', 'yes' ) ) && $this->is_price_by_formula_product( $product ) ) {
			if ( 'yes' === get_option( 'prowc_ppbf_check_for_product_changes_price', 'no' ) ) {
				$product_changes = $product->get_changes();
				if ( ! empty( $product_changes ) && isset( $product_changes['price'] ) ) {
					return $price;
				}
			}
			$this->get_current_product_data( $price, $product );
			if ( '' != ( $formula = $this->do_shortcode( $this->get_product_formula( $product ) ) ) ) {
				$price = $this->evaluate_formula( $formula, $output_errors );
			}
		}
		return $this->final_price( $price );
	}

	/**
	 * do_shortcode.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function do_shortcode( $value ) {
		// Deprecated "special values"
		$value = str_replace(
			array( '%total_sales%',         '%stock%',         '%weight%' ),
			array( '[product_total_sales]', '[product_stock]', '[product_weight]' ),
			$value
		);
		// Process "normal" shortcodes
		return do_shortcode( $value );
	}

	/**
	 * get_variation_prices_hash.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 * @todo    [dev] find better solution instead of `time()` (i.e. need to solve hashing for location, date, time, user role etc.)
	 */
	function get_variation_prices_hash( $price_hash, $_product, $display ) {
		if ( $this->is_price_by_formula_product( $_product ) ) {
			$price_hash['prowc_price_by_formula'] = array(
				'time' => time(),
			);
		}
		return $price_hash;
	}

	/**
	 * get_product_term_ids.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_product_term_ids( $product_id, $taxonomy ) {
		$product_term_ids = array();
		$product_terms    = get_the_terms( $product_id, $taxonomy );
		if ( $product_terms && ! is_wp_error( $product_terms ) ) {
			foreach ( $product_terms as $product_term ) {
				$product_term_ids[] = $product_term->term_id;
			}
		}
		return $product_term_ids;
	}

	/**
	 * disable_price_by_formula_for_product.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 * @todo    [dev] (now) check if `array_intersect` is safe to use (in older PHP versions)
	 * @todo    [dev] (now) multiselect
	 * @todo    [dev] (now) tags
	 */
	function disable_price_by_formula_for_product( $_product ) {
		if ( '' != ( $product_ids_to_disable = get_option( 'prowc_ppbf_disable_for_products', '' ) ) ) {
			$product_ids_to_disable = array_map( 'trim', explode( ',', $product_ids_to_disable ) );
			$product_id             = $this->get_product_id_or_variation_parent_id( $_product );
			return in_array( $product_id, $product_ids_to_disable );
		}
		if ( '' != ( $product_cats_ids_to_disable = get_option( 'prowc_ppbf_disable_for_product_cats', '' ) ) ) {
			$product_cats_ids_to_disable = array_map( 'trim', explode( ',', $product_cats_ids_to_disable ) );
			$product_cats_ids            = $this->get_product_term_ids( $this->get_product_id_or_variation_parent_id( $_product ), 'product_cat' );
			return ! empty( array_intersect( $product_cats_ids, $product_cats_ids_to_disable ) );
		}
		return false;
	}

	/**
	 * is_price_by_formula_product.
	 *
	 * @version 2.3.0
	 * @since   1.0.0
	 */
	function is_price_by_formula_product( $_product ) {
		return (
			( 'yes' === apply_filters( 'prowc_ppbf', 'no', 'value_enable_for_all_products' ) && ! $this->disable_price_by_formula_for_product( $_product ) ) ||
			'yes' === get_post_meta( $this->get_product_id_or_variation_parent_id( $_product ), '_' . 'prowc_ppbf_enabled', true )
		);
	}

}

endif;

return new ProWC_PPBF_Core();
