<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Local_Pickup_Customizer {
	
	private static $screen_id = 'alp_customizer';

	private static $text_domain = '';

	private static $screen_title = 'ALP Customizer'; 

	// WooCommerce email classes.
	public static $email_types_class_names  = array(
		//ALP custom status
		'ready_pickup'						=> 'WC_Email_Customer_Ready_Pickup_Order',
		'pickup'							=> 'WC_Email_Customer_Pickup_Order',
	);
	
	public static $email_types_order_status = array(
		//ALP custom status
		'ready_pickup'						=> 'ready-pickup',
		'pickup'							=> 'pickup',
	);
	
	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return WC_Local_Pickup_Customizer
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	*/
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	 * 
	 * @since  1.0
	*/
	public function __construct() {
		$this->init();
	}
	
	/*
	 * init function
	 *
	 * @since  1.0
	*/
	public function init() {

		//adding hooks
		add_action( 'admin_menu', array( $this, 'register_woocommerce_menu' ), 99 );

		add_action('rest_api_init', array( $this, 'route_api_functions' ) );
						
		add_action('admin_enqueue_scripts', array( $this, 'customizer_enqueue_scripts' ) );

		add_action('admin_footer', array( $this, 'admin_footer_enqueue_scripts' ) );

		add_action( 'wp_ajax_' . self::$screen_id . '_email_preview', array( $this, 'get_preview_func' ) );
		add_action( 'wp_ajax_send_' . self::$screen_id . '_test_email', array( $this, 'send_test_email_func' ) );

		// Custom Hooks for everyone
		add_filter( 'alp_customizer_email_options', array( $this, 'alp_customizer_email_options' ), 10, 2);
		add_filter( 'alp_customizer_preview_content', array( $this, 'alp_customizer_preview_content' ), 10, 1);
		
	}
	
	/*
	 * Admin Menu add function
	 *
	 * @since  2.4
	 * WC sub menu 
	*/
	public function register_woocommerce_menu() {
		add_menu_page( __( self::$screen_title, 'advanced-local-pickup-for-woocommerce' ), __( self::$screen_title, 'advanced-local-pickup-for-woocommerce' ), 'manage_options', self::$screen_id, array( $this, 'react_settingsPage' ) );
	}

	/*
	 * Call Admin Menu data function
	 *
	 * @since  2.4
	 * WC sub menu 
	*/
	public function react_settingsPage() {
		echo '<div id="root"></div>';
	}

	/*
	 * Add admin javascript
	 *
	 * @since  2.4
	 * WC sub menu 
	*/
	public function admin_footer_enqueue_scripts() {
		echo '<style type="text/css">#toplevel_page_' . esc_html(self::$screen_id) . ' { display: none !important; }</style>';

	}
	
	/*
	* Add admin javascript
	*
	* @since 1.0
	*/	
	public function customizer_enqueue_scripts() {
		
		
		$page = isset( $_GET['page'] ) ? sanitize_text_field($_GET['page']) : '' ;
		
		// Add condition for css & js include for admin page  
		if ( self::$screen_id == $page ) {
			// Add the WP Media 
			wp_enqueue_media();

			
			wp_enqueue_script( self::$screen_id, plugin_dir_url(__FILE__) . 'dist/main.js', ['jquery', 'wp-util', 'wp-color-picker'], time(), true);
			wp_localize_script( self::$screen_id, self::$screen_id, array(
				'main_title'	=> self::$screen_title,
				'admin_email' => get_option('admin_email'),
				'send_test_email_btn' => true,
				'iframeUrl'	=> array(
					'ready_pickup' => admin_url('admin-ajax.php?action=' . self::$screen_id . '_email_preview&preview=ready_pickup'),
					'pickup' => admin_url('admin-ajax.php?action=' . self::$screen_id . '_email_preview&preview=pickup'),
				),
				'back_to_wordpress_link' => admin_url('admin.php?page=local_pickup&tab=settings'),
				'rest_nonce'	=> wp_create_nonce('wp_rest'),
				'rest_base'	=> esc_url_raw( rest_url() ),
			));

			wp_enqueue_style( self::$screen_id . '-custom', plugin_dir_url(__FILE__) . 'assets/custom.css', array(), time() );
		}
		
	}


	/*
	 * Customizer Routes API 
	*/
	public function route_api_functions() {

		register_rest_route( self::$screen_id, 'settings', array(
			'methods'  => 'GET',
			'callback' => [$this, 'return_json_sucess_settings_route_api'],
			'permission_callback' => '__return_true',
		));

		register_rest_route( self::$screen_id, 'store/update', array(
			'methods'				=> 'POST',
			'callback'				=> [$this, 'update_store_settings'],
			'permission_callback'	=> '__return_true',
		));

		register_rest_route( self::$screen_id, 'send-test-email', array(
			'methods'				=> 'POST',
			'callback'				=> [$this, 'send_test_email_func'],
			'permission_callback'	=> '__return_true',
		));

	}

	/*
	 * Settings API 
	*/
	public function return_json_sucess_settings_route_api( $request ) {

		if ( !current_user_can( 'manage_options' ) ) {
			echo json_encode( array('permission' => 'false') );
			die();
		}

		$preview = !empty($request->get_param('preview')) ? $request->get_param('preview') : 'ready_pickup';
		return wp_send_json_success($this->customize_setting_options_func( $preview ));

	}

	public function customize_setting_options_func( $preview ) {

		$settings = apply_filters(  self::$screen_id . '_email_options' , $settings = array(), $preview );
		
		return $settings; 

	}


	public function get_preview_func() {
		if ( !current_user_can( 'manage_options' ) ) {
			echo json_encode( array('permission' => 'false') );
			die();
		}
		$preview = isset($_GET['preview']) ? sanitize_text_field($_GET['preview']) : 'ready_pickup';
		echo wp_kses_post($this->get_preview_email($preview));
		die();
	}

	/**
	 * Get the email content
	 *
	 */
	public function get_preview_email( $preview ) { 

		$content = apply_filters( self::$screen_id . '_preview_content' , $preview );

		$content .= '<style type="text/css">body{margin: 0;}</style>';

		add_filter( 'wp_kses_allowed_html', array( $this, 'allowed_css_tags' ) );
		add_filter( 'safe_style_css', array( $this, 'safe_style_css' ), 10, 1 );

		return wp_kses_post($content);
	}

	/*
	* update a customizer settings
	*/
	public function update_store_settings( $request ) {

		if ( !current_user_can( 'manage_options' ) ) {
			echo json_encode( array('permission' => 'false') );
			die();
		}

		$preview = !empty($request->get_param('preview')) ? $request->get_param('preview') : '';

		$data = $request->get_params() ? $request->get_params() : array();

		if ( ! empty( $data ) ) {

			//data to be saved
			
			$settings = $this->customize_setting_options_func( $preview );
			
			foreach ( $settings as $key => $val ) {

				if ( !isset($data[$key]) || ( isset($val['show']) && true != $val['show'] ) ) {
					continue;
				}

				//check column exist
				if ( isset( $val['option_type'] ) && 'key' == $val['option_type'] ) {
					$data[$key] = isset($data[$key]) ? wp_kses_post( wp_unslash( $data[$key] ) ) : '';
					update_option( $key, $data[$key] );
				} elseif ( isset( $val['option_type'] ) && 'array' == $val['option_type'] ) {
					if ( isset( $val['option_key'] ) && isset( $val['option_name'] ) ) {
						$option_data = get_option( $val['option_name'], array() );
						if ( 'enabled' == $val['option_key'] ) {
							$option_data[$val['option_key']] = isset($data[$key]) && 1 == $data[$key] ? wp_kses_post( wp_unslash( 'yes' ) ) : wp_kses_post( wp_unslash( 'no' ) );
						} else {
							$option_data[$val['option_key']] = isset($data[$key]) ? wp_kses_post( wp_unslash( $data[$key] ) ) : '';
						}
						update_option( $val['option_name'], $option_data );
					} elseif ( isset($val['option_name']) ) {
						$option_data = get_option( $val['option_name'], array() );
						$option_data[$key] = isset($data[$key]) ? wp_kses_post( wp_unslash( $data[$key] ) ) : '';
						update_option( $val['option_name'], $option_data );
					}
				}
			}
			
			echo json_encode( array('success' => true, 'preview' => $preview) );
			die();
	
		}

		echo json_encode( array('success' => false) );
		die();
	}

	/*
	* send a test email
	*/
	public function send_test_email_func( $request ) {
		
		if ( !current_user_can( 'manage_options' ) ) {
			echo json_encode( array('permission' => 'false') );
			die();
		}

		$data = $request->get_params() ? $request->get_params() : array();

		$preview = !empty( $data['preview'] ) ? sanitize_text_field($data['preview']) : '';
		$recipients = !empty( $data['recipients'] ) ? sanitize_text_field($data['recipients']) : '';

		if ( ! empty( $preview ) && ! empty( $recipients ) ) {
			$message 		= apply_filters( self::$screen_id . '_preview_content' , $preview );
			$subject_email 	= 'email';
			$subject = str_replace('{site_title}', get_bloginfo( 'name' ), 'Test ' . $subject_email );
			
			// create a new email
			$email 		= new WC_Email();
			add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
			add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );

			$recipients = explode( ',', $recipients );
			if ($recipients) {
				foreach ( $recipients as $recipient) {
					wp_mail( $recipient, $subject, $message, $email->get_headers() );
				}
			}
			
			echo json_encode( array('success' => true) );
			die();
			
		}

		echo json_encode( array('success' => false) );
		die();
	}

	public function alp_customizer_email_options( $settings, $preview ) {
						
		$pickup_instruction = get_option('pickup_instruction_customize_settings', array());
		
		$settings = array(
			
			//panels
			'email_content'	=> array(
				'title'	=> esc_html__( 'Email Content', 'advanced-local-pickup-for-woocommerce' ),
				'type'	=> 'panel',
			),
			'email_design'	=> array(
				'title'	=> esc_html__( 'Email Design', 'advanced-local-pickup-for-woocommerce' ),
				'type'	=> 'panel',
			),
			
			//sub-panels
			'widget_style' => array(
				'title'       => esc_html__( 'Widget Style', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'sub-panel',
				'parent'	=> 'email_design',
			),
			'widget_header' => array(
				'title'       => esc_html__( 'Widget Header', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'sub-panel',
				'parent'	=> 'email_design',
			),
			'pickup_location_info' => array(
				'title'       => esc_html__( 'Pickup Location info', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'sub-panel',
				'parent'	=> 'email_design',
			),
			
			//settings
			'background_color' => array(
				'parent'=> 'widget_style',
				'title'    => esc_html__( 'Background Color', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'color',
				'default'  => !empty($pickup_instruction['background_color']) ? $pickup_instruction['background_color'] : '#f5f5f5',
				'show'     => true,
				'option_name' => 'pickup_instruction_customize_settings',
				'option_type' => 'array',
			),
			'border_color' => array(
				'parent'=> 'widget_style',
				'title'    => esc_html__( 'Border Color', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'color',
				'default'  => !empty($pickup_instruction['border_color']) ? $pickup_instruction['border_color'] : '#e0e0e0',
				'show'     => true,
				'option_name' => 'pickup_instruction_customize_settings',
				'option_type' => 'array',
			),
			'padding' => array(
				'parent'=> 'widget_style',
				'title'    => esc_html__( 'Padding', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'select',
				'default'  => !empty($pickup_instruction['padding']) ? $pickup_instruction['padding'] : '15px',
				'show'     => true,
				'option_name' => 'pickup_instruction_customize_settings',
				'option_type' => 'array',
				'options'  => array(
					'0px' => '0px',
					'5px' => '5px',
					'10px' => '10px',
					'15px' => '15px',
					'20px' => '20px',
					'25px' => '25px',
					'30px' => '30px',
				)
			),
			'hide_widget_header' => array(
				'parent'=> 'widget_header',
				'title'    => esc_html__( 'Hide Widget Header', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => isset($pickup_instruction['hide_widget_header']) ? $pickup_instruction['hide_widget_header'] : '0',
				'type'     => 'checkbox',
				'show'     => true,
				'option_name' => 'pickup_instruction_customize_settings',
				'option_type' => 'array',
			),
			'widget_header_text' => array(
				'parent'=> 'widget_header',
				'title'    => esc_html__( 'Widget Header Text', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => !empty($pickup_instruction['widget_header_text']) ? $pickup_instruction['widget_header_text'] : esc_html__( 'Pick up information', 'advanced-local-pickup-for-woocommerce' ),
				'placeholder' => esc_html__( 'Pick up information', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'text',
				'show'     => true,
				'option_name' => 'pickup_instruction_customize_settings',
				'option_type' => 'array',
			),
			'hide_addres_header' => array(
				'parent'=> 'pickup_location_info',
				'title'    => esc_html__( 'Hide Pickup Address Header', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => isset($pickup_instruction['hide_addres_header']) ? $pickup_instruction['hide_addres_header'] : '0',
				'type'     => 'checkbox',
				'show'     => true,
				'option_name' => 'pickup_instruction_customize_settings',
				'option_type' => 'array',
			),
			'addres_header_text' => array(
				'parent'=> 'pickup_location_info',
				'title'    => esc_html__( 'Pickup Address Header Text', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => !empty($pickup_instruction['addres_header_text']) ? $pickup_instruction['addres_header_text'] : esc_html__( 'Pickup Address', 'advanced-local-pickup-for-woocommerce' ),
				'placeholder' => esc_html__( 'Pickup Address', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'text',
				'show'     => true,
				'option_name' => 'pickup_instruction_customize_settings',
				'option_type' => 'array',
			),
			'hide_hours_header' => array(
				'parent'=> 'pickup_location_info',
				'title'    => esc_html__( 'Hide Office Hours Header', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => isset($pickup_instruction['hide_hours_header']) ? $pickup_instruction['hide_hours_header'] : '0',
				'type'     => 'checkbox',
				'show'     => true,
				'option_name' => 'pickup_instruction_customize_settings',
				'option_type' => 'array',
			),
			'header_hours_text' => array(
				'parent'=> 'pickup_location_info',
				'title'    => esc_html__( 'Office Hours Header Text', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => !empty($pickup_instruction['header_hours_text']) ? $pickup_instruction['header_hours_text'] : esc_html__( 'Pickup Hours', 'advanced-local-pickup-for-woocommerce' ),
				'placeholder' => esc_html__( 'Pickup Hours', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'text',
				'show'     => true,
				'option_name' => 'pickup_instruction_customize_settings',
				'option_type' => 'array',
			),

		);
		
		//settings			
		$email_types = array(
			'ready_pickup'		=> esc_html__( 'Ready for Pickup', 'advanced-local-pickup-for-woocommerce' ),
			'pickup'			=> esc_html__( 'Picked Up', 'advanced-local-pickup-for-woocommerce' ),
		);
		
		$settings[ 'email_type' ] = array(
			'title'    => esc_html__( 'Email type', 'advanced-local-pickup-for-woocommerce' ),
			'type'     => 'select',
			'default'  => $preview ? $preview : 'ready_pickup',
			'options'  => $email_types,
			'show'     => true,
			'previewType' => true,
			'parent'=> 'email_content',
		);
		
		foreach ( $email_types as $key => $value ) {
			
			$email_settings = get_option('woocommerce_customer_' . $key . '_order_settings', array());
			$defualt_array = array(
				'ready_pickup_subject' => 'Your {site_title} order is now Ready for pickup',
				'ready_pickup_heading' => 'Your Order is Ready for pickup',
				'ready_pickup_additional_content' => "Hi there. we thought you'd like to know that your recent order from {site_title} has been ready for pickup.",
				'pickup_subject' => 'Your order from {site_title} was picked up',
				'pickup_heading' => "You've Got it!",
				'pickup_additional_content' => 'Hi {customer_first_name}. Thank you for picking up your {site_title} order #{order_number}. We hope you enjoyed your shopping experience.',
			);
			$email_settings = get_option('woocommerce_customer_' . $key . '_order_settings', array());
			
			$settings[ $key . '_enabled' ] = array(
				'parent'=> 'email_content',
				'title'    => esc_html__( 'Enable email', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => !empty($email_settings['enabled']) && 'no' == $email_settings['enabled'] ? 0 : 1,
				'type'     => 'tgl-btn',
				'show'     => true,
				'option_name'=> 'woocommerce_customer_' . $key . '_order_settings',
				'option_key'=> 'enabled',
				'option_type'=> 'array',
				'class'		=> $key . '_sub_menu all_status_submenu',
			);
			
			$settings[ $key . '_recipient' ] = array(
				'parent'=> 'email_content',
				'title'    => esc_html__( 'Recipients', 'advanced-local-pickup-for-woocommerce' ),
				'desc'  => esc_html__( 'add comma-seperated emails, defaults to placeholder {customer_email} ', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => !empty($email_settings['recipient']) ? $email_settings['recipient'] : '{customer_email}',
				'placeholder' => esc_html__( 'add comma-seperated emails, defaults to placeholder {customer_email}', 'advanced-local-pickup-for-woocommerce' ),
				'type'     => 'text',
				'show'     => true,
				'option_name' => 'woocommerce_customer_' . $key . '_order_settings',
				'option_key'=> 'recipient',
				'option_type' => 'array',
				'class'		=> $key . '_sub_menu all_status_submenu',
			);
			$settings[ $key . '_subject' ] = array(
				'parent'=> 'email_content',
				'title'    => esc_html__( 'Email Subject', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => !empty($email_settings['subject']) ? stripslashes($email_settings['subject']) : $defualt_array[$key . '_subject'],
				'placeholder' => $defualt_array[$key . '_subject'],
				'type'     => 'text',
				'show'     => true,
				'option_name' => 'woocommerce_customer_' . $key . '_order_settings',
				'option_key'=> 'subject',
				'option_type' => 'array',
				'class'		=> $key . '_sub_menu all_status_submenu',
			);
			
			$settings[ $key . '_heading' ] = array(
				'parent'=> 'email_content',
				'title'    => esc_html__( 'Email heading', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => !empty($email_settings['heading']) ? stripslashes($email_settings['heading']) : $defualt_array[$key . '_heading'],
				'placeholder' => $defualt_array[$key . '_heading'],
				'type'     => 'text',
				'show'     => true,
				'option_name' => 'woocommerce_customer_' . $key . '_order_settings',
				'option_key'=> 'heading',
				'option_type' => 'array',
				'class'		=> $key . '_sub_menu all_status_submenu',
			);
			
			$settings[ $key . '_additional_content' ] = array(
				'parent'=> 'email_content',
				'title'    => esc_html__( 'Email content', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => !empty($email_settings['additional_content']) ? stripslashes($email_settings['additional_content']) : $defualt_array[$key . '_additional_content'],
				'placeholder' => $defualt_array[$key . '_additional_content'],
				'type'     => 'textarea',
				'show'     => true,
				'option_key'=> 'additional_content',
				'option_name' => 'woocommerce_customer_' . $key . '_order_settings',
				'option_type' => 'array',
				'class'		=> $key . '_sub_menu all_status_submenu',
			);
			
			$settings[ $key . '_codeinfoblock' ] = array(
				'parent'=> 'email_content',
				'title'    => esc_html__( 'Available Placeholders:', 'advanced-local-pickup-for-woocommerce' ),
				'default'  => '<code>{customer_first_name}<br>{customer_last_name}<br>{site_title}<br>{order_number}</code>',
				'type'     => 'codeinfo',
				'show'     => true,
				'class'		=> $key . '_sub_menu all_status_submenu',
			);
		};

		return $settings;
	}

	public function alp_customizer_preview_content( $preview ) {
		
		$wc_emails      = WC_Emails::instance();
		$emails         = $wc_emails->get_emails();		

		$email_template = isset( $_GET['preview'] ) ? sanitize_text_field($_GET['preview']) : get_option( 'orderStatus', 'ready_pickup' );
		$preview_id = 'mockup';

		$email_type = self::get_email_class_name( $email_template );

		if ( false === $email_type ) {
			return false;
		}		 				
		
		if ( isset( $emails[ $email_type ] ) && is_object( $emails[ $email_type ] ) ) {
			$email = $emails[ $email_type ];
			
		}
		$order_status = self::get_email_order_status( $email_template );
		
		$order = self::get_wc_order_for_preview( $order_status, $preview_id );
		
		if ( is_object( $order ) ) {
			$user_id = (int) $order->get_meta( '_customer_user', true );
			if ( 0 === $user_id ) {
				$user_id = get_current_user_id();
			}
		} else {
			$user_id = get_current_user_id();
		}
		$user = get_user_by( 'id', $user_id );
		
		if ( isset( $email ) ) {
			WC()->payment_gateways();
			WC()->shipping();
			$email->object               = $order;
			$user_id = $order->get_meta( '_customer_user', true );
			if ( is_object( $order ) ) {
				$email->find['order-date']   = '{order_date}';
				$email->find['order-number'] = '{order_number}';
				$email->find['customer-first-name'] = '{customer_first_name}';
				$email->find['customer-last-name'] = '{customer_last_name}';
				$email->replace['order-date']   = wc_format_datetime( $email->object->get_date_created() );
				$email->replace['order-number'] = $email->object->get_order_number();
				$email->replace['customer-first-name'] = $email->object->get_billing_first_name();
				$email->replace['customer-last-name'] = $email->object->get_billing_last_name();
				$email->recipient = $email->object->get_billing_email();
			}
			
			if ( ! empty( $email ) ) {
				
				$content = $email->get_content();		
				$content = $email->style_inline( $content );
				$content = apply_filters( 'woocommerce_mail_content', $content );	
				
			} else {
				if ( false == $email->object ) {
					$content = '<div style="padding: 35px 40px; background-color: white;">' . __( 'This email type can not be previewed please try a different order or email type.', 'advanced-local-pickup-for-woocommerce' ) . '</div>';
				}
			}
		} else {
			$content = false;
		}
		
		return $content;
		die();
	}

	/**
	 * Get WooCommerce order for preview
	 *
	 * @param string $order_status
	 * @return object
	 */
	public static function get_wc_order_for_preview( $order_status = null, $order_id = null ) {
		if ( ! empty( $order_id ) && 'mockup' != $order_id ) { 
			return wc_get_order( $order_id );
		} else {
			// Use mockup order

			// Instantiate order object
			$order = new WC_Order();

			// Other order properties
			$order->set_props( array(
				'id'                 => 1,
				'status'             => ( null === $order_status ? 'processing' : $order_status ),
				'billing_first_name' => 'Sherlock',
				'billing_last_name'  => 'Holmes',
				'billing_company'    => 'Detectives Ltd.',
				'billing_address_1'  => '221B Baker Street',
				'billing_city'       => 'London',
				'billing_postcode'   => 'NW1 6XE',
				'billing_country'    => 'GB',
				'billing_email'      => 'sherlock@holmes.co.uk',
				'billing_phone'      => '02079304832',
				'date_created'       => gmdate( 'Y-m-d H:i:s' ),
				'total'              => 24.90,
			) );

			// Item #1
			$order_item = new WC_Order_Item_Product();
			$order_item->set_props( array(
				'name'     => 'A Study in Scarlet',
				'subtotal' => '9.95',
			) );
			$order->add_item( $order_item );

			// Item #2
			$order_item = new WC_Order_Item_Product();
			$order_item->set_props( array(
				'name'     => 'The Hound of the Baskervilles',
				'subtotal' => '14.95',
			) );
			$order->add_item( $order_item );
			
			$item = new WC_Order_Item_Shipping();
			$item->set_props( array(
				'method_title' => 'Local Pickup',
				'method_id' => 'local_pickup'
			) );
			$order->add_item($item);
			
			//echo '<pre>';print_r($order);echo '</pre>';		
			
			// Return mockup order
			return $order;
		}

	}

	/**
	 * Get the from name for outgoing emails.
	 *
	 * @return string
	 */
	public function get_from_name() {
		$from_name = apply_filters( 'woocommerce_email_from_name', get_option( 'woocommerce_email_from_name' ), $this );
		return wp_specialchars_decode( esc_html( $from_name ), ENT_QUOTES );
	}

	/**
	 * Get the from address for outgoing emails.
	 *
	 * @return string
	 */
	public function get_from_address() {
		$from_address = apply_filters( 'woocommerce_email_from_address', get_option( 'woocommerce_email_from_address' ), $this );
		return sanitize_email( $from_address );
	}
	
	/**
	 * Get the email order status
	 *
	 * @param string $email_template the template string name.
	 */
	public function get_email_order_status( $email_template ) {
		
		$order_status = apply_filters( 'customizer_email_type_order_status_array', self::$email_types_order_status );
		
		$order_status = self::$email_types_order_status;
		
		if ( isset( $order_status[ $email_template ] ) ) {
			return $order_status[ $email_template ];
		} else {
			return 'processing';
		}
	}

	/**
	 * Get the email class name
	 *
	 * @param string $email_template the email template slug.
	 */
	public function get_email_class_name( $email_template ) {
		
		$class_names = apply_filters( 'customizer_email_type_class_name_array', self::$email_types_class_names );

		$class_names = self::$email_types_class_names;
		if ( isset( $class_names[ $email_template ] ) ) {
			return $class_names[ $email_template ];
		} else {
			return false;
		}
	}

	public function allowed_css_tags( $tags ) {
		$tags['style'] = array( 'type' => true, );
		return $tags;
	}
	
	public function safe_style_css( $styles ) {
		 $styles[] = 'display';
		return $styles;
	}

}
