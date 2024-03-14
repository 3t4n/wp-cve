<?php
/**
 * Plugin Name: metaps PAYMENT for WooCommerce
 * Version: 1.2.0
 * Author: Artisan Workshop
 * Author URI: https://wc.artws.info/
 *
 * @package wc4jp-paydesign
 * @category View
 * @author Artisan Workshop
 */

use \ArtisanWorkshop\WooCommerce\PluginFramework\v2_0_12 as Framework;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Admin_Screen_PAYDESIGN {

	/**
	 * Error messages.
	 *
	 * @var array
	 */
	public $errors = array();

	/**
	 * Update messages.
	 *
	 * @var array
	 */
	public $messages = array();

	/**
	 * Japanized for WooCommerce Framework.
	 *
	 * @var object
	 */
	public $jp4wc_framework;
	public $prefix;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'wc_admin_paydesign_menu' ) ,55 );
		add_action( 'admin_notices', array( $this, 'paydesign_ssl_check' ) );
		add_action( 'admin_init', array( $this, 'wc_setting_paydesign_init') );

		$this->jp4wc_framework = new Framework\JP4WC_Plugin();
		$this->prefix = 'woocommerce_paydesign_';
		$this->post_prefix = 'wc_paydesign_';
	}
	/**
	 * Admin Menu
	 */
	public function wc_admin_paydesign_menu() {
		$page = add_submenu_page( 'woocommerce', __( 'metaps Payment Setting', 'woo-paydesign' ), __( 'metaps Payment Setting', 'woo-paydesign' ), 'manage_woocommerce', 'wc4jp-paydesign-output', array( $this, 'wc_paydesign_output' ) );
	}

	/**
	 * Admin Screen output
	 */
	public function wc_paydesign_output() {
		$tab = ! empty( $_GET['tab'] ) && $_GET['tab'] == 'info' ? 'info' : 'setting';
		include( 'views/html-admin-screen.php' );
	}

	/**
	 * Admin page for Setting
	 */
	public function admin_paydesign_setting_page() {
		include( 'views/html-admin-setting-screen.php' );
	}

	/**
	 * Admin page for infomation
	 */
	public function admin_paydesign_info_page() {
		include( 'views/html-admin-info-screen.php' );
	}

      /**
       * Check if SSL is enabled and notify the user.
       */
      function paydesign_ssl_check() {
		  if(isset($this->enabled)){
              if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'no' && $this->enabled == 'yes' ) {
              echo '<div class="error"><p>' . sprintf( __('metaps Payment Payment is enabled and the <a href="%s">force SSL option</a> is disabled; your checkout is not secure! Please enable SSL and ensure your server has a valid SSL certificate.', 'woo-paydesign' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) . '</p></div>';
            }
		  }
	  }

	function wc_setting_paydesign_init(){

		register_setting(
			'jp4wc_paydesign_options',
			'jp4wc_paydesign_options_name',
			array( $this, 'validate_options' )
		);

		// metaps PAYMENT Initial Setting
		add_settings_section(
			'jp4wc_paydesign_general',
			__( 'metaps Payment Initial Setting', 'woo-paydesign' ),
			'',
			'jp4wc_paydesign_options'
		);
		add_settings_field(
			'jp4wc_paydesign_prefix_order',
			__( 'Prefix of Order Number and Customer ID', 'woo-paydesign' ),
			array( $this, 'jp4wc_paydesign_prefix_order' ),
			'jp4wc_paydesign_options',
			'jp4wc_paydesign_general'
		);
		add_settings_field(
			'jp4wc_paydesign_notice_url',
			__( 'Payment completion notification', 'woo-paydesign' ),
			array( $this, 'jp4wc_paydesign_notice_url' ),
			'jp4wc_paydesign_options',
			'jp4wc_paydesign_general'
		);

		// Payment Method
		add_settings_section(
			'jp4wc_paydesign_payment',
			__( 'metaps Payment Payment Method', 'woo-paydesign' ),
			'',
			'jp4wc_paydesign_options'
		);
		add_settings_field(
			'jp4wc_paydesign_cc',
			__( 'Credit Card', 'woo-paydesign' ),
			array( $this, 'jp4wc_paydesign_cc' ),
			'jp4wc_paydesign_options',
			'jp4wc_paydesign_payment'
		);
		add_settings_field( 'jp4wc_paydesign_cc_token', __( 'Credit Card with Token', 'woo-paydesign' ), array( $this, 'jp4wc_paydesign_cc_token' ), 'jp4wc_paydesign_options', 'jp4wc_paydesign_payment' );
		add_settings_field( 'jp4wc_paydesign_cs', __( 'Convenience store', 'woo-paydesign' ), array( $this, 'jp4wc_paydesign_cs' ), 'jp4wc_paydesign_options', 'jp4wc_paydesign_payment' );
		add_settings_field( 'jp4wc_paydesign_pe', __( 'Pay-Easy Payment', 'woo-paydesign' ), array( $this, 'jp4wc_paydesign_pe' ), 'jp4wc_paydesign_options', 'jp4wc_paydesign_payment' );

		if( isset( $_POST['wc-paydesign-setting'] ) && $_POST['wc-paydesign-setting'] ){
			if( check_admin_referer( 'my-nonce-key', 'wc-paydesign-setting')){
				//Prefix of Order Number Setting
				if(isset($_POST['prefix_order']) && $_POST['prefix_order']){
					update_option( 'wc_paydesign_prefix_order', $_POST['prefix_order']);
				}
				//All payment method setting
				$paydesign_methods = array('cc','cc_token','cs','pe');
				foreach($paydesign_methods as $method){
					$paydesign_post_str = $this->post_prefix.$method;
					$paydesign_method_str = $this->prefix.$method;
					$paydesign_method_setting_str = $paydesign_method_str.'_settings';
					$jp4wc_paydesign = get_option( $paydesign_method_setting_str );

					if(isset($_POST[$method]) && $_POST[$method]){
						update_option( $paydesign_post_str, $_POST[$method]);
						if(isset($jp4wc_paydesign)){
							$jp4wc_paydesign['enabled'] = 'yes';
							update_option( $paydesign_method_setting_str, $jp4wc_paydesign);
						}
					}else{
						update_option( $paydesign_post_str , '');
						if(isset($jp4wc_paydesign)){
							$jp4wc_paydesign['enabled'] = 'no';
							update_option( $paydesign_method_setting_str , $jp4wc_paydesign);
						}
					}
				}
			}
		}
	}
	/**
	 * Prefix of Order Number and Customer ID.
	 * 
	 * @return mixed
	 */
	public function jp4wc_paydesign_prefix_order(){
		$title = __( 'Prefix of Order Number and Customer ID', 'woo-paydesign' );
		$description = __( 'Please input Word for prefix of Order Number and Customer ID. Alphabet only.', 'woo-paydesign' );
		$this->jp4wc_framework->jp4wc_input_text('prefix_order', $description, 10, 'wc', $this->post_prefix);
	}
	/**
	 * Payment completion notification.
	 * 
	 * @return mixed
	 */
	public function jp4wc_paydesign_notice_url(){
		$notice_url = WP_PLUGIN_URL.'/woo-paydesign/notice-url.php';
		echo __( 'Please use following url for URL for payment completion notification.', 'woo-paydesign' ).'<br /><strong>'.$notice_url.'</strong>';
	}
	/**
	 * Credit Card Payment option.
	 * 
	 * @return mixed
	 */
	public function jp4wc_paydesign_cc() {
		$title = __( 'Credit Card', 'woo-paydesign' );
		$description = $this->jp4wc_framework->jp4wc_description_payment_pattern( $title );
		$this->jp4wc_framework->jp4wc_input_checkbox('cc', $description, $this->post_prefix, $this->prefix);
	}
	/**
	 * Credit Card Payment option.
	 * 
	 * @return mixed
	 */
	public function jp4wc_paydesign_cc_token() {
		$title = __( 'Credit Card with Token', 'woo-paydesign' );
		$descritpion = $this->jp4wc_framework->jp4wc_description_payment_pattern( $title );
		$this->jp4wc_framework->jp4wc_input_checkbox('cc_token', $descritpion, $this->post_prefix, $this->prefix);
	}
	/**
	 * Credit Card Payment option.
	 * 
	 * @return mixed
	 */
	public function jp4wc_paydesign_cs() {
		$title = __( 'Convenience store', 'woo-paydesign' );
		$descritpion = $this->jp4wc_framework->jp4wc_description_payment_pattern( $title );
		$this->jp4wc_framework->jp4wc_input_checkbox('cs', $descritpion, $this->post_prefix, $this->prefix);
	}
	/**
	 * Credit Card Payment option.
	 * 
	 * @return mixed
	 */
	public function jp4wc_paydesign_pe() {
		$title = __( 'Pay-Easy Payment', 'woo-paydesign' );
		$descritpion = $this->jp4wc_framework->jp4wc_description_payment_pattern( $title );
		$this->jp4wc_framework->jp4wc_input_checkbox('pe', $descritpion, $this->post_prefix, $this->prefix);
	}
	/**
	 * Enqueue admin scripts and styles.
	 * 
	 * @global $pagenow
	 */
	public function admin_enqueue_scripts( $page ) {
		global $pagenow;
		if ( $page === 'woocommerce_page_wc4jp-paydesign-output' ) {
			wp_register_style( 'custom_jp4wc_admin_css', plugins_url( '/views/css/admin-jp4wc.css', __FILE__), false, '1.0.99' );
			wp_enqueue_style( 'custom_jp4wc_admin_css' );
			wp_enqueue_script( 'wc4jp-admin-script', plugins_url( 'views/js/admin-settings.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-button', 'jquery-ui-slider' ), WC4JP_METAPS_VERSION );
			wp_enqueue_script( 'postbox' );
		}
	}
}

new WC_Admin_Screen_PAYDESIGN();