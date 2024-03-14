<?php

/**
 * @author woofunnels
 * @package WooFunnels
 */
if ( ! class_exists( 'WooFunnels_Funnel_Builder_Commons' ) ) {
	#[AllowDynamicProperties]
	class WooFunnels_Funnel_Builder_Commons {

		protected static $instance;

		/**
		 * WooFunnels_Cache constructor.
		 */
		public function __construct() {
			add_action( 'manage_shop_order_posts_custom_column', array( $this, 'show_woofunnels_total_in_order_listings' ), 99, 2 );
			/**
			 * this is specific for the case when HPOS is enabled.
			 */
			add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( $this, 'show_woofunnels_total_in_order_listings' ), 99, 2 );
			add_action( 'admin_init', function () {
				if ( class_exists( 'WFOCU_Admin' ) ) {
					remove_filter( 'woocommerce_get_formatted_order_total', array( WFOCU_Core()->admin, 'show_upsell_total_in_order_listings' ), 999, 2 );
				}

				if ( class_exists( 'WFOB_Admin' ) ) {

					remove_filter( 'woocommerce_get_formatted_order_total', array( WFOB_Core()->admin, 'show_bump_total_in_order_listings' ), 9999, 2 );
				}
			} );
		}

		/**
		 * Creates an instance of the class
		 * @return WooFunnels_Funnel_Builder_Commons
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}


		public function show_woofunnels_total_in_order_listings( $column_name, $post_id ) {
			$total_woofunnels = 0;

			$order = wc_get_order( $post_id );

			$html = '';
			if ( 'order_total' === $column_name ) {
				$show_combined = true;
				if ( class_exists( 'WFOCU_Admin' ) ) {
					$result_in_order_currency = BWF_WC_Compatibility::get_order_meta( $order, '_wfocu_upsell_amount_currency' );
					if ( true === $show_combined && ! empty( $result_in_order_currency ) ) {
						$total_woofunnels = $total_woofunnels + $result_in_order_currency;
					}
				}
				if ( class_exists( 'WFOB_Admin' ) ) {
					if ( true === $show_combined ) {
						$bump_total = method_exists( 'WFOB_Common', 'get_bump_items_total' ) ? WFOB_Common::get_bump_items_total( $order ) : false;
						if ( false !== $bump_total ) {
							$total_woofunnels += $bump_total;
						}
					}
				}

				if ( ! empty( $total_woofunnels ) ) {
					$html = '<br/>
<p style="font-size: 12px;"><em> ' . sprintf( esc_html__( 'FunnelKit: %s' ), wc_price( $total_woofunnels, array( 'currency' => get_option( 'woocommerce_currency' ) ) ) ) . '</em></p>';

				}
			}

			echo $html;

		}


	}


}
