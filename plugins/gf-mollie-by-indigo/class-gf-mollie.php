<?php

add_action( 'wp', array( 'GFMollie', 'maybe_thankyou_page' ), 5 );

GFForms::include_payment_addon_framework();

class GFMollie extends GFPaymentAddOn {

	protected $_version = GF_MOLLIE_BY_INDIGO_VERSION;
	protected $_min_gravityforms_version = '2.8.0';
	protected $_slug = 'gf-mollie-by-indigo';
	protected $_path = 'gf-mollie-by-indigo/gf-mollie-by-indigo.php';
	protected $_full_path = __FILE__;
	protected $_url = 'http://www.gravityforms.com';
	protected $_title = 'GF Mollie by Indigo';
	protected $_short_title = 'GF Mollie by Indigo';
	protected $_supports_callbacks = true;

	// Members plugin integration
	protected $_capabilities = array( 'gravityforms_mollie' );

	// Permissions
	protected $_capabilities_settings_page = 'gravityforms_mollie';
	protected $_capabilities_form_settings = 'gravityforms_mollie';
	protected $_capabilities_uninstall = 'gravityforms_mollie';
	// Automatic upgrade enabled
	protected $_enable_rg_autoupgrade = false;
	private static $_instance = null;
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFMollie();
		}

		return self::$_instance;
	}
	private function __clone() {
	} /* do nothing */
	public function init_frontend() {
		parent::init_frontend();
	}
	public function init() {
		parent::init();
		// add tasks or filters here that you want to perform both in the backend and frontend and for ajax requests
		add_filter( 'gform_custom_merge_tags', array( $this,  'gf_merge_tags' ), 10 );
		add_filter( 'gform_replace_merge_tags', array( $this, 'gf_replace_merge_tags' ), 10, 7);
	}
	/*
	 *	Remove all billing fields from the mollie feed
	 */
	public function billing_info_fields() {
		$fields = array();
		return $fields;
	}
	public function feed_list_no_item_message() {
		return parent::feed_list_no_item_message();
	}
	
	/*
	 *	GF feed settings
	 */
	public function feed_settings_fields() {
		$default_settings = parent::feed_settings_fields();

		$fields = array(
			array(
				'type'  =>  __('helper_text',  'gf-mollie-by-indigo'),
				'name'  => 'help',
				'label' => '',
			),
			array(
				'label'   =>  __('Use default api key',  'gf-mollie-by-indigo'),
				'type'    => 'checkbox',
				'name'    => 'useDefaultKey',
				'description' => __('You can set a default api key in the general settings section',  'gf-mollie-by-indigo'),
				'choices' => array(
					array(
						'label' => 'Yes',
						'name'  => 'useDefaultKey'
					)
				)
			),
			array(
				'name'     => 'mollieKey',
				'label'    => esc_html__( 'Mollie api key ', 'gf-mollie-by-indigo' ),
				'type'     => 'text',
				'class'    => 'medium',
				'required' => false,
				'tooltip'  => '<h6>' . esc_html__( 'Mollie api key', 'gf-mollie-by-indigo' ) . '</h6>' . esc_html__( 'You can obtain your api key from your Mollie control panel', 'gf-mollie-by-indigo' )
			),
			
		);

		$default_settings = parent::add_field_after( 'feedName', $fields, $default_settings );
		//--------------------------------------------------------------------------------------

		//remove donations just to keep it simple
		$transaction_type = parent::get_field( 'transactionType', $default_settings );
		$choices          = $transaction_type['choices'];
		foreach ( $choices as $key=>$choice ) {
			//	Remove donations
			if ( $choice['value'] == 'subscription' ) {
				unset($choices[$key]);
			}
		}
		$transaction_type['choices'] = $choices;
		$default_settings            = $this->replace_field( 'transactionType', $transaction_type, $default_settings );
		//-------------------------------------------------------------------------------------------------

		//Add post fields if form has a post
		$form = $this->get_current_form();

		/**
		 * Filter through the feed settings fields for the Mollie feed
		 *
		 * @param array $default_settings The Default feed settings
		 * @param array $form The Form object to filter through
		 */
		return apply_filters( 'gform_mollie_feed_settings_fields', $default_settings, $form );
	}
	public function settings_helper_text(){
		printf( '<p>%s <a target="blank" href="https://www.mollie.com/dashboard/signup/308125?lang=nl">%s</a></p>', __( 'Don’t have a Mollie account?',  'gf-mollie-by-indigo'), __( 'Create one here!',  'gf-mollie-by-indigo'));
		?>

		<?php
	}
	public function field_map_title() {
		return esc_html__( 'Mollie Field', 'gf-mollie-by-indigo' );
	}

	/**
	 * Prevent the GFPaymentAddOn version of the options field being added to the feed settings.
	 * 
	 * @return bool
	 */
	public function option_choices() {
		return false;
	}

	public function save_feed_settings( $feed_id, $form_id, $settings ) {
		//--------------------------------------------------------
		//For backwards compatibility
		$feed = $this->get_feed( $feed_id );

		//Saving new fields into old field names to maintain backwards compatibility for delayed payments
		$settings['type'] = $settings['transactionType'];

		$feed['meta'] = $settings;
		$feed         = apply_filters( 'gform_mollie_save_config', $feed );
		
		//call hook to validate custom settings/meta added using gform_mollie_action_fields or gform_mollie_add_option_group action hooks
		$is_validation_error = apply_filters( 'gform_mollie_config_validation', false, $feed );
		if ( $is_validation_error ) {
			//fail save
			return false;
		}
		$settings = $feed['meta'];

		return parent::save_feed_settings( $feed_id, $form_id, $settings );
	}

	//------ SENDING TO MOLLIE -----------//
	public function redirect_url( $feed, $submission_data, $form, $entry ) {
		if ( ! rgempty( 'gf_mollie_return', $_GET ) ) {
			return false;
		}
		//updating lead's payment_status to Processing
		GFAPI::update_entry_property( $entry['id'], 'payment_status', 'Processing' );

		$payment_amount = rgar( $submission_data, 'payment_amount' );
		//	Make sure that we always has two decimals
		$payment_amount = number_format($payment_amount, 2, '.', '');

		$mollieKey = $this->get_mollie_key( $feed );
		if ( ! $mollieKey ) {
			$this->log_debug( __METHOD__ . '(): NOT sending to Mollie: The Mollie key is empty.' );
			return '';
		}
		
		$mollie = new \Mollie\Api\MollieApiClient();
		$mollie->setApiKey($mollieKey);
		$order_id = time();
		$return_url =  $this->return_url( $form['id'], $entry['id'] );
		
		//save payment amount to lead meta
		gform_update_meta( $entry['id'], 'payment_amount', $payment_amount );

		//  Allow description adjustments
		$description = apply_filters( 'gf_mollie_description', $feed['meta']['feedName'], $entry, $form );

		// Allow Mollie Payment Create adjustments
		$mollie_request_args = apply_filters( 'gf_mollie_request_args', array(
			"amount"       => array("currency" => "EUR", "value" => $payment_amount),
			"description"  => $description,
			"redirectUrl"  => $return_url,
			"webhookUrl"   => get_site_url().'/?page=gf_mollie_indigo&entry='.$entry['id'],
			"metadata"     => array(
			"order_id" => $order_id,
			"entry_id" => $entry['id'],
			),
		), $feed, $entry, $form );

		$payment = $mollie->payments->create( $mollie_request_args );

		//	Store transaction id as unique identification in gravityforms entry
		GFAPI::update_entry_property( $entry['id'], 'transaction_id',  $payment->id );
		$url = $payment->getCheckoutUrl();;
		$this->log_debug( __METHOD__ . "(): Sending to Mollie: {$url}" );
		return $url;
	}



	/*
	 *	This is the url we return to after payment
	 */
	public function return_url( $form_id, $lead_id ) {
		$pageURL = GFCommon::is_ssl() ? 'https://' : 'http://';
		$server_port = apply_filters( 'gform_mollie_return_url_port', $_SERVER['SERVER_PORT'] );
		if ( $server_port != '80' ) {
			$pageURL .= $_SERVER['SERVER_NAME'] . ':' . $server_port . $_SERVER['REQUEST_URI'];
		} else {
			$pageURL .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		}

		$ids_query = "ids={$form_id}|{$lead_id}";
		$ids_query .= '&hash=' . wp_hash( $ids_query );
		$url = add_query_arg( 'gf_mollie_return', base64_encode( $ids_query ), $pageURL );

		$query = 'gf_mollie_return=' . base64_encode( $ids_query );
		//	Allow modification of the mollie return url
		return apply_filters( 'gform_mollie_return_url', $url, $form_id, $lead_id, $query  );

	}

	public static function maybe_thankyou_page() {
		$instance = self::get_instance();
		if ( ! $instance->is_gravityforms_supported() ) {
			return;
		}

		if ( $str = rgget( 'gf_mollie_return' ) ) {
			$str = base64_decode( $str );
			parse_str( $str, $query );
			if ( wp_hash( 'ids=' . $query['ids'] ) == $query['hash'] ) {
				list( $form_id, $lead_id ) = explode( '|', $query['ids'] );
				$form = GFAPI::get_form( $form_id );
				$lead = GFAPI::get_entry( $lead_id );
				if ( ! class_exists( 'GFFormDisplay' ) ) {
					require_once( GFCommon::get_base_path() . '/form_display.php' );
				}
				$confirmation = GFFormDisplay::handle_confirmation( $form, $lead, false );
				if ( is_array( $confirmation ) && isset( $confirmation['redirect'] ) ) {
					header( "Location: {$confirmation['redirect']}" );
					exit;
				}
				GFFormDisplay::$submission[ $form_id ] = array( 'is_confirmation' => true, 'confirmation_message' => $confirmation, 'form' => $form, 'lead' => $lead );
			}
		}
	}


	//------- PROCESSING MOLLIE CALLBACK -----------//
	public function callback() {
		if ( ! $this->is_gravityforms_supported() ) {
			return false;
		}
		$this->log_debug( __METHOD__ . '(): request received. Starting to process => ' . print_r( $_POST, true ) );
		$this->log_debug( __METHOD__ . '(): request received. Starting to process GET=> ' . print_r( $_GET, true ) );
		$custom_field = rgpost( 'id' );
		if ( empty( $custom_field ) ) {
			$this->log_error( __METHOD__ . '(): Requests should have an id' );
			return false;
		}
		
		//	Get the entry
		$entry_id =  $_GET['entry'] ;
		$entry = GFAPI::get_entry( $entry_id );
		$this->log_debug( __METHOD__ . '(): result of get entry => ' . print_r( $entry, true ) );
	
		$feed =  $this->get_payment_feed($entry);
		$this->log_debug( __METHOD__ . '(): feed => ' . print_r( $feed, true ) );
	
		try {
			$mollie = new \Mollie\Api\MollieApiClient();
			$mollieKey = $this->get_mollie_key( $feed );
			$mollie->setApiKey($mollieKey);
			/*
			 * Retrieve the payment's current state.
			 */
			$payment  = $mollie->payments->get($_POST["id"]);
			$order_id = $payment->metadata->order_id;
			$this->log_debug( __METHOD__ . '(): Mollie Payment object'. print_r( $payment, true ) );
			$this->log_debug( __METHOD__ . '(): Mollie Payment entry id'. $payment->metadata->entry_id );
			$entry = GFAPI::get_entry(  $payment->metadata->entry_id );
			/*
			 * Allow other plugins to perform actions
			 */
			do_action( 'gform_mollie_payment_retreived', $entry, $payment );

			if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
				/*
				 * The payment is paid and isn't refunded or charged back.
				 * At this point you'd probably want to start the process of delivering the product to the customer.
				 */
				$this->log_debug( __METHOD__ . '(): Mollie status paid' );
				$action['id']             = $order_id . '_' . $payment->status;
				$action['type']           = 'complete_payment';
				$action['transaction_id'] = $order_id;
				$action['amount']         = $payment->amount->value;
				$action['entry_id']       = $entry['id'];
				$action['payment_date']   = gmdate( 'y-m-d H:i:s' );
				$action['payment_method'] = 'Mollie';
				return $action;
			} else {
				/*
				 * The payment isn't paid
				 */
				$this->log_debug( __METHOD__ . '(): Mollie status not paid' );
				$action['id']             = $order_id . '_' . $payment->status;
				$action['type']           = 'fail_payment';
				$action['transaction_id'] = $order_id;
				$action['entry_id']       = $entry['id'];
				$action['amount']         = $payment->amount->value;
				return $action;
			}
		} catch (Mollie_API_Exception $e) {
			/*
			 * The Api failed
			 */
			 $this->log_debug( __METHOD__ . '(): API call failed: ' . htmlspecialchars($e->getMessage()));
		}
		return false;
	}

	public function get_payment_feed( $entry, $form = false ) {
		$feed = parent::get_payment_feed( $entry, $form );
		if ( empty( $feed ) && ! empty( $entry['id'] ) ) {
			//looking for feed created by legacy versions
			$feed = $this->get_mollie_feed_by_entry( $entry['id'] );
		}
		$feed = apply_filters( 'gform_mollie_get_payment_feed', $feed, $entry, $form ? $form : GFAPI::get_form( $entry['form_id'] ) );
		return $feed;
	}

	private function get_mollie_feed_by_entry( $entry_id ) {
		$feed_id = gform_get_meta( $entry_id, 'mollie_feed_id' );
		$feed    = $this->get_feed( $feed_id );
		return ! empty( $feed ) ? $feed : false;
	}

	public function get_entry( $custom_field ) {
		//Getting entry associated with this IPN message (entry id is sent in the 'custom' field)
		list( $entry_id, $hash ) = explode( '|', $custom_field );
		$hash_matches = wp_hash( $entry_id ) == $hash;
		//allow the user to do some other kind of validation of the hash
		$hash_matches = apply_filters( 'gform_mollie_hash_matches', $hash_matches, $entry_id, $hash, $custom_field );
		//Validates that Entry Id wasn't tampered with
		if ( ! rgpost( 'test_ipn' ) && ! $hash_matches ) {
			$this->log_error( __METHOD__ . "(): Entry ID verification failed. Hash does not match. Custom field: {$custom_field}. Aborting." );
			return false;
		}
		$this->log_debug( __METHOD__ . "(): IPN message has a valid custom field: {$custom_field}" );
		$entry = GFAPI::get_entry( $entry_id );
		if ( is_wp_error( $entry ) ) {
			$this->log_error( __METHOD__ . '(): ' . $entry->get_error_message() );
			return false;
		}
		return $entry;
	}

	public function is_callback_valid() {
		$valid = true;
		if ( rgget( 'page' ) != 'gf_mollie_indigo' ) {
			$valid = false;
		}
		return apply_filters( 'gf_mollie_is_callback_valid', $valid );
	}

	public function supported_notification_events( $form ) {
		if ( ! $this->has_feed( $form['id'] ) ) {
			return false;
		}
		return array(
				'complete_payment'          => esc_html__( 'Mollie Payment Completed', 'gf-mollie-by-indigo' ),
				'fail_payment'              => esc_html__( 'Mollie Payment Failed', 'gf-mollie-by-indigo' )
		);
	}

	public function get_notifications_to_send( $form, $feed ) {
		$notifications_to_send  = array();
		$selected_notifications = rgars( $feed, 'meta/selectedNotifications' );
		if ( is_array( $selected_notifications ) ) {
			// Make sure that the notifications being sent belong to the form submission event, just in case the notification event was changed after the feed was configured.
			foreach ( $form['notifications'] as $notification ) {
				if ( rgar( $notification, 'event' ) != 'form_submission' || ! in_array( $notification['id'], $selected_notifications ) ) {
					continue;
				}
				$notifications_to_send[] = $notification['id'];
			}
		}
		return $notifications_to_send;
	}

	public function plugin_settings_fields() {
		$description = '
			<p style="text-align: left;">' .
		               esc_html__( 'Submit your default API key in the field below. You can override this field in the individual form settings if needed.', 'gf-mollie-by-indigo' ) .
		               '</p>';
		return array(
			array(
				'title'       => '',
				'description' => $description,
				'fields'      => array(
					array(
						'type'  => 'helper_text',
						'name'  => 'help',
						'label' => '',
					),
					array(
						'name'     => 'mollieKey',
						'label'    => esc_html__( 'Mollie api key ', 'gf-mollie-by-indigo' ),
						'type'     => 'text',
						'class'    => 'medium',
						'required' => true,
						'tooltip'  => '<h6>' . esc_html__( 'Mollie api key', 'gf-mollie-by-indigo' ) . '</h6>' . esc_html__( 'You can obtain your api key from your Mollie control panel', 'gf-mollie-by-indigo' )
					),
					array(
						'type' => 'save',
						'messages' => array(
							'success' => esc_html__( 'Settings have been updated.', 'gf-mollie-by-indigo' )
						),
					),
				),
			)
		);
	}

	//  Return mollie key for current feed
	public function get_mollie_key( $feed ) {
		if ( isset( $feed['meta']['useDefaultKey'] ) && $feed['meta']['useDefaultKey'] == true ) {
			$mollieKey = $this->get_plugin_setting( 'mollieKey' );
		} else {
			$mollieKey = $feed['meta']['mollieKey'];
		}
		return $mollieKey;
	}

	/*
	 * GF Mollie Merge tags
	 */
	public function gf_merge_tags( $merge_tags ) {
		$merge_tags[] = array(
			'label' => __( 'Payment Status', 'gf-mollie-by-indigo' ),
			'tag'   => '{gf-mollie-payment-status}',
		);
		return $merge_tags;
	}
	public function gf_replace_merge_tags( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
		$custom_merge_tag = '{gf-mollie-payment-status}';
		if (strpos($text, $custom_merge_tag) === false) {
			return $text;
		}
		//The current payment status of the entry (ie “Authorized”, “Paid”, “Processing”, “Pending”, “Active”, “Expired”, “Failed”, “Cancelled”, “Approved”, “Reversed”, “Refunded”, “Voided”).
		if ( isset($entry['payment_status'] ) ) {
			$replace = $entry['payment_status'];
		} else {
			$replace = '';
		}
		$text = str_replace($custom_merge_tag, $replace, $text);
		return $text;
	}
}









