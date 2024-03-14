<?php
/*
Plugin Name: Integration for Szamlazz.hu & WooCommerce
Plugin URI: http://visztpeter.me
Description: Számlázz.hu összeköttetés WooCommercehez
Author: Viszt Péter
Text Domain: wc-szamlazz
Domain Path: /languages/
Version: 5.9
WC requires at least: 7.0
WC tested up to: 8.5
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'WC_SZAMLAZZ_PLUGIN_FILE' ) ) {
	define( 'WC_SZAMLAZZ_PLUGIN_FILE', __FILE__ );
}

//HPOS compatibility
use \Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

//Generate stuff on plugin activation
function wc_szamlazz_activate() {
	$upload_dir = wp_upload_dir();

	$files = array(
		array(
			'base' => $upload_dir['basedir'] . '/wc_szamlazz',
			'file' => 'index.html',
			'content' => ''
		)
	);

	foreach ( $files as $file ) {
		if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
			if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
				fwrite( $file_handle, $file['content'] );
				fclose( $file_handle );
			}
		}
	}

	//Show welcome notice
	if(function_exists( 'wc_admin_url' )) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-panel-inbox.php' );
		$panel_inbox = new WC_Szamlazz_Admin_Panel_Inbox();
		$panel_inbox->create_welcome_note();
	}

}
register_activation_hook( __FILE__, 'wc_szamlazz_activate' );

function wc_szamlazz_deactivate() {
	if(function_exists( 'wc_admin_url' )) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-panel-inbox.php' );
		$panel_inbox = new WC_Szamlazz_Admin_Panel_Inbox();
		$panel_inbox->remove_notes();
	}
}
register_deactivation_hook( __FILE__, 'wc_szamlazz_deactivate' );

class WC_Szamlazz {

	public static $plugin_prefix;
	public static $plugin_url;
	public static $plugin_path;
	public static $plugin_basename;
	public static $version;
	public $xml_generator = null;
	public static $panel_inbox = null;

	protected static $_instance = null;

	//Get main instance
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	//Construct
	public function __construct() {

		//Default variables
		self::$plugin_prefix = 'wc_szamlazz_';
		self::$plugin_basename = plugin_basename(__FILE__);
		self::$plugin_url = plugin_dir_url(self::$plugin_basename);
		self::$plugin_path = trailingslashit(dirname(__FILE__));
		self::$version = '5.9';

		//Helper functions
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-pro.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-helpers.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-conditions.php' );

		//XML generator helper
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-xml-generator.php' );
		$this->xml_generator = new WC_Szamlazz_Xml_Generator();

		//Plugin loaded
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		//HPOS compatibility
		add_action( 'before_woocommerce_init', array( $this, 'woocommerce_hpos_compatible' ) );

		//Update notice, if needed
		add_action( 'in_plugin_update_message-integration-for-szamlazzhu-woocommerce/index.php', array( $this, 'in_plugin_update_message' ), 10, 2 );

		//Include compatibility modules
		require_once( plugin_dir_path( __FILE__ ) . 'includes/compatibility/class-compatibility.php' );
		WC_Szamlazz_Compatibility::instance();

		//Load admin messages
		add_action( 'admin_init', array( $this, 'load_admin_messages' ) );

	}

	public function load_admin_messages() {
		if (
			function_exists( 'wc_admin_url' ) &&
			false !== get_option( 'woocommerce_admin_install_timestamp' )
		) {
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-panel-inbox.php' );
			self::$panel_inbox = new WC_Szamlazz_Admin_Panel_Inbox();
		}
	}

	//Show upgrade notice for plugin updates in the future
	public function in_plugin_update_message( $data, $response ) {
		if( isset( $data['upgrade_notice'] ) ) {
			printf(
				'<div class="update-message">%s</div>',
				wpautop( $data['upgrade_notice'] )
			);
		}
	}

	public function load_plugin_textdomain() {
		$locale = determine_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'wc-szamlazz' );

		unload_textdomain( 'wc-szamlazz' );
		load_textdomain( 'wc-szamlazz', basename( dirname( __FILE__ ) ) . '/languages/wc-szamlazz-' . $locale . '.mo' );
		load_plugin_textdomain( 'wc-szamlazz', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	//Load plugin stuff
	public function init() {

		//Load locale
		$this->load_plugin_textdomain();

		//Background invoice generator
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-background-generator.php' );

		//Functions related to emails and ajax
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-emails.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-ajax.php' );

		//Check if pro enabled
		$is_pro = WC_Szamlazz_Pro::is_pro_enabled();
		$db_version = get_option('_wc_szamlazz_db_version');

		// Load includes
		if(is_admin()) {
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-settings.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-admin-notices.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-product-options.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-bulk-actions.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-grouped-invoice.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-invoice-preview.php' );
		}

		//Load custom webhooks
		if($is_pro) {
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-webhooks.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-ipn.php' );
		}

		//Load if new automations used
		if($is_pro && $this->get_option('auto_invoice_custom', 'no') == 'yes') {
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-automations.php' );
		}

		//Health check for WP 5.2+
		global $wp_version;
		if ( version_compare( $wp_version, '5.2-alpha', 'ge' ) ) {
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-health-check.php' );
		}

		//Plugin links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

		//Settings page
		if(is_admin()) {
			add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
		}

		//Admin CSS & JS
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		//Create order metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ), 10, 2 );

		//Create a hook based on the status setup in settings to auto-generate invoice, only if the advanced options are not used
		if($is_pro && $this->get_option('auto_invoice_custom', 'no') != 'yes') {

			//Auto generate proform or deposit invoice
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'on_order_processing' ) );

			if(($db_version != '4.5' && $this->get_option('auto_generate') == 'no')) {
				//Option naming changed
			} else {

				$auto_invoice_statuses = get_option('wc_szamlazz_auto_invoice_status');
				$auto_void_statuses = get_option('wc_szamlazz_auto_void_status');

				if($auto_invoice_statuses) {
					if(empty($auto_invoice_statuses)) $auto_invoice_statuses = array();
					if(empty($auto_void_statuses)) $auto_void_statuses = array();
				} else {
					$auto_invoice_statuses = array($this->get_option('auto_invoice_status', 'no'));
					$auto_void_statuses = array($this->get_option('auto_void_status', 'no'));
				}

				//Auto generate invoices
				foreach ($auto_invoice_statuses as $auto_invoice_status) {
					$order_auto_invoice_status = str_replace( 'wc-', '', $auto_invoice_status );
					if($order_auto_invoice_status != 'no') {
						add_action( 'woocommerce_order_status_'.$order_auto_invoice_status, array( $this, 'on_order_complete' ) );
					}
				}

				//Auto generate void invoices
				foreach ($auto_void_statuses as $auto_void_status) {
					$order_auto_void_status = str_replace( 'wc-', '', $auto_void_status );
					if($order_auto_void_status != 'no') {
						add_action( 'woocommerce_order_status_'.$order_auto_void_status, array( $this, 'on_order_deleted' ) );
					}
				}

			}
		}

		//Order list button
		add_filter( 'manage_edit-shop_order_columns', array( $this, 'add_listing_column' ) );
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'add_listing_actions' ), 10, 2 );
		add_filter( 'manage_woocommerce_page_wc-orders_columns', array( $this, 'add_listing_column' ) );
		add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( $this, 'add_listing_actions' ), 10, 2 );
		add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'orders_download_button' ), 10, 2);
		if($this->get_option('tools', 'no') == 'yes') add_action( 'woocommerce_admin_order_actions_end', array( $this, 'add_listing_actions_2' ) );

		//VAT number
		if($this->get_option('vat_number_form', 'no') == 'yes') {
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-vat-number.php' );

			//Checkout Block Compatibility
			require_once( plugin_dir_path( __FILE__ ) . 'includes/block/vat-number-block.php' );

		}

		//Frontend scripts & css
		if(($this->get_option('receipt') == 'yes' && $is_pro) || $this->get_option('vat_number_form', 'no') == 'yes') {
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_css' ));
		}

		//E-Nyugta
		if($this->get_option('receipt') == 'yes' && $is_pro) {
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-checkout-receipt.php' );
		}

		//Disable invoices on free orders
		add_action('woocommerce_checkout_order_processed', array( $this, 'disable_invoice_for_free_order' ), 10, 3);

		//Delete PDF when order is deleted
		add_action('woocommerce_before_delete_order', array($this, 'on_order_post_deleted'), 10, 2);

	}

	//Declares WooCommerce HPOS and Block compatibility.
	public function woocommerce_hpos_compatible() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
			//\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'product_block_editor', __FILE__, true );
		}
	}

	//Integration page
	public function add_integration( $integrations ) {
		$integrations[] = 'WC_Szamlazz_Settings';
		return $integrations;
	}

	//Add CSS & JS
	public function admin_init() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_script( 'wc_szamlazz_print_js', plugins_url( '/assets/js/print.min.js',__FILE__ ), array('jquery'), WC_Szamlazz::$version, TRUE );
		wp_enqueue_script( 'wc_szamlazz_admin_js', plugins_url( '/assets/js/admin'.$suffix.'.js',__FILE__ ), array('jquery', 'jquery-tiptip', 'jquery-blockui'), WC_Szamlazz::$version, TRUE );
		wp_enqueue_style( 'wc_szamlazz_admin_css', plugins_url( '/assets/css/admin.css',__FILE__ ), array(), WC_Szamlazz::$version );

		$wc_szamlazz_local = array(
			'loading' => plugins_url( '/assets/images/ajax-loader.gif',__FILE__ ),
			'delete_proform_too' => $this->get_option('delete_proform_too', 'yes'),
			'settings_link' => esc_url(admin_url( 'admin.php?page=wc-settings&tab=integration&section=wc_szamlazz' ))
		);
		wp_localize_script( 'wc_szamlazz_admin_js', 'wc_szamlazz_params', $wc_szamlazz_local );

		//Store and check version number
		$version = get_option('wc_szamlazz_version_number');

		//If plugin is updated, schedule imports(maybe a new provider was added for example)
		if(!$version || ($version != self::$version)) {
			update_option('wc_szamlazz_version_number', self::$version);

			//And check if its an old pro version
			WC_Szamlazz_Pro::migrate_old_pro();
		}

	}

	//Frontend CSS & JS
	public function frontend_css() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		if(is_checkout() || is_account_page()) {
			wp_enqueue_style( 'wc_szamlazz_frontend_css', plugins_url( '/assets/css/frontend.css',__FILE__ ), array(), WC_Szamlazz::$version );
			wp_enqueue_script( 'wc_szamlazz_frontend_js', plugins_url( '/assets/js/frontend'.$suffix.'.js',__FILE__ ), array('jquery', 'jquery-blockui'), WC_Szamlazz::$version );

			$vat_type_default = ($this->get_option('vat_number_always_show', 'no') == 'yes') ? 'show' : 'default';
			$wc_szamlazz_local = array(
				'type' => $this->get_option('vat_number_type', $vat_type_default),
				'autofill' => $this->get_option('vat_number_autofill', 'no'),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'eu_vat_number' => $this->get_option('vat_number_eu', 'no'),
				'eu_countries' => WC()->countries->get_european_union_countries(),
			);
			wp_localize_script( 'wc_szamlazz_frontend_js', 'wc_szamlazz_vat_number_params', $wc_szamlazz_local );
		}

	}

	//Meta box on order page
	public function add_metabox( $post_type, $post_or_order_object ) {
		if ( class_exists( CustomOrdersTableController::class ) && function_exists( 'wc_get_container' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ) {
			$screen = wc_get_page_screen_id( 'shop-order' );
		} else {
			$screen = 'shop_order';
		}

		add_meta_box('wc_szamlazz_metabox', __('Számlázz.hu', 'wc-szamlazz'), array( $this, 'render_meta_box_content' ), $screen, 'side');
		add_meta_box('wc_szamlazz_metabox', __('Számlázz.hu', 'wc-szamlazz'), array( $this, 'render_meta_box_content' ), 'awcdp_payment', 'side');

		$order = ( $post_or_order_object instanceof \WP_Post ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;
		if(is_a( $order, 'WC_Order' )) {
			$vat_number_data = $order->get_meta('_wc_szamlazz_adoszam_data');
			if($vat_number_data) {
				add_meta_box('wc_szamlazz_vat_number_metabox', __('VAT Number info', 'wc-szamlazz'), array( $this, 'render_meta_box_content_vat_number' ), $screen, 'side');
			}
		}
	}

	//Render metabox content
	public function render_meta_box_content($post_or_order_object) {
		$order = ( $post_or_order_object instanceof \WP_Post ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;
		include( dirname( __FILE__ ) . '/includes/views/html-metabox.php' );
	}

	//Vat number metabox content
	public function render_meta_box_content_vat_number($post_or_order_object) {
		$order = ( $post_or_order_object instanceof \WP_Post ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;
		$vat_number_data = $order->get_meta('_wc_szamlazz_adoszam_data');
		include( dirname( __FILE__ ) . '/includes/views/html-metabox-vat.php' );
	}

	//Generate XML for Szamla Agent
	public function generate_invoice($orderId, $type = 'invoice', $options = array()) {

		//Plugins can hook up here
		do_action('wc_szamlazz_before_generate_invoice', $orderId, $type, $options);

		//If multiple orders passed
		if(is_array($orderId)) {

			//The main order is the first one
			$order = wc_get_order($orderId[0]);

			//Collect all order items into single array
			$order_items = array();
			foreach ($orderId as $order_id) {
				$temp_order = wc_get_order($order_id);
				$order_items = $order_items + $temp_order->get_items();
			}

			//Set the $orderId to the main order's(first one) id
			$orderId = $order->get_id();

		} else {
			$order = wc_get_order($orderId);
			$order_items = $order->get_items();
		}

		//If its a void invoice, we use a different function
		if($type == 'void') {
			return $this->generate_void_invoice($orderId, $options);
		}

		//Receipts
		$document_type = ($order->get_meta('_wc_szamlazz_type_receipt')) ? 'receipt' : 'invoice';
		if($document_type == 'receipt') {
			return $this->generate_receipt($orderId);
		}

		//Response
		$response = array();
		$response['error'] = false;
		$response['type'] = $type;
		$response['messages'] = array();

		//Build Xml
		$szamla = new WCSzamlazzSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlszamla xmlns="http://www.szamlazz.hu/xmlszamla" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamla xmlszamla.xsd"></xmlszamla>');

		//Authentication
		$fixed_key = false;
		if(isset($options['account'])) $fixed_key = sanitize_text_field($options['account']);
		$szamla->appendXML($this->get_authentication_xml_object($order, $fixed_key));

		//Invoice basic settings
		$electronic_invoice_type = WC_Szamlazz_Helpers::get_invoice_type($order);

		//Override document type if value submitted
		$doc_type = '';
		if(isset($_POST['doc_type'])) $doc_type = sanitize_text_field($_POST['doc_type']);
		if(isset($options['doc_type'])) $doc_type = sanitize_text_field($options['doc_type']);
		if($doc_type == 'paper') $electronic_invoice_type = 'false';
		if($doc_type == 'electronic') $electronic_invoice_type = 'true';

		//Set document type
		$szamla->beallitasok->addChild('eszamla', $electronic_invoice_type);
		$szamla->beallitasok->addChild('szamlaLetoltes', 'true');

		//If custom details submitted
		if(isset($_POST['deadline']) && isset($_POST['completed'])) {
			$deadline = intval($_POST['deadline']);
			$complated_date = sanitize_text_field($_POST['completed']);
		} elseif (isset($options['deadline']) && isset($options['completed'])) {
			$deadline = intval($options['deadline']);
			$complated_date = sanitize_text_field($options['completed']);
		} else {
			$deadline = $this->get_payment_method_deadline($order->get_payment_method());
			$complated_date = date_i18n('Y-m-d');
		}

		//Get language
		$language = $this->get_option('language', 'hu');
		if(isset($_POST['lang'])) $language = sanitize_text_field($_POST['lang']);
		if(isset($options['lang'])) $language = sanitize_text_field($options['lang']);

		//Get order note
		$note = $this->get_invoice_note($order, $type, $language, $szamla);
		if(isset($_POST['note']) && !empty($_POST['note'])) $note = sanitize_textarea_field($_POST['note']);
		if(isset($options['note']) && !empty($options['note'])) $note = sanitize_textarea_field($options['note']);

		//Replace customer email and phone number in note
		$note = WC_Szamlazz_Helpers::replace_note_placeholders($note, $order);

		//Header element
		$fejlec = $szamla->addChild('fejlec');
		$fejlec->addChild('keltDatum', date_i18n('Y-m-d') );
		$fejlec->addChild('teljesitesDatum', $complated_date );
		$fejlec->addChild('fizetesiHataridoDatum', ($deadline) ? date_i18n('Y-m-d', strtotime('+'.$deadline.' days', current_time('timestamp'))) : date_i18n('Y-m-d'));
		$fejlec->addChild('fizmod', esc_html($order->get_payment_method_title()) ?: '-');
		$fejlec->addChild('penznem', WC_Szamlazz_Helpers::get_currency($order));
		$fejlec->addChild('szamlaNyelve', $language);
		$fejlec->addChild('megjegyzes', esc_html(wp_strip_all_tags($note)));
		if($order->get_currency() != 'HUF') $fejlec->addChild('arfolyamBank', 'MNB');
		$fejlec->addChild('rendelesSzam', $order->get_order_number());

		//If custom completed date sent
		if(isset($options['completed_date'])) {
			$fejlec->teljesitesDatum = sanitize_text_field($options['completed_date']);
		}

		//If custom deadline date sent
		if(isset($options['deadline_date'])) {
			$fejlec->fizetesiHataridoDatum = sanitize_text_field($options['deadline_date']);
		}

		//If custom payment method name is set
		$custom_payment_method_name = $this->check_payment_method_options($order->get_payment_method(), 'name');
		if($custom_payment_method_name != '' && $custom_payment_method_name !== false) {
			$fejlec->fizmod = $custom_payment_method_name;
		}

		//If proform already created
		if($this->is_invoice_generated($orderId, 'proform')) {
			$fejlec->addChild('dijbekeroSzamlaszam', $order->get_meta('_wc_szamlazz_proform'));
		}

		//Invoice types
		$fejlec->addChild('elolegszamla', 'false');
		$fejlec->addChild('vegszamla', 'false');
		$fejlec->addChild('helyesbitoszamla', 'false');
		$fejlec->addChild('helyesbitettSzamlaszam', 'false');
		$fejlec->addChild('dijbekero', 'false');
		$fejlec->addChild('szallitolevel', 'false');

		//Define custom logo(optional)
		$fejlec->logoExtra = '';

		//Előtag
		if($this->get_option('prefix')) {
			$fejlec->addChild('szamlaszamElotag', $this->get_option('prefix'));
		}

		//Mark as paid if needed
		$is_invoice_already_paid = false;
		if($type == 'invoice') {
			if($this->check_payment_method_options($order->get_payment_method(), 'complete')) {
				$fejlec->addChild('fizetve', 'true');
				$is_invoice_already_paid = true;
			} else {
				$fejlec->addChild('fizetve', 'false');
			}
		}

		//Mark as paid if needed with custom options
		if(isset($options['paid'])) {
			if(!$fejlec->fizetve) {
				$fejlec->addChild('fizetve', 'false');
			}
			if($options['paid']) {
				$fejlec->fizetve = 'true';
				$is_invoice_already_paid = true;
			} else {
				$fejlec->fizetve = 'false';
				$is_invoice_already_paid = false;
			}
		}

		//Mark as paid if total is 0
		if($type == 'invoice' && $order->get_total() == 0) {
			$is_invoice_already_paid = true;
		}

		//Check for the eusAfa parameter
		$fejlec->addChild('eusAfa', WC_Szamlazz_Helpers::check_eusafa($order));

		//Set custom template
		$fejlec->addChild('szamlaSablon', $this->get_option('template', 'SzlaMost'));

		//Language based on WPML
		if($this->get_option('language_wpml') == 'yes') {
			$wpml_lang_code = get_post_meta( $orderId, 'wpml_language', true );
			if(!$wpml_lang_code && function_exists('pll_get_post_language')){
				$wpml_lang_code = pll_get_post_language($orderId, 'locale');
			}
			if($wpml_lang_code && in_array($wpml_lang_code, array('hu', 'de', 'en', 'it', 'fr', 'hr', 'ro', 'sk', 'es', 'pl', 'cz'))) $fejlec->szamlaNyelve = $wpml_lang_code;
		}

		//Proform
		if($type == 'proform') {
			$fejlec->dijbekero = 'true';
		}

		//Delivery note
		if($type == 'delivery') {
			$fejlec->szallitolevel = 'true';
		}

		//Deposit
		if($type == 'deposit') {
			$fejlec->elolegszamla = 'true';
		}

		//Void using corrected invoice
		if($type == 'corrected') {
			$fejlec->helyesbitoszamla = 'true';
			$szamlaszam = $order->get_meta('_wc_szamlazz_invoice');
			$fejlec->helyesbitettSzamlaszam = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $szamlaszam);
		}

		//If deposit already generated and we now need an invoice
		if($this->is_invoice_generated($orderId, 'deposit') && $type == 'invoice') {
			$fejlec->vegszamla = 'true';
		}

		//Seller details
		$elado = $szamla->addChild('elado');
		$elado->addChild('bank', $this->get_option('bank_name', ''));
		$elado->addChild('bankszamlaszam', $this->get_option('bank_number', ''));
		$elado->addChild('emailReplyto', $this->get_option('auto_email_replyto', ''));
		$elado->addChild('emailTargy', WC_Szamlazz_Helpers::replace_note_placeholders($this->get_option('auto_email_subject', ''), $order));
		$elado->addChild('emailSzoveg', WC_Szamlazz_Helpers::replace_note_placeholders($this->get_option('auto_email_message', ''), $order));

		//Customer details
		$vevo = $szamla->addChild('vevo');
		$vevo->addChild('nev', '');

		//Set client name
		if($this->get_option('nev_csere') == 'yes') {
			$vevo->nev = $order->get_billing_last_name().' '.$order->get_billing_first_name();
		} else {
			$vevo->nev = $order->get_formatted_billing_full_name();
		}

		//Set company name
		if($order->get_billing_company() && $order->get_billing_company() != 'N/A') {
			if($this->get_option('company_name') == 'yes') {
				$vevo->nev = $order->get_billing_company().' - '.$vevo->nev;
			} else {
				$vevo->nev = $order->get_billing_company();
			}
		}

		//Set billing address
		$vevo->addChild('orszag', htmlspecialchars(WC()->countries->countries[$order->get_billing_country()] ? : ''));
		$vevo->addChild('irsz', $order->get_billing_postcode());
		$vevo->addChild('telepules', $order->get_billing_city());
		$vevo->addChild('cim', htmlspecialchars($order->get_billing_address_1()));
		$vevo->addChild('email', $order->get_billing_email());

		//Add second billing address if exists
		if($order->get_billing_address_2()) {
			$vevo->cim .= ' '.htmlspecialchars($order->get_billing_address_2());
		}

		//Do we need to send an email notification?
		$vevo->addChild('sendEmail', ($this->get_option('auto_email', 'yes') == 'yes') ? 'true' : 'false');

		//Don't send email for delivery note
		if($type == 'delivery') {
			$vevo->sendEmail = 'false';
		}

		//TAX number
		$taxcode = $order->get_meta( 'wc_szamlazz_adoszam' );
		if($order->get_meta( '_billing_wc_szamlazz_adoszam' )) $taxcode = $order->get_meta( '_billing_wc_szamlazz_adoszam' );

		//Let plugins change the vat number
		$taxcode = apply_filters('wc_szamlazz_xml_adoszam', $taxcode, $order);
		$adoszam_eu = apply_filters('wc_szamlazz_xml_adoszam_eu', '', $order);
		$vevo->addChild('adoszam', $taxcode);
		$vevo->addChild('csoportazonosito', '');
		$vevo->addChild('adoszamEU', $adoszam_eu);

		//Customer Shipping details if needed
		if($order->get_shipping_methods() && $this->get_option('hide_shipping_details', 'no') != 'yes') {
			$vevo->addChild('postazasiNev', '');
			$vevo->addChild('postazasiIrsz', $order->get_shipping_postcode());
			$vevo->addChild('postazasiTelepules', $order->get_shipping_city());
			$vevo->addChild('postazasiCim', $order->get_shipping_address_1());

			//Add second shipping address if exists
			if($order->get_shipping_address_2()) {
				$vevo->postazasiCim .= ' '.$order->get_shipping_address_2();
			}

			//Set client name
			if($this->get_option('nev_csere') == 'yes') {
				$vevo->postazasiNev = $order->get_shipping_last_name().' '.$order->get_shipping_first_name();
			} else {
				$vevo->postazasiNev = $order->get_formatted_shipping_full_name();
			}

			//Set company name
			if($order->get_shipping_company()) {
				if($this->get_option('company_name') == 'yes') {
					$vevo->postazasiNev = $order->get_shipping_company().' - '.$vevo->postazasiNev;
				} else {
					$vevo->postazasiNev = $order->get_shipping_company();
				}
			}
		}

		//Accounting details
		if($this->get_option('accounting_details_enabled', 'no') == 'yes' && $this->get_option('accounting_details_vevo_azonosito', 'no') == 'yes') {
			$vevo->addChild('vevoFokonyv');
			$vevo->vevoFokonyv->addChild('vevoAzonosito', $order->get_customer_id());
			$vevo->vevoFokonyv->addChild('vevoFokonyviSzam', $order->get_customer_id());
		}

		//User ID
		//$vevo->addChild('azonosito', $order->get_user_id());

		//Phone number
		$vevo->addChild('telefonszam', $order->get_billing_phone());

		/*
		//Check if we need to add waybill info
		if($this->get_option('template', 'SzlaMost') == 'SzlaFuvarlevelesAlap') {
			$fuvarlevel = $szamla->addChild('fuvarlevel');
			$fuvarlevel->addChild('futarSzolgalat', '');
			$fuvarlevel->addChild('vonalkod', '');
			$fuvarlevel->addChild('megjegyzes', '');
		}
		*/

		//Rounding precision. For HUF orders, we are rounding gross to 0 decimals as required by szamlazz.hu
		$rounding = ($order->get_currency() == 'HUF') ? 0 : wc_get_price_decimals();

		//Order Items
		$tetelek = $szamla->addChild('tetelek');
		$invoice_line_items = array();

		//Product items
		foreach( $order_items as $order_item ) {

			//$tetel = $tetelek->addChild('tetel');
			$tetel = new WCSzamlazzSimpleXMLElement('<tetel></tetel>');

			//Product name
			$tetel->addChild('megnevezes', esc_html(wp_strip_all_tags($order_item->get_name())));
			$tetel->addChild('azonosito', ($order_item->get_product()) ? $order_item->get_product()->get_sku() : '');
			$tetel->addChild('mennyiseg', $order_item->get_quantity());
			$tetel->addChild('mennyisegiEgyseg', $this->get_option('unit_type', __('pcs', 'wc-szamlazz')));

			//Custom product name
			if($order_item->get_product() && $order_item->get_product()->get_meta('wc_szamlazz_tetel_nev') && $order_item->get_product()->get_meta('wc_szamlazz_tetel_nev') != 'Array') {
				$tetel->megnevezes = esc_html($order_item->get_product()->get_meta('wc_szamlazz_tetel_nev'));
			}

			//Custom unit type
			if($order_item->get_product() && $order_item->get_product()->get_meta('wc_szamlazz_mennyisegi_egyseg') && $order_item->get_product()->get_meta('wc_szamlazz_mennyisegi_egyseg') != 'Array') {
				$tetel->mennyisegiEgyseg = $order_item->get_product()->get_meta('wc_szamlazz_mennyisegi_egyseg');
			}

			//Check if we need total or subtotal(total includes discount)
			$subtotal = $order_item->get_total();
			$subtotal_tax = $order_item->get_total_tax();
			if($this->get_option('separate_coupon') == 'yes') {
				$subtotal = $order_item->get_subtotal();
				$subtotal_tax = $order_item->get_subtotal_tax();
			}

			//Check if custom price is set
			if($order_item->get_product() && $order_item->get_product()->get_meta('wc_szamlazz_custom_cost') && $order_item->get_product()->get_meta('wc_szamlazz_custom_cost') != '' && $order_item->get_product()->get_meta('wc_szamlazz_custom_cost') != 'Array') {
				$orig_net = $subtotal;
				$orig_tax = $subtotal_tax;
				$subtotal = intval($order_item->get_product()->get_meta('wc_szamlazz_custom_cost')) * $order_item->get_quantity();
				$subtotal_tax = ($subtotal / $orig_net) * $orig_tax;
			}

			//Calculate the prices...
			$vat_rate = $this->get_order_item_tax_label($order, $order_item, $vevo);
			$vat_rate = WC_Szamlazz_Helpers::check_vat_override('product', $vat_rate, $order, $order_item);
			$tetel = $this->calculate_item_prices(array(
				'net' => $subtotal,
				'tax' => $subtotal_tax,
				'vat_rate' => $vat_rate,
				'qty' => $order_item->get_quantity(),
				'rounding' => $rounding,
				'tetel' => $tetel,
				'order_item' => $order_item
			));

			//Item note
			$note = '';

			//Show variation details if needed
			$product_name = $order_item->get_name();
			$note = html_entity_decode(wp_strip_all_tags(WC_Szamlazz_Helpers::get_item_meta( $order_item, array(
				'before' => "\n- ",
				'separator' => "\n- ",
				'after' => "",
				'echo' => false,
				'autop' => false,
				'label_before' => '',
				'label_after'  => ': ',
			))));
			if($note != '') $note .= "\n";

			//Hide note if needed, but still allow custom notes set in settings
			if($this->get_option('hide_item_notes', 'no') == 'yes') $note = '';

			//Custom note
			if($order_item->get_product() && $order_item->get_product()->get_meta('wc_szamlazz_megjegyzes') && $order_item->get_product()->get_meta('wc_szamlazz_megjegyzes') != 'Array') {
				$note .= $order_item->get_product()->get_meta('wc_szamlazz_megjegyzes');
			}

			//If we need to show sale price in the note
			if($this->get_option('discount_note') && $order_item->get_product() && $order_item->get_product()->is_on_sale()) {
				$sale_price = $order_item->get_product()->get_sale_price();
				$net_unit_price = $order_item->get_total()/$order_item->get_quantity();
				$gross_unit_price = ($order_item->get_total()+$order_item->get_total_tax())/$order_item->get_quantity();

				if(round($sale_price,2) == round($net_unit_price,2) || round($sale_price,2) == round($gross_unit_price,2)) {
					if(get_option( 'woocommerce_prices_include_tax') == 'no') {
						$afakulcs = 1+$order_item->get_total_tax()/$order_item->get_total();
					} else {
						$afakulcs = 1;
					}
					$regular_price = $order_item->get_product()->get_regular_price()*$afakulcs;
					$original_price = $regular_price*$order_item->get_quantity();
					$applied_sale = $original_price-($order_item->get_total()+$order_item->get_total_tax());
					$discounted_price = $order_item->get_total()+$order_item->get_total_tax();
					$discount_note = $this->get_option('discount_note');
					$discount_note_replacements = array('{eredeti_ar}' => wc_price($original_price), '{kedvezmeny_merteke}' => wc_price($applied_sale), '{kedvezmenyes_ar}' => wc_price($discounted_price));
					$discount_note = str_replace( array_keys( $discount_note_replacements ), array_values( $discount_note_replacements ), $discount_note);
					$discount_note = strip_tags($discount_note);
					$discount_note = html_entity_decode($discount_note);
					$note .= $discount_note;
				}
			}

			//Add note
			$tetel->addChild('megjegyzes', WC_Szamlazz_Helpers::replace_note_placeholders($note, $order));

			//See if we can get accounting details
			if($order_item->get_product()) {
				$accounting_details = $this->get_accounting_details($order, get_the_terms( $order_item->get_product_id(), 'product_cat' ), $order_item);
				if($accounting_details) {
					$tetel->addChild('tetelFokonyv');
					$tetel->tetelFokonyv->addChild('gazdasagiEsem', $accounting_details['gazd_esem']);
					$tetel->tetelFokonyv->addChild('gazdasagiEsemAfa', $accounting_details['afa_gazd_esem']);
					$tetel->tetelFokonyv->addChild('arbevetelFokonyviSzam', $accounting_details['fokonyvi_szam']);
					$tetel->tetelFokonyv->addChild('afaFokonyviSzam', $accounting_details['afa_fokonyvi_szam']);
				}
			}

			//Append to items
			if(!($order_item->get_product() && $order_item->get_product()->get_meta('wc_szamlazz_hide_item') && $order_item->get_product()->get_meta('wc_szamlazz_hide_item') == 'yes')) {

				//Skip free items
				if($this->get_option('hide_free_items', 'no') == 'yes' && $tetel->bruttoErtek == 0) continue;

				//Allow developres to modify
				$tetel = apply_filters('wc_szamlazz_invoice_line_item', $tetel, $order_item, $order, $szamla);
				if($tetel) {
					$tetelek->appendXML($tetel);
					$invoice_line_items[] = $tetel;
				}
			}

		}

		//Shipping
		foreach( $order->get_items( 'shipping' ) as $item_id => $shipping_item_obj ) {
			$order_shipping = $shipping_item_obj->get_total();
			$order_shipping_tax = $shipping_item_obj->get_total_tax();
			if($this->get_option('hide_free_shipping') == 'yes' && $order_shipping == 0) {
				continue;
			}

			$tetel = new WCSzamlazzSimpleXMLElement('<tetel></tetel>');
			$tetel->addChild('megnevezes', esc_html($shipping_item_obj->get_method_title()));
			$tetel->addChild('mennyiseg', 1);
			$tetel->addChild('mennyisegiEgyseg', $this->get_option('unit_type', __('pcs', 'wc-szamlazz')));

			//Calculate prices
			$vat_rate = $this->get_order_shipping_tax_label($order, $shipping_item_obj, $vevo);
			$vat_rate = WC_Szamlazz_Helpers::check_vat_override('shipping', $vat_rate, $order, $shipping_item_obj);
			$tetel = $this->calculate_item_prices(array(
				'net' => $order_shipping,
				'tax' => $order_shipping_tax,
				'vat_rate' => $vat_rate,
				'rounding' => $rounding,
				'tetel' => $tetel,
				'order_item' => $shipping_item_obj
			));

			$tetel->addChild('megjegyzes','');

			//See if we can get accounting details
			$accounting_details = $this->get_accounting_details($order, $shipping_item_obj->get_method_id(), $shipping_item_obj);
			if($accounting_details) {
				$tetel->addChild('tetelFokonyv');
				$tetel->tetelFokonyv->addChild('gazdasagiEsem', $accounting_details['gazd_esem']);
				$tetel->tetelFokonyv->addChild('gazdasagiEsemAfa', $accounting_details['afa_gazd_esem']);
				$tetel->tetelFokonyv->addChild('arbevetelFokonyviSzam', $accounting_details['fokonyvi_szam']);
				$tetel->tetelFokonyv->addChild('afaFokonyviSzam', $accounting_details['afa_fokonyvi_szam']);
			}

			//Check if we have a custom name specified
			$shipping_method_options = get_option('woocommerce_'.$shipping_item_obj->get_method_id().'_'.$shipping_item_obj->get_instance_id().'_settings');
			if($shipping_method_options && isset($shipping_method_options['wc_szamlazz_tetel_nev']) && !empty($shipping_method_options['wc_szamlazz_tetel_nev'])) {
				$tetel->megnevezes = $shipping_method_options['wc_szamlazz_tetel_nev'];
			}

			if($shipping_method_options && isset($shipping_method_options['wc_szamlazz_tetel_megjegyzes']) && !empty($shipping_method_options['wc_szamlazz_tetel_megjegyzes'])) {
				$tetel->megjegyzes = $shipping_method_options['wc_szamlazz_tetel_megjegyzes'];
			}

			if($shipping_method_options && isset($shipping_method_options['wc_szamlazz_tetel_mennyisegi_egyseg']) && !empty($shipping_method_options['wc_szamlazz_tetel_mennyisegi_egyseg'])) {
				$tetel->mennyisegiEgyseg = $shipping_method_options['wc_szamlazz_tetel_mennyisegi_egyseg'];
			}

			//Append to xml
			$tetel = apply_filters('wc_szamlazz_invoice_line_item', $tetel, $shipping_item_obj, $order, $szamla);
			if($tetel) {
				$tetelek->appendXML($tetel);
				$invoice_line_items[] = $tetel;
			}

		}

		//Extra Fees
		$fees = $order->get_fees();
		if(!empty($fees)) {
			foreach( $fees as $fee ) {
				$tetel = new WCSzamlazzSimpleXMLElement('<tetel></tetel>');
				$tetel->addChild('megnevezes',esc_html($fee->get_name()));
				$tetel->addChild('mennyiseg', 1);
				$tetel->addChild('mennyisegiEgyseg', $this->get_option('unit_type', __('pcs', 'wc-szamlazz')));

				$vat_rate = $this->get_order_shipping_tax_label($order, $fee, $vevo);
				$vat_rate = WC_Szamlazz_Helpers::check_vat_override('fee', $vat_rate, $order, $fee);
				$tetel = $this->calculate_item_prices(array(
					'net' => $fee->get_total(),
					'tax' => $fee->get_total_tax(),
					'vat_rate' => $vat_rate,
					'rounding' => $rounding,
					'tetel' => $tetel,
					'order_item' => $fee
				));

				$tetel->addChild('megjegyzes','');

				//Append to xml
				$tetel = apply_filters('wc_szamlazz_invoice_line_item', $tetel, $fee, $order, $szamla);
				if($tetel) {
					$tetelek->appendXML($tetel);
					$invoice_line_items[] = $tetel;
				}
			}
		}

		//Discount
		if ( $order->get_total_discount() > 0 ) {
			$discout_details = $this->get_coupon_invoice_item_details($order);

			//If coupon is a separate item
			if($this->get_option('separate_coupon') == 'yes') {

				$tetel = new WCSzamlazzSimpleXMLElement('<tetel></tetel>');
				$tetel->addChild('megnevezes', $discout_details["title"]);
				$tetel->addChild('mennyiseg', 1);
				$tetel->addChild('mennyisegiEgyseg', $this->get_option('unit_type', __('pcs', 'wc-szamlazz')));
				$vat_rate = $this->get_order_discout_tax_label($order);
				$vat_rate = WC_Szamlazz_Helpers::check_vat_override('discount', $vat_rate, $order);

				$tetel = $this->calculate_item_prices(array(
					'net' => $order->get_total_discount(),
					'tax' => $order->get_discount_tax(),
					'vat_rate' => $vat_rate,
					'rounding' => $rounding,
					'tetel' => $tetel,
					'negative' => true
				));

				$tetel->addChild('megjegyzes', $discout_details["desc"]);

				//Append to xml
				$tetelek->appendXML($tetel);
				$invoice_line_items[] = $tetel;

			} else {
				//Add space if theres already something in the comment
				if($szamla->fejlec->megjegyzes) {
					$szamla->fejlec->megjegyzes .= "\n";
				}
				$szamla->fejlec->megjegyzes .= $discout_details["desc"];
			}
		}

		//Refunds
		$order_refunds = $order->get_refunds();
		if ( $order_refunds ) {

			foreach ( $order_refunds as $refund ) {
				$tetel = new WCSzamlazzSimpleXMLElement('<tetel></tetel>');
				$tetel->addChild('megnevezes', __('Refund', 'wc-szamlazz'));
				$tetel->addChild('mennyiseg', 1);
				$tetel->addChild('mennyisegiEgyseg', $this->get_option('unit_type', __('pcs', 'wc-szamlazz')));
				$vat_rate = $this->get_order_shipping_tax_label($order, $refund, $vevo);
				$vat_rate = WC_Szamlazz_Helpers::check_vat_override('refund', $vat_rate, $order, $refund);

				$tetel = $this->calculate_item_prices(array(
					'net' => $refund->get_total()-$refund->get_total_tax(),
					'tax' => $refund->get_total_tax(),
					'vat_rate' => $vat_rate,
					'rounding' => $rounding,
					'tetel' => $tetel,
					'order_item' => $refund
				));

				$tetel->addChild('megjegyzes','');

				//Append to xml
				$tetelek->appendXML(apply_filters('wc_szamlazz_invoice_line_item', $tetel, $refund, $order, $szamla));
				$invoice_line_items[] = $tetel;

			}
		}

		//If we are creating an invoice based on a deposit invoice, duplicate invoice line items as negative values
		if($this->is_invoice_generated($orderId, 'deposit') && $type == 'invoice') {
			foreach ($invoice_line_items as $invoice_line_item) {
				//Convert prices to negative
				$invoice_line_item->mennyiseg = floatval($invoice_line_item->mennyiseg)*-1;
				$invoice_line_item->nettoErtek = floatval($invoice_line_item->nettoErtek)*-1;
				$invoice_line_item->afaErtek = floatval($invoice_line_item->afaErtek)*-1;
				$invoice_line_item->bruttoErtek = floatval($invoice_line_item->bruttoErtek)*-1;

				$tetelek->appendXML($invoice_line_item);
			}
		}

		//If we are creating an invoice based on a deposit invoice, duplicate invoice line items as negative values
		if($type == 'corrected') {
			foreach ($tetelek as $invoice_line_item) {
				//Convert prices to negative
				$invoice_line_item->mennyiseg = floatval($invoice_line_item->mennyiseg)*-1;
				$invoice_line_item->nettoErtek = floatval($invoice_line_item->nettoErtek)*-1;
				$invoice_line_item->afaErtek = floatval($invoice_line_item->afaErtek)*-1;
				$invoice_line_item->bruttoErtek = floatval($invoice_line_item->bruttoErtek)*-1;
			}
		}

		//If we don't have any line items, generate error instead
		if(empty($invoice_line_items)) {

			//Create response
			$response['error'] = true;
			$response['messages'][] = __('No line items on the invoice.', 'wc-szamlazz');
			$order->add_order_note(esc_html__('Szamlazz.hu invoice generation failed! No line items on the invoice.', 'wc-szamlazz'));

			//Callbacks
			do_action('wc_szamlazz_after_invoice_error', $order, false);

			return $response;

		}

		//Check for advanced options
		if($this->get_option('advanced_settings', 'no') == 'yes') {
			$szamla = WC_Szamlazz_Conditions::check_advanced_options($szamla, $order, $type);
		}

		//Allow plugins to customize
		$xml_szamla = apply_filters('wc_szamlazz_xml', $szamla, $order, $type, $options);

		//If plugins return empty, don't create a document
		if(!$xml_szamla) {
			return false;
		}

		//Generate XML
		$xml = $xml_szamla->asXML();

		//KATA compatibility
		if($this->get_option('kata_compatibility', 'no') == 'yes' && !WC_Szamlazz_Helpers::validate_kata($szamla)) {
			$response['xml'] = $xml;
			$response['error'] = true;
			$response['messages'][] = __('Invoices for orders with a VAT number are disabled.', 'wc-szamlazz');
			if(!isset($options['preview'])) {
				$order->add_order_note(esc_html__('Szamlazz.hu invoice generation failed! Invoices for orders with a VAT number are disabled.', 'wc-szamlazz'));
			}
			return $response;
		}

		//Return the XML for the preview function
		if(isset($options['preview'])) {
			$response['xml'] = $xml;
			return $response;
		}

		//Get response from Számlázz.hu
		$xml_response = $this->xml_generator->generate($xml, $orderId, 'action-xmlagentxmlfile');

		//If theres an error in the response
		if($xml_response['error']) {

			//Create response
			$response['error'] = true;
			$response['messages'] = $xml_response['messages'];
			$order->add_order_note(sprintf(esc_html__('Szamlazz.hu invoice generation failed! Agent error code: %s', 'wc-szamlazz'), urldecode($xml_response['agent_error'])));

			//Callbacks
			do_action('wc_szamlazz_after_invoice_error', $order, $xml_response);

			return $response;
		} else {

			//Get the Invoice ID from the response header
			$invoice_name = $this->xml_generator->get_invoice_id($xml_response['header_array']);

			//Download & Store PDF - generate a random file name so it will be downloadable later only by you
			$invoice_pdf = $this->xml_generator->save_pdf_file($type, $orderId, false, $invoice_name);

			//Do we sent an email?
			$auto_email_sent = ($this->get_option('auto_email', 'yes') == 'yes');

			//Create response
			$response['name'] = $invoice_name;

			//Based on invoice type
			switch ($type) {

				//Regular invoice
				case 'invoice':
					$response['messages'][] = ($auto_email_sent) ? esc_html__('Invoice successfully generated and sent to the customer via e-mail.','wc-szamlazz') : esc_html__('Invoice successfully generated.','wc-szamlazz');

					//Update order notes
					$order->add_order_note(sprintf(esc_html__('Számlázz.hu invoice generated successfully. Invoice number: %s', 'wc-szamlazz'), $invoice_name));

					//Store the filename
					$order->update_meta_data( '_wc_szamlazz_invoice', $invoice_name );
					$order->update_meta_data( '_wc_szamlazz_invoice_pdf', $invoice_pdf );

					//If it was manually generated with a custom account
					if(isset( $_POST['action']) && $_POST['action'] == 'wc_szamlazz_generate_invoice' && isset($_POST['account']) && $_POST['account'] != $this->get_option('agent_key')) {
						$order->update_meta_data( '_wc_szamlazz_account_id', substr(sanitize_text_field($_POST['account']), 0, 5) );
					}

					//Mark as paid if needed
					if($is_invoice_already_paid) {
						$order->update_meta_data( '_wc_szamlazz_completed', date_i18n('Y-m-d') );
						$response['completed'] = date_i18n('Y-m-d', date_i18n('Y-m-d') );
					}

					//Return download links
					$response['link'] = $this->generate_download_link($order);

					break;

				//Proforma invoice
				case 'proform':
					$response['messages'][] = ($auto_email_sent) ? esc_html__('Proforma invoice successfully generated and sent to the customer via e-mail.','wc-szamlazz') : esc_html__('Proforma invoice successfully generated.','wc-szamlazz');

					//Update order notes
					$order->add_order_note(sprintf(esc_html__('Számlázz.hu proforma invoice generated successfully. Invoice number: %s', 'wc-szamlazz'), $invoice_name));

					//Store the filename
					$order->update_meta_data( '_wc_szamlazz_proform', $invoice_name );
					$order->update_meta_data( '_wc_szamlazz_proform_pdf', $invoice_pdf );

					//Return download links
					$response['link'] = $this->generate_download_link($order, 'proform');

					break;

				//Proforma invoice
				case 'deposit':
					$response['messages'][] = ($auto_email_sent) ? esc_html__('Deposit invoice successfully generated and sent to the customer via e-mail.','wc-szamlazz') : esc_html__('Deposit invoice successfully generated.','wc-szamlazz');

					//Update order notes
					$order->add_order_note(sprintf(esc_html__('Számlázz.hu deposit invoice generated successfully. Invoice number: %s', 'wc-szamlazz'), $invoice_name));

					//Store the filename
					$order->update_meta_data( '_wc_szamlazz_deposit', $invoice_name );
					$order->update_meta_data( '_wc_szamlazz_deposit_pdf', $invoice_pdf );

					//Return download links
					$response['link'] = $this->generate_download_link($order, 'deposit');

					break;

				//Delivery note
				case 'delivery':
					$response['messages'][] = esc_html__('Delivery note successfully generated.','wc-szamlazz');

					//Update order notes
					$order->add_order_note(sprintf(esc_html__('Számlázz.hu delivery note successfully generated. Number of the delivery note: %s', 'wc-szamlazz'), $invoice_name));

					//Store the filename
					$order->update_meta_data( '_wc_szamlazz_delivery', $invoice_name );
					$order->update_meta_data( '_wc_szamlazz_delivery_pdf', $invoice_pdf );

					//Return download links
					$response['link'] = $this->generate_download_link($order, 'delivery');

					break;

				//Corrected invoice
				case 'corrected':
					$response['messages'][] = esc_html__('Correction invoice generated.','wc-szamlazz');

					//Update order notes
					$order->add_order_note(sprintf(esc_html__('Számlázz.hu correction invoice successfully generated. Invoice number: %s', 'wc-szamlazz'), $invoice_name));

					//Store the filename
					$order->update_meta_data( '_wc_szamlazz_corrected', $invoice_name );
					$order->update_meta_data( '_wc_szamlazz_corrected_pdf', $invoice_pdf );

					//Return download links
					$response['link'] = $this->generate_download_link($order, 'corrected');

					break;
			}

			//Delete void invoice if exists
			$order->delete_meta_data( '_wc_szamlazz_void' );
			$order->delete_meta_data( '_wc_szamlazz_void_pdf' );

			//Save the order
			$order->save();

			//Run action on successful invoice creation
			do_action('wc_szamlazz_after_invoice_success', $order, $response);

			//Action for webhooks
			do_action( 'wc_szamlazz_document_created', array('order_id' => $order->get_id(), 'document_type' => $type) );

			return $response;
		}
	}

	//Generate XML for Szamla Agent
	public function generate_invoice_complete($orderId, $date = false) {
		$order = wc_get_order($orderId);

		//Build Xml
		$szamla = new WCSzamlazzSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlszamlakifiz xmlns="http://www.szamlazz.hu/xmlszamlakifiz" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamlakifiz http://www.szamlazz.hu/docs/xsds/agentkifiz/xmlszamlakifiz.xsd"></xmlszamlakifiz>');

		//Response
		$response = array();
		$response['error'] = false;

		//Authentication
		if($order->get_meta('_wc_szamlazz_account_id')) $fixed_key = $this->get_szamlazz_agent_key_by_id($order->get_meta('_wc_szamlazz_account_id'));
		$szamla->appendXML($this->get_authentication_xml_object($order));

		//Account & Invoice settings
		$szamla->beallitasok->addChild('szamlaszam', str_replace(array('.', ' ', "\n", "\t", "\r"), '', $order->get_meta('_wc_szamlazz_invoice')));
		$szamla->beallitasok->addChild('additiv', 'false');

		//Invoice details
		$kifizetes = $szamla->addChild('kifizetes');

		//Check if payment date is stored
		$date_paid = date_i18n('Y-m-d');
		$date_paid_order = $order->get_date_paid();
		if( ! empty( $date_paid_order) ){
			$date_paid = $date_paid_order->date("Y-m-d");
		}

		//If a custom date is set
		if($date) {
			$date_paid = $date;
		}

		//Set date
		$kifizetes->addChild('datum', $date_paid );

		//Payment method
		$kifizetes->addChild('jogcim', $order->get_payment_method_title() ?: '-');

		//Rounding precision. For HUF orders, we are rounding gross to 0 decimals as required by szamlazz.hu
		$rounding = ($order->get_currency() == 'HUF') ? 0 : wc_get_price_decimals();

		//Set total cost
		$kifizetes->addChild('osszeg', round($order->get_total(), $rounding));

		//Generate XML
		$xml_szamla = apply_filters('wc_szamlazz_xml_kifiz',$szamla,$order);
		$xml = $xml_szamla->asXML();

		//Get response from Számlázz.hu
		$xml_response = $this->xml_generator->generate($xml, $orderId, 'action-szamla_agent_kifiz');

		if($xml_response['error']) {
			$response['error'] = true;
			$response['messages'][] = esc_html__('Failed to mark the invoice as paid.', 'wc-szamlazz');

			//Update order notes
			$order->add_order_note( sprintf(__( 'Failed to mark the Szamlazz.hu invoice as paid! Agent error code: %s', 'wc-szamlazz' ), urldecode($xml_response['agent_error'])) );

			return $response;

		} else {

			//Store as a custom field
			$order->update_meta_data( '_wc_szamlazz_completed', $date_paid );

			//Update order notes
			$order->add_order_note( esc_html__( 'Invoice successfully marked as paid', 'wc-szamlazz' ) );

			//Save order
			$order->save();

			//Response
			$response['completed'] = $date_paid;

			do_action('wc_szamlazz_after_invoice_complete_success', $order, $response);

			return $response;
		}

	}

	//Generate XML for Szamla Agent Sztornó
	public function generate_void_invoice($orderId, $options = array()) {
		$order = wc_get_order($orderId);

		//If we only have a proform invoice but not a normal one, delete it instead of creating a void invoice
		if(!$this->is_invoice_generated($orderId) && $this->is_invoice_generated($orderId, 'proform')) {
			return $this->generate_proform_delete($orderId);
		}

		//Response
		$response = array();
		$response['error'] = false;

		//If it was manually updated, we just need to delete the meta
		if($order->get_meta('_wc_szamlazz_invoice_manual')) {

			//Update order notes
			$order->add_order_note(esc_html__('Manually uploaded invoice deleted successfully.', 'wc-szamlazz'));
			$response['messages'][] = __('Manually uploaded invoice deleted successfully.', 'wc-szamlazz');

			//Delete existing meta
			$order->delete_meta_data( '_wc_szamlazz_invoice' );
			$order->delete_meta_data( '_wc_szamlazz_invoice_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_invoice_manual' );
			$order->delete_meta_data( '_wc_szamlazz_delivery' );
			$order->delete_meta_data( '_wc_szamlazz_delivery_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_delivery_manual' );
			$order->delete_meta_data( '_wc_szamlazz_deposit' );
			$order->delete_meta_data( '_wc_szamlazz_deposit_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_deposit_manual' );
			$order->delete_meta_data( '_wc_szamlazz_completed' );
			$order->delete_meta_data( '_wc_szamlazz_account_id' );
			$order->save();
			return $response;
		}

		//Build Xml
		$szamla = new WCSzamlazzSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlszamlast xmlns="http://www.szamlazz.hu/xmlszamlast" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamlast http://www.szamlazz.hu/docs/xsds/agentst/xmlszamlast.xsd"></xmlszamlast>');

		//Authentication
		$fixed_key = false;
		if(isset($options['account'])) $fixed_key = sanitize_text_field($options['account']);
		if($order->get_meta('_wc_szamlazz_account_id')) $fixed_key = $this->get_szamlazz_agent_key_by_id($order->get_meta('_wc_szamlazz_account_id'));
		$szamla->appendXML($this->get_authentication_xml_object($order, $fixed_key));

		//Invoice basic settings
		$electronic_invoice_type = WC_Szamlazz_Helpers::get_invoice_type($order);
		$szamla->beallitasok->addChild('eszamla', $electronic_invoice_type);
		$szamla->beallitasok->addChild('szamlaLetoltes', 'true');

		//Invoice details
		$fejlec = $szamla->addChild('fejlec');

		//Check what are we going to void
		if($this->is_invoice_generated($orderId)) {
			$szamlaszam = $order->get_meta('_wc_szamlazz_invoice');
		} else if($this->is_invoice_generated($orderId, 'proform')) {
			$szamlaszam = $order->get_meta('_wc_szamlazz_proform');
		} else if($this->is_invoice_generated($orderId, 'deposit')) {
			$szamlaszam = $order->get_meta('_wc_szamlazz_deposit');
		} else {
			$response['error'] = true;
			$response['messages'][] = __('There is nothing to cancel', 'wc-szamlazz');
			return $response;
		}

		//Create header
		$fejlec->addChild('szamlaszam', str_replace(array('.', ' ', "\n", "\t", "\r"), '', $szamlaszam));
		$fejlec->addChild('keltDatum', date_i18n('Y-m-d') );

		//Required elements
		$elado = $szamla->addChild('elado');
		$vevo = $szamla->addChild('vevo');
		
		//Generate XML
		$xml_szamla = apply_filters('wc_szamlazz_xml_void',$szamla,$order);
		$xml = $xml_szamla->asXML();

		//Get response from Számlázz.hu
		$xml_response = $this->xml_generator->generate($xml, $orderId, 'action-szamla_agent_st');

		if($xml_response['error']) {

			//Update order notes
			$order->add_order_note( sprintf(esc_html__( 'Szamlazz.hu reverse invoice generation failed! Agent error code: %s', 'wc-szamlazz' ), urldecode($xml_response['agent_error'])) );

			//Create response
			$response['error'] = true;
			$response['messages'] = $xml_response['messages'];

			do_action('wc_szamlazz_after_invoice_void_error', $order, $response);

			return $response;
		} else {

			//Get the Invoice ID from the response header
			$invoice_void_name = $this->xml_generator->get_invoice_id($xml_response['header_array']);

			//Download & Store PDF - generate a random file name so it will be downloadable later only by you
			$invoice_void_pdf = $this->xml_generator->save_pdf_file('void', $orderId, false, $invoice_void_name);

			//Store as a custom field
			$order->update_meta_data( '_wc_szamlazz_void', $invoice_void_name );
			$order->update_meta_data( '_wc_szamlazz_void_pdf', $invoice_void_pdf );

			//Get existing pdf invoice link & update order notes
			if($this->is_invoice_generated($orderId)) {
				$invoice_pdf_url = $this->generate_download_link($order, 'invoice');
				$invoice_number = $order->get_meta('_wc_szamlazz_invoice');
				$order->add_order_note(sprintf(esc_html__('Reverse invoice created successfully. Invoice number: %s. Original invoice: %s', 'wc-szamlazz'), $invoice_void_name, '<a target="_blank" href="'.$invoice_pdf_url.'">'.$invoice_number.'</a>'));
			} else {
				$order->add_order_note(sprintf(esc_html__('Reverse invoice created successfully. Invoice number: %s.', 'wc-szamlazz'), $invoice_void_name));
			}

			//Delete existing meta
			$order->delete_meta_data( '_wc_szamlazz_invoice' );
			$order->delete_meta_data( '_wc_szamlazz_invoice_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_invoice_manual' );
			$order->delete_meta_data( '_wc_szamlazz_delivery' );
			$order->delete_meta_data( '_wc_szamlazz_delivery_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_delivery_manual' );
			$order->delete_meta_data( '_wc_szamlazz_deposit' );
			$order->delete_meta_data( '_wc_szamlazz_deposit_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_deposit_manual' );
			$order->delete_meta_data( '_wc_szamlazz_completed' );
			$order->delete_meta_data( '_wc_szamlazz_account_id' );

			//Optionally delete proform invoices
			if($this->get_option('delete_proform_too', 'yes') == 'yes') {
				$deleted_proform_invoice = $this->generate_proform_delete($orderId);
			}

			//Create response
			$response['messages'][] = esc_html__('Reverse invoice created successfully.','wc-szamlazz');
			$response['name'] = $invoice_void_name;
			$response['link'] = $this->generate_download_link($order, 'void');

			//Save the order
			$order->save();

			do_action('wc_szamlazz_after_invoice_void_success', $order, $response);

			//Action for webhooks
			do_action( 'wc_szamlazz_document_created', array('order_id' => $order->get_id(), 'document_type' => 'void') );

			return $response;
		}

	}

	//Generate XML for Szamla Agent Sztornó
	public function generate_proform_delete($orderId) {
		$order = wc_get_order($orderId);

		//Build Xml
		$szamla = new WCSzamlazzSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlszamladbkdel xmlns="http://www.szamlazz.hu/xmlszamladbkdel" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamladbkdel http://www.szamlazz.hu/docs/xsds/szamladbkdel/xmlszamladbkdel.xsd"></xmlszamladbkdel>');

		//Response
		$response = array();
		$response['error'] = false;

		//If it was manually updated, we just need to delete the meta
		if($order->get_meta('_wc_szamlazz_proform_manual')) {

			//Update order notes
			$order->add_order_note(esc_html__('Manually uploaded invoice deleted successfully.', 'wc-szamlazz'));
			$response['messages'][] = __('Manually uploaded invoice deleted successfully.', 'wc-szamlazz');

			//Delete existing meta
			$order->delete_meta_data( '_wc_szamlazz_proform' );
			$order->delete_meta_data( '_wc_szamlazz_proform_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_proform_manual' );
			$order->save();
			return $response;
		}

		//Authentication
		$szamla->appendXML($this->get_authentication_xml_object($order));

		//Invoice details
		$fejlec = $szamla->addChild('fejlec');
		$szamlaszam = $order->get_meta('_wc_szamlazz_proform');

		//Create header
		$fejlec->addChild('szamlaszam', str_replace(array('.', ' ', "\n", "\t", "\r"), '', $szamlaszam));

		//Generate XML
		$xml_szamla = apply_filters('wc_szamlazz_xml_proform_delete',$szamla,$order);
		$xml = $xml_szamla->asXML();

		//Get response from Számlázz.hu
		$xml_response = $this->xml_generator->generate($xml, $orderId, 'action-szamla_agent_dijbekero_torlese');

		//Check for errors
		$xml_error = false;
		if($xml_response['error']) {
			$xml_error = true;
		}

		//Delete proform for error 335, which means it was already deleted manually on szamlazz.hu
		if($xml_error && isset($xml_response['agent_error_code']) && urldecode($xml_response['agent_error_code']) == '335') {
			$xml_error = false;
		}

		if($xml_error) {

			//Update order notes
			$order->add_order_note( sprintf(esc_html__( 'Unable to delete the Szamlazz.hu proforma invoice! Agent error code: %s', 'wc-szamlazz' ), urldecode($xml_response['agent_error'])) );

			//Create response
			$response['error'] = true;
			$response['messages'] = $xml_response['messages'];

			do_action('wc_szamlazz_after_proform_delete_error', $order, $response);

			return $response;
		} else {

			//Update order notes
			$order->add_order_note( sprintf(esc_html__('Proforma invoice deleted successfully. This was the proforma invoice number: %s', 'wc-szamlazz'), $szamlaszam));

			//Delete existing meta
			$order->delete_meta_data( '_wc_szamlazz_invoice' );
			$order->delete_meta_data( '_wc_szamlazz_invoice_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_proform' );
			$order->delete_meta_data( '_wc_szamlazz_proform_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_delivery' );
			$order->delete_meta_data( '_wc_szamlazz_delivery_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_deposit' );
			$order->delete_meta_data( '_wc_szamlazz_deposit_pdf' );
			$order->delete_meta_data( '_wc_szamlazz_completed' );

			//Create response
			$response['messages'][] = esc_html__('Proforma invoice deleted.','wc-szamlazz');
			$response['link'] = 'proform_deleted';

			//Save the order
			$order->save();

			do_action('wc_szamlazz_after_proform_delete_success', $order, $response);

			return $response;
		}

	}

	//Generate XML for Szamla Agent
	public function generate_receipt($orderId) {
		$order = wc_get_order($orderId);
		$order_items = $order->get_items();

		//Build Xml
		$szamla = new WCSzamlazzSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlnyugtacreate xmlns="http://www.szamlazz.hu/xmlnyugtacreate" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlnyugtacreate http://www.szamlazz.hu/docs/xsds/nyugta/xmlnyugtacreate.xsd"></xmlnyugtacreate>');

		//Response
		$response = array();
		$response['error'] = false;

		//Account & Invoice settings
		$szamla->appendXML($this->get_authentication_xml_object($order));
		$szamla->beallitasok->addChild('pdfLetoltes', 'true');

		//Invoice details
		$fejlec = $szamla->addChild('fejlec');
		$fejlec->addChild('hivasAzonosito', $order->get_order_number());
		$fejlec->addChild('elotag', $this->get_option('receipt_prefix'));
		$fejlec->addChild('fizmod', $order->get_payment_method_title());
		$fejlec->addChild('penznem', WC_Szamlazz_Helpers::get_currency($order));
		$fejlec->addChild('megjegyzes', $this->get_option('receipt_note'));
		if($this->get_option('receipt_template')) {
			$fejlec->addChild('pdfSablon', $this->get_option('receipt_template'));
		}

		//Get order note
		if($this->get_option('receipt_note') == '') {
			$note = $this->get_invoice_note($order, 'receipt', 'hu', $szamla);

			//Replace customer email and phone number in note
			$note = WC_Szamlazz_Helpers::replace_note_placeholders($note, $order);

			//Set note
			$fejlec->megjegyzes = $note;
		}

		if($order->get_currency() != 'HUF') {
			//if the base currency is not HUF, we should define currency rates
			$fejlec->addChild('devizabank', 'MNB');
			$exchange_rate = get_transient( 'wc_szamlazz_mnb_arfolyam_kozep' );
			if(!$exchange_rate) {
				$exchange_rate = wp_remote_retrieve_body( wp_remote_get( 'http://api.napiarfolyam.hu?bank=mnb&valuta='.$order_currency ) );
				$napiarfolyam_xml = new SimpleXMLElement($exchange_rate);
				$napiarfolyam_kozep = (Array)$napiarfolyam_xml->deviza->item->kozep;
				$napiarfolyam_kozep = $napiarfolyam_kozep[0];
				set_transient( 'wc_szamlazz_mnb_arfolyam_kozep', $napiarfolyam_kozep, 60*60*12 );
				$exchange_rate = $napiarfolyam_kozep;
			}
			$fejlec->addChild('devizaarf', $exchange_rate);
		}

		//Rounding precision. For HUF orders, we are rounding gross to 0 decimals as required by szamlazz.hu
		$rounding = ($order->get_currency() == 'HUF') ? 0 : wc_get_price_decimals();

		//Order Items
		$tetelek = $szamla->addChild('tetelek');
		foreach( $order_items as $order_item ) {

			$tetel = $tetelek->addChild('tetel');

			//Product name
			$tetel->addChild('megnevezes', esc_html($order_item->get_name()));
			$tetel->addChild('azonosito', ($order_item->get_product()) ? $order_item->get_product()->get_sku() : '');
			$tetel->addChild('mennyiseg', $order_item->get_quantity());
			$tetel->addChild('mennyisegiEgyseg', $this->get_option('unit_type', __('pcs', 'wc-szamlazz')));

			//Custom product name
			if($order_item->get_product() && $order_item->get_product()->get_meta('wc_szamlazz_tetel_nev')) {
				$tetel->megnevezes = esc_html($order_item->get_product()->get_meta('wc_szamlazz_tetel_nev'));
			}

			//Custom unit type
			if($order_item->get_product() && $order_item->get_product()->get_meta('wc_szamlazz_mennyisegi_egyseg')) {
				$tetel->mennyisegiEgyseg = $order_item->get_product()->get_meta('wc_szamlazz_mennyisegi_egyseg');
			}

			//Check if we need total or subtotal(total includes discount)
			$subtotal = $order_item->get_subtotal();
			$subtotal_tax = $order_item->get_subtotal_tax();

			//Calculate the prices...
			$vat_rate = $this->get_order_item_tax_label($order, $order_item);

			$tetel = $this->calculate_item_prices(array(
				'net' => $subtotal,
				'tax' => $subtotal_tax,
				'vat_rate' => $vat_rate,
				'qty' => $order_item->get_quantity(),
				'rounding' => $rounding,
				'tetel' => $tetel,
				'document' => 'receipt',
				'order_item' => $order_item
			));

		}

		//Shipping
		//We don't have shipping on receipts, because its for digital products

		//Extra Fees
		$fees = $order->get_fees();
		if(!empty($fees)) {
			foreach( $fees as $fee ) {
				$tetel = $tetelek->addChild('tetel');
				$tetel->addChild('megnevezes',esc_html($fee->get_name()));
				$tetel->addChild('mennyiseg', 1);
				$tetel->addChild('mennyisegiEgyseg', $this->get_option('unit_type', __('pcs', 'wc-szamlazz')));

				$vat_rate = $this->get_order_shipping_tax_label($order, $fee);
				$tetel = $this->calculate_item_prices(array(
					'net' => $fee->get_total(),
					'tax' => $fee->get_total_tax(),
					'vat_rate' => $vat_rate,
					'rounding' => $rounding,
					'tetel' => $tetel,
					'document' => 'receipt',
					'order_item' => $fee
				));
			}
		}

		//Discount
		if ( $order->get_total_discount() > 0 ) {
			$discout_details = $this->get_coupon_invoice_item_details($order);

			$tetel = $tetelek->addChild('tetel');
			$tetel->addChild('megnevezes', $discout_details["title"]);
			$tetel->addChild('mennyiseg', '1');
			$tetel->addChild('mennyisegiEgyseg', $this->get_option('unit_type', __('pcs', 'wc-szamlazz')));
			$vat_rate = round( ($order->get_discount_tax()/$order->get_total_discount()) * 100 );

			//Use tax override if its empty
			if($vat_rate == 0 && $this->get_option('afakulcs') != '') {
				$vat_rate = $this->get_option('afakulcs');
			}

			$tetel = $this->calculate_item_prices(array(
				'net' => $order->get_total_discount(),
				'tax' => $order->get_discount_tax(),
				'vat_rate' => $vat_rate,
				'rounding' => $rounding,
				'tetel' => $tetel,
				'negative' => true,
				'document' => 'receipt'
			));
		}

		//Generate XML
		$xml_szamla = apply_filters('wc_szamlazz_xml_receipt',$szamla,$order);
		$xml = $xml_szamla->asXML();

		$xml_response = $this->xml_generator->generate($xml, $orderId, 'action-szamla_agent_nyugta_create');
		$agent_body_xml = new SimpleXMLElement($xml_response['agent_body']);

		// ezt majd true-ra állítjuk ha volt hiba
		$volt_hiba = false;

		// ebben lesznek a hiba információk, plusz a bodyban
		$agent_error = '';
		$agent_error_code = '';

		// Nézzük meg volt e hiba
		if(!filter_var($agent_body_xml->sikeres, FILTER_VALIDATE_BOOLEAN)) {
			$volt_hiba = true;
			$agent_error = $agent_body_xml->hibauzenet;
			$agent_error_code = $agent_body_xml->hibakod;
		}

		if($xml_response['error'] || $volt_hiba) {

			//Create response
			$response['error'] = true;
			$response['messages'][] = 'Agent hibakód: '.$agent_error_code;
			$response['messages'][] = 'Agent hibaüzenet: '.$agent_error;
			$order->add_order_note(sprintf(esc_html__('Szamlazz.hu receipt generation failed! Agent error code: %s', 'wc-szamlazz'), urldecode($agent_error)));

			//Callbacks
			do_action('wc_szamlazz_after_receipt_error', $order, $response);

			return $response;
		} else {

			//Get the Invoice ID from the response header
			$invoice_name = (string)$agent_body_xml->nyugta->alap->nyugtaszam;

			//Download & Store PDF - generate a random file name so it will be downloadable later only by you
			$pdf_content = base64_decode($agent_body_xml->nyugtaPdf);
			$invoice_pdf = $this->xml_generator->save_pdf_file('receipt', $orderId, $pdf_content, $invoice_name);

			//Do we sent an email?
			$auto_email_sent = ($this->get_option('receipt_email') == 'yes');

			//Create response
			$response['name'] = $invoice_name;

			//Send email if needed
			if($auto_email_sent) {
				$email_info = $this->send_receipt($orderId, $order, $invoice_name);
			}

			//Response message
			$response['messages'][] = ($auto_email_sent) ? esc_html__('Receipt successfully generated and sent to the customer via email.','wc-szamlazz') : esc_html__('Receipt successfully generated.','wc-szamlazz');

			//Update order notes
			$order->add_order_note(sprintf(esc_html__('Számlázz.hu receipt successfully generated. Receipt number: %s', 'wc-szamlazz'), $invoice_name));

			//Store the filename
			$order->update_meta_data( '_wc_szamlazz_receipt', $invoice_name );
			$order->update_meta_data( '_wc_szamlazz_receipt_pdf', $invoice_pdf );

			//Return download links
			$response['link'] = $this->generate_download_link($order, 'receipt');

			//Save order
			$order->save();

			//Run action on successful receipt creation
			do_action('wc_szamlazz_after_receipt_success', $order, $response);

			//Action for webhooks
			do_action( 'wc_szamlazz_document_created', array('order_id' => $order->get_id(), 'document_type' => 'receipt') );

			return $response;
		}

	}

	//Generate XML for Szamla Agent Sztornó
	public function generate_void_receipt($orderId) {
		$order = wc_get_order($orderId);
		$order_items = $order->get_items();

		//Build Xml
		$szamla = new WCSzamlazzSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlnyugtast xmlns="http://www.szamlazz.hu/xmlnyugtast" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlnyugtast http://www.szamlazz.hu/docs/xsds/xmlnyugtast/xmlnyugtast.xsd"></xmlnyugtast>');

		//Response
		$response = array();
		$response['error'] = false;

		//Account & Invoice settings
		$szamla->appendXML($this->get_authentication_xml_object($order));
		$szamla->beallitasok->addChild('pdfLetoltes', 'true');

		//Invoice details
		$fejlec = $szamla->addChild('fejlec');
		$fejlec->addChild('nyugtaszam', str_replace(array('.', ' ', "\n", "\t", "\r"), '', $order->get_meta('_wc_szamlazz_receipt')));

		//Generate XML
		$xml_szamla = apply_filters('wc_szamlazz_xml_void_receipt', $szamla, $order);
		$xml = $xml_szamla->asXML();
		$xml_response = $this->xml_generator->generate($xml, $orderId, 'action-szamla_agent_nyugta_storno');
		$agent_body_xml = new SimpleXMLElement($xml_response['agent_body']);

		// ezt majd true-ra állítjuk ha volt hiba
		$volt_hiba = false;

		// ebben lesznek a hiba információk, plusz a bodyban
		$agent_error = '';
		$agent_error_code = '';

		// Nézzük meg volt e hiba
		if(!filter_var($agent_body_xml->sikeres, FILTER_VALIDATE_BOOLEAN)) {
			$volt_hiba = true;
			$agent_error = $agent_body_xml->hibauzenet;
			$agent_error_code = $agent_body_xml->hibakod;
		}

		if ($volt_hiba || $xml_response['error']) {

			//Create response
			$response['error'] = true;
			$response['messages'][] = 'Agent hibakód: '.$agent_error_code;
			$response['messages'][] = 'Agent hibaüzenet: '.$agent_error;
			$order->add_order_note(sprintf(esc_html__('Szamlazz.hu reverse receipt generation failed! Agent error code: %s', 'wc-szamlazz'), urldecode($agent_error)));

			do_action('wc_szamlazz_after_receipt_void_error', $order, $response);

			return $response;
		} else {

			//Get the Invoice ID from the response header
			$invoice_name = (string)$agent_body_xml->nyugta->alap->nyugtaszam;

			//Download & Store PDF - generate a random file name so it will be downloadable later only by you
			$pdf_content = base64_decode($agent_body_xml->nyugtaPdf);
			$invoice_pdf = $this->xml_generator->save_pdf_file('receipt_void', $orderId, $pdf_content, $invoice_name);

			//Create response
			$response['name'] = $invoice_name;

			//Response message
			$response['messages'][] = esc_html__('Reverse receipt successfully generated.','wc-szamlazz');

			//Get existing pdf receipt link
			$receipt_pdf_url = $this->generate_download_link($order, 'receipt');
			$receipt_number = $order->get_meta('_wc_szamlazz_receipt');
			$order->add_order_note(sprintf(esc_html__('Számlázz.hu reverse receipt successfully generated. Receipt number: %s. Original invoice: %s', 'wc-szamlazz'), $invoice_name, '<a target="_blank" href="'.$receipt_pdf_url.'">'.$receipt_number.'</a>'));

			//Store the filename
			$order->update_meta_data( '_wc_szamlazz_void_receipt', $invoice_name );
			$order->update_meta_data( '_wc_szamlazz_void_receipt_pdf', $invoice_pdf );

			//Return download links
			$response['link'] = $this->generate_download_link($order, 'void_receipt');

			//Remove existing szamla
			$order->delete_meta_data( '_wc_szamlazz_receipt' );
			$order->delete_meta_data( '_wc_szamlazz_receipt_pdf' );

			//Save order
			$order->save();

			do_action('wc_szamlazz_after_receipt_void_success', $order, $response);

			//Action for webhooks
			do_action( 'wc_szamlazz_document_created', array('order_id' => $order->get_id(), 'document_type' => 'void_receipt') );

			return $response;
		}

	}

	//Generate XML for Szamla Agent
	public function send_receipt($orderId, $order, $receiptID) {
		//Build Xml
		$szamla = new WCSzamlazzSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlnyugtasend xmlns="http://www.szamlazz.hu/xmlnyugtasend" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlnyugtasend http://www.szamlazz.hu/docs/xsds/nyugtasend/xmlnyugtasend.xsd"></xmlnyugtasend>');

		//Account & Invoice settings
		$szamla->appendXML($this->get_authentication_xml_object($order));

		//Invoice details
		$fejlec = $szamla->addChild('fejlec');
		$fejlec->addChild('nyugtaszam', $receiptID);

		//Email details
		$email = $szamla->addChild('emailKuldes');
		$email->addChild('email', $order->get_billing_email());

		if($this->get_option('receipt_email_replyto')) {
			$email->addChild('emailReplyto', $this->get_option('receipt_email_replyto'));
		}

		if($this->get_option('receipt_email_subject')) {
			$email->addChild('emailTargy', $this->get_option('receipt_email_subject'));
		}

		if($this->get_option('receipt_email_text')) {
			$email->addChild('emailSzoveg', $this->get_option('receipt_email_text'));
		}

		//Generate XML
		$xml_szamla = apply_filters('wc_szamlazz_xml_receipt_send', $szamla, $order);
		$xml = $xml_szamla->asXML();
		$xml_response = $this->xml_generator->generate($xml, $orderId, 'action-szamla_agent_nyugta_send');
		$agent_body_xml = new SimpleXMLElement($xml_response['agent_body']);

		// ezt majd true-ra állítjuk ha volt hiba
		$volt_hiba = false;

		// ebben lesznek a hiba információk, plusz a bodyban
		$agent_error = '';
		$agent_error_code = '';

		// Nézzük meg volt e hiba
		if(!filter_var($agent_body_xml->sikeres, FILTER_VALIDATE_BOOLEAN)) {
			$volt_hiba = true;
			$agent_error = $agent_body_xml->hibauzenet;
			$agent_error_code = $agent_body_xml->hibakod;
		}

		if ($volt_hiba) {
			$xml_response['error'] = true;

			// ha a számla nem készült el kiírjuk amit lehet
			$xml_response['messages'][] = sprintf( __( 'Agent error code: %s','wc-szamlazz' ), $agent_error_code );
			$xml_response['messages'][] = sprintf( __( 'Agent error message: %s','wc-szamlazz' ), $agent_error );

			//Log error messages
			$this->log_error_messages($xml_response, $field.'-'.$orderId);

			do_action('wc_szamlazz_after_receipt_send_error', $order, $xml_response);

			return $xml_response;

		} else {

			do_action('wc_szamlazz_after_receipt_send_success', $order, $xml_response);

			return $xml_response;

		}

	}

	//Autogenerate invoice
	public function on_order_complete( $order_id ) {

		//Only generate invoice, if it wasn't already generated & only if automatic invoice is enabled

		//What are we creating?
		$order = wc_get_order($order_id);
		$document_type = ($order->get_meta('_wc_szamlazz_type_receipt')) ? 'receipt' : 'invoice';
		$is_already_generated = $this->is_invoice_generated($order_id, $document_type);
		$return_info = false;
		$deferred = ($this->get_option('defer', 'no') == 'yes');
		$need_delivery_note = ($this->get_option('delivery_note', 'no') == 'yes');
		$need_delivery_note = apply_filters('wc_szamlazz_need_delivery_note', $need_delivery_note, $order);
		$order_total = $order->get_total();

		if($document_type == 'receipt' && !$is_already_generated) {
			$return_info = $this->generate_receipt($order_id);
		}

		//Don't create deferred if we are in an admin page and only mark one order completed
		if(is_admin() && isset( $_GET['action']) && $_GET['action'] == 'woocommerce_mark_order_status') {
			$deferred = false;
		}

		//Don't defer if we are just changing one or two order status using bulk actions
		if(is_admin() && isset($_GET['_wp_http_referer']) && isset($_GET['post']) && count($_GET['post']) < 3) {
			$deferred = false;
		}

		//Don't create for free orders
		if($order_total == 0 && ($this->get_option('disable_free_order', 'yes') == 'yes')) {
			$is_already_generated = true;
		}

		//Check payment method settings
		$should_generate_auto_invoice = true;
		$payment_method = $order->get_payment_method();
		if($this->check_payment_method_options($order->get_payment_method(), 'auto_disabled')) {
			$should_generate_auto_invoice = false;
		}

		//Check for product option
		$order_items = $order->get_items();
		foreach( $order_items as $order_item ) {
			if($order_item->get_product() && $order_item->get_product()->get_meta('wc_szamlazz_disable_auto_invoice') && $order_item->get_product()->get_meta('wc_szamlazz_disable_auto_invoice') == 'yes') {
				$should_generate_auto_invoice = false;
			}
		}

		//Allow customization with filters
		$should_generate_auto_invoice = apply_filters('wc_szamlazz_should_generate_auto_invoice', $should_generate_auto_invoice, $order_id);

		if($document_type == 'invoice' && !$is_already_generated && $should_generate_auto_invoice) {

			//Check if we generate this invoice deferred
			if($deferred) {
				WC()->queue()->add( 'wc_szamlazz_generate_document_async', array( 'invoice_type' => 'invoice', 'order_id' => $order_id ), 'wc-szamlazz' );
				if($need_delivery_note) {
					WC()->queue()->add( 'wc_szamlazz_generate_document_async', array( 'invoice_type' => 'delivery', 'order_id' => $order_id ), 'wc-szamlazz' );
				}
			} else {
				if($need_delivery_note) {
					$return_info = $this->generate_invoice($order_id, 'delivery');
				}
				$return_info = $this->generate_invoice($order_id);
			}

		}

		if($return_info && $return_info['error']) {
			$this->on_auto_invoice_error($order_id);
		}

	}

	//Autogenerate proform or deposit invoice
	public function on_order_processing( $order_id ) {

		//Only generate invoice, if it wasn't already generated & only if automatic invoice is enabled
		$order = wc_get_order($order_id);
		$payment_method = $order->get_payment_method();
		$is_receipt = ($order->get_meta('_wc_szamlazz_type_receipt'));

		if(!$this->is_invoice_generated($order_id) && !$is_receipt) {
			$invoice_types = array('proform', 'deposit');
			foreach ($invoice_types as $invoice_type) {

				if($this->check_payment_method_options($payment_method, $invoice_type) && !$this->is_invoice_generated($order_id, $invoice_type)) {
					if($this->get_option('defer') == 'yes') {
						WC()->queue()->add( 'wc_szamlazz_generate_document_async', array( 'invoice_type' => $invoice_type, 'order_id' => $order_id ), 'wc-szamlazz' );
					} else {
						$return_info = $this->generate_invoice($order_id, $invoice_type);
					}

				}
			}
		}
	}

	//Autogenerate invoice
	public function on_order_deleted( $order_id ) {

		//Only generate sztornó, if regular invoice already generated & only if automatic invoice is enabled
		if($this->is_invoice_generated($order_id) || $this->is_invoice_generated($order_id, 'receipt') || $this->is_invoice_generated($order_id, 'proform')) {
			$return_info = false;

			//Check if we need to generate an invoice or a receipt
			$order = wc_get_order($order_id);
			if($order->get_meta('_wc_szamlazz_type_receipt')) {
				$return_info = $this->generate_void_receipt($order_id);
			} else {

				$deferred = ($this->get_option('defer', 'no') == 'yes');

				//Don't create deferred if we are in an admin page and only mark one order completed
				if(is_admin() && isset( $_GET['action']) && $_GET['action'] == 'woocommerce_mark_order_status') {
					$deferred = false;
				}

				//Don't defer if we are just changing one or two order status using bulk actions
				if(is_admin() && isset($_GET['_wp_http_referer']) && isset($_GET['post']) && count($_GET['post']) < 3) {
					$deferred = false;
				}

				if($deferred) {
					WC()->queue()->add( 'wc_szamlazz_generate_document_async', array( 'invoice_type' => 'void', 'order_id' => $order_id ), 'wc-szamlazz' );
				} else {
					$return_info = $this->generate_void_invoice($order_id);
				}
			}
		}

	}

	//Helper function to calculate prices
	public function calculate_item_prices($args) {
		$defaults = array(
			'net' => 0,
			'tax' => 0,
			'vat_rate' => 0,
			'qty' => 1,
			'rounding' => 0,
			'tetel' => false,
			'negative' => false,
			'document' => 'invoice',
			'order_item' => false
		);

		$args = wp_parse_args( $args, $defaults );

		//So plugins can overwrite if needed
		$args = apply_filters('wc_szamlazz_calculate_item_prices_args', $args);

		//Fix for coupon items with a fixed tax rate set in settings
		if($args['negative'] && !$args['tax']) {
			if(in_array($args['vat_rate'], array('0', '5', '7', '18', '19', '20', '25', '27'))) {
				$args['vat_rate'] = (float)$args['vat_rate'];
			}

			$discount_vat_rate = (is_float($args['vat_rate'])) ? $args['vat_rate'] : 0;
			$orig_net = $args['net'];
			$args['net'] = 100*$args['net']/(100+$discount_vat_rate);
			$args['tax'] = $orig_net-$args['net'];
		}

		if(round($args['net'],2) == 0) {
			$gross_total = 0;
			$vat_rate = $args['vat_rate'];
			$vat_percentage = 0;
			$vat_amount = 0;
			$net_total = 0;
			$net_unit_price = 0;
		} else {
			$vat_rate = $args['vat_rate'];
			$gross_total = round($args['net'] + $args['tax'], $args['rounding']);
			$vat_percentage = (is_float($args['vat_rate'])) ? $args['vat_rate'] : 0;
			$vat_amount = $gross_total/(100+$vat_percentage) * $vat_percentage;
			$net_total = $gross_total-$vat_amount;
			$net_unit_price = $net_total/$args['qty'];
		}

		//Convert to negative values for coupons for example
		$multiply = 1;
		if($args['negative']) $multiply = -1;
		$tetel = $args['tetel'];

		if($args['document'] == 'receipt') {

			//On receipts, the max decimal places are 2
			$vat_amount = round($vat_amount, 2);
			$net_total = $gross_total-$vat_amount;
			$net_unit_price = $net_total/$args['qty'];

			$tetel->addChild('nettoEgysegar', $net_unit_price*$multiply);
			$tetel->addChild('netto', $net_total*$multiply);
			$tetel->addChild('afakulcs', $vat_rate);
			$tetel->addChild('afa', $vat_amount*$multiply);
			$tetel->addChild('brutto', $gross_total*$multiply);
		} else {
			$net_unit_price_rounding = apply_filters('wc_szamlazz_net_unit_price_rounding_precision', 2, $args);
			$tetel->addChild('nettoEgysegar', round($net_unit_price*$multiply, $net_unit_price_rounding));
			$tetel->addChild('afakulcs', $vat_rate);
			$tetel->addChild('nettoErtek', $net_total*$multiply);
			$tetel->addChild('afaErtek', $vat_amount*$multiply);
			$tetel->addChild('bruttoErtek', $gross_total*$multiply);
		}

		return $tetel;
	}

	//Send email on error
	public function on_auto_invoice_error( $order_id ) {

		//Create an error note
		if(self::$panel_inbox) {
			self::$panel_inbox->create_error_note($order_id);
		}

		//Check if we need to send an email todo
		if($this->get_option('error_email')) {
			$order = wc_get_order($order_id);
			$mailer = WC()->mailer();
			$content = wc_get_template_html( 'includes/emails/invoice-error.php', array(
				'order' => $order,
				'email_heading' => __('Failed invoice generation', 'wc-szamlazz'),
				'plain_text' => false,
				'email' => $mailer,
				'sent_to_admin' => true,
			), '', plugin_dir_path( __FILE__ ) );
			$recipient = $this->get_option('error_email');
			$subject = __("Failed invoice generation", 'wc-szamlazz');
			$headers = "Content-Type: text/html\r\n";
			$mailer->send( $recipient, $subject, $content, $headers );
		}

	}

	//Check if it was already generated or not
	public function is_invoice_generated( $order_id, $type = 'invoice' ) {
		$order = wc_get_order($order_id);
		$own_invoice = false;
		if(($type == 'invoice' && $order->get_meta('_wc_szamlazz_own')) || ($type == 'receipt' && $order->get_meta('_wc_szamlazz_own'))) {
			return true;
		}
		return ($order->get_meta('_wc_szamlazz_'.$type) || $own_invoice);
	}

	//Check if it was already marked as paid or not
	public function is_invoice_paid( $order_id ) {
		$order = wc_get_order($order_id);
		return ($order->get_meta('_wc_szamlazz_completed'));
	}

	//Column on orders page
	public function add_listing_column($columns) {
		$new_columns = array();
		foreach ($columns as $column_name => $column_info ) {
			$new_columns[ $column_name ] = $column_info;
			if ( 'order_total' === $column_name ) {
				$new_columns['wc_szamlazz'] = __( 'Számlázz.hu', 'wc-szamlazz' );
			}
		}
		return $new_columns;
	}

	//Add icon to order list to show invoice
	public function add_listing_actions( $column, $post_or_order_object ) {
		$order = ( $post_or_order_object instanceof \WP_Post ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;
		if ( ! is_object( $order ) && is_numeric( $order ) ) {
			$order = wc_get_order( absint( $order ) );
		}

		if ( $order && 'order_total' === $column && WC_Szamlazz_Pro::is_pro_enabled()) {
			echo '<span class="wc-szamlazz-mark-paid-item">';

			//Replicate the original price content
			if ( $order->get_payment_method_title() ) {
				echo '<span class="tips" data-tip="' . esc_attr( sprintf( __( 'via %s', 'wc-szamlazz' ), $order->get_payment_method_title() ) ) . '">' . wp_kses_post( $order->get_formatted_order_total() ) . '</span>';
			} else {
				echo wp_kses_post( $order->get_formatted_order_total() );
			}

			if($this->is_invoice_generated($order->get_id(), 'invoice')) {

				if($order->get_meta('_wc_szamlazz_completed')) {
					$paid_date = $order->get_meta('_wc_szamlazz_completed');
					if (strpos($paid_date, '-') == false) {
						$paid_date = date('Y-m-d', $paid_date);
					}

					echo '<span class="wc-szamlazz-mark-paid-button paid tips" data-tip="'.sprintf(__('Paid on: %s', 'wc-szamlazz'), $paid_date).'"></span>';
				} else {
					if(!$order->get_meta('_wc_szamlazz_own')) {
						echo '<a href="#" data-nonce="'.wp_create_nonce( 'wc_szamlazz_generate_invoice' ).'" data-order="'.$order->get_id().'" class="wc-szamlazz-mark-paid-button tips" data-tip="'.__('Mark as paid', 'wc-szamlazz').'"></a>';
					}
				}

			} else {
				$tip = __("There's no invoice for this order yet", "wc-szamlazz");
				echo '<span class="wc-szamlazz-mark-paid-button pending tips" data-tip="'.$tip.'"></span>';
			}

			echo '</span>';
		}

		if ( $order && 'wc_szamlazz' === $column ) {
			$invoice_types = WC_Szamlazz_Helpers::get_document_types();

			foreach ($invoice_types as $invoice_type => $invoice_label) {
				if($this->is_invoice_generated($order->get_id(), $invoice_type) && !$order->get_meta('_wc_szamlazz_own')):
				?>
					<a href="<?php echo $this->generate_download_link($order, $invoice_type); ?>" class="button tips wc-szamlazz-button" target="_blank" data-tip="<?php echo $invoice_label; ?>">
						<img src="<?php echo WC_Szamlazz::$plugin_url . 'assets/images/icon-'.$invoice_type.'.svg'; ?>" alt="" width="16" height="16">
					</a>
				<?php
				endif;
			}
		}
	}

	//Add to tools column
	public function add_listing_actions_2($order) {
		$this->add_listing_actions('wc_szamlazz', $order);
	}

	//Generate download url
	public function generate_download_link( $order, $type = 'invoice', $absolute = false) {
		if($order) {
			$pdf_name = '';
			$pdf_name = $order->get_meta('_wc_szamlazz_'.$type.'_pdf');

			if($pdf_name) {
				$paths = $this->get_pdf_file_path('invoice', 0);
				if($absolute) {
					$pdf_file_url = $paths['basedir'].$pdf_name;
				} else {
					$pdf_file_url = $paths['baseurl'].$pdf_name;
				}
				return apply_filters('wc_szamlazz_download_link', $pdf_file_url, $order);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//Add download icons to order details page
	public function orders_download_button($actions, $order) {
		$order_id = $order->get_id();
		if($this->get_option('customer_download','no') == 'yes') {
			$document_types = WC_Szamlazz_Helpers::get_document_types();

			foreach ($document_types as $document_type => $document_label) {
				if($this->is_invoice_generated($order_id, $document_type)) {
					$link = $this->generate_download_link($order, $document_type);
					$actions['wc_szamlazz_pdf'] = array(
						'url' => $link,
						'name' => $document_label
					);
				}
			}
		}
		return $actions;
	}

	//Get options stored
	public function get_option($key, $default = '') {
		$settings = get_option( 'woocommerce_wc_szamlazz_settings', null );
		$value = $default;

		if($settings && isset($settings[$key]) && !empty($settings[$key])) {
			$value = $settings[$key];
		} else if(get_option($key)) {
			$value = get_option($key);
		}

		//Try to get password from wp-config
		if($key == 'agent_key' && defined( 'WC_SZAMLAZZ_AGENT_KULCS' )) {
			$value = WC_SZAMLAZZ_AGENT_KULCS;
		}

		return apply_filters('wc_szamlazz_get_option', $value, $key);
	}

	//Plugin links
	public function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . esc_url(admin_url( 'admin.php?page=wc-settings&tab=integration&section=wc_szamlazz' )) . '" aria-label="' . esc_attr__( 'Számlázz.hu Settings', 'wc-szamlazz' ) . '">' . esc_html__( 'Settings', 'wc-szamlazz' ) . '</a>',
		);
		return array_merge( $action_links, $links );
	}

	public static function plugin_row_meta( $links, $file ) {
		$basename = plugin_basename( WC_SZAMLAZZ_PLUGIN_FILE );
		if ( $basename !== $file ) {
			return $links;
		}

		$row_meta = array(
			'documentation' => '<a href="https://visztpeter.me/dokumentacio/" target="_blank" aria-label="' . esc_attr__( 'Számlázz.hu Documentation', 'wc-szamlazz' ) . '">' . esc_html__( 'Documentation', 'wc-szamlazz' ) . '</a>'
		);

		if (!WC_Szamlazz_Pro::is_pro_enabled() ) {
			$row_meta['get-pro'] = '<a target="_blank" rel="noopener noreferrer" style="color:#46b450;" href="https://visztpeter.me/woocommerce-szamlazz-hu/" aria-label="' . esc_attr__( 'Számlázz.hu Pro version', 'wc-szamlazz' ) . '">' . esc_html__( 'Pro version', 'wc-szamlazz' ) . '</a>';
		}

		return array_merge( $links, $row_meta );
	}

	public function check_payment_method_options($payment_method_id, $option) {
		$found = false;
		$payment_method_options = $this->get_option('wc_szamlazz_payment_method_options_v2');
		if(isset($payment_method_options[$payment_method_id]) && isset($payment_method_options[$payment_method_id][$option])) {
			$found = $payment_method_options[$payment_method_id][$option];
		}
		return $found;
	}

	public function get_payment_method_deadline($payment_method_id) {
		$deadline = $this->get_option('payment_deadline');
		$custom_deadline = $this->check_payment_method_options($payment_method_id, 'deadline');
		if($custom_deadline != '' && $custom_deadline !== false) {
			$deadline = $custom_deadline;
		}
		return $deadline;
	}

	public function get_accounting_details($order, $category, $order_item) {
		$function_enabled = $this->get_option('accounting_details_enabled');
		$saved_values = get_option('wc_szamlazz_accounting_details');
		if(!$saved_values || !$function_enabled) return false;
		if(is_array($category) && count($category) < 1) return false;

		if(is_array($category)) {
			$category_id = esc_attr( $category[0]->term_id );
		} else {
			$category_id = esc_attr( $category );
		}

		$detail_items = array('afa_fokonyvi_szam', 'fokonyvi_szam', 'gazd_esem', 'afa_gazd_esem');
		$is_hungarian = WC_Szamlazz_Helpers::is_order_hungarian($order);
		$suffix = ($is_hungarian) ? '_hu' : '_kulfold';
		$defaults = array();
		$data = array();

		foreach ($detail_items as $detail_item) {
			$defaults[$detail_item] = esc_attr( $saved_values['default'][$detail_item.$suffix]);
			if(isset($category_id) && $category_id && isset($saved_values[esc_attr( $category_id )]) && $saved_values[esc_attr( $category_id )][$detail_item.$suffix] && $saved_values[esc_attr( $category_id )][$detail_item.$suffix] != '') {
				$data[$detail_item] = $saved_values[esc_attr( $category_id )][$detail_item.$suffix];
			}
		}

		$data = wp_parse_args( $data, $defaults );
		return apply_filters('wc_szamlazz_get_accounting_details', $data, $order, $order_item);
	}

	public function get_authentication_xml_object($order, $fixed_agent_key = false) {

		//Check if its manually created, if so, $_POST might have a key set
		if(isset( $_POST['action']) && $_POST['action'] == 'wc_szamlazz_generate_invoice' && isset($_POST['account'])) {
			$agent_key = sanitize_text_field($_POST['account']);
		} else {
			$agent_key = $this->get_szamlazz_agent_key($order);
		}

		//If a fixed key is set
		if($fixed_agent_key) $agent_key = $fixed_agent_key;

		$beallitasok = new WCSzamlazzSimpleXMLElement('<beallitasok></beallitasok>');
		$beallitasok->addChild('szamlaagentkulcs', $agent_key);
		return apply_filters('wc_szamlazz_authentication_xml_object', $beallitasok, $order);
	}

	public function get_order_item_tax_label($order, $item, $vevo = false) {
		$tax_item_label = '';

		//If a fixed value is set in settings
		if($this->get_option('afakulcs') != '') {
			$afakulcs = $this->get_option('afakulcs');
			if(in_array($afakulcs, array('0', '5', '7', '18', '19', '20', '25', '27'))) {
				if($afakulcs == '7') $afakulcs = '27';
				$afakulcs = (float)$afakulcs;
			}
			return $afakulcs;
		}

		if(wc_tax_enabled()) {
			$tax_items_labels = array();
			$valid_tax_labels = WC_Szamlazz_Helpers::get_vat_types(true);
			$tax_items_percentages = array();
			$tax_item_percentage = false;

			//Get all tax labels indexed by rate id
			foreach ( $order->get_items('tax') as $tax_item ) {
				$tax_items_labels[$tax_item->get_rate_id()] = $tax_item->get_label();
				$tax_items_percentages[$tax_item->get_rate_id()] = $tax_item->get_rate_percent();
			}

			//Get line item tax id and find label
			if(count($tax_items_labels) > 0) {
				$taxes = $item->get_taxes();
				foreach( $taxes['subtotal'] as $rate_id => $tax ){
					if($tax != '') {
						$tax_item_label = $tax_items_labels[$rate_id];
						if($tax > 0) {
							$tax_item_percentage = $tax_items_percentages[$rate_id];
						}
					}
				}
			}

			//If its not a valid label
			if(!in_array($tax_item_label, $valid_tax_labels)) {
				$tax_item_label = '';
			}

			//If its a percentage value higher than 0
			if($tax_item_label == '' && $tax_item_percentage && $tax_item_percentage > 0) {
				$tax_item_label = $tax_item_percentage;
			}

			//If its a free item, try to get tax class anyway
			if(
				($this->get_option('separate_coupon', 'no') == 'yes' && round($item->get_subtotal(), 2) == 0) ||
				($this->get_option('separate_coupon', 'no') == 'no'  && round($item->get_total(), 2) == 0)) {

					//Get the product's tax class(by default its standard, empty)
					$tax_class = '';
					if($item->get_product()) {
						$tax_class = $item->get_product()->get_tax_class();
					}

					//Get the WC_Tax class
					$wc_tax = new WC_Tax();

					//Find rates based on the billing country
					$tax_rates = $wc_tax->find_rates(
						array(
							"tax_class" => $tax_class,
							"country" => $order->get_billing_country(),
					));

					//If rates are found, get the first result and check the label or the rate as a valid tax type
					//Only if the order is taxed
					if($tax_rates && $order->get_items('tax')) {
						$tax_rate = reset($tax_rates);
						$tax_item_label = $tax_rate['label'];
						if(!in_array($tax_item_label, $valid_tax_labels)) {
							$tax_item_label = $tax_rate['rate'];
						}
					}
			}
		}

		//If theres no ID, return percentage value
		if($tax_item_label == '') {
			if($this->get_option('separate_coupon', 'no') == 'yes') {
				if(round($item->get_subtotal(), 2) == 0) {
					$tax_item_label = 0;
				} else {
					$tax_item_label = round( ($item->get_subtotal_tax()/$item->get_subtotal()) * 100, 1 );
				}
			} else {
				if(round($item->get_total(), 2) == 0) {
					$tax_item_label = 0;
				} else {
					$tax_item_label = round( ($item->get_total_tax()/$item->get_total()) * 100, 1 );
				}
			}
		}

		//If tax is empty, maybe replace it with EU and EUK
		if($tax_item_label == 0 && $order->get_billing_country() != 'HU') {
			$eu_countries = WC()->countries->get_european_union_countries('eu_vat');
			if(in_array($order->get_billing_country(), $eu_countries) && $vevo && $vevo->adoszamEU != '') {
				if($this->get_option('afakulcs_eu', 'no') == 'yes') {
					$tax_item_label = 'EUT';
				}
			} else {
				if($this->get_option('afakulcs_euk', 'no') == 'yes') {
					$tax_item_label = 'EUKT';
				}
			}
		}

		return $tax_item_label;
	}

	public function get_order_shipping_tax_label($order, $shipping_item_obj, $vevo = false) {
		$tax_item_label = '';
		$valid_tax_labels = WC_Szamlazz_Helpers::get_vat_types(true);
		$total = $shipping_item_obj->get_total();
		if($shipping_item_obj->get_type() == 'shop_order_refund') {
			$total = $shipping_item_obj->get_total()-$shipping_item_obj->get_total_tax();
		}

		//If a fixed value is set in settings
		if($this->get_option('afakulcs') != '') {
			$afakulcs = $this->get_option('afakulcs');
			if(in_array($afakulcs, array('0', '5', '7', '18', '19', '20', '25', '27'))) {
				if($afakulcs == '7') $afakulcs = '27';
				$afakulcs = (float)$afakulcs;
			}
			return $afakulcs;
		}

		if(wc_tax_enabled()) {
			$tax_data = $shipping_item_obj->get_taxes();
			$tax_item_percentage = false;
			foreach ( $order->get_items('tax') as $tax_item ) {
				$tax_item_id = $tax_item->get_rate_id();
				$tax_item_total = isset( $tax_data['total'][ $tax_item_id ] ) ? $tax_data['total'][ $tax_item_id ] : '';

				if($tax_item_total != '') {
					$tax_item_label = $tax_item->get_label();
					if($tax_item->get_rate_percent() > 0) {
						$tax_item_percentage = $tax_item->get_rate_percent();
					}
				}
			}

			//If its not a valid label
			if(!in_array($tax_item_label, $valid_tax_labels)) {
				$tax_item_label = '';
			}

			//User percentage value if found
			if($tax_item_label == '' && $tax_item_percentage && $tax_item_percentage > 0) {
				$tax_item_label = $tax_item_percentage;
			}
		}

		if($tax_item_label == '') {
			$order_shipping = $total;
			$order_shipping_tax = $shipping_item_obj->get_total_tax();
			if($order_shipping != 0) {
				$tax_item_label = round(($order_shipping_tax/$order_shipping)*100);
			} else {
				$tax_item_label = 0;
			}
		}

		//If tax is empty, maybe replace it with EU and EUK
		if($tax_item_label == 0 && $order->get_billing_country() != 'HU') {
			$eu_countries = WC()->countries->get_european_union_countries('eu_vat');
			if(in_array($order->get_billing_country(), $eu_countries) && $vevo && $vevo->adoszamEU != '') {
				if($this->get_option('afakulcs_eu', 'no') == 'yes') {
					$tax_item_label = 'EUT';
				}
			} else {
				if($this->get_option('afakulcs_euk', 'no') == 'yes') {
					$tax_item_label = 'EUKT';
				}
			}
		}

		//If still nothing, try to get the default tax rate for the shipping
		if($total == 0 && ($tax_item_label == '' || $tax_item_label == 0)) {

			//Get shipping tax calss
			$tax_class = '';
			$shipping_tax_class = get_option( 'woocommerce_shipping_tax_class' );
			if ( 'inherit' !== $shipping_tax_class ) {
				$tax_class = $shipping_tax_class;
			}

			//Get the WC_Tax class
			$wc_tax = new WC_Tax();

			//Find rates based on the billing country
			$tax_rates = $wc_tax->find_rates(
				array(
					"tax_class" => $tax_class,
					"country" => $order->get_billing_country(),
			));

			//If rates are found, get the first result and check the label or the rate as a valid tax type
			//Only if the order is taxed
			if($tax_rates && $order->get_items('tax')) {
				$tax_rate = reset($tax_rates);
				$tax_item_label = $tax_rate['label'];
				if(!in_array($tax_item_label, $valid_tax_labels)) {
					$tax_item_label = $tax_rate['rate'];
				}
			}

		}

		return $tax_item_label;
	}

	public function get_order_discout_tax_label($order) {
		$vat_rate = round( ($order->get_discount_tax()/$order->get_total_discount()) * 100 );

		//Use tax override if its empty
		if($vat_rate == 0 && $this->get_option('afakulcs') != '') {
			$vat_rate = $this->get_option('afakulcs');
		}

		return $vat_rate;
	}

	public function get_coupon_invoice_item_details($order) {
		$details = array(
			"title" => esc_html__('Discount', 'wc-szamlazz'),
			"desc" => ''
		);

		$order_discount = method_exists( $order, 'get_discount_total' ) ? $order->get_discount_total() : $order->order_discount;
		if ( $order_discount > 0 ) {
			$coupons = implode(', ', $order->get_coupon_codes());
			$discount = strip_tags(html_entity_decode($order->get_discount_to_display()));
			$details["desc"] = sprintf( __( '%1$s discount with the following coupon code: %2$s', 'wc-szamlazz' ), $discount, $coupons );

			if($this->get_option('separate_coupon_name')) {
				$details["title"] = $this->get_option('separate_coupon_name');
			}

			if($this->get_option('separate_coupon_desc')) {
				$discount_note_replacements = array('{kedvezmeny_merteke}' => $discount, '{kupon}' => $coupons);
				$discount_note = str_replace( array_keys( $discount_note_replacements ), array_values( $discount_note_replacements ), $this->get_option('separate_coupon_desc'));
				$details["desc"] = $discount_note;
			}
		}

		return $details;
	}

	//Log error message if needed
	public function log_error_messages($error, $source) {
		$logger = wc_get_logger();
		$logger->error(
			$source.' - '.json_encode($error),
			array( 'source' => 'wc_szamlazz' )
		);
	}

	//Log debug messages if needed
	public function log_debug_messages($data, $source, $force = false) {
		if($this->get_option('debug', 'no') == 'yes' || $force) {
			$logger = wc_get_logger();
			$logger->debug(
				$source.' - '.json_encode($data),
				array( 'source' => 'wc_szamlazz' )
			);
		}
	}

	//Disable invoice generation for free orders
	function disable_invoice_for_free_order($order_id, $data, $order) {
		$order_total = $order->get_total();
		if($order_total == 0 && ($this->get_option('disable_free_order', 'yes') == 'yes')) {
			$order->update_meta_data( '_wc_szamlazz_own', __('Invoices not required for free orders', 'wc-szamlazz') );
			$order->save();
		}
	}

	//Get file path for pdf files
	public function get_pdf_file_path($type, $order_id, $invoice_name = false) {
		$upload_dir = wp_upload_dir( null, false );
		$basedir = $upload_dir['basedir'] . '/wc_szamlazz/';
		$baseurl = $upload_dir['baseurl'] . '/wc_szamlazz/';
		$random_file_name = substr(md5(rand()),5);
		$pdf_file_name = implode( '-', array( $type, $order_id, $random_file_name ) ).'.pdf';
		$pdf_file_name = apply_filters('wc_szamlazz_pdf_file_name', $pdf_file_name, $type, $order_id, $invoice_name);
		$file_dir = $basedir;

		//Group by year and month if needed
		if (get_option('uploads_use_yearmonth_folders') ) {
			$time = current_time( 'mysql' );
			$y = substr( $time, 0, 4 );
			$m = substr( $time, 5, 2 );
			$subdir = "/$y/$m";
			$pdf_file_name = $y.'/'.$m.'/'.$pdf_file_name;
			$file_dir = $basedir.$y.'/'.$m.'/';
		}

		return array('name' => $pdf_file_name, 'file_dir' => $file_dir, 'path' => $basedir.$pdf_file_name, 'baseurl' => $baseurl, 'basedir' => $basedir);
	}

	//Get order note
	public function get_invoice_note($order, $document_type, $invoice_lang, $szamla) {

		//If we don't have any notes, try to return the old one
		$notes = get_option('wc_szamlazz_notes');
		if(!$notes) return $this->get_option('note');

		//Custom conditions
		$order_details = WC_Szamlazz_Conditions::get_order_details($order, 'notes');
		$order_details['language'] = apply_filters('wc_szamlazz_get_order_language', $invoice_lang, $order);
		$order_details['document'] = $document_type;
		$order_details['account'] = (string)$szamla->beallitasok->szamlaagentkulcs;;

		//We will return a single note at the end
		$final_note = '';

		//Loop through each note
		foreach ($notes as $note_id => $note) {

			//If this is based on a condition
			if($note['conditional']) {

				//Compare conditions with order details and see if we have a match
				$note_is_a_match = WC_Szamlazz_Conditions::match_conditions($notes, $note_id, $order_details);

				//If its not a match, continue to next not
				if(!$note_is_a_match) continue;

				//Check if we need to append or replace the text
				if($note['append']) {
					$final_note .= "\n".$note['comment'];
				} else {
					$final_note = $note['comment'];
				}

			} else {
				$final_note = $note['comment'];
			}

		}

		return $final_note;
	}

	//Get available számlázz.hu accounts
	public function get_szamlazz_accounts() {
		$accounts = array(
			$this->get_option('agent_key') => __('Default', 'wc-szamlazz')
		);

		$extra_accounts_enabled = $this->get_option('multiple_accounts', 'no');
		$extra_accounts = get_option('wc_szamlazz_extra_accounts');
		if($extra_accounts && $extra_accounts_enabled == 'yes') {
			foreach ($extra_accounts as $extra_account) {
				$accounts[$extra_account['key']] = $extra_account['name'];
			}
		}

		return $accounts;
	}

	//Get account thats related to the order
	public function get_szamlazz_agent_key($order) {

		//Default key
		$key = $this->get_option('agent_key', '');

		//Get accounts
		$extra_accounts_enabled = $this->get_option('multiple_accounts', 'no');
		$extra_accounts = get_option('wc_szamlazz_extra_accounts');
		$conditions = array();

		//Return if just a single account is setup
		if($extra_accounts_enabled == 'no' || !$extra_accounts || empty($extra_accounts) || !$order) {
			return $key;
		}

		//Get payment method id
		$conditions[] = $order->get_payment_method();

		//Get shipping method id
		$shipping_method = '';
		$shipping_methods = $order->get_shipping_methods();
		if($shipping_methods) {
			foreach( $shipping_methods as $shipping_method_obj ){
				$conditions[] = $shipping_method_obj->get_method_id().':'.$shipping_method_obj->get_instance_id();
			}
		}

		//Get currency
		$conditions[] = $order->get_currency();

		//Get order type
		$conditions[] = ($order->get_billing_company()) ? 'order-company' : 'order-individual';

		//Get product category ids
		$product_categories = array();
		$order_items = $order->get_items();
		foreach ($order_items as $order_item) {
			if($order_item->get_product()) {
				$product_categories = $product_categories + wp_get_post_terms( $order_item->get_product_id(), 'product_cat', array('fields' => 'ids') );
			}
		}

		//Append to conditions
		foreach ($product_categories as $category_id) {
			$conditions[] = 'product_cat_'.$category_id;
		}

		//Custom conditions
		$conditions = apply_filters('wc_szamlazz_account_conditions_values', $conditions, $order);

		//Find a matching account
		$key = $this->get_option('agent_key', '');
		foreach ($extra_accounts as $extra_account) {
			if($extra_account['condition'] && in_array($extra_account['condition'], $conditions)) {
				$key = $extra_account['key'];
			}
		}

		return $key;
	}

	//Get account by id
	public function get_szamlazz_agent_key_by_id($key_id) {

		//Default key
		$key = $this->get_option('agent_key', '');

		//Get accounts
		$extra_accounts_enabled = $this->get_option('multiple_accounts', 'no');
		$extra_accounts = get_option('wc_szamlazz_extra_accounts');

		//Return if just a single account is setup
		if($extra_accounts_enabled == 'no' || !$extra_accounts || empty($extra_accounts)) {
			return $key;
		}

		foreach ($extra_accounts as $extra_account) {
			if(substr($extra_account['key'], 0, 5) == $key_id) {
				$key = $extra_account['key'];
			}
		}

		return $key;
	}

	public function should_generate_auto_invoice($order) {
		$db_version = get_option('_wc_szamlazz_db_version');
		$should_generate = false;

		if(($db_version != '4.5' && $this->get_option('auto_generate') != 'no')) {
			$should_generate = array('completed');
		} else {
			$auto_invoice_statuses = get_option('wc_szamlazz_auto_invoice_status');
			if($auto_invoice_statuses) {
				if(empty($auto_invoice_statuses)) $auto_invoice_statuses = array();
			} else if($this->get_option('auto_invoice_status', '')) {
				$auto_invoice_statuses = array($this->get_option('auto_invoice_status'));
			}
			$should_generate = $auto_invoice_statuses;
		}

		//Check payment method settings
		$payment_method = $order->get_payment_method();
		if($this->check_payment_method_options($order->get_payment_method(), 'auto_disabled')) {
			$should_generate = false;
		}

		return apply_filters('wc_szamlazz_should_generate_auto_invoice', $should_generate, $order->get_id());
	}

	//Runs when an order is deleted
	public function on_order_post_deleted($order_id, $order) {

		//Check if order has some számlázz.hu documents generated
		$document_types = WC_Szamlazz_Helpers::get_document_types();
		foreach ($document_types as $document_type => $document_label) {

			//Check if document exists and delete it
			if($order->get_meta('_wc_szamlazz_'.$document_type)) {
				$path = $this->generate_download_link($order, $document_type, true);
				if($path) {
					unlink($path);
				}
			}
		}
	}

}

//WC Detection
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ;
	}
}


//WooCommerce inactive notice.
function wc_szamlazz_woocommerce_inactive_notice() {
	if ( current_user_can( 'activate_plugins' ) ) {
		echo '<div id="message" class="error"><p>';
		printf( __( '%1$sWooCommerce Számlázz.hu is inactive%2$s. The %3$s1$sWooCommerce plugin %4$s must be active. %5$sPlease install or activate the latest WooCommerce &raquo;%6$s', 'wc-szamlazz' ), '<strong>', '</strong>', '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">', '</a>' );
		echo '</p></div>';
	}
}

//Initialize
if ( is_woocommerce_active() ) {
	function WC_Szamlazz() {
		return WC_Szamlazz::instance();
	}

	//For backward compatibility
	$GLOBALS['wc_szamlazz'] = WC_Szamlazz();
} else {
	add_action( 'admin_notices', 'wc_szamlazz_woocommerce_inactive_notice' );
}
