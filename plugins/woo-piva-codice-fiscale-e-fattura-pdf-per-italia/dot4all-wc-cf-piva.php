<?php
/**

Plugin Name: WooCommerce P.IVA e Codice Fiscale per Italia
Plugin URI: http://dot4all.it/woocommerce-inserire-codice-fiscale-partita-iva/
Description: Il plugin che rende compatibile woocommerce per il mercato italiano.
Version: 2.1.11
Author: dot4all
Author URI: https://dot4all.it
License: GPLv2 or later
License URI: https://www.opensource.org/licenses/gpl-license.php
Text Domain: woocommerce-piva-cf-invoice-ita-pro
Domain Path: /languages
WC requires at least: 4
WC tested up to: 4.9.1
Credits & Copyrights: labdav • the plugin is a fork of WooCommerce PDF Invoices Italian Add-on

*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( !class_exists( 'WC_Piva_Cf_Invoice_Ita' ) ) :
define('WCPIVACF_IT_DOMAIN', 'woocommerce-piva-cf-invoice-ita-pro');

class WC_Piva_Cf_Invoice_Ita {
	public $plugin_basename;
	public $plugin_url;
	public $plugin_path;
	public $settings;
	public $version = '2.1.11';
	public $regexCF = "/^[a-zA-Z]{6}\d{2}[a-zA-Z]{1}\d{2}[a-zA-Z]{1}\d{3}[a-zA-Z]{1}$/";
	public $regexPIVA = "/^([0-9]{11})$/i";
	protected static $instance = null;

	
	public static function instance() {
		if ( is_null( self::$instance ) ) self::$instance = new self();
		return self::$instance;
	}

	public function __construct() {
		$this->plugin_basename = plugin_basename(__FILE__);
		$this->plugin_url = plugin_dir_url($this->plugin_basename);
		$this->plugin_path = trailingslashit(dirname(__FILE__));
		$this->init();
		$this->init_hooks();
	}


	public function init() {
		//LOAD TRANSLATION
		$locale = apply_filters( 'plugin_locale', get_locale(), WCPIVACF_IT_DOMAIN );
		load_textdomain( WCPIVACF_IT_DOMAIN, WP_LANG_DIR."/plugins/{" . WCPIVACF_IT_DOMAIN . "}-{$locale}.mo" );
		load_plugin_textdomain( WCPIVACF_IT_DOMAIN, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
		//LOAD SETTINGS
		include_once 'includes/class-wp-settings.php';
		include_once 'includes/class-wcpdf-cfcheck.php';
		include_once 'includes/class-session.php';
		$this->settings = new WC_Piva_Cf_Invoice_Ita_Setting($this);
		if ( in_array( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) )) {
			include_once 'includes/class-wcpdf-integration.php';
			$this->wcpdf_add_on = new wcpdf_WC_Piva_Cf_Invoice_Ita($this);
		}
	}

	private function init_hooks() {
		//add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
		if ($this->is_wc_active()) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );
			add_filter( 'woocommerce_billing_fields' , array( $this, 'billing_fields'), 10, 1);
			add_filter( 'woocommerce_admin_billing_fields' , array( $this, 'admin_billing_field' ));
			add_action( 'woocommerce_checkout_process', array( $this, 'piva_checkout_field_process'));
			add_filter( 'woocommerce_order_formatted_billing_address' , array( $this, 'woocommerce_order_formatted_billing_address'), 10, 2 );
			add_filter( 'woocommerce_my_account_my_address_formatted_address', array( $this, 'my_account_my_address_formatted_address'), 10, 3 );
			add_filter( 'woocommerce_formatted_address_replacements', array( $this, 'formatted_address_replacements'), 10, 2 );
			add_filter( 'woocommerce_localisation_address_formats', array( $this, 'localisation_address_format') );
			add_filter( 'woocommerce_found_customer_details', array( $this, 'found_customer_details') );
			add_filter( 'woocommerce_customer_meta_fields', array( $this, 'customer_meta_fields') );
			add_action( 'template_redirect', array( $this, 'save_address' ) );
			add_filter( 'manage_edit-shop_order_columns', array( $this, 'custom_column' ));
			add_action( 'manage_shop_order_posts_custom_column', array( $this, 'custom_column_data' ),10,2);
			add_action( 'woocommerce_checkout_update_order_review', array( $this, 'vies_check_based_on_piva'), 10, 1 );

		} else {
			add_action( 'admin_notices', array ( $this, 'check_wc' ) );
		}
	}

	
	/**
	 * CHECK WOOCOMMERCE IS ACTIVE
	 *
	 * @return bool
	 */
	public function is_wc_active() {
		$plugins = get_site_option( 'active_sitewide_plugins', array());
		if (in_array('woocommerce/woocommerce.php', get_option( 'active_plugins', array())) || isset($plugins['woocommerce/woocommerce.php'])) {
			return true;
		} else {
			return false;
		}
	}

	public function check_wc( $fields ) {
		$class = "error";
		$message = sprintf( __( 'WooCommerce P.IVA e Codice Fiscale per Italia requires %sWooCommerce%s to be installed and activated!' , WCPIVACF_IT_DOMAIN ), '<a href="https://wordpress.org/plugins/woocommerce/">', '</a>' );
		echo"<div class=\"$class\"> <p>$message</p></div>";
	}	

	/**
	 * ENQUEUE SCRIPTS
	 */
	public function enqueue_scripts(  )
	{
		if(is_checkout() || is_account_page()){
			
			wp_enqueue_style( 'wcpicfi', plugin_dir_url(__FILE__).'assets/wcpicfi.css',null,$this->version );
			wp_enqueue_script('wcpicfi', plugin_dir_url(__FILE__).'assets/wcpicfi.js', array('jquery'),$this->version);
			$params = array(
				'plugin_url' => $this->plugin_url,
				'incorrect_cf' => __('Tax Identification Number is not correct', WCPIVACF_IT_DOMAIN),
				'incorrect_piva' => __('VAT Number is not correct', WCPIVACF_IT_DOMAIN),
			);
			wp_localize_script( 'wcpicfi', 'wcpicfi', $params );
		}

	}
	
	/**
	 * ADMIN ENQUEUE SCRIPTS
	 */
	
	function load_custom_wp_admin_style($hook) {
		// Load only on ?page=mypluginname
		//wp_die($hook);
		if($hook != 'woocommerce_page_wcpdf_IT_options_page') {
				return;
		}
		wp_enqueue_style( 'wcpicfia', plugins_url('assets/wcpicfi_admin.css', __FILE__) );
	}
	
	/**
	 * Gets array value
	 *
	 * @param array $array
	 * @param string $key
	 * @param string $default
	 * @return mixed
	 */
	function get_value($array, $key, $default='') {
		$value='';
		if(isset($array[$key])) {
			if(is_array($array[$key])) {
				$value=reset($array[$key]);
			} else {
				$value=$array[$key];
			}
		} else if ($default!=='') {
			$value=$default;
		}

		return maybe_unserialize($value);
	}
	/**
	 * VALIDATE CF validity by regex
	 * @param string $value - CF
	 * @return bool - validate value
	 */
	public function check_cf( $value = '' ) {

		if(preg_match($this->regexCF, $value)){
			$cf = new CodiceFiscale();
			$cf ->SetCF($value);
			if ($cf->GetCodiceValido()) {
				return true;
			}else{
				return false;
			}
		}
		return false;
	}
	/**
	 * VALIDATE PIVA validity by regex
	 * @param string $value - PIVA
	 * @return bool - validate value
	 */
	public function check_piva( $value = '' ) {

		if(preg_match($this->regexPIVA, $value))
			return true;
		return false;
	}
	/**
	 * VALIDATE PIVA/CF validity for myaccount page
	 */
	public function save_address(  ) {
		global $wp;

		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
			return;
		}

		if ( empty( $_POST['action'] ) || 'edit_address' !== $_POST['action'] || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'woocommerce-edit_address' ) ) {
			return;
		}

		$user_id = get_current_user_id();

		if ( $user_id <= 0 ) {
			return;
		}
		$this->piva_checkout_field_process();

	}

	public function billing_fields( $fields ) {
		if ( in_array( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) )) 		{
		$fields['billing_invoice_type'] = array(
			'label' => __('Invoice type', WCPIVACF_IT_DOMAIN),
			'placeholder' => __( 'Invoice type', WCPIVACF_IT_DOMAIN ),
			'required'    => false,
			'optional'	  => false,
			'class'       => array( 'form-row clear'),
			'clear'       => false,
			'type'        => 'select',
			'options'     => array(
				'receipt' => __('Receipt', WCPIVACF_IT_DOMAIN),
				'invoice' => __('Invoice', WCPIVACF_IT_DOMAIN),
				'private_invoice' => __('Invoice with Fiscal Code', WCPIVACF_IT_DOMAIN),
				'professionist_invoice' => __('Invoice with VAT number + Fiscal Code', WCPIVACF_IT_DOMAIN),
			),
			'value'       => get_user_meta( get_current_user_id(), 'billing_invoice_type', true )
		);
		}else{
			$fields['billing_invoice_type'] = array(
			'label' => __('Invoice type', WCPIVACF_IT_DOMAIN),
			'placeholder' => __( 'Invoice type', WCPIVACF_IT_DOMAIN ),
			'required'    => false,
			'optional'	  => false,
			'class'       => array( 'form-row clear'),
			'clear'       => false,
			'type'        => 'select',
			'options'     => array(
				'invoice' => __('Invoice', WCPIVACF_IT_DOMAIN),
				'private_invoice' => __('Invoice with Fiscal Code', WCPIVACF_IT_DOMAIN),
				'professionist_invoice' => __('Invoice with VAT number + Fiscal Code', WCPIVACF_IT_DOMAIN),
			),
			'value'       => get_user_meta( get_current_user_id(), 'billing_invoice_type', true )
		);
		}
		/*if(!$this->view_selected_choices['receipt']) {
			unset($fields['billing_invoice_type']['options']['receipt']);
		}
		if(!$this->view_selected_choices['invoice']) {
			unset($fields['billing_invoice_type']['options']['invoice']);
		}
		if(!$this->view_selected_choices['private_invoice']) {
			unset($fields['billing_invoice_type']['options']['private_invoice']);
		}
		if(!$this->view_selected_choices['professionist_invoice']) {
			unset($fields['billing_invoice_type']['options']['professionist_invoice']);
		}*/
		$fields['billing_cf'] = array(
			'label'       => __('Fiscal Code', WCPIVACF_IT_DOMAIN),
			'placeholder' => __('Please enter your Fiscal code', WCPIVACF_IT_DOMAIN),
			'required'    => false,
			'optional'	  => false,
			'class'       => array( 'form-row' ),
			'clear'		  => true,
			'value'       => get_user_meta( get_current_user_id(), 'billing_cf', true )
		);
		
		$fields['billing_piva'] = array(
			'label'       => __('VAT', WCPIVACF_IT_DOMAIN),
			'placeholder' => __('Please enter your VAT number', WCPIVACF_IT_DOMAIN),
			'required'    => false,
			'optional'	  => false,
			'clear'       => true,
			'class'       => array( 'form-row update_totals_on_change' ),
			'value'       => get_user_meta( get_current_user_id(), 'billing_piva', true )
		);
		
		$fields['billing_pec'] = array(
			'label'       => __('PEC', WCPIVACF_IT_DOMAIN),
			'placeholder' => __('Please enter your PEC Address', WCPIVACF_IT_DOMAIN),
			'required'    => false,
			'clear'       => false,
			'class'       => array( 'form-row einvoice-group' ),
			'value'       => get_user_meta( get_current_user_id(), 'billing_pec', true )
		);
			// codice destinatario
		$fields['billing_pa_code'] = array(
			'label'       => __('PA CODE', WCPIVACF_IT_DOMAIN),
			'placeholder' => __('Please enter your PA code', WCPIVACF_IT_DOMAIN),
			'required'    => false,
			'clear'       => false,
			'class'       => array( 'form-row einvoice-group' ),
			'value'       => get_user_meta( get_current_user_id(), 'billing_pa_code', true )
			);
		
		if ( !in_array( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) )) 		{
			
			unset($fields['billing_invoice_type']['options']['receipt']);
			$fields['billing_invoice_type']['label'] = __('Invoice Type', WCPIVACF_IT_DOMAIN);
		}
		if($fields['billing_invoice_type']['options']['invoice'] == null){
			unset($fields['billing_invoice_type']['options']['invoice']);
		}
		if($fields['billing_invoice_type']['options']['private_invoice'] == null){
			unset($fields['billing_invoice_type']['options']['private_invoice']);
		}
		if($fields['billing_invoice_type']['options']['professionist_invoice'] == null){
			unset($fields['billing_invoice_type']['options']['professionist_invoice']);
		}
		
		//check IT country
		if(isset($_POST['billing_country']) && $_POST['billing_country'] != 'IT'){
			$fields['billing_cf']['required'] = false;
		}
		//echo $this->force_required;
		if(isset($_POST['billing_invoice_type']) && !$this->force_required && $_POST['billing_country'] == 'IT'){
			switch($_POST['billing_invoice_type']){
				case "receipt":
					$fields['billing_cf']['required'] = $this->cf_force_mandatory_for_receipt;
					$fields['billing_piva']['required'] = false;
					$fields['billing_pec']['required'] = false;
					$fields['billing_pa_code']['required'] = false;
					break;
				case "private_invoice":
					$fields['billing_cf']['required'] = true;
					$fields['billing_piva']['required'] = false;
					$fields['billing_company']['required'] = false;
					$fields['billing_pec']['required'] = false;
					$fields['billing_pa_code']['required'] = false;

					break;
				case "invoice":
					$fields['billing_cf']['required'] = false;
					$fields['billing_piva']['required'] = true;
					$fields['billing_company']['required'] = true;
					$fields['billing_pec']['required'] = true;
					$fields['billing_pa_code']['required'] = false;

					break;
				case "professionist_invoice":
					$fields['billing_cf']['required'] = true;
					$fields['billing_piva']['required'] = true;
					$fields['billing_company']['required'] = false;
					$fields['billing_pec']['required'] = true;
					$fields['billing_pa_code']['required'] = false;

					break;
			}

		}
		// Ordinamento campi checkout VIES ready
		$fields['billing_phone']['clear'] = true;
		$fields['billing_phone']['class'] = array( 'form-row');
		$fields['billing_email']['clear'] = true;
		$fields['billing_email']['class'] = array( 'form-row');
		$fields['billing_city']['clear'] = true;
		$fields['billing_city']['class'] = array( 'form-row-first');
		$fields['billing_cf']['clear'] = true;
		$fields['billing_cf']['clear'] = array( 'form-row-first');
		$fields['billing_company']['clear'] = true;
		$fields['billing_company']['clear'] = array( 'form-row-first');
		$fields['billing_company']['optional'] = false;

		
		$order = array(
			"billing_invoice_type",
			"billing_email",
			"billing_company",
			"billing_first_name",
			"billing_last_name",
			"billing_country",
			"billing_state",
			"billing_city",
			"billing_address_1",
			"billing_address_2",
			"billing_postcode",	
			"billing_phone",
			"billing_piva",
			"billing_cf",
		);
		//order billing field
		foreach ($order as $field) {
			$ordered_fields[$field] = $fields[$field];
			unset($fields[$field]);
		}
		foreach ($tmp_fields = $fields as $key => $field) {
			$ordered_fields[$key] = $fields[$key];
		}
		$fields = $ordered_fields;
		
		
		return $fields;
	}
	// def
	public function admin_billing_field( $fields ) {
		if ( in_array( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) )) 		{
		$fields['invoice_type'] = array(
			'label' => __('Invoice type', WCPIVACF_IT_DOMAIN),
			'show' => false,
			'wrapper_class' => 'form-field-wide',
			'type'        => 'select',
			'options'     => array(
				'receipt' => __('Receipt', WCPIVACF_IT_DOMAIN),
				'invoice' => __('Invoice', WCPIVACF_IT_DOMAIN),
				'private_invoice' => __('Invoice with Fiscal Code', WCPIVACF_IT_DOMAIN),
				'professionist_invoice' => __('Invoice with VAT number + Fiscal Code', WCPIVACF_IT_DOMAIN),
			),
		);
		}else{
		$fields['invoice_type'] = array(
			'label' => __('Invoice type', WCPIVACF_IT_DOMAIN),
			'show' => false,
			'wrapper_class' => 'form-field-wide',
			'type'        => 'select',
			'options'     => array(
				'invoice' => __('Invoice', WCPIVACF_IT_DOMAIN),
				'private_invoice' => __('Invoice with Fiscal Code', WCPIVACF_IT_DOMAIN),
				'professionist_invoice' => __('Invoice with VAT number + Fiscal Code', WCPIVACF_IT_DOMAIN),
			),
		);	
		}
		$fields['cf'] = array(
			'label' => __('Fiscal Code', WCPIVACF_IT_DOMAIN),
			'wrapper_class' => 'form-field-wide',
			'show' => false
		);
		$fields['piva'] = array(
			'label' => __('VAT', WCPIVACF_IT_DOMAIN),
			'wrapper_class' => 'form-field-wide',
			'show' => false
		);
		$fields['pec'] = array(
			'label' => __('PEC', WCPIVACF_IT_DOMAIN),
			'wrapper_class' => 'form-field-wide',
			'show' => false
		);
		$fields['pa_code'] = array(
			'label' => __('PA CODE', WCPIVACF_IT_DOMAIN),
			'wrapper_class' => 'form-field-wide',
			'show' => false
		);
	
		return $fields;
	}
	/* Add fields in Edit User Page - def */
	public function customer_meta_fields( $fields ) {
		if ( in_array( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) )) 		{
		$fields['billing']['fields']['billing_invoice_type'] = array(
			'label'       => __('Invoice type', WCPIVACF_IT_DOMAIN),
			'type'        => 'select',
			'options'     => array(
				'receipt' => __('Receipt', WCPIVACF_IT_DOMAIN),
				'invoice' => __('Invoice', WCPIVACF_IT_DOMAIN),
				'private_invoice' => __('Invoice with Fiscal Code', WCPIVACF_IT_DOMAIN),
				'professionist_invoice' => __('Invoice with VAT number + Fiscal Code', WCPIVACF_IT_DOMAIN),
			),
			'description'       => ""
		);
		}else{
		$fields['billing']['fields']['billing_invoice_type'] = array(
			'label'       => __('Invoice type', WCPIVACF_IT_DOMAIN),
			'type'        => 'select',
			'options'     => array(
				'invoice' => __('Invoice', WCPIVACF_IT_DOMAIN),
				'private_invoice' => __('Invoice with Fiscal Code', WCPIVACF_IT_DOMAIN),
				'professionist_invoice' => __('Invoice with VAT number + Fiscal Code', WCPIVACF_IT_DOMAIN),
			),
			'description'       => ""
		);	
		}
		$fields['billing']['fields']['billing_cf'] = array(
			'label'       => __('Fiscal Code', WCPIVACF_IT_DOMAIN),
			'description'       => ""
		);
		$fields['billing']['fields']['billing_piva'] = array(
			'label'       => __('VAT number', WCPIVACF_IT_DOMAIN),
			'description'       => ""
		);
		$fields['billing']['fields']['billing_pec'] = array(
			'label'       => __('PEC', WCPIVACF_IT_DOMAIN),
			'description'       => ""
		);
		$fields['billing']['fields']['billing_pa_code'] = array(
			'label'       => __('PA CODE', WCPIVACF_IT_DOMAIN),
			'description'       => ""
		);
		return $fields;
	}
	/**
	 *
	 * VALIDATE fields
	 */
	public function piva_checkout_field_process() {
		if(!$this->force_required){
			if(in_array($_POST["billing_invoice_type"],array('invoice','professionist_invoice'))) {
				$billing_piva = preg_replace('/\s+/', '', sanitize_text_field($_POST['billing_piva']));
				if(!empty($billing_piva) && !$this->check_piva($_POST['billing_piva'])) {
					wc_add_notice(sprintf(__('VAT Number %1$s is not correct', WCPIVACF_IT_DOMAIN), "<strong>". strtoupper($_POST['billing_piva']) . "</strong>"),$notice_type = 'error');
				}

			}
			if(in_array($_POST["billing_invoice_type"],array('private_invoice','professionist_invoice')) && $_POST["billing_country"] == 'IT'){
				$billing_cf = preg_replace('/\s+/', '', sanitize_text_field($_POST['billing_cf']));
				if(!empty($billing_cf) && !$this->check_cf($_POST['billing_cf']) ) {
					wc_add_notice(sprintf(__('Tax Identification Number %1$s is not correct', WCPIVACF_IT_DOMAIN), "<strong>". strtoupper($_POST['billing_cf']) . "</strong>"),$notice_type = 'error');
				}
			}
		} // force required
	}
	
	
	public function woocommerce_order_formatted_billing_address( $fields, $order) {
		
		$fields['invoice_type'] = $order->get_meta('_billing_invoice_type');
		
		$_SESSION["invoice_type"] = $fields['invoice_type'];
		$fields['cf'] = $order->get_meta('_billing_cf');
		$fields['piva'] = $order->get_meta('_billing_piva');
		$fields['pec'] = $order->get_meta('_billing_pec');
		$fields['pa_code'] = $order->get_meta('_billing_pa_code');
		return $fields;
	}
	
	public function my_account_my_address_formatted_address( $fields, $customer_id, $type ) {
		
		if ( $type == 'billing' ) {
			$fields['invoice_type'] = get_user_meta( $customer_id, '_billing_invoice_type', true );
			$fields['cf'] = get_user_meta( $customer_id, '_billing_cf', true );
			$fields['piva'] = get_user_meta( $customer_id, '_billing_piva', true );
			$fields['pec'] = get_user_meta( $customer_id, '_billing_pec', true );
			$fields['pa_code'] = get_user_meta( $customer_id, '_billing_pa_code', true );
		}
		
		return $fields;
	}
	
	public function formatted_address_replacements( $address, $args ) {
		$address['{invoice_type}'] = '';
		$address['{cf}'] = '';
		$address['{piva}'] = '';
		$address['{pec}'] = '';
		$address['{pa_code}'] = '';
		//print_r($_SESSION["invoice_type"]);
		//die();
	
		if (! empty( $args['invoice_type'] ) ) {
			switch($args['invoice_type']){
				case 'receipt':
				case 'private_invoice':
					$address['{cf}'] =  __('Fiscal code', WCPIVACF_IT_DOMAIN) . ': ' . strtoupper( $args['cf'] );
				case 'invoice':
					$address['{piva}'] = __('VAT', WCPIVACF_IT_DOMAIN) . ": " . $args['country'] . strtoupper( $args['piva'] );
					$address['{pec}'] = __('PEC', WCPIVACF_IT_DOMAIN) . ": " . $args['pec'];
					$address['{pa_code}'] = __('PA CODE', WCPIVACF_IT_DOMAIN) . ": " .strtoupper($args['pa_code']);

				case 'professionist_invoice':
					$address['{cf}'] =  __('Fiscal code', WCPIVACF_IT_DOMAIN) . ': ' . strtoupper( $args['cf'] );
					$address['{piva}'] = __('VAT', WCPIVACF_IT_DOMAIN) . ": " . $args['country'] . strtoupper( $args['piva'] );
					$address['{pec}'] = __('PEC', WCPIVACF_IT_DOMAIN) . ": " . $args['pec'];
					$address['{pa_code}'] = __('PA CODE', WCPIVACF_IT_DOMAIN) . ": " . strtoupper($args['pa_code']);

			}
		}
		return $address;
	}
	
	public function localisation_address_format( $formats ) {
		
		//print_r($formats); //die();

		if(isset($_SESSION["invoice_type"])){
			
			if($_SESSION["invoice_type"] == 'receipt' || $_SESSION["invoice_type"] == 'private_invoice'){
				$formats['IT'] .= "\n\n{cf}";	
			}else if ($_SESSION["invoice_type"] == 'invoice'){
				$formats['IT'] .= "\n\n{piva}\n\n{pec}\n\n{pa_code}";
			}else{
				$formats['IT'] .= "\n\n{cf}\n\n{piva}\n\n{pec}\n\n{pa_code}";
			}
			return $formats;
		}else
		{	
			$formats['IT'] .= "\n\n{cf}\n\n{piva}\n\n{pec}\n\n{pa_code}";
			return $formats;	
		}
	}
	
	public function found_customer_details( $customer_data ) {
		$umeta = get_user_meta( $_POST['user_id']);
		$customer_data['billing_invoice_type'] = $this->get_value($umeta,'billing_invoice_type');
		$customer_data['billing_cf'] = $this->get_value($umeta,'billing_cf');
		$customer_data['billing_piva'] = $this->get_value($umeta,'billing_piva');
		$customer_data['billing_pec'] = $this->get_value($umeta,'billing_pec');
		$customer_data['billing_pa_code'] = $this->get_value($umeta,'billing_pa_code');
		return $customer_data;
	}
	


	/**
	 * ADD CUSTOM COLUMNS TO ORDER LISTS
	 * @param $columns
	 *
	 * @return array
	 */
	public function custom_column( $columns ) {
		$new_columns = array_slice($columns, 0, 2, true) +
			array( 'invoice_type' => '<span class="status_head tips" data-tip="' . __( 'Invoice type', WCPIVACF_IT_DOMAIN ) . '">' . __( 'Invoice or Receipt', WCPIVACF_IT_DOMAIN ) . '</span>') +
			array_slice($columns, 2, count($columns) - 1, true) ;
		return $new_columns;
	}

	/**
	 * ADD CUSTOM COLUMNS TO ORDER LISTS
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function custom_column_data( $column, $post_id ) {
		$meta = get_post_meta($post_id);
		$invoice_type = isset($meta['_billing_invoice_type']) ? $meta['_billing_invoice_type'][0] : '';
		switch ($column) {
			case 'invoice_type' :
				switch($invoice_type) {
					case "invoice":
						$label = __('Invoice', WCPIVACF_IT_DOMAIN);
						$color = 'f90000';
						break;
					case "private_invoice":
						$label = __('Invoice with Fiscal Code', WCPIVACF_IT_DOMAIN);
						$color = '270dff ';
						break;
					case "receipt":
						$label = __('Receipt', WCPIVACF_IT_DOMAIN);
						$color = 'fdb308';
						break;
					case "professionist_invoice":
						$label = __('Invoice with VAT number + Fiscal Code', WCPIVACF_IT_DOMAIN);
						$color = '0f6f02';
						break;

					default:
						$label = '-';
						$color = '';
				}
				echo "<i style='color:#$color' class='dashicons dashicons-media-document tips' data-tip='$label'></i>";
				break;
		}
		return $column;
	}
		
	/**
	 * @snippet       Rimuove Tasse @ Checkout se controllo VIES è valido
	 * @author        Luca Organtini
	 */
	 
	public function vies_check_based_on_piva( $post_data ) {
			global $woocommerce;
			$woocommerce->customer->set_is_vat_exempt( false );
			parse_str($post_data,$output);
			
			if ($output['billing_piva']){
				if(in_array($output['billing_country'],array('AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DE', 'DK', 'EE', 'EL','ES', 'FI', 'FR', 'HU', 'IE', 'LT', 'LU', 'LV','MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK'))){
					//if (isEU($billing_country)){
						$client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");	
						$mycheck = $client->checkVat(array('countryCode' => $output['billing_country'],'vatNumber' => $output['billing_piva']));
						if($mycheck->valid)
							$woocommerce->customer->set_is_vat_exempt( true );
						else
							$woocommerce->customer->set_is_vat_exempt( false );
					//}
				}
			}
	}
}
endif;

$wcpivacf_IT = new WC_Piva_Cf_Invoice_Ita();
