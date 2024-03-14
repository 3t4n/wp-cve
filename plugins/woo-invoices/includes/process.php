<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

add_action( 'woocommerce_saved_order_items', 'sliced_woocommerce_update_from_admin_order', 10, 2 );
add_filter( 'woocommerce_email_attachments', 'sliced_attach_pdf_to_woocomerce_email', 999, 3);

// gets the customer details from within an order
add_filter( 'woocommerce_ajax_get_customer_details', 'sliced_woocomerce_get_customer_details', 10, 3 );

// client accepts the quote
add_action( 'sliced_client_accepted_quote', 'sliced_woocommerce_client_accepted_quote');

// client declined the quote
add_action( 'sliced_client_declined_quote', 'sliced_woocommerce_client_declined_quote');

// client makes a successful payment on an invoice
// add_action( 'sliced_payment_made', 'sliced_woocommerce_invoice_payment_made', 10, 3 );


/**
 * Add the invoice or quote from the front end
 * 
 * @version 1.2.4
 * @since   1.0.0
 */
function sliced_woocommerce_create_quote_or_invoice( $type, $order, $items = null ) {

    // don't check for items as they may be empty
    if ( ! $order || ! $type ) {
        return;
	}
	
	$sliced_settings = get_option( 'woocommerce_sliced-invoices_settings' );

    /*
     * insert the invoice or quote
     */
    $post_array = array(
        'post_content'   => '',
        'post_title'     => 'Order ID ' . sliced_woocommerce_get_object_property( $order, 'order', 'id' ),
        'post_status'    => 'publish',
        'post_type'      => "sliced_${type}",
    );
    $id = wp_insert_post( $post_array, $wp_error = false );

    $taxonomy = "${type}_status";

    /*
     * update depending on type
     */
    if( $type == 'quote' ) {
        
		if( $sliced_settings['auto_quote_email'] === 'yes' ) {
			wp_set_object_terms( $id, array( 'sent' ), $taxonomy );
		} else {
			wp_set_object_terms( $id, array( 'draft' ), $taxonomy );
		}

		$quote = get_option( 'sliced_quotes' );
		update_post_meta( $id, '_sliced_quote_valid_until', Sliced_Quote::get_auto_valid_until_date() );
		update_post_meta( $id, "_sliced_quote_terms", $quote['terms'] );
        update_post_meta( $id, "_sliced_quote_prefix", sliced_get_quote_prefix() );
        update_post_meta( $id, "_sliced_quote_number", sliced_get_next_quote_number() );
        Sliced_Quote::update_quote_number( $id );
    
    } else {

        wp_set_object_terms( $id, array( 'unpaid' ), $taxonomy );
		
		$invoice = get_option( 'sliced_invoices' );
		update_post_meta( $id, '_sliced_invoice_due', Sliced_Invoice::get_auto_due_date() );
		update_post_meta( $id, "_sliced_invoice_terms", $invoice['terms'] );
        update_post_meta( $id, "_sliced_invoice_prefix", sliced_get_invoice_prefix() );
        update_post_meta( $id, "_sliced_invoice_number", sliced_get_next_invoice_number() );
        update_post_meta( $id, "_sliced_order_number", sliced_woocommerce_get_object_property( $order, 'order', 'id' ) );
        Sliced_Invoice::update_invoice_number( $id );
        
        /*
         * Check the payment methods and add them to the invoice
         */
        if( ! empty( $sliced_settings['enable_payment_methods'] ) ) {
            update_post_meta( $id, '_sliced_payment_methods', $sliced_settings['enable_payment_methods'] );
        }

    }
    //update_post_meta( $id, "_sliced_${type}_woocommerce_order", sliced_woocommerce_get_object_property( $order, 'order', 'id' ) );
	// maybe set both for now, just to be safe:
	update_post_meta( $id, "_sliced_invoice_woocommerce_order", sliced_woocommerce_get_object_property( $order, 'order', 'id' ) );
	if( $type == 'quote' ) {
		update_post_meta( $id, "_sliced_quote_woocommerce_order", sliced_woocommerce_get_object_property( $order, 'order', 'id' ) );
	}
	
    update_post_meta( $id, "_sliced_${type}_created", time() );
    
    /*
     * add the line items
     */
    if( !empty( $items ) ) {
        // loop through the line items and put into our format
        foreach ($items as $index => $item) {
            $items_array[] = array(
                'qty'           => esc_html( $item['qty'] ),
                'title'         => esc_html( $item['name'] ),
                'description'   => '',
                'amount'        => esc_html( $item['line_total'] / $item['qty'] ),
            );
        }
        add_post_meta( $id, '_sliced_items', $items_array );
    } 

    /*  
     * add the client or get existing
     */
    sliced_woocommerce_maybe_add_client( $id, $order );

    return $id;
}    


/**
 * UPDATE or ADD the invoice or quote when order is added or updated from admin
 * @since   1.0
 */
function sliced_woocommerce_update_from_admin_order( $order_id, $items ) {

    $order = wc_get_order( $order_id );
    if( ! $order )
        return;

    // get the status that is being saved
    $status = isset( $items['order_status'] ) ? esc_html( $items['order_status'] ) : '';

    /*
     * Get the existing sliced invoices post id
     * If there is no id, this means it is not associated with a Sliced Invoices quote or invoice.
     */
    $id = sliced_woocommerce_get_invoice_id( $order_id );

    if( ! $id || $id == false ) {
        // if the status is an invoice or quote, go ahead and create the invoice/quote
        if( $status == 'wc-quote' || $status == 'wc-invoice' ) {
            $id = sliced_woocommerce_create_quote_or_invoice( substr($status, 3), $order, null );
        }
    } else {
        // still need to run it through this to update the client or get existing
        sliced_woocommerce_maybe_add_client( $id, $order );
    };
	
	if ( ! $id ) {
		// nothing more to do...
		return;
	}


    /*
     * Set the meta data depending on post type
     * If the type remains the same, do nothing to the meta data
     */
    $type = substr($status, 3);
    $current_type = sliced_get_the_type( $id );
    if( $type != $current_type ) {
    
        if( $type == 'quote' ) {
            
            $created = get_post_meta( $id, "_sliced_invoice_created", true );
            $quote = get_option( 'sliced_quotes' );
            update_post_meta( $id, "_sliced_quote_terms", $quote['terms'] );
            update_post_meta( $id, "_sliced_quote_number", sliced_get_next_quote_number() );
            update_post_meta( $id, "_sliced_quote_prefix", sliced_get_quote_prefix() );
            update_post_meta( $id, "_sliced_quote_created", $created );
            update_post_meta( $id, "_sliced_quote_woocommerce_order", $order_id );
            Sliced_Quote::update_quote_number( $id );

        } else if( $type == 'invoice' ) {

            $created = get_post_meta( $id, "_sliced_quote_created", true );
            $invoice = get_option( 'sliced_invoices' );
            $wc_si   = get_option( 'woocommerce_sliced-invoices_settings' );
            $payment = $wc_si['enable_payment_methods'];
            update_post_meta( $id, "_sliced_invoice_terms", $invoice['terms'] );
            update_post_meta( $id, "_sliced_invoice_number", sliced_get_next_invoice_number() );
            update_post_meta( $id, "_sliced_invoice_prefix", sliced_get_invoice_prefix() );
            update_post_meta( $id, '_sliced_payment_methods', $payment );
            update_post_meta( $id, "_sliced_invoice_created", $created );
            update_post_meta( $id, "_sliced_invoice_woocommerce_order", $order_id );
            Sliced_Invoice::update_invoice_number( $id );

        }
            
    }

    /*
     * Set the Status and the Post Type on the Sliced Invoices item
     */
    if ( $status ) {

        if( $current_type == 'quote' ) {
            // update the invoice status if set
            switch ( $status ) {
                case 'wc-completed':
                    $term = 'sent';
                    break; 
                case 'wc-cancelled':
                    $term = 'cancelled';
                    break; 
                case 'wc-failed':
                    $term = 'declined';
                    break;
                default:
                    $term = 'draft';
                    break;
            } 

        }        

        if( $current_type == 'invoice' ) {
            // update the invoice status if set
            switch ( $status ) {
                case 'wc-completed':
                    $term = 'paid';
                    break; 
                case 'wc-refunded':
                case 'wc-failed':
                case 'wc-cancelled':
                    $term = 'cancelled';
                    break; 
                case 'wc-quote':
                    $term = 'draft';
                    break; 
                case 'wc-invoice':
                case 'wc-pending':
                case 'wc-processing':
                    $term = 'unpaid';
                    break;
                default:
                    $term = 'draft';
                    break;
            } 

        } 


        if( $type == 'quote' || $type == 'invoice' ) {
            set_post_type( $id, 'sliced_' . $type );
        }   

        $taxonomy = "${type}_status";
        wp_set_object_terms( $id, array( $term ), $taxonomy );
        
    }
          
    /*
     * update the line items
     */
    $products = $order->get_items();
    if( !empty( $products ) ) {
        // delete the items we currently have
        delete_post_meta( $id, '_sliced_items' );
        // loop through the line items and put into our format
        foreach ($products as $index => $item) {
            $product_array[] = array(
                'qty'           => esc_html( $item['qty'] ),
                'title'         => esc_html( $item['name'] ),
                'description'   => '',
                'amount'        => esc_html( $item['line_total'] / $item['qty'] ),
            );
        }
        update_post_meta( $id, '_sliced_items', $product_array );
    }


}


/**
 * What to do when a user accepts the quote
 * @since  1.0
 */ 
function sliced_woocommerce_client_accepted_quote( $id ) {
    
    if( ! $id )
        return;

    $order_id = get_post_meta( $id, '_sliced_quote_woocommerce_order', true );
    
    if( ! $order_id )
        return;

    // update woocommerce order status to an invoice
    $order = new WC_Order($order_id);
    $order->update_status('invoice');

    // update sliced invoices invoice to unpaid
    wp_set_object_terms( $id, array( 'unpaid' ), 'invoice_status' );

    //Check the payment methods and add them to the invoice
    $sliced_settings = get_option( 'woocommerce_sliced-invoices_settings' );
    if( $sliced_settings['enable_payment_methods'] != "" ) {
        update_post_meta( $id, '_sliced_payment_methods', $sliced_settings['enable_payment_methods'] );
    }
    
    update_post_meta( $id, '_sliced_invoice_woocommerce_order', $order_id );

}


/**
 * What to do when a user declines the quote
 * @since  1.0
 */ 
function sliced_woocommerce_client_declined_quote( $id ) {
    
    if( ! $id )
        return;

    $order_id = get_post_meta( $id, '_sliced_quote_woocommerce_order', true );
    
    if( ! $order_id )
        return;

    // update woocommerce order status to an invoice
    $order = new WC_Order($order_id);
    $order->update_status('cancelled');

}



/**
 * Attach the PDF to the order email
 * @since   1.0
 */
function sliced_attach_pdf_to_woocomerce_email( $attachments, $status, $order ) {
    
    // if we don't have the PDF and email extension installed, then return
    if ( ! class_exists( 'Sliced_Pdf' ) ) {
        return $attachments;
	}

    $allowed_statuses = array( 'new_order', 'customer_invoice', 'customer_processing_order', 'customer_completed_order', 'customer_quote' );

    if( isset( $status ) && in_array ( $status, $allowed_statuses ) ) {
        $id = sliced_woocommerce_get_invoice_id( sliced_woocommerce_get_object_property( $order, 'order', 'id' ) );
        $emails = new Sliced_Emails;
        $attachment = null;
        $pdf = $emails->maybe_attach_pdf( $attachment, $id );
        $attachments[] = $pdf;
    }

    return $attachments;
}


/**
 * Loads some missing data when adding a customer to an order
 * @since  1.0
 */ 
function sliced_woocomerce_get_customer_details( $data, $customer, $user_id ) {

    $client = get_userdata( $user_id );

    if( ! isset( $data['billing']['first_name'] ) || $data['billing']['first_name'] == '' ) {
        $data['billing']['first_name'] = $client->first_name;
    }
    if( ! isset( $data['billing']['last_name'] ) || $data['billing']['last_name'] == '' ) {
        $data['billing']['last_name'] = $client->last_name;
    }
    if( ! isset( $data['billing']['email'] ) || $data['billing']['email'] == '' ) {
        $data['billing']['email'] = $client->user_email;
    }
    if( ! isset( $data['billing']['company'] ) || $data['billing']['company'] == '' ) {
        $data['billing']['company'] = get_user_meta( $user_id, '_sliced_client_business', true );
    }

    return $data;

}


/**
 * Check for existing client and add new one if does not exist.
 * @since  1.0
 */ 
function sliced_woocommerce_maybe_add_client( $id, $order ) {

    // if on the front end
    if( is_checkout() ) {

        $client_id  = get_current_user_id();
        $first_name = ! empty( $_POST['billing_first_name'] ) ? esc_html( $_POST['billing_first_name'] ) : '';
        $last_name  = ! empty( $_POST['billing_last_name'] ) ? esc_html( $_POST['billing_last_name'] ) : '';
        $company    = ! empty( $_POST['billing_company'] ) ? esc_html( $_POST['billing_company'] ) : '';
        $email      = ! empty( $_POST['billing_email'] ) ? sanitize_email( $_POST['billing_email'] ) : '';

    // if in the admin, doing an order
    } else {

        $client_id  = ! empty( $_POST['customer_user'] ) || $_POST['customer_user'] != 0 ? $_POST['customer_user'] : false;
        $first_name = ! empty( $_POST['_billing_first_name'] ) ? esc_html( $_POST['_billing_first_name'] ) : '';
        $last_name  = ! empty( $_POST['_billing_last_name'] ) ? esc_html( $_POST['_billing_last_name'] ) : '';
        $company    = ! empty( $_POST['_billing_company'] ) ? esc_html( $_POST['_billing_company'] ) : '';
        $email      = ! empty( $_POST['_billing_email'] ) ? sanitize_email( $_POST['_billing_email'] ) : '';

    }

    // generate random password to be used later on
    $password = wp_generate_password( 10, true, true );
    
    // a bit of safeguarding to try to ensure we get a name and/or business
    $name       = trim( $first_name != '' ? $first_name . ' ' . $last_name : $company ); // could still be empty
    $business   = trim( $company != '' ? $company : $first_name . ' ' . $last_name ); // could still be empty

    // if no name or business, use email
    if( empty( $name ) && empty( $business ) ) {
        $name = $email;
    }

    if( ! $client_id ) {

        /*
         * if client does not exist, create one
         */
        if( ! email_exists( $email ) ) {

            $username = sanitize_user( $name );

            // if username exists, add a dash and some random numbers to the end
            if( username_exists( $username ) ) {
                $username = $username . '-' . rand(1000, 9999) . substr($password, 0, 4);
            }
            // if username is empty, which it shouldn't be. Jsut some safeguarding
            if( $username == '' || $username == false ) {
                $username = 'Blank-Name-' . rand(1000, 9999) . substr($password, 0, 4);
            }   

            // create the user
            $userdata = array(
                'user_login'  =>  $username,
                'user_email'  =>  $email,
                'user_pass'   =>  $password  // When creating a user, `user_pass` is expected.
            );
            $client_id = wp_insert_user( $userdata );

            // roughly format the address
            $formatted_address = esc_html( $_POST['_billing_address_1'] ) . '<br>';
            $formatted_address .= esc_html( $_POST['_billing_address_2'] ) . '<br>';
            $formatted_address .= esc_html( $_POST['_billing_city'] ) . ', ' . esc_html( $_POST['_billing_state'] ) . ' ' . esc_html( $_POST['_billing_postcode'] );

            // add the user meta
            update_user_meta( $client_id, '_sliced_client_business', $business );
            update_user_meta( $client_id, '_sliced_client_address', $formatted_address );
            update_user_meta( $client_id, 'first_name', esc_html( $first_name ) );
            update_user_meta( $client_id, 'last_name', esc_html( $last_name ) );

        } else {

            // get the existing user id
            $client     = get_user_by( 'email', $email );
            $client_id  = $client->ID;

        }

    /*
     * we have a client_id    
     */
    } else { 

        // if the client doesn't have a Sliced Invoices business name set
        $existing_business = get_user_meta( $client_id, '_sliced_client_business', true );
        if( ! $existing_business || empty( $existing_business ) || $existing_business == '' ) {
            update_user_meta( $client_id, '_sliced_client_business', $business );  
        }

    }

    // add the user to the post
    update_post_meta( $id, '_sliced_client', (int)$client_id );

    return $client_id;

}


/**
 * Update the woocommerce status after payment made.
 *
 * @since   2.0.0
 */
// function sliced_woocommerce_invoice_payment_made( $id, $gateway, $status ) {
//     // if( $status == 'success' ) {  
//     // }
// }