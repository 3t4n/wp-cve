<?php

use Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils;

if ( ! class_exists( 'WC_Szamlazz_Settings' ) ) :

if ( ! class_exists( 'WC_Szamlazz_Background_Migrator', false ) ) {
	include_once dirname( __FILE__ ) . '/class-background-migrate.php';
}

class WC_Szamlazz_Settings extends WC_Integration {
	public static $activation_url;
	protected static $background_migrator;

	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		$this->id = 'wc_szamlazz';
		$this->method_title = __( 'Számlázz.hu', 'wc-szamlazz' );

		//Setup migration utility
		if ( ! self::$background_migrator ) {
			self::$background_migrator = new WC_Szamlazz_Background_Migrator();
		}

		// Load the settings.
		if(isset($_GET['tab']) && ($_GET['tab'] == 'integration' || $_GET['tab'] == 'debug')) {
			add_action( 'woocommerce_init', function(){
				$this->init_form_fields();
				$this->init_settings();
			} );
		}

		//Customize admin screen design and layout
		add_filter( 'admin_body_class', array( $this, 'add_class_to_body') );
		add_action( 'woocommerce_sections_integration', array($this, 'wrap_start'), 20 );
		add_action( 'woocommerce_settings_integration', array($this, 'wrap_end'), 20 );

		// Action to save the fields
		add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'save_payment_options' ) );

		//Check and save PRO version
		add_action( 'wp_ajax_wc_szamlazz_migrate', array( $this, 'migrate_settings' ) );

		//Get email ids
		add_action( 'wp_ajax_wc_szamlazz_get_email_ids', array( $this, 'get_email_ids_with_ajax' ) );

		//Refresh database tool
		add_filter( 'woocommerce_debug_tools', array( $this, 'refresh_database_tool') );

		//Hide metaboxes
		add_action( 'wp_ajax_wc_szamlazz_hide_rate_request', array( $this, 'hide_rate_request' ) );
		add_action( 'wp_ajax_wc_szamlazz_hide_addons', array( $this, 'hide_addons' ) );

		//Define activation url
		self::$activation_url = 'http://vpordermigrate.local/';
	}

	public function migrate_settings() {
		check_ajax_referer( 'wc-szamlazz-migrate', 'security' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Cheatin&#8217; huh?' ) );
		}

		//Check if already migrated
		if(get_option('_wc_szamlazz_migrated')) {
			return false;
		}

		//Pairs old and new setting values
		$option_pairs = array(
			'agent_key' => 'api_key',
			'invoice_type' => 'invoice_type',
			'invoice_type_company' => 'invoice_type_company',
			'payment_deadline' => 'payment_deadline',
			'note' => 'note',
			'afakulcs' => 'afakulcs',
			'prefix' => 'elotag',
			'language' => 'nyelv',
			'language_wpml' => 'nyelv_wpml',
			'unit_type' => 'mennyisegi_egyseg',
			'company_name' => 'cegnev',
			'hide_shipping_details' => 'hide_shipping_info',
			'auto_generate' => 'auto',
			'auto_invoice_status' => 'auto_status',
			'auto_void_status' => 'auto_sztorno',
			'ipn_close_order' => 'ipn_close_order',
			'delivery_note' => 'szallitolevel',
			'separate_coupon' => 'coupon_type',
			'separate_coupon_name' => 'coupon_type_name',
			'separate_coupon_desc' => 'coupon_type_desc',
			'discount_note' => 'discount_note',
			'hide_free_shipping' => 'hide_free_shipping',
			'vat_number_form' => 'vat_number_form',
			'vat_number_validate' => 'vat_number_validate',
			'vat_number_form_min' => 'vat_number_form_min',
			'vat_number_notice' => 'vat_number_notice',
			'auto_email' => 'auto_email',
			'email_attachment_file' => 'attachment',
			'customer_download' => 'customer_download',
			'receipt' => 'nyugta',
			'receipt_prefix' => 'nyugta_elotag',
			'receipt_note' => 'nyugta_megjegyzes',
			'receipt_email' => 'nyugta_email',
			'receipt_email_subject' => 'nyugta_email_subject',
			'receipt_email_text' => 'nyugta_email_text',
			'receipt_email_replyto' => 'nyugta_email_replyto',
			'nev_csere' => 'nev_csere',
			'debug' => 'debug',
			'error_email' => 'error_email',
			'defer' => 'defer',
			'uninstall' => 'uninstall',
			'accounting_details_enabled' => 'accounting_details_enabled',
			'accounting_details_vevo_azonosito' => 'accounting_details_vevo_azonosito',
		);

		//Migrate old settings to new values
		$old_settings = get_option('woocommerce_wc_szamlazz_settings');
		foreach ($option_pairs as $new_option_id => $old_option_id) {
			$old_option_value = $old_settings['wc_szamlazz_'.$old_option_id];
			$this->update_option($new_option_id, $old_option_value);
		}

		//Save payment options
		$accounts = array();
		$old_payment_methods = get_option('wc_szamlazz_payment_method_options');
		foreach ($old_payment_methods as $payment_method_id => $payment_method) {
			$accounts[$payment_method_id] = array(
				'deadline' => $payment_method['deadline'],
				'complete' => $payment_method['complete'],
				'proform' => $payment_method['request'],
				'deposit' => false
			);
		}
		update_option( 'wc_szamlazz_payment_method_options_v2', $accounts );

		//Migrate orders too. This might take a while
		self::$background_migrator->push_to_queue( array( 'task' => 'migrate_orders' ) );
		self::$background_migrator->save()->dispatch();
		update_option('_wc_szamlazz_migrating', true);

		wp_die();
	}

	//Check if we are on the settings page
	public function is_settings_page() {
		global $current_section;
		$is_settings_page = false;
		if( isset( $_GET['page'], $_GET['tab'] ) && 'wc-settings' === $_GET['page'] && 'integration' === $_GET['tab'] ) {
			if ( !$current_section ) {
				$integrations = WC()->integrations->get_integrations();

				if(!empty($integrations)) {
					$current_section_id = current( $integrations )->id;
					if($current_section_id === $this->id) {
						$is_settings_page = true;
					}
				}
			} else if($current_section === $this->id) {
				$is_settings_page = true;
			}
		}
		return $is_settings_page;
	}

	//Add class to body for styling purposes
	public function add_class_to_body($extra_class) {
		if($this->is_settings_page()) {
			$extra_class = $this->id.'_settings_page';
		}

		return $extra_class;
	}

	public function wrap_start() {
		if($this->is_settings_page()) {
			echo '<div class="wc-szamlazz-settings-wrapper">';
			echo '<div class="wc-szamlazz-settings-content">';
		}
	}

	public function wrap_end() {
		if($this->is_settings_page()) {
			echo '</div>';
			$addons = $this->get_addons();
			include( dirname( __FILE__ ) . '/views/html-admin-sidebar.php' );
			echo '</div>';
		}
	}

	public function get_addons() {
		$addons = array();

		$addons['salesperson'] = array(
			'name' => __('Salesperson identification', 'wc-szamlazz'),
			'desc' => __('You can set separate invoice prefixes for each user. Useful feature for orders created by different salespersons.', 'wc-szamlazz')
		);

		$addons['aam_27'] = array(
			'name' => __('AAM and 27% VAT rate at the same time', 'wc-szamlazz'),
			'desc' => __('You can change between AAM and 27% VAT rate while manually creating an invoice.', 'wc-szamlazz')
		);

		$addons['paper_fee'] = array(
			'name' => __('Paper invoice fee', 'wc-szamlazz'),
			'desc' => __('The customer can choose a paper invoice on the checkout form for an extra fee.', 'wc-szamlazz')
		);

		return apply_filters('wc_szamlazz_addons', $addons);
	}

	//Initialize integration settings form fields.
	public function init_form_fields() {
		$pro_required = false;
		$pro_icon = false;
		$checkout_block_used = false;
		if(!WC_Szamlazz_Pro::is_pro_enabled()) {
			$pro_required = true;
			$pro_icon = '<i class="wc_szamlazz_pro_label">PRO</i>';
		}

		//Check for block compatibility
		if(class_exists( 'Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils' ) && CartCheckoutUtils::is_checkout_block_default()){
			$checkout_block_used = true;
		}
		
		//Authentication settings
		$settings_top = array(
			'section_auth' => array(
				'title' => __( 'Account settings', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'Enter the Agent Key that you generated on szamlazz.hu.', 'wc-szamlazz' ),
			),
			'agent_key' => array(
				'title' => __( 'Számla Agent key', 'wc-szamlazz' ),
				'type' => 'text',
				'description' => __('To create an Agent Key, sign in into Számlázz.hu, go to the dashboard and click on the key icon at the bottom. <br><a target="_blank" href="https://visztpeter.me/dokumentacio/">Where can I find the agent key?</a>', 'wc-szamlazz')
			),
			'multiple_accounts' => array(
				'title' => __( 'I have multiple accounts', 'wc-szamlazz' ).$pro_icon,
				'type' => 'checkbox',
				'disabled' => $pro_required,
				'class' => 'wc-szamlazz-toggle-group-accounts',
				'description' => __('You can set up more accounts based on various conditions, like payment and shipping method.', 'wc-szamlazz')
			),
			'multiple_accounts_table' => array(
				'type' => 'wc_szamlazz_settings_accounts',
				'class' => 'wc-szamlazz-toggle-group-accounts-item',
				'description' => __('If the condition is not matched, it will use the default Számla Agent Key for automatic invoice generation. You can change the account if you are creating an invoice manually.', 'wc-szamlazz')
			)
		);

		//Every other settings
		$settings_rest = array(

			//General settings
			'section_invoice' => array(
				'title' => __( 'Invoice settings', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'General settings related to invoices.', 'wc-szamlazz' ),
			),
			'invoice_type' => array(
				'title' => __( 'Invoice type', 'wc-szamlazz' ),
				'class' => 'chosen_select',
				'css' => 'min-width:300px;',
				'type' => 'select',
				'options' => array(
					'electronic' => __( 'Electronic', 'wc-szamlazz' ),
					'paper' => __( 'Paper', 'wc-szamlazz' )
				)
			),
			'invoice_type_company' => array(
				'title' => __( 'Invoice type on company orders', 'wc-szamlazz' ),
				'class' => 'chosen_select',
				'css' => 'min-width:300px;',
				'type' => 'select',
				'options' => array(
					'' => __( 'Default', 'wc-szamlazz' ),
					'electronic' => __( 'Electronic', 'wc-szamlazz' ),
					'paper' => __( 'Paper', 'wc-szamlazz' )
				),
				'desc_tip' => __( "Invoice type for company orders(if the customer entered a company name at the checkout form, it's a company order).", 'wc-szamlazz')
			),
			'payment_deadline' => array(
				'title' => __( 'Payment deadline(days)', 'wc-szamlazz' ),
				'type' => 'number',
			),
			'notes' => array(
				'title' => __( 'Notes', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_notes',
				'description' => __("You can use the following shortcodes in the description that appears on the invoice:<br>{customer_email} - The customer's e-mail address<br>{customer_phone} - The customer's phone number<br>{transaction_id} - Payment's transaction ID<br>{shipping_address} - Customer's shipping address<br>{customer_note} - Customer's order note<br>{order_number} - Order number", "wc-szamlazz")
			),
			'afakulcs' => array(
				'type' => 'select',
				'class' => 'chosen_select',
				'css' => 'min-width:300px;',
				'title' => __( 'VAT rates', 'wc-szamlazz' ),
				'options' => WC_Szamlazz_Helpers::get_vat_types(),
				'description' => __( "The VAT rate that is visible on the invoice. By default, it will use the values set in WooCommerce / Tax menu, if the values match the following tax types: TAM, AAM, EU, EUK, MAA, F.AFA, ÁKK. If there's no match, it will calculate the percentage based on the net and gross prices.", 'wc-szamlazz' ),
				'default' => ''
			),
			'vat_overrides_custom' => array(
				'type' => 'checkbox',
				'disabled' => $pro_required,
				'title' => __( 'Custom VAT rate overrrides', 'wc-szamlazz' ),
				'description' => __( 'Advanced settings to setup VAT rates.', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-vat-override',
			),
			'vat_overrides' => array(
				'type' => 'wc_szamlazz_settings_vat_overrides',
				'title' => '',
				'class' => 'wc-szamlazz-toggle-group-vat-override-item'
			),
			'afakulcs_eu' => array(
				'type'		 => 'checkbox',
				'title'    => __( 'EUT vat rate', 'wc-szamlazz' ),
				'description' => __( "If there's 0% VAT on the invoice, but the customer entered an EU VAT Number, it will use the EUT vat rate instead of 0%.", 'wc-szamlazz' ),
				'default'  => ''
			),
			'afakulcs_euk' => array(
				'type'		 => 'checkbox',
				'title'    => __( 'EUKT vat rate', 'wc-szamlazz' ),
				'description' => __( "If there's 0% VAT on the invoice and the customer's billing and shipping location is outside of the EU, it will use the EUKT vat rate.", 'wc-szamlazz' ),
				'default'  => ''
			),
			'prefix' => array(
				'title' => __( 'Invoice prefix', 'wc-szamlazz' ),
				'type' => 'text',
				'desc_tip' => __('Make sure the prefix exists in Számlázz.hu / settings / prefixes.', 'wc-szamlazz')
			),
			'language' => array(
				'type' => 'select',
				'class' => 'chosen_select',
				'css' => 'min-width:300px;',
				'title' => __( 'Invoice language', 'wc-szamlazz' ),
				'options' => $this->get_languages(),
				'default' => 'hu'
			),
			'language_wpml' => array(
				'title' => __( 'WPML, Polylang & Translatepress compatibility', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __('If turned on, the language code stored by WPML, Polylang, or Translatepress will be used to create the invoice.', 'wc-szamlazz')
			),
			'unit_type' => array(
				'title' => __( 'Quantity unit', 'wc-szamlazz' ),
				'type' => 'text',
				'desc_tip' => __('This will be the default quantity unit on the invoice. You can change this for each product one-by-one on the Advanced tab too.', 'wc-szamlazz')
			),
			'company_name' => array(
				'title' => __( 'Company name + name', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __('If turned on and the buyer enters a company name, the regular first/last name will be visible after the company name on the invoice.', 'wc-szamlazz')
			),
			'hide_shipping_details' => array(
				'title' => __( 'Hide shipping details', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __("If turned on, it will hide the buyer's shipping address in the invoice.", 'wc-szamlazz')
			),
			'hide_item_notes' => array(
				'title' => __( 'Hide line item notes', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __("If turned on, it will hide the note section of the line items.", 'wc-szamlazz')
			),
			'bank_name' => array(
				'title' => __( 'Bank name', 'wc-szamlazz' ),
				'type' => 'text',
				'desc_tip' => __("The seller's bank name. If empty, it will use the value entered on szamlazz.hu.", 'wc-szamlazz')
			),
			'bank_number' => array(
				'title' => __( 'Bank account number', 'wc-szamlazz' ),
				'type' => 'text',
				'desc_tip' => __("The seller's bank account number. If empty, it will use the value entered on szamlazz.hu.", 'wc-szamlazz')
			),
			'template' => array(
				'type' => 'select',
				'class' => 'chosen_select',
				'css' => 'min-width:300px;',
				'title' => __( 'Document design', 'wc-szamlazz' ),
				'options' => array(
					'SzlaMost' => __('Számlázz.hu recommended design', 'wc-szamlazz'),
					'SzlaAlap' => __('Traditional design', 'wc-szamlazz'),
					'SzlaNoEnv' => __('Envelope-friendly', 'wc-szamlazz'),
					'Szla8cm' => __('Thermal printer compatible (8 cm wide)', 'wc-szamlazz'),
					'SzlaTomb' => __('Retro design', 'wc-szamlazz'),
					//'SzlaFuvarlevelesAlap' => __('Invoice combined with waybill', 'wc-szamlazz'),
				),
				'default' => 'SzlaMost'
			),
			'advanced_settings' => array(
				'type' => 'checkbox',
				'disabled' => $pro_required,
				'title' => __( 'Advanced settings', 'wc-szamlazz' ).$pro_icon,
				'description' => __( 'Overwrite the bank account number, the invoice block and the language based on conditional logic.', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-advanced',
			),
			'advanced' => array(
				'type' => 'wc_szamlazz_settings_advanced',
				'title' => '',
				'class' => 'wc-szamlazz-toggle-group-advanced-item'
			),

			//Automatic settings
			'section_automatic' => array(
				'title' => __( 'Automatization', 'wc-szamlazz' ).$pro_icon,
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'Settings related to automatic invoicing. If the mark as completed option is checked for a specific payment method, the invoice will be marked as paid in számlázz.hu if you close the order. If you turn on the proforma invoice option, a proforma invoice will be created for the order. The payment deadline can be set individually(the global option is above)', 'wc-szamlazz' ),
			),
			'auto_invoice_custom' => array(
				'type' => 'checkbox',
				'disabled' => $pro_required,
				'title' => __( 'Custom automations', 'wc-szamlazz' ),
				'description' => __( 'Advanced settings to setup automations.', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-automation',
			),
			'automations' => array(
				'type' => 'wc_szamlazz_settings_automations',
				'title' => '',
				'class' => 'wc-szamlazz-toggle-group-automation-item'
			),
			'auto_invoice_status' => array(
				'type' => 'wc_szamlazz_settings_auto_status',
				'disabled' => $pro_required,
				'title' => __( 'Automatic billing', 'wc-szamlazz' ),
				'options' => $this->get_order_statuses(),
				'description' => __( 'The invoice will be generated automatically if the order is in this status.', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-automation-item-hide',
			),
			'auto_void_status' => array(
				'type' => 'wc_szamlazz_settings_auto_status',
				'title' => __( 'Automatic reverse invoice', 'wc-szamlazz' ),
				'disabled' => $pro_required,
				'options' => $this->get_order_statuses(),
				'description' => __( 'A reverse invoice will be generated automatically if the order is in this status.', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-automation-item-hide',
			),
			'payment_methods' => array(
				'title' => __( 'Payment methods', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_payment_methods',
				'disabled' => $pro_required,
			),
			'ipn_url' => array(
				'title' => __( 'Szamlazz.hu IPN Url', 'wc-szamlazz' ).$pro_icon,
				'type' => 'wc_szamlazz_settings_ipn',
				'disabled' => $pro_required,
				'default' => $this->get_ipn_url(),
				'custom_attributes' => array(
					'readonly' => 'readonly'
				),
				'description' => __( 'Your webshop can get notified when an invoice has been paid. This message is sent by Számlázz.hu to a specific web address. You can define this address on <a href="https://www.szamlazz.hu/szamla/?page=beallitasok-szamlaalapertelmezett#cegpaynotifurl" target="_blank">this</a> page.', 'wc-szamlazz' )
			),
			'ipn_close_order' => array(
				'title' => __( 'Order status based on IPN', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_auto_ipn',
				'options' => $this->get_order_statuses(),
				'disabled' => $pro_required,
				'default' => 'no',
				'desc_tip' => __( 'If a deposit or proforma invoice was marked as payment completed in Számlázz.hu and if it notifies your website via IPN about it, the order will be marked with this status.', 'wc-szamlazz' ),
			),
			'delivery_note' => array(
				'title' => __( 'Delivery note alongside with invoice', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __('If turned on, a delivery note will be generated alongside with the invoice.', 'wc-szamlazz')
			),

			//Coupons
			'section_coupons' => array(
				'title' => __( 'Discounts', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'Settings related to discounts and coupons on the invoice', 'wc-szamlazz' ),
			),
			'separate_coupon' => array(
				'title' => __( 'Discount as a separate line item', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'class' => 'wc-szamlazz-toggle-group-coupon',
				'desc_tip' => __('If turned on, the discount will be a new separate negative line item instead of the reduced prices shown for each order item.', 'wc-szamlazz')
			),
			'separate_coupon_name' => array(
				'title' => __( 'Discount line item name', 'wc-szamlazz' ),
				'type' => 'text',
				'placeholder' => __('Discount', 'wc-szamlazz'),
				'class' => 'wc-szamlazz-toggle-group-coupon-item',
				'desc_tip' => __('This is the line item name if a coupon is applied to the order. The default value is "Discount"', 'wc-szamlazz')
			),
			'separate_coupon_desc' => array(
				'title' => __( 'Discount line item description', 'wc-szamlazz' ),
				'type' => 'textarea',
				'placeholder' => '{kedvezmeny_merteke} discount with the following coupon code: {kupon}',
				'class' => 'wc-szamlazz-toggle-group-coupon-item',
				'desc_tip' => __("If turned on, the discount will be a new separate negative order item and you can change it's description here. Default value: {kedvezmeny_merteke} discount with the following coupon code: {kupon}", 'wc-szamlazz')
			),
			'discount_note' => array(
				'title' => __( 'Discounted product note', 'wc-szamlazz' ),
				'type' => 'textarea',
				'desc_tip' => __('You can display the original price and the amount of the discount in the comment of the line item. Use these shortcodes: {eredeti_ar}, {kedvezmeny_merteke}, {kedvezmenyes_ar}', 'wc-szamlazz')
			),
			'hide_free_shipping' => array(
				'title' => __( 'Hide free shipping', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __('If turned on, this will hide the free shipping invoice line item.', 'wc-szamlazz')
			),
			'disable_free_order' => array(
				'title' => __( 'Do not create an invoice for free orders', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __("If turned on, it won't create an invoice automatically for free orders.", 'wc-szamlazz'),
				'default' => 'yes'
			),
			'hide_free_items' => array(
				'title' => __( 'Hide free line items', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __("If turned on, this will hide all free invoice line item.", 'wc-szamlazz')
			),
		);

		$settings_vat = array(
			'section_vat_number' => array(
				'title' => __( 'VAT number', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'If the customer is a company, you are required to collect a VAT number. You can use these options to show an extra field on the checkout form.', 'wc-szamlazz' ),
			),
			'vat_number_form' => array(
				'title' => __( 'VAT number field during checkout', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'class' => 'wc-szamlazz-toggle-group-vatnumber',
				'description' => '',
				'desc_tip' => __( 'It will be collected on the checkout form. It is stored in the order details and will be visible on the invoice too.', 'wc-szamlazz' ),
			)
		);

		$setting_vat_eu = array(
			'title' => __( 'Accept EU VAT numbers', 'wc-szamlazz' ).$pro_icon,
			'type' => 'checkbox',
			'disabled' => $pro_required,
			'class' => 'wc-szamlazz-toggle-group-vatnumber-item',
			'description' => __( 'Customer can enter an EU VAT Number too(with country prefix) and it will be validated with VIES and also removes VAT from the order.', 'wc-szamlazz' ),
		);

		$settings_vat_shortcode = array(
			'vat_number_type' => array(
				'title' => __('VAT number field type'),
				'type' => 'wc_szamlazz_settings_radio',
				'class' => 'wc-szamlazz-toggle-group-vatnumber-item',
				'options' => array(
					'default' => __('Show the field if the Company Name field is filled', 'wc-szamlazz'),
					'show' => __('Show the VAT number field always(optional by default, required if company name filled)',  'wc-szamlazz'),
					'toggle' => __('Display a company billing checkbox to toggle both the company name and VAT number fields',  'wc-szamlazz')
				),
				'default' => ($this->get_option('vat_number_always_show') == 'yes') ? 'show' : 'default'
			),
			'vat_number_position' => array(
				'title' => __( 'VAT number field position', 'wc-szamlazz' ),
				'type' => 'number',
				'class' => 'wc-szamlazz-toggle-group-vatnumber-item',
				'default' => 35,
				'desc_tip' => __( 'The default priority is 35, which will place the field just after the company name field. Change this number if you want to place it somewhere else.', 'wc-szamlazz' ),
			),
			'vat_number_alignment' => array(
				'title' => __( 'Align company and VAT number field side by side', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'class' => 'wc-szamlazz-toggle-group-vatnumber-item',
				'desc_tip' => __( 'Show the company name and VAT number fields side by side, like the First name and Last name fields.', 'wc-szamlazz' ),
			),
			'vat_number_autofill' => array(
				'title' => __( 'Fill in the address automatically', 'wc-szamlazz' ).$pro_icon,
				'type' => 'checkbox',
				'disabled' => $pro_required,
				'class' => 'wc-szamlazz-toggle-group-vatnumber-item',
				'description' => __( 'If the customer enters a VAT number, it will validate it and also prefill the address and company name fields automatically.', 'wc-szamlazz' ),
			),
			'vat_number_eu' => array(
				'title' => __( 'Accept EU VAT numbers', 'wc-szamlazz' ).$pro_icon,
				'type' => 'checkbox',
				'disabled' => $pro_required,
				'class' => 'wc-szamlazz-toggle-group-vatnumber-item',
				'description' => __( 'Customer can enter an EU VAT Number too(with country prefix) and it will be validated with VIES and also removes VAT from the order.', 'wc-szamlazz' ),
			),
			'eu_vat_exempt' => array(
				'title'    => __( 'EU VAT exempt', 'wc-szamlazz' ),
				'label' => __('VAT exempt for orders inside the EU with valid VAT number', 'wc-szamlazz' ),
				'type'     => 'checkbox',
				'class' => 'wc-szamlazz-toggle-group-vatnumber-item',
				'default' => 'yes',
			),
		);

		//Show different info based on checkout block and shortcode page
		if($checkout_block_used) {
			$settings_vat['vat_number_form']['description'] = '<div class="wc-szamlazz-toggle-group-vatnumber-cell-show">'.__('Since you are using the Checkout Block, you can find the rest of the settings related to the VAT number field after you add the Vat Number Field block inside the Contact Information step.').'<img src="'.WC_Szamlazz()::$plugin_url.'assets/images/vat-number-block-info.png" class="wc-szamlazz-settings-vat-number-block-preview"></div></div>';
			$settings_vat['vat_number_form']['class'] = 'wc-szamlazz-toggle-group-vatnumber wc-szamlazz-toggle-group-vatnumber-block-info';
			$settings_vat['vat_number_eu'] = $setting_vat_eu;
		} else {
			$settings_vat = array_merge($settings_vat, $settings_vat_shortcode);
			$settings_vat['vat_number_eu'] = $setting_vat_eu;
		}

		$settings_emails = array(
			//Settings related to invoice notices
			'section_emails' => array(
				'title' => __( 'Invoice sharing', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'Settings related to sending the invoice to the customer.', 'wc-szamlazz' ),
			),
			'auto_email' => array(
				'title' => __( 'Invoice notification', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'class' => 'wc-szamlazz-toggle-group-email-notify',
				'desc_tip' => __( 'If turned on, Szamlazz.hu will email the customer about the invoice automatically.', 'wc-szamlazz' ),
				'default' => 'yes'
			),
			'auto_email_replyto' => array(
				'title' => __( 'Invoice notification e-mail address', 'wc-szamlazz' ),
				'type' => 'text',
				'class' => 'wc-szamlazz-toggle-group-email-notify-item',
				'desc_tip' => __('If someone responds to the notification e-mail, this will be the reply-to address.', 'wc-szamlazz')
			),
			'auto_email_subject' => array(
				'title' => __( 'Invoice notification e-mail subject', 'wc-szamlazz' ),
				'type' => 'text',
				'class' => 'wc-szamlazz-toggle-group-email-notify-item',
				'desc_tip' => __('The subject of the email notification sent by Számlázz.hu', 'wc-szamlazz')
			),
			'auto_email_message' => array(
				'title' => __( 'Invoice notification e-mail content', 'wc-szamlazz' ),
				'type' => 'text',
				'class' => 'wc-szamlazz-toggle-group-email-notify-item',
				'desc_tip' => __('The body of the email notification sent by Számlázz.hu.', 'wc-szamlazz')
			),
			'email_attachment' => array(
				'title' => __( 'Insert invoices into e-mails', 'wc-szamlazz' ).$pro_icon,
				'type' => 'checkbox',
				'disabled' => $pro_required,
				'class' => 'wc-szamlazz-toggle-group-emails',
				'desc_tip' => __( 'This option places the invoice download links into the WooCommerce e-mails. You should disable the invoice notification option in this case. You can select which document type is attached to the WooCommerce e-mails.', 'wc-szamlazz' ),
			),
			'email_attachment_file' => array(
				'title' => __( 'Attach invoices to e-mail', 'wc-szamlazz' ).$pro_icon,
				'type' => 'checkbox',
				'disabled' => $pro_required,
				'class' => 'wc-szamlazz-toggle-group-emails',
				'desc_tip' => __( 'This option attaches the invoices to the WooCommerce e-mails. You should disable the invoice notification option in this case. You can select which document type is attached to the WooCommerce e-mails.', 'wc-szamlazz' ),
			),
			'email_attachment_invoice' => array(
				'type' => 'multiselect',
				'title' => __( 'Invoice and receipt pairing', 'wc-szamlazz' ),
				'class' => 'wc-enhanced-select wc-szamlazz-toggle-group-emails-item',
				'default' => array('customer_completed_order', 'customer_invoice'),
				'options' => array(),
				'description' => '<span id="wc_szamlazz_load_email_ids_nonce" data-nonce="'.wp_create_nonce("wc_szamlazz_load_email_ids").'"></span>'
			),
			'email_attachment_proform' => array(
				'type' => 'multiselect',
				'title' => __( 'Proforma pairing', 'wc-szamlazz' ),
				'class' => 'wc-enhanced-select wc-szamlazz-toggle-group-emails-item',
				'default' => array('customer_processing_order', 'customer_on_hold_order'),
				'options' => array(),
			),
			'email_attachment_deposit' => array(
				'type' => 'multiselect',
				'title' => __( 'Deposit invoice pairing', 'wc-szamlazz' ),
				'class' => 'wc-enhanced-select wc-szamlazz-toggle-group-emails-item',
				'default' => array('customer_processing_order', 'customer_on_hold_order'),
				'options' => array(),
			),
			'email_attachment_void' => array(
				'type' => 'multiselect',
				'title' => __( 'Reverse invoice pairing', 'wc-szamlazz' ),
				'class' => 'wc-enhanced-select wc-szamlazz-toggle-group-emails-item',
				'default' => array('customer_refunded_order', 'cancelled_order'),
				'options' => array(),
			),
			'email_attachment_delivery' => array(
				'type' => 'multiselect',
				'title' => __( 'Delivery note pairing', 'wc-szamlazz' ),
				'class' => 'wc-enhanced-select wc-szamlazz-toggle-group-emails-item',
				'options' => array(),
			),
			'email_attachment_position' => array(
				'type' => 'select',
				'class' => 'wc-enhanced-select wc-szamlazz-toggle-group-emails-item',
				'title' => __( 'E-mail link position', 'wc-szamlazz' ),
				'desc_tip' => __( 'Where should the download links be included in the emails?', 'wc-szamlazz' ),
				'default' => 'beginning',
				'options' => array(
					'beginning' => __( 'At the beginning', 'wc-szamlazz' ),
					'end' => __( 'At the end', 'wc-szamlazz' ),
				),
			),
			'customer_download' => array(
				'title' => __( 'Invoices in My Orders', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __( 'If turned on, the user can download the invoices once he/she is logged in on the website, under the My Orders page.', 'wc-szamlazz' ),
				'default' => 'no'
			),
			'invoice_forward' => array(
				'title' => __( 'Invoice forwarding', 'wc-szamlazz' ).$pro_icon,
				'type' => 'text',
				'disabled' => $pro_required,
				'description' => __('You can enter multiple email addresses separated with a comma and every document created will be forwarded to these addresses. You can use this to setup automation with Zapirt or emailitin.com for example.', 'wc-szamlazz')
			),

			//Receipt
			'section_receipt' => array(
				'title' => __( 'E-Receipt', 'wc-szamlazz' ).$pro_icon,
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'You can find settings related to the E-Receipt. With this option, you can collect just the name and email address of the buyer. Instead of an invoice, a simple receipt will be generated that the user receives via email. Ideal for digital products, tickets. Please consult with your accountant first to make sure this is a good solution for you. If the buyer wants a regular invoice, a checkbox is added for that on the checkout form.', 'wc-szamlazz' ),
			),
			'receipt' => array(
				'disabled' => $pro_required,
				'title' => __( 'Receipt', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'class' => 'wc-szamlazz-toggle-group-receipt',
			),
			'receipt_prefix' => array(
				'title' => __( 'Receipt prefix', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-receipt-item',
				'type' => 'text'
			),
			'receipt_note' => array(
				'title' => __( 'Receipt note', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-receipt-item',
				'type' => 'text',
				'description' => __( 'General notice that is visible on the receipt', 'wc-szamlazz' )
			),
			'receipt_email' => array(
				'title' => __( 'Receipt emailing', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-receipt-item',
				'type' => 'checkbox',
				'desc_tip' => __( "Számlázz.hu can send out the receipt via email to the buyer's email address. It's not an attached receipt, but a simple text version that is included in the email body", 'wc-szamlazz' ),
			),
			'receipt_email_subject' => array(
				'title' => __( 'Receipt e-mail subject', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-receipt-item',
				'type' => 'text',
				'default' => __( 'Receipt', 'wc-szamlazz' )
			),
			'receipt_email_text' => array(
				'title' => __( 'Receipt e-mail text', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-receipt-item',
				'type' => 'textarea'
			),
			'receipt_email_replyto' => array(
				'title' => __( 'Receipt e-mail address', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-receipt-item',
				'type' => 'text',
				'desc_tip' => __( 'If someone replies to the email, this will be the reply address(reply-to)', 'wc-szamlazz' ),
			),
			'receipt_template' => array(
				'title' => __( 'Receipt template', 'wc-szamlazz' ),
				'class' => 'chosen_select wc-szamlazz-toggle-group-receipt-item',
				'css' => 'min-width:300px;',
				'type' => 'select',
				'options' => array(
					'' => __( 'A4 format', 'wc-szamlazz' ),
					'J' => __( 'J - Thermal paper without logo', 'wc-szamlazz' ),
					'L' => __( 'L - Thermal paper with logo', 'wc-szamlazz' )
				),
				'description' => '<a href="#" class="wc_szamlazz_receipt_templates_preview">'._x( 'Preview', 'Invoice layout preview', 'wc-szamlazz' ).'</a>'
			),
			'receipt_hidden_fields' => array(
				'type' => 'multiselect',
				'title' => __( 'Hidden checkout fields', 'wc-szamlazz' ),
				'class' => 'wc-enhanced-select wc-szamlazz-toggle-group-receipt-item',
				'default' => array('billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_country', 'billing_state', 'billing_phone', 'billing_address_2', 'wc_szamlazz_adoszam', 'order_comments'),
				'description' => __('These fields will be hidden on the checkout field if the customer only needs a receipt. The e-mail address field is required.', 'wc-szamlazz'),
				'options' => $this->get_receipt_billing_fields()
			),
			'receipts_invalid_payment_methods' => array(
				'type' => 'multiselect',
				'title' => __( 'Disabled payment methods', 'wc-szamlazz' ),
				'class' => 'wc-enhanced-select wc-szamlazz-toggle-group-receipt-item',
				'default' => array('billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_country', 'billing_state', 'billing_phone', 'billing_address_2', 'wc_szamlazz_adoszam', 'order_comments'),
				'description' => __('Select the payment methods that are not allowed to be used with e-receipts, like cash on delivery or bank transfer.', 'wc-szamlazz'),
				'options' => $this->get_payment_methods()
			),

			//Other settings
			'section_other' => array(
				'title' => __( 'Other settings', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_title'
			),
			'nev_csere' => array(
				'title' => __( 'Switch first name / last name', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __( 'If the order of the name is not correct on the invoice, you can use this option to switch the first/last name.', 'wc-szamlazz' ),
			),
			'debug' => array(
				'title' => __( 'Developer mode', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __( 'If turned on, the generated XML file will be logged in WooCommerce / Status / Logs. Can be used to debug issues.', 'wc-szamlazz' ),
			),
			'error_email' => array(
				'title' => __( 'E-mail address for error notifications', 'wc-szamlazz' ),
				'type' => 'text',
				'desc_tip' => __( "If you enter an email address, you will receive a notification if there was an error generating an invoice. Leave it empty if you don't need this(számlázz.hu also sends an email usually)", 'wc-szamlazz' ),
			),
			'defer' => array(
				'title' => __( 'Delayed invoice generation', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __( 'If turned on, the invoice will be generated in the background process, so the customer can reach the thank you page faster during checkout. Keep in mind that in this case, the invoice is not ready yet when the WooCommerce e-mail is sent, so make sure you have the email notification option turned on.', 'wc-szamlazz' ),
			),
			'corrected' => array(
				'title' => __( 'Cancellation using a correction invoice', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __( "If turned on, you can create a correction invoice instead of a reverse invoice manually for each order. It will create a correction invoice with negative prices. The automatic reverse invoice is still enabled with this option, so it's a good idea to turn that off if you plan to use this feature.", 'wc-szamlazz' ),
			),
			'tools' => array(
				'title' => __( 'Download icons in the tools column', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __( 'This will display the download icons in the Tools column on the orders management table.', 'wc-szamlazz' ),
			),
			'uninstall' => array(
				'title' => __( 'Delete settings', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __( 'If turned on, during plugin uninstall it will also delete all settings from the database.', 'wc-szamlazz' ),
			),
			'bulk_download_zip' => array(
				'title'    => __( 'Create a ZIP file during bulk download', 'wc-szamlazz' ),
				'type'     => 'checkbox',
				'disabled' => (!class_exists('ZipArchive')),
				'desc_tip' => __( 'If you want to download multiple invoices at once, this option will create a ZIP file with separate PDF files(the default option will merge all invoices into a single PDF).', 'wc-szamlazz' ),
				'description' => $this->get_bulk_zip_error()
			),
			'grouped_invoice_status' => array(
				'type' => 'select',
				'title' => __( 'Combined invoice order status', 'wc-szamlazz' ).$pro_icon,
				'class' => 'wc-enhanced-select',
				'default' => 'no',
				'disabled' => $pro_required,
				'options' => $this->get_order_statuses_for_void(),
				'desc_tip' => __( 'If you create a combined invoice, the related order statuses will change to this.', 'wc-szamlazz' ),
			),
			'delete_proform_too' => array(
				'title'    => __( 'Delete proform invoices after reverse invoice', 'wc-szamlazz' ),
				'type'     => 'checkbox',
				'default' => 'yes',
				'desc_tip' => __( 'If turned on, proform invoices will be deleted automatically when you create a reverse invoice.', 'wc-szamlazz' ),
			),
			'bulk_actions' => array(
				'type' => 'multiselect',
				'title' => __( 'Bulk actions', 'wc-szamlazz' ),
				'class' => 'wc-enhanced-select',
				'default' => WC_Szamlazz_Helpers::get_default_bulk_actions(),
				'description' => __('These options will be visible in the bulk actions dropdown.', 'wc-szamlazz'),
				'options' => array(
					'generate_invoice' => _x( 'Create invoices', 'bulk actions settings', 'wc-szamlazz' ),
					'print_invoice' => _x( 'Print invoices', 'bulk actions settings', 'wc-szamlazz' ),
					'download_invoice' => _x( 'Download invoices', 'bulk actions settings', 'wc-szamlazz' ),
					'generate_void' => _x( 'Create reverse invoices', 'bulk actions settings', 'wc-szamlazz' ),
					'generator' => _x( 'Create documents (PRO)', 'bulk actions settings', 'wc-szamlazz' ),
					'print_delivery' => _x( 'Print delivery notes', 'bulk actions settings', 'wc-szamlazz' ),
					'download_delivery' => _x( 'Download delivery notes', 'bulk actions settings', 'wc-szamlazz' ),
					'grouped_generate' => _x( 'Create grouped invoice (PRO)', 'bulk actions settings', 'wc-szamlazz' )
				)
			),
			'custom_order_statues' => array(
				'title' => __( 'Custom order statuses', 'wc-szamlazz' ),
				'type' => 'text',
				'desc_tip' => __( "If you are using a custom order status extension and the automation you setup for that status won't trigger, try to add the slug of your custom status. You can add multiple, separated with a comma.", 'wc-szamlazz' ),
			),

			//Accounting details
			'section_accounting' => array(
				'title' => __( 'Accounting details', 'wc-szamlazz' ).$pro_icon,
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'In this section, you can set various accounting details, like ledger numbers based on product categories. You can set these values for both Hungarian and international orders. ', 'wc-szamlazz' ),
			),
			'eusafa_custom' => array(
				'type' => 'checkbox',
				'disabled' => $pro_required,
				'title' => __( 'Disable data disclosure towards NTCAs', 'wc-szamlazz' ),
				'description' => __( 'If there is no hungarian VAT on the receipt, data disclosure towards NTCAs Online Invoice System might not be needed. You can use this option to setup a conditional logic for this.', 'wc-szamlazz' ),
				'class' => 'wc-szamlazz-toggle-group-eusafa',
			),
			'eusafa' => array(
				'type' => 'wc_szamlazz_settings_eusafa',
				'title' => '',
				'class' => 'wc-szamlazz-toggle-group-eusafa-item'
			),
			'accounting_details_enabled' => array(
				'disabled' => $pro_required,
				'title' => __( 'Accounting details', 'wc-szamlazz' ).$pro_icon,
				'class' => 'wc-szamlazz-toggle-group-accounting',
				'type' => 'checkbox',
			),
			'kata_compatibility' => array(
				'title'    => __( 'KATA compatibility', 'wc-szamlazz' ),
				'type'     => 'checkbox',
				'default' => 'no',
				'desc_tip' => __( 'If turned on and the invoice would include a VAT number, it will show an error message instead of creating an invoice.', 'wc-szamlazz' ),
			),
			'vat_exempt_abroad' => array(
				'title'    => __( 'VAT exempt for virtual company orders outside of the EU', 'wc-szamlazz' ),
				'type'     => 'checkbox',
				'default' => 'no',
				'desc_tip' => __( "If turned on, it will not collect VAT from the customer for orders that doesn't need shipping(virtual procuts), billing country is outside of the EU and the customer specified a company name.", 'wc-szamlazz' ),
			),
		);

		if(WC_Szamlazz()->get_option('accounting_details_enabled', 'no') == 'yes' || (isset($_POST['woocommerce_wc_szamlazz_accounting_details_enabled']) && $_POST['woocommerce_wc_szamlazz_accounting_details_enabled'])) {
			$settings_rest['accounting_details_vevo_azonosito'] = array(
				'class' => 'wc-szamlazz-toggle-group-accounting-item',
				'title' => __( 'Customer ledger number', 'wc-szamlazz' ),
				'type' => 'checkbox',
				'desc_tip' => __( "If the customer is a registered user, the user id will be stored as the buyer's ledger number.", 'wc-szamlazz' ),
			);
			$settings_rest['accounting_details'] = array(
				'class' => 'wc-szamlazz-toggle-group-accounting-item',
				'title' => __( 'Accounting details', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_accounting_details'
			);
		}

		$load_settings = false;
		if((isset($_POST['woocommerce_wc_szamlazz_agent_key']) && $_POST['woocommerce_wc_szamlazz_agent_key'] != '') || $this->get_option('agent_key')) {
			$load_settings = true;
		}

		//Show every field if
		$old_settings = get_option('woocommerce_wc_szamlazz_settings');
		if($old_settings && isset($old_settings['wc_szamlazz_password'])) {
			$load_settings = true;
		}

		//Temp
		$load_settings = true;

		if($load_settings) {
			$this->form_fields = apply_filters('wc_szamlazz_settings_fields', array_merge($settings_top, $settings_rest, $settings_vat, $settings_emails));
		} else {
			$this->form_fields = array_merge($settings_top);
		}
	}

	//Get order statues
	public function get_order_statuses() {
		if(function_exists('wc_order_status_manager_get_order_status_posts')) {
			$filtered_statuses = array();
			$custom_statuses = wc_order_status_manager_get_order_status_posts();
			foreach ($custom_statuses as $status ) {
				$filtered_statuses[ 'wc-' . $status->post_name ] = $status->post_title;
			}
			return $filtered_statuses;
		} else {
			return wc_get_order_statuses();
		}
	}

	//Order statuses
	public function get_order_statuses_for_void() {
		$built_in_statuses = array("no"=>__("Turned off")) + $this->get_order_statuses();
		return $built_in_statuses;
	}

	//Get IPN url
	public function get_ipn_url() {
		$url = '';
		if(WC_Szamlazz_Pro::is_pro_enabled()) {
			$ipn_id = add_option( '_wc_szamlazz_ipn_url', substr(md5(rand()),5)); //this will only store it if doesn't exists yet
			$url = home_url( '?wc_szamlazz_ipn_url=' ).get_option('_wc_szamlazz_ipn_url');
		}
		return $url;
	}

	//Get payment methods
	public static function get_payment_methods() {
		$available_gateways = WC()->payment_gateways->payment_gateways();

		$payment_methods = array();
		foreach ($available_gateways as $available_gateway) {
			if($available_gateway->enabled == 'yes') {
				$payment_methods[$available_gateway->id] = $available_gateway->title;
			}
		}
		return $payment_methods;
	}

	//Get shipping methods
	public static function get_shipping_methods() {
		$active_methods = array();
		$custom_zones = WC_Shipping_Zones::get_zones();
		$worldwide_zone = new WC_Shipping_Zone( 0 );
		$worldwide_methods = $worldwide_zone->get_shipping_methods();

		foreach ( $custom_zones as $zone ) {
			$shipping_methods = $zone['shipping_methods'];
			foreach ($shipping_methods as $shipping_method) {
				if ( isset( $shipping_method->enabled ) && 'yes' === $shipping_method->enabled ) {
					$method_title = $shipping_method->method_title;
					$active_methods[$shipping_method->id.':'.$shipping_method->instance_id] = $method_title.' ('.$zone['zone_name'].')';
				}
			}
		}

		foreach ($worldwide_methods as $shipping_method_id => $shipping_method) {
			if ( isset( $shipping_method->enabled ) && 'yes' === $shipping_method->enabled ) {
				$method_title = $shipping_method->method_title;
				$active_methods[$shipping_method->id.':'.$shipping_method->instance_id] = $method_title.' (Worldwide)';
			}
		}

		return $active_methods;
	}

	//Get email ids
	public function get_email_ids_with_ajax() {
		check_ajax_referer( 'wc_szamlazz_load_email_ids', 'nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Cheatin&#8217; huh?' ) );
		}
		$email_invoice_selected = $this->get_option('email_attachment_invoice');
		$document_types = array('invoice', 'proform', 'deposit', 'void', 'delivery');

		//Get registered emails
		$mailer = WC()->mailer();
		$email_templates = $mailer->get_emails();
		$emails = array();

		//Omit a few one thats not required at all
		$disabled = ['failed_order', 'customer_note', 'customer_reset_password', 'customer_new_account'];

		//Loop through each document type
		foreach ($document_types as $document_type) {

			//Get saved values
			$saved_values = $this->get_option('email_attachment_'.$document_type);
			if(!$saved_values) $saved_values = array();

			//Create options
			$options = array();
			foreach ( $email_templates as $email ) {
				if(!in_array($email->id,$disabled)) {
					$options[] = array(
						'label' => $email->get_title(),
						'selected' => in_array($email->id, $saved_values),
						'id' => $email->id
					);
				}
			}

			//Return select + options
			$emails[] = array(
				'field' => $document_type,
				'options' => $options
			);
		}

		wp_send_json_success($emails);
	}

	//Get available languages
	public function get_languages() {
		return WC_Szamlazz_Helpers::get_supported_languages();
	}

	//Get checkout form fields as an array
	public function get_receipt_billing_fields() {
		if ( ! class_exists( 'WC_Session' ) ) {
    	include_once( WP_PLUGIN_DIR . '/woocommerce/includes/abstracts/abstract-wc-session.php' );
		}

		WC()->session = new WC_Session_Handler;
		WC()->customer = new WC_Customer;
		$exclude = array('wc_szamlazz_receipt', 'billing_email');
		$fields = array();
		foreach (WC()->checkout->checkout_fields['billing'] as $field_id => $field) {
			if(!in_array($field_id, $exclude) && isset($field['label'])) {
				$fields[$field_id] = wp_strip_all_tags($field['label']);
			}
		}
		return $fields;
	}

	public function get_bulk_zip_error() {
		$message = '';
		if(	!class_exists('ZipArchive')) {
			$message = '<span class="wc-szamlazz-settings-error"><span class="dashicons dashicons-warning"></span> '.__('This feature requires the ZipArchive function and by the looks of it, this is not enabled on your website. You can ask your hosting provider for help.').'</span>';
		}
		return $message;
	}

	public function save_payment_options() {

		//Just in case, remove migrating message of already migrated
		if(get_option('_wc_szamlazz_migrated') && get_option('_wc_szamlazz_migrating')) {
			update_option('_wc_szamlazz_migrating', false);
		}

		//Save payment options
		$accounts = array();
		if ( isset( $_POST['wc_szamlazz_payment_options'] ) ) {
			foreach ($_POST['wc_szamlazz_payment_options'] as $payment_method_id => $payment_method) {
				$deadline = wc_clean($payment_method['deadline']);
				$complete = isset($payment_method['complete']) ? true : false;
				$proform = isset($payment_method['proform']) ? true : false;
				$deposit = isset($payment_method['deposit']) ? true : false;
				$name = isset($payment_method['name']) ? wc_clean($payment_method['name']) : '';
				$auto_disabled = isset($payment_method['auto_disabled']) ? true : false;

				$accounts[$payment_method_id] = array(
					'deadline' => $deadline,
					'complete' => $complete,
					'proform' => $proform,
					'deposit' => $deposit,
					'name' => $name,
					'auto_disabled' => $auto_disabled
				);
			}
		}
		update_option( 'wc_szamlazz_payment_method_options_v2', $accounts );

		//Save accounting details
		$accounts = array();
		if ( isset( $_POST['wc_szamlazz_accounting_details'] ) ) {
			foreach ($_POST['wc_szamlazz_accounting_details'] as $payment_method_id => $payment_method) {
				$afa_fokonyvi_szam_hu = wc_clean($payment_method['afa_fokonyvi_szam_hu']);
				$fokonyvi_szam_hu = wc_clean($payment_method['fokonyvi_szam_hu']);
				$gazd_esem_hu = wc_clean($payment_method['gazd_esem_hu']);
				$afa_gazd_esem_hu = wc_clean($payment_method['afa_gazd_esem_hu']);
				$afa_fokonyvi_szam_kulfold = wc_clean($payment_method['afa_fokonyvi_szam_kulfold']);
				$fokonyvi_szam_kulfold = wc_clean($payment_method['fokonyvi_szam_kulfold']);
				$gazd_esem_kulfold = wc_clean($payment_method['gazd_esem_kulfold']);
				$afa_gazd_esem_kulfold = wc_clean($payment_method['afa_gazd_esem_kulfold']);

				$accounts[$payment_method_id] = array(
					'afa_fokonyvi_szam_hu' => $afa_fokonyvi_szam_hu,
					'fokonyvi_szam_hu' => $fokonyvi_szam_hu,
					'gazd_esem_hu' => $gazd_esem_hu,
					'afa_gazd_esem_hu' => $afa_gazd_esem_hu,
					'afa_fokonyvi_szam_kulfold' => $afa_fokonyvi_szam_kulfold,
					'fokonyvi_szam_kulfold' => $fokonyvi_szam_kulfold,
					'gazd_esem_kulfold' => $gazd_esem_kulfold,
					'afa_gazd_esem_kulfold' => $afa_gazd_esem_kulfold
				);
			}
		}
		update_option( 'wc_szamlazz_accounting_details', $accounts );

		//Save multi account options
		$extra_accounts = array();
		if ( isset( $_POST['wc_szamlazz_additional_accounts'] ) ) {
			foreach ($_POST['wc_szamlazz_additional_accounts'] as $account_id => $account) {
				$name = wc_clean($account['name']);
				$key = wc_clean($account['key']);
				$condition = wc_clean($account['condition']);

				$extra_accounts[$account_id] = array(
					'name' => $name,
					'key' => $key,
					'condition' => $condition
				);
			}
		}
		update_option( 'wc_szamlazz_extra_accounts', $extra_accounts );

		//Save notes
		$notes = array();
		if ( isset( $_POST['wc_szamlazz_notes'] ) ) {
			foreach ($_POST['wc_szamlazz_notes'] as $note_id => $note) {

				$comment = wp_kses_post( trim( wp_unslash($note['note']) ) );
				$notes[$note_id] = array(
					'comment' => $comment,
					'conditional' => false
				);

				//If theres conditions to setup
				$condition_enabled = isset($note['condition_enabled']) ? true : false;
				$append_enabled = isset($note['append']) ? true : false;
				$conditions = (isset($note['conditions']) && count($note['conditions']) > 0);

				if($condition_enabled && $conditions) {
					$notes[$note_id]['conditional'] = true;
					$notes[$note_id]['conditions'] = array();
					$notes[$note_id]['logic'] = wc_clean($note['logic']);
					$notes[$note_id]['append'] = $append_enabled;

					foreach ($note['conditions'] as $condition) {
						if(isset($condition['category'])) {
							$condition_details = array(
								'category' => wc_clean($condition['category']),
								'comparison' => wc_clean($condition['comparison']),
								'value' => $condition[$condition['category']]
							);

							$notes[$note_id]['conditions'][] = $condition_details;
						}
					}
				}
			}
		}
		update_option( 'wc_szamlazz_notes', $notes );

		//Save automations
		$automations = array();
		if ( isset( $_POST['wc_szamlazz_automations'] ) ) {
			foreach ($_POST['wc_szamlazz_automations'] as $automation_id => $automation) {

				$document = sanitize_text_field($automation['document']);
				$trigger = sanitize_text_field($automation['trigger']);
				$complete = sanitize_text_field($automation['complete']);
				$complete_delay = sanitize_text_field($automation['complete_delay']);
				$deadline_start = sanitize_text_field($automation['deadline_start']);
				$deadline = sanitize_text_field($automation['deadline']);
				$paid = isset($automation['paid']) ? true : false;
				$id = sanitize_text_field($automation['id']);
				$automations[$automation_id] = array(
					'document' => $document,
					'trigger' => $trigger,
					'complete' => $complete,
					'complete_delay' => $complete_delay,
					'deadline_start' => $deadline_start,
					'deadline' => $deadline,
					'paid' => $paid,
					'id' => $id,
					'conditional' => false
				);

				//If theres conditions to setup
				$condition_enabled = isset($automation['condition_enabled']) ? true : false;
				$conditions = (isset($automation['conditions']) && count($automation['conditions']) > 0);

				if($condition_enabled && $conditions) {
					$automations[$automation_id]['conditional'] = true;
					$automations[$automation_id]['conditions'] = array();
					$automations[$automation_id]['logic'] = wc_clean($automation['logic']);
					$automations[$automation_id]['append'] = $append_enabled;

					foreach ($automation['conditions'] as $condition) {
						$condition_details = array(
							'category' => wc_clean($condition['category']),
							'comparison' => wc_clean($condition['comparison']),
							'value' => $condition[$condition['category']]
						);

						$automations[$automation_id]['conditions'][] = $condition_details;
					}
				}
			}
		}
		update_option( 'wc_szamlazz_automations', $automations );

		//Save vat overrides
		$vat_overrides = array();
		if ( isset( $_POST['wc_szamlazz_vat_overrides'] ) ) {
			foreach ($_POST['wc_szamlazz_vat_overrides'] as $vat_override_id => $vat_override) {
				$line_item = sanitize_text_field($vat_override['line_item']);
				$vat_type = sanitize_text_field($vat_override['vat_type']);
				$vat_overrides[$vat_override_id] = array(
					'line_item' => $line_item,
					'vat_type' => $vat_type,
					'conditional' => false
				);

				//If theres conditions to setup
				$condition_enabled = isset($vat_override['condition_enabled']) ? true : false;
				$conditions = (isset($vat_override['conditions']) && count($vat_override['conditions']) > 0);
				if($conditions && $condition_enabled) {
					$vat_overrides[$vat_override_id]['conditional'] = true;
					$vat_overrides[$vat_override_id]['conditions'] = array();
					$vat_overrides[$vat_override_id]['logic'] = wc_clean($vat_override['logic']);

					foreach ($vat_override['conditions'] as $condition) {
						$condition_details = array(
							'category' => wc_clean($condition['category']),
							'comparison' => wc_clean($condition['comparison']),
							'value' => $condition[$condition['category']]
						);

						$vat_overrides[$vat_override_id]['conditions'][] = $condition_details;
					}
				}
			}
		}

		update_option( 'wc_szamlazz_vat_overrides', $vat_overrides );

		//Save vat overrides
		$eusafas = array();
		if ( isset( $_POST['wc_szamlazz_eusafas'] ) ) {
			foreach ($_POST['wc_szamlazz_eusafas'] as $vat_override_id => $vat_override) {
				$eusafas[$vat_override_id] = array(
					'enabled' => true,
					'conditional' => false
				);

				//If theres conditions to setup
				$condition_enabled = isset($vat_override['condition_enabled']) ? true : false;
				$conditions = (isset($vat_override['conditions']) && count($vat_override['conditions']) > 0);
				if($conditions && $condition_enabled) {
					$eusafas[$vat_override_id]['conditional'] = true;
					$eusafas[$vat_override_id]['conditions'] = array();

					foreach ($vat_override['conditions'] as $condition) {
						$condition_details = array(
							'category' => wc_clean($condition['category']),
							'comparison' => wc_clean($condition['comparison']),
							'value' => $condition[$condition['category']]
						);

						$eusafas[$vat_override_id]['conditions'][] = $condition_details;
					}
				}
			}
		}

		update_option( 'wc_szamlazz_eusafa', $eusafas );

		//Save advanced options
		$advanced_options = array();
		if ( isset( $_POST['wc_szamlazz_advanced_options'] ) ) {
			foreach ($_POST['wc_szamlazz_advanced_options'] as $advanced_option_id => $advanced_option) {
				$property = sanitize_text_field($advanced_option['property']);
				$value = sanitize_text_field($advanced_option['value']);
				$advanced_options[$advanced_option_id] = array(
					'property' => $property,
					'value' => $value,
					'conditional' => false
				);

				if(!$value) continue;

				//If theres conditions to setup
				$condition_enabled = isset($advanced_option['condition_enabled']) ? true : false;
				$conditions = (isset($advanced_option['conditions']) && count($advanced_option['conditions']) > 0);
				if($conditions && $condition_enabled) {
					$advanced_options[$advanced_option_id]['conditional'] = true;
					$advanced_options[$advanced_option_id]['conditions'] = array();
					$advanced_options[$advanced_option_id]['logic'] = wc_clean($advanced_option['logic']);

					foreach ($advanced_option['conditions'] as $condition) {
						$condition_details = array(
							'category' => wc_clean($condition['category']),
							'comparison' => wc_clean($condition['comparison']),
							'value' => $condition[$condition['category']]
						);

						$advanced_options[$advanced_option_id]['conditions'][] = $condition_details;
					}
				}
			}
		}

		update_option( 'wc_szamlazz_advanced_options', $advanced_options );

		//Delete cookies
		delete_option('_wc_szamlazz_cookie_name');

		//Save version number
		update_option( '_wc_szamlazz_db_version', '4.5' );

		//Save checkbox groups
		$checkbox_groups = array('auto_invoice_status', 'auto_void_status');
		foreach ($checkbox_groups as $checkbox_group) {
			$checkbox_values = array();
			if ( isset( $_POST['wc_szamlazz_'.$checkbox_group] ) ) {
				foreach ($_POST['wc_szamlazz_'.$checkbox_group] as $checkbox_value) {
					$checkbox_values[] = wc_clean($checkbox_value);
				}
			}
			update_option('wc_szamlazz_'.$checkbox_group, $checkbox_values);
		}

	}

	public function refresh_database_tool($tools) {

		//Check if old version is installed already
		$settings = get_option('woocommerce_wc_szamlazz_settings');
		if($settings['wc_szamlazz_invoice_type']) {
			$tools['wc_szamlazz_migrate_settings'] = array(
				'name' => __( 'Számlázz.hu database upgrade', 'wc-szamlazz' ),
				'button' => __( 'Database upgrade', 'wc-szamlazz' ),
				'desc' => __( 'If you used an old version of this extension before and you cannot find your existing invoices, try this option to upgrade the database.', 'wc-szamlazz' ),
				'callback' => array( $this, 'migrate_orders_again' ),
			);
		}

		return $tools;
	}

	function migrate_orders_again() {
		self::$background_migrator->push_to_queue( array( 'task' => 'migrate_orders' ) );
		self::$background_migrator->save()->dispatch();
		update_option('_wc_szamlazz_migrating', true);
	}

	//Save an option with ajax, so the rate request widget can be hidden
	public function hide_rate_request() {
		check_ajax_referer( 'wc-szamlazz-hide-rate-request', 'nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Cheatin&#8217; huh?' ) );
		}
		update_option('_wc_szamlazz_hide_rate_request', true);
		wp_send_json_success();
	}

	//Save an option with ajax, so the addons widget can be hidden
	public function hide_addons() {
		check_ajax_referer( 'wc-szamlazz-hide-rate-request', 'nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Cheatin&#8217; huh?' ) );
		}
		update_option('_wc_szamlazz_hide_addons', true);
		wp_send_json_success();
	}

	//Slightly modified html for title section
	public function generate_wc_szamlazz_settings_title_html( $key, $data ) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for the additional accounts field
	public function generate_wc_szamlazz_settings_accounts_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for the notes field
	public function generate_wc_szamlazz_settings_notes_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for the notes field
	public function generate_wc_szamlazz_settings_payment_methods_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for the accounting fields
	public function generate_wc_szamlazz_settings_accounting_details_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for ipn field
	public function generate_wc_szamlazz_settings_ipn_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for order status selector field
	public function generate_wc_szamlazz_settings_auto_status_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for order status selector field
	public function generate_wc_szamlazz_settings_auto_ipn_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for order status selector field
	public function generate_wc_szamlazz_settings_automations_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for order status selector field
	public function generate_wc_szamlazz_settings_vat_overrides_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for order status selector field
	public function generate_wc_szamlazz_settings_eusafa_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for order status selector field
	public function generate_wc_szamlazz_settings_advanced_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	//Generate html for order status selector field
	public function generate_wc_szamlazz_settings_radio_html( $key, $data) {
		return $this->render_custom_setting_html($key, $data);
	}

	public function render_custom_setting_html($key, $data) {
		$field_key = $this->get_field_key( $key );
		$defaults = array(
			'title' => '',
			'disabled' => false,
			'class' => '',
			'css' => '',
			'placeholder' => '',
			'type' => 'text',
			'desc_tip' => false,
			'description' => '',
			'custom_attributes' => array(),
		);
		$data = wp_parse_args( $data, $defaults );
		$template_name = str_replace('wc_szamlazz_settings_', '', $data['type']);
		ob_start();
		include( dirname( __FILE__ ) . '/views/html-admin-'.str_replace('_', '-', $template_name).'.php' );
		return ob_get_clean();
	}

}

endif;
