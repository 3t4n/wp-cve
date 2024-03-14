<?php
/*
Plugin Name: SKT Donation - Charity and Fundraising Plugin
Description: SKT Donation plugin for accepting donations for NGO, non profit, charity, charitable organizations, crowdfunding, fundraisers via payment gateways PayPal and 2Checkout across the world.
Version: 1.9 
Author: SKT Themes
Author URI: https://sktthemes.org/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: skt-donation
*/ 
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
// Set Constants
define( 'SKT_DONATIONS_DIR', dirname( __FILE__ ) );
define( 'SKT_DONATIONS_URI', plugins_url( '', __FILE__ ) );
/*
* Install plugin
*/
function skt_donation_install()
{
    // clear the permalinks after the post type has been registered
    flush_rewrite_rules();
    //Form General Options
	if ( get_option('skt_donation_paypal_mode_zero_one') == '') {
		update_option('skt_donation_paypal_mode_zero_one', 'true');
	}
	if ( get_option('skt_donation_paypalexp_active_show') == '') {
		update_option('skt_donation_paypalexp_active_show', 'true');
	}
	if ( get_option('skt_donation_default_gateway') == '') {
		update_option('skt_donation_default_gateway', 'payPal');
	}
	if ( get_option('skt_donation_paypal_active_show') == '') {
		update_option('skt_donation_paypal_active_show', 'false');
	}
	if ( get_option('skt_donation_first_name_show') == '') {
		update_option('skt_donation_first_name_show', 'true');
	}
	if ( get_option('skt_donation_last_name_show') == '') {
		update_option('skt_donation_last_name_show', 'true');
	}
	if ( get_option('skt_donation_address_show') == '') {
		update_option('skt_donation_address_show', 'true');
	}
	if ( get_option('skt_donation_note_show') == '') {
		update_option('skt_donation_note_show', 'true');
	}
	if ( get_option('skt_donation_message_show') == '') {
		update_option('skt_donation_message_show', 'true');
	}
	if ( get_option('skt_donation_day_show') == '') {
		update_option('skt_donation_day_show', 'true');
	}
	if ( get_option('skt_donation_week_show') == '') {
		update_option('skt_donation_week_show', 'true');
	}
	if ( get_option('skt_donation_month_show') == '') {
		update_option('skt_donation_month_show', 'true');
	}
	if ( get_option('skt_donation_quaterly_show') == '') {
		update_option('skt_donation_quaterly_show', 'true');
	}
	if ( get_option('skt_donation_semiquaterly_show') == '') {
		update_option('skt_donation_semiquaterly_show', 'true');
	}
	if ( get_option('skt_donation_annual_show') == '') {
		update_option('skt_donation_annual_show', 'true');
	}
	if ( get_option('skt_donation_phone_show') == '') {
		update_option('skt_donation_phone_show', 'true');
	}
	if ( get_option('skt_donation_email_show') == '') {
		update_option('skt_donation_email_show', 'true');
	}
	if ( get_option('skt_donation_admin_backgroundcolor') == '') {
		update_option('skt_donation_admin_backgroundcolor', '#08A0E3');
	}
	if ( get_option('skt_donation_admin_hover_backgroundcolor') == '') {
		update_option('skt_donation_admin_hover_backgroundcolor', '#F98315');
	}
	if ( get_option('skt_donation_admin_menu_backgroundcolor') == '') {
		update_option('skt_donation_admin_menu_backgroundcolor', '#333333');
	}
	if ( get_option('skt_donation_admin_menu_backgroundcolor') == '') {
		update_option('skt_donation_admin_page_backgroundcolor', '#08A0E3');
	}
	if ( get_option('skt_donation_fend_backgroundcolor') == '') {
		update_option('skt_donation_fend_backgroundcolor', '#f98315');
	}
	if ( get_option('skt_donation_fend_hover_backgroundcolor') == '') {
		update_option('skt_donation_fend_hover_backgroundcolor', '#09a0e2');
	}
	if ( get_option('skt_donation_fend_menu_backgroundcolor') == '') {
		update_option('skt_donation_fend_menu_backgroundcolor', '#09a0e2');
	}
	if ( get_option('skt_donation_fend_menu_hover_backgroundcolor') == '') {
		update_option('skt_donation_fend_menu_hover_backgroundcolor', '#08A0E3');
	}
	if ( get_option('skt_donation_fend_form_backgroundcolor') == '') {
		update_option('skt_donation_fend_form_backgroundcolor', '#f3f3f3');
	}
	if ( get_option('skt_donation_installation_date') == '') {
		update_option('skt_donation_installation_date', date('d-m-Y'));
	}
	//PayPalForm Empty
	if ( get_option('skt_donation_stripe_first_name') == '') {
		update_option('skt_donation_stripe_first_name', 'Enter First Name');
	}
	if ( get_option('skt_donation_stripe_last_name') == '') {
		update_option('skt_donation_stripe_last_name', 'Enter Last Name');
	}
	if ( get_option('skt_donation_stripe_email') == '') {
		update_option('skt_donation_stripe_email', 'Enter Email');
	}
	if ( get_option('skt_donation_stripe_phone_name') == '') {
		update_option('skt_donation_stripe_phone_name', 'Enter Phone');
	}
	if ( get_option('skt_donation_stripe_normal_payment') == '') {
		update_option('skt_donation_stripe_normal_payment', 'Normal');
	}
	if ( get_option('skt_donation_stripe_subscription_payment') == '') {
		update_option('skt_donation_stripe_subscription_payment', 'Subscription');
	}
	if ( get_option('skt_donation_stripe_amount') == '') {
		update_option('skt_donation_stripe_amount', 'Amount');
	}
	if ( get_option('skt_donation_stripe_card_no') == '') {
		update_option('skt_donation_stripe_card_no', 'Card Number');
	}
	//Label Form PayPal Form Empty
	if ( get_option('skt_donation_stripe_first_name_lable') == '') {
		update_option('skt_donation_stripe_first_name_lable', 'First Name');
	}
	if ( get_option('skt_donation_stripe_last_name_lable') == '') {
		update_option('skt_donation_stripe_last_name_lable', 'Last Name');
	}
	if ( get_option('skt_donation_stripe_email_lable') == '') {
		update_option('skt_donation_stripe_email_lable', 'Email');
	}
	if ( get_option('skt_donation_stripe_phone_name_lable') == '') {
		update_option('skt_donation_stripe_phone_name_lable', 'Phone');
	}
	if ( get_option('skt_donation_stripe_amount_lable') == '') {
		update_option('skt_donation_stripe_amount_lable', 'Amount');
	}
	if ( get_option('skt_donation_stripe_card_no_lable') == '') {
		update_option('skt_donation_stripe_card_no_lable', 'Card Number');
	}
	if ( get_option('skt_donation_stripe_type_of_payment_label') == '') {
		update_option('skt_donation_stripe_type_of_payment_label', 'Select Donation Type');
	}
	// 2Chectout for form setting
	if ( get_option('skt_donation_twocheck_address') == '') {
		update_option('skt_donation_twocheck_address', 'Address');
	}
	if ( get_option('skt_donation_twocheck_city') == '') {
		update_option('skt_donation_twocheck_city', 'City');
	}
	if ( get_option('skt_donation_twocheck_state') == '') {
		update_option('skt_donation_twocheck_state', 'State');
	}
	if ( get_option('skt_donation_twocheck_zipcode') == '') {
		update_option('skt_donation_twocheck_zipcode', 'ZipCode');
	}
	if ( get_option('skt_donation_twocheck_country') == '') {
		update_option('skt_donation_twocheck_country', 'Country');
	}
	// Default Amount
	if ( get_option('skt_donation_amount_in_usd') == '') {
		update_option('skt_donation_amount_in_usd', '15');
	}
	if ( get_option('skt_donation_priceper') == '') {
		update_option('skt_donation_priceper', 'year');
	}

	
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	//* Create the skt_donation_amount table
	$table_name = $wpdb->prefix .'skt_donation_amount';
		$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		customer_firstname varchar(255) DEFAULT NULL,
		customer_lastname mediumtext,
		customer_email varchar(255) DEFAULT NULL,
		customer_phone varchar(255) DEFAULT NULL,
		paypal_payment_id mediumtext,
		paypal_payer_id mediumtext,
		paypal_token mediumtext,
		paypalrxpress_subscriptions_id VARCHAR (255),
		mode varchar(255) NOT NULL DEFAULT 'PayPal',
		status varchar(255) DEFAULT NULL,
		donation_amount varchar(255) DEFAULT NULL,
		payment_date varchar(255) DEFAULT NULL,
		subscription_normal varchar(255) NOT NULL,
		duration_of_subscription varchar(255) NOT NULL
	 ) $charset_collate;";
	 dbDelta( $sql );

	//* Create the wp_skt_choose_currency_paypal table
	$skt_choose_currency_paypal = $wpdb->prefix .'skt_choose_currency_paypal';
	$sql_skt_choose_currency_paypal = "CREATE TABLE $skt_choose_currency_paypal (
	  id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  type_currency_id bigint(20) NOT NULL,
	  currency_symbol_id bigint(20) NOT NULL,
	  added_date datetime NOT NULL,
	  modified_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
	) $charset_collate;";
	dbDelta( $sql_skt_choose_currency_paypal );	
	
	//* Create the wp_skt_country_type_currency table
	$skt_country_type_currency = $wpdb->prefix .'skt_country_type_currency';
	$sql_skt_country_type_currency = "CREATE TABLE $skt_country_type_currency (
		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		currency_stripe char(10) NOT NULL,
		type_mode char(20) NOT NULL,
		currency_sign varchar(255) NOT NULL
	) $charset_collate;";
	dbDelta( $sql_skt_country_type_currency );
	if($sql_skt_country_type_currency){
		$insert_query = $wpdb->query("INSERT INTO $skt_country_type_currency (id, currency_stripe, type_mode, currency_sign) VALUES
		(138, 'BRL', 'paypal', '&#82;&#36;'),
		(139, 'AUD', 'paypal', '&#36;'),
		(140, 'CZK', 'paypal', '&#75;&#269;'),
		(141, 'DKK', 'paypal', '&#107;&#114;'),
		(142, 'EUR', 'paypal', '&#8364;'),
		(143, 'HKD', 'paypal', '&#36;'),
		(144, 'HUF', 'paypal', ' &#70;&#116;'),
		(145, 'INR', 'paypal', '&#8377;'),
		(146, 'ILS', 'paypal', '&#8362;'),
		(147, 'JPY', 'paypal', '&#165;'),
		(148, 'MYR', 'paypal', '&#82;&#77;'),
		(149, 'MXN', 'paypal', '&#36;'),
		(150, 'NOK', 'paypal', '&#107;&#114;'),
		(151, 'NZD', 'paypal', ' &#36;'),
		(152, 'PHP', 'paypal', '&#8369;'),
		(153, 'PLN', 'paypal', '&#122;&#322;'),
		(154, 'GBP', 'paypal', '-'),
		(155, 'RUB', 'paypal', '&#1088;&#1091;&#1073;'),
		(156, 'SGD', 'paypal', '&#36;'),
		(157, 'SEK', 'paypal', '&#107;&#114;'),
		(158, 'CHF', 'paypal', '&#67;&#72;&#70;'),
		(159, 'TWD', 'paypal', '&#78;&#84;&#36;'),
		(160, 'THB', 'paypal', '&#3647;'),
		(161, 'USD', 'paypal', '&#36;');");
	}
}
register_activation_hook( __FILE__, 'skt_donation_install' );
/*
* Deactivate plugin
*/
function skt_donation_deactivation(){
    // our post type will be automatically removed, so no need to unregister it
    // clear the permalinks to remove our post type's rules
	    global $wpdb;
	    $skt_country_type_currency = $wpdb->prefix .'skt_country_type_currency';
	   	$sql_country_type = "DROP TABLE IF EXISTS $skt_country_type_currency";
	    $wpdb->query($sql_country_type);
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'skt_donation_deactivation' );
/*
* Enqueuing scripts and styles
*/
add_action( 'wp_enqueue_scripts', 'skt_donation_enqueue' );
function skt_donation_enqueue() {
	wp_enqueue_style( 'skt-donation-stylesheet', SKT_DONATIONS_URI . '/css/skt-donation.css', 'skt-donation-stylesheet');
	wp_enqueue_script( 'skt-donations-bootstrap-min-script', SKT_DONATIONS_URI . '/js/bootstrap.min.js', array( 'jquery' ), true );
}
// load js into the admin pages
function sktdonations_enqueue_options_script() {
	wp_enqueue_script( 'skt-donations-admin-custom-script', SKT_DONATIONS_URI . '/js/custom-admin.js', array( 'jquery' ), true );	
	wp_enqueue_script( 'skt-donations-admin-datatable-script', SKT_DONATIONS_URI . '/js/dataTables.min.js', array( 'jquery' ), true );	
	if( is_admin() ) {
		// Include our custom jQuery file with WordPress Color Picker dependency
		wp_enqueue_script( 'skt-donation-admin-frontend-color-picker', SKT_DONATIONS_URI . '/js/color-picker.js', array( 'wp-color-picker' ), false, true ); 
	}	
}
add_action( 'admin_enqueue_scripts', 'sktdonations_enqueue_options_script' );
// load css into the admin pages
function sktdonations_enqueue_options_style() {
wp_enqueue_style( 'skt-donations-customadmin', SKT_DONATIONS_URI. '/css/custom-admin.css', 'skt-donations-customadmin');
	if( is_admin() ) {
		// Add the color picker css file       
		wp_enqueue_style( 'wp-color-picker' ); 
	}
	load_plugin_textdomain( 'skt-donation', FALSE, basename(dirname(__FILE__)).'/languages' );
}
add_action( 'admin_enqueue_scripts', 'sktdonations_enqueue_options_style' );
include_once( SKT_DONATIONS_DIR . '/includes/settings.php' );
include_once( SKT_DONATIONS_DIR . '/includes/shortcodes.php' );
include_once( SKT_DONATIONS_DIR . '/includes/manage_currency.php' );
include_once( SKT_DONATIONS_DIR . '/includes/settings-donation.php' );
include_once( SKT_DONATIONS_DIR . '/includes/delete-donation.php' );