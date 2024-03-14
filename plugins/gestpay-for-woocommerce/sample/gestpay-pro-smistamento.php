<?php

/**
 * Gestpay for WooCommerce
 *
 * Copyright: © 2013-2016 Mauro Mascia (info@mauromascia.com)
 * Copyright: © 2017-2021 Axerve S.p.A. - Gruppo Banca Sella (https://www.axerve.com - ecommerce@sella.it)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/*
 * This file is an example useful when someone want to use the same
 * GestPay Pro account on more than one site. In this example a new
 * parameter called "SITE" must be defined into the backoffice.
 * Each site must have the same IP address in order to be accepted
 * as source of payment from the same GestPay account.
 * Here you have to adjust the code as your needs, because this file
 * is for example purposes only.
 */

if ( isset( $_GET['a'] ) && isset( $_GET['b'] ) ) {

  // Change this if using testing or real environment
  $is_test = false; // true

  // Set parameters to be decrypted
  $params = new stdClass();
  $params->shopLogin = $_GET['a'];
  $params->CryptedString = $_GET['b'];

  $crypt_url = $is_test
    ? "https://sandbox.gestpay.net/gestpay/GestPayWS/WsCryptDecrypt.asmx?WSDL"
    : "https://ecomms2s.sella.it/gestpay/GestPayWS/WSCryptDecrypt.asmx?WSDL";

  try {
    $client = new SoapClient( $crypt_url );
  }
  catch ( Exception $e ) {
    echo "Soap Client error: " . $e->getMessage();
    exit( 1 );
  }

  try {
    $objectresult = $client->Decrypt( $params );
  }
  catch ( Exception $e ) {
    echo "GestPay Decrypt error: " . $e->getMessage();
    exit( 1 );
  }

  $xml = simplexml_load_string( $objectresult->DecryptResult->any );

  $src = ( string ) $xml->CustomInfo; // for example "SITE=something"

  if ( ! empty( $src ) && $src == 'SITE=site1' ) {
    $url = "http://www.site1.it/";
  }
  else {
    $url = "http://www.site2.it/";
  }

  // Process the Payment into the right website.
  $full_url = $url . "?wc-api=WC_Gateway_Gestpay&a=" . $params->shopLogin . "&b=" . $params->CryptedString;

  if ( isset( $_GET['s2s'] ) ) {
    // s2s call, process in background
    $full_url = $full_url . "&s2s=1";
    $contents = file_get_contents( $full_url );
  }
  else {
    // Redirect the customer the right website.
    header( "Location: " . $full_url );
  }
}
?>
