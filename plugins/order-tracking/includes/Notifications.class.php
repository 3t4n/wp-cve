<?php

/**
 * Class to handle sending notifications when an order is submitted or updated
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdotpNotifications' ) ) {
class ewdotpNotifications {

	public function __construct() {
		
		add_action( 'ewd_otp_customer_note_updated', 	array( $this, 'admin_customer_note_email' ) );
		add_action( 'ewd_otp_insert_customer_order', 	array( $this, 'admin_customer_order_email' ) );
		add_action( 'ewd_otp_insert_customer_order', 	array( $this, 'user_order_created_notification' ) );

		add_action( 'ewd_otp_admin_order_inserted', 	array( $this, 'user_order_created_notification' ) );
		add_action( 'ewd_otp_admin_order_updated', 		array( $this, 'user_status_updated_notification' ), 10, 2 );
		add_action( 'ewd_otp_status_updated', 			array( $this, 'user_status_updated_notification' ), 10, 2 );

		// Sales rep notifications
		add_action( 'ewd_otp_insert_customer_order', 	array( $this, 'sales_rep_status_updated_notification' ) );
		add_action( 'ewd_otp_admin_order_updated', 		array( $this, 'sales_rep_status_updated_notification' ) );
	}


	/**
	 * Send an email to the site admin when an order's customer note is updated, if selected
	 *
	 * @since 3.0.0
	 */
	public function admin_customer_note_email( $order ) {
		global $ewd_otp_controller;

		$notification_id = $ewd_otp_controller->settings->get_setting( 'customer-notes-email' );

		if ( ! $notification_id ) { return; }

		$sms = ! is_numeric( $notification_id ) ? true : false;

		$recipient = $sms ? $ewd_otp_controller->settings->get_setting( 'admin-phone-number' ) : $ewd_otp_controller->settings->get_setting( 'admin-email' );
	
		if ( $sms ) { 

			$this->send_text( $notification_id, $recipient, $order );
		}
		elseif ( $notification_id < 0 ) {

			$args = array(
				'email_id'			=> $notification_id * -1,
				'order_id'			=> $order->id,
				'email_address'		=> $recipient
			);

			if ( function_exists( 'ewd_uwpm_send_email' ) ) { ewd_uwpm_send_email( $args ); }
		}
		else {

			$this->send_email( $notification_id, $recipient, $order );
		}
	}

	/**
	 * Send an email to the site admin when a customer order is submitted, if selected
	 *
	 * @since 3.0.0
	 */
	public function admin_customer_order_email( $order ) {
		global $ewd_otp_controller;

		$notification_id = $ewd_otp_controller->settings->get_setting( 'customer-order-email' );

		if ( ! $notification_id ) { return; }

		$sms = ! is_numeric( $notification_id ) ? true : false;

		$recipient = $sms ? $ewd_otp_controller->settings->get_setting( 'admin-phone-number' ) : $ewd_otp_controller->settings->get_setting( 'admin-email' );
	
		if ( $sms ) { 

			$this->send_text( $notification_id, $recipient, $order );
		}
		elseif ( $notification_id < 0 ) {

			$args = array(
				'email_id'			=> $notification_id * -1,
				'order_id'			=> $order->id,
				'email_address'		=> $recipient
			);

			if ( function_exists( 'ewd_uwpm_send_email' ) ) { ewd_uwpm_send_email( $args ); }
		}
		else {

			$this->send_email( $notification_id, $recipient, $order );
		}
	}

	/**
	 * Send an email to the client when an order is created, if selected
	 *
	 * @since 3.0.0
	 */
	public function user_order_created_notification( $order ) {
		global $ewd_otp_controller;

		if ( $ewd_otp_controller->settings->get_setting( 'email-frequency' ) == 'never' ) { return; }

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		foreach ( $statuses as $status ) {

			if ( $status->status == $order->status ) { $notification_id = $status->email; }
		}

		if ( empty( $notification_id ) ) { return; }

		$sms = ! is_numeric( $notification_id ) ? true : false;

		$recipients = $sms ? explode( ',', $order->phone_number ) : explode( ',', $order->email );

		if ( empty( $recipients ) ) { return; }

		foreach ( $recipients as $recipient ) {
			
			if ( $sms ) {

				$this->send_text( $notification_id, $recipient, $order );
			}
			elseif ( $notification_id < 0 ) {
	
				$args = array(
					'email_id'			=> $notification_id * -1,
					'order_id'			=> $order->id,
					'email_address'		=> $recipient
				);
	
				if ( function_exists( 'ewd_uwpm_send_email' ) ) { ewd_uwpm_send_email( $args ); }
			}
			else {
	
				$this->send_email( $notification_id, $recipient, $order );
			}
		}
	}

	/**
	 * Send an email to the client when an order status is changed, if selected
	 *
	 * @since 3.0.0
	 */
	public function user_status_updated_notification( $order, $old_status ) {
		global $ewd_otp_controller;

		if ( $ewd_otp_controller->settings->get_setting( 'email-frequency' ) == 'never' or $ewd_otp_controller->settings->get_setting( 'email-frequency' ) == 'creation' ) { return; }

		if ( $ewd_otp_controller->settings->get_setting( 'email-frequency' ) == 'status_change' and $order->status == $old_status ) { return; }

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		foreach ( $statuses as $status ) {

			if ( $status->status == $order->status and $status->internal != 'yes' ) { $notification_id = $status->email; }
		}

		if ( empty( $notification_id ) ) { return; }

		$sms = ! is_numeric( $notification_id ) ? true : false;

		$recipients = $sms ? explode( ',', $order->phone_number ) : explode( ',', $order->email );

		foreach ( $recipients as $recipient ) {
		
			if ( $sms ) {

				$this->send_text( $notification_id, $recipient, $order );
			}
			elseif ( $notification_id < 0 ) {
	
				$args = array(
					'email_id'			=> $notification_id * -1,
					'order_id'			=> $order->id,
					'email_address'		=> $recipient
				);
	
				if ( function_exists( 'ewd_uwpm_send_email' ) ) { ewd_uwpm_send_email( $args ); }
			}
			else {
				
				$this->send_email( $notification_id, $recipient, $order );
			}
		}
	}

	/**
	 * Send an email to the sales rep when an order status is changed, if selected
	 *
	 * @since 3.0.0
	 */
	public function sales_rep_status_updated_notification( $order ) {
		global $ewd_otp_controller;

		if ( empty( $ewd_otp_controller->settings->get_setting( 'sales-rep-status-notifications' ) ) ) { return; }

		$notification_id = $ewd_otp_controller->settings->get_setting( 'sales-rep-status-email' );

		if ( empty( $notification_id ) ) { return; }

		if ( empty( $order->sales_rep ) ) { return; }

		$sales_rep = new ewdotpSalesRep();

		$sales_rep->load_sales_rep_from_id( $order->sales_rep );

		$sms = ! is_numeric( $notification_id ) ? true : false;

		if ( ( ! $sms and empty( $sales_rep->email ) ) or ( $sms and empty( $sales_rep->phone_number ) ) ) { return; }

		if ( $sms ) {

			$this->send_text( $notification_id, $sales_rep->phone_number, $order );
		}
		elseif ( $notification_id < 0 ) {
	
			$args = array(
				'email_id'			=> $notification_id * -1,
				'order_id'			=> $order->id,
				'email_address'		=> $sales_rep->email
			);
	
			if ( function_exists( 'ewd_uwpm_send_email' ) ) { ewd_uwpm_send_email( $args ); }
		}
		else {
				
			$this->send_email( $notification_id, $sales_rep->email, $order );
		}
	}

	/**
	 * Send an email using an admin created template
	 *
	 * @since 3.0.0
	 */
	public function send_email( $email_id, $email_address, $order ) {
		global $ewd_otp_controller;

		$email_messages = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'email-messages' ) );

		foreach ( $email_messages as $email_message ) {

			if ( $email_message->id != $email_id ) { continue; }

			$message = $this->substitute_message_text( $this->get_email_template( $email_message ), $order );
			$subject = $this->substitute_message_text( $email_message->subject, $order);
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			
			$mail_success = wp_mail( $email_address, $subject, $message, $headers );

			return $mail_success;
		}
	}

	/**
	 * Send a text message using an admin created template
	 *
	 * @since 3.3.0
	 */
	public function send_text( $notification_id, $phone_number, $order ) {
		global $ewd_otp_controller;

		$sms = ! is_numeric( $notification_id ) ? true : false;

		// remove the 'sms_' prefix from the notification ID, if it exists
		$notification_id = $sms ? substr( $notification_id, 4 ) : $notification_id;

		$sms_messages = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'sms-messages' ) );

		foreach ( $sms_messages as $sms_message ) {

			if ( $sms_message->id != $notification_id ) { continue; }

			// replace tracking link ahead of time with URL, to prevent button code being inserted
			$message = str_replace( '[tracking-link]', $this->get_order_tracking_link( $order ), strip_tags( $sms_message->message ) );

			$url = add_query_arg(
				array(
					'plugin'				=> 'otp',
					'license_key' 	=> urlencode( get_option( 'otp-ultimate-license-key', 'no license key entered' ) ),
					'admin_email' 	=> urlencode( $ewd_otp_controller->settings->get_setting( 'ultimate-purchase-email' ) ),
					'phone_number' 	=> urlencode( $phone_number ),
					'message'		=> urlencode( $this->substitute_message_text( $message, $order ) ),
					'country_code'	=> urlencode( $ewd_otp_controller->settings->get_setting( 'sms-country-code' ) )
				),
				'http://www.etoilewebdesign.com/sms-handling/sms-client.php'
			);

			$opts = array( 'http' =>array( 'method' => "GET" ) );
			$context = stream_context_create( $opts );
			$return = json_decode( file_get_contents( $url, false, $context ) );

			return isset( $return->success ) ? $return->success : false;
		}
	}

	/**
	 * Replace plugin-defined tags with order information
	 *
	 * @since 3.0.0
	 */
	function substitute_message_text( $text, $order ) {
		global $ewd_otp_controller;
	
		$tracking_url = $this->get_order_tracking_link( $order );
	
		$tracking_link = "[button link='" . $tracking_url . "']" . __( 'Track your order', 'order-tracking' ) . "[/button]";
	
		date_default_timezone_set( get_option( 'timezone_string' ) );
	
		$search = array(
			"[order-name]",
			"[order-number]",
			"[order-status]",
			"[order-notes]",
			"[customer-notes]",
			"[order-time]",
			"[tracking-link]",
			"[customer-name]",
			"[customer-number]",
			"[customer-id]",
			"[sales-rep]",
			"[sales-rep-number]"
		);
	
		$replace = array(
			! empty( $order->name ) ? $order->name : '',
			! empty( $order->number ) ? $order->number : '', 
			! empty( $order->external_status ) ? $order->external_status : '',
			! empty( $order->notes_public ) ? $order->notes_public : '',
			! empty( $order->customer_notes ) ? $order->customer_notes : '',
			date( $ewd_otp_controller->settings->get_setting( 'date-format' ), strtotime( $order->status_updated ) ),
			$tracking_link,
			$ewd_otp_controller->customer_manager->get_customer_field( 'name', $order->customer ),
			$ewd_otp_controller->customer_manager->get_customer_field( 'number', $order->customer ),
			! empty( $order->customer ) ? $order->customer : '',
			$ewd_otp_controller->sales_rep_manager->get_sales_rep_field( 'first_name', $order->sales_rep ) . ' ' . $ewd_otp_controller->sales_rep_manager->get_sales_rep_field( 'last_name', $order->sales_rep ),
			$ewd_otp_controller->sales_rep_manager->get_sales_rep_field( 'number', $order->sales_rep ),
		);
	
		$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();
	
		foreach ( $custom_fields as $custom_field ) {

			$value = $ewd_otp_controller->order_manager->get_field_value( $custom_field->id, $order->id );
		
			$search[] = '[' . $custom_field->slug . ']';
			$replace[] = $value;
		}
	
		$order_text = str_replace( $search, $replace, $text );
	
		return $this->replace_email_content( $order_text );
	}

	/**
	 * Returns the URL code tracking an individual order
	 *
	 * @since 3.0.0
	 */
	public function get_order_tracking_link( $order ) {
		global $ewd_otp_controller;

		$confirmation_code = ewd_random_string();

		$order->set_tracking_link_code( $confirmation_code );

		$args = array(
			'tracking_number'			=> $order->number,
			'email'								=> $order->email,
			'tracking_link_code'	=> $confirmation_code
		);
	
		return add_query_arg( $args, $ewd_otp_controller->settings->get_setting( 'tracking-page-url' ) );
	}

	/**
	 * Returns a template of the email message, along with admin styling for it
	 *
	 * @since 3.0.0
	 */
	public function get_email_template( $email_message ) {
		global $ewd_otp_controller;

		$message_title = $email_message->subject;
		$message_content = $this->replace_email_content( stripslashes( $email_message->message ) );
	
		$message =   <<< EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>$message_title</title>
	
	
<style type="text/css">
	
img {
max-width: 100%;
}
body {
-webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;
}
body {
background-color: #f6f6f6;
}

@media only screen and (max-width: 640px) {
body {
  padding: 0 !important;
}
h1 {
  font-weight: 800 !important; margin: 20px 0 5px !important;
}
h2 {
  font-weight: 800 !important; margin: 20px 0 5px !important;
}
h3 {
  font-weight: 800 !important; margin: 20px 0 5px !important;
}
h4 {
  font-weight: 800 !important; margin: 20px 0 5px !important;
}
h1 {
  font-size: 22px !important;
}
h2 {
  font-size: 18px !important;
}
h3 {
  font-size: 16px !important;
}
.container {
  padding: 0 !important; width: 100% !important;
}
.content {
  padding: 0 !important;
}
.content-wrap {
  padding: 10px !important;
}
.invoice {
  width: 100% !important;
}
}
</style>
</head>
	
<body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
	
<table class="body-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
<td class="container" width="600" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
<div class="content" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
<meta itemprop="name" content="Please Review" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" /><table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
$message_content
</div>
</td>
<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
</tr></table></body>
</html>
	
EOT;
	
	  return $message;
	}

	/**
	 * Replace the structure elements of an email template
	 *
	 * @since 3.0.0
	 */
	public function replace_email_content( $unprocessed_message ) {

		$search = array('[section]', '[/section]', '[footer]', '[/footer]', '[/button]');
		$replace = array(
			'<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">',
			'</td></tr>',
			'</table></td></tr></table><div class="footer" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;"><table width="100%" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="aligncenter content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">',
			'</td></tr></table></div>',
			'</a></td></tr>'
		);
		$intemediate_message = str_replace( $search, $replace, $unprocessed_message );
		$processed_message = $this->replace_email_links( $intemediate_message );

  		return $processed_message;
	}

	/**
	 * Replace all of the button links used in the email template
	 *
	 * @since 3.0.0
	 */
	public function replace_email_links( $unprocessed_message ) {
	
		$pattern = "/\[button link=\'(.*?)\'\]/";
	
		preg_match_all( $pattern, $unprocessed_message, $matches );
	
		$replace = '<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"><a href="INSERTED_LINK" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">';
		$message = preg_replace( $pattern, $replace, $unprocessed_message );
	
		if ( is_array( $matches[1] ) ) {
	
			foreach ( $matches[1] as $link ) {
	
				$pos = strpos( $message, "INSERTED_LINK" );
	
				if ($pos !== false) {
	
				    $intermediate_message = substr_replace( $message, $link, $pos, 13 );
				    $message = $intermediate_message;
				}
			}
		}
	
		return $message;
	}
}
} // endif;

