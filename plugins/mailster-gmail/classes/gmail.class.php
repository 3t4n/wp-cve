<?php

class MailsterGmail {

	private $plugin_path;
	private $plugin_url;
	private $client;


	public function __construct() {

		$this->plugin_path = plugin_dir_path( MAILSTER_GMAIL_FILE );
		$this->plugin_url  = plugin_dir_url( MAILSTER_GMAIL_FILE );

		register_activation_hook( MAILSTER_GMAIL_FILE, array( &$this, 'activate' ) );
		register_deactivation_hook( MAILSTER_GMAIL_FILE, array( &$this, 'deactivate' ) );

		load_plugin_textdomain( 'mailster-gmail' );

		add_action( 'init', array( &$this, 'init' ), 1 );
	}


	public function init() {

		if ( ! function_exists( 'mailster' ) ) {

			add_action( 'admin_notices', array( &$this, 'notice' ) );

		} else {

			add_filter( 'mailster_delivery_methods', array( &$this, 'delivery_method' ) );
			add_action( 'mailster_deliverymethod_tab_gmail', array( &$this, 'deliverytab' ) );

			add_filter( 'mailster_verify_options', array( &$this, 'verify_options' ) );

			add_action( 'load-newsletter_page_mailster_settings', array( &$this, 'auth_endpoint' ) );

			if ( mailster_option( 'deliverymethod' ) == 'gmail' ) {

				add_action( 'mailster_initsend', array( &$this, 'initsend' ) );
				add_action( 'mailster_presend', array( &$this, 'presend' ) );
				add_action( 'mailster_dosend', array( &$this, 'dosend' ) );
				add_action( 'mailster_check_bounces', array( &$this, 'check_bounces' ) );
				add_action( 'mailster_section_tab_bounce', array( &$this, 'section_tab_bounce' ) );
				add_filter( 'mailster_subscriber_errors', array( $this, 'subscriber_errors' ) );
			}
		}

	}


	public function auth_endpoint() {

		if ( isset( $_GET['mailster_gmail'] ) ) {

			$redirect_url = admin_url( 'edit.php?post_type=newsletter&page=mailster_settings#delivery' );

			if ( isset( $_GET['error'] ) ) {
				switch ( $_GET['error'] ) {
					case 'access_denied':
						mailster_notice( esc_html__( 'You have to allow access to your app in order to use Gmail as delivery method.', 'mailster-gmail' ), 'error', true );
						break;

					default:
						mailster_notice( esc_html__( 'There was an error while authorizing your app.', 'mailster-gmail' ), 'error', true );
						break;
				}
			} elseif ( isset( $_GET['code'] ) ) {

				$this->get_client( $_GET['code'] );

			}
			wp_redirect( $redirect_url );

		}
	}


	public function get_redirect_url() {
		return apply_filters( 'mailster_gmail_redirect_url', admin_url( 'edit.php?post_type=newsletter&page=mailster_settings&mailster_gmail=auth' ) );
	}


	public function get_client( $code = null ) {

		$client_id     = mailster_option( 'gmail_client_id' );
		$client_secret = mailster_option( 'gmail_client_secret' );
		$token         = mailster_option( 'gmail_token' );

		if ( ! $this->client && $client_id && $client_secret ) {

			$client = new Mailster\Gmail\Google\Client();
			$client->setClientId( $client_id );
			$client->setClientSecret( $client_secret );
			$client->setRedirectUri( $this->get_redirect_url() );
			$client->setAccessType( 'offline' );
			if ( $token ) {
				$client->setAccessToken( $token );
			}
			$client->setApprovalPrompt( 'force' );

			$client->addScope( 'https://mail.google.com/' );

			// If there is no previous token or it's expired.
			if ( $client->isAccessTokenExpired() ) {

				// Refresh the token if possible, else fetch a new one.
				if ( $client->getRefreshToken() ) {
					$client->fetchAccessTokenWithRefreshToken( $client->getRefreshToken() );
				} elseif ( ! is_null( $code ) ) {
					$client->authenticate( $code );
				} else {
					if ( $token ) {
						mailster_notice( esc_html__( 'The Gmail Integration for Mailster is no longer connected. Please check the delivery settings!', 'mailster-gmail' ), 'error', false, 'mailster_gmail_isAccessTokenExpired' );
					}
					return null;
				}

				$token = json_encode( $client->getAccessToken() );
				remove_filter( 'mailster_verify_options', array( &$this, 'verify_options' ) );
				mailster_update_option( 'gmail_token', $token );

			}

			$this->client = $client;
		}

		if ( ! is_null( $code ) ) {
			$this->client->authenticate( $code );
			mailster_update_option( 'gmail_token', json_encode( $client->getAccessToken() ) );
		}

		return $this->client;
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

		// Gmail will handle DKIM integration
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

		$mailobject->pre_send();

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

		$client = $this->get_client();

		if ( ! $client ) {
			$mailobject->set_error( sprintf( esc_html__( 'Please create your Gmail App as explained in our guide %s.', 'mailster-gmail' ), '<a href="https://kb.mailster.co/send-your-newsletters-via-gmail/" class="external">' . esc_html__( 'here', 'mailster-gmail' ) . '</a>' ) );
			$mailobject->sent = false;
			return false;
		}

		try {
			$mailobject->mailer->PreSend();

			$service = new Mailster\Gmail\Google\Service\Gmail( $this->get_client() );

			$rawmessage = $mailobject->mailer->getSentMIMEMessage();

			$msg = new Mailster\Gmail\Google\Service\Gmail\Message();
			$msg->setRaw( rtrim( strtr( base64_encode( $rawmessage ), '+/', '-_' ), '=' ) );

			$response = $service->users_messages->send( 'me', $msg );
			if ( method_exists( $response, 'getId' ) ) {
				$message_id       = $response->getId();
				$mailobject->sent = ! empty( $message_id );
			}
		} catch ( Mailster\Google\Service\Exception $e ) {
			$errorObj = json_decode( $e->getMessage() );
			$code     = $errorObj->error->code;
			if ( 429 == $code ) {
				if ( ! mailster_option( 'pause_campaigns' ) && $mailobject->campaignID && defined( 'MAILSTER_DOING_CRON' ) ) {
					$time       = preg_replace( '/(.*?)(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})\.(.*)/', '$2', $errorObj->error->message );
					$campaign   = mailster( 'campaigns' )->get( $mailobject->campaignID );
					$timeformat = mailster( 'helper' )->timeformat();
					$timeoffset = mailster( 'helper' )->gmt_offset( true );
					mailster_notice( sprintf( esc_html__( 'Campaign %1$s has been paused and will resume on %2$s.', 'mailster-gmail' ), $campaign->post_title, date( $timeformat, strtotime( $time ) + $timeoffset ) ), 'info', false, 'gmail_campaign_paused_' . $mailobject->campaignID );
					mailster( 'campaigns' )->pause( $mailobject->campaignID );
					mailster( 'campaigns' )->resume( $mailobject->campaignID, strtotime( $time ) + 10 );
				}
			} elseif ( 401 == $code ) {
				mailster_update_option( 'gmail_token', '' );
			}
			$mailobject->set_error( $errorObj->error->message );
			$mailobject->sent = false;
		} catch ( Exception $e ) {
			$mailobject->set_error( $e->getMessage() );
			$mailobject->sent = false;
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
		$delivery_methods['gmail'] = 'Gmail';
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

		$verified = ! is_null( $this->get_client() );

		include $this->plugin_path . '/views/settings.php';

	}



	/**
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	public function verify_options( $options ) {

		if ( $options['deliverymethod'] == 'gmail' ) {

			$send_limit = 500;
			if ( false && $options['send_limit'] != $send_limit ) {
				$options['send_limit']  = $send_limit;
				$options['send_period'] = 24;
				update_option( '_transient__mailster_send_period_timeout', false );
				add_settings_error( 'mailster_settings', uniqid(), sprintf( esc_html__( 'Send limit has been adjusted to %d for Gmail', 'mailster-gmail' ), $send_limit ), 'updated' );
			}

			if ( $client = $this->get_client() ) {
				try {
					$service         = new Mailster\Gmail\Google\Service\Gmail( $client );
					$response        = $service->users->getProfile( 'me' );
					$options['from'] = $options['reply_to'] = $options['bounce'] = $response->emailAddress;
				} catch ( Exception $e ) {
					$errorObj = json_decode( $e->getMessage() );
					if ( isset( $errorObj->error->code ) ) {
						$code = $errorObj->error->code;
					} else {
						$code = $errorObj->error;
					}
					if ( $code == 401 ) {
						$options['gmail_token'] = '';
					}
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

		if ( get_transient( 'mailster_check_bounces_lock' ) ) {
			return false;
		}

		if ( ! ( $client = $this->get_client() ) ) {
			return false;
		}

		$service = new Mailster\Gmail\Google\Service\Gmail( $client );

		$pageToken         = null;
		$userId            = 'me';
		$messages          = array();
		$messages_todelete = array();
		$opt_param         = array( 'q' => 'in:(inbox OR spam) from:("Mail Delivery Subsystem") OR subject:"Please remove me from the list" after:' . date( 'Y/m/d', time() - DAY_IN_SECONDS ) );

		do {
			try {
				if ( $pageToken ) {
					$opt_param['pageToken'] = $pageToken;
				}
				$messagesResponse = $service->users_messages->listUsersMessages( $userId, $opt_param );
				if ( $messagesResponse->getMessages() ) {
					$messages  = array_merge( $messages, $messagesResponse->getMessages() );
					$pageToken = $messagesResponse->getNextPageToken();
				}
			} catch ( Exception $e ) {
			}
		} while ( $pageToken );

		foreach ( $messages as $id => $message ) {

			$m = $service->users_messages->get( $userId, $message->id, array( 'format' => 'full' ) );

			$status      = null;
			$action      = null;
			$MID         = null;
			$campaign_id = null;
			$hash        = null;
			$info        = $m->getSnippet();

			// handle reply from using client specific feature
			if ( false !== strpos( $info, 'X-Mailster:' ) ) {
				preg_match( '#X-Mailster-ID: ([a-f0-9]{32})#i', $info, $MID );
				preg_match( '#X-Mailster: ([a-f0-9]{32})#i', $info, $hash );
				preg_match( '#X-Mailster-Campaign: (\d+)#i', $info, $camp );
				$MID            = isset( $MID[1] ) ? $MID[1] : null;
				$hash           = isset( $hash[1] ) ? $hash[1] : null;
				$campaign_id    = isset( $camp[1] ) ? (int) $camp[1] : null;
				$campaign_index = null;

				// get the campaign index
				if ( false !== strpos( $campaign_id, '-' ) ) {
					$campaign_index = absint( strrchr( $campaign_id, '-' ) );
					$campaign_id    = absint( $campaign_id );
				}
				$info   = 'list_unsubscribe';
				$action = 'unsubscribe';
			}

			// handle regular bounce messages
			if ( ! $action ) {
				foreach ( $m->payload->parts as $message_part ) {

					foreach ( $message_part->parts as $part ) {
						foreach ( $part->headers as $part_header ) {
							switch ( $part_header->name ) {
								case 'Status':
									$status = $part_header->value;
									break;
								case 'Action':
									$action = $part_header->value;
									break;
								case 'X-Mailster-ID':
									$MID = $part_header->value;
									break;
								case 'X-Mailster-Campaign':
									$campaign_id    = $part_header->value;
									$campaign_index = null;

									// get the campaign index
									if ( false !== strpos( $campaign_id, '-' ) ) {
										$campaign_index = absint( strrchr( $campaign_id, '-' ) );
										$campaign_id    = absint( $campaign_id );
									}
									break;
								case 'X-Mailster':
									$hash = $part_header->value;
									break;
							}
						}
					}
				}
			}

			// handle Gmail special cases.
			if ( ! $action ) {
				foreach ( $m->payload->headers as $message_headers ) {

					if ( 'From' == $message_headers->name && 'Mail Delivery Subsystem <mailer-daemon@googlemail.com>' == $message_headers->value ) {

						$raw_status = base64_decode( $m->payload->parts[1]->parts[0]->body->data );

						$icon_attachment_id = $m->payload->parts[0]->parts[1]->body->attachmentId;
						if ( $attachment_id = $m->payload->parts[2]->body->attachmentId ) {
							$org_email   = $service->users_messages_attachments->get( $userId, $message->id, $attachment_id );
							$org_message = base64_decode( strtr( $org_email->data, '-_', '+/' ) );

							preg_match( '#Status: ([0-9.]+)#i', $raw_status, $status );
							preg_match( '#Action: ([0-9A-Za-z-]+)#i', $raw_status, $action );
							preg_match( '#X-Mailster-ID: ([a-f0-9]{32})#i', $org_message, $MID );
							preg_match( '#X-Mailster: ([a-f0-9]{32})#i', $org_message, $hash );
							preg_match( '#X-Mailster-Campaign: (\d+)#i', $org_message, $camp );

							$status         = isset( $status[1] ) ? $status[1] : null;
							$action         = isset( $action[1] ) ? $action[1] : null;
							$MID            = isset( $MID[1] ) ? $MID[1] : null;
							$hash           = isset( $hash[1] ) ? $hash[1] : null;
							$campaign_id    = isset( $camp[1] ) ? (int) $camp[1] : null;
							$campaign_index = null;

							// get the campaign index
							if ( false !== strpos( $campaign_id, '-' ) ) {
								$campaign_index = absint( strrchr( $campaign_id, '-' ) );
								$campaign_id    = absint( $campaign_id );
							}
						}

						break;

					}
				}
			}

			if ( ! empty( $hash ) && $MID == mailster_option( 'ID' ) ) {

				$subscriber = mailster( 'subscribers' )->get_by_hash( $hash, false );

				if ( $subscriber ) {

					switch ( $action ) {
						case 'success':
							// no action on delayed as Gmail handles that
						case 'delayed':
							break;
						case 'unsubscribe':
							// unsubscribe
							if ( version_compare( MAILSTER_VERSION, '3.0', '<' ) ) {
								mailster( 'subscribers' )->unsubscribe( $subscriber->ID, $campaign_id, 'list_unsubscribe' );
							} else {
								mailster( 'subscribers' )->unsubscribe( $subscriber->ID, $campaign_id, 'list_unsubscribe', $campaign_index );
							}
							break;
						case 'failed':
							// hardbounce
							if ( version_compare( MAILSTER_VERSION, '3.0', '<' ) ) {
								mailster( 'subscribers' )->bounce( $subscriber->ID, $campaign_id, true, $info );
							} else {
								mailster( 'subscribers' )->bounce( $subscriber->ID, $campaign_id, true, $info, $campaign_index );
							}
							break;

						case 'transient':
						default:
							// softbounce
							if ( version_compare( MAILSTER_VERSION, '3.0', '<' ) ) {
								mailster( 'subscribers' )->bounce( $subscriber->ID, $campaign_id, false, $info );
							} else {
								mailster( 'subscribers' )->bounce( $subscriber->ID, $campaign_id, false, $info, $campaign_index );
							}
					}
				}

				$messages_todelete[] = $message->id;

			}
		}

		if ( ! empty( $messages_todelete ) ) {
			try {
				$request = new Mailster\Gmail\Google\Service\Gmail\BatchDeleteMessagesRequest();
				$request->setIds( $messages_todelete );
				$service->users_messages->batchDelete( $userId, $request );
			} catch ( Exception $e ) {

			}
		}

		set_transient( '_mailster_gmail_last_bounce_check', time() );
	}


	public function subscriber_errors( $errors ) {
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
		<div class="error inline"><p><strong><?php esc_html_e( 'Bouncing is handled by Gmail so all your settings will be ignored', 'mailster-gmail' ); ?></strong></p></div>

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
	   <strong>Gmail integration for Mailster</strong> requires the <a href="https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=plugin&utm_term=Gmail">Mailster Newsletter Plugin</a>, at least version <strong><?php echo MAILSTER_GMAIL_REQUIRED_VERSION; ?></strong>.
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

			mailster_notice( sprintf( esc_html__( 'Change the delivery method on the %s!', 'mailster-gmail' ), '<a href="edit.php?post_type=newsletter&page=mailster_settings&mailster_remove_notice=delivery_method#delivery">' . esc_html__( 'Settings Page', 'mailster-gmail' ) . '</a>' ), '', 360, 'delivery_method' );

			$defaults = array(
				'gmail_client_id'     => '',
				'gmail_client_secret' => '',
				'gmail_token'         => '',
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
			if ( mailster_option( 'deliverymethod' ) == 'gmail' ) {
				mailster_update_option( 'deliverymethod', 'simple' );
				mailster_notice( sprintf( __( 'Change the delivery method on the %s!', 'mailster-gmail' ), '<a href="edit.php?post_type=newsletter&page=mailster_settings&mailster_remove_notice=delivery_method#delivery">Settings Page</a>' ), '', 360, 'delivery_method' );
			}
		}
	}



}
