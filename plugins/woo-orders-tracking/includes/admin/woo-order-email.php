<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_ORDERS_TRACKING_ADMIN_WOO_ORDER_EMAIL {
	protected $settings;
	protected static $has_tracking_number;

	public function __construct() {
		$this->settings = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
		add_action( 'init', array(
			$this,
			'add_shortcode'
		) );
		if ( $this->settings->get_params( 'email_woo_enable' ) ) {
			$email_woo_position = $this->settings->get_params( 'email_woo_position' );
			switch ( $email_woo_position ) {
				case 'before_order_table':
					add_action( 'woocommerce_email_before_order_table', array(
						$this,
						'woocommerce_email_before_order_table'
					), 20, 4 );

					break;
				case 'after_order_item':
					break;
				case 'after_order_table':
				default:
					add_action( 'woocommerce_email_after_order_table', array(
						$this,
						'woocommerce_email_after_order_table'
					), 20, 4 );
			}
		}
	}

	/**
	 * @param $order
	 * @param $sent_to_admin
	 * @param $plain_text
	 * @param $email
	 *
	 * @throws Exception
	 */
	public function woocommerce_email_before_order_table( $order, $sent_to_admin, $plain_text, $email ) {
		$email_woo_status = $this->settings->get_params( 'email_woo_status' );
		if ( ! $email || ( $email_woo_status && is_array( $email_woo_status ) && in_array( $email->id, $email_woo_status ) ) ) {
			$this->include_tracking_info( $order );
		}
	}

	/**
	 * @return false|string
	 * @throws Exception
	 */

	public function woocommerce_orders_tracking_info_woo_email() {
		global $post;
		$return = '';
		if ( $post && ! empty( $post->ID ) ) {
			$order = wc_get_order( $post->ID );
			if ( $order ) {
				ob_start();
				$this->include_tracking_info( $order );
				$return = ob_get_clean();
			}
		}

		return $return;
	}

	public function add_shortcode() {
		add_shortcode( 'woocommerce_orders_tracking_info_woo_email', array(
			$this,
			'woocommerce_orders_tracking_info_woo_email'
		) );
	}

	/**
	 * @param $order
	 * @param $sent_to_admin
	 * @param $plain_text
	 * @param $email
	 *
	 * @throws Exception
	 */
	public function woocommerce_email_after_order_table( $order, $sent_to_admin, $plain_text, $email ) {
		$email_woo_status = $this->settings->get_params( 'email_woo_status' );
		if ( ! $email || ( $email_woo_status && is_array( $email_woo_status ) && in_array( $email->id, $email_woo_status ) ) ) {
			$this->include_tracking_info( $order );
		}
	}

	/**
	 * @param $order WC_Order
	 *
	 * @throws Exception
	 */
	public function include_tracking_info( $order ) {
		if ( $order ) {
			$email_woo_html               = '<h2 class="email-upsell-title">' . esc_html__( 'Tracking information', 'woo-orders-tracking' ) . '</h2>' . esc_html__( 'Your tracking number:', 'woo-orders-tracking' ) . ' {tracking_list}';
			$email_woo_tracking_list_html = '<a href="{tracking_url}" target="_blank">{tracking_number}</a>' . esc_html__( ' by ', 'woo-orders-tracking' ) . '{carrier_name}';
			if ( $email_woo_html || $email_woo_tracking_list_html ) {
				$tracking_info = array();
				$tracking_list = array();
				foreach ( $order->get_items() as $item_id => $item ) {
					$item_tracking_data = wc_get_order_item_meta( $item_id, '_vi_wot_order_item_tracking_data', true );
					if ( $item_tracking_data ) {
						$item_tracking_data    = vi_wot_json_decode( $item_tracking_data );
						$current_tracking_data = array_pop( $item_tracking_data );
						$this->get_tracking_list( $current_tracking_data, $order, $email_woo_tracking_list_html, $tracking_info, $tracking_list );
					}
				}
				if ( count( $tracking_list ) ) {
					echo wp_kses_post( ent2ncr( str_replace( '{tracking_list}', implode( ', ', $tracking_list ), $email_woo_html ) ) );
				}
			}
		}
	}

	/**
	 * @param $current_tracking_data
	 * @param $order WC_Order
	 * @param $email_woo_tracking_list_html
	 * @param $tracking_info
	 * @param $tracking_list
	 */
	protected function get_tracking_list( $current_tracking_data, $order, $email_woo_tracking_list_html, &$tracking_info, &$tracking_list ) {
		$carrier_id    = $current_tracking_data['carrier_slug'];
		$tracking_code = $current_tracking_data['tracking_number'];
		$carrier_url   = $current_tracking_data['carrier_url'];
		$carrier_name  = $current_tracking_data['carrier_name'];
		$carrier       = $this->settings->get_shipping_carrier_by_slug( $carrier_id, '' );
		if ( is_array( $carrier ) && count( $carrier ) ) {
			$carrier_url  = $carrier['url'];
			$carrier_name = $carrier['name'];
		}
		$tracking_url = $this->settings->get_url_tracking( $carrier_url, $tracking_code, $carrier_id, $order->get_shipping_postcode(), false, false, $order->get_id() );
		if ( $tracking_code && $carrier_id && $tracking_url ) {
			$t = array(
				'tracking_code' => $tracking_code,
				'tracking_url'  => $tracking_url,
				'carrier_name'  => $carrier_name,
			);
			if ( ! in_array( $t, $tracking_info ) ) {
				$tracking_info[] = $t;
				$tracking_list[] = str_replace( array(
					'{tracking_number}',
					'{tracking_url}',
					'{carrier_name}',
					'{carrier_url}'
				), array(
					$tracking_code,
					$tracking_url,
					$carrier_name,
					$carrier_url
				), $email_woo_tracking_list_html );
			}
		}
	}

	/**
	 * @param $item_id
	 * @param $order WC_Order
	 * @param $plain_text
	 * @param bool $add_nonce
	 * @param string $language
	 *
	 * @throws Exception
	 */
	public static function include_tracking_info_after_order_item( $item_id, $order, $plain_text = false, $add_nonce = false, $language = '' ) {
		if ( ! $plain_text ) {
			if ( self::$has_tracking_number === null ) {
				self::$has_tracking_number = 0;
			}
			$settings              = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
			$item_tracking_data    = wc_get_order_item_meta( $item_id, '_vi_wot_order_item_tracking_data', true );
			$current_tracking_data = array(
				'tracking_number' => '',
				'carrier_slug'    => '',
				'carrier_url'     => '',
				'carrier_name'    => '',
				'carrier_type'    => '',
				'time'            => time(),
			);
			if ( $item_tracking_data ) {
				$item_tracking_data    = vi_wot_json_decode( $item_tracking_data );
				$current_tracking_data = array_pop( $item_tracking_data );
			}
			$email_woo_tracking_number_html  = esc_html__( 'Tracking number: ', 'woo-orders-tracking' ) . '<a href="{tracking_url}" target="_blank">{tracking_number}</a>';
			$email_woo_tracking_carrier_html = esc_html__( 'Carrier: ', 'woo-orders-tracking' ) . '<a href="{carrier_url}" target="_blank">{carrier_name}</a>';
			self::print_tracking_info( $current_tracking_data, $settings, $order, $email_woo_tracking_number_html, $email_woo_tracking_carrier_html, $add_nonce );
		}
	}

	/**
	 * @param $current_tracking_data
	 * @param $settings VI_WOO_ORDERS_TRACKING_DATA
	 * @param $order WC_Order
	 * @param $email_woo_tracking_number_html
	 * @param $email_woo_tracking_carrier_html
	 * @param $add_nonce
	 */
	protected static function print_tracking_info( $current_tracking_data, $settings, $order, $email_woo_tracking_number_html, $email_woo_tracking_carrier_html, $add_nonce ) {
		$carrier_id      = $current_tracking_data['carrier_slug'];
		$tracking_code   = $current_tracking_data['tracking_number'];
		$carrier_url     = $current_tracking_data['carrier_url'];
		$carrier_name    = $current_tracking_data['carrier_name'];
		$tracking_status = isset( $current_tracking_data['status'] ) ? VI_WOO_ORDERS_TRACKING_DATA::convert_status( $current_tracking_data['status'] ) : '';
		$carrier         = $settings->get_shipping_carrier_by_slug( $carrier_id, '' );
		if ( is_array( $carrier ) && count( $carrier ) ) {
			$carrier_url  = $carrier['url'];
			$carrier_name = $carrier['name'];
		}
		$tracking_url = $settings->get_url_tracking( $carrier_url, $tracking_code, $carrier_id, $order->get_shipping_postcode(), false, $add_nonce, $order->get_id() );
		$carrier_url  = str_replace( array(
			'{tracking_number}',
			'{postal_code}'
		), '', $carrier_url );
		if ( $tracking_code && $tracking_url ) {
			self::$has_tracking_number ++;
			?>
            <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'orders-details' ) ) ?>">
                <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
					'orders-details-tracking-number',
					'tracking-number-container-' . $tracking_status
				) ) ) ?>">
					<?php echo str_replace( array(
						'{tracking_number}',
						'{tracking_url}',
						'{carrier_name}',
						'{carrier_url}'
					), array(
						$tracking_code,
						$tracking_url,
						$carrier_name,
						$carrier_url
					), $email_woo_tracking_number_html ) ?>
                </div>
                <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'orders-details-tracking-carrier' ) ) ?>">
					<?php echo str_replace( array(
						'{tracking_number}',
						'{tracking_url}',
						'{carrier_name}',
						'{carrier_url}'
					), array(
						$tracking_code,
						$tracking_url,
						$carrier_name,
						$carrier_url
					), $email_woo_tracking_carrier_html ) ?>
                </div>
            </div>
			<?php
		}
	}
}