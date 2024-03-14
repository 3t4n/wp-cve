<?php

class pisol_corw_emails{
    function __construct(){
        add_filter('woocommerce_email_classes', array($this, 'addEmailClasses'),100,1);

        add_action('woocommerce_email_header_corw', [$this, 'get_heading']);
    }

    function addEmailClasses($email_classes)    {

        require_once('class-admin-order-cancellation-request-email.php');
        require_once('class-customer-order-cancellation-request-email.php');
        require_once('class-customer-order-cancelled.php');
        require_once('class-customer-order-cancelled-rejected.php');
        
        $email_classes['WC_Email_Admin_Order_Cancel_Request'] = new WC_Email_Admin_Order_Cancel_Request();
        $email_classes['WC_Email_Customer_Order_Cancel_Request'] = new WC_Email_Customer_Order_Cancel_Request();
        $email_classes['WC_Email_Customer_Order_Cancelled'] = new WC_Email_Customer_Order_Cancelled();
        $email_classes['WC_Email_Customer_Order_Cancellation_Request_Rejected'] = new WC_Email_Customer_Order_Cancellation_Request_Rejected();
       
        return $email_classes;
    }

    function get_heading( $email_heading ) {
		wc_get_template( 'emails/email-header.php', array( 'email_heading' => $email_heading ),'', PISOL_CORW_BASE_DIR.'/templates/' );
	}
}

new pisol_corw_emails();