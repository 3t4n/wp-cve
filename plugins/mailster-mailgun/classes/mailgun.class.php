<?php

class MailsterMailgun {

	private $plugin_path;
	private $plugin_url;

	/**
	 *
	 */
	public function __construct() {

		$this->plugin_path = plugin_dir_path( MAILSTER_MAILGUN_FILE );
		$this->plugin_url  = plugin_dir_url( MAILSTER_MAILGUN_FILE );

		register_activation_hook( MAILSTER_MAILGUN_FILE, array( &$this, 'activate' ) );
		register_deactivation_hook( MAILSTER_MAILGUN_FILE, array( &$this, 'deactivate' ) );

		load_plugin_textdomain( 'mailster-mailgun' );

		add_action( 'init', array( &$this, 'init' ), 1 );
	}


	/*
	 * init the plugin
	 *
	 * @access public
	 * @return void
	 */
	public function init() {

		if ( ! function_exists( 'mailster' ) ) {

			add_action( 'admin_notices', array( &$this, 'notice' ) );

		} else {

			add_filter( 'mailster_delivery_methods', array( &$this, 'delivery_method' ) );
			add_action( 'mailster_deliverymethod_tab_mailgun', array( &$this, 'deliverytab' ) );

			add_filter( 'mailster_verify_options', array( &$this, 'verify_options' ) );

			if ( mailster_option( 'deliverymethod' ) == 'mailgun' ) {
				add_action( 'mailster_initsend', array( &$this, 'initsend' ) );
				add_action( 'mailster_presend', array( &$this, 'presend' ) );
				add_action( 'mailster_dosend', array( &$this, 'dosend' ) );
				add_action( 'mailster_cron_bounce', array( &$this, 'check_bounces' ) );
				add_action( 'mailster_check_bounces', array( &$this, 'check_bounces' ) );
				add_action( 'mailster_section_tab_bounce', array( &$this, 'section_tab_bounce' ) );
				add_filter( 'mailster_subscriber_errors', array( $this, 'subscriber_errors' ) );
			}
		}

	}


	/**
	 * initsend function.
	 *
	 * uses mailster_initsend hook to set initial settings
	 *
	 * @access public
	 * @return void
	 * @param mixed $mailobject
	 */
	public function initsend( $mailobject ) {

		$method = mailster_option( 'mailgun_api' );

		if ( $method == 'smtp' ) {

			$username = mailster_option( 'mailgun_smtp_login' ) . '@' . mailster_option( 'mailgun_domain' );
			$port     = mailster_option( 'mailgun_smtp_port' );
			$password = mailster_option( 'mailgun_smtp_password' );

			$mailobject->mailer->Mailer        = 'smtp';
			$mailobject->mailer->SMTPSecure    = $port == 465 ? 'ssl' : 'tls';
			$mailobject->mailer->Host          = mailster_option( 'mailgun_endpoint' ) ? 'smtp.eu.mailgun.org' : 'smtp.mailgun.org';
			$mailobject->mailer->Port          = $port;
			$mailobject->mailer->SMTPAuth      = 'LOGIN';
			$mailobject->mailer->Username      = $username;
			$mailobject->mailer->Password      = $password;
			$mailobject->mailer->SMTPKeepAlive = true;

		} else {

		}

		// Mailgun will handle DKIM integration
		$mailobject->dkim = false;

	}


	/**
	 * presend function.
	 *
	 * uses the mailster_presend hook to apply settings before each mail
	 *
	 * @access public
	 * @return void
	 * @param mixed $mailobject
	 */
	public function presend( $mailobject ) {

		$method = mailster_option( 'mailgun_api' );

		$mailobject->pre_send();

		$mailobject->mailgun_object = array();

		if ( $tracking_options = mailster_option( 'mailgun_track' ) ) {
			$open_tracking  = 'opens' == $tracking_options || 'opens,clicks' == $tracking_options;
			$click_tracking = 'clicks' == $tracking_options || 'opens,clicks' == $tracking_options;
		}

		$data = array(
			'mailster_id'   => mailster_option( 'ID' ),
			'campaign_id'   => (string) $mailobject->campaignID,
			'index'         => (string) $mailobject->index,
			'subscriber_id' => (string) $mailobject->subscriberID,
		);

		$data = json_encode( $data );
		$data = base64_encode( $data );

		if ( $tags = mailster_option( 'mailgun_tags', '' ) ) {
			$tags = array_map( 'trim', explode( ',', $tags ) );
		}

		if ( $method == 'smtp' ) {

			if ( $tags ) {
				foreach ( $tags as $tag ) {
					$mailobject->mailer->addCustomHeader( 'X-Mailgun-Tag: ' . $tag );
				}
			}

			if ( $tracking_options ) {
				$mailobject->mailer->addCustomHeader( 'X-Mailgun-Track: ' . ( ( $open_tracking || $click_tracking ) ? 'yes' : 'no' ) );
				$mailobject->mailer->addCustomHeader( 'X-Mailgun-Track-Clicks: ' . ( $click_tracking ? 'yes' : 'no' ) );
				$mailobject->mailer->addCustomHeader( 'X-Mailgun-Track-Opens: ' . ( $open_tracking ? 'yes' : 'no' ) );
			}

			$mailobject->mailer->addCustomHeader( 'X-Mailgun-Variables: ' . json_encode( array( 'Mailster' => $data ) ) );

		} else {

			$recipients = '';

			foreach ( $mailobject->to as $i => $to ) {
				$recipients .= ( $mailobject->to_name[ $i ] ? $mailobject->to_name[ $i ] . ' ' : '' ) . '<' . ( $mailobject->to[ $i ] ? $mailobject->to[ $i ] : null ) . '>';
			}

			if ( $tags ) {
				$mailobject->mailgun_object['o:tag'] = $tags;
			}

			if ( $tracking_options ) {
				$mailobject->mailgun_object['o:tracking']        = ( $open_tracking || $click_tracking ) ? 'yes' : 'no';
				$mailobject->mailgun_object['o:tracking-opens']  = $open_tracking ? 'yes' : 'no';
				$mailobject->mailgun_object['o:tracking-clicks'] = $click_tracking ? 'yes' : 'no';
			}

			$mailobject->mailgun_object['from']       = $mailobject->from_name . ' <' . $mailobject->from . '>';
			$mailobject->mailgun_object['to']         = $recipients;
			$mailobject->mailgun_object['text']       = $mailobject->mailer->AltBody;
			$mailobject->mailgun_object['html']       = $mailobject->mailer->Body;
			$mailobject->mailgun_object['subject']    = $mailobject->subject;
			$mailobject->mailgun_object['h:Reply-To'] = $mailobject->reply_to;

			$mailobject->mailgun_object['v:Mailster'] = $data;

			if ( $mailobject->headers ) {
				foreach ( $mailobject->headers as $key => $value ) {
					$mailobject->mailgun_object[ 'h:' . $key ] = $value;
				}
			}

			if ( ! empty( $mailobject->attachments ) || $mailobject->embed_images ) {

				$org_attachments                          = $mailobject->mailer->getAttachments();
				$mailobject->mailgun_object['attachment'] = array();

				foreach ( $org_attachments as $attachment ) {

					$mailobject->mailgun_object['attachment'][] = array(
						'content'  => file_get_contents( $attachment[0] ),
						'filename' => $attachment[1],
						'type'     => $attachment[6],
					);
					if ( 'inline' == $attachment[6] ) {
						$mailobject->mailgun_object['html'] = str_replace( '"cid:' . $attachment[7] . '"', '"cid:' . $attachment[1] . '"', $mailobject->mailgun_object['html'] );
					}
				}
			}
		}

		$mailobject->mailgun_object = apply_filters( 'mailster_mailgun_object', $mailobject->mailgun_object, $mailobject );

	}


	/**
	 * dosend function.
	 *
	 * uses the mailster_dosend hook and triggers the send
	 *
	 * @access public
	 * @param mixed $mailobject
	 * @return void
	 */
	public function dosend( $mailobject ) {

		$method = mailster_option( 'mailgun_api' );

		if ( $method == 'smtp' ) {

			// use send from the main class
			$mailobject->do_send();

		} else {

			if ( ! isset( $mailobject->mailgun_object ) ) {
				$mailobject->set_error( __( 'Mailgun options not defined', 'mailster-mailgun' ) );
				$mailobject->sent = false;
				return false;
			}

			$response = $this->do_post( 'messages', $mailobject->mailgun_object, 60 );

			if ( is_wp_error( $response ) ) {
				$code = $response->get_error_code();
				if ( 403 == $code ) {
					$errormessage = __( 'Not able to send message. Make sure your API Key is allowed to read and write Transmissions!', 'mailster-mailgun' );
				} else {
					$errormessage = $response->get_error_message();
				}
				$mailobject->set_error( $errormessage );
				$mailobject->sent = false;
			} else {
				$mailobject->sent = true;
			}
		}
	}



	/**
	 * delivery_method function.
	 *
	 * add the delivery method to the options
	 *
	 * @access public
	 * @param mixed $delivery_methods
	 * @return void
	 */
	public function delivery_method( $delivery_methods ) {
		$delivery_methods['mailgun'] = 'Mailgun';
		return $delivery_methods;
	}


	/**
	 * deliverytab function.
	 *
	 * the content of the tab for the options
	 *
	 * @access public
	 * @return void
	 */
	public function deliverytab() {

		$verified = mailster_option( 'mailgun_verified' );

		include $this->plugin_path . '/views/settings.php';

	}


	public function do_get( $endpoint, $args = array(), $timeout = 15 ) {
		return $this->do_call( 'GET', $endpoint, $args, $timeout );
	}
	public function do_post( $endpoint, $args = array(), $timeout = 15 ) {
		return $this->do_call( 'POST', $endpoint, $args, $timeout );
	}


	/**
	 *
	 * @access public
	 * @param unknown $apikey  (optional)
	 * @return void
	 */
	private function do_call( $method, $endpoint, $args = array(), $timeout = 15 ) {

		$args             = wp_parse_args( $args, array() );
		$body             = null;
		$apikey           = isset( $this->apikey ) ? $this->apikey : mailster_option( 'mailgun_apikey' );
		$domain           = isset( $this->domain ) ? $this->domain : mailster_option( 'mailgun_domain' );
		$mailgun_endpoint = mailster_option( 'mailgun_endpoint' ) ? 'https://api.eu.mailgun.net/v3/' : 'https://api.mailgun.net/v3/';
		$url              = $mailgun_endpoint . ( $domain ? $domain . '/' : '' ) . $endpoint;

		$headers = array(
			'Authorization' => 'Basic ' . base64_encode( 'api:' . $apikey ),
		);

		if ( 'GET' == $method ) {
			$url = add_query_arg( $args, $url );
		} elseif ( 'POST' == $method ) {
			$boundary = hash( 'crc32', md5( uniqid( 'boundary', true ) ) );

			$attachments = false;
			foreach ( $args as $key => $value ) {

				if ( 'attachment' == $key ) {
					$attachments = $value;
					continue;
				}
				if ( is_array( $value ) ) {
					$parent_key = $key;
					foreach ( $value as $key => $value ) {
						$body .= '--' . $boundary . "\r\n";
						$body .= 'Content-Disposition: form-data; name="' . $parent_key . "\"\r\n\r\n";
						$body .= $value . "\r\n";
					}
				} else {
					$body .= '--' . $boundary . "\r\n";
					$body .= 'Content-Disposition: form-data; name="' . $key . '"' . "\r\n\r\n";
					$body .= $value . "\r\n";
				}
			}

			if ( $attachments ) {
				foreach ( $attachments as $i => $attachment ) {
					$body .= '--' . $boundary . "\r\n";
					$body .= 'Content-Disposition: form-data; name="' . $attachment['type'] . '[' . $i . ']"; filename="' . basename( $attachment['filename'] ) . '"' . "\r\n\r\n";
					$body .= $attachment['content'] . "\r\n";
				}
			}

			$body .= '--' . $boundary . '--';

			$headers['Content-Type'] = 'multipart/form-data; boundary=' . $boundary;

		} else {
			return new WP_Error( 'method_not_allowed', 'This method is not allowed' );
		}

		$response = wp_remote_request(
			$url,
			array(
				'method'  => $method,
				'headers' => $headers,
				'timeout' => $timeout,
				'body'    => $body,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		if ( 200 != $code ) {
			$body = json_decode( $body );
			if ( isset( $body->message ) ) {
				$message = $body->message;
			} else {
				$message = wp_remote_retrieve_response_message( $response );
			}
			return new WP_Error( $code, $message );
		} elseif ( 'GET' == $method ) {
			$body = json_decode( $body );
		}

		return $body;

	}


	/**
	 *
	 * @access public
	 * @return void
	 */
	public function verify( $apikey = null ) {

		if ( ! is_null( $apikey ) ) {
			$this->apikey = $apikey;
		}

		$response = $this->get_sending_domains();

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return $response;

	}


	/**
	 *
	 * @access public
	 * @return void
	 */
	public function get_sending_domains() {

		$this->domain = '';
		$response     = $this->do_get( 'domains', 'limit=1000' );
		$this->domain = null;

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$domains = $response->items;

		return $domains;

	}

	/**
	 *
	 * @access public
	 * @return void
	 */
	public function get_subaccounts() {

		$response = $this->do_get( 'subaccounts' );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$accounts = $response->results;

		return $accounts;

	}



	/**
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	public function verify_options( $options ) {

		if ( $timestamp = wp_next_scheduled( 'mailster_mailgun_cron' ) ) {
			wp_unschedule_event( $timestamp, 'mailster_mailgun_cron' );
		}

		if ( $options['deliverymethod'] == 'mailgun' ) {

			$old_apikey          = mailster_option( 'mailgun_apikey' );
			$old_delivery_method = mailster_option( 'deliverymethod' );

			if ( ! wp_next_scheduled( 'mailster_mailgun_cron' ) ) {
				wp_schedule_event( time(), 'mailster_cron_interval', 'mailster_mailgun_cron' );
			}

			if ( $old_apikey != $options['mailgun_apikey'] || ! $options['mailgun_verified'] || $old_delivery_method != 'mailgun' ) {
				$response = $this->verify( $options['mailgun_apikey'] );

				if ( is_wp_error( $response ) ) {
					$options['mailgun_verified'] = false;
					add_settings_error( 'mailster_options', 'mailster_options', __( 'Not able to get Account details. Make sure your API Key is correct and allowed to read Account details!', 'mailster-mailgun' ) );
				} else {

					$options['mailgun_verified'] = true;
				}

				if ( isset( $options['mailgun_api'] ) && $options['mailgun_api'] == 'smtp' ) {
					if ( function_exists( 'fsockopen' ) ) {
						$host = $options['mailgun_endpoint'] ? 'smtp.eu.mailgun.org' : 'smtp.mailgun.org';
						$port = $options['mailgun_port'];
						$conn = fsockopen( $host, $port, $errno, $errstr, 15 );

						if ( is_resource( $conn ) ) {

							fclose( $conn );

						} else {

							add_settings_error( 'mailster_options', 'mailster_options', sprintf( __( 'Not able to use Mailgun with SMTP API cause of the blocked port %s! Please send with the WEB API, use a different port or choose a different delivery method!', 'mailster-mailgun' ), $port ) );

						}
					}
				} else {

				}
			}
		}

		return $options;
	}


	/**
	 * check_bounces function.
	 *
	 * @access public
	 * @return void
	 */
	public function check_bounces() {

		if ( get_transient( 'mailster_check_bounces_lock' ) || ! mailster_option( 'mailgun_verified' ) ) {
			return false;
		}

		$now = time();

		if ( ! ( $last_bounce_check = get_transient( '_mailster_mailgun_last_bounce_check' ) ) ) {
			set_transient( '_mailster_mailgun_last_bounce_check', $now );
			$last_bounce_check = $now;
		}

		$response = $this->do_get(
			'events',
			array(
				'begin'     => date( 'r', $last_bounce_check ),
				'event'     => '(rejected OR failed OR unsubscribed OR complained)',
				'limit'     => 300,
				'ascending' => 'yes',
			),
			30
		);

		if ( is_wp_error( $response ) ) {
			mailster_notice( sprintf( __( 'Not able to check bounces via Mailgun: %s', 'mailster-mailgun' ), $response->get_error_message() ), 'error', false, 'mailster_mailgun_bounce_error' );
			return;
		} else {
			mailster_remove_notice( 'mailster_mailgun_bounce_error' );
		}

		$MID = mailster_option( 'ID' );

		foreach ( $response->items as $result ) {

			if ( ! isset( $result->{'user-variables'}->Mailster ) ) {
				continue;
			}

			$data = json_decode( base64_decode( $result->{'user-variables'}->Mailster ) );

			if ( ! isset( $data->mailster_id ) || $data->mailster_id != $MID ) {
				continue;
			}

			if ( isset( $data->subscriber_id ) ) {
				$subscriber = mailster( 'subscribers' )->get( $data->subscriber_id );
			} else {
				$subscriber = mailster( 'subscribers' )->get_by_mail( $result->recipient );
			}
			if ( ! $subscriber ) {
				continue;
			}
			if ( isset( $data->campaign_id ) ) {
				$campaign_id = $data->campaign_id;
			} else {
				$campaign_id = null;
			}
			if ( isset( $data->index ) ) {
				$index = $data->index;
			} else {
				$index = null;
			}

			switch ( trim( $result->event . ' ' . $result->severity ) ) {
				case 'rejected':
					break;
				case 'failed permanent':
					$reason      = trim( $result->{'delivery-status'}->message . ' ' . $result->{'delivery-status'}->description );
					$hard_bounce = true;
					if ( version_compare( MAILSTER_VERSION, '3.0', '<' ) ) {
						mailster( 'subscribers' )->bounce( $subscriber->ID, $campaign_id, $hard_bounce, $reason );
					} else {
						mailster( 'subscribers' )->bounce( $subscriber->ID, $campaign_id, $hard_bounce, $reason, $index );
					}
					break;
				case 'failed temporary':
					// soft bounces are handled by Mailgun
					break;
				case 'unsubscribed':
				case 'complained':
					if ( version_compare( MAILSTER_VERSION, '3.0', '<' ) ) {
						mailster( 'subscribers' )->unsubscribe( $subscriber->ID, $campaign_id, $result->event );
					} else {
						mailster( 'subscribers' )->unsubscribe( $subscriber->ID, $campaign_id, $result->event, $index );
					}
					break;
				default:
					break;
			}
		}

		set_transient( '_mailster_mailgun_last_bounce_check', $now );
	}


	public function subscriber_errors( $errors ) {
		$errors[] = 'Message generation rejected';
		$errors[] = '\'to\' parameter is not a valid address. please check documentation';
		return $errors;
	}


	/**
	 * section_tab_bounce function.
	 *
	 * displays a note on the bounce tab
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	public function section_tab_bounce() {

		?>
		<div class="error inline"><p><strong><?php _e( 'Bouncing is handled by Mailgun so all your settings will be ignored', 'mailster-mailgun' ); ?></strong></p></div>

		<?php
	}



	/**
	 * Notice if Mailster is not available
	 *
	 * @access public
	 * @return void
	 */
	public function notice() {
		?>
	<div id="message" class="error">
	  <p>
	   <strong>Mailgun integration for Mailster</strong> requires the <a href="https://mailster.co/?utm_campaign=wporg&utm_source=Mailgun+integration+for+Mailster&utm_medium=plugin">Mailster Newsletter Plugin</a>, at least version <strong><?php echo MAILSTER_MAILGUN_REQUIRED_VERSION; ?></strong>.
	  </p>
	</div>
		<?php
	}



	/**
	 * activate function
	 *
	 * @access public
	 * @return void
	 */
	public function activate() {

		if ( function_exists( 'mailster' ) ) {

			mailster_notice( sprintf( __( 'Change the delivery method on the %s!', 'mailster-mailgun' ), '<a href="edit.php?post_type=newsletter&page=mailster_settings&mailster_remove_notice=delivery_method#delivery">' . __( 'Settings Page', 'mailster-mailgun' ) . '</a>' ), '', 360, 'delivery_method' );

			$defaults = array(
				'mailgun_apikey'        => '',
				'mailgun_api'           => 'web',
				'mailgun_domain'        => null,
				'mailgun_endpoint'      => false,
				'mailgun_smtp_port'     => 587,
				'mailgun_smtp_login'    => 'postmaster',
				'mailgun_smtp_password' => '',
				'mailgun_track'         => 0,
				'mailgun_tags'          => '',
				'mailgun_verified'      => false,
			);

			$mailster_options = mailster_options();

			foreach ( $defaults as $key => $value ) {
				if ( ! isset( $mailster_options[ $key ] ) ) {
					mailster_update_option( $key, $value );
				}
			}
		}
	}


	/**
	 * deactivate function
	 *
	 * @access public
	 * @return void
	 */
	public function deactivate() {

		if ( function_exists( 'mailster' ) ) {
			if ( mailster_option( 'deliverymethod' ) == 'mailgun' ) {
				mailster_update_option( 'deliverymethod', 'simple' );
				mailster_notice( sprintf( __( 'Change the delivery method on the %s!', 'mailster-mailgun' ), '<a href="edit.php?post_type=newsletter&page=mailster_settings&mailster_remove_notice=delivery_method#delivery">Settings Page</a>' ), '', 360, 'delivery_method' );
			}
		}
	}


}
