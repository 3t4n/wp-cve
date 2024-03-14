<?php

include_once 'lib/Walletdoc.php';

try {

    $pgwalletdoc = new WP_Gateway_Walletdoc();

    $testmode = $pgwalletdoc->get_option( 'testmode' );

    $client_id = '';

    $client_secret = ( $pgwalletdoc->get_option( 'testmode' ) == 'yes' ) ? $pgwalletdoc->get_option( 'client_secret' ) : $pgwalletdoc->get_option( 'production_secret' );

    $api = new Walletdoc( $client_id, $client_secret, $testmode );

    $userId = get_user_option( '_walletdoc_customer_id', get_current_user_id() );

   
    $shop = get_option( 'woocommerce_shop_page_id' );

    $genrated_customer_id = '0001234' . get_current_user_id() . "$shop";

    $res =  $api->deleteCustomerToken( $genrated_customer_id, $token->get_token() );
    if($res->deleted){
        WC_Walletdoc_log( 'Card deleted by customer');
    }
    

} catch ( WalletdocWcValidationException $e ) {

    WC_Walletdoc_log( 'Validation Exception Occured with response ' . print_r( $e->getResponse(), true ) );

} catch ( Exception $e ) {

    WC_Walletdoc_log( $e->getMessage() );

}

