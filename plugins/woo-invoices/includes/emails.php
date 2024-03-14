<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit;
}
    
    add_filter( 'woocommerce_email_classes', 'sliced_add_woocommerce_quote_email' );
    add_filter( 'woocommerce_resend_order_emails_available', 'sliced_make_quote_email_available' );
    add_filter( 'woocommerce_email_actions', 'sliced_add_transactional_emails' );


    /**
     * Hook in our invoice and quote transactional emails. 
     *
     * @since   1.0
     */
    function sliced_add_transactional_emails( $emails ) {
        $emails[] = 'woocommerce_order_status_pending_to_quote';
        $emails[] = 'woocommerce_order_status_pending_to_invoice';
        return $emails;
    }    

    /**
     * Add our quote email template
     *
     * @since   1.0
     */
    function sliced_add_woocommerce_quote_email( $emails ) {
		$emails['WC_Email_Customer_Quote'] = include( 'class-wc-email-customer-quote.php' );
        return $emails;
    }

    /**
     * Make the quote email available for manual sending
     *
     * @since   1.0
     */
    function sliced_make_quote_email_available( $available_emails ) {
        $available_emails = array( 'new_order', 'cancelled_order', 'customer_processing_order', 'customer_completed_order', 'customer_invoice', 'customer_refunded_order', 'customer_quote' );
        return $available_emails;
    }
