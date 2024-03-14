<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 * 
 * Handles Loan Calculator
 *
 * @package Loan Calculator
 * @since 1.0.0
 */
class WW_Loan_Calculator_Admin_Pages {
	
	public function __construct() {
		
	}

	/**
	 * Admin Pages Class
	 * 
	 * Handles Loan Calculator Menu
	 *
	 * @package Loan Calculator
	 * @since 1.0.0
	 */
	public function ww_loan_calculator_admin_menu() {
		
		// Loan Calculator Menu
		add_menu_page( esc_html__('Loan Calculator', 'ww_loan_calculator_page'), esc_html__('Loan Calculator', 'ww_loan_calculator_page'), WW_LOAN_CALCULATOR_LEVEL, 'ww_loan_calculator_page', '', 'dashicons-calculator' );

		add_submenu_page( 'ww_loan_calculator_page', esc_html__('Loan Calculator', 'ww_loan_calculator_page'), esc_html__('Loan Calculator','loan-calculator-wp'), WW_LOAN_CALCULATOR_LEVEL, 'ww_loan_calculator_page', array($this, 'ww_loan_calculator_page'));

	}

    /**
	 * Setting Page for Loan Calculator Plugin
	 *
	 * Handles Function to Setting Page for Loan Calculator Setting Page
	 * 
	 * @package Loan Calculator
	 * @since 1.0.0
	 */
	public function ww_loan_calculator_page() {

		// Include Loan Calculator Setting page
		include_once( WW_LOAN_CALCULATOR_ADMIN . '/forms/ww-loan-calculator-setting.php' );

	}

	function loan_calculator_settings(){

		// Register Loan Calculator Setting
		register_setting( 'ww_loan_calculaor_option', 'ww_loan_option' );

		//  Section Setting Tab
		add_settings_section('section_setting_id','Section Setting','', 'section-setting-admin'); 

	}
	
   /**
     * Adding Hooks
     *
     * @package Loan Calculator
     * @since 1.0.0
     */
    function add_hooks() {

    	// Add Action For Menu Add Loan Calculator
    	add_action( 'admin_menu', array( $this, 'ww_loan_calculator_admin_menu' ) );
    	// Add Loan Calculator Register Setting
    	add_action( 'admin_init', array( $this, 'loan_calculator_settings' ) );
    }
}