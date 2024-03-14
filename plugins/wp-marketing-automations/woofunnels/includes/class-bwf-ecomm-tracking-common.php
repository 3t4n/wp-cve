<?php
/**
 * Class BWF_Ecomm_Tracking_Common
 */
if ( ! class_exists( 'BWF_Ecomm_Tracking_Common' ) ) {
	class BWF_Ecomm_Tracking_Common {
		public $api_events = [];
		public $gtag_rendered = false;
		private static $ins = null;

		private $conv_table = 'bwf_conversion_tracking';

		public function __construct() {

			if ( ! class_exists( 'WFFN_Core' ) ) {
				return;
			}
			add_action( 'wp_head', array( $this, 'render' ), 12 );
			add_action( 'wffn_optin_form_submit', array( $this, 'update_optin_tracking_data' ), 10, 2 );
			add_filter( 'bwf_add_db_table_schema', array( $this, 'create_db_tables' ), 10, 2 );
			add_action( 'add_meta_boxes', array( $this, 'add_single_order_meta_box' ), 50, 2 );

			add_action( 'woocommerce_checkout_create_order', array( $this, 'add_tracking_data_in_order_meta' ), 12, 1 );
			add_action( 'wfocu_offer_accepted_and_processed', array( $this, 'insert_tracking_data_in_upsell_order' ), 10, 4 );
			add_action( 'woocommerce_thankyou', array( $this, 'insert_tracking_data_from_order_meta' ), 9, 1 );
			add_action( 'woocommerce_order_status_changed', array( $this, 'maybe_insert_pending_tracking_data' ), 9, 3 );

			/***
			 * conversion delete row process on order delete and fully refunded
			 */
			add_action( 'woocommerce_order_fully_refunded', array( $this, 'delete_conversion_row' ) );
			add_action( 'woocommerce_order_partially_refunded', array( $this, 'partially_refunded_process' ), 10, 2 );
			if ( ! BWF_WC_Compatibility::is_hpos_enabled() ) {
				add_action( 'delete_post', array( $this, 'delete_conversion_row' ) );
			} else {
				add_action( 'woocommerce_delete_order', array( $this, 'delete_conversion_row' ) );
			}


			add_action( 'bwf_conversion_tracking_index_completed', array( $this, 'update_conversion_table' ), 10, 2 );

		}

		/**
		 * @return BWF_Ecomm_Tracking_Common|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function render() {
			$this->render_js_to_track_referer();
		}

		/**
		 * Render UTM js to fire events
		 */
		public function render_js_to_track_referer() {
			$min = '.min';
			if ( defined( 'WFFN_IS_DEV' ) && true === WFFN_IS_DEV ) {
				$min = '';
			}

			$data = apply_filters( 'wffn_conversion_tracking_localize_data', [
				'utc_offset'         => esc_attr( $this->get_timezone_offset() ),
				'site_url'           => esc_url( site_url() ),
				'genericParamEvents' => wp_json_encode( $this->get_generic_event_params() ),
				'cookieKeys'         => [ "flt", "timezone", "is_mobile", "browser", "fbclid", "gclid", "referrer", "fl_url" ]
			] );

			wp_enqueue_script( 'wfco-utm-tracking', plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/js/utm-tracker' . $min . '.js', array(), WooFunnel_Loader::$version, false );
			wp_localize_script( 'wfco-utm-tracking', 'wffnUtm', $data );

		}

		/**
		 * Add Generic event params to the data in events
		 * @return array
		 */
		public function get_generic_event_params() {
			$user = wp_get_current_user();
			if ( $user->ID !== 0 ) {
				$user_roles = implode( ',', $user->roles );
			} else {
				$user_roles = 'guest';
			}

			return array(
				'domain'     => substr( get_home_url( null, '', 'http' ), 7 ),
				'user_roles' => $user_roles,
				'plugin'     => 'Funnel Builder',
			);

		}


		/**
		 * Create DB tables
		 * Actions and bwf_conversion_tracking
		 */
		public function create_db_tables( $args, $tables ) {

			if ( $tables['version'] !== BWF_DB_VERSION || ! in_array( $this->conv_table, $tables['tables'], true ) ) {
				$args[] = [
					'name'   => $this->conv_table,
					'schema' => $this->conversion_table_schema(),
				];
			}

			return $args;
		}

		/**
		 * update conversion table after migration
		 * @return void
		 */
		public function update_conversion_table() {

			global $wpdb;
			$charset_collate = '';
			if ( $wpdb->has_cap( 'collation' ) ) {
				$charset_collate = $wpdb->get_charset_collate();
			}

			$schema = $this->conversion_table_schema();
			$schema = str_replace( array( '{table_prefix}', '{table_collate}' ), array( $wpdb->prefix, $charset_collate ), $schema );

			dbDelta( $schema );
			if ( ! empty( $wpdb->last_error ) ) {
				WFFN_Core()->logger->log( 'migration process failed update table ' . $wpdb->last_error . ' last query ' . $wpdb->last_query, 'fk_conv_migration', true );
			}

			$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'bwf_conversion_tracking WHERE funnel_id = %d OR  source = %d', 0, 0 ) );

			if ( ! empty( $wpdb->last_error ) ) {
				WFFN_Core()->logger->log( 'migration process failed optimised referrer table ' . $wpdb->last_error . ' last query ' . $wpdb->last_query, 'fk_conv_migration', true );

			}


		}

		/**
		 * Filter referrer to be saved inside the database
		 *
		 * @param string $url
		 *
		 * @return string
		 */
		public function filter_referrer( $url ) {
			$domain = get_site_url();

			$url    = str_replace( array( 'http://', 'https://' ), '', $url );
			$domain = str_replace( array( 'http://', 'https://' ), '', $domain );


			/**
			 * if its a same site referrer then return empty
			 */
			if ( false !== strpos( $url, $domain ) ) {
				return '';
			}

			/**
			 * Remove trailing slash from the end of the url
			 */
			$url = rtrim( $url, '/' );

			return $this->parse_url_query_param( $url );
		}

		public function conversion_table_schema() {
			$max_index_length = 191;
			$blank = "NOT NULL DEFAULT ''";

			return "CREATE TABLE `{table_prefix}" . $this->conv_table . "` (
						`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						`contact_id` bigint(20) unsigned NOT NULL default 0,
						`funnel_id` bigint(20) unsigned NOT NULL default 0,
						`step_id` bigint(20) unsigned NOT NULL default 0,
						`automation_id` bigint(20) unsigned NOT NULL default 0,
						`source` bigint(20) unsigned NOT NULL default 0,
						`type` tinyint(2) unsigned COMMENT '1- optin 2- wc_order 3- edd_order',
						`country` char(2),
						`value` double DEFAULT 0 NOT NULL,
						`checkout_total` double DEFAULT 0 NOT NULL,
						`bump_total` double DEFAULT 0 NOT NULL,
						`offer_total` double DEFAULT 0 NOT NULL,
						`bump_accepted` varchar(255)" . $blank . ",
						`bump_rejected` varchar(255) " . $blank . ",
						`offer_accepted` varchar(255) " . $blank . ",
						`offer_rejected` varchar(255) " . $blank . ",
						`first_click` DateTime NOT NULL,
						`device` varchar(100) " . $blank . ",
						`browser` varchar(100) " . $blank . ",
						`first_landing_url` varchar(255) " . $blank . ",
						`referrer` varchar(255) " . $blank . ",
						`utm_source` varchar(255) " . $blank . ",
						`utm_medium` varchar(255) " . $blank . ",
						`utm_campaign` varchar(255) " . $blank . ",
						`utm_term` varchar(255) " . $blank . ",
						`utm_content` varchar(255) " . $blank . ",
						`referrer_last` varchar(255) " . $blank . ",
						`utm_source_last` varchar(255) " . $blank . ",
						`utm_medium_last` varchar(255) " . $blank . ",
						`utm_campaign_last` varchar(255) " . $blank . ",
						`utm_term_last` varchar(255) " . $blank . ",
						`utm_content_last` varchar(255) " . $blank . ",
						`click_id` varchar(255) " . $blank . ",
						`journey` longtext,
						`timestamp` DateTime NOT NULL,
						PRIMARY KEY (`id`),
						KEY `id` (`id`),
						KEY `step_id` (`step_id`),
						KEY `funnel_id` (`funnel_id`),
						KEY `contact_id` (`contact_id`),
						KEY `utm_source` (utm_source($max_index_length)),
						KEY `utm_medium` (utm_medium($max_index_length)),
						KEY `utm_campaign` (utm_campaign($max_index_length)),    
						KEY `utm_term` (utm_term($max_index_length)),
						KEY `utm_source_last` (utm_source_last($max_index_length)),
						KEY `utm_medium_last` (utm_medium_last($max_index_length)),
						KEY `utm_campaign_last` (utm_campaign_last($max_index_length)),    
						KEY `utm_term_last` (utm_term_last($max_index_length)),
						KEY `bump_accepted` (`bump_accepted`),
						KEY `bump_rejected` (`bump_rejected`),
						KEY `offer_accepted` (`offer_accepted`),    
						KEY `offer_rejected` (`offer_rejected`),
						KEY `value` (`value`),
						KEY `source` (`source`),
						KEY `first_landing_url` (`first_landing_url`),
						KEY `referrer` (`referrer`),
						KEY `referrer_last` (`referrer_last`)			
						) {table_collate};";
		}

		/**
		 * @param $optin_id
		 * @param $posted_data
		 *
		 * @return void
		 */
		public function update_optin_tracking_data( $optin_id, $posted_data ) {
			$get_data = $this->get_common_tracking_data( true );

			$funnel_id = get_post_meta( $optin_id, '_bwf_in_funnel', true );

			$args = [
				'contact_id'     => ! empty( $posted_data['cid'] ) ? $posted_data['cid'] : 0,
				'type'           => 1,
				'value'          => 0,
				'step_id'        => $optin_id,
				'funnel_id'      => ! ( empty( $funnel_id ) ) ? $funnel_id : 0,
				'automation_id'  => 0,
				'source'         => isset( $posted_data['optin_entry_id'] ) ? $posted_data['optin_entry_id'] : 0,
				'country'        => isset( $get_data['country'] ) ? $get_data['country'] : '',
				'timestamp'      => current_time( 'mysql' ),//phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				'checkout_total' => 0,
				'bump_total'     => 0,
				'offer_total'    => 0,
				'bump_accepted'  => '',
				'bump_rejected'  => '',
				'offer_accepted' => '',
				'offer_rejected' => '',
			];

			$get_data = array_merge( $get_data, $args );

			$this->insert_tracking_data( $get_data );
		}

		/**
		 * @param $order
		 *
		 * @return void
		 */
		public function add_tracking_data_in_order_meta( $order ) {
			$order = apply_filters( 'bwf_tracking_insert_order', $order );

			if ( ! $order instanceof WC_Order ) {
				return;
			}
			/** save tracking data in order meta data */

			$get_data = $this->get_common_tracking_data();
			$order->update_meta_data( '_wffn_tracking_data', $get_data );
			$order->save_meta_data();
		}

		/**
		 * @param $offer_id
		 * @param $package
		 * @param $parent_order
		 * @param $new_order
		 *
		 *  insert tracking data in newly created upsell order
		 *
		 * @return void
		 */
		public function insert_tracking_data_in_upsell_order( $offer_id, $package, $parent_order, $new_order ) {
			if ( empty( $new_order ) || ! is_object( $new_order ) ) {
				return;
			}

			$tracking_data = BWF_WC_Compatibility::get_order_meta( $parent_order, '_wffn_tracking_data' );

			if ( empty ( $tracking_data ) || ! is_array( $tracking_data ) ) {
				return;
			}

			$order   = $new_order;
			$step_id = BWF_WC_Compatibility::get_order_meta( $parent_order, '_wfacp_post_id' );

			$date_created = $order->get_date_created();
			if ( ! empty( $date_created ) ) {
				$timezone = new DateTimeZone( wp_timezone_string() );
				$date_created->setTimezone( $timezone );
				$date_created = $date_created->format( 'Y-m-d H:i:s' );
			}

			$upsell_id = get_post_meta( $offer_id, '_funnel_id', true );
			$funnel_id = get_post_meta( $upsell_id, '_bwf_in_funnel', true );

			if ( 0 === absint( $funnel_id ) ) {
				return;
			}

			$args = [
				'contact_id'     => ! ( empty( $parent_order->get_meta( '_woofunnel_cid' ) ) ) ? $parent_order->get_meta( '_woofunnel_cid' ) : 0,
				'type'           => 2,
				'value'          => $order->get_total(),
				'step_id'        => $step_id,
				'funnel_id'      => ! ( empty( $funnel_id ) ) ? $funnel_id : 0,
				'automation_id'  => 0,
				'source'         => $order->get_id(),
				'country'        => $order->get_billing_country(),
				'timestamp'      => empty( $date_created ) ? current_time( 'mysql' ) : $date_created,
				'checkout_total' => 0,
				'bump_total'     => 0,
				'offer_total'    => isset( $package['total'] ) ? $package['total'] : 0,
				'bump_accepted'  => '',
				'bump_rejected'  => '',
				'offer_accepted' => wp_json_encode( array( ( string ) $offer_id ) ),
				'offer_rejected' => '',
			];

			$tracking_data = array_merge( $tracking_data, $args );

			if ( ! in_array( $new_order->get_status(), wc_get_is_paid_statuses(), true ) ) {
				$new_order->update_meta_data( '_wffn_tracking_data', $tracking_data );
				$new_order->update_meta_data( '_wffn_need_normalize', 'yes' );
				$new_order->update_meta_data( '_wfocu_offer_id', $offer_id );
				$order->save_meta_data();
				return;
			}
			/** Insert data */
			$this->insert_tracking_data( $tracking_data );
		}

		/**
		 * @param $order_id
		 *
		 * @return false|void
		 */
		public function insert_tracking_data_from_order_meta( $order_id ) {
			$order = apply_filters( 'bwf_tracking_insert_order', wc_get_order( $order_id ) );

			if ( ! $order instanceof WC_Order ) {
				return;
			}

			if ( $this->is_order_renewal( $order ) ) {
				return;
			}

			$tracking_data = BWF_WC_Compatibility::get_order_meta( $order, '_wffn_tracking_data' );

			if ( ! in_array( $order->get_status(), wc_get_is_paid_statuses(), true ) ) {
				$order->update_meta_data( '_wffn_tracking_data', $tracking_data );
				$order->update_meta_data( '_wffn_need_normalize', 'yes' );
				$order->save_meta_data();

				return false;
			}

			if ( ! empty ( $tracking_data ) && is_array( $tracking_data ) ) {
				$this->insert_tracking_order( $order, $tracking_data );
			}
		}

		/**
         *  Insert tracking data on change order status which is not
		 *  insert on thankyou hook due to order status not paid
		 *
		 * @param $order_id
		 * @param $from
		 * @param $to
		 *
		 * @return false|void
		 */
		public function maybe_insert_pending_tracking_data( $order_id, $from, $to ) {

			if ( ! class_exists( 'WFACP_Core' ) ) {
				return false;
			}

			if ( in_array( $from, wc_get_is_paid_statuses(), true ) ) {
				return false;
			}

			$order = wc_get_order( $order_id );

			if ( ! $order instanceof WC_Order ) {
				return;
			}
            $payment_method = $order->get_payment_method();

			/**
			 * If this is a renewal order then delete the meta if exists and return straight away
			 */
			if ( $this->is_order_renewal( $order ) ) {
				return false;
			}

			$tracking_data = $order->get_meta( '_wffn_tracking_data' );

			if ( empty( $tracking_data ) || ! is_array( $tracking_data ) ) {
				return false;
			}

			$ipn_gateways = WFACP_Core()->reporting->get_ipn_gateways();

			/**
			 * condition1 : if one of IPN gateways
			 * condition2: Thankyou page hook with pending status ran on this order
			 * condition3: In case thankyou page not open and order mark complete by IPN
			 */
			if ( in_array( $payment_method, $ipn_gateways, true ) || 'yes' === $order->get_meta( '_wffn_need_normalize' ) || ( class_exists( 'WC_Geolocation' ) && ( $order->get_customer_ip_address() !== WC_Geolocation::get_ip_address() ) ) ) {
				/**
				 * reaching this code means, 1) we have a ipn gateway OR 2) we have meta stored during thankyou
				 */
				if ( $order_id > 0 && in_array( $to, wc_get_is_paid_statuses(), true ) ) {

					$offer_id = $order->get_meta( '_wfocu_offer_id' );
					if ( ! empty( $offer_id ) ) {
						$this->insert_tracking_data( $tracking_data );
						$order->delete_meta_data( '_wffn_need_normalize' );
						$order->delete_meta_data( '_wfocu_offer_id' );
						$order->delete_meta_data( '_wffn_tracking_data' );
						$order->save_meta_data();

					} else {
						$this->insert_tracking_order( $order, $tracking_data );
						$order->delete_meta_data( '_wffn_need_normalize' );
						$order->save_meta_data();
					}

				}
			}

		}

		public function insert_tracking_order( $order, $tracking_data ) {//phpcs:ignore WordPressVIPMinimum.Hooks.AlwaysReturnInFilter.MissingReturnStatement
			/**
			 * prepare checkout data for insert
			 */
			$wfacp_report_data = BWF_WC_Compatibility::get_order_meta( $order, '_wfacp_report_data' );
			$step_id           = BWF_WC_Compatibility::get_order_meta( $order, '_wfacp_post_id' );
			$funnel_id         = get_post_meta( $step_id, '_bwf_in_funnel', true );
			$cid               = BWF_WC_Compatibility::get_order_meta( $order, '_woofunnel_cid' );
			$checkout_total    = ( is_array( $wfacp_report_data ) && isset( $wfacp_report_data['wfacp_total'] ) ) ? abs( $wfacp_report_data['wfacp_total'] ) : 0;

			/**
			 * prepare bump data for insert
			 */
			$bump_data     = BWF_WC_Compatibility::get_order_meta( $order, '_wfob_report_data' );
			$bump_accepted = array();
			$bump_rejected = array();
			$bump_total    = 0;

			if ( is_array( $bump_data ) && count( $bump_data ) > 0 ) {
				foreach ( $bump_data as $id => $b_item ) {
					if ( 1 == absint( $b_item['converted'] ) ) {
						$bump_total      = floatval( $bump_total ) + floatval( $b_item['total'] );
						$bump_accepted[] = ( string ) $id;
					} else {
						$bump_rejected[] = ( string ) $id;
					}
				}
			}

			$date_created = $order->get_date_created();
			if ( ! empty( $date_created ) ) {
				$timezone = new DateTimeZone( wp_timezone_string() );
				$date_created->setTimezone( $timezone );
				$date_created = $date_created->format( 'Y-m-d H:i:s' );
			}

			$args = [
				'contact_id'     => ! ( empty( $cid ) ) ? $cid : 0,
				'type'           => 2,
				'value'          => $order->get_total(),
				'step_id'        => ! ( empty( $step_id ) ) ? $step_id : 0,
				'funnel_id'      => ! ( empty( $funnel_id ) ) ? $funnel_id : 0,
				'automation_id'  => 0,
				'source'         => $order->get_id(),
				'country'        => $order->get_billing_country(),
				'timestamp'      => empty( $date_created ) ? current_time( 'mysql' ) : $date_created,
				'checkout_total' => $checkout_total,
				'bump_total'     => $bump_total,
				'offer_total'    => 0,
				'bump_accepted'  => ! empty( $bump_accepted ) ? wp_json_encode( $bump_accepted ) : '',
				'bump_rejected'  => ! empty( $bump_rejected ) ? wp_json_encode( $bump_rejected ) : '',
				'offer_accepted' => '',
				'offer_rejected' => '',
			];

			$tracking_data = array_merge( $tracking_data, $args );

			$tracking_data = $this->maybe_get_offer_data( $tracking_data, $order );


			if ( empty( $tracking_data['funnel_id'] ) && ( ! class_exists( 'WFFN_Common' ) || ! method_exists( 'WFFN_Common', 'get_store_checkout_id' ) || 0 === WFFN_Common::get_store_checkout_id() || false === wffn_string_to_bool( WFFN_Core()->get_dB()->get_meta( WFFN_Common::get_store_checkout_id(), 'status' ) ) ) ) {
				// No valid funnel ID found, set to 0 and return
				$tracking_data['funnel_id'] = 0;

				return;
			}

			// If we are here, it means we have a potential funnel ID
			if ( empty( $tracking_data['funnel_id'] ) ) {
				$funnel = new WFFN_Funnel( WFFN_Common::get_store_checkout_id() );

				if ( ! wffn_is_valid_funnel( $funnel ) || false === $funnel->is_funnel_has_native_checkout() ) {
					// Invalid funnel, set to 0 and return
					$tracking_data['funnel_id'] = 0;

					return;
				}

				// Valid funnel, assign the ID
				$tracking_data['funnel_id'] = WFFN_Common::get_store_checkout_id();
			}

			/**
			 * returning from here implies that we do not find any funnel ID to process any further
			 * And in this case we no longer need to insert the tracking data
			 */
			if ( empty( $tracking_data['funnel_id'] ) ) {
				return;
			}

			/** Insert data */
			$lastId = $this->insert_tracking_data( $tracking_data );

			if ( intval( $lastId ) > 0 ) {
				$order->delete_meta_data( '_wffn_tracking_data' );
				$order->save();
			}
		}

		public function get_common_tracking_data( $is_optin = false ) {
			$click_id = '';
			$get_data = $_COOKIE; //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
			if ( isset( $get_data['wffn_fbclid'] ) ) {
				$click_id = $get_data['wffn_fbclid'];
			} elseif ( isset( $get_data['wffn_gclid'] ) ) {
				$click_id = $get_data['wffn_gclid'];
			}

			$args = [
				'utm_source'        => isset( $get_data['wffn_utm_source'] ) ? $this->string_length( bwf_clean( $get_data['wffn_utm_source'] ) ) : '',
				'utm_medium'        => isset( $get_data['wffn_utm_medium'] ) ? $this->string_length( bwf_clean( $get_data['wffn_utm_medium'] ) ) : '',
				'utm_campaign'      => isset( $get_data['wffn_utm_campaign'] ) ? $this->string_length( bwf_clean( $get_data['wffn_utm_campaign'] ) ) : '',
				'utm_term'          => isset( $get_data['wffn_utm_term'] ) ? $this->string_length( bwf_clean( $get_data['wffn_utm_term'] ) ) : '',
				'utm_content'       => isset( $get_data['wffn_utm_content'] ) ? $this->string_length( bwf_clean( $get_data['wffn_utm_content'] ) ) : '',
				'first_landing_url' => isset( $get_data['wffn_fl_url'] ) ? bwf_clean( $get_data['wffn_fl_url'] ) : '',
				'browser'           => isset( $get_data['wffn_browser'] ) ? bwf_clean( $get_data['wffn_browser'] ) : '',
				'first_click'       => isset( $get_data['wffn_flt'] ) ? bwf_clean( $get_data['wffn_flt'] ) : '',
				'device'            => isset( $get_data['wffn_is_mobile'] ) ? ( true === bwf_string_to_bool( $get_data['wffn_is_mobile'] ) ? 'mobile' : 'desktop' ) : '',
				'click_id'          => $click_id,
				'referrer'          => isset( $get_data['wffn_referrer'] ) ? $this->filter_referrer( $get_data['wffn_referrer'] ) : '',
				'journey'           => '',
			];

			if ( true === $is_optin ) {
				$timezone        = isset( $get_data['wffn_timezone'] ) ? $this->string_length( bwf_clean( $get_data['wffn_timezone'] ) ) : '';
				$country_data    = $this->get_country_and_timezone( $timezone );
				$args['country'] = ( is_array( $country_data ) && isset( $country_data['country_code'] ) ) ? $country_data['country_code'] : '';
			}

			return $args;

		}

		/**
		 * Insert tracking data
		 *
		 * @param $args
		 *
		 * @return int
		 */
		public function insert_tracking_data( $args ) {
			global $wpdb;

			$args     = apply_filters( 'bwf_insert_conversion_tracking_data', $args );
			$inserted = $wpdb->insert( $wpdb->prefix . $this->conv_table, $args );

			$lastId = 0;
			if ( $inserted ) {
				$lastId = $wpdb->insert_id;
			}
			if ( ! empty( $wpdb->last_error ) ) {
				BWF_Logger::get_instance()->log( 'Get last error in ' . $this->conv_table . ' : ' . $wpdb->last_error . ' --- Last query ' . $wpdb->last_query, 'woofunnel-failed-actions', 'buildwoofunnels', true ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}


			return $lastId;
		}

		/**
		 * @param $timezone
		 *
		 * @return array|string
		 */
		public function get_country_and_timezone( $timezone ) {
			$result = '';
			if ( '' === $timezone ) {
				return $result;
			}

			ob_start();
			include dirname( __DIR__ ) . '/contact/data/contries-timzone.json'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingNonPHPFile
			$list = ob_get_clean();
			$list = json_decode( $list, true );

			$country_list = wp_list_pluck( $list, 'timezone' );

			//check valid timezone
			foreach ( $country_list as $key => $item ) {
				if ( false !== array_search( $timezone, $item, true ) ) {
					$result = array(
						'country_code' => $key,
						'timezone'     => $timezone
					);
					break;
				}
			}

			return $result;
		}

		/**
		 * get the timezone offset in minutes
		 * @return float|int
		 */
		public function get_timezone_offset() {
			$offset                 = 0;
			$offset_diff_in_seconds = current_time( 'timestamp' ) - current_time( 'timestamp', true );
			if ( absint( $offset_diff_in_seconds ) > 0 ) {
				$offset = $offset_diff_in_seconds / 60;
			}

			return $offset;
		}

		public function add_single_order_meta_box( $post_type, $post ) {

			if ( ! class_exists( 'WFFN_Common' ) ) {
				return;
			}

			if ( ! WFFN_Common::wffn_is_funnel_pro_active() ) {
				return;
			}

			if ( 'shop_order' !== $post_type && 'woocommerce_page_wc-orders' !== $post_type ) {
				return;
			}
			$order_id = 0;

			if ( isset( $_GET['id'] ) ) {
				$order_id = $_GET['id'];
			}

			if ( 0 === absint( $order_id ) && $post instanceof WP_Post ) {
				$order_id =$post->ID;
			}

			if ( 0 === absint( $order_id ) ) {
				return;
			}

			/**
			 * @todo we will update code showing for funnel meta box currently not have exact mata for check order create by funnel
			 * so we run query in conversion table and check order created by funnel
             */
            global $wpdb;
			$query    = $wpdb->prepare( "SELECT * from " . $wpdb->prefix . $this->conv_table . " WHERE source = %d", $order_id );
			$get_data = $wpdb->get_row( $query, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( empty( $get_data ) ) {
				return;
			}

			$data = array(
				'bwf_meta_data' => $get_data,
			);

			add_meta_box( 'bwfan_utm_info_box', __( 'Conversion Tracking', 'woofunnels' ), array(
				$this,
				'order_meta_box_data'
			), function_exists( 'wc_get_page_screen_id' ) ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order', 'side', 'default', $data );
		}

		public function is_order_renewal( $order ) {
			if ( is_numeric( $order ) ) {
				$order = wc_get_order( $order );
			}
			$subscription_renewal = BWF_WC_Compatibility::get_order_data( $order, '_subscription_renewal' );

			return ! empty( $subscription_renewal );
		}

		public function maybe_get_offer_data( $args, $order ) {

			if ( ! class_exists( 'WFOCU_Core' ) ) {
				return $args;
			}

			global $wpdb;

			/**
			 * offer data not save in upsell created order
			 */
			$sibling_order = BWF_WC_Compatibility::get_order_meta( $order, '_wfocu_sibling_order' );

			if ( ! empty( $sibling_order ) ) {
				return $args;
			}

			$order_id       = $order->get_id();
			$offer_accepted = array();
			$offer_rejected = array();
			$offer_total    = 0;


			$offer_query = $wpdb->prepare( "SELECT events.object_type as object_type, events.id as event_id, events.object_id as object_id, events.action_type_id as action_type_id, events.value as total FROM ".$wpdb->prefix ."wfocu_event AS events
                  LEFT JOIN ".$wpdb->prefix ."wfocu_session AS session ON ( events.sess_id = session.id ) WHERE 1=1 AND events.object_type = 'offer' AND (events.action_type_id = '4' OR events.action_type_id = '6' ) AND session.order_id = %s", $order_id );

			$offer_data = $wpdb->get_results( $offer_query, ARRAY_A );

			if ( is_array( $offer_data ) && count( $offer_data ) > 0 ) {
				foreach ( $offer_data as $o_item ) {
					if ( 4 == absint( $o_item['action_type_id'] ) ) {
						$offer_total      = floatval( $offer_total ) + floatval( $o_item['total'] );
						$offer_accepted[] = ( string ) $o_item['object_id'];
						/**
						 * remove from reject offer array
						 */
						$reject_key = array_search( $o_item['object_id'], $offer_rejected );
						if ( false !== $reject_key ) {
							unset( $offer_rejected[ $reject_key ] );
							$offer_rejected = array_values( $offer_rejected );
						}
					} else if ( 6 == absint( $o_item['action_type_id'] ) ) {
						$offer_rejected[] = ( string ) $o_item['object_id'];
					}
				}
			}

			/**
			 * Restricted insert data for offer if find sibling order
			 * because for accepted offer data already enter in offer newly created data
			 */
			$is_sibling = BWF_WC_Compatibility::get_order_meta( $order, '_wfocu_sibling_order' );

			if ( ! empty( $is_sibling ) ) {
				$offer_accepted = [];
			}

			$args['offer_total']    = $offer_total;
			$args['offer_accepted'] = ! empty( $offer_accepted ) ? wp_json_encode( $offer_accepted ) : '';
			$args['offer_rejected'] = ! empty( $offer_rejected ) ? wp_json_encode( $offer_rejected ) : '';

			return $args;

		}

		public function order_meta_box_data( $post, $meta_data ) {

			if ( ! is_array( $meta_data ) || ! isset( $meta_data['args'] ) || ! isset( $meta_data['args']['bwf_meta_data'] ) ) {
                return;
			}
			$get_data = $meta_data['args']['bwf_meta_data'];

			$first_click = ( isset( $get_data['first_click'] ) && '0000-00-00 00:00:00' !== $get_data['first_click'] ) ? $get_data['first_click'] : '';
			$timestamp   = isset( $get_data['timestamp'] ) ? $get_data['timestamp'] : '';

			$funnel_id = isset( $get_data['funnel_id'] ) ? $get_data['funnel_id'] : 0;
			$funnel    = $funnel_id;

			if ( class_exists( 'WFFN_Funnel' ) && class_exists( 'WFFN_Common' ) ) {
				$funnel_obj = new WFFN_Funnel( $funnel_id );
				if ( $funnel_obj instanceof WFFN_Funnel && $funnel_obj->get_id() > 0 ) {
					$link         = WFFN_Common::get_funnel_edit_link( $funnel_obj->get_id() );
					$funnel_title = ! empty( $funnel_obj->get_title() ) ? $funnel_obj->get_title() : $funnel_obj->get_id();
					$funnel       = '<a href="' . $link . '" target="_blank">' . $funnel_title . '</a>';
				}

			}
			$diff = '';
			$ref  = '';
			$data = [];
			if ( ! empty( $first_click ) ) {
				$d1   = strtotime( $timestamp );
				$d2   = strtotime( $first_click );
				$diff = human_time_diff( $d1, $d2 );
			}

			if ( isset( $get_data['referrer'] ) && $get_data['referrer'] !== '' ) {
				$ref = explode( '?', $get_data['referrer'] );
			}
			$data['funnel'] = array(
				'name'  => __( 'Funnel', 'woofunnels' ),
				'value' => $funnel
			);
			if ( '' !== $first_click ) {
				$data['first_click'] = array(
					'name'  => __( 'First Interaction', 'woofunnels' ),
					'value' => $first_click
				);
			}
			if ( '' !== $diff ) {
				$data['convert'] = array(
					'name'  => __( 'Conversion Time', 'woofunnels' ),
					'value' => $diff,
				);
			}
			if ( isset( $get_data['utm_source'] ) && '' !== $get_data['utm_source'] ) {
				$data['utm_source'] = array(
					'name'  => __( 'UTM Source', 'woofunnels' ),
					'value' => ucfirst( $get_data['utm_source'] ),
				);
			}
			if ( isset( $get_data['utm_medium'] ) && '' !== $get_data['utm_medium'] ) {
				$data['utm_medium'] = array(
					'name'  => __( 'UTM Medium', 'woofunnels' ),
					'value' => ucfirst( $get_data['utm_medium'] ),
				);
			}
			if ( isset( $get_data['utm_campaign'] ) && '' !== $get_data['utm_campaign'] ) {
				$data['utm_campaign'] = array(
					'name'  => __( 'UTM Campaign', 'woofunnels' ),
					'value' => $get_data['utm_campaign'],
				);
			}
			if ( isset( $get_data['utm_term'] ) && '' !== $get_data['utm_term'] ) {
				$data['utm_term'] = array(
					'name'  => __( 'UTM Term', 'woofunnels' ),
					'value' => $get_data['utm_term'],
				);
			}
			if ( isset( $get_data['utm_content'] ) && '' !== $get_data['utm_content'] ) {
				$data['utm_content'] = array(
					'name'  => __( 'UTM Content', 'woofunnels' ),
					'value' => $get_data['utm_content'],
				);
			}
			if ( isset( $get_data['referrer'] ) && '' !== $get_data['referrer'] ) {
				$data['referrer'] = array(
					'name'  => __( 'Referrer', 'woofunnels' ),
					'value' => ( is_array( $ref ) && isset( $ref[0] ) ) ? '<a href="' . $ref[0] . '" target="_blank">' . $ref[0] . '</a>' : ''
				);
			}
			if ( isset( $get_data['click_id'] ) ) {
				$data['click_id'] = array(
					'name'  => __( 'Click ID', 'woofunnels' ),
					'value' => ( '' !== $get_data['click_id'] ) ? __( 'Yes', 'woofunnels' ) : __( 'No', 'woofunnels' ),
				);
			}
			if ( isset( $get_data['device'] ) && '' !== $get_data['device'] ) {
				$data['device'] = array(
					'name'  => __( 'Device', 'woofunnels' ),
					'value' => ucfirst( $get_data['device'] ),
				);
			}
			if ( isset( $get_data['browser'] ) && '' !== $get_data['browser'] ) {
				$data['browser'] = array(
					'name'  => __( 'Browser', 'woofunnels' ),
					'value' => $get_data['browser'],
				);
			}

			$data = apply_filters( 'bwf_utm_tracking_meta_box', $data, $meta_data, $post );
			if ( empty( $data ) ) {
				return;
			}
			?>
            <style>
                .bwf-utm-box-data {
                    margin: 10px 0;
                }

                .bwf-utm-box-data > div > span:nth-child(1) {
                    font-weight: 500;
                    width: 80px;
                    display: inline-block;
                    min-width: 105px;
                }

                .bwf-utm-box-data > div {
                    margin-bottom: 8px;
                    display: flex;
                    word-break: break-all;
                }

                .bwf-utm-box-data .bwf-utm-data-gap {
                    display: block;
                    clear: both;
                    height: 1px;
                    border-bottom: 1px solid #eee;
                    margin-bottom: 10px;
                }
            </style>
            <div class="bwf-utm-box-data">
                <div class="bwf-utm-data-gap"></div>
				<?php
				foreach ( $data as $item ) {
					?>
                    <div>
                        <span class="bwf-utm-lable"><?php echo $item['name'] . ': '; ?></span>
                        <span class="bwf-utm-text"><?php echo $item['value']; ?></span>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
		}

		public function string_length( $string, $length = 255 ) {
			return ( strlen( $string ) > $length ) ? substr( $string, 0, $length ) : $string;
		}


		/**
		 * remove query param from url
		 *
		 * @param $url
		 * @param $is_journey
		 *
		 * @return mixed|string
		 */
		public function parse_url_query_param( $url, $is_journey = false ) {
			$get_referrer = ! empty( $url ) ? wp_parse_url( $url ) : '';
			$referrer_url = '';
			if ( is_array( $get_referrer ) ) {
				if ( ! $is_journey && isset( $get_referrer['host'] ) ) {
					$referrer_url = $get_referrer['host'] . ( isset( $get_referrer['path'] ) ? $get_referrer['path'] : '' );
				} else if ( isset( $get_referrer['path'] ) ) {
					$referrer_url = $get_referrer['path'];

				}
			}

			return $this->string_length( $referrer_url );
		}

		/**
		 * Delete order entry from conversion table on order delete
		 *
		 * @param $order_id
		 *
		 * @return void
		 */
		public function delete_conversion_row( $order_id ) {

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
			$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . $this->conv_table . " WHERE type= 2 AND source = %1s ", $order_id ) ); //phpcs:ignore WordPress.DB.PreparedSQL
		}

		public function partially_refunded_process( $order_id, $refund_id ) {
			global $wpdb;

			/**
			 * check if db updated
			 */
			if ( ! function_exists( 'wffn_conversion_tracking_migrator' ) ) {
				return;
			}

			if ( ! in_array( absint( wffn_conversion_tracking_migrator()->get_upgrade_state() ), [ 3, 4 ], true ) ) {
				return;
			}

			$order           = wc_get_order( $order_id );
			$refund          = wc_get_order( $refund_id );
			$total_refund    = 0;
			$checkout_refund = 0;
			$offer_refund    = 0;
			$bump_refund     = 0;

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
					$total_refund       += abs( $refund_item->get_total() );

					if ( '' !== $_bump_purchase ) {
						$bump_refund += abs( $refund_item->get_total() );
					} else if ( '' !== $_upstroke_purchase ) {
						$offer_refund += abs( $refund_item->get_total() );
					} else {
						$checkout_refund += abs( $refund_item->get_total() );
					}

				}
			} else {
				$total_refund = BWF_WC_Compatibility::get_order_meta( $refund, '_refund_amount' );
			}

			if ( $total_refund > 0 ) {
				$get_totals = $wpdb->get_row( "SELECT value, checkout_total, bump_total, offer_total FROM " . $wpdb->prefix . $this->conv_table . " WHERE type = 2 AND source = " . $order_id, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared*/

				if ( is_array( $get_totals ) && count( $get_totals ) > 0 ) {
					/**
					 * get save totals amount from conversion table
					 * Set 0 for checkout, bump and upsell amount if total refund amount is 0
					 */
					$total_value    = ! empty( $get_totals['value'] ) ? ( float ) $get_totals['value'] : 0;
					$checkout_total = ! empty( $get_totals['checkout_total'] ) ? ( float ) $get_totals['checkout_total'] : 0;
					$bump_total     = ! empty( $get_totals['bump_total'] ) ? ( float ) $get_totals['bump_total'] : 0;
					$offer_total    = ! empty( $get_totals['offer_total'] ) ? ( float ) $get_totals['offer_total'] : 0;

					$update_args = array(
						'value'          => ( $total_value <= $total_refund ) ? 0 : $total_value - $total_refund,
						'checkout_total' => ( $total_refund === 0 || $checkout_total <= $checkout_refund ) ? 0 : $checkout_total - $checkout_refund,
						'bump_total'     => ( $total_refund === 0 || $bump_total <= $bump_refund ) ? 0 : $bump_total - $bump_refund,
						'offer_total'    => ( $total_refund === 0 || $offer_total <= $offer_refund ) ? 0 : $offer_total - $offer_refund
					);
					$wpdb->update( $wpdb->prefix . $this->conv_table, $update_args, [ 'type' => 2, 'source' => $order_id ] );

				}
			}
		}

		public function conversion_table_name() {
			return $this->conv_table;

		}

	}

	BWF_Ecomm_Tracking_Common::get_instance();
}