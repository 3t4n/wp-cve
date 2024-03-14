<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

#[AllowDynamicProperties]

  class WFACP_Reporting {

	private static $ins = null;
	private $is_cart_restored = false;

	private function __construct() {
		global $wpdb;
		$wpdb->wfacp_stats = $wpdb->prefix . 'wfacp_stats';
		add_action( 'admin_init', [ $this, 'create_table' ] );
		add_action( 'woocommerce_checkout_create_order', [ $this, 'update_reporting_data_in_meta' ], 11, 2 );
		add_action( 'woocommerce_order_status_changed', array( $this, 'insert_row_for_ipn_based_gateways' ), 10, 3 );

		if ( class_exists( 'BWF_WC_Compatibility' ) && BWF_WC_Compatibility::is_hpos_enabled() ) {
			add_action( 'woocommerce_delete_order', [ $this, 'delete_report_for_order' ] );
		} else {
			add_action( 'delete_post', [ $this, 'delete_report_for_order' ] );
		}
		add_action( 'wfab_pre_abandoned_cart_restored', [ $this, 'check_if_autobot_cart_restored' ] );
		add_action( 'woocommerce_thankyou', [ $this, 'wfacp_clear_view_session' ], 10, 1 );

		add_action( 'woocommerce_thankyou', [ $this, 'updating_reports_from_orders' ] );
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'update_order_review' ] );

		add_action( 'woocommerce_order_fully_refunded', array( $this, 'fully_refunded_process' ), 10, 1 );
		add_action( 'woocommerce_order_partially_refunded', array( $this, 'partially_refunded_process' ), 10, 2 );
	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;
	}


	public function create_table() {
		/** create table in ver 1.0 */
		if ( false !== get_option( 'wfacp_db_ver_2_1', false ) ) {
			return;
		}
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		global $wpdb;
		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$creationSQL = "CREATE TABLE {$wpdb->prefix}wfacp_stats (
 		  ID bigint(20) unsigned NOT NULL auto_increment,
 		  order_id bigint(20) unsigned NOT NULL,
 		  wfacp_id bigint(20) unsigned NOT NULL,
 		  total_revenue varchar(255) not null default 0,
 		  cid bigint(20) unsigned NOT NULL DEFAULT 0,
 		  fid bigint(20) unsigned NOT NULL DEFAULT 0, 		 		  
 		  date datetime NOT NULL, 		  
		  PRIMARY KEY  (ID),
		  KEY ID (ID),
		  KEY oid (order_id),
		  KEY bid (wfacp_id),
		  KEY date (date)
		) $collate;";
		dbDelta( $creationSQL );
		update_option( 'wfacp_db_ver_2_1', gmdate( 'Y-m-d' ) );
	}

	public function is_order_renewal( $order ) {
		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}
		$subscription_renewal = BWF_WC_Compatibility::get_order_data( $order, '_subscription_renewal' );

		return empty( $subscription_renewal ) ? false : true;
	}

	/**
	 * hooked @woocommerce_order_status_changed
	 *
	 * @param $order_id
	 * @param $from
	 * @param $to
	 *
	 * @return false
	 */
	public function insert_row_for_ipn_based_gateways( $order_id, $from, $to ) {
		if ( in_array( $from, wc_get_is_paid_statuses(), true ) ) {
			return false;
		}

		$order          = wc_get_order( $order_id );
		$payment_method = $order->get_payment_method();

		/**
		 * If this is a renewal order then delete the meta if exists and return straight away
		 */
		if ( $this->is_order_renewal( $order ) ) {
			$order->delete_meta_data( '_wfacp_report_needs_normalization' );
			$order->save();

			return false;
		}

		$ipn_gateways = $this->get_ipn_gateways();

		/**
		 * condition1 : if one of IPN gateways
		 * condition2: Thankyou page hook with pending status ran on this order
		 * condition3: In case thankyou page not open and order mark complete by IPN
		 */
		if ( in_array( $payment_method, $ipn_gateways, true ) || 'yes' === $order->get_meta( '_wfacp_report_needs_normalization' ) || ( class_exists( 'WC_Geolocation' ) && ( $order->get_customer_ip_address() !== WC_Geolocation::get_ip_address() ) ) ) {
			/**
			 * reaching this code means, 1) we have a ipn gateway OR 2) we have meta stored during thankyou
			 */
			if ( $order_id > 0 && in_array( $to, wc_get_is_paid_statuses(), true ) ) {
				$this->updating_reports_from_orders( $order_id );
			}
		}
	}

	/**
	 * hooked @woocommerce_checkout_create_order
	 *
	 * @param WC_Order $order
	 * @param $posted_data
	 */
	public function update_reporting_data_in_meta( $order, $posted_data ) {
		$wfacp_id = isset( $posted_data['wfacp_post_id'] ) ? $posted_data['wfacp_post_id'] : apply_filters( 'wfacp_mark_conversion_post_id', 0, $posted_data );

		$funnel_id = 0;
		if ( empty( $wfacp_id ) || $wfacp_id < 1 ) {
			/**
			 * Check if store checkout is configures
			 */
			if ( ! class_exists( 'WFFN_Common' ) || ! method_exists( 'WFFN_Common', 'get_store_checkout_id' ) || 0 === WFFN_Common::get_store_checkout_id() ) {
				return;
			}

			if ( false === wffn_string_to_bool( WFFN_Core()->get_dB()->get_meta( WFFN_Common::get_store_checkout_id(), 'status' ) ) ) {
				return;
			}

			$funnel = new WFFN_Funnel( WFFN_Common::get_store_checkout_id() );

			/**
			 * Check if this is a valid funnel and has native checkout
			 * if not then return from here
			 */
			if ( ! wffn_is_valid_funnel( $funnel ) || false === $funnel->is_funnel_has_native_checkout() ) {
				return;
			}

			$funnel_id = WFFN_Common::get_store_checkout_id();
			$wfacp_id  = 0;
		}

		$wfacp_used_total = $order->get_total();
		$wfacp_used_total = BWF_Plugin_Compatibilities::get_fixed_currency_price_reverse( $wfacp_used_total, BWF_WC_Compatibility::get_order_currency( $order ) );
		$bump_data        = wfacp_get_order_meta( $order, '_wfob_report_data' );

		if ( is_array( $bump_data ) && count( $bump_data ) > 0 ) {
			$bump_total = 0;
			foreach ( $bump_data as $bump_datum ) {
				$bump_total += isset( $bump_datum['total'] ) ? floatval( $bump_datum['total'] ) : 0;
			}
			$wfacp_used_total -= $bump_total;
			$wfacp_used_total = round( $wfacp_used_total, 2 );
		}


		$fid = ( 0 === $wfacp_id ) ? $funnel_id : get_post_meta( $wfacp_id, '_bwf_in_funnel', true );
		if ( $fid > 0 ) {
			$funnel_id = $fid;
		}

		$order->update_meta_data( '_wfacp_report_data', array( 'wfacp_total' => $wfacp_used_total, 'funnel_id' => $funnel_id ) );
		$order->save();
	}

	/**
	 * hooked @woocommerce_thankyou
	 *
	 * @param $order_id
	 *
	 * @return bool
	 */
	public function updating_reports_from_orders( $order_id ) {
		/**
		 * @var $order WC_Order
		 */
		$order = apply_filters( 'wfacp_maybe_update_order', wc_get_order( $order_id ) );

		$order_id = $order->get_id();

		$order_status = $order->get_status();

		/**
		 * If this is a renewal order then delete the meta if exists and return straight away
		 */
		if ( $this->is_order_renewal( $order ) ) {
			$order->delete_meta_data( '_wfacp_report_needs_normalization' );
			$order->save();

			return false;
		}

		add_filter( 'woocommerce_order_is_paid_statuses', [ $this, 'wfacp_custom_order_status' ] );
		$payment_method = $order->get_payment_method();
		/**
		 * if woocommerce thank you showed up and order status not paid, save meta to normalize status later
		 */
		if ( did_action( 'woocommerce_thankyou' ) ) {

			if ( in_array( $payment_method, $this->get_ipn_gateways(), true ) || ! in_array( $order_status, wc_get_is_paid_statuses(), true ) ) {
				$order->update_meta_data( '_wfacp_report_needs_normalization', 'yes' );
				$order->save();

				return false;
			}
		}

		$wfacp_report_data = wfacp_get_order_meta( $order, '_wfacp_report_data' );

		if ( empty( $wfacp_report_data ) ) {
			return false;
		}

		$wfacp_total  = ( is_array( $wfacp_report_data ) && isset( $wfacp_report_data['wfacp_total'] ) ) ? $wfacp_report_data['wfacp_total'] : '';
		$wfacp_id     = wfacp_get_order_meta( $order, '_wfacp_post_id' );
		$cid          = wfacp_get_order_meta( $order, '_woofunnel_cid' );
		$funnel_id    = ( is_array( $wfacp_report_data ) && isset( $wfacp_report_data['funnel_id'] ) ) ? $wfacp_report_data['funnel_id'] : 0;
		$date_created = $order->get_date_created();


		if ( ! empty( $date_created ) ) {

			$timezone = new DateTimeZone( wp_timezone_string() );
			$date_created->setTimezone( $timezone );
			$date_created = $date_created->format( 'Y-m-d H:i:s' );
		}

		$wfacp_data = [
			'order_id'      => absint( $order_id ),
			'wfacp_id'      => absint( $wfacp_id ),
			'total_revenue' => abs( $wfacp_total ),
			'date'          => empty( $date_created ) ? current_time( 'mysql' ) : $date_created,
			'cid'           => $cid,
			'fid'           => $funnel_id
		];
		$this->insert_data( $wfacp_data );

		$order->delete_meta_data( '_wfacp_report_data' );
		$order->delete_meta_data( '_wfacp_report_needs_normalization' );
		$order->save();
		remove_filter( 'woocommerce_order_is_paid_statuses', [ $this, 'wfacp_custom_order_status' ] );
	}

	public function delete_report_for_order( $order_id ) {
		if ( empty( $order_id ) || absint( 0 === $order_id ) ) {
			return;
		}
		if ( 0 < did_action( 'delete_post' ) ) {
			$get_post_type = get_post_type( $order_id );
			if ( 'shop_order' !== $get_post_type ) {
				return;
			}
		}
		global $wpdb;
		$wpdb->delete( $wpdb->wfacp_stats, [ 'order_id' => $order_id ], [ '%d' ] );
	}

	private function insert_data( $data ) {
		global $wpdb;
		$status = $wpdb->insert( $wpdb->wfacp_stats, $data, [ '%d', '%d', '%s', '%s', '%d', '%d' ] );
		if ( false !== $status ) {
			return $status;
		}

		return null;
	}

	private function update_data( $data, $where ) {
		global $wpdb;
		$status = $wpdb->update( $wpdb->wfacp_stats, $data, $where, [ '%s' ], [ '%d', '%d' ] );
		if ( false !== $status ) {
			return true;
		}

		return null;
	}

	public function get_session_key( $aero_id ) {
		return WC()->session->get( 'wfacp_view_session_' . $aero_id, false );
	}

	public function update_session_key( $aero_id ) {
		WC()->session->set( 'wfacp_view_session_' . $aero_id, true );
	}


	public function check_if_autobot_cart_restored() {
		$this->is_cart_restored = true;
	}

	public function wfacp_clear_view_session( $order_id ) {
		$aero_id = ( $order_id > 0 ) ? wfacp_get_order_meta( wc_get_order( $order_id ), '_wfacp_post_id' ) : 0;
		if ( $aero_id > 0 && ! is_null( WC()->session ) && WC()->session->has_session() ) {
			WC()->session->set( 'wfacp_view_session_' . $aero_id, false );
		}
	}

	public function update_order_review( $postdata ) {
		$post_data = [];
		parse_str( $postdata, $post_data );
		$wfacp_id  = isset( $post_data['_wfacp_post_id'] ) ? $post_data['_wfacp_post_id'] : 0;
		$funnel_id = 0;
		if ( $wfacp_id < 1 ) {
			/**
			 * Check if store checkout is configures
			 */
			if ( ! class_exists( 'WFFN_Common' ) || ! method_exists( 'WFFN_Common', 'get_store_checkout_id' ) || 0 === WFFN_Common::get_store_checkout_id() ) {
				return;
			}

			if ( false === wffn_string_to_bool( WFFN_Core()->get_dB()->get_meta( WFFN_Common::get_store_checkout_id(), 'status' ) ) ) {
				return;
			}
			$funnel_id = WFFN_Common::get_store_checkout_id();
			$wfacp_id  = 0;

		}

		$status = $this->get_session_key( $wfacp_id );

		/** Already captured */
		if ( true === $status ) {
			return;
		}

		/** Check if AutoBot installed and cart tracking in enabled and Cart is restored, don't require cart initiate increment */
		if ( true === $this->is_cart_restored ) {
			$this->update_session_key( $wfacp_id );

			return;
		}

		if ( true === apply_filters( 'wfacp_update_report_views', false, $wfacp_id, $postdata ) ) {
			return;
		}

		if ( ! class_exists( 'WFCO_Model_Report_views' ) ) {
			$bwf_configuration = WooFunnel_Loader::get_the_latest();
			require $bwf_configuration['plugin_path'] . '/woofunnels/connector/db/class-wfco-model-report-views.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
		}
		WFCO_Model_Report_views::update_data( gmdate( 'Y-m-d', current_time( 'timestamp' ) ), $wfacp_id, 4 );
		$this->update_session_key( $wfacp_id );

		/** update store checkout views  */
		if ( absint( $funnel_id ) > 0 ) {
			WFCO_Model_Report_views::update_data( gmdate( 'Y-m-d', current_time( 'timestamp' ) ), $funnel_id, 7 );
		}

	}

	public function wfacp_custom_order_status( $all_status ) {
		if ( is_array( $all_status ) ) {
			$all_status = apply_filters( 'wfacp_analytics_custom_order_status', $all_status );
		}

		return $all_status;
	}

	/**
	 * @param $order_id
	 *
	 * Full refunded process for analytics
	 */
	public function fully_refunded_process( $order_id ) {
		global $wpdb;
		$wpdb->update( $wpdb->prefix . "wfacp_stats", [ 'total_revenue' => 0 ], [ 'order_id' => $order_id ] );
	}

	/**
	 * @param $order_id
	 * @param $refund_id
	 * Partially refunded process for analytics
	 */

	public function partially_refunded_process( $order_id, $refund_id ) {
		global $wpdb;
		$order         = wc_get_order( $order_id );
		$refund        = wc_get_order( $refund_id );
		$refund_amount = 0;

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		if ( ! $refund instanceof WC_Order_Refund ) {
			return;
		}


		$types = apply_filters( 'wfacp_order_type_to_group', array(
			'line_item',
			'tax',
			'shipping',
			'fee',
			'coupon',
		) );
		if ( 0 < count( $refund->get_items( $types ) ) ) {
			foreach ( $refund->get_items( $types ) as $refund_item ) {
				$item_id = $refund_item->get_meta( '_refunded_item_id', true );
				if ( empty( $item_id ) ) {
					continue;
				}
				$item = $order->get_item( $item_id );
				if ( ! $item instanceof WC_Order_Item ) {
					continue;
				}
				$_bump_purchase     = $item->get_meta( '_bump_purchase' );
				$_upstroke_purchase = $item->get_meta( '_upstroke_purchase' );
				if ( ( '' === $_bump_purchase ) && ( '' === $_upstroke_purchase ) ) {
					$refund_amount += abs( $refund_item->get_total() );
				}
			}
		} else {
			$refund_amount = wfacp_get_order_meta( $refund, '_refund_amount' );
		}

		if ( $refund_amount > 0 ) {
			$get_total     = $wpdb->get_var( "SELECT total_revenue FROM " . $wpdb->prefix . "wfacp_stats WHERE order_id = " . $order_id ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared*/
			$refund_amount = ( $get_total <= $refund_amount ) ? 0 : $get_total - $refund_amount;
			$wpdb->update( $wpdb->prefix . "wfacp_stats", [ 'total_revenue' => $refund_amount ], [ 'order_id' => $order_id ] );
		}
	}

	public function get_ipn_gateways() {
		return apply_filters( 'wfacp_ipn_gateways_list', array( 'paypal', 'mollie_wc_gateway_ideal', 'mollie_wc_gateway_bancontact', 'mollie_wc_gateway_sofort', 'infusionsoft_cc' ) );
	}
}

if ( class_exists( 'WFACP_Core' ) && ! WFACP_Common::is_disabled() ) {
	WFACP_Core::register( 'reporting', 'WFACP_Reporting' );
}
