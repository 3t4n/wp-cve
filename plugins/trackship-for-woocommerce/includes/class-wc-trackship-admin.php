<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

class WC_Trackship_Admin {

	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		global $wpdb;
	}
	
	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Get the class instance
	 *
	 * @return WC_Trackship_Admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	* init from parent mail class
	*/
	public function init() {
		
		add_action( 'admin_menu', array( $this, 'register_woocommerce_menu' ), 110 );

		add_action( 'admin_footer', array( $this, 'footer_function'), 1 );
		add_action( 'wp_ajax_add_trackship_mapping_row', array( $this, 'add_trackship_mapping_row' ) );
		add_action( 'wp_ajax_remove_tracking_event', array( $this, 'remove_tracking_event' ) );
		add_action( 'wp_ajax_remove_trackship_logs', array( $this, 'remove_trackship_logs' ) );
		add_action( 'wp_ajax_verify_database_table', array( $this, 'verify_database_table' ) );
		add_action( 'wp_ajax_ts_bulk_migration', array( $this, 'ts_bulk_migration' ) );
		add_action( 'wp_ajax_trackship_mapping_form_update', array( $this, 'trackship_custom_mapping_form_update') );
		add_action( 'wp_ajax_trackship_integration_form_update', array( $this, 'trackship_integration_form_update_callback') );

		add_filter( 'convert_provider_name_to_slug', array( $this, 'detect_custom_mapping_provider') );	
		add_action( 'wp_ajax_ts_late_shipments_email_form_update', array( $this, 'ts_late_shipments_email_form_update_callback' ) );
		add_action( 'wp_ajax_dashboard_page_count_query', array( $this, 'dashboard_page_count_query' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'register_metabox') );
		
		add_action( 'wp_ajax_metabox_get_shipment_status', array( $this, 'metabox_get_shipment_status_cb' ) );
		
		add_action( 'wp_ajax_get_admin_tracking_widget', array( $this, 'get_admin_tracking_widget_cb' ) );
		
		add_action( 'woocommerce_auth_page_footer', array( $this, 'remove_connect_store_border' ), 5 );
		
		add_filter('woocommerce_order_is_download_permitted', array( $this, 'add_onhold_status_to_download_permission' ), 10, 2);

		$newstatus = get_option( 'wc_ast_status_delivered', 1);
		if ( true == $newstatus ) {
			//register order status 
			add_action( 'init', array( $this, 'register_order_status') );
			//add status after completed
			add_filter( 'wc_order_statuses', array( $this, 'add_delivered_to_order_statuses') );
			//Custom Statuses in admin reports
			add_filter( 'woocommerce_reports_order_statuses', array( $this, 'include_custom_order_status_to_reports'), 20, 1 );
			// for automate woo to check order is paid
			add_filter( 'woocommerce_order_is_paid_statuses', array( $this, 'delivered_woocommerce_order_is_paid_statuses' ) );
			//add bulk action
			add_filter( 'bulk_actions-edit-shop_order', array( $this, 'add_bulk_actions'), 50, 1 );
			//add reorder button
			add_filter( 'woocommerce_valid_order_statuses_for_order_again', array( $this, 'add_reorder_button_delivered'), 50, 1 );
			//add button in preview
			add_filter( 'woocommerce_admin_order_preview_actions', array( $this, 'additional_admin_order_preview_buttons_actions'), 5, 2 );
			//add actions in column
			add_filter( 'woocommerce_admin_order_actions', array( $this, 'add_delivered_order_status_actions_button'), 100, 2 );
		}

	}
	
	public function add_onhold_status_to_download_permission( $data, $order ) {
		if ( $order->has_status( 'delivered' ) ) {
			return true;
		}
		return $data;
	}
	
	public function remove_connect_store_border() {
		?>
			<style>body.wc-auth.wp-core-ui {border: 0;}</style>
		<?php
	}
	
	public function get_admin_tracking_widget_cb() {
		if ( !current_user_can( 'manage_product' ) && !current_user_can( 'manage_woocommerce' ) ) {
			exit( 'You are not allowed' );
		}
		$page = isset( $_POST['page'] ) ? sanitize_text_field( $_POST['page'] ) : '' ;
		$tracking_id = isset( $_POST['tracking_id'] ) && 'wcpv-vendor-order' == $page ? sanitize_text_field( $_POST['tracking_id'] ) : null ;
		$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : '' ;
		$order = wc_get_order( $order_id );
		check_ajax_referer( 'tswc-' . $order_id, 'security' );
		
		if ( current_user_can( 'manage_product' ) || current_user_can( 'manage_woocommerce' ) ) {
			$trackship_apikey = is_trackship_connected();
			if ( $trackship_apikey ) {
				trackship_for_woocommerce()->front->admin_tracking_page_widget( $order_id, $tracking_id );
			} else {
				echo '<strong>';
				esc_html_e( 'Please connect your store with trackship.com.', 'trackship-for-woocommerce' );
				echo '</strong>';
			}
		} else {
			esc_html_e( 'Please refresh the page and try again.', 'trackship-for-woocommerce' );
		}
		die();
	}
	
	public function metabox_get_shipment_status_cb() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			exit( 'You are not allowed' );
		}
		$o_id = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : '' ;
		$security = isset( $_POST['security'] ) ? sanitize_text_field( $_POST['security'] ) : '' ;
		$order_id = wc_clean( $o_id );
		
		if ( !wp_verify_nonce( $security, 'tswc-' . $order_id ) ) {
			$data = array(
				'msg' => 'Security check fail, please refresh and try again.'
			);

			wp_send_json_error( $data );
		}

		$bool = trackship_for_woocommerce()->actions->schedule_trackship_trigger( $order_id );
		if ( $bool ) {
			$data = array(
				'msg' => 'Tracking information has been sent to TrackShip.'
			);
			wp_send_json_success( $data );
		} else {
			$data = array(
				'msg' => 'Tracking information was not sent to TrackShip.'
			);

			wp_send_json_error( $data );
		}
		die();
	}

	public function register_metabox() {
		if ( ! trackship_for_woocommerce()->is_ast_active() ) {
			if ( class_exists( 'Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) ) {
				$screen = wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
			} else {
				$screen = 'shop_order';
			}
			add_meta_box( 'trackship', 'TrackShip', array( $this, 'trackship_metabox_cb'), $screen, 'side', 'high' );
		}
	}
	
	public function trackship_metabox_cb( $post_or_order_object ) {
		$order = ( $post_or_order_object instanceof WP_Post ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;
		$order_id = $order->get_id();
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
		?>
		<div id="trackship-tracking-items">
			<?php foreach ( $tracking_items as $key => $tracking_item ) { ?>
				<?php
				$tracking_provider = ! empty( $tracking_item['formatted_tracking_provider'] ) ? $tracking_item['formatted_tracking_provider'] : ( !empty( $tracking_item['tracking_provider'] ) ? $tracking_item['tracking_provider'] : $tracking_item['custom_tracking_provider'] ) ;
				$tracking_number = $tracking_item['tracking_number'];
				$tracking_link = $tracking_item['tracking_page_link'] ? $tracking_item['tracking_page_link'] : $tracking_item['formatted_tracking_link'];
				?>
				<div class="ts-tracking-item">
					<div class="tracking-content">
						<div>
							<strong><?php esc_html_e( $tracking_provider ); ?></strong> - 
							<?php if ( $tracking_link ) { ?>
								<?php echo sprintf( '<a href="%s" target="_blank" title="' . esc_attr( __( 'Track Shipment', 'trackship-for-woocommerce' ) ) . '">' . esc_html( $tracking_number ) . '</a>', esc_url( $tracking_link ) ); ?>
							<?php } else { ?>
								<span><?php esc_html_e( $tracking_number ); ?></span>
							<?php } ?>
						</div>
						<?php 
						do_action(	'ast_after_tracking_number', $order_id, $tracking_item['tracking_id'] );
						do_action(	'ast_shipment_tracking_end', $order_id, $tracking_item ); 
						?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
		//echo '<pre>';print_r($tracking_items);echo '</pre>';
		wp_enqueue_style( 'trackshipcss' );
		wp_enqueue_script( 'trackship_script' );
		
		//front_style for tracking widget
		wp_register_style( 'front_style', trackship_for_woocommerce()->plugin_dir_url() . 'assets/css/front.css', array(), trackship_for_woocommerce()->version );
		wp_enqueue_style( 'front_style' );
	}
	
	public function build_html( $template, $data = null ) {
		global $wpdb;
		$t = new \stdclass();
		$t->data = $data;
		ob_start();
		include(dirname(__FILE__) . '/admin-html/' . $template . '.phtml');
		$s = ob_get_contents();
		ob_end_clean();
		return $s;
	}
	
	/*
	* Admin Menu add function
	* WC sub menu
	*/
	public function register_woocommerce_menu() {

		add_menu_page( __( 'TrackShip', 'trackship-for-woocommerce' ), __( 'TrackShip', 'trackship-for-woocommerce' ), apply_filters( 'trackship_menu_capabilities', 'manage_woocommerce' ), 'trackship-dashboard', array( $this, 'dashboard_page_callback' ), trackship_for_woocommerce()->plugin_dir_url() . 'assets/images/ts-20.svg', '55.4' );
		
		if ( is_trackship_connected() ) {
			add_submenu_page( 'trackship-dashboard', 'Dashboard', __( 'Dashboard', 'trackship-for-woocommerce' ), apply_filters( 'trackship_dashboard_menu_capabilities', 'manage_woocommerce' ), 'trackship-dashboard', array( $this, 'dashboard_page_callback' ), 1 );
			
			add_submenu_page( 'trackship-dashboard', 'Shipments', __( 'Shipments', 'trackship-for-woocommerce' ), apply_filters( 'trackship_shipments_menu_capabilities', 'manage_woocommerce' ), 'trackship-shipments', array( $this, 'shipments_page_callback' ) );
			
			add_submenu_page( 'trackship-dashboard', 'Logs', __( 'Logs', 'trackship-for-woocommerce' ), apply_filters( 'trackship_logs_menu_capabilities', 'manage_woocommerce' ), 'trackship-logs', array( $this, 'logs_page_callback' ) );
			
			add_submenu_page( 'trackship-dashboard', 'Analytics', __( 'Analytics', 'trackship-for-woocommerce' ), 'manage_woocommerce', 'trackship-analytics', array( $this, 'analytics_page_callback' ) );

			add_submenu_page( 'trackship-dashboard', 'Settings', __( 'Settings', 'trackship-for-woocommerce' ), apply_filters( 'trackship_settings_menu_capabilities', 'manage_woocommerce' ), 'trackship-for-woocommerce', array( $this, 'settings_page_callback' ) );
		}
	}
	
	/*
	* callback for Settings
	*/
	public function settings_page_callback() {
		?>
		<div class="zorem-layout">
			<?php include 'views/header2.php'; ?>
			<?php include 'views/content.php'; ?>
		</div>
		<?php
	}
	
	/*
	* callback for Shipment
	*/
	public function shipments_page_callback() {
		?>
		<div class="zorem-layout">
			<?php include 'views/header2.php'; ?>
			<?php $this->get_trackship_notice_msg(); ?>
			<div class="trackship_admin_content">
				<section id="content_trackship_dashboard" style="display:block" class="inner_tab_section">
					<div class="tab_inner_container">
						<?php include 'views/shipments.php'; ?>
					</div>
				</section>
			</div>
		</div>
		<?php
	}

	/*
	* callback for Shipment
	*/
	public function logs_page_callback() {
		?>
		<div class="zorem-layout">
			<?php include 'views/header2.php'; ?>
			<?php $this->get_trackship_notice_msg(); ?>
			<div class="trackship_admin_content">
				<section id="content_trackship_logs" style="display:block" class="inner_tab_section">
					<div class="tab_inner_container">
						<?php include 'views/logs.php'; ?>
					</div>
				</section>
			</div>
		</div>
		<?php
	}

	/*
	* callback for Dashboard
	*/
	public function dashboard_page_callback() {
		$database_upg = isset( $_GET['trackship-database-upgrade'] ) ? sanitize_text_field( $_GET['trackship-database-upgrade'] ) : '';
		if ( 'true' == $database_upg ) {
			trackship_for_woocommerce()->ts_install->update_database_check();
		}
		?>
		<div class="zorem-layout">
			<?php
			include 'views/header2.php';
			if ( is_trackship_connected() ) {
				$this->get_trackship_notice_msg();
			}
			?>
			<div class="trackship_admin_content">
				<section id="content_trackship_fullfill_dashboard" class="">
					<div class="tab_inner_container">
						<?php if ( is_trackship_connected() ) { ?>
							<?php include 'views/dashboard.php'; ?>
						<?php } else { ?>
							<div class="woocommerce trackship_admin_layout">
								<div class="trackship_admin_content" >
									<div class="trackship_nav_div">
										<?php include 'views/trackship-integration.php'; ?>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</section>
			</div>
		</div>
		<?php
	}
	
	/*
	* callback for Analytics
	*/
	public function analytics_page_callback () {
		wp_redirect( admin_url('admin.php?page=wc-admin&path=/analytics/trackship-analytics'), 301 );
		exit;
	}

	/*
	* Query for Dashboard
	*/
	public function dashboard_page_count_query() {
		
		check_ajax_referer( 'wc_ast_tools', 'security' );
		$start_date = isset( $_POST['selected_option'] ) ? wc_clean( $_POST['selected_option'] ) : '';
		$end_date = gmdate( 'Y-m-d' );
		
		global $wpdb;
		$woo_trackship_shipment = $wpdb->prefix . 'trackship_shipment';

		$result = $wpdb->get_row( $wpdb->prepare("
			SELECT
				SUM( IF( `shipping_date` BETWEEN %s AND %s, 1, 0 ) ) as total_shipment,
				SUM( IF( (`shipment_status` NOT LIKE 'delivered' OR `pending_status` IS NOT NULL) AND `shipping_date` BETWEEN %s AND %s, 1, 0 ) ) as active_shipment,
				SUM( IF( (`shipment_status` LIKE 'delivered') AND `shipping_date` BETWEEN %s AND %s, 1, 0 ) ) as delivered_shipment,
				SUM( IF((`shipment_status` NOT IN ( 'delivered', 'in_transit', 'out_for_delivery', 'pre_transit', 'exception', 'return_to_sender', 'available_for_pickup' ) OR `pending_status` IS NOT NULL) AND `shipping_date` BETWEEN %s AND %s, 1, 0) ) as tracking_issues
				FROM {$wpdb->prefix}trackship_shipment AS row1",
				$start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date
		), ARRAY_A);

		// print_r($wpdb->last_query);

		wp_send_json($result);
	}

	public function get_settings_html( $arrays ) {
		?>
		<ul class="settings_ul">
			<?php foreach ( (array) $arrays as $id => $array ) { ?>
				<?php
				if ( $array['show'] ) {
					if ( 'multiple_select' == $array['type'] ) {
						trackship_for_woocommerce()->html->get_multiple_select_html( $id, $array );
					} elseif ( 'tgl_checkbox' == $array['type'] ) {
						trackship_for_woocommerce()->html->get_tgl_checkbox_html( $id, $array );
					} elseif ( 'number' == $array['type'] ) {
						trackship_for_woocommerce()->html->get_number_html( $id, $array );
					} elseif ( 'dropdown_tpage' == $array['type'] ) {
						trackship_for_woocommerce()->html->get_dropdown_tpage_html( $id, $array );
					} elseif ( 'text' == $array['type'] ) {
						trackship_for_woocommerce()->html->get_text_html( $id, $array );
					} elseif ( 'time' == $array['type'] ) {
						trackship_for_woocommerce()->html->get_time_html( $id, $array );
					} elseif ( 'button' == $array['type'] ) {
						trackship_for_woocommerce()->html->get_button_html( $id, $array );
					}
				}
			}
			?>
		</ul>
		<?php
	}
	
	/*
	* get settings tab array data
	* return array
	*/
	public function get_trackship_general_data() {
		$order_statuses = wc_get_order_statuses();
		
		$status_array = array();
		foreach ( $order_statuses as $key => $val ) {

			if ( 'wc-cancelled' == $key ) {
				continue;
			}
			if ( 'wc-failed' == $key ) {
				continue;
			}
			if ( 'wc-pending' == $key ) {
				continue;
			}

			$status_slug = ( 'wc-' === substr( $key, 0, 3 ) ) ? substr( $key, 3 ) : $key;
			$status_array[$status_slug] = $val;
		}
		
		$form_data = array(
			'trackship_trigger_order_statuses' => array(
				'type'		=> 'multiple_select',
				'title'		=> __( 'Order statuses to trigger TrackShip ', 'trackship-for-woocommerce' ),
				'tooltip'	=> __( 'Choose on which order emails to include the shipment tracking info', 'trackship-for-woocommerce' ),
				'options'	=> $status_array,
				'show'		=> true,
				'class'		=> '',
			),
			'wc_ts_shipment_status_filter' => array(
				'type'		=> 'tgl_checkbox',
				'title'		=> __( 'Enable a shipment status filter on orders admin', 'trackship-for-woocommerce' ),
				'show'		=> true,
				'class'		=> '',
			),
			'enable_email_widget' => array(
				'type'		=> 'tgl_checkbox',
				'title'		=> __( 'Enable unsubscribe (opt-out) from Shipment status notifications', 'trackship-for-woocommerce' ),
				'show'		=> true,
				'class'		=> '',
				'tooltip'	=> __( 'Allow users to opt-out of receiving Shipment status notifications on the Tracking page and Shipment status emails.', 'trackship-for-woocommerce' ),
			),
		);

		if ( ( is_plugin_active( 'wp-lister-for-amazon/wp-lister-amazon.php' ) || is_plugin_active( 'wp-lister-amazon/wp-lister-amazon.php' ) ) && !in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ) {
			$form_data[ 'enable_notification_for_amazon_order' ] = array(
				'type'		=> 'tgl_checkbox',
				'title'		=> __( 'Enable shipment status notification for order created by Amazon', 'trackship-for-woocommerce' ),
				'show'		=> true,
				'class'		=> '',
			);
		}
		return $form_data;
	}

	/*
	* get Admin notifications Late shipments tab array data
	* return array
	*/
	public function get_late_shipment_data() {
		$late_shipment = array(
			'late_shipments_days' => array(
				'type'	=> 'number',
				'title'	=> __( 'Number of days for late shipments', 'trackship-for-woocommerce' ),
				'show'	=> true,
				'class'	=> '',
			),
			'late_shipments_email_to' => array(
				'title'	=> __( 'Recipient(s)', 'trackship-for-woocommerce'),
				'type'	=> 'text',
				'show'	=> true,
				'class'	=> '',
			),
			'late_shipments_digest_time' => array(
				'title'	=> __( 'Send email at', 'trackship-for-woocommerce'),
				'type'	=> 'time',
				'show'	=> true,
				'class'	=> '',
			),
		);
		return $late_shipment;
	}

	/*
	* Get Admin notifications exception admin shipments tab array data
	* return array
	*/
	public function get_exception_shipment_data() {
		$exception_shipment = array(
			'exception_shipments_email_to' => array(
				'title'	=> __( 'Recipient(s)', 'trackship-for-woocommerce'),
				'type'	=> 'text',
				'show'	=> true,
				'class'	=> '',
			),
			'exception_shipments_digest_time' => array(
				'title'	=> __( 'Send email at', 'trackship-for-woocommerce'),
				'type'	=> 'time',
				'show'	=> true,
				'class'	=> '',
			),
		);
		return $exception_shipment;
	}

	/*
	* Get Admin notifications on_hold admin shipments tab array data
	* return array
	*/
	public function get_on_hold_shipment_data() {
		$on_hold_shipment = array(
			'on_hold_shipments_email_to' => array(
				'title'	=> __( 'Recipient(s)', 'trackship-for-woocommerce'),
				'type'	=> 'text',
				'show'	=> true,
				'class'	=> '',
			),
			'on_hold_shipments_digest_time' => array(
				'title'	=> __( 'Send email at', 'trackship-for-woocommerce'),
				'type'	=> 'time',
				'show'	=> true,
				'class'	=> '',
			),
		);
		return $on_hold_shipment;
	}

	/*
	* get Integrations tab array data
	* return array
	*/
	public function get_trackship_integrations_data() {
		$integrations = array(
			'klaviyo' => array(
				'title'	=> 'Klaviyo',
				'value'	=> get_trackship_settings( 'klaviyo', ''),
				'docs'	=> '',
				'image' => trackship_for_woocommerce()->plugin_dir_url() . 'assets/images/integrations/klaviyo.png',
			),
		);
		return $integrations;
	}

	public function get_trackship_notice_msg() {
		$completed_order_with_tracking = $this->completed_order_with_tracking();
		$completed_order_with_zero_balance = $this->completed_order_with_zero_balance();
		$completed_order_with_do_connection = $this->completed_order_with_do_connection();
		$total_orders = $completed_order_with_tracking + $completed_order_with_zero_balance + $completed_order_with_do_connection;
		$cookie = isset( $_COOKIE['Notice'] ) ? sanitize_text_field( $_COOKIE['Notice'] ) : '';
		if ( 'delete' != $cookie && $total_orders > 0 ) {
			?>
			<div class="trackship_notice_msg tools_tab_ts4wc">
				<div class="trackship-notice" style="border: 0;">
					<?php /* translators: %s: search for a total_orders */ ?>
					<p><?php printf( esc_html__( 'We detected %s Shipments from the last 30 days that were not sent to TrackShip, you can bulk send them to TrackShip', 'trackship-for-woocommerce' ), esc_html( $total_orders ) ); ?><span class="dashicons remove-icon dashicons-no-alt"></span></p>
					<button class="button-primary button-trackship bulk_shipment_status_button" <?php echo 0 == $total_orders ? 'disabled' : ''; ?>><?php esc_html_e( 'Get Shipment Status', 'trackship-for-woocommerce' ); ?></button>
				</div>
			</div>
			<?php
		}
	}

	/*
	* get completed order with tracking that not sent to TrackShip
	* return number
	*/
	public function completed_order_with_tracking() {
		// Get orders completed.
		$args = array(
			'status' => 'wc-completed',
			'limit'	 => 100,
			'date_created' => '>' . ( time() - 2592000 ),
		);
		$orders = wc_get_orders( $args );
		
		$completed_order_with_tracking = 0;
		
		foreach ( $orders as $order ) {
			$order_id = $order->get_id();
			$order = wc_get_order( $order_id );
			$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
			
			if ( $tracking_items ) {
				foreach ( $tracking_items as $key => $tracking_item ) {
					$row = trackship_for_woocommerce()->actions->get_shipment_row($order_id, $tracking_item['tracking_number']);
					if ( !$row ) {
						$completed_order_with_tracking++;
					}
				}
			}
		}
		return $completed_order_with_tracking;
	}
	
	/*
	* get completed order with Trackship Balance 0 status
	* return number
	*/
	public function completed_order_with_zero_balance() {
		
		// Get orders completed.
		$args = array(
			'status' => 'wc-completed',
			'limit'	 => 100,	
			'date_created' => '>' . ( time() - 2592000 ),
		);
		
		$orders = wc_get_orders( $args );
		
		$completed_order_with_zero_balance = 0;
		
		foreach ( $orders as $order ) {
			$order_id = $order->get_id();
			$order = wc_get_order( $order_id );
			$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
			
			if ( $tracking_items ) {
				foreach ( $tracking_items as $key => $tracking_item ) {
					$row = trackship_for_woocommerce()->actions->get_shipment_row($order_id, $tracking_item['tracking_number']);
					if ( isset( $row->pending_status ) && 'insufficient_balance' == $row->pending_status ) {
						$completed_order_with_zero_balance++;
					}
				}
			}
		}
		return $completed_order_with_zero_balance;
	}
	
	/*
	* get completed order with Trackship connection issue status
	* return number
	*/
	public function completed_order_with_do_connection() {
		
		// Get orders completed.
		$args = array(
			'status' => 'wc-completed',
			'limit'	 => 100,	
			'date_created' => '>' . ( time() - 2592000 ),
		);
		
		$orders = wc_get_orders( $args );
		
		$completed_order_with_do_connection = 0;
		
		foreach ( $orders as $order ) {
			$order_id = $order->get_id();
			$order = wc_get_order( $order_id );
			$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
			
			if ( $tracking_items ) {
				foreach ( $tracking_items as $key => $tracking_item ) {
					$row = trackship_for_woocommerce()->actions->get_shipment_row($order_id, $tracking_item['tracking_number']);
					if ( isset( $row->pending_status ) && in_array( $row->pending_status, array( 'connection_issue', 'unauthorized' ) ) ) {
						$completed_order_with_do_connection++;
					}
				}
			}
		}
		return $completed_order_with_do_connection;
	}
	
	/** 
	* Register new status : Delivered
	**/
	public function register_order_status() {
		register_post_status( 'wc-delivered', array(
			'label'						=> __( 'Delivered', 'trackship-for-woocommerce' ),
			'public'					=> true,
			'show_in_admin_status_list'	=> true,
			'show_in_admin_all_list'	=> true,
			'exclude_from_search'		=> false,
			/* translators: %s: search number of order */
			'label_count'				=> _n_noop( 'Delivered <span class="count">(%s)</span>', 'Delivered <span class="count">(%s)</span>', 'trackship-for-woocommerce' )
		) );
	}
	
	/*
	* add status after completed
	*/
	public function add_delivered_to_order_statuses( $order_statuses ) {
		$new_order_statuses = array();
		foreach ( $order_statuses as $key => $status ) {
			$new_order_statuses[ $key ] = $status;
			if ( 'wc-completed' === $key ) {
				$new_order_statuses['wc-delivered'] = __( 'Delivered', 'trackship-for-woocommerce' );
			}
		}
		
		return $new_order_statuses;
	}
	
	/*
	* Adding the custom order status to the default woocommerce order statuses
	*/
	public function include_custom_order_status_to_reports( $statuses ) {
		if ( $statuses ) {
			$statuses[] = 'delivered';
		}
		return $statuses;
	}
	
	/*
	* mark status as a paid.
	*/
	public function delivered_woocommerce_order_is_paid_statuses( $statuses ) { 
		$statuses[] = 'delivered';
		return $statuses; 
	}
	
	/*
	* add bulk action
	* Change order status to delivered
	*/
	public function add_bulk_actions( $bulk_actions ) {
		$lable = wc_get_order_status_name( 'delivered' );
		$bulk_actions['mark_delivered'] = __( 'Change status to ' . $lable . '', 'trackship-for-woocommerce' );	
		return $bulk_actions;
	}
	
	/*
	* add order again button for delivered order status	
	*/
	public function add_reorder_button_delivered( $statuses ) {
		$statuses[] = 'delivered';
		return $statuses;
	}

	/*
	* Add delivered action button in preview order list to change order status from completed to delivered
	*/
	public function additional_admin_order_preview_buttons_actions( $actions, $order ) {
		
		$wc_ast_status_delivered = get_option( 'wc_ast_status_delivered', 1 );
		if ( $wc_ast_status_delivered ) {
			// Below set your custom order statuses (key / label / allowed statuses) that needs a button
			$custom_statuses = array(
				'delivered' => array( // The key (slug without "wc-")
					'label'		=> __( 'Delivered', 'ast-pro' ), // Label name
					'allowed'	=> array( 'completed'), // Button displayed for this statuses (slugs without "wc-")
				),
			);
		
			// Loop through your custom orders Statuses
			foreach ( $custom_statuses as $status_slug => $values ) {
				if ( $order->has_status( $values['allowed'] ) ) {
					$actions[ 'status' ][ 'group' ] = __( 'Change status: ', 'woocommerce' );
					$actions[ 'status' ][ 'actions' ][ $status_slug ] = array(
						'url'	=> wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=' . $status_slug . '&order_id=' . $order->get_id() ), 'woocommerce-mark-order-status' ),
						'name'	=> $values['label'],
						'title'	=> __( 'Change order status to', 'ast-pro' ) . ' ' . strtolower( $values['label'] ),
						'action'=> $status_slug,
					);
				}
			}
		}
		return $actions;
	}
	
	/*
	* Add action button in order list to change order status from completed to delivered
	*/
	public function add_delivered_order_status_actions_button( $actions, $order ) {
		
		$wc_ast_status_delivered = get_option( 'wc_ast_status_delivered', 1 );
		
		if ( $wc_ast_status_delivered ) {
			if ( $order->has_status( array( 'completed' ) ) || $order->has_status( array( 'shipped' ) ) ) {
				
				// Get Order ID (compatibility all WC versions)
				$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
				
				// Set the action button
				$actions['delivered'] = array(
					'url'	=> wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=delivered&order_id=' . $order_id ), 'woocommerce-mark-order-status' ),
					'name'	=> __( 'Mark order as delivered', 'ast-pro' ),
					'icon'	=> '<i class="fa fa-truck">&nbsp;</i>',
					'action'=> 'delivered_icon', // keep "view" class for a clean button CSS
				);
			}
		}
		
		return $actions;
	}

	/*
	* change style of delivered order label
	*/	
	public function footer_function() {
		if ( !is_plugin_active( 'woocommerce-order-status-manager/woocommerce-order-status-manager.php' ) ) {
			$bg_color = get_option('wc_ast_status_label_color', '#09d3ac');
			$color = get_option('wc_ast_status_label_font_color', '#000');
			?>
			<style>
			.order-status.status-delivered,.status-label-li .order-label.wc-delivered{
				background: <?php echo esc_html( $bg_color ); ?>;
				color: <?php echo esc_html( $color ); ?>;
			}
			</style>
		<?php } ?>
		<style> #toplevel_page_trackship_customizer { display: none !important; } </style>
		<?php echo '<div id=admin_tracking_widget class=popupwrapper style="display:none;"><span class="admin_tracking_page_close popupclose"><span class="dashicons dashicons-no-alt"></span></span><div class=popuprow></div><div class=popupclose></div></div>'; ?>
		<div id="free_user_popup" class="popupwrapper" style="display:none;">
			<div class="free_user_popup popuprow" style="padding:20px">
				<h1 style="text-align: center;"><?php esc_html_e( 'Upgrade to TrackShip Pro', 'trackship-for-woocommerce' ); ?></h1>
				<div style="margin-top: 30px;display:flex;">
					<div style="position: relative; width: 100%;">
						<ul>
							<li><?php esc_html_e( 'Priority Support', 'trackship-for-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'SMS Notifications', 'trackship-for-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Remove TrackShip’s branding', 'trackship-for-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Shipments Dashboard', 'trackship-for-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Late Shipments Notifications', 'trackship-for-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Exception Shipments Notifications', 'trackship-for-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'On Hold Shipments Notifications', 'trackship-for-woocommerce' ); ?></li>
							<li><?php esc_html_e( 'Shipping & Delivery Analytics', 'trackship-for-woocommerce' ); ?></li>
							<p style="font-size: 16px;"><?php esc_html_e( 'Starting from $11 a month', 'trackship-for-woocommerce' ); ?></p>
						</ul>
						<div>
							<a href="https://my.trackship.com/?utm_source=wpadmin&utm_medium=TS4WC&utm_campaign=shipment"><button class="button-primary button-trackship btn_large" style="font-size: 17px; padding: 8px 30px; background-color: #09d3ac;border-color:#09d3ac;"><?php esc_html_e( 'UPGRADE TO PRO', 'trackship-for-woocommerce' ); ?><span style="line-height: 18px;" class="dashicons dashicons-arrow-right-alt2"></span></button></a>
						</div>
					</div>
					<div style="position: relative; width: 100%;">
						<img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/popup-free-user.png" style="">
					</div>
				</div>
			</div>
			<?php $page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : ''; ?>
			<?php if ( !in_array( $page, array( 'trackship-shipments' ) ) ) { ?>
				<div class="popupclose"></div>
			<?php } ?>
		</div>
		<div id="" class="popupwrapper sync_trackship_provider_popup" style="display:none;">
			<div class="popuprow trackship_provider">
				<div class="popup_header">
					<h3 class="popup_title"><?php esc_html_e( 'Sync TrackShip Providers', 'trackship-for-woocommerce'); ?></h3>
					<span class="dashicons dashicons-no-alt popup_close_icon"></span>
				</div>
				<div class="popup_body">
					<p class="sync_message"><?php esc_html_e( 'Syncing the TrackShip providers list add or updates the pre-set TrackShip providers and will not effect custom shipping providers.', 'trackship-for-woocommerce' ); ?></p>
					<ul class="synch_result">
						<li class="providers_updated"><?php esc_html_e( 'Providers list Updated', 'trackship-for-woocommerce' ); ?></li>
					</ul>
					<button class="sync_trackship_providers_btn button-primary button-trackship"><?php esc_html_e( 'Sync TrackShip Providers', 'trackship-for-woocommerce' ); ?></button>
					<div class="spinner"></div>
				</div>
				<input type="hidden" id="nonce_trackship_provider" value="<?php esc_html_e( wp_create_nonce( 'nonce_trackship_provider' ) ); ?>">
			</div>	
			<div class="popupclose"></div>
		</div>
		<div class="popupwrapper trackship_logs_details" style="display:none;">
			<div class="popuprow">
				<div class="popup_header">
					<h3 class="popup_title"><?php esc_html_e( 'Notifications detail', 'trackship-for-woocommerce'); ?></h3>
					<span class="dashicons dashicons-no-alt popup_close_icon"></span>
				</div>
				<div class="popup_body">
					<div class="order_id"><strong><?php esc_html_e( 'Order Number', 'trackship-for-woocommerce' ); ?></strong><span></span></div>
					<div class="shipment_status"><strong><?php esc_html_e( 'Shipment status', 'trackship-for-woocommerce' ); ?></strong><span></span></div>
					<div class="tracking_number"><strong><?php esc_html_e( 'Tracking Number', 'trackship-for-woocommerce' ); ?></strong><span></span></div>
					<div class="time"><strong><?php esc_html_e( 'Time', 'trackship-for-woocommerce' ); ?></strong><span></span></div>
					<div class="to"><strong><?php esc_html_e( 'To', 'trackship-for-woocommerce' ); ?></strong><span></span></div>
					<div class="type"><strong><?php esc_html_e( 'Type', 'trackship-for-woocommerce' ); ?></strong><span></span></div>
					<div class="status"><strong><?php esc_html_e( 'Status', 'trackship-for-woocommerce' ); ?></strong><span></span></div>
				</div>
			</div>
			<div class="popupclose"></div>
		</div>
	<?php
	}
	
	public function get_trackship_provider() {
		
		global $wpdb;
		$ts_shippment_providers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}trackship_shipping_provider" );
		return $ts_shippment_providers ;
		
	}
	
	/*
	* Return add maping table row
	*/
	public function add_trackship_mapping_row() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			exit( 'You are not allowed' );
		}
		$ts_shippment_providers = $this->get_trackship_provider();
		
		ob_start();
		?>
		<tr>
			<td><input type="text" class="map_shipping_provider_text" name="detected_provider[]"></td>
			<td>
				<select name="ts_provider[]" class="select2">
					<option value=""><?php esc_html_e( 'Select', 'woocommerce' ); ?></option>
					<?php foreach ( $ts_shippment_providers as $ts_provider ) { ?>
						<option value="<?php echo esc_html( $ts_provider->ts_slug ); ?>"><?php echo esc_html( $ts_provider->provider_name ); ?></option>
					<?php } ?>
				</select>
				<span class="dashicons dashicons-trash remove_custom_maping_row"></span>
			</td>
		</tr>
		
		<?php 
		$html = ob_get_clean();	
		wp_send_json( array( 'table_row' => $html) );
	}
	
	/*
	* Return add maping table row
	*/
	public function remove_tracking_event() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			exit( 'You are not allowed' );
		}
		check_ajax_referer( 'wc_ast_tools', 'security' );
		$days = isset( $_POST['days'] ) ? sanitize_text_field($_POST['days']) : false;
		$args = array(
			'post_type'	=> 'shop_order',
			'posts_per_page' => '1000',
			'meta_query' => array(
				'relation' => 'AND',
				'shipment_status' => array(
					'key' => 'shipment_status',
					'value' => 'delivered',
					'compare' => 'LIKE',
				),
				array(
					'key' => 'shipment_events_deleted',
					'compare' => 'NOT EXISTS'
				),
			),
			'date_query' => array(
				array(
					'before' => '-' . $days . ' days',
					'column' => 'post_date',
				),
			),
			'post_status' => array_keys( wc_get_order_statuses() )
		);
		$query = new WP_Query( $args );
		while ( $query->have_posts() ) {
			$query->the_post();
			$order_id = get_the_id();
			$order = wc_get_order( $order_id );
			$shipment_status = $order->get_meta( 'shipment_status', true );
			foreach ( $shipment_status as $key => $val ) {
				$shipment_status[$key]['tracking_events'] = array();
				$shipment_status[$key]['tracking_destination_events'] = array();
			}
			$order = wc_get_order( $order_id );

			$order->update_meta_data( 'shipment_status', $shipment_status );
			$order->update_meta_data( 'shipment_events_deleted', 1 );
			$order->save();
		}

		global $wpdb;
		
		$response = $wpdb->query($wpdb->prepare( "
			UPDATE {$wpdb->prefix}trackship_shipment_meta meta
			JOIN {$wpdb->prefix}trackship_shipment shipment ON shipment.id = meta.meta_id
			SET meta.tracking_events = NULL
			WHERE shipment_status LIKE ('delivered') AND shipment.shipping_date <= NOW() - INTERVAL %d DAY
		", $days ));
		wp_send_json(array('success' => true, 'response' => $response));
	}
	
	public function remove_trackship_logs() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			exit( 'You are not allowed' );
		}
		check_ajax_referer( 'wc_ast_tools', 'security' );
		global $wpdb;
		$row_query = $wpdb->get_results("
			DELETE
			FROM {$wpdb->prefix}zorem_email_sms_log
			WHERE ( `type` = 'Email' OR `sms_type` = 'shipment_status' ) AND `date` < NOW() - INTERVAL 30 DAY;
		");
		wp_send_json( array( 'success' => 'true' ) );
	}

	public function verify_database_table() {
		check_ajax_referer( 'wc_ast_tools', 'security' );

		$install = trackship_for_woocommerce()->ts_install;
		$install->create_shipment_table();
		$install->create_shipment_meta_table();
		$install->create_email_log_table();
		$install->check_column_exists();

		wp_send_json( array( 'success' => 'true' ) );
	}

	public function ts_bulk_migration() {
		check_ajax_referer( 'wc_ast_tools', 'security' );

		global $wpdb;
		$orderids = $wpdb->get_col(
			"SELECT t.order_id FROM {$wpdb->prefix}trackship_shipment t
			LEFT JOIN {$wpdb->prefix}trackship_shipment_meta m  
			ON t.id = m.meta_id
			WHERE (m.tracking_events IS NULL OR m.tracking_events = '')
				AND t.shipping_date >= DATE_SUB(NOW(), INTERVAL 60 DAY)
			GROUP BY t.order_id
			LIMIT 2000"
		);

		if ( $orderids ) {
			update_trackship_settings( 'ts_migration', true );
		}

		foreach ( ( array ) $orderids as $order_id ) {
			trackship_for_woocommerce()->actions->set_temp_pending( $order_id );
			as_schedule_single_action( time() + 1, 'trackship_tracking_apicall', array( $order_id ) );
		}
		as_schedule_single_action( time() + 3600*60, 'remove_ts_temp_key' );
		delete_trackship_settings( 'old_user' );
		wp_send_json( array( 'success' => 'true' ) );
	}

	public function trackship_integration_form_update_callback() {
		check_ajax_referer( 'ts_integrations', 'integrations_nonce' );
		$integrations = $this->get_trackship_integrations_data();
		foreach ( $integrations as $key => $value ) {
			$posted_val = isset( $_POST[ $key ] ) ? wc_clean( $_POST[ $key ] ) : '';
			update_trackship_settings( $key, $posted_val );
		}
		wp_send_json( array( 'success' => 'true' ) );
	}

	/*
	* Save Custom Mapping data
	*/
	public function trackship_custom_mapping_form_update() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			exit( 'You are not allowed' );
		}
		if ( ! empty( $_POST ) && check_admin_referer( 'trackship_mapping_form', 'trackship_mapping_form_nonce' ) ) {
			
			$map_provider_array = array();
			if ( !empty( $_POST['detected_provider'] ) ) {
				foreach ( wc_clean( $_POST['detected_provider'] ) as $key => $provider ) {
					if ( isset( $_POST[ 'ts_provider' ][ $key ] ) ) {
						$map_provider_array[$provider] = wc_clean( $_POST['ts_provider'][$key] );
					}
				}
			}
			update_option( 'trackship_map_provider', $map_provider_array );		
			wp_send_json( array( 'success' => 'true' ) );
		}
	}

	public function detect_custom_mapping_provider( $tracking_provider ) {
		$map_provider_array = get_option( 'trackship_map_provider', [] );

		if ( isset( $map_provider_array[ $tracking_provider ] ) ) {
			return $map_provider_array[ $tracking_provider ];
		}
		
		// $map_provider_array key replace space to '-' and lower case
		$map_provider_array = array_change_key_case( $map_provider_array, CASE_LOWER );
		$keys = str_replace( ' ', '-', array_keys( $map_provider_array ) );
		$map_provider_array = array_combine( $keys, array_values( $map_provider_array ) );
		
		$provider_slug = str_replace( ' ', '-', strtolower($tracking_provider) );
		if ( isset( $map_provider_array[ $provider_slug ] ) ) {
			return $map_provider_array[ $provider_slug ];
		}
		return $tracking_provider;
	}

	/*
	* number of days
	*/
	public function get_num_of_days( $first_date, $last_date ) {
		$date1 = strtotime($first_date);
		$date2 = strtotime($last_date);
		$diff = abs($date2 - $date1);
		return gmdate( 'd', $diff );
	}
	
	/*
	* late shipments form save
	*/
	public function ts_late_shipments_email_form_update_callback() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			exit( 'You are not allowed' );
		}
		if ( ! empty( $_POST ) && check_admin_referer( 'ts_late_shipments_email_form', 'ts_late_shipments_email_form_nonce' ) ) {
			
			$late_shipments_email_enable = isset( $_POST['late_shipments_email_enable'] ) ? sanitize_text_field( $_POST['late_shipments_email_enable'] ) : '';
			$exception_admin_email_enable = isset( $_POST['exception_admin_email_enable'] ) ? sanitize_text_field( $_POST['exception_admin_email_enable'] ) : '';
			$on_hold_admin_email_enable = isset( $_POST['on_hold_admin_email_enable'] ) ? sanitize_text_field( $_POST['on_hold_admin_email_enable'] ) : '';

			$data = $this->get_late_shipment_data();
			foreach ( $data as $key => $val ) {
				update_trackship_settings( $key, wc_clean( $_POST[ $key ] ) );
			}

			$data2 = $this->get_exception_shipment_data();
			foreach ( $data2 as $key2 => $val2 ) {
				update_trackship_settings( $key2, wc_clean( $_POST[ $key2 ] ) );
			}

			$data3 = $this->get_on_hold_shipment_data();
			foreach ( $data3 as $key3 => $val3 ) {
				update_trackship_settings( $key3, wc_clean( $_POST[ $key3 ] ) );
			}

			update_trackship_settings( 'late_shipments_email_enable', $late_shipments_email_enable );
			update_trackship_settings( 'exception_admin_email_enable', $exception_admin_email_enable );
			update_trackship_settings( 'on_hold_admin_email_enable', $on_hold_admin_email_enable );

			$Late_Shipments = new WC_TrackShip_Late_Shipments();
			$Late_Shipments->remove_cron();
			$Late_Shipments->setup_cron();
			$return1 = array(
				'message'	=> 'success',
			);

			$Exception_Shipments = new WC_TrackShip_Exception_Shipments();
			$Exception_Shipments->remove_cron();
			$Exception_Shipments->setup_cron();
			$return2 = array(
				'message'	=> 'success',
			);

			$On_Hold_Shipments = new WC_TrackShip_On_Hold_Shipments();
			$On_Hold_Shipments->remove_cron();
			$On_Hold_Shipments->setup_cron();
			$return3 = array(
				'message'	=> 'success',
			);
			wp_send_json_success( array($return1, $return2, $return3 ));
		}
	}
		
	/*
	* get settings tab array data
	* return array
	*/
	public function get_tracking_page_data() {
		$page_list = wp_list_pluck( get_pages(), 'post_title', 'ID' );
		
		$slug = '';
		
		$wc_ast_trackship_page_id = get_trackship_settings('wc_ast_trackship_page_id');
		$post = get_post($wc_ast_trackship_page_id); 
		if ( $post ) {
			$slug = $post->post_name;
		}
		
		if ( 'ts-shipment-tracking' != $slug ) {
			$page_desc = '';
		} else {
			$page_desc = '';
		}

		$form_data = array(
			'wc_ast_use_tracking_page' => array(
				'type'		=> 'tgl_checkbox',
				'title'		=> __( 'Enable Tracking Page', 'trackship-for-woocommerce' ),
				'show'		=> true,
				'class'		=> 'wc_ast_use_tracking_page',
			),
			'wc_ast_trackship_page_id' => array(
				'type'		=> 'dropdown_tpage',
				'title'		=> __( 'Select tracking page:', 'trackship-for-woocommerce' ),
				'options'	=> $page_list,
				'show'		=> true,
				'desc'		=> $page_desc,
				'class'		=> '',
			),
			'wc_ast_trackship_other_page' => array(
				'type'		=> 'text',
				'title'		=> __( 'Other', '' ),
				'show'		=> false,
				'class'		=> '',
			),
			'wc_ast_tracking_page_customize_btn' => array(
				'type'		=> 'button',
				'title'		=> '',
				'show'		=> true,
				'class'		=> '',
				'customize_link' => admin_url( 'admin.php?page=trackship_customizer' ),
			),	
		);
		return $form_data;
	}
	
	public function trackship_shipment_status_notifications_data() {
		$notifications_data = array(
			'in_transit' => array(
				'title'	=> __( 'In Transit', 'trackship-for-woocommerce' ),
				'slug' => 'in-transit',
				'option_name'	=> 'wcast_intransit_email_settings',
				'enable_status_name'	=> 'wcast_enable_intransit_email',
				'customizer_url'	=> admin_url( 'admin.php?page=trackship_customizer&type=shipment_email&status=in_transit' ),
			),
			'available_for_pickup' => array(
				'title'	=> __( 'Available For Pickup', 'trackship-for-woocommerce' ),
				'slug'	=> 'available-for-pickup',
				'option_name'	=> 'wcast_availableforpickup_email_settings',
				'enable_status_name' => 'wcast_enable_availableforpickup_email',
				'customizer_url' => admin_url( 'admin.php?page=trackship_customizer&type=shipment_email&status=available_for_pickup' ),	
			),
			'out_for_delivery' => array(
				'title'	=> __( 'Out For Delivery', 'trackship-for-woocommerce' ),
				'slug'	=> 'out-for-delivery',
				'option_name'	=> 'wcast_outfordelivery_email_settings',
				'enable_status_name' => 'wcast_enable_outfordelivery_email',
				'customizer_url' => admin_url( 'admin.php?page=trackship_customizer&type=shipment_email&status=out_for_delivery' ),	
			),
			'failure' => array(
				'title'	=> __( 'Failed Attempt', 'trackship-for-woocommerce' ),
				'slug'	=> 'failed-attempt',
				'option_name'	=> 'wcast_failure_email_settings',
				'enable_status_name' => 'wcast_enable_failure_email',
				'customizer_url' => admin_url( 'admin.php?page=trackship_customizer&type=shipment_email&status=failure' ),
			),
			'on_hold' => array(
				'title'	=> __( 'On Hold', 'trackship-for-woocommerce' ),
				'slug'	=> 'on-hold',
				'option_name'	=> 'wcast_onhold_email_settings',
				'enable_status_name' => 'wcast_enable_onhold_email',
				'customizer_url' => admin_url( 'admin.php?page=trackship_customizer&type=shipment_email&status=on_hold' ),
			),
			'exception' => array(
				'title'	=> __( 'Exception', 'trackship-for-woocommerce' ),
				'slug'	=> 'exception',
				'option_name'	=> 'wcast_exception_email_settings',
				'enable_status_name' => 'wcast_enable_exception_email',
				'customizer_url' => admin_url( 'admin.php?page=trackship_customizer&type=shipment_email&status=exception' ),
			),
			'return_to_sender' => array(
				'title'	=> __( 'Return To Sender', 'trackship-for-woocommerce' ),
				'slug'	=> 'return-to-sender',
				'option_name'	=> 'wcast_returntosender_email_settings',
				'enable_status_name' => 'wcast_enable_returntosender_email',
				'customizer_url' => admin_url( 'admin.php?page=trackship_customizer&type=shipment_email&status=return_to_sender' ),
			),
			'delivered' => array(
				'title'	=> __( 'Delivered', 'trackship-for-woocommerce' ),
				'title2'=> __( 'Send only when all shipments for the order are delivered', 'trackship-for-woocommerce' ),
				'slug'	=> 'delivered-status',
				'option_name'	=> 'wcast_delivered_status_email_settings',
				'enable_status_name' => 'wcast_enable_delivered_status_email',
				'customizer_url' => admin_url( 'admin.php?page=trackship_customizer&type=shipment_email&status=delivered' ),
			),
			'pickup_reminder' => array(
				'title'	=> __( 'Pickup reminder', 'trackship-for-woocommerce' ),
				'slug'	=> 'pickup-reminder',
				'option_name'	=> 'wcast_pickupreminder_email_settings',
				'enable_status_name' => 'wcast_enable_pickupreminder_email',
				'customizer_url' => admin_url( 'admin.php?page=trackship_customizer&type=shipment_email&status=pickup_reminder' ),
			),
		);
		return $notifications_data;
	}
	
	public function calculate_percent( $first, $second ) {
		if ( 0 == $second ) {
			return '';
		}
		$percent = $first * 100 / $second;
		return '(' . round( $percent, 2 ) . '%)';
	}

	/*
	* transaltion function for loco generater
	* this function is not called from any function
	*/
	public function translation_func() {
		__( 'Tracking Analytics', 'trackship-for-woocommerce');
		__( 'SMS Settings', 'trackship-for-woocommerce');
	}
}
