<?php

class SI_Formidable_Controller extends SI_Controller {
	const FORM_ID_MAPPING = 'si_invoice_submission_form_mapping';
	const SUBMISSION_UPDATE = 'invoice_submission';
	const FORM_ASSOC_META = '_invoice_form_submission_association';
	// Integration options
	protected static $form_mapping;

	public static function init() {
		self::$form_mapping = get_option( self::FORM_ID_MAPPING, array() );
	}

	public static function get_form_map_id( $key = '' ) {
		$value = ( isset( self::$form_mapping[ $key ] ) ) ? self::$form_mapping[ $key ] : '' ;
		return $value;
	}

	///////////////
	// Settings //
	///////////////

	public static function register_settings() {
		// integrations handle options
	}


	//////////////
	// Utility //
	//////////////

	/**
	 * Create Invoice
	 */
	public static function maybe_create_estimate( $args = array(), $entry_id = 0 ) {
		if ( ! isset( $args['status'] ) ) {
			$args['status'] = SI_Estimate::STATUS_PENDING;
		}
		// Create estimate
		$args = apply_filters( 'si_estimate_submmissions_args', $args );
		$estimate_id = SI_Estimate::create_estimate( $args );
		$estimate = SI_Estimate::get_instance( $estimate_id );
		do_action( 'si_estimate_submitted_from_adv_form', $estimate, $args );
		if ( isset( $args['line_item_list'] ) && ! empty( $args['line_item_list'] ) ) {
			$line_items = array();
			foreach ( $args['line_item_list'] as $item_id ) {
				$item = SI_Item::get_instance( $item_id );
				$line_items[] = array(
					'rate' => $item->get_default_rate(),
					'type' => $item->get_type(),
					'qty' => $item->get_default_qty(),
					'tax' => $item->get_default_percentage(),
					'total' => ($item->get_default_rate() * $item->get_default_qty()),
					'desc' => $item->get_content(),
				);
			}
			$estimate->set_line_items( $line_items );
		}

		$estimate->reset_totals();

		if ( $entry_id ) {
			add_post_meta( $estimate->get_id(), self::FORM_ASSOC_META, $entry_id );
		}

		// History
		do_action( 'si_new_record',
			sprintf( __( 'Invoice Submitted: Form %s.', 'sprout-invoices' ), $args['history_link'] ),
			self::SUBMISSION_UPDATE,
			$estimate_id,
			sprintf( __( 'Invoice Submitted: Form %s.', 'sprout-invoices' ), $args['history_link'] ),
			0,
		false );

		do_action( 'si_estimate_submitted_from_adv_form_complete', $estimate, $args );
		return $estimate;
	}

	/**
	 * Create Invoice
	 */
	public static function maybe_create_invoice( $args = array(), $entry_id = 0 ) {
		if ( ! isset( $args['status'] ) ) {
			$args['status'] = SI_Invoice::STATUS_PENDING;
		}
		// Create invoice
		$args = apply_filters( 'si_invoice_submmissions_args', $args );
		$invoice_id = SI_Invoice::create_invoice( $args );
		$invoice = SI_Invoice::get_instance( $invoice_id );
		do_action( 'si_invoice_submitted_from_adv_form', $invoice, $args );
		if ( isset( $args['line_item_list'] ) && ! empty( $args['line_item_list'] ) ) {
			$line_items = array();
			foreach ( $args['line_item_list'] as $item_id ) {
				$item = SI_Item::get_instance( $item_id );
				$line_items[] = array(
					'rate' => $item->get_default_rate(),
					'type' => $item->get_type(),
					'qty' => $item->get_default_qty(),
					'tax' => $item->get_default_percentage(),
					'total' => ($item->get_default_rate() * $item->get_default_qty()),
					'desc' => $item->get_content(),
				);
			}
			$invoice->set_line_items( $line_items );
		}

		$invoice->reset_totals();

		if ( $entry_id ) {
			add_post_meta( $invoice->get_id(), self::FORM_ASSOC_META, $entry_id );
		}

		// History
		do_action( 'si_new_record',
			sprintf( __( 'Invoice Submitted: Form %s.', 'sprout-invoices' ), $args['history_link'] ),
			self::SUBMISSION_UPDATE,
			$invoice_id,
			sprintf( __( 'Invoice Submitted: Form %s.', 'sprout-invoices' ), $args['history_link'] ),
			0,
		false );

		do_action( 'si_invoice_submitted_from_adv_form_complete', $invoice, $args );
		return $invoice;
	}

	public static function get_invoice_id_from_entry_id( $entry_id = 0 ) {
		$invoice_id = 0;
		if ( $entry_id ) {
			$invoice_ids = SI_Post_Type::find_by_meta( SI_Invoice::POST_TYPE, array( self::FORM_ASSOC_META => $entry_id ) );
			if ( ! empty( $invoice_ids ) ) {
				$invoice_id = $invoice_ids[0];
			}
		}
		return $invoice_id;
	}

	/**
	 * Maybe create a client from submission
	 * @param  SI_Invoice $invoice
	 * @param  array       $args     * email - required
	 *                               * client_id - if client_id is passed than just assign invoice
	 *                               * client_name - required
	 *                               * full_name -
	 *                               * website
	 *                               * contact_street
	 *                               * contact_city
	 *                               * contact_zone
	 *                               * contact_postal_code
	 *                               * contact_country
	 *
	 */
	public static function maybe_create_client( $doc_id, $args = array() ) {
		// check if client_id set is valid
		$client_id = ( isset( $args['client_id'] ) && get_post_type( $args['client_id'] ) == SI_Client::POST_TYPE ) ? $args['client_id'] : 0;

		// get user_id
		$user_id = get_current_user_id();
		if ( isset( $args['email'] ) && $args['email'] != '' ) { // check to see if the user exists by email
			if ( $user = get_user_by( 'email', $args['email'] ) ) {
				$user_id = $user->ID;
			}
		}

		// Check to see if the user is assigned to a client already
		if ( ! $client_id ) {
			$client_ids = SI_Client::get_clients_by_user( $user_id );
			if ( ! empty( $client_ids ) ) {
				$client_id = array_pop( $client_ids );
			}
		}

		// Create a user for the submission if an email is provided.
		if ( ! $user_id ) {
			// email is critical
			if ( isset( $args['email'] ) && $args['email'] != '' ) {
				$user_args = array(
					'user_login' => esc_html( $args['email'] ),
					'display_name' => isset( $args['client_name'] ) ? esc_html( $args['client_name'] ) : esc_html( $args['email'] ),
					'user_pass' => wp_generate_password(), // random password
					'user_email' => isset( $args['email'] ) ? esc_html( $args['email'] ) : '',
					'first_name' => si_split_full_name( esc_html( $args['full_name'] ), 'first' ),
					'last_name' => si_split_full_name( esc_html( $args['full_name'] ), 'last' ),
					'user_url' => isset( $args['website'] ) ? esc_html( $args['website'] ) : '',
				);
				$user_id = SI_Clients::create_user( $user_args );
			}
		}

		// create the client based on what's submitted.
		if ( ! $client_id ) {
			$address = array(
				'street' => isset( $args['contact_street'] ) ?esc_html( $args['contact_street'] ) : '',
				'city' => isset( $args['contact_city'] ) ? esc_html( $args['contact_city'] ) : '',
				'zone' => isset( $args['contact_zone'] ) ? esc_html( $args['contact_zone'] ) : '',
				'postal_code' => isset( $args['contact_postal_code'] ) ? esc_html( $args['contact_postal_code'] ) : '',
				'country' => isset( $args['contact_country'] ) ? esc_html( $args['contact_country'] ) : '',
			);

			$args = array(
				'company_name' => isset( $args['client_name'] ) ? esc_html( $args['client_name'] ) : '',
				'website' => isset( $args['website'] ) ? esc_html( $args['website'] ) : '',
				'address' => $address,
				'user_id' => $user_id,
			);
			$client_id = SI_Client::new_client( $args );

			// History
			do_action( 'si_new_record',
				sprintf( 'Client Created & Assigned: %s', get_the_title( $client_id ) ),
				self::SUBMISSION_UPDATE,
				$doc_id,
				sprintf( 'Client Created & Assigned: %s', get_the_title( $client_id ) ),
				0,
			false );
		}
		// Set the invoices client
		$doc = si_get_doc_object( $doc_id );
		if ( method_exists( $doc, 'set_client_id' ) ) {
			$doc->set_client_id( $client_id );
		}

		return $client_id;
	}
}
SI_Formidable_Controller::init();
