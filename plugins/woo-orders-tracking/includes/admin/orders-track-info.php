<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_ORDERS_TRACKING_ADMIN_ORDERS_TRACK_INFO {
	protected static $settings;
	protected $carriers;
	protected $tracking_service_action_buttons;

	public function __construct() {
		self::$settings = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
		VILLATHEME_ADMIN_SHOW_MESSAGE::get_instance();
		$this->carriers = array();
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ), 99 );
		add_action( 'admin_head-edit.php', array( $this, 'addCustomImportButton' ) );
		add_filter( 'manage_edit-shop_order_columns', array( $this, 'add_new_order_admin_list_column' ) );
		add_filter( 'manage_woocommerce_page_wc-orders_columns', array( $this, 'add_new_order_admin_list_column' ) );
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'manage_shop_order_posts_custom_column' ), 10, 2 );
		add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( $this, 'manage_shop_order_posts_custom_column' ), 10, 2 );
		add_action( 'wp_ajax_vi_wot_refresh_track_info', array( $this, 'refresh_track_info' ) );
		add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ), 10 );
		add_action( 'woocommerce_order_list_table_restrict_manage_orders', array( $this, 'restrict_manage_posts' ) );
		add_action( 'woocommerce_orders_table_query_clauses', array( $this, 'add_items_query' ) );
		add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );
		/*Woo Alidropship*/
		add_filter( 'vi_woo_alidropship_order_item_tracking_data', array( $this, 'vi_woo_alidropship_order_item_tracking_data' ), 10, 3 );
	}

	public function admin_enqueue_script() {
		global $pagenow, $post_type;
		if ( ($pagenow === 'edit.php' && $post_type === 'shop_order') || (!empty($_GET['page']) && wc_clean(wp_unslash($_GET['page'])) === 'wc-orders') ) {
			wp_enqueue_style( 'semantic-ui-popup', VI_WOO_ORDERS_TRACKING_CSS . 'popup.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			wp_enqueue_style( 'vi-wot-admin-order-manager-icon', VI_WOO_ORDERS_TRACKING_CSS . 'woo-orders-tracking-icons.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			wp_enqueue_style( 'vi-wot-admin-order-manager-css', VI_WOO_ORDERS_TRACKING_CSS . 'admin-order-manager.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			$css = '.woo-orders-tracking-tracking-number-container-delivered a{color:' . self::$settings->get_params( 'timeline_track_info_status_background_delivered' ) . '}';
			$css .= '.woo-orders-tracking-tracking-number-container-pickup a{color:' . self::$settings->get_params( 'timeline_track_info_status_background_pickup' ) . '}';
			$css .= '.woo-orders-tracking-tracking-number-container-transit a{color:' . self::$settings->get_params( 'timeline_track_info_status_background_transit' ) . '}';
			$css .= '.woo-orders-tracking-tracking-number-container-pending a{color:' . self::$settings->get_params( 'timeline_track_info_status_background_pending' ) . '}';
			$css .= '.woo-orders-tracking-tracking-number-container-alert a{color:' . self::$settings->get_params( 'timeline_track_info_status_background_alert' ) . '}';
			wp_add_inline_style( 'vi-wot-admin-order-manager-css', $css );
			wp_enqueue_script( 'vi-wot-admin-order-manager-js', VI_WOO_ORDERS_TRACKING_JS . 'admin-order-manager.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
			wp_localize_script(
				'vi-wot-admin-order-manager-js',
				'vi_wot_admin_order_manager',
				array(
					'ajax_url'      => admin_url( 'admin-ajax.php' ),
					'paypal_image'  => VI_WOO_ORDERS_TRACKING_PAYPAL_IMAGE,
					'loading_image' => VI_WOO_ORDERS_TRACKING_LOADING_IMAGE,
					'message_copy'  => esc_html__( 'Tracking number is copied to clipboard', 'woo-orders-tracking' ),
				)
			);
		}
	}

	public function vi_woo_alidropship_order_item_tracking_data( $current_tracking_data, $item_id, $order_id ) {
		if ( ! empty( $current_tracking_data['carrier_slug'] ) ) {
			$carrier = $this->get_shipping_carrier_by_slug( $current_tracking_data['carrier_slug'] );
			if ( is_array( $carrier ) && count( $carrier ) ) {
				$current_tracking_data['carrier_name'] = $carrier['name'];
				$order                                 = wc_get_order( $order_id );
				$postal_code                           = '';
				if ( $order ) {
					$postal_code = $order->get_shipping_postcode();
				}
				$current_tracking_data['carrier_url'] = self::$settings->get_url_tracking( $carrier['url'], $current_tracking_data['tracking_number'], $current_tracking_data['carrier_slug'], $postal_code );
			}
		}

		return $current_tracking_data;
	}

	public static function set( $name, $set_name = false ) {
		return VI_WOO_ORDERS_TRACKING_DATA::set( $name, $set_name );
	}

	public function add_nonce_field() {
		wp_nonce_field( 'vi_wot_item_action_nonce', '_vi_wot_item_nonce' );
	}

	public function addCustomImportButton() {
		global $current_screen;
		if ( 'shop_order' != $current_screen->post_type ) {
			return;
		}
		add_action( 'admin_footer', array( $this, 'add_nonce_field' ) );
		?>
        <script type="text/javascript">
            'use strict';
            jQuery(document).ready(function ($) {
                jQuery(".wrap .page-title-action").eq(0).after("<a class='page-title-action' target='_blank' href='<?php echo esc_url( admin_url( 'admin.php?page=woo-orders-tracking-import-csv' ) ); ?>'><?php esc_html_e( 'Import tracking number', 'woo-orders-tracking' ) ?></a>"
                    + "<a class='page-title-action' target='_blank' href='<?php echo esc_url( admin_url( 'admin.php?page=woo-orders-tracking-export' ) ); ?>'><?php esc_html_e( 'Export tracking number ', 'woo-orders-tracking' ) ?></a>");
            });
        </script>
		<?php
	}

	public function add_new_order_admin_list_column( $columns ) {
		$bulk_refresh = '';
		if ( self::$settings->get_params( 'service_carrier_enable' ) && self::$settings->get_params( 'service_carrier_api_key' ) && self::$settings->get_params( 'service_carrier_type' ) !== 'cainiao' ) {
			$bulk_refresh = '<span class="woo_orders_tracking_icons-refresh ' . esc_attr( self::set( array(
					'tracking-service-refresh-bulk'
				) ) ) . '" title="' . esc_html__( 'Bulk refresh tracking', 'woo-orders-tracking' ) . '"></span>';
		}
		$columns['vi_wot_tracking_code'] = '<span class="' . esc_attr( self::set( array(
				'tracking-service-refresh-bulk-container'
			) ) ) . '">' . esc_html__( 'Tracking Number', 'woo-orders-tracking' ) . $bulk_refresh . '</span>';

		return $columns;
	}

	public function tracking_service_action_buttons_html( $tracking_link, $current_tracking_data, $tracking_status ) {
		if ( $this->tracking_service_action_buttons === null ) {
			$this->tracking_service_action_buttons = '';
			$service_carrier_enable                = self::$settings->get_params( 'service_carrier_enable' );
			$service_carrier_api_key               = self::$settings->get_params( 'service_carrier_api_key' );
			$service_carrier_type                  = self::$settings->get_params( 'service_carrier_type' );
			ob_start();
			?>
            <div class="<?php echo esc_attr( self::set( 'tracking-service-action-button-container' ) ) ?>">
                    <span class="woo_orders_tracking_icons-duplicate <?php echo esc_attr( self::set( array(
	                    'tracking-service-action-button',
	                    'tracking-service-copy'
                    ) ) ) ?>" title="<?php echo esc_attr__( 'Copy tracking number', 'woo-orders-tracking' ) ?>">
                    </span>
                <a href="{tracking_link}" target="_blank">
                        <span class="woo_orders_tracking_icons-redirect <?php echo esc_attr( self::set( array(
	                        'tracking-service-action-button',
	                        'tracking-service-track'
                        ) ) ) ?>"
                              title="<?php echo esc_attr__( 'Open tracking link', 'woo-orders-tracking' ) ?>">
                        </span>
                </a>
				<?php
				if ( $service_carrier_enable && $service_carrier_api_key && $service_carrier_type !== 'cainiao' ) {
					?>
                    <span class="woo_orders_tracking_icons-refresh <?php echo esc_attr( self::set( array(
						'tracking-service-action-button',
						'tracking-service-refresh'
					) ) ) ?>" title="{button_refresh_title}">
                        </span>
					<?php
				}
				?>
            </div>
			<?php
			$this->tracking_service_action_buttons = ob_get_clean();
		}
		$button_refresh_title = esc_html__( 'Update latest data', 'woo-orders-tracking' );
		if ( ! empty( $current_tracking_data['last_update'] ) ) {
			$button_refresh_title = sprintf( esc_html__( 'Last update: %s. Click to refresh.', 'woo-orders-tracking' ), date_i18n( 'Y-m-d H:i:s', $current_tracking_data['last_update'] ) );
		}

		return str_replace( array( '{button_refresh_title}', '{tracking_link}' ), array(
			$button_refresh_title,
			esc_url( $tracking_link )
		), $this->tracking_service_action_buttons );
	}

	public function get_shipping_carrier_by_slug( $slug ) {
		if ( ! isset( $this->carriers[ $slug ] ) ) {
			$this->carriers[ $slug ] = self::$settings->get_shipping_carrier_by_slug( $slug );
		}

		return $this->carriers[ $slug ];
	}

	/**
	 * @param $column
	 * @param $order_id
	 *
	 * @throws Exception
	 */
	public function manage_shop_order_posts_custom_column( $column, $order_id ) {
		if ( $column === 'vi_wot_tracking_code' ) {
			$order = wc_get_order( $order_id );
			if ( $order ) {
                $order_id = $order->get_id();
				$line_items = $order->get_items();
				if ( count( $line_items ) ) {
					$tracking_list          = array();
					$transID                = $order->get_transaction_id();
					$paypal_method          = $order->get_payment_method();
					$paypal_added_trackings = $order->get_meta( 'vi_wot_paypal_added_tracking_numbers', true );
					if ( ! $paypal_added_trackings ) {
						$paypal_added_trackings = array();
					}
					?>
                    <div class="<?php echo esc_attr( self::set( 'tracking-number-column-container' ) ) ?>">
						<?php
						foreach ( $line_items as $item_id => $line_item ) {
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
							$this->print_tracking_row( $current_tracking_data, $item_id, $order_id, $order, $transID, $paypal_method, $paypal_added_trackings, $tracking_list );
						}
						?>
                    </div>
					<?php
				}
			}
		}
	}

	protected function print_tracking_row( $current_tracking_data, $item_id, $order_id, $order, $transID, $paypal_method, $paypal_added_trackings, &$tracking_list ) {
		$tracking_number = apply_filters( 'vi_woo_orders_tracking_current_tracking_number', $current_tracking_data['tracking_number'], $item_id, $order_id );
		$carrier_url     = apply_filters( 'vi_woo_orders_tracking_current_tracking_url', $current_tracking_data['carrier_url'], $item_id, $order_id );
		$carrier_name    = apply_filters( 'vi_woo_orders_tracking_current_carrier_name', $current_tracking_data['carrier_name'], $item_id, $order_id );
		$carrier_slug    = apply_filters( 'vi_woo_orders_tracking_current_carrier_slug', $current_tracking_data['carrier_slug'], $item_id, $order_id );
		$tracking_status = isset( $current_tracking_data['status'] ) ? VI_WOO_ORDERS_TRACKING_DATA::convert_status( $current_tracking_data['status'] ) : '';
		if ( $tracking_number && ! in_array( $tracking_number, $tracking_list ) ) {
			$tracking_list[] = $tracking_number;
			$carrier         = $this->get_shipping_carrier_by_slug( $current_tracking_data['carrier_slug'] );
			if ( is_array( $carrier ) && count( $carrier ) ) {
				$carrier_url  = $carrier['url'];
				$carrier_name = $carrier['name'];
			}
			$tracking_url_show = apply_filters( 'vi_woo_orders_tracking_current_tracking_url_show', self::$settings->get_url_tracking( $carrier_url, $tracking_number, $carrier_slug, $order->get_shipping_postcode(), false, true, $order_id ), $item_id, $order_id );
			$container_class   = array( 'tracking-number-container' );
			if ( $tracking_status ) {
				$container_class[] = 'tracking-number-container-' . $tracking_status;
			}
			?>
            <div class="<?php echo esc_attr( self::set( $container_class ) ) ?>"
                 data-tracking_number="<?php echo esc_attr( $tracking_number ) ?>"
                 data-carrier_slug="<?php echo esc_attr( $carrier_slug ) ?>"
                 data-order_id="<?php echo esc_attr( $order_id ) ?>" <?php if ( $tracking_status ) {
				echo 'data-tooltip="' . esc_attr( isset( $current_tracking_data['status'] ) ? self::$settings->get_status_text_by_service_carrier( $current_tracking_data['status'] ) : '' ) . '"';
			} ?>>
                <a class="<?php echo esc_attr( self::set( 'tracking-number' ) ) ?>"
                   href="<?php echo esc_url( $tracking_url_show ) ?>"
                   title="<?php echo esc_attr__( "Tracking carrier {$carrier_name}", 'woo-orders-tracking' ) ?>"
                   target="_blank"><?php echo esc_html( $tracking_number ) ?></a>
				<?php
				echo wp_kses_post( $this->tracking_service_action_buttons_html( $tracking_url_show, $current_tracking_data, $tracking_status ) );
				if ( $transID && in_array( $paypal_method, VI_WOO_ORDERS_TRACKING_ADMIN_PAYPAL::get_supported_paypal_gateways() ) ) {
					$paypal_class = array( 'item-tracking-button-add-to-paypal-container' );
					if ( ! in_array( $tracking_number, $paypal_added_trackings ) ) {
						$paypal_class[] = 'paypal-active';
						$title          = esc_attr__( 'Add this tracking number to PayPal', 'woo-orders-tracking' );
					} else {
						$paypal_class[] = 'paypal-inactive';
						$title          = esc_attr__( 'This tracking number was added to PayPal', 'woo-orders-tracking' );
					}
					?>
                    <span class="<?php echo esc_attr( self::set( $paypal_class ) ) ?>"
                          data-item_id="<?php echo esc_attr( $item_id ) ?>"
                          data-order_id="<?php echo esc_attr( $order_id ) ?>">
                                        <img class="<?php echo esc_attr( self::set( 'item-tracking-button-add-to-paypal' ) ) ?>"
                                             title="<?php echo esc_attr( $title ) ?>"
                                             src="<?php echo esc_url( VI_WOO_ORDERS_TRACKING_PAYPAL_IMAGE ) ?>">
                                    </span>
					<?php
				}
				?>
            </div>
			<?php
		}
	}

	/**
	 * @param $tracking_number
	 * @param $carrier_slug
	 * @param $status
	 * @param string $change_order_status
	 *
	 * @throws Exception
	 */
	public static function update_order_items_tracking_status( $tracking_number, $carrier_slug, $status, $change_order_status = '' ) {
		$results = VI_WOO_ORDERS_TRACKING_DATA::search_order_item_by_tracking_number( $tracking_number, '', '', $carrier_slug, false );
		$now     = time();
		if ( count( $results ) ) {
			$order_ids      = array_unique( array_column( $results, 'order_id' ) );
			$order_item_ids = array_unique( array_column( $results, 'order_item_id' ) );
			foreach ( $results as $result ) {
				$item_tracking_data = vi_wot_json_decode( $result['meta_value'] );
				if ( $result['meta_key'] === '_vi_wot_order_item_tracking_data' ) {
					$current_tracking_data                = array_pop( $item_tracking_data );
					$current_tracking_data['status']      = $status;
					$current_tracking_data['last_update'] = $now;
					$item_tracking_data[]                 = $current_tracking_data;
					wc_update_order_item_meta( $result['order_item_id'], '_vi_wot_order_item_tracking_data', vi_wot_json_encode( $item_tracking_data ) );
				}
			}
			$convert_status = VI_WOO_ORDERS_TRACKING_DATA::convert_status( $status );
			self::update_order_status( $convert_status, $order_ids, $order_item_ids, $change_order_status );
			$log = '';
			do_action_ref_array( 'woo_orders_tracking_handle_shipment_status', array(
				$tracking_number,
				$status,
				$order_ids,
				$order_item_ids,
				&$log,
				self::$settings->get_params( 'service_carrier_type' )
			) );
		}
	}

	/**
	 * @param $status
	 * @param $order_ids
	 * @param $order_item_ids
	 * @param $change_order_status
	 * @param string $shipment_status
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function update_order_status( $status, $order_ids, $order_item_ids, $change_order_status, $shipment_status = 'delivered' ) {
		$changed_orders = array();
		if ( $status === $shipment_status && $change_order_status ) {
			foreach ( $order_ids as $order_id ) {
				$order = wc_get_order( $order_id );
				if ( $order ) {
					$line_items = $order->get_items();
					if ( count( $line_items ) ) {
						$shipment_status_count = 0;
						foreach ( $line_items as $line_item_k => $line_item_v ) {
							if ( ! in_array( $line_item_k, $order_item_ids ) ) {
								$item_tracking_data = wc_get_order_item_meta( $line_item_k, '_vi_wot_order_item_tracking_data', true );
								if ( $item_tracking_data ) {
									$item_tracking_data    = vi_wot_json_decode( $item_tracking_data );
									$current_tracking_data = array_pop( $item_tracking_data );
									$tracking_status       = isset( $current_tracking_data['status'] ) ? VI_WOO_ORDERS_TRACKING_DATA::convert_status( $current_tracking_data['status'] ) : '';
									if ( $tracking_status === $shipment_status ) {
										$shipment_status_count ++;
									}
								}
							} else {
								$shipment_status_count ++;
							}
						}
						if ( apply_filters( "vi_woo_orders_tracking_is_order_{$shipment_status}", $shipment_status_count === count( $line_items ), $order ) ) {
							$update_status = substr( $change_order_status, 3 );
							if ( $update_status !== $order->get_status() ) {
								$changed_orders[] = $order_id;
								$order->update_status( $update_status );
							}
						}
					}
				}
			}
		}

		return $changed_orders;
	}

	/** For Aftership and Easypost
	 * @throws Exception
	 */
	public function refresh_track_info() {
		$response        = array(
			'status'                   => 'success',
			'message'                  => esc_html__( 'Update tracking data successfully.', 'woo-orders-tracking' ),
			'message_content'          => '',
			'tracking_change'          => 0,
			'tracking_status'          => '',
			'tracking_container_class' => '',
			'button_title'             => sprintf( esc_html__( 'Last update: %s. Click to refresh.', 'woo-orders-tracking' ), date_i18n( 'Y-m-d H:i:s', time() ) ),
		);
		$tracking_number = isset( $_POST['tracking_number'] ) ? sanitize_text_field( $_POST['tracking_number'] ) : '';
		$carrier_slug    = isset( $_POST['carrier_slug'] ) ? sanitize_text_field( $_POST['carrier_slug'] ) : '';
		$order_id        = isset( $_POST['order_id'] ) ? sanitize_text_field( stripslashes( $_POST['order_id'] ) ) : '';
		$order           = wc_get_order( $order_id );
		if ( $order && $tracking_number && $carrier_slug && self::$settings->get_params( 'service_carrier_enable' ) ) {
			$response['message_content'] = '<div>' . sprintf( esc_html__( 'Tracking number: %s', 'woo-orders-tracking' ), $tracking_number ) . '</div>';
			$carrier                     = $this->get_shipping_carrier_by_slug( $carrier_slug );
			if ( is_array( $carrier ) && count( $carrier ) ) {
				$status                      = '';
				$convert_status              = '';
				$carrier_name                = $carrier['name'];
				$tracking_more_slug          = empty( $carrier['tracking_more_slug'] ) ? VI_WOO_ORDERS_TRACKING_TRACKINGMORE::get_carrier_slug_by_name( $carrier_name ) : $carrier['tracking_more_slug'];
				$response['message_content'] .= '<div>' . sprintf( esc_html__( 'Carrier: %s', 'woo-orders-tracking' ), $carrier_name ) . '</div>';
				$service_carrier_type        = self::$settings->get_params( 'service_carrier_type' );
				switch ( $service_carrier_type ) {
					case 'trackingmore':
						$tracking_from_db        = VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::get_row_by_tracking_number( $tracking_number, $carrier_slug, $order_id );
						$service_carrier_api_key = self::$settings->get_params( 'service_carrier_api_key' );
						$trackingMore            = new VI_WOO_ORDERS_TRACKING_TRACKINGMORE( $service_carrier_api_key );
						$description             = '';
						$track_info              = '';
						if ( ! count( $tracking_from_db ) ) {
							$track_data = $trackingMore->create_tracking( $tracking_number, $tracking_more_slug, $order_id );
							if ( $track_data['status'] === 'success' ) {
								$status = $track_data['data']['status'];
								VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::insert( $order_id, $tracking_number, $status, $carrier_slug, $carrier_name, VI_WOO_ORDERS_TRACKING_FRONTEND_FRONTEND::get_shipping_country_by_order_id( $order_id ), $track_info, '' );
							} else {
								if ( $track_data['code'] === 4016 ) {
									/*Tracking exists*/
									$track_data  = $trackingMore->get_tracking( $tracking_number, $tracking_more_slug );
									$modified_at = '';
									if ( $track_data['status'] === 'success' ) {
										if ( count( $track_data['data'] ) ) {
											$track_info  = vi_wot_json_encode( $track_data['data'] );
											$last_event  = array_shift( $track_data['data'] );
											$status      = $last_event['status'];
											$description = $last_event['description'];
											$modified_at = false;
										}
									} else {
										$response['status']  = 'error';
										$response['message'] = $track_data['data'];
									}
									VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::insert( $order_id, $tracking_number, $status, $carrier_slug, $carrier_name, VI_WOO_ORDERS_TRACKING_FRONTEND_FRONTEND::get_shipping_country_by_order_id( $order_id ), $track_info, $description, $modified_at );
								} else {
									$response['status']  = 'error';
									$response['message'] = $track_data['data'];
								}
							}
						} else {
							$need_update_tracking_table = true;
							$convert_status             = VI_WOO_ORDERS_TRACKING_DATA::convert_status( $tracking_from_db['status'] );
							if ( $convert_status !== 'delivered' ) {
								$track_data = $trackingMore->get_tracking( $tracking_number, $tracking_more_slug );
								if ( $track_data['status'] === 'success' ) {
									if ( count( $track_data['data'] ) ) {
										$need_update_tracking_table = false;
										$track_info                 = vi_wot_json_encode( $track_data['data'] );
										$last_event                 = array_shift( $track_data['data'] );
										$status                     = $last_event['status'];
										VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::update( $tracking_from_db['id'], $order_id, $status, $carrier_slug, $carrier_name, VI_WOO_ORDERS_TRACKING_FRONTEND_FRONTEND::get_shipping_country_by_order_id( $order_id ), $track_info, $description );
										if ( $last_event['status'] !== $tracking_from_db['status'] || $track_info !== $tracking_from_db['track_info'] ) {
											$response['tracking_change'] = 1;
										}
									}
								} else {
									if ( $track_data['code'] === 4017 || $track_data['code'] === 4031 ) {
										/*Tracking NOT exists*/
										$track_data = $trackingMore->create_tracking( $tracking_number, $tracking_more_slug, $order_id );
										if ( $track_data['status'] === 'success' ) {
											$status = $track_data['data']['status'];
											VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::insert( $order_id, $tracking_number, $status, $carrier_slug, $carrier_name, VI_WOO_ORDERS_TRACKING_FRONTEND_FRONTEND::get_shipping_country_by_order_id( $order_id ), $track_info, '' );
										}
									} else {
										$response['status']  = 'error';
										$response['message'] = $track_data['data'];
									}
								}
							} else {
								$status = $tracking_from_db['status'];
							}

							if ( $need_update_tracking_table ) {
								if ( $order_id != $tracking_from_db['order_id'] ) {
									VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::update( $tracking_from_db['id'], $order_id, $status, $carrier_slug, $carrier_name, VI_WOO_ORDERS_TRACKING_FRONTEND_FRONTEND::get_shipping_country_by_order_id( $order_id ), $track_info, $description );
								} else {
									VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::update( $tracking_from_db['id'], '', false, false, false, false, false, false, '' );
								}
							}
						}
						break;
					default:
				}
				self::update_order_items_tracking_status( $tracking_number, $carrier_slug, $status );
				if ( $status ) {
					$convert_status                       = VI_WOO_ORDERS_TRACKING_DATA::convert_status( $status );
					$response['message_content']          .= '<div>' . self::$settings->get_status_text_by_service_carrier( $status ) . '</div>';
					$response['tracking_container_class'] = self::set( array(
						'tracking-number-container',
						'tracking-number-container-' . $convert_status
					) );
				}
				$response['tracking_status'] = $convert_status;
			} else {
				$response['status']  = 'error';
				$response['message'] = esc_html__( 'Carrier not found', 'woo-orders-tracking' );
			}
		} else {
			$response['status']  = 'error';
			$response['message'] = esc_html__( 'Not available', 'woo-orders-tracking' );
		}
		wp_send_json( $response );
	}

	public function restrict_manage_posts() {
		global $typenow;
		if ( in_array( $typenow, wc_get_order_types( 'view-orders' ), true ) || (wc_clean(wp_unslash($_GET['page'] ??'')) === 'wc-orders') ) {
			?>
            <input type="text" name="woo_orders_tracking_search_tracking"
                   placeholder="<?php echo esc_attr__( 'Search tracking number', 'woo-orders-tracking' ) ?>"
                   autocomplete="off"
                   value="<?php echo isset( $_GET['woo_orders_tracking_search_tracking'] ) ? esc_attr( htmlentities( sanitize_text_field( $_GET['woo_orders_tracking_search_tracking'] ) ) ) : '' ?>">
			<?php
		}
	}

	public function posts_join( $join, $wp_query ) {
		global $wpdb;
		$join .= " JOIN {$wpdb->prefix}woocommerce_order_items as wotg_woocommerce_order_items ON $wpdb->posts.ID=wotg_woocommerce_order_items.order_id";
		$join .= " JOIN {$wpdb->prefix}woocommerce_order_itemmeta as wotg_woocommerce_order_itemmeta ON wotg_woocommerce_order_items.order_item_id=wotg_woocommerce_order_itemmeta.order_item_id";

		return $join;
	}

	public function posts_where( $where, $wp_query ) {
		global $wpdb;
		$post_type     = isset( $wp_query->query_vars['post_type'] ) ? $wp_query->query_vars['post_type'] : '';
		$tracking_code = isset( $_GET['woo_orders_tracking_search_tracking'] ) ? $_GET['woo_orders_tracking_search_tracking'] : '';
		if ( isset( $_GET['filter_action'] ) && $tracking_code && $post_type === 'shop_order' ) {
			$where .= $wpdb->prepare( " AND wotg_woocommerce_order_itemmeta.meta_key='_vi_wot_order_item_tracking_data' AND wotg_woocommerce_order_itemmeta.meta_value like %s", '%' . $wpdb->esc_like( $tracking_code ) . '%' );
			add_filter( 'posts_join', array( $this, 'posts_join' ), 10, 2 );
			add_filter( 'posts_distinct', array( $this, 'posts_distinct' ), 10, 2 );
		}

		return $where;
	}

	public function posts_distinct( $join, $wp_query ) {
		return 'DISTINCT';
	}
	public function add_items_query( $args ) {
		if ( isset($_GET['page'] ) &&  sanitize_text_field(wp_unslash($_GET['page'])) === 'wc-orders' && !empty($_GET['woo_orders_tracking_search_tracking']) ) {
			$tracking_code = sanitize_text_field(wp_unslash($_GET['woo_orders_tracking_search_tracking']));
			global $wpdb;
			$args['join']  .= " LEFT JOIN {$wpdb->prefix}wc_orders_meta ON {$wpdb->prefix}wc_orders.id={$wpdb->prefix}wc_orders_meta.order_id";
			$args['join']  .= " LEFT JOIN {$wpdb->prefix}woocommerce_order_items ON {$wpdb->prefix}wc_orders.id={$wpdb->prefix}woocommerce_order_items.order_id";
			$args['join']  .= " LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta ON {$wpdb->prefix}woocommerce_order_items.order_item_id={$wpdb->prefix}woocommerce_order_itemmeta.order_item_id";
			$args['where'] .= $wpdb->prepare( " AND (({$wpdb->prefix}woocommerce_order_itemmeta.meta_key='_vi_wot_order_item_tracking_data' AND {$wpdb->prefix}woocommerce_order_itemmeta.meta_value like %s) or ({$wpdb->prefix}wc_orders_meta.meta_key='_wot_tracking_number' AND {$wpdb->prefix}wc_orders_meta.meta_value=%s))",
				'%' . $wpdb->esc_like( $tracking_code ) . '%', $tracking_code );
		}

		return $args;
	}
}