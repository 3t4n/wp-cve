<?php
defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPCleverWoonp' ) ) {
	return;
}

class WoonpCore {
	protected static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_to_cart_item_data' ], PHP_INT_MAX );
		add_filter( 'woocommerce_get_cart_contents', [ $this, 'get_cart_contents' ], PHP_INT_MAX, 1 );
		add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'loop_add_to_cart_link' ], PHP_INT_MAX, 2 );
		add_filter( 'woocommerce_get_price_html', [ $this, 'hide_original_price' ], PHP_INT_MAX, 2 );
		add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_input_field' ], PHP_INT_MAX );
		add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'add_to_cart_validation' ], PHP_INT_MAX, 2 );
	}

	public function add_to_cart_item_data( $cart_item_data ) {
		if ( isset( $_REQUEST['wpc_name_your_price'] ) ) {
			$cart_item_data['wpc_name_your_price'] = self::sanitize_price( $_REQUEST['wpc_name_your_price'] );
			unset( $_REQUEST['wpc_name_your_price'] );
		}

		return $cart_item_data;
	}

	public function get_cart_contents( $cart_contents ) {
		foreach ( $cart_contents as $cart_item ) {
			if ( ! isset( $cart_item['wpc_name_your_price'] ) ) {
				continue;
			}

			$final_value = $cart_item['wpc_name_your_price'];
			$cart_item['data']->set_price( $final_value );
		}

		return $cart_contents;
	}

	public function hide_original_price( $price, $product ) {
		if ( is_admin() ) {
			return $price;
		}

		$product_id    = $product->get_id();
		$get_post_meta = get_post_meta( $product_id, '_woonp_status', true );

		if (
			( WoonpHelper::get_setting( 'global_status', 'enable' ) === 'enable' && $get_post_meta !== 'disable' ) ||
			( WoonpHelper::get_setting( 'global_status', 'enable' ) === 'disable' && $get_post_meta === 'overwrite' )
		) {
			$suggested_price = apply_filters( 'woonp_suggested_price', WoonpHelper::get_setting( 'suggested_price', esc_html__( 'Suggested Price: %s', 'wpc-name-your-price' ) ), $product_id );

			return sprintf( $suggested_price, $price );
		}

		return $price;
	}

	function loop_add_to_cart_link( $link, $product ) {
		$product_id    = $product->get_id();
		$get_post_meta = get_post_meta( $product_id, '_woonp_status', true );

		if ( ( WoonpHelper::get_setting( 'atc_button', 'show' ) === 'hide' ) &&
		     ( ( WoonpHelper::get_setting( 'global_status', 'enable' ) === 'enable' && $get_post_meta !== 'disable' ) ||
		       ( WoonpHelper::get_setting( 'global_status', 'enable' ) === 'disable' && $get_post_meta === 'overwrite' ) )
		) {
			return '';
		}

		return $link;
	}

	public static function is_woonp_product() {
		global $product;

		if ( $product->is_type( 'variation' ) && $product->get_parent_id() ) {
			$product_id = $product->get_parent_id();
		} else {
			$product_id = $product->get_id();
		}

		$status = get_post_meta( $product_id, '_woonp_status', true ) ?: 'default';

		return ( $status !== 'disable' );
	}

	public static function add_input_field() {
		global $product;

		if ( self::is_woonp_product() ) {
			// $status !== 'disable'
			$product_id    = $product->get_id();
			$global_status = WoonpHelper::get_setting( 'global_status', 'enable' );
			$status        = get_post_meta( $product_id, '_woonp_status', true ) ?: 'default';
			$type          = $min = $max = $step = $values = '';

			if ( $status === 'overwrite' ) {
				$global_status = 'enable';
				$type          = get_post_meta( $product_id, '_woonp_type', true );
				$min           = get_post_meta( $product_id, '_woonp_min', true );
				$max           = get_post_meta( $product_id, '_woonp_max', true );
				$step          = get_post_meta( $product_id, '_woonp_step', true );
				$values        = get_post_meta( $product_id, '_woonp_values', true );
			}

			if ( $status === 'default' ) {
				$type   = WoonpHelper::get_setting( 'type', 'default' );
				$min    = WoonpHelper::get_setting( 'min' );
				$max    = WoonpHelper::get_setting( 'max' );
				$step   = WoonpHelper::get_setting( 'step' );
				$values = WoonpHelper::get_setting( 'values' );
			}

			if ( $global_status === 'disable' ) {
				return;
			}

			switch ( WoonpHelper::get_setting( 'value', 'price' ) ) {
				case 'price':
					$value = self::sanitize_price( $product->get_price() );
					break;
				case 'min':
					$value = self::sanitize_price( $min );
					break;
				case 'max':
					$value = self::sanitize_price( $max );
					break;
				default:
					$value = '';
			}

			if ( is_product() && isset( $_REQUEST['wpc_name_your_price'] ) ) {
				$value = self::sanitize_price( $_REQUEST['wpc_name_your_price'] );
			}

			$input_id    = 'woonp_' . $product_id;
			$input_label = apply_filters( 'woonp_input_label', WoonpHelper::get_setting( 'label', esc_html__( 'Name Your Price (%s) ', 'wpc-name-your-price' ) ), $product_id );
			$label       = sprintf( $input_label, get_woocommerce_currency_symbol() );
			$price       = '<div class="' . esc_attr( apply_filters( 'woonp_input_class', 'woonp woonp-' . $status . ' woonp-type-' . $type, $product ) ) . '" data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '" data-step="' . esc_attr( $step ) . '">';
			$price       .= '<label for="' . esc_attr( $input_id ) . '">' . esc_html( $label ) . '</label>';

			if ( ( $type === 'select' ) && ( $values = WPCleverWoonp::get_values( $values ) ) && ! empty( $values ) ) {
				// select
				$select = '<select id="' . esc_attr( $input_id ) . '" class="woonp-select" name="wpc_name_your_price">';

				foreach ( $values as $v ) {
					$select .= '<option value="' . esc_attr( $v['value'] ) . '" ' . ( $value == $v['value'] ? 'selected' : '' ) . '>' . $v['name'] . '</option>';
				}

				$select .= '</select>';

				$price .= apply_filters( 'woonp_input_select', $select, $product );
			} else {
				// default
				$input = '<input type="number" id="' . esc_attr( $input_id ) . '" class="woonp-input" step="' . esc_attr( $step ) . '" min="' . esc_attr( $min ) . '" max="' . esc_attr( 0 < $max ? $max : '' ) . '" name="wpc_name_your_price" value="' . esc_attr( $value ) . '" size="4"/>';

				$price .= apply_filters( 'woonp_input_number', $input, $product );
			}

			$price .= '</div>';

			echo apply_filters( 'woonp_input', $price, $product );
		}
	}

	public static function add_to_cart_validation( $passed, $product_id ) {
		if ( isset( $_REQUEST['wpc_name_your_price'] ) ) {
			$price = (float) $_REQUEST['wpc_name_your_price'];

			if ( $price < 0 ) {
				wc_add_notice( esc_html__( 'You can\'t fill the negative price.', 'wpc-name-your-price' ), 'error' );

				return false;
			} else {
				$status = get_post_meta( $product_id, '_woonp_status', true ) ?: 'default';
				$step   = 1;

				if ( $status === 'overwrite' ) {
					$min  = (float) get_post_meta( $product_id, '_woonp_min', true );
					$max  = (float) get_post_meta( $product_id, '_woonp_max', true );
					$step = (float) ( get_post_meta( $product_id, '_woonp_step', true ) ?: 1 );
				} elseif ( $status === 'default' ) {
					$status = WoonpHelper::get_setting( 'global_status', 'enable' );
					$min    = (float) WoonpHelper::get_setting( 'min' );
					$max    = (float) WoonpHelper::get_setting( 'max' );
					$step   = (float) ( WoonpHelper::get_setting( 'step' ) ?: 1 );
				}

				if ( $step <= 0 ) {
					$step = 1;
				}

				if ( $status !== 'disable' ) {
					$pow = pow( 10, strlen( (string) $step ) );
					$mod = ( ( $price * $pow ) - ( $min * $pow ) ) / ( $step * $pow );

					if ( ( $min && ( $price < $min ) ) || ( $max && ( $price > $max ) ) || ( $mod != intval( $mod ) ) ) {
						wc_add_notice( esc_html__( 'Invalid price. Please try again!', 'wpc-name-your-price' ), 'error' );

						return false;
					}
				}
			}
		}

		return $passed;
	}

	public static function sanitize_price( $price ) {
		return filter_var( sanitize_text_field( $price ), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	}

	public static function fmod_round( $x, $y ) {
		$i = round( $x / $y );

		return $x - $i * $y;
	}
}

return WoonpCore::instance();
