<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class WC_TrackShip_Email_Manager {

	private static $instance;
	public $order;
	public $shipment_row;
	public $tracking_item;
	public $tracking_number;
	
	/**
	 * Constructor sets up actions
	 */
	public function __construct() {
		add_action( 'ts_status_change_trigger', array( $this, 'ts_status_change_trigger' ), 10, 4 );
		add_action( 'trigger_pickup_reminder_email', array( $this, 'trigger_pickup_reminder_email' ), 10, 4 );
	}

	public function ts_status_change_trigger ( $order_id, $old_status, $new_status, $tracking_number ) {
		$order = wc_get_order( $order_id );
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );

		foreach ( ( array ) $tracking_items as $key => $tracking_item ) {
			if ( trim( $tracking_item['tracking_number'] ) != trim($tracking_number) ) {
				continue;
			}
			$row = trackship_for_woocommerce()->actions->get_shipment_row( $order_id , $tracking_item['tracking_number'] );
			$this->shippment_email_trigger( $order_id, $old_status, $new_status, $tracking_item, $row );
			break;
		}
	}
	
	/**
	 * Code for send shipment status email
	 */
	public function shippment_email_trigger( $order_id, $old_status, $new_status, $tracking_item, $shipment_row ) {
		$order = wc_get_order( $order_id );
		$this->order = $order;
		$this->shipment_row = $shipment_row;
		$this->tracking_item = $tracking_item;
		$this->tracking_number = $tracking_item['tracking_number'];
		$status = str_replace('_', '', $new_status);
		$status = 'delivered' == $status ? 'delivered_status' : $status;

		$enable = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_' . $status . '_email_settings', 'wcast_enable_' . $status . '_email', '');
		$for_amazon_order = trackship_for_woocommerce()->ts_actions->is_notification_on_for_amazon( $order_id );
		$receive_email = $order->get_meta( '_receive_shipment_emails', true );

		$arg = array(
			'order_id'			=> $order_id,
			'order_number'		=> wc_get_order( $order_id )->get_order_number(),
			'tracking_number'	=> $tracking_item['tracking_number'],
			'date'				=> current_time( 'Y-m-d H:i:s' ),
			'shipment_status'	=> $new_status,
			'status_msg'		=> 'Settings disabled',
		);

		$logger = wc_get_logger();
		
		if ( ! $enable || ! $for_amazon_order || '0' == $receive_email ) {
			$logger->info( print_r($arg, true), array( 'source' => 'trackship_email_log' ) );
			return;
		}
		
		if ( 'delivered_status' == $status ) {
			$toggle = get_option( 'all-shipment-status-delivered' );
			$all_delivered = trackship_for_woocommerce()->ts_actions->is_all_shipments_delivered( $order_id );
			
			if ( $toggle && !$all_delivered ) {
				$logger->info( print_r($arg, true), array( 'source' => 'trackship_email_log' ) );
				return;
			}
		}

		global $sitepress;
		if ( $sitepress ) {
			$old_lan = $sitepress->get_current_language();
			$new_lan = $order->get_meta( 'wpml_language', true );
			$sitepress->switch_lang($new_lan);
		}

		$default = trackship_admin_customizer()->wcast_shipment_settings_defaults( $status );

		$email_to = [];
		$email_to[] = $order ? $order->get_billing_email() : '';
		$email_to = apply_filters( 'add_multiple_emails_to_shipment_email', $email_to, $new_status );

		$email_subject = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'wcast_' . $status . '_email_settings', 'wcast_' . $status . '_email_subject', $default['wcast_' . $status . '_email_subject']);
		$email_heading = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_' . $status . '_email_settings', 'wcast_' . $status . '_email_heading', $default['wcast_' . $status . '_email_heading']);

		$email_content = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_' . $status . '_email_settings', 'wcast_' . $status . '_email_content', $default['wcast_' . $status . '_email_content']);
		$email_content = html_entity_decode( $email_content );
		
		$wcast_show_order_details = trackship_for_woocommerce()->ts_actions->get_checkbox_option_value_from_array( 'wcast_' . $status . '_email_settings', 'wcast_' . $status . '_show_order_details', $default['wcast_' . $status . '_show_order_details'] );
		
		$wcast_show_product_image = trackship_for_woocommerce()->ts_actions->get_checkbox_option_value_from_array( 'wcast_' . $status . '_email_settings', 'wcast_' . $status . '_show_product_image', $default['wcast_' . $status . '_show_product_image']);

		$wcast_show_shipping_address = trackship_for_woocommerce()->ts_actions->get_checkbox_option_value_from_array( 'wcast_' . $status . '_email_settings', 'wcast_' . $status . '_show_shipping_address', $default['wcast_' . $status . '_show_shipping_address']);
		
		$sent_to_admin = false;
		$plain_text = false;

		$recipients = $this->email_to($email_to, $order, $order_id);
		
		$subject = $this->email_subject($email_subject, $order_id, $order);

		$email_content = $this->email_content($email_content, $order_id, $order);
		
		$email_heading = $this->email_heading($email_heading, $order_id, $order);
		$message = $this->append_analytics_link($email_content, $status);

		$message .= $this->get_tracking_info_template( $tracking_item, $shipment_row, $order_id, $new_status );

		$tpi_order = false;
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );
		$tpi_order = trackship_for_woocommerce()->front->check_if_tpi_order( $tracking_items, $order );
		
		if ( $tpi_order ) {
			$message .= $this->get_order_details_template( 'emails/tswc-tpi-email-order-details.php', $order, $sent_to_admin, $plain_text, $tracking_item, $wcast_show_product_image, $wcast_show_order_details );
		} else {
			$message .= $this->get_order_details_template( 'emails/tswc-email-order-details.php', $order, $sent_to_admin, $plain_text, $tracking_item, $wcast_show_product_image, $wcast_show_order_details );
		}

		$message.= wc_get_template_html(
			'emails/shipping-email-addresses.php', array(
				'order' => $order,
				'sent_to_admin' => $sent_to_admin,
				'ts4wc_preview' => false,
				'wcast_show_shipping_address' => $wcast_show_shipping_address,
			),
			'woocommerce-advanced-shipment-tracking/', 
			trackship_for_woocommerce()->get_plugin_path() . '/templates/'
		);

		// create a new email
		$mailer = WC()->mailer();
		$email_class = new WC_Email();
		$email_class->id = 'shipment_email';
	
		add_filter( 'safe_style_css', array( $this, 'safe_style_css' ), 10, 1 );
		add_filter( 'woocommerce_email_footer_text', array( $this, 'email_footer_text' ) );

		// wrap the content with the email template and then add styles
		$message = $mailer->wrap_message( $email_heading, $message );
		$message = apply_filters( 'trackship_mail_content', $message, $email_heading );

		foreach ( $recipients as $recipient ) {
			$email_send = $email_class->send( $recipient, $subject, $message, $email_class->get_headers(), [] );
			$arg = array(
				'order_id'			=> $order_id,
				'order_number'		=> wc_get_order( $order_id )->get_order_number(),
				'user_id'			=> wc_get_order( $order_id )->get_user_id(),
				'tracking_number'	=> $tracking_item['tracking_number'],
				'date'				=> current_time( 'Y-m-d H:i:s' ),
				'to'				=> $recipient,
				'shipment_status'	=> $new_status,
				'status'			=> $email_send,
				'status_msg'		=> $email_send ? 'Sent' : 'Not Sent',
				'type'				=> 'Email',
			);
			trackship_for_woocommerce()->ts_actions->update_notification_table( $arg );
		}

		if ( $sitepress ) {
			$sitepress->switch_lang($old_lan);
		}
	}

	public function trigger_pickup_reminder_email( $order_id, $old_status, $new_status, $tracking_number ) {
		$tracking_items = trackship_for_woocommerce()->get_tracking_items( $order_id );

		foreach ( ( array ) $tracking_items as $key => $tracking_item ) {
			if ( trim( $tracking_item['tracking_number'] ) != trim($tracking_number) ) {
				continue;
			}
			$shipment_row = trackship_for_woocommerce()->actions->get_shipment_row( $order_id , $tracking_item['tracking_number'] );
			$order = wc_get_order( $order_id );
			$this->order = $order;
			$this->shipment_row = $shipment_row;
			$this->tracking_item = $tracking_item;
			$this->tracking_number = $tracking_item['tracking_number'];
	
			$enable = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_pickupreminder_email_settings', 'wcast_enable_pickupreminder_email', '');
	
			$arg = array(
				'order_id'			=> $order_id,
				'order_number'		=> wc_get_order( $order_id )->get_order_number(),
				'tracking_number'	=> $tracking_item['tracking_number'],
				'date'				=> current_time( 'Y-m-d H:i:s' ),
				'shipment_status'	=> 'pickup_reminder',
				'status_msg'		=> 'available_for_pickup' != $shipment_row->shipment_status ? 'Shipment is not available for pickup.' : 'Settings disabled',
			);
	
			$logger = wc_get_logger();
			if ( ! $enable || 'available_for_pickup' != $shipment_row->shipment_status ) {
				$logger->info( print_r($arg, true), array( 'source' => 'trackship_email_log' ) );
				return;
			}
	
			global $sitepress;
			if ( $sitepress ) {
				$old_lan = $sitepress->get_current_language();
				$new_lan = $order->get_meta( 'wpml_language', true );
				$sitepress->switch_lang($new_lan);
			}
	
			$default = trackship_admin_customizer()->wcast_shipment_settings_defaults( 'pickupreminder' );
	
			$email_to = [];
			$email_to[] = $order ? $order->get_billing_email() : '';
			$email_to = apply_filters( 'add_multiple_emails_to_shipment_email', $email_to, $new_status );
	
			$email_subject = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'wcast_pickupreminder_email_settings', 'wcast_pickupreminder_email_subject', $default['wcast_pickupreminder_email_subject']);
			$email_heading = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_pickupreminder_email_settings', 'wcast_pickupreminder_email_heading', $default['wcast_pickupreminder_email_heading']);	
		
			$email_content = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_pickupreminder_email_settings', 'wcast_pickupreminder_email_content', $default['wcast_pickupreminder_email_content']);
			$email_content = html_entity_decode( $email_content );
			
			$wcast_show_order_details = trackship_for_woocommerce()->ts_actions->get_checkbox_option_value_from_array('wcast_pickupreminder_email_settings', 'wcast_pickupreminder_show_order_details', $default['wcast_pickupreminder_show_order_details']);
			
			$wcast_show_product_image = trackship_for_woocommerce()->ts_actions->get_checkbox_option_value_from_array('wcast_pickupreminder_email_settings', 'wcast_pickupreminder_show_product_image', $default['wcast_pickupreminder_show_product_image']);
			
			$sent_to_admin = false;
			$plain_text = false;
	
			$recipients = $this->email_to($email_to, $order, $order_id);
			
			$subject = $this->email_subject($email_subject, $order_id, $order);
	
			$email_content = $this->email_content($email_content, $order_id, $order);
			
			$email_heading = $this->email_heading($email_heading, $order_id, $order);
			$message = $this->append_analytics_link($email_content, 'pickupreminder');

			$message .= $this->get_tracking_info_template( $tracking_item, $shipment_row, $order_id, $new_status );

			$tpi_order = false;
			$tpi_order = trackship_for_woocommerce()->front->check_if_tpi_order( $tracking_items, $order );
			
			if ( $tpi_order ) {
				$message .= $this->get_order_details_template( 'emails/tswc-tpi-email-order-details.php', $order, $sent_to_admin, $plain_text, $tracking_item, $wcast_show_product_image, $wcast_show_order_details );
			} else {
				$message .= $this->get_order_details_template( 'emails/tswc-email-order-details.php', $order, $sent_to_admin, $plain_text, $tracking_item, $wcast_show_product_image, $wcast_show_order_details );
			}
	
			// create a new email
			$mailer = WC()->mailer();
			$email_class = new WC_Email();
			$email_class->id = 'pickup_reminder';

			add_filter( 'safe_style_css', array( $this, 'safe_style_css' ), 10, 1 );
			// wrap the content with the email template and then add styles
			$message = $mailer->wrap_message( $email_heading, $message );
			$message = apply_filters( 'trackship_mail_content', $message, $email_heading );
	
			$email_send = $email_class->send( implode(', ', $recipients), $subject, $message, $email_class->get_headers(), [] );
			$arg = array(
				'order_id'			=> $order_id,
				'order_number'		=> wc_get_order( $order_id )->get_order_number(),
				'user_id'			=> wc_get_order( $order_id )->get_user_id(),
				'tracking_number'	=> $tracking_item['tracking_number'],
				'date'				=> current_time( 'Y-m-d H:i:s' ),
				'to'				=> implode(', ', $recipients),
				'shipment_status'	=> 'pickup_reminder',
				'status'			=> $email_send,
				'status_msg'		=> $email_send ? 'Sent' : 'Not Sent',
				'type'				=> 'Email',
			);
			trackship_for_woocommerce()->ts_actions->update_notification_table( $arg );
	
			if ( $sitepress ) {
				$sitepress->switch_lang($old_lan);
			}
			break;
		}

	}

	public function get_tracking_info_template( $tracking_item, $shipment_row, $order_id, $new_status ) {
		$message = wc_get_template_html( 'emails/tracking-info.php', array( 
			'tracking_items' => array($tracking_item),
			'shipment_row' => $shipment_row,
			'order_id' => $order_id,
			'show_shipment_status' => false,
			'new_status' => $new_status,
			'ts4wc_preview' => false,
		), 'woocommerce-advanced-shipment-tracking/', trackship_for_woocommerce()->get_plugin_path() . '/templates/' );
		return $message;
	}

	public function get_order_details_template( $template_path, $order, $sent_to_admin, $plain_text, $tracking_item, $wcast_show_product_image, $wcast_show_order_details ) {
		$message = wc_get_template_html(
			$template_path,
			array(
				'order'			=> $order,
				'sent_to_admin' => $sent_to_admin,
				'plain_text'	=> $plain_text,
				'tracking_items'=> array($tracking_item),
				'email'			=> '',
				'wcast_show_product_image' => $wcast_show_product_image,
				'wcast_show_order_details' => $wcast_show_order_details,
				'ts4wc_preview' => false,
			),
			'woocommerce-advanced-shipment-tracking/', 
			trackship_for_woocommerce()->get_plugin_path() . '/templates/'
		);
		return $message;
	}

	/**
	 * Code for format email subject
	*/
	public function email_footer_text( $footer_text ) {
		$unsubscribe = '';
		if ( get_trackship_settings( 'enable_email_widget' ) ) {
			$tracking_item = isset( $this->tracking_item ) && $this->tracking_item ? $this->tracking_item : [];
			$track_link = $tracking_item['tracking_page_link'] ? $tracking_item['tracking_page_link'] : $this->order->get_view_order_url();
			$track_link = add_query_arg( array( 'unsubscribe' => 'true' ), $track_link );
			$unsubscribe = '<div style="text-align:center;padding-bottom: 10px;"><a href="' . $track_link . '">' . esc_html__( 'Unsubscribe', 'trackship-for-woocommerce' ) . '</a></div>';
		}

		$footer_text = $unsubscribe ? $unsubscribe : $footer_text;
		return $footer_text;
	}

	public function safe_style_css ( $styles ) {
		$styles[] = 'display';
		return $styles;
	}

	/**
	 * Code for format email subject
	*/
	public function email_subject( $string, $order_id, $order ) {
		$customer_email = $order->get_billing_email();
		$first_name = $order->get_billing_first_name();
		$last_name = $order->get_billing_last_name();
		$user = $order->get_user();
		if ( $user ) {
			$username = $user->user_login;
		}
		$string = str_replace( '{order_number}', $order->get_order_number(), $string );
		$string = str_replace( '{customer_email}', $customer_email, $string );
		$string = str_replace( '{customer_first_name}', $first_name, $string );
		$string = str_replace( '{customer_last_name}', $last_name, $string );
		if ( isset( $username ) ) {
			$string = str_replace( '{customer_username}', $username, $string );
		} else {
			$string = str_replace( '{customer_username}', '', $string );
		}
		$string = str_replace( '{site_title}', $this->get_blogname(), $string );
		return $string;
	} 

	/**
	 * Code for format email heading
	 */	
	public function email_heading( $string, $order_id, $order ) {
		$customer_email = $order->get_billing_email();
		$first_name = $order->get_billing_first_name();
		$last_name = $order->get_billing_last_name();
		$user = $order->get_user();
		if ( $user ) {
			$username = $user->user_login;
		}
		$string = str_replace( '{order_number}', $order->get_order_number(), $string );
		$string = str_replace( '{customer_email}', $customer_email, $string );
		$string = str_replace( '{customer_first_name}', $first_name, $string );
		$string = str_replace( '{customer_last_name}', $last_name, $string );
		if ( isset( $username ) ) {
			$string = str_replace( '{customer_username}', $username, $string );
		} else {
			$string = str_replace( '{customer_username}', '', $string );
		}
		$string = str_replace( '{site_title}', $this->get_blogname(), $string );
		return $string;
	} 
	
	/**
	 * Code for format recipients 
	 */	
	public function email_to( $string, $order, $order_id ) {
		$customer_email = $order ? $order->get_billing_email() : '';
		$admin_email = get_option('admin_email');
		$string = str_replace( '{admin_email}', $admin_email, $string );
		$string = str_replace( '{customer_email}', $customer_email, $string );
		return $string;
	} 
	
	/**
	 * Code for format email content 
	 */
	public function email_content( $email_content, $order_id, $order ) {
		$customer_email = $order->get_billing_email();
		$first_name = $order->get_billing_first_name();
		$last_name = $order->get_billing_last_name();
		$company_name = $order->get_billing_company();
		$user = $order->get_user();
		if ( $user ) {
			$username = $user->user_login;
		}
		
		$trackship_apikey = is_trackship_connected();
		if ( $trackship_apikey ) {
			$est_delivery_date = $this->get_est_delivery_date($order_id, $order);
		}
		
		$email_content = str_replace( '{customer_email}', $customer_email, $email_content );
		$email_content = str_replace( '{site_title}', $this->get_blogname(), $email_content );
		$email_content = str_replace( '{customer_first_name}', $first_name, $email_content );
		$email_content = str_replace( '{customer_last_name}', $last_name, $email_content );
		
		if ( isset( $company_name ) ) {
			$email_content = str_replace( '{customer_company_name}', $company_name, $email_content );
		} else {
			$email_content = str_replace( '{customer_company_name}', '', $email_content );
		}	 
		
		if ( isset( $username ) ) {
			$email_content = str_replace( '{customer_username}', $username, $email_content );
		} else {
			$email_content = str_replace( '{customer_username}', '', $email_content );
		}
		$email_content = str_replace( '{order_number}', $order->get_order_number(), $email_content );
		if ( $trackship_apikey ) {
			$email_content = str_replace( '{est_delivery_date}', $est_delivery_date, $email_content );
		}
		return '<div class="shipment_email_content">' . $email_content . '</div>';
	}
	
	/**
	 * Code for append analytics link
	 */
	public function append_analytics_link( $message, $status ) {
		if ( 'delivered_status' == $status ) {
			$analytics_link = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'wcast_delivered_status_email_settings', 'wcast_delivered_status_analytics_link', '' );
		} else {
			$analytics_link = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'wcast_' . $status . '_email_settings', 'wcast_' . $status . '_analytics_link', '' );
		}
	
		if ( $analytics_link ) {
			$regex = '#(<a href=")([^"]*)("[^>]*?>)#i';
			$message = preg_replace_callback( $regex, function ( $match ) use ( $status ) {
				$url = $match[2];
				if ( strpos($url, '?') === false ) {
					$url .= '?';
				}
				$url .= $analytics_link;
				return $match[1] . $url . $match[3];
			}, $message);
		}
		return $message;
	}	

	/**
	 * Code for get estimate delivery date
	 */
	public function get_est_delivery_date( $order_id, $order ) {
		
		$row = isset( $this->shipment_row ) && $this->shipment_row ? $this->shipment_row : (object) [];
		$est_delivery_date = isset( $row->est_delivery_date ) ? $row->est_delivery_date : '';

		return $est_delivery_date ? date_i18n( 'l, M d', strtotime( $est_delivery_date ) ) : 'Not Available';
	}
	
	/**
	 * Get blog name formatted for emails.
	 *
	 * @return string
	 */
	public function get_blogname() {
		return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}
}

function WC_TrackShip_Email_Manager() {
	static $instance;

	if ( ! isset( $instance ) ) {
		$instance = new WC_TrackShip_Email_Manager();
	}

	return $instance;
}
WC_TrackShip_Email_Manager();
