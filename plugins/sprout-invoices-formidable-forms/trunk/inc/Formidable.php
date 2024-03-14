<?php

class SI_Formidable extends SI_Formidable_Controller {
	const FORMIDABLE_FORM_ID = 'si_formidable_invoice_submissions_id';
	const GENERATION = 'si_formidable_record_generation';
	// Integration options
	protected static $formidable_form_id;
	protected static $generation;

	public static function init() {
		// Store options
		self::$formidable_form_id = get_option( self::FORMIDABLE_FORM_ID, 0 );
		self::$generation = get_option( self::GENERATION, 'estimate' );

		add_filter( 'si_settings_options', array( __CLASS__, 'add_settings_options' ) );

		add_action( 'si_settings_saved', array( get_class(), 'save_mappings' ) );

		add_filter( 'si_settings', array( __CLASS__, 'register_settings' ) );

		// plugin menu
		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );

		if ( self::$formidable_form_id ) {
			// Create invoice before confirmation
			add_action( 'frm_after_create_entry', array( __CLASS__, 'maybe_process_formidable_form' ), 10, 2 );

			// Add pre-defined items
			add_filter( 'frm_available_fields', array( __CLASS__, 'add_basic_field' ) );
			add_filter( 'frm_form_fields', array( __CLASS__, 'show_my_front_field' ), 10, 2 );
		}
	}

	/**
	 * Add settings link to the plugin actions.
	 *
	 * @param  array  $actions Plugin actions.
	 * @param  string $plugin_file Path to the plugin file.
	 * @return array
	 */

	public static function plugin_action_links( $actions, $plugin_file ) {
		static $si_formiddable_forms;

		if ( ! isset( $plugin ) ) {
			$si_formiddable_forms = plugin_basename( SA_ADDON_INVOICE_SUBMISSIONS_FILE );
		}
		if ( $si_formiddable_forms === $plugin_file ) {
			$settings = array( 'settings' => '<a href="admin.php?page=sprout-invoices-addons#addons">' . __( 'Settings', 'General' ) . '</a>' );
			$actions  = array_merge( $settings, $actions );
		}
		return $actions;
	}

	public static function register_settings( $settings = array() ) {

		$frdbl_options = array( 0 => __( 'No forms found', 'sprout-invoices' ) );
		$forms = FrmForm::get_published_forms();
		if ( ! empty( $forms ) ) {
			$frdbl_options = array();
			foreach ( $forms as $form ) {
				$frdbl_options[ $form->id ] = ( ! isset( $form->name ) ) ? __( '(no title)', 'formidable' ) : esc_attr( FrmAppHelper::truncate( $form->name, 33 ) );
			}
		}

		$settings['formidable_integration'] = array(
				'title' => __( 'Formidable Submissions', 'sprout-invoices' ),
				'weight' => 6,
				'tab' => 'addons',
				'description' => sprintf( __( 'Refer to <a href="%s">this documentation</a> if you are unsure about these settings.', 'sprout-invoices' ), 'https://docs.sproutapps.co/article/8-integrating-gravity-forms-ninja-forms-or-custom-estimate-submissions' ),
				'settings' => array(
					self::FORMIDABLE_FORM_ID => array(
						'label' => __( 'Formidable Form', 'sprout-invoices' ),
						'option' => array(
							'type' => 'select',
							'options' => $frdbl_options,
							'default' => self::$formidable_form_id,
							'description' => sprintf( __( 'Select the submission form built with <a href="%s">Formidables</a>.', 'sprout-invoices' ), 'https://sproutapps.co/link/formidable-forms' ),
						),
					),
					self::GENERATION => array(
						'label' => __( 'Submission Records', 'sprout-invoices' ),
						'option' => array(
							'type' => 'select',
							'options' => array( 'estimate' => __( 'Estimate', 'sprout-invoices' ), 'invoice' => __( 'Invoice', 'sprout-invoices' ), 'client' => __( 'Client Only', 'sprout-invoices' ) ),
							'default' => self::$generation,
							'description' => __( 'Select the type of records you would like to be created. Note: estimates and invoices create client records.', 'sprout-invoices' ),
						),
					),
					self::FORM_ID_MAPPING => array(
						'label' => __( 'Formidable ID Mapping', 'sprout-invoices' ),
						'option' => array( __CLASS__, 'show_formidable_form_field_mapping' ),
					),
				),
		);

		return $settings;
	}

	public static function add_settings_options( $options = array() ) {
		$save_options = array();
		$form_mapping_options = get_option( self::FORM_ID_MAPPING, array() );
		$mapping_options = self::mapping_options();
		foreach ( $mapping_options as $key => $title ) {
			$value = ( isset( $form_mapping_options[ $key ] ) ) ? $form_mapping_options[ $key ] : '' ;
			$save_options[ SI_Settings_API::_sanitize_input_for_vue( 'si_invoice_sub_mapping_' . $key ) ] = $value;
		}
		return array_merge( $save_options, $options );
	}

	public static function show_formidable_form_field_mapping( $fields = array() ) {
		$fields = self::mapping_options();
		foreach ( $fields as $name => $label ) {
			$value = ( isset( self::$form_mapping[ $name ] ) ) ? self::$form_mapping[ $name ] : '' ;
			printf( '<div class="si_input_field_wrap si_field_wrap_input_select si_form_int"><label class="si_input_label">%2$s</label><input v-model="vm.si_invoice_sub_mapping_%4$s" type="text" name="si_invoice_sub_mapping_%1$s" id="si_invoice_sub_mapping_%1$s" class="si_input" value="%3$s"></div><br/>', $name, $label, $value, SI_Settings_API::_sanitize_input_for_vue( $name ) );
		}

		printf( '<p class="description">%s</p>', __( 'Map the field IDs of your form to the data name.', 'sprout-invoices' ) );
	}


	public static function save_mappings() {
		$mappings = array();
		$fields = self::mapping_options();
		foreach ( $fields as $key => $label ) {
			$mappings[ $key ] = isset( $_POST[ 'si_invoice_sub_mapping_' . $key ] ) ? $_POST[ 'si_invoice_sub_mapping_' . $key ] : '';
		}
		update_option( self::FORM_ID_MAPPING, $mappings );
	}


	public static function mapping_options() {
		$options = array(
				'subject' => __( 'Subject/Title', 'sprout-invoices' ),
				'line_item_list' => __( 'Pre-defined Item Selection (Checkboxes Field)', 'sprout-invoices' ),
				'email' => __( 'Email', 'sprout-invoices' ),
				'client_name' => __( 'Client/Company Name', 'sprout-invoices' ),
				'first_name' => __( 'First Name', 'sprout-invoices' ),
				'last_name' => __( 'Last Name', 'sprout-invoices' ),
				'contact_street' => __( 'Street Address', 'sprout-invoices' ),
				'contact_city' => __( 'City', 'sprout-invoices' ),
				'contact_zone' => __( 'State/Province', 'sprout-invoices' ),
				'contact_postal_code' => __( 'Zip/Postal', 'sprout-invoices' ),
				'contact_country' => __( 'Country', 'sprout-invoices' ),
			);
		return $options;
	}

	//////////////////////////////
	// Populate Front-end Form //
	//////////////////////////////

	public static function add_basic_field( $fields ) {
		$fields['si_line_items'] = __( 'SI Line Items', 'sprout-invoices' ); // the key for the field and the label
		return $fields;
	}

	public static function show_my_front_field( $field, $field_name ) {
		if ( 'si_line_items' !== $field['type'] ) {
			return;
		}

		$field_id = $field['id'];
		$field['value'] = stripslashes_deep( $field['value'] );

		$items_and_products = Predefined_Items::get_items_and_products();
		$item_groups = apply_filters( 'si_predefined_items_for_submission', $items_and_products );
		$list_options_span_class = apply_filters( 'formidable_display_list_options_span_class', 'si_line_items', $field_id );

		$x = 0;
		?>
			<div id="frm_field_<?php echo esc_attr( $field_id ) ?>_container" class="frm_form_field form-field  frm_top_container">
				<div class="frm_opt_container">
					<div class="frm_checkbox" id="frm_checkbox_<?php echo esc_attr( $field_id ) ?>-0">
						<?php foreach ( $item_groups as $type => $items ) : ?>
							<?php foreach ( $items as $item ) : ?>
								<?php
									$value = $item['id'];
									$label = sprintf( '&nbsp;&nbsp;<b>%s</b><br/><small>%s</small>', $item['title'], $item['content'] );
									printf( '<label id="field_%1$s_%2$s_label"><input id="ninja_forms_field_%1$s_%2$s" name="item_meta[%1$s][]" type="checkbox" class="%5$s field_%1$s" value="%3$s""/>%4$s</label>', $field_id, $x, $value, $label, $list_options_span_class );
									$x++;
										?>
							<?php endforeach ?>
						<?php endforeach ?>

					</div>
				</div>
			</div>
		<?php
	}

	////////////////////
	// Process forms //
	////////////////////

	public static function maybe_process_formidable_form( $entry_id, $form_id ) {
		/**
		 * Only a specific form do this process
		 */
		if ( (int) $form_id !== (int) self::$formidable_form_id ) {
			return;
		}
		/**
		 * Set variables
		 * @var string
		 */
		$subject = isset( $_POST['item_meta'][ self::get_form_map_id( 'subject' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'subject' ) ] : '';
		$email = isset( $_POST['item_meta'][ self::get_form_map_id( 'email' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'email' ) ] : '';
		$client_name = isset( $_POST['item_meta'][ self::get_form_map_id( 'client_name' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'client_name' ) ] : '';
		$full_name = isset( $_POST['item_meta'][ self::get_form_map_id( 'first_name' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'first_name' ) ] . ' ' . $_POST['item_meta'][ self::get_form_map_id( 'last_name' ) ] : '';
		$website = isset( $_POST['item_meta'][ self::get_form_map_id( 'website' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'website' ) ] : '';
		$contact_street = isset( $_POST['item_meta'][ self::get_form_map_id( 'contact_street' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'contact_street' ) ] : '';
		$contact_city = isset( $_POST['item_meta'][ self::get_form_map_id( 'contact_city' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'contact_city' ) ] : '';
		$contact_zone = isset( $_POST['item_meta'][ self::get_form_map_id( 'contact_zone' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'contact_zone' ) ] : '';
		$contact_postal_code = isset( $_POST['item_meta'][ self::get_form_map_id( 'contact_postal_code' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'contact_postal_code' ) ] : '';
		$contact_country = isset( $_POST['item_meta'][ self::get_form_map_id( 'contact_country' ) ] ) ? $_POST['item_meta'][ self::get_form_map_id( 'contact_country' ) ] : '';

		/**
		 * Build line item array
		 * @var array
		 */
		$line_item_list = array();
		if ( ! empty( $_POST['item_meta'][ self::get_form_map_id( 'line_item_list' ) ] ) ) {
			$line_item_list = $_POST['item_meta'][ self::get_form_map_id( 'line_item_list' ) ];
			if ( ! is_array( $line_item_list ) ) {
				$line_item_list = array( $line_item_list );
			}
		}

		$doc_id = 0;

		if ( 'invoice' === self::$generation ) {
			/**
			 * Create invoice
			 * @var array
			 */
			$invoice_args = array(
				'status' => SI_Invoice::STATUS_PENDING,
				'subject' => $subject,
				'line_item_list' => $line_item_list,
				'fields' => $_POST['item_meta'],
				'form' => $_POST['item_meta'],
				'history_link' => sprintf( '<a href="%s">#%s</a>', add_query_arg( array( 'post' => $entry_id ), admin_url( 'post.php?action=edit' ) ), $entry_id ),
			);
			$invoice = self::maybe_create_invoice( $invoice_args, $entry_id );
			$doc_id = $invoice->get_id();
		}

		if ( 'estimate' === self::$generation ) {
			/**
			 * Create estimate
			 * @var array
			 */
			$estimate_args = array(
				'status' => SI_Estimate::STATUS_PENDING,
				'subject' => $subject,
				'line_item_list' => $line_item_list,
				'fields' => $_POST['item_meta'],
				'form' => $_POST['item_meta'],
				'history_link' => sprintf( '<a href="%s">#%s</a>', add_query_arg( array( 'post' => $entry_id ), admin_url( 'post.php?action=edit' ) ), $entry_id ),
			);
			$estimate = self::maybe_create_estimate( $estimate_args, $entry_id );
			$doc_id = $estimate->get_id();
		}

		/**
		 * Make sure an invoice was created, if so create a client
		 */
		$client_args = array(
			'email' => $email,
			'client_name' => $client_name,
			'full_name' => $full_name,
			'website' => $website,
			'contact_street' => $contact_street,
			'contact_city' => $contact_city,
			'contact_zone' => $contact_zone,
			'contact_postal_code' => $contact_postal_code,
			'contact_country' => $contact_country,
		);

		if ( 'estimate' === self::$generation ) {
			$client_args = apply_filters( 'si_estimate_submission_maybe_process_formidable_client_args', $client_args, $_POST['item_meta'], $entry_id, $form_id );
			$doc = $estimate;
		} elseif ( 'invoice' === self::$generation ) {
			$client_args = apply_filters( 'si_invoice_submission_maybe_process_formidable_client_args', $client_args, $_POST['item_meta'], $entry_id, $form_id );
			$doc = $invoice;
		}

		self::maybe_create_client( $doc_id, $client_args );

		do_action( 'si_formidable_submission_complete', $doc_id );

		self::maybe_redirect_after_submission( $doc_id );
	}

	public static function maybe_redirect_after_submission( $doc_id ) {
		if ( apply_filters( 'si_invoice_submission_redirect_to_invoice', true ) ) {
			if ( get_post_type( $doc_id ) == ( SI_Invoice::POST_TYPE || SI_Estimate::POST_TYPE ) ) {
				$url = get_permalink( $doc_id );
				wp_redirect( $url );
				die();
			}
		}
	}
}
SI_Formidable::init();
