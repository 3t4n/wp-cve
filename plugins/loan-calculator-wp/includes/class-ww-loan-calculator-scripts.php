<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages as well as the front pages.
 *
 * @package Loan Calculator
 * @since 1.0.0
 */
class WW_Loan_Calculator_Script {

	/**
	 * Include JS and CSS for backend	 
	 *
	 * @package Loan Calculator
	 * @since 1.0.0
	 */
	function ww_loan_calculator_admin_scripts( $hooks_suffix ) {
		
		if( $hooks_suffix == "toplevel_page_ww_loan_calculator_page" ) {			

			// Add JS
			wp_enqueue_script( 
				'loan-calculator-admin-script',
				WW_LOAN_CALCULATOR_URL . 'includes/js/admin-script.js', 
				array('jquery'), 
				WW_LOAN_CALCULATOR_VERSION, 
				true 
			);			
		}
	}

	/**
	 * Include JS and CSS for frontend	 
	 *
	 * @package Loan Calculator
	 * @since 1.0.0
	 */
	function ww_loan_calculator_front_scripts() {	

	  
	  

		wp_register_script( 'loan-calculator-jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array(), WW_LOAN_CALCULATOR_VERSION, true );


		// CHART JS
		wp_register_script( 'loan-calculator-chart-js', WW_LOAN_CALCULATOR_URL . 'includes/js/chart-js/chart.js', array(), WW_LOAN_CALCULATOR_VERSION, true );
		wp_register_script( 'loan-calculator-chart-bundle-script', WW_LOAN_CALCULATOR_URL . 'includes/js/chart-js/chart-bundle.js', array(), WW_LOAN_CALCULATOR_VERSION, true );

		
		// Font awesome
		$loan_all_setting_data = get_option( "ww_loan_option" );
		$disable_font_awesome = isset( $loan_all_setting_data['disable_font_awesome'] ) ? $loan_all_setting_data['disable_font_awesome'] : "";

		if(empty($disable_font_awesome) && $disable_font_awesome ==""){

			wp_register_style( 'loan-calculator-font-awesome-script', WW_LOAN_CALCULATOR_URL . 'includes/css/all.min.css', array(), WW_LOAN_CALCULATOR_VERSION );	
		}		

		// Custom CSS and JS
		//01/05/23
		wp_register_style( 'loan-calculator-new-theme-style', WW_LOAN_CALCULATOR_URL . 'includes/admin/forms/theme-templates/new-theme/css/style.css', array(), WW_LOAN_CALCULATOR_VERSION );	

		 wp_register_style( 'loan-calculator-new-theme-print-styles', WW_LOAN_CALCULATOR_URL .'includes/admin/forms/theme-templates/new-theme/css/print.css', array(), WW_LOAN_CALCULATOR_VERSION, 'print' );

		wp_register_script( 'loan-calculator-new-theme-script', WW_LOAN_CALCULATOR_URL . 'includes/admin/forms/theme-templates/new-theme/js/new-theme-script.js', array('jquery'), WW_LOAN_CALCULATOR_VERSION, true );	

		wp_register_style( 'loan-calculator-frontend-style', WW_LOAN_CALCULATOR_URL . 'includes/css/frontend-style.css', array(), WW_LOAN_CALCULATOR_VERSION );	

		wp_register_script( 'loan-calculator-frontend-script', WW_LOAN_CALCULATOR_URL . 'includes/js/frontend-script.js', array('jquery'), WW_LOAN_CALCULATOR_VERSION, true );	

		wp_register_script( 'loan-calculator-frequency-payment', WW_LOAN_CALCULATOR_URL . 'includes/js/frequency_payment.js', array('jquery'), WW_LOAN_CALCULATOR_VERSION, true );	

		// Fetch Loan Calculator setting data from option table and pass in script.
		$loan_all_setting_data = get_option( "ww_loan_option" );

		$loan_amount_min_value = isset( $loan_all_setting_data['loan_amount_min_value'] ) ? $loan_all_setting_data['loan_amount_min_value'] : "";

		$loan_amount_max_value = isset( $loan_all_setting_data['loan_amount_max_value'] ) ? $loan_all_setting_data['loan_amount_max_value'] : "";

		$loan_term_min_value = isset( $loan_all_setting_data['loan_term_min_value'] ) ? $loan_all_setting_data['loan_term_min_value'] : "";

		$loan_term_max_value = isset( $loan_all_setting_data['loan_term_max_value'] ) ? $loan_all_setting_data['loan_term_max_value'] : "";

		$monthly_rate = isset( $loan_all_setting_data['monthly_rate'] ) ? $loan_all_setting_data['monthly_rate'] : "";

		$application_fee = isset( $loan_all_setting_data['application_fee'] )? $loan_all_setting_data['application_fee'] : "";

		$back_ground_color = isset( $loan_all_setting_data['back_ground_color'] ) ? $loan_all_setting_data['back_ground_color'] : "";

		$interest_rate_min_value = isset( $loan_all_setting_data['interest_rate_min_value'] ) ? $loan_all_setting_data['interest_rate_min_value'] : "";

		$interest_rate_max_value = isset( $loan_all_setting_data['interest_rate_max_value'] ) ? $loan_all_setting_data['interest_rate_max_value'] : "";

		$calculation_fee_setting_enable = isset( $loan_all_setting_data['calculation_fee_setting_enable'] ) ? $loan_all_setting_data['calculation_fee_setting_enable'] : "";
		$remove_decimal_point = isset( $loan_all_setting_data['remove_decimal_point'] ) ? $loan_all_setting_data['remove_decimal_point'] : "";

		$payment_mode_enable = isset( $loan_all_setting_data['payment_mode_enable'] ) ? $loan_all_setting_data['payment_mode_enable'] : "";  

		$total_payouts_heading = isset( $loan_all_setting_data['total_payouts_heading'] ) ? $loan_all_setting_data['total_payouts_heading'] : "";


		$currency_symbols = ww_loan_get_currency_symbol();

		$month_label = __( 'Months','loan-calculator-wp' );
		$year_label = __( 'Years','loan-calculator-wp' );
		$interest_label = __( 'Interest','loan-calculator-wp' );
		$principal_label = __( 'Principal','loan-calculator-wp' );

		// Setting data is passed in js file using Localize 
		$setting_data = array(
			'loan_amount_min_value' =>  $loan_amount_min_value,
			'loan_amount_max_value'   => $loan_amount_max_value,
			'loan_term_min_value'      => $loan_term_min_value,
			'loan_term_max_value' => $loan_term_max_value,
			'monthly_rate' => $monthly_rate,
			'application_fee' => $application_fee,
			'back_ground_color' => $back_ground_color,
			'interest_rate_min_value'=> $interest_rate_min_value,
			'interest_rate_max_value' => $interest_rate_max_value,
			'calculation_fee_setting_enable'=> $calculation_fee_setting_enable,
			'currency_symbols' =>$currency_symbols,
			'remove_decimal_point'=>$remove_decimal_point,
			'month_label' =>$month_label,
			'year_label' =>$year_label,
			'interest_label' =>$interest_label,
			'principal_label' =>$principal_label,
			'payment_mode_enable' =>$payment_mode_enable,
			'total_payouts_heading' =>$total_payouts_heading,

		);
		
		wp_localize_script( 'loan-calculator-frontend-script', 'setting_data', $setting_data );	
	}

	/**
	 * Adding Hooks
	 *
	 * Adding hooks for the styles and scripts.
	 *
	 * @package Loan Calculator
	 * @since 1.0.0
	 */
	public function add_hooks() {

		// add scripts and styles for backend and frontend
		add_action( 'admin_enqueue_scripts', array( $this, 'ww_loan_calculator_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'ww_loan_calculator_front_scripts'));
	}
}