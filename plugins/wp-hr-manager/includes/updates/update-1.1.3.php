<?php
/**
 * Update transaction table, udpate column invoice_number to inoive_format, new invoice_number
 * 
 * @return void
 */
function wphr_ac_update_1_1_3_table() {
	global $wpdb;
	$table = $wpdb->prefix . 'wphr_ac_transactions';
	$cols = $wpdb->get_col( "DESC " . $table );


	if ( in_array( 'invoice_number', $cols ) && ! in_array( 'invoice_format', $cols ) ) {
		$wpdb->query( "ALTER TABLE $table CHANGE `invoice_number` `invoice_format` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
	}

	$cols = $wpdb->get_col( "DESC " . $table );

	if ( ! in_array( 'invoice_number', $cols ) ) {
		$wpdb->query( "ALTER TABLE $table ADD `invoice_number` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `invoice_format`;");
	}

}

/**
 * Update transaction table, udpate column invoice_number and invoice_format for payment form type
 * 
 * @return void
 */
function wphr_ac_update_1_1_3_payment() {
	global $wpdb;

	$table        = $wpdb->prefix . 'wphr_ac_transactions';
	$payment      = wphr_get_option( 'wphr_ac_payment', false, 'SPN-{id}' );
	$pattern      = str_replace( '{id}', '[0-9]+', $payment );
	$get_payments = $wpdb->get_results( $wpdb->prepare( "SELECT id, invoice_format FROM $table WHERE form_type = 'payment' AND invoice_format REGEXP '^{%s}$'", $pattern) );

	foreach ( $get_payments as $key => $value ) {
		$invoice_number = wphr_ac_updater_get_invoice_num_fromat_from_submit_invoice( $value->invoice_format, $payment );
		\WPHR\HR_MANAGER\Accounting\Model\Transaction::where( 'id', '=', $value->id )->update( [ 'invoice_number' => $invoice_number, 'invoice_format' => $payment  ] );
	}

}

/**
 * Update transaction table, udpate column invoice_number and invoice_format for invoice form type
 * 
 * @return void
 */
function wphr_ac_update_1_1_3_invoice() {
	global $wpdb;

	$table        = $wpdb->prefix . 'wphr_ac_transactions';
	$invoice      = wphr_get_option( 'wphr_ac_invoice', false, 'INV-{id}' );
	$pattern      = str_replace( '{id}', '[0-9]+', $invoice );
	$get_invoices = $wpdb->get_results( $wpdb->prepare( "SELECT id, invoice_format FROM $table WHERE form_type = 'invoice' AND invoice_format REGEXP '^{%s}$'", $pattern));

	foreach ( $get_invoices as $key => $value ) {
		$invoice_number = wphr_ac_updater_get_invoice_num_fromat_from_submit_invoice( $value->invoice_format, $invoice );
		\WPHR\HR_MANAGER\Accounting\Model\Transaction::where( 'id', '=', $value->id )->update( [ 'invoice_number' => $invoice_number, 'invoice_format' => $invoice  ] );
	}

}

/**
 * Get invoice number and format fron transaction submit value
 *
 * @param  string $submit_invoice
 * @param  string $invoice_format
 * 
 * @return array
 */
function wphr_ac_updater_get_invoice_num_fromat_from_submit_invoice( $submit_invoice, $invoice_format ) {
    //was found
    $pattern = str_replace( '{id}', '([0-9]+)', $invoice_format ); // INV-([0-9])+-INV
    
    preg_match( "/${pattern}/", $submit_invoice, $match );
 
    $id            = isset( $match[1] ) ? $match[1] : false;
    $check_invoice = false;
    
    if ( $id === false ) {
        return 0;
    } 

    $check_invoice = str_replace( '{id}', $id, $invoice_format );

    $invoice_number = $check_invoice == $submit_invoice ? intval( $id ) : 0;

    return $invoice_number;
}

wphr_ac_update_1_1_3_table();
wphr_ac_update_1_1_3_payment();
wphr_ac_update_1_1_3_invoice();



