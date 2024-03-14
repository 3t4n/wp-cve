<?php
/**
 * Product Price by Formula for WooCommerce - Shortcodes Class
 *
 * @version 2.3.2
 * @since   2.0.0
 * @author  ProWCPlugins
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ProWC_PPBF_Shortcodes' ) ) :

class ProWC_PPBF_Shortcodes {

	/**
	 * Constructor.
	 *
	 * @version 2.3.2
	 * @since   2.0.0
	 * @todo    [dev] (maybe) remove `evaluate_formula` from all `math_` shortcodes atts and content?
	 * @todo    [feature] add `[if_product_on_sale]` shortcode (to implement will need to remove and re-add price filters)
	 * @todo    [feature] add `[if_day]` shortcode (i.e. day of the week)
	 * @todo    [feature] add `[if_in_cart]` shortcode (i.e. quantity in cart affecting the price) (#12885, #13072)
	 */
	public $shortcodes_prefix;
	public $shortcodes;
	public $extra_shortcodes;
	public function __construct() {
		$this->shortcodes_prefix = get_option( 'prowc_ppbf_shortcodes_prefix', '' );
		$this->shortcodes = array(

			'math_round',
			'math_ceil',
			'math_floor',
			'math_min',
			'math_max',

			'product_meta',
			'product_weight',
			'product_height',
			'product_width',
			'product_length',
			'product_stock',
			'product_total_sales',
			'product_id',
			'product_attr',
			'product_date',

			'option',

			'timestamp',
			'to_timestamp',

			'if_customer_location',
			'if_user_role',
			'if_user_id',
			'if_time',
			'if_date',

		);
		$this->extra_shortcodes = array(

			'if_value',
			'if_product_id',
			'if_product_meta',
			'if_product_category',
			'if_product_tag',
			'if_product_taxonomy',
			'if_regular_price',
			'if_sale_price',
			'if_current_filter',

		);
		foreach ( array_merge( $this->shortcodes, $this->extra_shortcodes ) as $shortcode ) {
			add_shortcode( $this->shortcodes_prefix . $shortcode, array( $this, $shortcode ) );
		}
	}

	/**
	 * product_date.
	 *
	 * @version 2.3.2
	 * @since   2.3.2
	 */
	function product_date( $atts, $content = '' ) {
		// E.g.: `x[if_date from="0" to="{product_date} +6 months"]*0.9[/if_date]`
		$product_id = ( isset( $atts['product_id'] ) ? $atts['product_id'] : prowc_ppbf()->core->current_product_id );
		if ( isset( $atts['use_parent_product'] ) && 'yes' === $atts['use_parent_product'] && 0 != ( $parent_product_id = wp_get_post_parent_id( $product_id ) ) ) {
			$product_id = $parent_product_id;
		}
		return get_the_date( ( isset( $atts['date_format'] ) ? $atts['date_format'] : '' ), $product_id );
	}

	/**
	 * option.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function option( $atts, $content = '' ) {
		return ( isset( $atts['name'] ) ? get_option( $atts['name'], ( isset( $atts['default'] ) ? $atts['default'] : '' ) ) : '' );
	}

	/**
	 * timestamp.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function timestamp( $atts, $content = '' ) {
		$offset = ( isset( $atts['offset'] ) ? $atts['offset'] : 0 );
		return ( time() + $offset );
	}

	/**
	 * to_timestamp.
	 *
	 * @version 2.3.2
	 * @since   2.2.0
	 * @todo    [dev] (maybe) rename / add alias: `[strtotime]`
	 */
	function to_timestamp( $atts, $content = '' ) {
		if ( ! isset( $atts['value'] ) ) {
			return '';
		}
		$value  = prowc_ppbf_process_atts_shortcodes( $atts['value'] );
		$offset = ( isset( $atts['offset'] ) ? $atts['offset'] : 0 );
		return strtotime( $value ) + $offset;
	}

	/**
	 * if_regular_price.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 * @todo    [fix] in "Final price preview"
	 */
	function if_regular_price( $atts, $content = '' ) {
		// E.g.: `x[if_regular_price]*2[/if_regular_price][if_sale_price]/2[/if_sale_price]`
		return apply_filters( 'prowc_ppbf', '', 'if_type_price', array( 'price_type' => 'regular_price', 'current_filter' => current_filter(), 'content' => $content ) );
	}

	/**
	 * if_sale_price.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 * @todo    [fix] in "Final price preview"
	 */
	function if_sale_price( $atts, $content = '' ) {
		// E.g.: `x[if_regular_price]*2[/if_regular_price][if_sale_price]/2[/if_sale_price]`
		return apply_filters( 'prowc_ppbf', '', 'if_type_price', array( 'price_type' => 'sale_price', 'current_filter' => current_filter(), 'content' => $content ) );
	}

	/**
	 * if_current_filter.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 * @todo    [dev] rename / add alias: `[if_price_filter]`
	 */
	function if_current_filter( $atts, $content = '' ) {
		if ( ! isset( $atts['current_filter'] ) ) {
			return '';
		}
		return apply_filters( 'prowc_ppbf', '', 'if_current_filter', array( 'current_filter' => current_filter(), 'atts' => $atts, 'content' => $content ) );
	}

	/**
	 * product_id.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function product_id( $atts, $content = '' ) {
		return prowc_ppbf()->core->current_product_id;
	}

	/**
	 * if_product_id.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function if_product_id( $atts, $content = '' ) {
		// E.g.: `[if_product_id compare_operator="in" compare_to_value="100,101,102"]p1[/if_product_id][if_product_id compare_operator="not_in" compare_to_value="100,101,102"]p2[/if_product_id]`
		$atts['value'] = '{product_id}';
		return apply_filters( 'prowc_ppbf', '', 'if_value', array( 'atts' => $atts, 'content' => $content ) );
	}

	/**
	 * if_product_meta.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function if_product_meta( $atts, $content = '' ) {
		// E.g.: `[if_product_meta key="_weight" compare_operator="less" compare_to_value="100"]p1[/if_product_meta][if_product_meta key="_weight" compare_operator="greater_or_equal" compare_to_value="100"]p2[/if_product_meta]`
		if ( ! isset( $atts['key'] ) ) {
			return '';
		}
		$atts['value'] = $this->product_meta( $atts );
		return apply_filters( 'prowc_ppbf', '', 'if_value', array( 'atts' => $atts, 'content' => $content ) );
	}

	/**
	 * if_value.
	 *
	 * @version 2.1.2
	 * @since   2.1.2
	 */
	function if_value( $atts, $content = '' ) {
		// E.g.: `[if_value value="{product_meta key='_my_meta_key'}" compare_operator="less" compare_to_value="8.4"][product_meta key="_my_price_key"]*4[/if_value][if_value value="{product_meta key='_my_meta_key'}" compare_operator="more_or_equal" compare_to_value="8.4"][product_meta key="_my_price_key"]*3[/if_value]`
		return apply_filters( 'prowc_ppbf', '', 'if_value', array( 'atts' => $atts, 'content' => $content ) );
	}

	/**
	 * convert_to_numeric.
	 *
	 * @version 2.1.2
	 * @since   2.1.2
	 * @todo    [dev] maybe `round( $result )` in case of fraction
	 * @todo    [dev] maybe handle more complex fractions, e.g.: `2 1/10 oz`
	 */
	function convert_to_numeric( $value ) {
		if ( is_numeric( $value ) ) {
			return $value;
		} else {
			$value_parts = explode( '/', $value );
			if ( count( $value_parts ) < 2 ) {
				return floatval( $value );
			} else { // fraction, e.g.: `1/10 oz`
				$result = floatval( $value_parts[0] );
				unset( $value_parts[0] );
				foreach ( $value_parts as $value_part ) {
					$result /= floatval( $value_part );
				}
				return $result;
			}
		}
	}

	/**
	 * product_meta.
	 *
	 * @version 2.3.1
	 * @since   2.0.0
	 * @todo    [dev] maybe run `do_shortcode` and `evaluate_formula` on `$atts`
	 */
	function product_meta( $atts, $content = '' ) {
		if ( ! isset( $atts['key'] ) ) {
			return '';
		}
		$product_id            = ( isset( $atts['product_id'] ) ? $atts['product_id'] : prowc_ppbf()->core->current_product_id );
		if ( isset( $atts['use_parent_product'] ) && 'yes' === $atts['use_parent_product'] && 0 != ( $parent_product_id = wp_get_post_parent_id( $product_id ) ) ) {
			$product_id        = $parent_product_id;
		}
		$value                 = get_post_meta( $product_id, $atts['key'], true );
		$do_convert_to_numeric = ( ! isset( $atts['convert_to_numeric'] ) || 'yes' == $atts['convert_to_numeric'] );
		return ( '' === $value ?
			( isset( $atts['default'] ) ? $atts['default'] : ( $do_convert_to_numeric ? 0 : '' ) ) :
			( $do_convert_to_numeric ? $this->convert_to_numeric( $value ) : $value )
		);
	}

	/**
	 * product_attr.
	 *
	 * @version 2.2.1
	 * @since   2.2.1
	 */
	function product_attr( $atts, $content = '' ) {
		if ( ! isset( $atts['attribute'] ) ) {
			return '';
		}
		return prowc_ppbf()->core->current_product->get_attribute( $atts['attribute'] );
	}

	/**
	 * product_weight.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function product_weight( $atts, $content = '' ) {
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}
		$atts['key'] = '_weight';
		return $this->product_meta( $atts, $content );
	}

	/**
	 * product_length.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function product_length( $atts, $content = '' ) {
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}
		$atts['key'] = '_length';
		return $this->product_meta( $atts, $content );
	}

	/**
	 * product_width.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function product_width( $atts, $content = '' ) {
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}
		$atts['key'] = '_width';
		return $this->product_meta( $atts, $content );
	}

	/**
	 * product_height.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function product_height( $atts, $content = '' ) {
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}
		$atts['key'] = '_height';
		return $this->product_meta( $atts, $content );
	}

	/**
	 * product_stock.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function product_stock( $atts, $content = '' ) {
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}
		$atts['key'] = '_stock';
		return $this->product_meta( $atts, $content );
	}

	/**
	 * product_total_sales.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function product_total_sales( $atts, $content = '' ) {
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}
		$atts['key'] = 'total_sales';
		return $this->product_meta( $atts, $content );
	}

	/**
	 * min_max.
	 *
	 * @version 2.3.2
	 * @since   2.0.0
	 */
	function min_max( $min_or_max, $atts, $content = '' ) {
		if ( ! isset( $atts['value1'] ) || ! isset( $atts['value2'] ) ) {
			return '';
		}
		$value1 = prowc_ppbf()->core->evaluate_formula( prowc_ppbf_process_atts_shortcodes( $atts['value1'] ) );
		$value2 = prowc_ppbf()->core->evaluate_formula( prowc_ppbf_process_atts_shortcodes( $atts['value2'] ) );
		return $min_or_max( $value1, $value2 );
	}

	/**
	 * math_min.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function math_min( $atts, $content = '' ) {
		return $this->min_max( 'min', $atts, $content );
	}

	/**
	 * math_max.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function math_max( $atts, $content = '' ) {
		return $this->min_max( 'max', $atts, $content );
	}

	/**
	 * math_round.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function math_round( $atts, $content = '' ) {
		// E.g.: `[math_round precision="1"][if_customer_location country="US,CA"]x*1.10[/if_customer_location][if_customer_location not_country="US,CA"]x*1.20[/if_customer_location][/math_round]`
		return round( prowc_ppbf()->core->evaluate_formula( do_shortcode( $content ) ), ( isset( $atts['precision'] ) ? $atts['precision'] : 0 ) );
	}

	/**
	 * math_ceil.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function math_ceil( $atts, $content = '' ) {
		return ceil( prowc_ppbf()->core->evaluate_formula( do_shortcode( $content ) ) );
	}

	/**
	 * math_floor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function math_floor( $atts, $content = '' ) {
		return floor( prowc_ppbf()->core->evaluate_formula( do_shortcode( $content ) ) );
	}

	/**
	 * if_user_id.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 * @todo    [dev] recheck if we need to check for `function_exists( 'get_current_user_id' )`
	 */
	function if_user_id( $atts, $content = '' ) {
		// E.g.: `[if_user_id id="1,5"]x*1.10[/if_user_id][if_user_id not_id="1,5"]x*1.20[/if_user_id]`
		// E.g.: `x[if_user_id id="0"]*2[/if_user_id]`
		$user_id = ( function_exists( 'get_current_user_id' ) ? get_current_user_id() : 0 );
		return (
			( isset( $atts['id'] )     && ! in_array( $user_id, array_map( 'trim', explode( ',', strtolower( $atts['id'] ) ) ) ) ) ||
			( isset( $atts['not_id'] ) &&   in_array( $user_id, array_map( 'trim', explode( ',', strtolower( $atts['not_id'] ) ) ) ) )
		) ? '' : $content;
	}

	/**
	 * get_user_role.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 * @todo    [dev] remove `include( ABSPATH . 'wp-includes/pluggable.php' )`
	 * @todo    [dev] handle cases when user has multiple roles
	 */
	function get_user_role() {
		if ( ! function_exists( 'wp_get_current_user' ) ) {
			include( ABSPATH . 'wp-includes/pluggable.php' );
		}
		$user = wp_get_current_user();
		return ( empty( $user->roles ) ? 'guest' : strtolower( is_array( $user->roles ) ? $user->roles[0] : $user->roles ) );
	}

	/**
	 * if_user_role.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function if_user_role( $atts, $content = '' ) {
		// E.g.: `[if_user_role role="guest,administrator"]x*1.10[/if_user_role][if_user_role not_role="guest,administrator"]x*1.20[/if_user_role]`
		$user_role = $this->get_user_role();
		return (
			( ! empty( $atts['role'] )     && ( ! $user_role || ! in_array( $user_role, array_map( 'trim', explode( ',', strtolower( $atts['role'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_role'] ) &&     $user_role &&   in_array( $user_role, array_map( 'trim', explode( ',', strtolower( $atts['not_role'] ) ) ) ) )
		) ? '' : $content;
	}

	/**
	 * if_product_taxonomy.
	 *
	 * @version 2.1.1
	 * @since   2.1.1
	 * @todo    [dev] handle cases when product has multiple terms
	 * @todo    [feature] option to use something else instead of `slug` (e.g. `ID`)
	 */
	function if_product_taxonomy( $atts, $content = '' ) {
		// E.g.: `[if_product_taxonomy taxonomy="product_cat" slug="t-shirts"]x*1.5[/if_product_taxonomy][if_product_taxonomy taxonomy="product_cat" not_slug="t-shirts"]x*1.6[/if_product_taxonomy]`
		return apply_filters( 'prowc_ppbf', '', 'if_product_taxonomy', array( 'atts' => $atts, 'content' => $content, 'product_id' => prowc_ppbf()->core->current_product_id ) );
	}

	/**
	 * if_product_category.
	 *
	 * @version 2.1.1
	 * @since   2.1.1
	 */
	function if_product_category( $atts, $content = '' ) {
		$atts['taxonomy'] = 'product_cat';
		return $this->if_product_taxonomy( $atts, $content );
	}

	/**
	 * if_product_tag.
	 *
	 * @version 2.1.1
	 * @since   2.1.1
	 */
	function if_product_tag( $atts, $content = '' ) {
		$atts['taxonomy'] = 'product_tag';
		return $this->if_product_taxonomy( $atts, $content );
	}

	/**
	 * if_time.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function if_time( $atts, $content = '' ) {
		// E.g.: `[if_time from="00:00:00" to="11:59:59"]x*1.10[/if_time][if_time from="12:00:00" to="23:59:59"]x*1.20[/if_time]`
		if ( ! isset( $atts['from'] ) || ! isset( $atts['to'] ) ) {
			return '';
		}
		$current_time = (int) current_time( 'timestamp' );
		$today        = date( 'Y-m-d', $current_time );
		$from         = strtotime( $today . ' ' . $atts['from'] );
		$to           = strtotime( $today . ' ' . $atts['to'] );
		return ( $current_time >= $from && $current_time <= $to ? $content : '' );
	}

	/**
	 * if_date.
	 *
	 * @version 2.3.2
	 * @since   2.0.0
	 */
	function if_date( $atts, $content = '' ) {
		// E.g.: `[if_date from="2018-09-15 00:00:00" to="2018-10-15 23:59:59"]x*1.10[/if_date][if_date from="2018-10-16 00:00:00" to="2019-09-14 23:59:59"]x*1.20[/if_date]`
		if ( ! isset( $atts['from'] ) || ! isset( $atts['to'] ) ) {
			return '';
		}
		$current_time = (int) current_time( 'timestamp' );
		$from         = prowc_ppbf_process_atts_shortcodes( $atts['from'] );
		$to           = prowc_ppbf_process_atts_shortcodes( $atts['to'] );
		$from         = ( '0' === $from ? 0 : strtotime( $from ) );
		$to           = ( '0' === $to   ? 0 : strtotime( $to ) );
		return ( $current_time >= $from && $current_time <= $to ? $content : '' );
	}

	/**
	 * get_customer_location_by_ip.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_customer_location_by_ip() {
		if ( ! empty( $this->customer_location_by_ip ) ) {
			return $this->customer_location_by_ip;
		}
		// Get the country by IP
		$location = ( class_exists( 'WC_Geolocation' ) ? WC_Geolocation::geolocate_ip() : array( 'country' => '' ) );
		// Base fallback
		if ( empty( $location['country'] ) ) {
			$location = apply_filters( 'woocommerce_customer_default_location', get_option( 'woocommerce_default_country' ) );
			if ( function_exists( 'wc_format_country_state_string' ) ) {
				$location = wc_format_country_state_string( $location );
			}
		}
		$this->customer_location_by_ip = ( isset( $location['country'] ) ? strtoupper( $location['country'] ) : false );
		return $this->customer_location_by_ip;
	}

	/**
	 * if_customer_location.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 * @todo    [feature] options instead of "by IP" (e.g. billing country for logged customers)
	 */
	function if_customer_location( $atts, $content = '' ) {
		// E.g.: `[if_customer_location country="US,CA"]x*1.10[/if_customer_location][if_customer_location not_country="US,CA"]x*1.20[/if_customer_location]`
		$customer_location = $this->get_customer_location_by_ip();
		return (
			( ! empty( $atts['country'] )     && ( ! $customer_location || ! in_array( $customer_location, array_map( 'trim', explode( ',', strtoupper( $atts['country'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_country'] ) &&     $customer_location &&   in_array( $customer_location, array_map( 'trim', explode( ',', strtoupper( $atts['not_country'] ) ) ) ) )
		) ? '' : $content;
	}

}

endif;

return new ProWC_PPBF_Shortcodes();
