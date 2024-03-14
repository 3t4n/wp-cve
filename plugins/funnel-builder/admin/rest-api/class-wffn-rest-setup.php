<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Setup
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Setup' ) ) {
	#[AllowDynamicProperties]
	class WFFN_REST_Setup extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'setup';

		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Register the routes for taxes.
		 */
		public function register_routes() {


			register_rest_route( $this->namespace, '' . $this->rest_base, array(

				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_status_responses' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(),
				),
			) );

		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_status_responses( $is_rest = true ) {
			$statuses                             = [];
			$statuses['override_global_checkout'] = [];
			$statuses['funnels']                  = [];
			$statuses['is_checkout']              = false;
			$statuses['is_orderbump']             = false;
			$statuses['is_upsells']               = false;
			$statuses['tracking']                 = false;
			$statuses['stripe']                   = [ 'status' => 'not_installed' ];


			$global_funnel_id = WFFN_Common::get_store_checkout_id();
			if ( absint( $global_funnel_id ) > 0 ) {
				$get_funnel = new WFFN_Funnel( $global_funnel_id );
				if ( $get_funnel instanceof WFFN_Funnel && 0 !== $get_funnel->get_id() ) {
					$statuses['override_global_checkout'] = array(
						'funnel_id'   => $global_funnel_id,
						'funnel_name' => $get_funnel->get_title(),
					);
				}
			}


			$args    = array(
				'offset'  => 0,
				'limit'   => 1,
				'meta'    => array(
					'key'     => '_is_global',
					'compare' => 'NOT_EXISTS',
				),
				'context' => 'listing',
			);
			$funnels = WFFN_Core()->admin->get_funnels( $args );
			if ( is_array( $funnels ) && isset( $funnels['items'] ) && is_array( $funnels['items'] ) && count( $funnels['items'] ) > 0 ) {
				$statuses['funnels'] = [ absint( $funnels['items'][0]['id'] ) ];
			} else {
				$statuses['funnels'] = 0;

			}


			$sql_query     = "SELECT count(id) as ids FROM {table_name} WHERE `steps` LIKE '%wc_checkout%'";
			$found_funnels = WFFN_Core()->get_dB()->get_results( $sql_query );
			if ( is_array( $found_funnels ) && count( $found_funnels ) > 0 && isset( $found_funnels[0]['ids'] ) && absint( $found_funnels[0]['ids'] ) > 0 ) {
				$statuses['is_checkout'] = true;
			}


			$sql_query     = "SELECT count(id) as ids FROM {table_name} WHERE `steps` LIKE '%wc_upsells%'";
			$found_funnels = WFFN_Core()->get_dB()->get_results( $sql_query );

			if ( is_array( $found_funnels ) && count( $found_funnels ) > 0 && isset( $found_funnels[0]['ids'] ) && absint( $found_funnels[0]['ids'] ) > 0 ) {
				$statuses['is_upsells'] = true;
			}

			$sql_query     = "SELECT count(id) as ids FROM {table_name} WHERE `steps` LIKE '%wc_order_bump%'";
			$found_funnels = WFFN_Core()->get_dB()->get_results( $sql_query );
			if ( is_array( $found_funnels ) && count( $found_funnels ) > 0 && isset( $found_funnels[0]['ids'] ) && absint( $found_funnels[0]['ids'] ) > 0 ) {
				$statuses['is_orderbump'] = true;
			}

			BWF_Admin_General_Settings::get_instance()->setup_options();
			/**
			 * Check pixel settings
			 */
			$fb_key       = BWF_Admin_General_Settings::get_instance()->get_option( 'fb_pixel_key' );
			$pint_key     = BWF_Admin_General_Settings::get_instance()->get_option( 'pint_key' );
			$ga_key       = BWF_Admin_General_Settings::get_instance()->get_option( 'ga_key' );
			$gad_key      = BWF_Admin_General_Settings::get_instance()->get_option( 'gad_key' );
			$tiktok_key   = BWF_Admin_General_Settings::get_instance()->get_option( 'tiktok_pixel' );
			$snapchat_key = BWF_Admin_General_Settings::get_instance()->get_option( 'snapchat_pixel' );

			if ( ! empty( $fb_key ) || ! empty( $pint_key ) || ! empty( $ga_key ) || ! empty( $gad_key ) || ! empty( $tiktok_key ) || ! empty( $snapchat_key ) ) {
				$statuses['tracking'] = true;
			}


			$all_plugins = get_plugins();

			$other_stripe_exists = ( defined( 'WC_STRIPE_VERSION' ) || defined( 'WC_STRIPE_PLUGIN_FILE_PATH' ) );

			if ( isset( $all_plugins['funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php'] ) ) {

				$statuses['stripe'] = [ 'status' => 'not_activated', 'other_exists' => $other_stripe_exists ];
				if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php' ) ) {
					$statuses['stripe'] = [ 'status' => 'not_connected', 'link' => \FKWCS\Gateway\Stripe\Admin::get_instance()->get_connect_url(), 'other_exists' => $other_stripe_exists ];
					if ( \FKWCS\Gateway\Stripe\Admin::get_instance()->is_stripe_connected() ) {
						$statuses['stripe'] = [ 'status' => 'connected' ];

					}

				}
			} else {
				$statuses['stripe'] = [ 'status' => 'not_installed', 'other_exists' => $other_stripe_exists ];
			}

			if ( $is_rest === true ) {
				return rest_ensure_response( array( 'success' => true, 'statuses' => $statuses ) );
			}

			return $statuses;


		}


	}

}
WFFN_REST_Setup::get_instance();