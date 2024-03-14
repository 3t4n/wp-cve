<?php
/**
 * Plugin Name: Loan Calculator WP
 * Plugin URI: http://www.worldwebtechnology.com/
 * Description:  Loan / EMI Calculator for Home Loan and Personal Loan
 * Version: 1.2.2
 * Author: World Web Technology
 * Author URI: http://www.worldwebtechnology.com
 * Text Domain: loan-calculator-wp
 * Domain Path: languages
 * 
 * @package Loan Calculator
 * @category Core
 * @author World Web Technology
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Define Some needed predefined variables
 * 
 * @package Loan Calculator
 * @since 1.0.0
 */
if( !defined( 'WW_LOAN_CALCULATOR_VERSION' ) ) {
	define( 'WW_LOAN_CALCULATOR_VERSION', '1.2.2' ); //version of plugin
}
if (!defined('WW_LOAN_CALCULATOR_TEXT_DOMAIN')) { //check if variable is not defined previous then define it
    define( 'WW_LOAN_CALCULATOR_TEXT_DOMAIN', 'loan-calculator-wp' ); //this is for multi language support in plugin
}
if( !defined( 'WW_LOAN_CALCULATOR_DIR' ) ) {
	define( 'WW_LOAN_CALCULATOR_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'WW_LOAN_CALCULATOR_ADMIN' ) ) {
	define( 'WW_LOAN_CALCULATOR_ADMIN', WW_LOAN_CALCULATOR_DIR . '/includes/admin' ); // plugin admin dir
}
if( !defined( 'WW_LOAN_CALCULATOR_LEVEL' ) ) { //check if variable is not defined previous then define its
	define( 'WW_LOAN_CALCULATOR_LEVEL','manage_options' ); //this is capability in plugin
}
if( !defined( 'WW_LOAN_CALCULATOR_URL' ) ) {
	define( 'WW_LOAN_CALCULATOR_URL',plugin_dir_url( __FILE__ ) ); // plugin url
}
if ( !defined( 'WW_LOAN_CALCULATOR_OPTION' ) ) {
    define( 'WW_LOAN_CALCULATOR_OPTION', 'ww_loan_calculaor_option' ); // Option Name
}
if( !defined( 'WW_LOAN_CALCULATOR_BASENAME' ) ) {
	define( 'WW_LOAN_CALCULATOR_BASENAME', basename( WW_LOAN_CALCULATOR_DIR ) ); // base name
}

//add action to load plugin
add_action( 'plugins_loaded', 'ww_loan_calculator_load_plugin_textdomain' );

// Includes admin File
require_once( WW_LOAN_CALCULATOR_DIR . '/includes/loan-calculator-misc-functions.php' );

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Loan Calculator
 * @since 1.0.0
 **/
function ww_loan_calculator_load_plugin_textdomain() {
	
	// Set filter for plugin's languages directory
	$ww_lc_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$ww_lc_lang_dir	= apply_filters( 'ww_loan_calculator_languages_directory', $ww_lc_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'loan-calculator-wp' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'loan-calculator-wp', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $ww_lc_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . WW_LOAN_CALCULATOR_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/loan-calculator folder
		load_textdomain( 'loan-calculator-wp', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/loan-calculator/languages/ folder
		load_textdomain( 'loan-calculator-wp', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'loan-calculator-wp', false, $ww_lc_lang_dir );
	}
}

/**
 * Plugin Activation Hook
 */
register_activation_hook( __FILE__, 'ww_loan_calculator_register_activation' );
function ww_loan_calculator_register_activation() {
    
    $loan_calculator_db_version = get_option( 'loan_calculator_db_version' );
    if( empty( $loan_calculator_db_version ) ) {

        // Set default data
        $loan_calculator_default_options = array();
        $loan_calculator_default_options['first_heading_lbl'] = esc_html__( 'I want a', 'loan-calculator-wp');
        $loan_calculator_default_options['application_fee_heading'] = esc_html__( 'Application fee', 'loan-calculator-wp');
        $loan_calculator_default_options['monthly_fee_heading'] = esc_html__( 'Monthly fee', 'loan-calculator-wp');
        $loan_calculator_default_options['total_regular_fees'] = esc_html__( 'Total regular fees', 'loan-calculator-wp');
        $loan_calculator_default_options['total_fees'] = esc_html__( 'Total fees', 'loan-calculator-wp');
        $loan_calculator_default_options['back_ground_color'] = '#fad712';
        $loan_calculator_default_options['hover_color'] = '#08e71f';
        $loan_calculator_default_options['selected_color'] = '#404245';
        $loan_calculator_default_options['background_light_color'] = '#fff7d7';
        $loan_calculator_default_options['border_color'] = '#000000';
        $loan_calculator_default_options['graph_color'] = '#b3b2b2';
        $loan_calculator_default_options['graph_border_color'] = '#545454';
        $loan_calculator_default_options['graph_color_sub'] = '#ead1d1';
        $loan_calculator_default_options['graph_border_color_sub'] = '#aaa1a1';
        $loan_calculator_default_options['select_theme'] = 'default_theme';
        $loan_calculator_default_options['loan_amount'] = '10000';
        $loan_calculator_default_options['loan_amount_min_value'] = '1000';
        $loan_calculator_default_options['loan_amount_max_value'] = '1000000';
        $loan_calculator_default_options['loan_term'] = '12';
        $loan_calculator_default_options['loan_term_min_value'] = '12';
        $loan_calculator_default_options['loan_term_max_value'] = '84';
        $loan_calculator_default_options['ballon_per'] = '30';
        $loan_calculator_default_options['interested_rate'] = '4.5';
        $loan_calculator_default_options['interest_rate_min_value'] = '0.25';
        $loan_calculator_default_options['interest_rate_max_value'] = '15.00';
        $loan_calculator_default_options['application_fee'] = '500';
        $loan_calculator_default_options['monthly_rate'] = '10';
        $loan_calculator_default_options['regular_repayment_heading'] = esc_html__('Monthly Payment (incl fees)', 'loan-calculator-wp');
        $loan_calculator_default_options['total_payouts_heading'] = esc_html__('Total Payment', 'loan-calculator-wp');
        $loan_calculator_default_options['per_month_heading'] = esc_html__( 'per month for', 'loan-calculator-wp');
        $loan_calculator_default_options['years_heading'] = esc_html__( 'years', 'loan-calculator-wp'); 
        $loan_calculator_default_options['total_interests_payable_heading'] = esc_html__( 'Total interest payable', 'loan-calculator-wp');
        $loan_calculator_default_options['over_heading'] = esc_html__( 'over', 'loan-calculator-wp');
        $loan_calculator_default_options['ballon_amt_heading'] = esc_html__( 'Balloon amount', 'loan-calculator-wp');
        $loan_calculator_default_options['loan_feature_product_heading'] = esc_html__( 'Loan Product Features', 'loan-calculator-wp');
        $loan_calculator_default_options['video_heading'] = esc_html__( 'Video', 'loan-calculator-wp');
        $loan_calculator_default_options['repayment_chart_heading'] = esc_html__( 'Repayment Chart', 'loan-calculator-wp');
        $loan_calculator_default_options['loan_table_heading'] = esc_html__( 'Loan Amortisation Table', 'loan-calculator-wp');
        $loan_calculator_default_options['youtube_video_link'] = '';
        $loan_calculator_default_options['contact_popup_button_heading'] = esc_html__( 'Contact us now for a quote', 'loan-calculator-wp');
        $loan_calculator_default_options['calculator_disclaimer_heading'] = esc_html__( 'Calculator Disclaimer', 'loan-calculator-wp');
        $loan_calculator_default_options['calculator_disclaimer_description'] = esc_html__( 'The repayment amount shown using this calculator is an estimate, based on information you have provided. It is provided for illustrative purposes only and actual repayment amounts may vary. To find out actual repayment amounts, contact us. This calculation does not constitute a quote, loan approval, agreement or advice by My Finance. It does not take into account your personal or financial circumstances.', 'loan-calculator-wp');
        $loan_calculator_default_options['loan_amount_tooltip'] = esc_html__( 'Please enter your loan amount here.', 'loan-calculator-wp');
        $loan_calculator_default_options['loan_terms_tooltip'] = esc_html__( 'Please enter the number of years in which you plan to repay the loan.', 'loan-calculator-wp');
        $loan_calculator_default_options['interest_rates_tooltip'] = esc_html__( 'The interest rate will default to the product you selected. You can also change the rate up or down to understand the effect on your repayments and total interest payable.', 'loan-calculator-wp');
        $loan_calculator_default_options['print_option_enable'] = '1';
        $loan_calculator_default_options['print_label'] = esc_html__( 'Print', 'loan-calculator-wp'); 
        $loan_calculator_default_options['about_this_calculator'] = esc_html__( 'About this calculator', 'loan-calculator-wp');
        $loan_calculator_default_options['calculator_heading'] = esc_html__( 'Feel free to use our Equipment Finance Calculator', 'loan-calculator-wp');
        $loan_calculator_default_options['calculator_popup_content'] = '<h1>Calculator Information</h1>
The Equipment Finance Calculator calculates the type of repayment required, at the frequency requested, in respect of the loan parameters entered, namely amount, term and interest rate. The Product selected determines the default interest rate for personal loan product.

The Equipment Finance Calculator also calculates the time saved to pay off the loan and the amount of interest saved based on an additional input from the customer. This is if repayments are increased by the entered amount of extra contribution per repayment period. This feature is only enabled for the products that support an extra repayment.

The calculations are done at the repayment frequency entered, in respect of the original loan parameters entered, namely amount, annual interest rate and term in years.
<h1>Calculator Assumptions</h1>
<h3>Length of Month</h3>
All months are assumed to be of equal length. In reality, many loans accrue on a daily basis leading to a varying number of days interest dependent on the number of days in the particular month.
<h3>Number of Weeks or Fortnights in a Year</h3>
One year is assumed to contain exactly 52 weeks or 26 fortnights. This implicitly assumes that a year has 364 days rather than the actual 365 or 366.
<h3>Rounding of Amount of Each Repayment</h3>
In practice, repayments are rounded to at least the nearer cent. However the calculator uses the unrounded repayment to derive the amount of interest payable at points along the graph and in total over the full term of the loan. This assumption allows for a smooth graph and equal repayment amounts. Note that the final repayment after the increase in repayment amount.
<h3>Rounding of Time Saved</h3>
The time saved is presented as a number of years and months, fortnights or weeks, based on the repayment frequency selected. It assumes the potential partial last repayment when calculating the savings.
<h3>Amount of Interest Saved</h3>
This amount can only be approximated from the amount of time saved and based on the original loan details.
<h3>Calculator Disclaimer</h3>
The results from this calculator should be used as an indication only. Results do not represent either quotes or pre-qualifications for the product. Individual institutions apply different formulas. Information such as interest rates quoted and default figures used in the assumptions are subject to change.';
        $loan_calculator_default_options['calculation_fee_setting_enable'] = '0';
        $loan_calculator_default_options['delete_data_enable'] = '0';
        $loan_calculator_default_options['enable_repayment_chart'] = '1';
        $loan_calculator_default_options['enable_video_tab'] = '0';
        $loan_calculator_default_options['enable_loan_mortisation_tab'] = '1';
        $loan_calculator_default_options['print_option_heading'] = esc_html__( 'Print', 'loan-calculator-wp'); 
        $loan_calculator_default_options['disable_font_awesome'] = '1';   
        $loan_calculator_default_options['ww_loan_currency'] = 'USD';   
        
        //update loan calculator default option 
        update_option( 'ww_loan_option', $loan_calculator_default_options );

        // update db version 
        update_option( 'loan_calculator_db_version', '1.0.1' );
    }

    $loan_calculator_db_version = get_option( 'loan_calculator_db_version' );
    if( $loan_calculator_db_version == '1.0.1' ) {

        // Get option & update currency option
        $loan_calculator_default_options = get_option( 'ww_loan_option' );
        $loan_calculator_default_options['ww_loan_currency'] = 'USD';
        update_option( 'ww_loan_option', $loan_calculator_default_options );

        update_option( 'loan_calculator_db_version', '1.0.2' );
    }

    $loan_calculator_db_version = get_option( 'loan_calculator_db_version' );
    if( $loan_calculator_db_version == '1.0.2' ) {
        // Next update should be here.
    }
}

/**
 * Plugin Deactivation Hook
 */
register_deactivation_hook( __FILE__, 'deactivate_ww_loan_calculator' );
function deactivate_ww_loan_calculator() {
    
    // Check Delete Data flag enable true or not
    $loan_calculator_option_data = get_option( 'ww_loan_option' );

    if( isset( $loan_calculator_option_data['delete_data_enable'] ) && $loan_calculator_option_data['delete_data_enable'] == 1 ) {

        // Delete all plugin setting data
        delete_option( 'ww_loan_option' );
        delete_option( 'delete_data_enable' );
        delete_option( 'loan_calculator_db_version' );
    }
}

// global variables
global $ww_loan_calculator_admin,  $ww_loan_calculator_public, $ww_loan_calculator_script;

// Includes admin File
require_once( WW_LOAN_CALCULATOR_ADMIN . '/class-loan-calculator-admin.php');
$ww_loan_calculator_admin = new WW_Loan_Calculator_Admin_Pages();
$ww_loan_calculator_admin->add_hooks();

// Includes Public File
require_once( WW_LOAN_CALCULATOR_DIR . '/includes/class-ww-loan-calculator-public.php');
$ww_loan_calculator_public = new WW_Loan_Calculator_Public();
$ww_loan_calculator_public->add_hooks();

// Includes Scripts File
require_once( WW_LOAN_CALCULATOR_DIR . '/includes/class-ww-loan-calculator-scripts.php');
$ww_loan_calculator_script = new WW_Loan_Calculator_Script();
$ww_loan_calculator_script->add_hooks();


// Includes Misc File
require_once( WW_LOAN_CALCULATOR_DIR . '/includes/loan-calculator-misc-functions.php');
$loan_currency=ww_loan_get_currencies();