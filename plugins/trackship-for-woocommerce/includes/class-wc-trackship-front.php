<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_TrackShip_Front {

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		$this->init();	
	}
	
	/**
	 * Get the class instance
	 *
	 * @return WC_Advanced_Shipment_Tracking_Actions
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
		
		add_shortcode( 'wcast-track-order', array( $this, 'woo_track_order_function') );
		add_shortcode( 'trackship-track-order', array( $this, 'woo_track_order_function') );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_styles' ) );		
		add_action( 'wp_ajax_nopriv_get_tracking_info', array( $this, 'get_tracking_info_fun') );
		add_action( 'wp_ajax_get_tracking_info', array( $this, 'get_tracking_info_fun') );
		
		add_action( 'plugins_loaded', array( $this, 'on_plugin_loaded' ) );
		add_action( 'plugins_loaded', array( $this, 'on_plugin_loaded2' ), 12 );
		
		add_action( 'woocommerce_view_order', array( $this, 'show_tracking_page_widget' ), 5, 1 );

		add_filter( 'tracking_widget_product_array', array( $this, 'tracking_widget_product_array_callback' ), 10, 5 );

		//save optin optout butoon 
		add_action( 'wp_ajax_save_unsunscribe_email_notifications_data', array( $this, 'unsubscribe_emails_save_callback') );
		add_action( 'wp_ajax_nopriv_save_unsunscribe_email_notifications_data', array( $this, 'unsubscribe_emails_save_callback') );
		add_action( 'wp_ajax_resubscribe_emails_save', array( $this, 'resubscribe_emails_save_callback') );
		add_action( 'wp_ajax_nopriv_resubscribe_emails_save', array( $this, 'resubscribe_emails_save_callback') );
	}
	
	public function on_plugin_loaded() {
		
		if ( function_exists( 'wc_advanced_shipment_tracking' ) && !function_exists( 'ast_pro' ) ) {
			remove_action( 'woocommerce_view_order', array( wc_advanced_shipment_tracking()->actions, 'show_tracking_info_order' ) );
		}
		
		if ( function_exists( 'ast_pro' ) && isset( ast_pro()->ast_pro_actions ) ) {
			remove_action( 'woocommerce_view_order', array( ast_pro()->ast_pro_actions, 'show_tracking_info_order' ) );
		}
		
		if ( function_exists( 'wc_shipment_tracking' ) && !function_exists( 'ast_pro' ) ) {
			// View Order Page.
			remove_action( 'woocommerce_view_order', array( wc_shipment_tracking()->actions, 'display_tracking_info' ) );
			remove_action( 'woocommerce_email_before_order_table', array( wc_shipment_tracking()->actions, 'email_display' ), 0, 4 );
			
			// View Order Page.
			add_action( 'woocommerce_email_before_order_table', array( $this, 'wc_shipment_tracking_email_display' ), 0, 4 );
		}

	}
	
	public function on_plugin_loaded2() {
		if ( trackship_for_woocommerce()->is_active_yith_order_tracking() && !function_exists( 'ast_pro' ) ) {
			global $YWOT_Instance;
			// View Order Page.
			remove_action( 'woocommerce_order_items_table', array( $YWOT_Instance, 'add_order_shipping_details' ) );
			remove_action( 'woocommerce_order_details_after_order_table_items', array( $YWOT_Instance, 'add_order_shipping_details' ) );
			remove_action( 'woocommerce_order_details_after_order_table', array( $YWOT_Instance, 'add_order_shipping_details' ) );
	
			// email hook for Yith order tracking.
			if ( class_exists('YITH_WooCommerce_Order_Tracking_Premium') ) {
				remove_action( 'woocommerce_email_before_order_table', array( $YWOT_Instance, 'add_email_shipping_details' ), 10, 4 );
				remove_action( 'woocommerce_email_after_order_table', array( $YWOT_Instance, 'add_email_shipping_details' ), 10, 4 );
			}
			// View Order Page.
			add_action( 'woocommerce_email_before_order_table', array( $this, 'wc_shipment_tracking_email_display' ), 10, 4 );
		}
	}
	
	public function wc_shipment_tracking_email_display( $order, $sent_to_admin, $plain_text = null, $email = null ) {
		
		if ( is_a( $email, 'WC_Email_Customer_Refunded_Order' ) ) {
			return;
		}

		$local_template	= get_stylesheet_directory() . '/woocommerce/emails/tracking-info.php';
		if ( file_exists( $local_template ) && is_writable( $local_template ) ) {
			wc_get_template( 'emails/tracking-info.php', array( 
				'tracking_items' => trackship_for_woocommerce()->get_tracking_items( $order->get_id() ),
				'order_id' => $order->get_id(),
				'new_status' => 'shipped',
				'ts4wc_preview' => false,
			), 'woocommerce-advanced-shipment-tracking/', get_stylesheet_directory() . '/woocommerce/' );
		} else {
			wc_get_template( 'emails/tracking-info.php', array( 
				'tracking_items' => trackship_for_woocommerce()->get_tracking_items( $order->get_id() ),
				'order_id' => $order->get_id(),
				'new_status' => 'shipped',
				'ts4wc_preview' => false,
			), 'woocommerce-advanced-shipment-tracking/', trackship_for_woocommerce()->get_plugin_path() . '/templates/' );
		}
	}

	/*
	* Save data
	*/
	public function unsubscribe_emails_save_callback() {
		$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : '';
		check_ajax_referer( 'unsubscribe_emails' . $order_id, 'security' );
		$checkbox = isset( $_POST['checkbox'] ) ? sanitize_text_field( $_POST['checkbox'] ) : '';
		$order = wc_get_order( $order_id );
		$lable = isset( $_POST['lable'] ) ? sanitize_text_field( $_POST['lable'] ) : '';
		if ( 'email' == $lable ) {
			$order->update_meta_data( '_receive_shipment_emails', $checkbox );
		} else {
			$receive_sms = $checkbox ? 'yes' : 'no';
			$order->update_meta_data( '_smswoo_receive_sms', $receive_sms );
		}
		$order->save();

		echo json_encode( array('success' => 'true') );
		die();
	}

	/**
	 * Show tracking page widget
	**/
	public function show_tracking_page_widget( $order_id ) {
		$order = wc_get_order( $order_id );
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
		$this->display_tracking_page( $order_id, $tracking_items );
	}
	
	public function admin_tracking_page_widget( $order_id, $tracking_id ) {
		$order = wc_get_order( $order_id );
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
		foreach ( $tracking_items as $key => $tracking_item ) {
			if ( $tracking_item['tracking_id'] != $tracking_id && null != $tracking_id ) {
				unset($tracking_items[$key]);
			}
		}
		$this->display_tracking_page( $order_id, $tracking_items );
	}

	/**
	 *
	 * Include front js and css
	 *
	 *
	*/
	public function front_styles() {
		
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );
		wp_register_script( 'front-js', trackship_for_woocommerce()->plugin_dir_url() . 'assets/js/front.js', array( 'jquery' ), trackship_for_woocommerce()->version );
		wp_localize_script( 'front-js', 'zorem_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		
		wp_register_style( 'front_style', trackship_for_woocommerce()->plugin_dir_url() . 'assets/css/front.css', array(), trackship_for_woocommerce()->version );		
		
		$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : '';
		// echo '<pre>';print_r($_REQUEST);echo '</pre>';
		if ( in_array( $action, array( 'preview_tracking_page', 'tracking-form-preview' ) ) || is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'view-order' ) ) {
			wp_enqueue_style( 'front_style' );
			wp_enqueue_script( 'front-js' );
		}
	}
	
	/**
	 * Return tracking details or tracking form for shortcode - [wcast-track-order]
	 * Return tracking details or tracking form for shortcode - [trackship-track-order]
	*/
	public function woo_track_order_function() {
		
		wp_enqueue_style( 'front_style' );
		wp_enqueue_script( 'jquery-blockui' );
		wp_enqueue_script( 'front-js' );	
		
		if ( ! is_trackship_connected() ) { ?>
			<p><a href="https://trackship.com/" target="blank">TrackShip</a> is not active.</p>
			<?php
			return;
		}
		
		if ( isset( $_GET['order_id'] ) &&  isset( $_GET['order_key'] ) ) {
			
			$order_id = wc_clean($_GET['order_id']);
			$order = wc_get_order( $order_id );
			
			if ( empty( $order ) ) {
				$error = new WP_Error( 'ts4wc', __( 'Unable to locate the order.', 'trackship-for-woocommerce' ) );
			} else {
				
				$order_key = $order->get_order_key();
			
				if ( $order_key != $_GET['order_key'] ) {
					$error = new WP_Error( 'ts4wc', __( 'Unable to locate the order. or Invalid order key', 'trackship-for-woocommerce' ) );
				}
				
			}
		}

		if ( isset( $_GET['tracking'] ) ) {
			global $wpdb;
			$tracking_number = wc_clean( $_GET[ 'tracking' ] );
			$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT order_id FROM {$wpdb->prefix}trackship_shipment WHERE tracking_number = %s", $tracking_number ) );
			$order = wc_get_order( $order_id );
			if ( empty( $order ) ) {
				$error = new WP_Error( 'ts4wc', __( 'Unable to locate the order.', 'trackship-for-woocommerce' ) );
			}
		}
	
		if ( ! isset( $order_id ) || empty( $order ) || isset( $error ) ) {

			if ( isset( $error ) && is_wp_error( $error ) ) {
				echo esc_html($error->get_error_message());
			}

			ob_start();
			$this->track_form_template();
			$form = ob_get_clean();	
			return $form;

		} else {

			$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
			if ( !$tracking_items ) {
				unset( $order_id );
			}

			ob_start();
			echo esc_html( $this->display_tracking_page( $order_id, $tracking_items ) );
			$form = ob_get_clean();
			return $form;
		}
	}
	
	/**
	 * Ajax function for get tracking details
	*/
	public function get_tracking_info_fun() {
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? wc_clean( $_REQUEST['_wpnonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'tracking_form' ) ) {
			wp_send_json( array('success' => 'false', 'message' => __( 'Security verification failed, please refresh page and try again.', 'trackship-for-woocommerce' ) ) );
		}

		if ( ! is_trackship_connected() ) {
			return;
		}
		$order_id = isset( $_POST['order_id'] ) ? ltrim( wc_clean( wp_unslash( $_POST['order_id'] ) ), '#' ) : '';
		$email = isset( $_POST['order_email'] ) ? sanitize_email( $_POST['order_email'] ) : '';
		$tracking_number = isset( $_POST['order_tracking_number'] ) ? wc_clean( $_POST['order_tracking_number'] ) : '';
		
		$order_id = $order_id ? trackship_for_woocommerce()->ts_actions->get_formated_order_id($order_id) : $order_id;
		
		if ( !empty( $tracking_number ) ) {
			global $wpdb;
			$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}trackship_shipment WHERE tracking_number = %s", $tracking_number ) );
			$order_id = $row ? $row->order_id : '';
		}
		
		$order = wc_get_order( $order_id );
		if ( empty( $order ) ) {
			ob_start();
			$this->track_form_template();
			$form = ob_get_clean();
			echo json_encode( array('success' => 'false', 'message' => __( 'Unable to locate the order.', 'trackship-for-woocommerce' ), 'html' => $form ));
			die();
		}
		
		if ( empty( $tracking_number ) ) {
			$order_email = $order->get_billing_email();
			if ( strtolower( $order_email ) != strtolower( $email ) ) {
				ob_start();
				$this->track_form_template();
				$form = ob_get_clean();	
				echo json_encode( array('success' => 'false', 'message' => __( 'Unable to locate the order.', 'trackship-for-woocommerce' ), 'html' => $form ));
				die();
			}
		}
		
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
		$rows = trackship_for_woocommerce()->actions->get_shipment_rows( $order_id );
		if ( !$tracking_items || !$rows ) {
			ob_start();
			$this->track_processing_template( $order, $order_id );
			$form = ob_get_clean();
			echo json_encode( array('success' => 'true', 'message' => '', 'html' => $form ));
			die();
		}
		ob_start();
		$html = $this->display_tracking_page( $order_id, $tracking_items );
		$html = ob_get_clean();
		echo json_encode( array('success' => 'true', 'message' => '', 'html' => $html ));
		die();
	}
	
	/*
	* retuern Tracking form preview
	*/
	public function track_form_preview() {
		$action = isset( $_REQUEST[ 'action' ] ) ? sanitize_text_field( $_REQUEST[ 'action'] ) : '';
		
		if ( 'tracking-form-preview' != $action ) {
			return;
		}
		wp_head();
		
		show_admin_bar( false );
		?>
		<style>
		html {
			background: #f7f7f7;
			margin-top: 30px !important;
		}
		body { background: #f7f7f7; }
		</style>
		<?php
		$this->track_form_template();
		wp_footer();
		die();
	}

	/*
	* retuern Tracking form HTML
	*/
	public function track_form_template() {
		$local_template	= get_stylesheet_directory() . '/woocommerce/tracking/tracking-form.php';
		if ( file_exists( $local_template ) && is_writable( $local_template ) ) {	
			wc_get_template( 'tracking/tracking-form.php', array(), 'trackship-for-woocommerce/', get_stylesheet_directory() . '/woocommerce/' );
		} else {
			wc_get_template( 'tracking/tracking-form.php', array(), 'trackship-for-woocommerce/', trackship_for_woocommerce()->get_plugin_path() . '/templates/' );	
		}
	}

	public function track_processing_template( $order, $order_id ) {
		?>
		<div class="tracking-detail col not-shipped-widget">
			<div class="shipment-content">
				<div class="tracking-header">
					<div class="tracking_number_wrap">
						<span class="wc_order_id">
							<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" target="_blank">#<?php echo esc_html($order_id); ?></a>
						</span>
						<div class="shipment_heading"><?php esc_html_e( 'Order Processing', 'trackship-for-woocommerce' ); ?></div>	
					</div>
					<div class="not_shipped_content">
						<span><?php esc_html_e( "Your order is being processed, the tracking details will be available once it's Shipped.", 'trackship-for-woocommerce' ); ?></span>
						<span><?php esc_html_e( 'Please try again after you receive the shipping confirmation email.', 'trackship-for-woocommerce' ); ?></span>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	
	/*
	* retuern Tracking page HTML
	*/
	public function display_tracking_page( $order_id, $tracking_items ) {
		wp_enqueue_style( 'front_style' );
		wp_enqueue_script( 'jquery-blockui' );
		wp_enqueue_script( 'front-js' );
		
		global $wpdb;

		$unsubscribe = isset( $_GET['unsubscribe'] ) ? sanitize_text_field($_GET['unsubscribe']) : '' ;
		if ( 'true' == $unsubscribe ) {
			$order = wc_get_order( $order_id );
			$order->update_meta_data( '_receive_shipment_emails', 0 );
			$order->save();
			?>
			<div class="unsubscribe_message"><?php esc_html_e( 'You have been unsubscribed from shipment status emails.', 'trackship-for-woocommerce' ); ?></div>
			<?php
		}

		$tracking_page_defaults = trackship_admin_customizer();
		
		$border_color = get_trackship_settings('wc_ts_border_color', $tracking_page_defaults->defaults['wc_ts_border_color'] );
		$link_color = get_trackship_settings( 'wc_ts_link_color', $tracking_page_defaults->defaults['wc_ts_link_color'] );
		$background_color = get_trackship_settings('wc_ts_bg_color', $tracking_page_defaults->defaults['wc_ts_bg_color'] );
		$font_color = get_trackship_settings('wc_ts_font_color', $tracking_page_defaults->defaults['wc_ts_font_color'] );
		$hide_tracking_events = get_trackship_settings('ts_tracking_events', $tracking_page_defaults->defaults['ts_tracking_events'] );
		$tracking_page_layout = get_trackship_settings('ts_tracking_page_layout', $tracking_page_defaults->defaults['ts_tracking_page_layout'] );
		$border_radius = get_trackship_settings('wc_ts_border_radius', $tracking_page_defaults->defaults['wc_ts_border_radius'] );
		$show_trackship_branding = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'show_trackship_branding', 1 );
		?>
		<style>
			<?php if ( $link_color ) { ?>
				.col.tracking-detail .tracking_number_wrap a, .tracking_event_tab_view .view_more_class, .content_panel.product_details a, div.col.enhanced_tracking_detail a {
					color: <?php echo esc_html( $link_color ); ?>;
				}
				span.copy_tracking_page.trackship-tip svg {
					fill: <?php echo esc_html( $link_color ); ?>;
				}
				div.shipment-header .ts_from_input:checked + label {
					color: <?php echo esc_html( $link_color ); ?> !important;
					border-bottom: 3px solid <?php echo esc_html( $link_color ); ?>;
				}
				.heading_panel span.accordian-arrow.down, span.accordian-arrow.down {
					border-color: <?php echo esc_html( $link_color ); ?> !important;
				}
			<?php } ?>
			<?php if ( $border_radius ) { ?>
				.col.tracking-detail, .col.enhanced_tracking_detail {
					border-radius: <?php echo esc_html( $border_radius ); ?>px;
				}
			<?php } ?>
			<?php if ( $border_color ) { ?>
				body .col.tracking-detail, .shipment_heading, .shipment-header {
					border: 1px solid <?php echo esc_html( $border_color ); ?> !important;
				}
				body .col.tracking-detail .shipment_heading{
					border-bottom: 1px solid <?php echo esc_html( $border_color ); ?>;
				}
				body .tracking-detail .h4-heading {
					border-bottom: 1px solid <?php echo esc_html( $border_color ); ?> !important;
				}
				.tracking-detail .tracking_number_wrap {
					border-bottom: 1px solid <?php echo esc_html( $border_color ); ?> !important;
				}
				.trackship_branding, .tracking-detail .heading_panel {
					border-top: 1px solid <?php echo esc_html( $border_color ); ?> !important;
				}
				.col.enhanced_tracking_detail, div.est_delivery_section, div.tracking_widget_tracking_events_section, .enhanced_tracking_detail .enhanced_heading, .enhanced_tracking_detail .enhanced_content, div.last_mile_tracking_number, .enhanced_content .shipping_from_to , .enhanced_content ul.tpi_product_tracking_ul li {
					border-color: <?php echo esc_html( $border_color ); ?> !important;
				}
			<?php } ?>
			<?php if ( $background_color ) { ?>
				body .col.tracking-detail, .shipment-header, .tracking-detail .heading_panel, .tracking-detail .content_panel, .col.enhanced_tracking_detail {
					background: <?php echo esc_html( $background_color ); ?> !important;
				}
			<?php } ?>
			<?php if ( $font_color ) { ?>
				body .tracking-detail .shipment-content, body .tracking-detail .shipment-content h4, .shipment-header label.ts_from_label, .shipment_status_heading, .content_panel.shipment_status_notifications span, body .col.enhanced_tracking_detail, body .enhanced_content label, .enhanced_trackship_branding p {
					color: <?php echo esc_html( $font_color ); ?> !important;
				}				
				.heading_panel span.accordian-arrow, span.accordian-arrow.right {
					border-color: <?php echo esc_html( $font_color ); ?>;
				}
			<?php } ?>
			.woocommerce-account.woocommerce-view-order .tracking-header span.wc_order_id {display: none;}
			<?php if ( !$show_trackship_branding ) { ?>
				.trackship_branding, .enhanced_trackship_branding {display:none;}
			<?php } ?>
		</style>
		<?php
		$tracking_page_type = get_trackship_settings( 'tracking_page_type', $tracking_page_defaults->defaults['tracking_page_type'] );
		if ( 'modern' == $tracking_page_type ) {
			$this->new_tracking_widget( $order_id, $tracking_items );
			return;
		}
		$num = 1;
		$total_trackings = count( $tracking_items );
		$rows = trackship_for_woocommerce()->actions->get_shipment_rows( $order_id );
		if ( $total_trackings > 1 && $rows ) {
			$i = 1;
			$post_tracking = isset( $_POST['tnumber'] ) ? sanitize_text_field($_POST['tnumber']) : '' ;
			$post_tracking = isset( $_POST['order_tracking_number'] ) ? sanitize_text_field($_POST['order_tracking_number']) : $post_tracking;
			$url_tracking = isset( $_GET['tracking'] ) ? sanitize_text_field($_GET['tracking']) : $post_tracking;
			$url_tracking = str_replace( ' ', '', $url_tracking );
			echo '<div class="shipment-header">';
			foreach ( $tracking_items as $key => $item ) {
				$tracking_number = $item['tracking_number'];
				$class = str_replace( ' ', '', $tracking_number );
				?>
				<input id="<?php echo 'shipment_' . esc_attr($i); ?>" type="radio" name="ts_shipments" class="ts_from_input" <?php echo $class == $url_tracking ? 'checked' : ''; ?> >
				<?php /* translators: %s: search for a tag */ ?>
				<label for="<?php echo 'shipment_' . esc_attr($i); ?>" class="ts_from_label"><?php printf( esc_html__( 'Shipment %1$s', 'trackship-for-woocommerce' ), esc_html($i) ); ?></label>
				<?php
				$i++;
			}
			echo '</div>';
		}
		foreach ( $tracking_items as $key => $item ) {
			$tracking_number = $item['tracking_number'];
			$tracking_provider = $item['tracking_provider'];

			$tracker = new \stdClass();
			$row = trackship_for_woocommerce()->actions->get_shipment_row( $order_id , $tracking_number );
			if ( !$row ) {
				continue;
			}
			$tracker->ep_status = $row->pending_status ? $row->pending_status : $row->shipment_status;
			$tracker->est_delivery_date = $row->est_delivery_date;
			$tracker->tracking_detail = $row->tracking_events;
			$tracker->tracking_destination_events = $row->destination_events;
			
			$tracking_detail_org = '';
			$trackind_detail_by_status_rev = '';
			if ( isset( $tracker->tracking_detail ) && 'null' != $tracker->tracking_detail ) {
				$tracking_detail_org = json_decode($tracker->tracking_detail);
				$trackind_detail_by_status_rev = is_array($tracking_detail_org) ? array_reverse($tracking_detail_org) : array();
			}
			
			$tracking_details_by_date = array();
			
			foreach ( (array) $trackind_detail_by_status_rev as $key => $details ) {
				if ( isset( $details->datetime ) ) {
					$date = gmdate( 'Y-m-d', strtotime($details->datetime) );
					$tracking_details_by_date[$date][] = $details;
				}
			}
			
			$tracking_destination_detail_org = '';	
			$trackind_destination_detail_by_status_rev = '';
			
			if ( isset( $tracker->tracking_destination_events ) && 'null' != $tracker->tracking_destination_events ) {
				$tracking_destination_detail_org = json_decode($tracker->tracking_destination_events);
				$trackind_destination_detail_by_status_rev = array_reverse($tracking_destination_detail_org);	
			}
			
			$tracking_destination_details_by_date = array();
			
			foreach ( (array) $trackind_destination_detail_by_status_rev as $key => $details ) {
				if ( isset( $details->datetime ) ) {
					$date = gmdate( 'Y-m-d', strtotime( $details->datetime ) );
					$tracking_destination_details_by_date[$date][] = $details;
				}
			}

			$order = wc_get_order( $order_id );
			if ( isset( $tracker->ep_status ) ) {
				?>
				<div class="tracking-detail col <?php echo !in_array( $tracking_page_layout, array( 't_layout_1', 't_layout_3' ) ) ? 'tracking-layout-2' : ''; ?><?php echo ' shipment_' . esc_html($num); ?>" <?php echo 1 == $total_trackings ? 'style="display:block;"' : ''; ?>>
					<div class="shipment-content">
						<?php
						
						esc_html_e( $this->tracking_page_header( $order, $tracking_provider, $tracking_number, $tracker, $item, $trackind_detail_by_status_rev ) );
						
						esc_html_e( $this->tracking_progress_bar( $tracker ) );
						
						esc_html_e( $this->layout1_tracking_details( $trackind_detail_by_status_rev, $tracking_details_by_date, $trackind_destination_detail_by_status_rev, $tracking_destination_details_by_date, $tracker , $order_id, $tracking_provider, $tracking_number ) );
						
						?>
					</div>
					<div class="trackship_branding">
						<p><span><?php esc_html_e( 'Powered by ', 'trackship-for-woocommerce' ); ?></span><a href="https://trackship.com" title="TrackShip" target="blank"><img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/trackship-logo.png"></a></p>
					</div>
					<?php if ( in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ) { ?>
						<style> .trackship_branding{display:block !important;} </style>
					<?php } ?>
				</div>
				<?php 
			}
			$num++;
		}
	}
	
	/*
	* New Tracking Page
	*/
	public function new_tracking_widget( $order_id, $tracking_items ) {
		$post_tracking = isset( $_POST['tnumber'] ) ? sanitize_text_field($_POST['tnumber']) : '' ;
		$post_tracking = isset( $_POST['order_tracking_number'] ) ? sanitize_text_field($_POST['order_tracking_number']) : $post_tracking;
		$url_tracking = isset( $_GET['tracking'] ) ? sanitize_text_field($_GET['tracking']) : $post_tracking;
		$url_tracking = str_replace( ' ', '', $url_tracking );

		$num = 1;
		foreach ( $tracking_items as $key => $item ) {
			$tracking_number = isset( $item[ 'tracking_number' ] )? $item[ 'tracking_number' ] : false;
			$row = trackship_for_woocommerce()->actions->get_shipment_row( $order_id, $tracking_number );
			if ( !$row ) {
				continue;
			}
			// echo '<pre>';print_r($row);echo '</pre>';
			?>
			<div class="enhanced_tracking_detail col <?php echo 'shipment_' . esc_html($num); ?>">
				<?php $this->new_tracking_widget_header( $order_id, $row, $item, $tracking_number, $url_tracking ); ?>
				<div class="enhanced_tracking_content <?php echo 'shipment_' . esc_html($num); ?>">
					<?php $this->tracking_widget_est_delivery_section( $row, $num ); ?>
					<?php $this->new_tracking_widget_tracking_events( $order_id, $row, $item, $tracking_number, $num ); ?>
					<?php $this->shipment_details_notifications( $order_id, $tracking_items, $row, $tracking_number ); ?>
				</div>
			</div>
			<?php
			$num++;
		}
		if ( $num > 1 ) {
			?>
			<div class="enhanced_trackship_branding">
				<p><span><?php esc_html_e( 'Powered by ', 'trackship-for-woocommerce' ); ?></span><a href="https://trackship.com/" title="TrackShip" target="blank"><img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/trackship-logo.png"></a></p>
			</div>
			<?php if ( in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ) { ?>
				<style> .enhanced_trackship_branding{display:block !important;} </style>
			<?php } ?>
			<?php
		}
	}

	public function new_tracking_widget_header( $order_id, $row, $item, $tracking_number, $url_tracking ) {
		$hide_tracking_provider_image = get_trackship_settings('hide_provider_image');
		$ts_link_to_carrier = get_trackship_settings( 'ts_link_to_carrier' );
		$provider_image = isset( $item[ 'tracking_provider_image' ] ) ? $item[ 'tracking_provider_image' ] : false ;
		$tracking_link = isset( $item[ 'formatted_tracking_link' ] )? $item[ 'formatted_tracking_link' ] : false;
		$provider_name = isset( $item[ 'tracking_provider' ] )? $item[ 'tracking_provider' ] : false;

		include 'views/front/enhanced_tracking_widget_header.php';
	}
	
	public function tracking_widget_est_delivery_section ( $row, $num ) {
		$show_est_delivery_date = apply_filters( 'show_est_delivery_date', true, $row->shipping_provider );
		if ( $row->est_delivery_date && $show_est_delivery_date ) {
			$tracking_detail_org = '';
			$trackind_detail_by_status_rev = [];
			if ( isset( $row->tracking_events ) && 'null' != $row->tracking_events ) {
				$tracking_detail_org = json_decode($row->tracking_events);
				$trackind_detail_by_status_rev = is_array($tracking_detail_org) ? array_reverse($tracking_detail_org) : array();
			}
			$event_count = count($trackind_detail_by_status_rev);
			?>
			<div class="est_delivery_section">
				<span class="est-delivery-date <?php echo esc_html($row->shipment_status); ?>">
					<?php 'delivered' != $row->shipment_status ? esc_html_e( 'Est. Delivery Date', 'trackship-for-woocommerce' ) : esc_html_e( 'Delivered on', 'trackship-for-woocommerce' ); ?> : 
					<strong><?php esc_html_e( date_i18n( 'l, M d', strtotime( $row->est_delivery_date ) ) ); ?></strong>
				</span>
				<?php
				if ( $event_count > 1 ) {
					$this->enhanced_toogle_switch($num);
				}
				?>
			</div>
			<?php
		}
	}

	public function new_tracking_widget_tracking_events( $order_id, $row, $item, $tracking_number, $num ) {
		$tracking_detail_org = '';
		$trackind_detail_by_status_rev = [];
		if ( isset( $row->tracking_events ) && 'null' != $row->tracking_events ) {
			$tracking_detail_org = json_decode($row->tracking_events);
			$trackind_detail_by_status_rev = is_array($tracking_detail_org) ? array_reverse($tracking_detail_org) : array();	
		}

		$tracking_destination_detail_org = '';	
		$trackind_destination_detail_by_status_rev = [];
		
		if ( isset( $row->destination_events ) && 'null' != $row->destination_events ) {
			$tracking_destination_detail_org = json_decode($row->destination_events);
			$trackind_destination_detail_by_status_rev = array_reverse($tracking_destination_detail_org);
		}

		$event_count = count($trackind_detail_by_status_rev);
		// echo '<pre>';print_r($trackind_destination_detail_by_status_rev);echo '</pre>';
		$show_est_delivery_date = apply_filters( 'show_est_delivery_date', true, $row->shipping_provider );
		include 'views/front/enhanced_tracking_events.php';
	}

	public function shipment_details_notifications( $order_id, $tracking_items, $row, $tracking_number ) {
		$hide_last_mile = get_trackship_settings( 'ts_hide_list_mile_tracking', trackship_admin_customizer()->defaults['ts_hide_list_mile_tracking'] );
		$hide_from_to = get_trackship_settings('ts_hide_from_to', trackship_admin_customizer()->defaults['ts_hide_from_to'] );
		$order = wc_get_order( $order_id );
		include 'views/front/enhanced_shipment_details_notifications.php';
	}

	public function enhanced_toogle_switch ( $num ) {
		?>
		<span class="tracking_details_switch">
			<input id="enhanced_overview_<?php echo esc_html($num); ?>" data-type="overview" data-number="shipment_<?php echo esc_html($num); ?>" type="radio" name="enhanced_switch_<?php echo esc_html($num); ?>" class="enhanced_switch_input" checked >
			<label for="enhanced_overview_<?php echo esc_html($num); ?>" class="enhanced_switch"><?php esc_html_e('Overview', 'trackship-for-woocommerce' ); ?></label>

			<input id="enhanced_journey_<?php echo esc_html($num); ?>" data-type="journey" data-number="shipment_<?php echo esc_html($num); ?>" type="radio" name="enhanced_switch_<?php echo esc_html($num); ?>" class="enhanced_switch_input"  >
			<label for="enhanced_journey_<?php echo esc_html($num); ?>" class="enhanced_switch"><?php esc_html_e('Journey', 'trackship-for-woocommerce' ); ?></label>
		</span>
		<?php
	}

	/*
	* Tracking Page Header
	*/
	public function tracking_page_header( $order, $tracking_provider, $tracking_number, $tracker, $item, $trackind_detail_by_status_rev ) {
		$hide_tracking_provider_image = get_trackship_settings('hide_provider_image');
		$hide_from_to = get_trackship_settings('ts_hide_from_to', trackship_admin_customizer()->defaults['ts_hide_from_to'] );
		$hide_last_mile = get_trackship_settings( 'ts_hide_list_mile_tracking', trackship_admin_customizer()->defaults['ts_hide_list_mile_tracking'] );
		$provider_name = isset( $item[ 'formatted_tracking_provider' ] ) && !empty( $item[ 'formatted_tracking_provider' ] ) ? $item[ 'formatted_tracking_provider' ] : $item[ 'tracking_provider' ] ;
		$provider_image = isset( $item[ 'tracking_provider_image' ] ) ? $item[ 'tracking_provider_image' ] : false ;
		$tracking_link = isset( $item[ 'formatted_tracking_link' ] )? $item[ 'formatted_tracking_link' ] : false;
		$ts_link_to_carrier = get_trackship_settings( 'ts_link_to_carrier' );
		
		include 'views/front/tracking_page_header.php';	
	}
	
	public function tracking_progress_bar( $tracker ) {
		
		if ( in_array( $tracker->ep_status, array( 'invalid_tracking', 'carrier_unsupported', 'invalid_user_key', 'invalid_carrier', 'deleted' ) ) ) {
			return;
		}
		
		$tracking_page_layout = get_trackship_settings( 'ts_tracking_page_layout', 't_layout_1' );
		
		if ( in_array( $tracking_page_layout, array( 't_layout_1', 't_layout_3' ) ) ) {
			$width = '0';
		} else {
			if ( in_array( $tracker->ep_status, array( 'pending_trackship', 'pending', 'unknown', 'carrier_unsupported', 'insufficient_balance', 'invalid_carrier', '' ) ) ) {
				$width = '10%';
			} elseif ( in_array( $tracker->ep_status, array( 'in_transit', 'on_hold', 'failure' ) ) ) {
				$width = '30%';
			} elseif ( in_array( $tracker->ep_status, array( 'out_for_delivery', 'available_for_pickup', 'return_to_sender', 'exception' ) ) ) {
				$width = '60%';			
			} elseif ( 'delivered' == $tracker->ep_status ) {
				$width = '100%';
			} elseif ( 'pre_transit' == $tracker->ep_status ) {
				$width = '10%';
			} else {
				$width = '0';
			}
		}
		if ( 't_layout_4' == $tracking_page_layout && in_array( $tracker->ep_status, array( 'pending_trackship', 'pending', 'unknown', 'carrier_unsupported', 'insufficient_balance', 'invalid_carrier' ) ) ) {
			$width = '10%';
		}
		?>
		<div class="tracker-progress-bar <?php echo in_array( $tracking_page_layout, array( 't_layout_1', 't_layout_3' ) ) ? 'tracking_icon_layout ' : 'tracking_progress_layout'; ?> <?php echo esc_html( $tracking_page_layout ); ?>">
			<div class="progress <?php esc_html_e( $tracker->ep_status ); ?>">
				<div class="progress-bar <?php esc_html_e( $tracker->ep_status ); ?>" style="width: <?php esc_html_e( $width ); ?>;"></div>
				<?php if ( in_array( $tracking_page_layout, array( 't_layout_1', 't_layout_3' ) ) ) { ?>
					<div class="progress-icon icon1"></div>
					<div class="progress-icon icon2"></div>
					<div class="progress-icon icon3"></div>
					<div class="progress-icon icon4"></div>
				<?php } ?>
			</div>
		</div>
	<?php
	}
	
	public function layout1_tracking_details( $trackind_detail_by_status_rev, $tracking_details_by_date, $trackind_destination_detail_by_status_rev, $tracking_destination_details_by_date, $tracker, $order_id, $tracking_provider, $tracking_number ) {
		$tracking_page_defaults = trackship_admin_customizer();
		$hide_tracking_events = get_trackship_settings( 'ts_tracking_events', $tracking_page_defaults->defaults[ 'ts_tracking_events' ] );
		$action = isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : '';
		if ( 'get_admin_tracking_widget' == $action ) {
			$hide_tracking_events = 2;
		}
		include 'views/front/layout1_tracking_details.php';
	}
	
	public function get_products_detail_in_shipment ( $order_id, $tracker, $tracking_provider, $tracking_number ) {
		// echo $order_id;
		$order = wc_get_order( $order_id );
		$items = $order->get_items();
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
		 
		$products = array();
		foreach ( $items as $item_id => $item ) {
			
			$variation_id = $item->get_variation_id();
			$product_id = $item->get_product_id();
			
			if ( 0 != $variation_id ) {
				$product_id = $variation_id;
			}
			
			$products[$item_id] = array(
				'item_id' => $item_id,
				'product_id' => $product_id,
				'product_name' => $item->get_name(),
				'product_qty' => $item->get_quantity(),
			);
		}

		$products = apply_filters( 'tracking_widget_product_array', $products, $order_id, $tracker, $tracking_provider, $tracking_number );

		?>
		
		<ul class="tpi_product_tracking_ul">
			<?php
			foreach ( $products as $item_id => $product ) {
				$_product = wc_get_product( $product['product_id'] );
				if ( $_product ) {
					$image_size = array( 50, 50 );
					$image = $_product->get_image( $image_size );
					// echo esc_html($image);
					echo '<li>' . wp_kses_post( $image ) . '<span><a target="_blank" href=' . esc_url( get_permalink( $product['product_id'] ) ) . '>' . esc_html( $product['product_name'] ) . '</a> x ' . esc_html( $product['product_qty'] ) . '</span></li>';
				}
			}
			?>
		</ul>
		
		<style>
		ul.tpi_product_tracking_ul {
			list-style: none;
		}
		ul.tpi_product_tracking_ul li{
			font-size: 14px;
			margin: 0 0 5px;
			border-bottom: 1px solid #ccc;
			padding: 0 0 5px;
		}
		ul.tpi_product_tracking_ul li:last-child{
			border-bottom: 0;
			margin: 0;
			padding: 0;
		}
		ul.tpi_product_tracking_ul li img{
			vertical-align: middle;
		}
		ul.tpi_product_tracking_ul li span{
			margin: 0px 0px 0 10px;
			vertical-align: middle;
		}
		.tpi_products_heading{
			margin-top: -10px;
		}
		</style>
		<?php
	}

	public function tracking_widget_product_array_callback ( $products, $order_id, $tracker, $tracking_provider, $tracking_number ) {
		
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
		$order = wc_get_order( $order_id );
		$items = $order->get_items();
		$items_count = count($items);

		foreach ( $items as $item ) {
			
			$qty = $item->get_quantity();
			
			if ( 1 == $items_count && 1 == $qty ) {
				return $products;
			}
		}
		
		$show = $this->check_if_tpi_order( $tracking_items, $order );
		
		if ( !$show ) {
			return $products;
		}
		
		$tpi_products = array();

		foreach ( $tracking_items as $tracking_item ) {
			if ( $tracking_item['tracking_number'] == $tracking_number ) {
				
				if ( !isset( $tracking_item['products_list'] ) ) {
					return $products;
				}
				
				if ( empty( $tracking_item['products_list'] ) ) {
					return $products; 
				}

				foreach ( (array) $tracking_item[ 'products_list' ] as $product_list ) {
					if ( $product_list->product ) {
						$product = wc_get_product( $product_list->product );
						if ( $product ) {
							$tpi_products[$product_list->item_id] = array(
								'product_id' => $product_list->product,
								'product_name' => $product->get_name(),
								'product_qty' => $product_list->qty,
							);
						}
					}
				}
			}
		}
		return $tpi_products;
	}

	/**	 
	 * Function for check if order is Tracking Per Item
	 */
	public function check_if_tpi_order( $tracking_items, $order ) {
		
		$show_products = array();
		$product_list = array();
		$show = false;
		$items = $order->get_items();
		
		foreach ( $items as $item_id => $item ) {
			
			$product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();			
			
			$products[] = (object) array (
				'product' => $product_id,
				'item_id' => $item_id,	
				'qty' => $item->get_quantity(),
			);
		}

		foreach ( $tracking_items as $t_item ) {			
			if ( isset( $t_item[ 'products_list' ] ) && !empty( $t_item[ 'products_list' ] ) ) {
				$product_list[ $t_item[ 'tracking_id' ] ] = $t_item[ 'products_list' ];

				$array_check = ( $product_list[ $t_item[ 'tracking_id' ] ] == $products );
				
				if ( empty( $t_item[ 'products_list' ] ) || 1 == $array_check ) {
					$show_products[$t_item['tracking_id']] = 0;
				} else {
					$show_products[$t_item['tracking_id']] = 1;
				} 
			}
		}
		
		foreach ( $show_products as $key => $value ) {
			if ( 1 == $value ) {
				$show = true;
				break;
			}
		}
		return $show;
	}

	/**	 
	 * Function for check if order is Shipped and order has only 1 Shipment
	 */
	public function shipped_order_has_one_shipment( $tracking_items, $order ) {
		$order_status  = $order->get_status();
		if ( 1 == count( $tracking_items ) && in_array( $order_status, apply_filters( 'allowed_order_status_for_delivered', array( 'completed', 'updated-tracking', 'shipped', 'delivered' ) ) ) ) {
			return true;
		}
		return false;
	}

	public function get_notifications_option ( $order_id ) {
		if ( get_trackship_settings( 'enable_email_widget' ) ) {
			$order = wc_get_order( $order_id );
			$receive_email = $order->get_meta( '_receive_shipment_emails', true );
			$receive_email = '' != $receive_email ? $receive_email : 1;

			$receive_sms = $order->get_meta( '_smswoo_receive_sms', true );
			$receive_sms = '' != $receive_sms ? $receive_sms : 1;
			$receive_sms = 'no' == $receive_sms ? 0 : 1;
			?>
			<label>
				<input type="checkbox" class="unsubscribe_emails_checkbox" name="unsubscribe_emails" data-lable="email" value="1" <?php echo $receive_email ? 'checked' : ''; ?>>
				<span style="font-weight: normal;"><?php esc_html_e( 'Email notifications', 'trackship-for-woocommerce' ); ?></span>
			</label>
			<?php if ( class_exists( 'SMS_for_WooCommerce' ) ) { ?>
				<label>
					<input type="checkbox" class="unsubscribe_sms_checkbox" name="unsubscribe_sms" data-lable="sms" value="1" <?php echo $receive_sms ? 'checked' : ''; ?>>
					<span style="font-weight: normal;"><?php esc_html_e( 'SMS notifications', 'trackship-for-woocommerce' ); ?></span>
				</label>
			<?php } ?>
			<?php $ajax_nonce = wp_create_nonce( 'unsubscribe_emails' . $order_id ); ?>
			<input type="hidden" class="order_id_field" value="<?php echo esc_attr( $order_id ); ?>">
			<input type="hidden" name="action" value="unsubscribe_emails_save">
			<input type="hidden" name="unsubscribe_emails_nonce" class="unsubscribe_emails_nonce" value="<?php echo esc_html( $ajax_nonce ); ?>"/>

			<?php do_action( 'tracking_page_notifications_tab', $order_id ); ?>
			<?php
		}
	}

	/*
	* Tracking Page preview
	*/
	public static function preview_tracking_page() {
		
		$action = isset( $_REQUEST[ 'action' ] ) ? sanitize_text_field( $_REQUEST[ 'action'] ) : '';
		//echo '<pre>';print_r($_GET);echo '</pre>';
		$type = isset( $_GET['type'] ) ? sanitize_text_field($_GET['type']) : '' ;
		$status = isset( $_GET['status'] ) ? sanitize_text_field($_GET['status']) : '' ;
		
		if ( 'preview_tracking_page' != $action ) {
			return;
		}
		
		wp_head();

		show_admin_bar( false );
		
		$tracking_page_defaults = trackship_admin_customizer();
		
		$tracking_page_layout = get_trackship_settings( 'ts_tracking_page_layout', $tracking_page_defaults->defaults['ts_tracking_page_layout'] );
		$hide_tracking_events = get_trackship_settings( 'ts_tracking_events', $tracking_page_defaults->defaults['ts_tracking_events'] );
		$border_color = get_trackship_settings( 'wc_ts_border_color', $tracking_page_defaults->defaults['wc_ts_border_color'] );
		$link_color = get_trackship_settings( 'wc_ts_link_color', $tracking_page_defaults->defaults['wc_ts_link_color'] );
		$ts_link_to_carrier = get_trackship_settings( 'ts_link_to_carrier' );
		$hide_tracking_provider_image = get_trackship_settings( 'hide_provider_image' );
		$show_trackship_branding = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'show_trackship_branding', 1 );
		$font_color = get_trackship_settings( 'wc_ts_font_color', $tracking_page_defaults->defaults['wc_ts_font_color'] );
		$border_radius = get_trackship_settings('wc_ts_border_radius', $tracking_page_defaults->defaults['wc_ts_border_radius'] );
		$background_color = get_trackship_settings( 'wc_ts_bg_color', $tracking_page_defaults->defaults['wc_ts_bg_color'] );
		$hide_from_to = get_trackship_settings('ts_hide_from_to', $tracking_page_defaults->defaults['ts_hide_from_to'] );
		$hide_last_mile = get_trackship_settings( 'ts_hide_list_mile_tracking', $tracking_page_defaults->defaults['ts_hide_list_mile_tracking'] );
		$tracking_page_type = get_trackship_settings( 'tracking_page_type', $tracking_page_defaults->defaults['tracking_page_type'] );
		
		?>
		<style>
			<?php if ( 'modern' == $tracking_page_type ) { ?>
				.tracking-detail.col {
					display: none;
				}
			<?php } else { ?>
				.preview_enhanced_tracking_widget {
					display: none;
				}
				.tracking-detail.col {
					display: block;
				}
			<?php } ?>
		</style>
		<?php

		include 'views/front/preview_enhanced_tracking_page.php';
		include 'views/front/preview_tracking_page.php';
		wp_footer();
		die();
	}
}
