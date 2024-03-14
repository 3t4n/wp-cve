<?php 
/**
 * Charitable Instamojo Gateway Hooks. 
 *
 * Action/filter hooks used for handling payments through the Instamojo gateway.
 * 
 * @package     Charitable Instamojo/Hooks/Gateway
 * @version     1.0.0
 * @author      Gautam Garg
 * @copyright   Copyright (c) 2018, GautamMKGarg
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Process the donation. 
 * 
 * @see     Charitable_Gateway_Instamojo::process_donation() 
 */
if ( -1 == version_compare( charitable()->get_version(), '1.3.0' ) ) {
    /** 
     * This is for backwards-compatibility. Charitable before 1.3 used on ation hook, not a filter.
     * 
     * @see     Charitable_Gateway_Instamojo::redirect_to_processing_legacy()
     */
    add_action( 'charitable_process_donation_instamojo', array( 'Charitable_Gateway_Instamojo', 'redirect_to_processing_legacy' ) );
}
else {
    add_filter( 'charitable_process_donation_instamojo', array( 'Charitable_Gateway_Instamojo', 'redirect_to_processing' ), 10, 2 );
}

/**
 * Render the Instamojo donation processing page content. 
 *
 * This is the page that users are redirected to after filling out the donation form. 
 * It automatically redirects them to Instamojo's website.
 *
 * @see Charitable_Gateway_Instamojo::process_donation()
 */
add_filter( 'charitable_processing_donation_instamojo', array( 'Charitable_Gateway_Instamojo', 'process_donation' ), 10, 2 );

/**
 * Check the response from Instamojo after the donor has completed payment.
 *
 * @see Charitable_Gateway_Instamojo::process_response()
 */
add_action( 'charitable_donation_receipt_page', array( 'Charitable_Gateway_Instamojo', 'process_response' ) );

/**
 * Make the "phone" field required in the donation form since Instamojo requires it.
 *
 * @see Charitable_Gateway_Instamojo::set_phone_field_required()
 */
add_filter( 'charitable_donation_form_user_fields', array( 'Charitable_Gateway_Instamojo', 'set_phone_field_required' ) );

/**
 * Change the currency to INR. 
 *
 * @see Charitable_Gateway_Instamojo::change_currency_to_inr()
 */
add_action( 'wp_ajax_charitable_change_currency_to_inr', array( 'Charitable_Gateway_Instamojo', 'change_currency_to_inr' ) );

/**
 * Change the default gateway to Instamojo
 *
 * @see Charitable_Gateway_Instamojo::change_gateway_to_instamojo()
 */
add_action( 'wp_ajax_charitable_change_gateway_to_instamojo', array( 'Charitable_Gateway_Instamojo', 'change_gateway_to_instamojo' ) );
