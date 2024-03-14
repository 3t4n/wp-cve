<?php

/**
 * This class take care of ecommerce tracking setup
 * It renders necessary javascript code to fire events as well as creates dynamic data for the tracking
 * @author woofunnels.
 */
if ( ! class_exists( 'WFFN_Ecomm_Tracking' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Ecomm_Tracking extends WFFN_Ecomm_Tracking_Common {
		private static $ins = null;
		private $data = [];
		private $general_data = [];
		public $api_events = [];

		public function __construct() {
			parent::__construct();
			add_action( 'wp_head', array( $this, 'render' ), 90 );

			add_action( 'wp_enqueue_scripts', array( $this, 'tracking_log_js' ) );
			add_filter( 'wfocu_print_tracking_script', array( $this, 'maybe_print_tracking_code' ) );


		}

		public static function get_instance() {
			if ( self::$ins === null ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		public function should_render_view( $type ) {
			if ( $type === 'ga' ) {
				return $this->do_track_ga_view();
			} elseif ( $type === 'fb' ) {

				return $this->do_track_fb_view();
			} elseif ( $type === 'gad' ) {

				return $this->do_track_gad_view();
			} elseif ( $type === 'snapchat' ) {

				return $this->do_track_snapchat_view();
			} elseif ( $type === 'pint' ) {

				return $this->do_track_pint_view();
			} elseif ( $type === 'tiktok' ) {

				return $this->do_track_tiktok_view();
			}

			return false;

		}

		public function do_track_ga_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_page_view_global' ) ) ) {
				return true;
			}

			$ga_tracking = $this->admin_general_settings->get_option( 'is_ga_purchase_page_view' );
			if ( is_array( $ga_tracking ) && count( $ga_tracking ) > 0 && 'yes' === $ga_tracking[0] ) {
				return true;
			}

			return false;
		}

		/**
		 * maybe render script to fire fb pixel view event
		 */
		public function do_track_fb_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_page_view_global' ) ) ) {
				return true;
			}

			$fb_tracking = $this->admin_general_settings->get_option( 'is_fb_purchase_page_view' );
			if ( is_array( $fb_tracking ) && count( $fb_tracking ) > 0 && 'yes' === $fb_tracking[0] ) {
				return true;
			}

			return false;

		}

		/**
		 * maybe render script to fire fb pixel view event
		 */
		public function do_track_gad_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_page_view_global' ) ) ) {
				return true;
			}

			$view_tracking = $this->admin_general_settings->get_option( 'is_gad_pageview_event' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
			}

			return false;

		}


		/**
		 * maybe render script to fire fb pixel view event
		 */
		public function do_track_snapchat_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_page_view_global' ) ) ) {
				return true;
			}

			$view_tracking = $this->admin_general_settings->get_option( 'is_snapchat_pageview_event' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
			}

			return false;

		}

		/**
		 * maybe render script to fire fb pixel view event
		 */
		public function do_track_pint_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_pint_page_view_global' ) ) ) {
				return true;
			}

			$view_tracking = $this->admin_general_settings->get_option( 'is_pint_pageview_event' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
			}

			return false;
		}

		public function do_track_tiktok_view() {
			if ( true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_tiktok_page_view_global' ) ) ) {
				return true;
			}

			$view_tracking = $this->admin_general_settings->get_option( 'is_tiktok_pageview_event' );
			if ( is_array( $view_tracking ) && count( $view_tracking ) > 0 && 'yes' === $view_tracking[0] ) {
				return true;
			}

			return false;

		}


		public function render() {
			if ( $this->should_render() ) {
				$this->maybe_save_order_data( $this->get_current_order() );
				$this->render_fb();
				$this->render_ga();
				$this->render_gad();
				$this->render_general_data();
				$this->render_pint();
				$this->render_tiktok();
				$this->render_snapchat();
				$this->update_order_event_pushed();
				$this->maybe_render_conv_api();
			}
		}

		public function is_purchase_event_pushed() {
			$get_order = $this->get_current_order();
			if ( empty( $get_order ) ) {
				return false;
			}

			return ( 'yes' === $get_order->get_meta( '_wffn_ecom_purchase', true ) );
		}

		public function update_order_event_pushed() {
			$get_order = $this->get_current_order();
			if ( empty( $get_order ) ) {
				return false;
			}
			if ( true === $this->is_purchase_event_pushed() ) {
				return false;
			}
			$get_order->update_meta_data( '_wffn_ecom_purchase', 'yes' );
			$get_order->save_meta_data();
		}


		/**
		 * render script to print general data.
		 */
		public function render_general_data() {
			if ( $this->should_render() ) {
				$general_data = $this->general_data;
				if ( is_array( $general_data ) && count( $general_data ) > 0 ) { ?>
                    <script type="text/javascript">
                        let wffn_tracking_data = JSON.parse('<?php echo wp_json_encode( $general_data ); ?>');
                    </script>
					<?php
					do_action( 'wffn_custom_purchase_tracking', $general_data );
				}
			}
		}


		/**
		 * Decide whether script should render or not
		 * Bases on condition given and based on the action we are in there exists some boolean checks
		 *
		 * @param bool $check_session whether consider thank you page
		 *
		 * @return bool
		 */
		public function should_render( $check_session = true ) {
			if ( false === parent::should_render( $check_session ) ) {
				return false;
			}
			if ( true === apply_filters( 'wffn_do_not_run_ecomm_purchase_on_thankyou', defined( 'WFOCU_VERSION' ) ) ) {
				return false;
			}

			if ( WFFN_Core()->thank_you_pages->is_wfty_page() && ( ( $check_session && WFFN_Core()->data->has_valid_session() ) || ! $check_session ) ) {
				return true;

			}

			if ( WFFN_Core()->thank_you_pages->is_wfty_page() ) {

				if ( 0 === WFFN_Common::get_store_checkout_id() ) {
					return false;
				}

				$funnel = new WFFN_Funnel( WFFN_Common::get_store_checkout_id() );

				/**
				 * Check if this is a valid funnel and has native checkout
				 */
				if ( wffn_is_valid_funnel( $funnel ) && true === $funnel->is_funnel_has_native_checkout() ) {
					return true;
				}
			}

			if ( true === apply_filters( 'wffn_allow_native_thankyou_tracking', is_order_received_page(), $check_session ) ) {
				return true;

			}

			return false;
		}

		public function get_advanced_pixel_data( $type ) {
			$data = $this->data;

			if ( ! is_array( $data ) ) {
				return array();
			}

			if ( ! isset( $data[ $type ] ) ) {
				return array();
			}

			if ( ! isset( $data[ $type ]['advanced'] ) ) {
				return array();
			}

			return $data[ $type ]['advanced'];
		}


		/**
		 * Maybe print facebook pixel javascript
		 * @see WFFN_Ecomm_Tracking::render_fb();
		 */
		public function maybe_print_fb_script() {
			$data = $this->data; //phpcs:ignore
			if ( $this->do_track_fb_purchase_event() ) {
				include_once WFFN_Core()->thank_you_pages->get_module_path() . 'js-blocks/analytics-fb.phtml'; //phpcs:ignore
			}


			if ( $this->do_track_fb_general_event() ) {
				global $post;

				$thank_you_id           = $post->ID;
				$getEventName           = $this->admin_general_settings->get_option( 'general_event_name' );
				$params                 = array();
				$params['post_type']    = $post->post_type;
				$params['content_name'] = get_the_title( $thank_you_id );
				$params['post_id']      = $thank_you_id;
				?>
                var wffnGeneralData = <?php echo wp_json_encode( $params ); ?>;

                wffnGeneralData = (typeof wffnAddTrafficParamsToEvent !== "undefined")?wffnAddTrafficParamsToEvent(wffnGeneralData ):wffnGeneralData;
                fbq('trackCustom', '<?php echo esc_js( $getEventName ); ?>', wffnGeneralData,{'eventID': '<?php echo esc_attr( $this->get_event_id( 'trackCustom' ) ); ?>'});
				<?php
				if ( $this->is_conversion_api() ) {

					$this->api_events[] = array( 'event' => 'trackCustom', 'event_id' => $this->get_event_id( 'trackCustom' ) );

				}
			}
		}

		public function do_track_fb_synced_purchase() {
			$do_track_fb_synced_purchase = $this->admin_general_settings->get_option( 'is_fb_synced_event' );
			if ( is_array( $do_track_fb_synced_purchase ) && count( $do_track_fb_synced_purchase ) > 0 && 'yes' === $do_track_fb_synced_purchase[0] ) {
				return true;
			}

			return false;
		}

		public function do_track_fb_purchase_event() {
			$do_track_fb_purchase_event = $this->admin_general_settings->get_option( 'is_fb_purchase_event' );
			if ( is_array( $do_track_fb_purchase_event ) && count( $do_track_fb_purchase_event ) > 0 && 'yes' === $do_track_fb_purchase_event[0] ) {
				return true;
			}

			return false;
		}

		public function do_track_fb_general_event() {
			$enable_general_event = $this->admin_general_settings->get_option( 'enable_general_event' );
			if ( is_array( $enable_general_event ) && count( $enable_general_event ) > 0 && 'yes' === $enable_general_event[0] ) {
				return true;
			}

			return false;
		}


		public function is_ga4_tracking() {
			$is_ga4_tracking = $this->admin_general_settings->get_option( 'is_ga4_tracking' );
			if ( is_array( $is_ga4_tracking ) && count( $is_ga4_tracking ) > 0 && 'yes' === $is_ga4_tracking[0] ) {
				return true;
			}

			return false;
		}


		/**
		 * Maybe print google analytics javascript
		 * @see WFFN_Ecomm_Tracking::render_ga();
		 * @see WFFN_Ecomm_Tracking::render_gad();
		 */
		public function maybe_print_gtag_script( $k, $code, $label, $track = false, $is_gads = false ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			$data = $this->data;
			if ( true === $track && is_array( $data ) && ( isset( $data['ga'] ) || isset( $data['gad'] ) ) ) {
				include_once WFFN_Core()->thank_you_pages->get_module_path() . 'js-blocks/analytics-gad.phtml'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingNonPHPFile,WordPressVIPMinimum.Files.IncludingFile.UsingCustomFunction
			}
		}

		public function do_track_snapchat() {
			$do_track_purchase = $this->admin_general_settings->get_option( 'is_snapchat_purchase_event' );
			if ( is_array( $do_track_purchase ) && count( $do_track_purchase ) > 0 && 'yes' === $do_track_purchase[0] ) {
				return true;
			}

			return false;
		}

		/**
		 * Maybe print google analytics javascript
		 * @see WFFN_Ecomm_Tracking::render_ga();
		 * @see WFFN_Ecomm_Tracking::render_gad();
		 */
		public function maybe_print_snapchat_ecomm() { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			$data = $this->data;
			if ( true === $this->do_track_snapchat() && is_array( $data ) && isset( $data['snapchat'] ) ) {

				?>

                snaptr('track', 'PURCHASE', <?php echo wp_json_encode( $data['snapchat'] ); ?>);

				<?php

			}
		}

		public function do_track_tiktok() {
			$do_track_purchase = $this->admin_general_settings->get_option( 'is_tiktok_purchase_event' );
			if ( is_array( $do_track_purchase ) && count( $do_track_purchase ) > 0 && 'yes' === $do_track_purchase[0] ) {
				return true;
			}

			return false;
		}

		public function do_track_cp_tiktok() {
			$do_cp_purchase = $this->admin_general_settings->get_option( 'is_tiktok_complete_payment_event' );
			if ( is_array( $do_cp_purchase ) && count( $do_cp_purchase ) > 0 && 'yes' === $do_cp_purchase[0] ) {
				return true;
			}

			return false;
		}

		/**
		 * Maybe print google analytics javascript
		 * @see WFFN_Ecomm_Tracking::render_ga();
		 * @see WFFN_Ecomm_Tracking::render_gad();
		 */
		public function maybe_print_tiktok_ecomm( $id, $purchase = false, $complete_payment = false ) {  //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			$data = $this->data;
			if ( true === $this->do_track_tiktok() && is_array( $data ) ) {
				include_once WFFN_Core()->thank_you_pages->get_module_path() . 'js-blocks/analytics-tiktok.phtml'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingNonPHPFile,WordPressVIPMinimum.Files.IncludingFile.UsingCustomFunction
			}
		}


		public function do_track_pint() {
			$do_track_purchase = $this->admin_general_settings->get_option( 'is_pint_purchase_event' );
			if ( is_array( $do_track_purchase ) && count( $do_track_purchase ) > 0 && 'yes' === $do_track_purchase[0] ) {
				return true;
			}

			return false;
		}

		/**
		 * Maybe print google analytics javascript
		 * @see WFFN_Ecomm_Tracking::render_ga();
		 * @see WFFN_Ecomm_Tracking::render_gad();
		 */
		public function maybe_print_pint_ecomm() { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			$data = $this->data;
			if ( true === $this->do_track_pint() && is_array( $data ) ) {
				include_once WFFN_Core()->thank_you_pages->get_module_path() . 'js-blocks/analytics-pint.phtml'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingNonPHPFile,WordPressVIPMinimum.Files.IncludingFile.UsingCustomFunction
			}
		}


		/**
		 * * @hooked over `wp_head`
		 *
		 * That will be further used by WFFN_Ecomm_Tracking::render_fb() && WFFN_Ecomm_Tracking::render_ga()
		 *
		 * @param WC_Order $order
		 *
		 * @return false
		 */
		public function maybe_save_order_data( $order ) {
			if ( $order instanceof WC_Order ) {
				$order = wc_get_order( $order );
			}

			if ( ! $order instanceof WC_Order ) {
				return false;
			}
			if ( true === $this->is_purchase_event_pushed() ) {
				return false;
			}
			$order_id = BWF_WC_Compatibility::get_order_id( $order );
			$this->maybe_save_order_data_general( $order );
			if ( empty( $this->data ) ) {
				$items               = $order->get_items( 'line_item' );
				$content_ids         = [];
				$content_name        = [];
				$category_names      = [];
				$num_qty             = 0;
				$products            = [];
				$google_ads_products = [];
				$pint_products       = [];
				$google_products     = [];
				$tiktok_contents     = [];
				$billing_email       = BWF_WC_Compatibility::get_order_data( $order, 'billing_email' );
				foreach ( $items as $item ) {
					$pid     = $item->get_product_id();
					$product = wc_get_product( $pid );
					if ( $product instanceof WC_product ) {

						$category       = $product->get_category_ids();
						$content_name[] = $product->get_title();
						$get_content_id = $content_ids[] = $this->get_content_id( $item->get_product() );

						$category_name = '';

						if ( is_array( $category ) && count( $category ) > 0 ) {
							$category_id = $category[0];
							if ( is_numeric( $category_id ) && $category_id > 0 ) {
								$cat_term = get_term_by( 'id', $category_id, 'product_cat' );
								if ( $cat_term ) {
									$category_name    = $cat_term->name;
									$category_names[] = $category_name;
								}
							}
						}
						$num_qty    += $item->get_quantity();
						$products[] = array_map( 'html_entity_decode', array(
							'name'       => $product->get_title(),
							'category'   => ( $category_name ),
							'id'         => $get_content_id,
							'quantity'   => $item->get_quantity(),
							'item_price' => $order->get_line_subtotal( $item ),
						) );


						$get_content_id_pint = $this->get_content_id( $item->get_product(), 'pint' );

						$pint_products[] = array_map( 'html_entity_decode', array(
							'product_name'     => $product->get_title(),
							'product_category' => ( $category_name ),
							'product_id'       => $get_content_id_pint,
							'product_quantity' => $item->get_quantity(),
							'product_price'    => $order->get_line_total( $item ),
						) );


						$get_content_id_gad = $this->get_content_id( $item->get_product(), 'google_ads' );

						$google_ads_products[] = array_map( 'html_entity_decode', array(
							'id'       => apply_filters( 'wffn_ga_ecomm_id', $get_content_id_gad, $product ),
							'sku'      => empty( $product->get_sku() ) ? $product->get_id() : $product->get_sku(),
							'category' => $category_name,
							'name'     => $product->get_title(),
							'quantity' => $item->get_quantity(),
							'price'    => $order->get_line_total( $item ),
						) );


						$get_content_id_ga = $this->get_content_id( $item->get_product(), 'google_ua' );

						$google_products[] = array_map( 'html_entity_decode', array(
							'id'       => apply_filters( 'wffn_ga_ecomm_id', $get_content_id_ga, $product ),
							'sku'      => empty( $product->get_sku() ) ? $product->get_id() : $product->get_sku(),
							'category' => $category_name,
							'name'     => $product->get_title(),
							'quantity' => $item->get_quantity(),
							'price'    => ( $item->get_quantity() ) > 1 ? $order->get_line_total( $item ) / $item->get_quantity() : $order->get_line_total( $item ),
                        ) );
						$tiktok_contents[] = array_map( 'html_entity_decode', array(
							'content_id'   => $product->get_id(),
							'quantity'     => $item->get_quantity(),
							'content_type' => 'product'
						) );

					}
				}

				$advanced = array();
				/**
				 * Facebook advanced matching
				 */
				if ( $this->is_fb_advanced_tracking_on() ) {

					if ( ! empty( $billing_email ) ) {
						$advanced['em'] = $billing_email;
					}

					$billing_phone = BWF_WC_Compatibility::get_order_data( $order, 'billing_phone' );
					if ( ! empty( $billing_phone ) ) {
						$advanced['ph'] = $billing_phone;
					}

					$shipping_first_name = BWF_WC_Compatibility::get_order_data( $order, 'shipping_first_name' );
					if ( ! empty( $shipping_first_name ) ) {
						$advanced['fn'] = $shipping_first_name;
					}

					$shipping_last_name = BWF_WC_Compatibility::get_order_data( $order, 'shipping_last_name' );
					if ( ! empty( $shipping_last_name ) ) {
						$advanced['ln'] = $shipping_last_name;
					}

					$shipping_city = BWF_WC_Compatibility::get_order_data( $order, 'shipping_city' );
					if ( ! empty( $shipping_city ) ) {
						$advanced['ct'] = $shipping_city;
					}

					$shipping_state = BWF_WC_Compatibility::get_order_data( $order, 'shipping_state' );
					if ( ! empty( $shipping_state ) ) {
						$advanced['st'] = $shipping_state;
					}

					$shipping_postcode = BWF_WC_Compatibility::get_order_data( $order, 'shipping_postcode' );
					if ( ! empty( $shipping_postcode ) ) {
						$advanced['zp'] = $shipping_postcode;
					}
					$billing_country = BWF_WC_Compatibility::get_order_data( $order, 'billing_country' );
					if ( ! empty( $billing_country ) ) {
						$advanced['country'] = $billing_country;
					}
				}

				/**
				 * Facebook advanced matching
				 */
				$tiktok_advanced = array();
				if ( ! empty( $billing_email ) ) {
					$tiktok_advanced['sha256_email'] = hash( 'sha256', $billing_email );
				}
				$billing_phone = BWF_WC_Compatibility::get_order_data( $order, 'billing_phone' );
				if ( ! empty( $billing_phone ) ) {
					$tiktok_advanced['sha256_phone_number'] = hash( 'sha256', $billing_phone );
				}

                if ( $order->get_customer_id() > 0 ) {
                    $tiktok_advanced['external_id'] = hash( 'sha256', $order->get_customer_id() );
                }
				$fb_total = $this->get_total_order_value( $order, 'order', 'fb' );

				$purchase_data = array(
					'fb'   => array(
						'products'       => $products,
						'total'          => ( 0.00 ===  $fb_total || '0.00' === $fb_total ) ? 0 : $fb_total,
						'currency'       => BWF_WC_Compatibility::get_order_currency( $order ),
						'advanced'       => $advanced,
						'content_ids'    => $content_ids,
						'content_name'   => $content_name,
						'category_name'  => array_map( 'html_entity_decode', $category_names ),
						'num_qty'        => $num_qty,
						'additional'     => $this->purchase_custom_aud_params( $order ),
						'transaction_id' => BWF_WC_Compatibility::get_order_id( $order ),
						'is_order'       => BWF_WC_Compatibility::get_order_id( $order ),
					),
					'pint' => array(
						'order_id'       => BWF_WC_Compatibility::get_order_id( $order ),
						'products'       => $pint_products,
						'total'          => $this->get_total_order_value( $order, 'order', 'pint' ),
						'currency'       => BWF_WC_Compatibility::get_order_currency( $order ),
						'email'          => $billing_email,
						'post_type'      => get_post_type(),
						'order_quantity' => $num_qty,
						'shipping'       => BWF_WC_Compatibility::get_order_shipping_total( $order ),
						'page_title'     => get_the_title(),
						'post_id'        => get_the_ID(),
						'event_url'      => $this->getRequestUri(),
						'eventID'        => WFFN_Core()->data->generate_transient_key()
					),
				);

				$gad = apply_filters( 'wffn_ecomm_tracking_gad_params', array(
					'event_category'   => 'ecommerce',
					'transaction_id'   => (string) BWF_WC_Compatibility::get_order_id( $order ),
					'value'            => $this->get_total_order_value( $order, 'order', 'gad' ),
					'currency'         => BWF_WC_Compatibility::get_order_currency( $order ),
					'items'            => $google_ads_products,
					'tax'              => $order->get_total_tax(),
					'shipping'         => BWF_WC_Compatibility::get_order_shipping_total( $order ),
					'ecomm_prodid'     => wp_list_pluck( $google_ads_products, 'id' ),
					'ecomm_pagetype'   => 'purchase',
					'ecomm_totalvalue' => array_sum( wp_list_pluck( $google_ads_products, 'price' ) ),
				) );
				$ga  = apply_filters( 'wffn_ecomm_tracking_ga_params', array(
					'event_category'   => 'ecommerce',
					'transaction_id'   => (string) BWF_WC_Compatibility::get_order_id( $order ),
					'value'            => $this->get_total_order_value( $order, 'order', 'ga' ),
					'currency'         => BWF_WC_Compatibility::get_order_currency( $order ),
					'items'            => $google_products,
					'tax'              => $order->get_total_tax(),
					'shipping'         => BWF_WC_Compatibility::get_order_shipping_total( $order ),
					'ecomm_prodid'     => wp_list_pluck( $google_products, 'id' ),
					'ecomm_pagetype'   => 'purchase',
					'ecomm_totalvalue' => array_sum( wp_list_pluck( $google_products, 'price' ) ),
				) );

				$tiktok = apply_filters( 'wffn_ecomm_tracking_tiktok_params', [

					'contents'         => $tiktok_contents,
					'currency'         => BWF_WC_Compatibility::get_order_currency( $order ),
					'value'            => $this->get_total_order_value( $order, 'order' ),
					'content_name'     => implode( ', ', $content_name ),
					'content_category' => implode( ', ', array_map( 'html_entity_decode', $category_names ) ),
					'advanced'         => $tiktok_advanced,
				] );


				if ( $this->is_ga4_tracking() ) {

					if ( is_array( $ga['items'] ) && count( $ga['items'] ) > 0 ) {
						foreach ( $ga['items'] as &$ga_items ) {
							$ga_items['item_id']   = $ga_items['id'];
							$ga_items['item_name'] = $ga_items['name'];
							$ga_items['currency']  = BWF_WC_Compatibility::get_order_currency( $order );
							$ga_items['index']     = 0;
							$count                 = 1;
							if ( is_array( $category_names ) && count( $category_names ) > 0 ) {
								foreach ( $category_names as $cat_name ) {
									if ( 1 === $count ) {
										$ga_items['item_category'] = $cat_name;

									} else {
										$ga_items[ 'item_category' . $count ] = $cat_name;
									}
									$count ++;
								}
							}
							unset( $ga_items['id'] );
							unset( $ga_items['name'] );

						}
					}

					unset( $ga['event_category'] );
					unset( $ga['ecomm_pagetype'] );
					unset( $ga['ecomm_prodid'] );
					unset( $ga['ecomm_totalvalue'] );

					$ga['content_name'] = get_the_title();
					$ga['event_url']    = $this->getRequestUri();
					$ga['post_id']      = get_the_ID();
					$ga['post_type']    = get_post_type();
				}

				$purchase_data['ga']       = $ga;
				$purchase_data['gad']      = $gad;
				$purchase_data['tiktok']   = $tiktok;
				$purchase_data['snapchat'] = [
					'item_ids'       => $content_ids,
					'currency'       => BWF_WC_Compatibility::get_order_currency( $order ),
					'price'          => $this->get_total_order_value( $order, 'order' ),
					'number_items'   => count( $products ),
					'transaction_id' => BWF_WC_Compatibility::get_order_id( $order ),
				];

				$this->data = $purchase_data;
			}
			WFFN_Core()->logger->log( "Data tracking successfully saved for order id: $order_id." );
		}


		public function gad_product_id( $product_id ) {
			$prefix = $this->admin_general_settings->get_option( 'id_prefix_gad' );
			$suffix = $this->admin_general_settings->get_option( 'id_suffix_gad' );

			return $prefix . $product_id . $suffix;
		}

		/**
		 * Get the value of purchase event for the different cases of calculations.
		 *
		 * @param WC_Order/offer_Data $data
		 * @param string $type type for which this function getting called, order|offer
		 *
		 * @return string the modified order value
		 */
		public function get_total_order_value( $data, $type = 'order', $party = 'fb' ) {

			$disable_shipping = $this->is_disable_shipping( $party );
			$disable_taxes    = $this->is_disable_taxes( $party );
			if ( 'order' === $type ) {
				//process order
				if ( ! $disable_taxes && ! $disable_shipping ) {
					//send default total
					$total = $data->get_total();
				} elseif ( ! $disable_taxes && $disable_shipping ) {

					$cart_total     = floatval( $data->get_total( 'edit' ) );
					$shipping_total = floatval( $data->get_shipping_total( 'edit' ) );
					$shipping_tax   = floatval( $data->get_shipping_tax( 'edit' ) );

					$total = $cart_total - $shipping_total - $shipping_tax;
				} elseif ( $disable_taxes && ! $disable_shipping ) {

					$cart_subtotal = $data->get_subtotal();

					$discount_total = floatval( $data->get_discount_total( 'edit' ) );
					$shipping_total = floatval( $data->get_shipping_total( 'edit' ) );

					$total = $cart_subtotal - $discount_total + $shipping_total;
				} else {
					$cart_subtotal = $data->get_subtotal();

					$discount_total = floatval( $data->get_discount_total( 'edit' ) );

					$total = $cart_subtotal - $discount_total;
				}
			} else {
				//process offer
				if ( ! $disable_taxes && ! $disable_shipping ) {

					//send default total
					$total = $data['total'];

				} elseif ( ! $disable_taxes && $disable_shipping ) {
					//total - shipping cost - shipping tax
					$total = $data['total'] - ( isset( $data['shipping']['diff'] ) && isset( $data['shipping']['diff']['cost'] ) ? $data['shipping']['diff']['cost'] : 0 ) - ( isset( $data['shipping']['diff'] ) && isset( $data['shipping']['diff']['tax'] ) ? $data['shipping']['diff']['tax'] : 0 );

				} elseif ( $disable_taxes && ! $disable_shipping ) {
					//total - taxes
					$total = $data['total'] - ( isset( $data['taxes'] ) ? $data['taxes'] : 0 );

				} else {

					//total - taxes - shipping cost
					$total = $data['total'] - ( isset( $data['taxes'] ) ? $data['taxes'] : 0 ) - ( isset( $data['shipping']['diff'] ) && isset( $data['shipping']['diff']['cost'] ) ? $data['shipping']['diff']['cost'] : 0 );

				}
			}
			$total = apply_filters( 'wffn_purchase_ecommerce_pixel_tracking_value', $total, $data, $party, $this->admin_general_settings );
			return number_format( $total, wc_get_price_decimals(), '.', '' );
		}

		public function is_disable_shipping( $party = 'fb' ) {
			if ( $party === 'fb' ) {
				$exclude_from_total = $this->admin_general_settings->get_option( 'exclude_from_total' );
			} elseif ( $party === 'ga' ) {
				$exclude_from_total = $this->admin_general_settings->get_option( 'ga_exclude_from_total' );
			} elseif ( $party === 'gad' ) {
				$exclude_from_total = $this->admin_general_settings->get_option( 'gad_exclude_from_total' );
			} elseif ( $party === 'pint' ) {
				$exclude_from_total = $this->admin_general_settings->get_option( 'pint_exclude_from_total' );
			} else {
				return false;
			}

			if ( is_array( $exclude_from_total ) && count( $exclude_from_total ) > 0 && in_array( 'is_disable_shipping', $exclude_from_total, true ) ) {
				return true;
			}

			return false;

		}

		public function is_disable_taxes( $party = 'fb' ) {
			if ( $party === 'fb' ) {
				$exclude_from_total = $this->admin_general_settings->get_option( 'exclude_from_total' );
			} elseif ( $party === 'ga' ) {
				$exclude_from_total = $this->admin_general_settings->get_option( 'ga_exclude_from_total' );
			} elseif ( $party === 'gad' ) {
				$exclude_from_total = $this->admin_general_settings->get_option( 'gad_exclude_from_total' );
			} elseif ( $party === 'pint' ) {
				$exclude_from_total = $this->admin_general_settings->get_option( 'pint_exclude_from_total' );
			} else {
				return false;
			}

			if ( is_array( $exclude_from_total ) && count( $exclude_from_total ) > 0 && in_array( 'is_disable_taxes', $exclude_from_total, true ) ) {
				return true;
			}

			return false;

		}


		/**
		 * @param WC_Order $order
		 *
		 * @return array
		 */
		public function purchase_custom_aud_params( $order ) {

			$params = array();


			$params['town']    = $order->get_billing_city();
			$params['state']   = $order->get_billing_state();
			$params['country'] = $order->get_billing_country();


			$params['payment'] = $order->get_payment_method_title();


			// shipping method
			$shipping_methods = $order->get_items( 'shipping' );
			if ( $shipping_methods ) {

				$labels = array();
				foreach ( $shipping_methods as $shipping ) {
					$labels[] = $shipping['name'] ? $shipping['name'] : null;
				}

				$params['shipping'] = implode( ', ', $labels );

			}


			$coupons = $order->get_items( 'coupon' );
			if ( $coupons ) {

				$labels = array();
				foreach ( $coupons as $coupon ) {
					if ( $coupon instanceof WC_Order_Item ) {
						$labels[] = $coupon->get_code();
					} else {
						$labels[] = $coupon['name'] ? $coupon['name'] : null;

					}
				}
				$params['coupon_used'] = 'yes';
				$params['coupon_name'] = implode( ', ', $labels );

			} else {

				$params['coupon_used'] = 'no';

			}

			return $params;

		}


		/**
		 * @param string $taxonomy Taxonomy name
		 * @param int $post_id (optional) Post ID. Current will be used of not set
		 *
		 * @return string|array List of object terms
		 */
		public

		function get_object_terms(
			$taxonomy, $post_id = null, $implode = true
		) {

			$post_id = isset( $post_id ) ? $post_id : get_the_ID();
			$terms   = get_the_terms( $post_id, $taxonomy );

			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				return $implode ? '' : array();
			}

			$results = array();

			foreach (
				$terms as $term
			) {
				$results[] = html_entity_decode( $term->name );
			}

			if ( $implode ) {
				return implode( ', ', $results );
			} else {
				return $results;
			}
		}

		public function get_localstorage_hash( $key = 'fb' ) {
			$data = $this->data;
			if ( is_array( $data ) && count( $data ) > 0 ) {
				return md5( wp_json_encode( array(
					'key'       => WFFN_Core()->data->get_transient_key(),
					'data'      => $data,
					'keyunique' => $key
				) ) );
			}

			return 0;
		}

		public function tracking_log_js() {
			wp_add_inline_script( 'jquery-core', $this->maybe_clear_local_storage_for_tracking_log() );
		}

		/**
		 * We track in localstorage if we pushed ecommerce event for certain data or not
		 * Unfortunately we cannot remove the storage on thank you as user still can press the back button and events will fire again
		 * So the next most logical way to remove the storage is during the next updated checkout action.
		 */
		public function maybe_clear_local_storage_for_tracking_log() {
			$js = '';
			if ( is_checkout() ) {
				$js = "if (window.jQuery) {
						(function ($) {
							if (!String.prototype.startsWith) {
								String.prototype.startsWith = function (searchString, position) {
									position = position || 0;
	
									return this.indexOf(searchString, position) === position;
								};
							}
							window.addEventListener('DOMContentLoaded', (event) => {
								$(document.body).on('updated_checkout', function () {
									if (localStorage.length > 0) {								
										var	len = localStorage.length;
										var	wffnRemoveLS = [];
										for (var i = 0; i < len; ++i) {
											var	storage_key = localStorage.key(i);
											if (storage_key.startsWith('wffnH_') === true) {
												wffnRemoveLS.push(storage_key);
											}
										}
										for (var eachLS in wffnRemoveLS) {
											localStorage.removeItem(wffnRemoveLS[eachLS]);
										}
									}
								});
							});
						})(jQuery);
					}";
			}

			return $js;
		}

		/**
		 * * @hooked over `wp_head`
		 *
		 * That will be further used by general rendering
		 *
		 * @param WC_Order $order
		 *
		 * @return false
		 */
		public function maybe_save_order_data_general( $order ) {
			if ( empty( $this->general_data ) ) {
				$items          = $order->get_items( 'line_item' );
				$content_ids    = [];
				$content_name   = [];
				$category_names = [];
				$num_qty        = 0;
				$products       = [];
				$billing_email  = BWF_WC_Compatibility::get_order_data( $order, 'billing_email' );
				$order_id       = BWF_WC_Compatibility::get_order_id( $order );
				foreach ( $items as $item ) {
					$pid     = $item->get_product_id();
					$product = wc_get_product( $pid );
					if ( $product instanceof WC_product ) {

						$category       = $product->get_category_ids();
						$content_name[] = $product->get_title();
						$variation_id   = $item->get_variation_id();
						$get_content_id = 0;
						if ( empty( $variation_id ) || ( ! empty( $variation_id ) && true === $this->do_treat_variable_as_simple() ) ) {
							$get_content_id = $content_ids[] = $this->get_woo_product_content_id( $item->get_product_id() );
						} elseif ( false === $this->do_treat_variable_as_simple() ) {
							$get_content_id = $content_ids[] = $this->get_woo_product_content_id( $item->get_variation_id() );
						}
						$category_name = '';

						if ( is_array( $category ) && count( $category ) > 0 ) {
							$category_id = $category[0];
							if ( is_numeric( $category_id ) && $category_id > 0 ) {
								$cat_term = get_term_by( 'id', $category_id, 'product_cat' );
								if ( $cat_term ) {
									$category_name    = $cat_term->name;
									$category_names[] = $category_name;
								}
							}
						}
						$num_qty    += $item->get_quantity();
						$products[] = array_map( 'html_entity_decode', array(
							'name'       => $product->get_title(),
							'category'   => ( $category_name ),
							'id'         => $get_content_id,
							'pid'        => $pid,
							'sku'        => empty( $product->get_sku() ) ? $pid : $product->get_sku(),
							'quantity'   => $item->get_quantity(),
							'item_price' => $order->get_line_subtotal( $item ),
						) );
					}
				}

				$advanced = array();

				if ( ! empty( $billing_email ) ) {
					$advanced['em'] = $billing_email;
				}

				$billing_phone = BWF_WC_Compatibility::get_order_data( $order, 'billing_phone' );
				if ( ! empty( $billing_phone ) ) {
					$advanced['ph'] = $billing_phone;
				}

				$shipping_first_name = BWF_WC_Compatibility::get_order_data( $order, 'shipping_first_name' );
				if ( ! empty( $shipping_first_name ) ) {
					$advanced['fn'] = $shipping_first_name;
				}

				$shipping_last_name = BWF_WC_Compatibility::get_order_data( $order, 'shipping_last_name' );
				if ( ! empty( $shipping_last_name ) ) {
					$advanced['ln'] = $shipping_last_name;
				}

				$shipping_city = BWF_WC_Compatibility::get_order_data( $order, 'shipping_city' );
				if ( ! empty( $shipping_city ) ) {
					$advanced['ct'] = $shipping_city;
				}

				$shipping_state = BWF_WC_Compatibility::get_order_data( $order, 'shipping_state' );
				if ( ! empty( $shipping_state ) ) {
					$advanced['st'] = $shipping_state;
				}

				$shipping_postcode = BWF_WC_Compatibility::get_order_data( $order, 'shipping_postcode' );
				if ( ! empty( $shipping_postcode ) ) {
					$advanced['zp'] = $shipping_postcode;
				}

				$billing_country = BWF_WC_Compatibility::get_order_data( $order, 'billing_country' );
				if ( ! empty( $billing_country ) ) {
					$advanced['country'] = $billing_country;
				}

				$this->general_data = array(
					'products'         => $products,
					'total'            => $this->get_total_order_value( $order, 'order' ),
					'currency'         => BWF_WC_Compatibility::get_order_currency( $order ),
					'advanced'         => $advanced,
					'content_ids'      => $content_ids,
					'content_name'     => $content_name,
					'category_name'    => array_map( 'html_entity_decode', $category_names ),
					'num_qty'          => $num_qty,
					'additional'       => $this->purchase_custom_aud_params( $order ),
					'transaction_id'   => $order_id,
					'order_id'         => $order_id,
					'email'            => $billing_email,
					'shipping'         => BWF_WC_Compatibility::get_order_shipping_total( $order ),
					'affiliation'      => esc_attr( get_bloginfo( 'name' ) ),
					'ecomm_prodid'     => array_map( array( $this, 'gad_product_id' ), wp_list_pluck( $products, 'id' ) ),
					'ecomm_pagetype'   => 'purchase',
					'ecomm_totalvalue' => array_sum( wp_list_pluck( $products, 'item_price' ) )
				);
			}
			WFFN_Core()->logger->log( "General data tracking successfully saved for order id: $order_id" );
		}

		public function maybe_print_tracking_code( $step_id ) {

			if ( $step_id > 0 ) {
				return $step_id;
			}

			if ( WFFN_Core()->thank_you_pages->is_wfty_page() ) {
				return WFFN_Core()->thank_you_pages->thankyoupage_id;
			}

			if ( is_order_received_page() ) {
				global $wp;
				$order_id = $wp->query_vars['order-received'];

				if ( $order_id > 0 ) {
					$wfacp_id = BWF_WC_Compatibility::get_order_meta(wc_get_order($order_id), '_wfacp_post_id');

					if ( $wfacp_id > 0 ) {
						return $wfacp_id;
					}
				}
			}

			return $step_id;
		}


		/**
		 * Check all possible UTMs value saved in cookies
		 * @return array
		 */
		public function get_utms() {
			$wffnUtm_terms = [ "utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content" ];
			$utms          = [];
			foreach ( $wffnUtm_terms as $term ) {
				if ( isset( $_COOKIE[ 'wffn_fb_pixel_' . $term ] ) && ! empty( $_COOKIE[ 'wffn_fb_pixel_' . $term ] ) ) {
					$utms[ $term ] = wc_clean( $_COOKIE[ 'wffn_fb_pixel_' . $term ] ); //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
				}

			}

			return $utms;
		}

		/**
		 * Get traffic source saved in cookie for the conversion API
		 * @return array|false|string
		 */
		public function get_traffic_source() {
			$referrer = wc_get_raw_referer();

			$direct = empty( $referrer ) ? false : true;
			if ( $direct ) {
				$internal = false;
			} else {
				if ( false !== strpos( $referrer, site_url() ) ) {
					$internal = true;
				} else {
					$internal = false;
				}
			}

			if ( ! ( $direct || $internal ) ) {
				$external = true;
			} else {
				$external = false;
			}
			if ( isset( $_COOKIE['wffn_fb_pixel_traffic_source'] ) && ! empty( $_COOKIE['wffn_fb_pixel_traffic_source'] ) ) {
				$cookie = wc_clean( $_COOKIE['wffn_fb_pixel_traffic_source'] ); //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
			} else {
				$cookie = false;
			}

			if ( $external === false ) {
				return $cookie ? $cookie : 'direct';
			} else {
				return $cookie && $cookie === $referrer ? $cookie : $referrer;
			}


		}


		/**
		 * Get User data for the specific event
		 *
		 * @param string $type
		 *
		 * @return array
		 */
		public function get_user_data( $type ) {
			$user_data = [];
			if ( $type === 'Purchase' ) {
				$get_data_from_session = $this->data;
				if ( isset( $get_data_from_session['fb'] ) && isset( $get_data_from_session['fb']['advanced'] ) ) {
					$user_data                  = array();
					$user_data ['email']        = isset( $get_data_from_session['fb']['advanced']['em'] ) ? $get_data_from_session['fb']['advanced']['em'] : '';
					$user_data ['phone']        = isset( $get_data_from_session['fb']['advanced']['ph'] ) ? $get_data_from_session['fb']['advanced']['ph'] : '';
					$user_data ['last_name']    = isset( $get_data_from_session['fb']['advanced']['ln'] ) ? $get_data_from_session['fb']['advanced']['ln'] : '';
					$user_data ['first_name']   = isset( $get_data_from_session['fb']['advanced']['fn'] ) ? $get_data_from_session['fb']['advanced']['fn'] : '';
					$user_data ['city']         = isset( $get_data_from_session['fb']['advanced']['ct'] ) ? strtolower( $get_data_from_session['fb']['advanced']['ct'] ) : '';
					$user_data ['state']        = isset( $get_data_from_session['fb']['advanced']['st'] ) ? strtolower( $get_data_from_session['fb']['advanced']['st'] ) : '';
					$user_data ['country_code'] = isset( $get_data_from_session['fb']['advanced']['country'] ) ? strtolower( $get_data_from_session['fb']['advanced']['country'] ) : '';
					$user_data ['zip_code']     = isset( $get_data_from_session['fb']['advanced']['zp'] ) ? $get_data_from_session['fb']['advanced']['zp'] : '';
				}
			}


			return array_merge( $user_data, parent::get_user_data( $type ) );

		}

		/**
		 * Get generic event params for pass with the general event
		 * @return array
		 */
		public function get_generic_event_params_for_conv_api() {
			$get_offer              = WFFN_Core()->thank_you_pages->thankyoupage_id;
			$params                 = array();
			$params['post_type']    = WFFN_Core()->thank_you_pages->get_post_type_slug();
			$params['content_name'] = get_the_title( $get_offer );
			$params['post_id']      = $get_offer;

			return $params;
		}

		/**
		 * Get all purchase event params prepared using data saved in sessions
		 * @return array
		 */
		public function get_purchase_params() {
			$get_data_from_session = $this->data;
			$purchase_params       = array(
				'value'            => $get_data_from_session['fb']['total'],
				'currency'         => $get_data_from_session['fb']['currency'],
				'content_name'     => ! empty( $get_data_from_session['fb']['content_name'] ) ? join( ',', $get_data_from_session['fb']['content_name'] ) : __( 'FunnelKit', 'woofunnels-upstroke-one-click-upsells' ),
				'content_category' => ! empty( $get_data_from_session['fb']['category_name'] ) ? join( ',', $get_data_from_session['fb']['category_name'] ) : '',
				'content_ids'      => $get_data_from_session['fb']['content_ids'],
				'content_type'     => 'product',
				'contents'         => $this->get_contents_for_conv_api( $get_data_from_session['fb']['products'] ),
				'domain'           => site_url(),
				'plugin'           => 'FunnelKit Thankyou',
				'event_day'        => gmdate( "l" ),
				'event_month'      => gmdate( "F" ),
				'event_hour'       => $this->getHour(),
				'traffic_source'   => $this->get_traffic_source(),
			);
			$utms                  = $this->get_utms();
			if ( is_array( $utms ) ) {
				$purchase_params = array_merge( $purchase_params, $utms );
			}

			return $purchase_params;
		}

		/**
		 * Format content property to be pass with the conversion API
		 *
		 * @param array $products
		 *
		 * @return array|mixed
		 */
		public function get_contents_for_conv_api( $products ) {
			if ( is_array( $products ) && count( $products ) > 0 ) {
				foreach ( $products as &$prod ) {
					unset( $prod['name'] );
					unset( $prod['category'] );
				}
			}

			return $products;
		}

		public function is_enable_custom_event_pint() {

			if ( parent::is_enable_custom_event_pint() === true && WFFN_Core()->thank_you_pages->is_wfty_page() ) {
				return true;
			}

			return false;
		}

		public function is_enable_custom_event_ga() {

			if ( parent::is_enable_custom_event_ga() === true && WFFN_Core()->thank_you_pages->is_wfty_page() ) {
				return true;
			}

			return false;
		}

		public function is_enable_custom_event_gad() {

			if ( parent::is_enable_custom_event_gad() === true && WFFN_Core()->thank_you_pages->is_wfty_page() ) {
				return true;
			}

			return false;
		}

		public function is_enable_custom_event() {

			if ( parent::is_enable_custom_event() === true && WFFN_Core()->thank_you_pages->is_wfty_page() ) {
				return true;
			}

			return false;
		}

		public function get_custom_event_name() {
			return 'WooFunnels_Thankyou';
		}

		public function get_content_id( $product_obj, $mode = 'pixel' ) {
			$get_content_id = 0;
			if ( $product_obj->is_type( 'variation' ) && false === $this->do_treat_variable_as_simple( $mode ) ) {
				$get_content_id = $this->get_woo_product_content_id( $product_obj->get_id(), $mode );

			} else {
				if ( $product_obj->is_type( 'variation' ) ) {
					$get_content_id = $this->get_woo_product_content_id( $product_obj->get_parent_id(), $mode );

				} else {
					$get_content_id = $this->get_woo_product_content_id( $product_obj->get_id(), $mode );

				}
			}

			return $get_content_id;
		}

		public function get_custom_event_params() {
			$funnel = WFFN_Core()->data->get_session_funnel();
			if ( wffn_is_valid_funnel( $funnel ) ) {
				$params = [];
				if ( is_singular() ) {
					global $post;
					$params['page_title'] = $post->post_title;
					$params['post_id']    = $post->ID;

				}
				$params['funnel_id']    = $funnel->get_id();
				$params['funnel_title'] = $funnel->get_title();

				return wp_json_encode( $params );

			}

			return parent::get_custom_event_params();
		}

		public function get_current_order() {
			$get_order = WFFN_Core()->thank_you_pages->data->get_order();
			if ( empty( $get_order ) && is_order_received_page() ) {
				global $wp;

				if ( isset( $wp->query_vars['order-received'] ) ) {
					$order_id  = absint( $wp->query_vars['order-received'] );
					$get_order = wc_get_order( $order_id );
				}
			}

			return $get_order;

		}


	}
}