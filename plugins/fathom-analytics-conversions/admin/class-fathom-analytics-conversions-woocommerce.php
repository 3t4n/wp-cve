<?php

/**
 * The woocommerce-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0.9
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/woocommerce
 */

/**
 * The woocommerce-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the woocommerce-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/woocommerce
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_Woocommerce {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.9
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.9
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.9
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if ( function_exists( 'WC' ) ) {
			$GLOBALS['gtm4wp_is_woocommerce3']   = version_compare( WC()->version, '3.0', '>=' );
			$GLOBALS['gtm4wp_is_woocommerce3_7'] = version_compare( WC()->version, '3.7', '>=' );
		}
		else {
			$GLOBALS['gtm4wp_is_woocommerce3']   = false;
			$GLOBALS['gtm4wp_is_woocommerce3_7'] = false;
		}

		$this->fac_check_woocommerce();

		// Check to add event id to new form.
		//add_action( 'wp_footer', array( $this, 'fac_woo_footer_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fac_woo_script' ) );

	}

	/**
	 * JavaScript
	 *
	 * @since    1.0.9
	 */
	public function fac_woo_footer_script() {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WOOCOMMERCE ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
			if ( ! ( empty( $fac4wp_options[ FAC_FATHOM_TRACK_ADMIN ] ) && current_user_can( 'manage_options' ) ) ) { // track visits by administrators!
				$woo = WC();
				if ( is_order_received_page() ) {
					$fac_is_woocommerce3 = version_compare( WC()->version, '3.0', '>=' );

					$order_id          = empty( $_GET['order'] ) ? ( $GLOBALS['wp']->query_vars['order-received'] ? $GLOBALS['wp']->query_vars['order-received'] : 0 ) : absint( $_GET['order'] );
					$order_id_filtered = apply_filters( 'woocommerce_thankyou_order_id', $order_id );
					if ( '' != $order_id_filtered ) {
						$order_id = $order_id_filtered;
					}

					$order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] ) );

					if ( $order_id > 0 ) {
						$order = wc_get_order( $order_id );

						if ( $order instanceof WC_Order ) {
							if ( $fac_is_woocommerce3 ) {
								$this_order_key = $order->get_order_key();
							}
							else {
								$this_order_key = $order->order_key;
							}

							if ( $this_order_key != $order_key ) {
								unset( $order );
							}
						}
						else {
							unset( $order );
						}
					}

					if ( isset ( $order ) ) {
						$order_total = esc_js( $order->get_total() );
						$order_total *= 100;
						$option      = (array) get_option( 'fac_options', array() );
						$fac_content = '
<!-- Fathom Analytics Conversions -->
<script data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
						$fac_content .= '
	window.addEventListener("load", (event) => {
        fathom.trackGoal("' . $option['wc_order_event_id'] . '", ' . $order_total . ');
	});';
						$fac_content .= '
</script>
<!-- END Fathom Analytics Conversions -->';
						echo $fac_content;
					}
				}
			}
		}
	}

	public function fac_woo_script() {
		global $fac4wp_options, $fac4wp_plugin_url;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WOOCOMMERCE ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
			if ( ! ( empty( $fac4wp_options[ FAC_FATHOM_TRACK_ADMIN ] ) && current_user_can( 'manage_options' ) ) ) { // track visits by administrators!
				$woo = WC();
				if ( is_order_received_page() ) {
					$fac_is_woocommerce3 = version_compare( WC()->version, '3.0', '>=' );

					$order_id          = empty( $_GET['order'] ) ? ( $GLOBALS['wp']->query_vars['order-received'] ? $GLOBALS['wp']->query_vars['order-received'] : 0 ) : absint( $_GET['order'] );
					$order_id_filtered = apply_filters( 'woocommerce_thankyou_order_id', $order_id );
					if ( '' != $order_id_filtered ) {
						$order_id = $order_id_filtered;
					}

					$order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] ) );

					if ( $order_id > 0 ) {
						$order = wc_get_order( $order_id );

						if ( $order instanceof WC_Order ) {
							if ( $fac_is_woocommerce3 ) {
								$this_order_key = $order->get_order_key();
							}
							else {
								$this_order_key = $order->order_key;
							}

							if ( $this_order_key != $order_key ) {
								unset( $order );
							}
						}
						else {
							unset( $order );
						}
					}

					if ( isset ( $order ) ) {
						$order_total = esc_js( $order->get_total() );
						$order_total *= 100;
						$option      = (array) get_option( 'fac_options', array() );

						$in_footer = apply_filters( 'fac4wp_' . FAC4WP_OPTION_INTEGRATE_WOOCOMMERCE, true );
						wp_enqueue_script( 'fac-woo-tracker', $fac4wp_plugin_url . 'public/js/fac-woo-tracker.js', array(), FATHOM_ANALYTICS_CONVERSIONS_VERSION, $in_footer );
						$woo_data = array(
							'wc_order_event_id' => $option['wc_order_event_id'],
							'order_total'       => $order_total,
						);
						wp_localize_script( 'fac-woo-tracker', 'woo_data', $woo_data );
					}
				}
			}
		}
	}

	/**
	 * Check event id of Woocommerce Order Event
	 *
	 * @since    1.0.9
	 */
	public function fac_check_woocommerce() {
		global $fac4wp_options;
		$update      = 0;
		$option_name = 'fac_options';
		$option      = (array) get_option( $option_name, array() );
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WOOCOMMERCE ] ) {
			if ( ! isset( $option['wc_order_event_id'] ) || empty( $option['wc_order_event_id'] ) ) {
				$event_title  = __( 'WooCommerce Order', 'fathom-analytics-conversions' );
				$new_event_id = fac_add_new_fathom_event( $event_title );
				//$new_event_id = 'IN6NIAKX';
				if ( ! empty( $new_event_id ) ) {
					$option['wc_order_event_id'] = $new_event_id;
					$update                      = 1;
				}
			}
		}
		if ( $update ) {
			update_option( $option_name, $option );
		}

	}

}
