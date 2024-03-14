<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_enqueue_scripts', 'get_cash_resources' );
function get_cash_resources() {
  wp_register_style("get-cash-form-css", GET_CASH_PLUGIN_DIR_URL . 'includes/css/form.css');
  // wp_enqueue_style("get-cash-form-css"); // enqueued when in use at /shortcodes.php

	wp_register_script("get-cash-form-js", GET_CASH_PLUGIN_DIR_URL . 'includes/js/form.js', array('jquery'), null, true);
  // wp_enqueue_script("get-cash-form-js"); // enqueued when in use at /shortcodes.php
}

// Create CPT
add_action( 'init', 'get_cash_cpt' ) ;
function get_cash_cpt() {
  if ( !post_type_exists( 'gc-transactions' ) ) {
    register_POST_type( 'gc-transactions',
    array(
      'labels' => array(
          'name' => __( 'GC transactions' ),
          'singular' => __( 'GC transaction' )
      ),
      'public' => false,
      'show_ui' => true,
      'show_in_rest' => false,
      'has_archive' => false,
      'rewrite' => array('slug' => 'gc-transactions'),
      'show_in_rest' => false,
      'menu_icon' => 'dashicons-money-alt',
      'menu_position' => 20,
      )
    );
  }
}

// Create REST API route
add_action( 'rest_api_init', 'get_cash_form_route' ) ;
function get_cash_form_route() {
  register_rest_route( 'get-cash/v1', '/form', array(
    'methods' => 'POST',
    'callback' => 'get_cash_form_data',
    'permission_callback' => '__return_true',
  ) );
}
// Update order
function get_cash_form_data() {
    // $_POST = unserialize( $_POST );
    // print_r($_POST);
    // GetCashReceiverEmail, GetCashReceiverNo, GetCashReceiverName, GetCashNote, GetCashSenderEmail, GetCashSenderNo, GetCashSenderName, GetCashPaymentMethod, GetCashCurrency, GetCashAmount
    $receiver_email = isset( $_POST['GetCashReceiverEmail'] ) ? getcash_sanitize( $_POST['GetCashReceiverEmail'] ) : null;
    $receiver_no = isset( $_POST['GetCashReceiverNo'] ) ? getcash_sanitize( $_POST['GetCashReceiverNo'] ) : null;
    $receiver = isset( $_POST['GetCashReceiverName'] ) ? getcash_sanitize( $_POST['GetCashReceiverName'] ) : null;
    $note = isset( $_POST['GetCashNote'] ) ? getcash_sanitize( $_POST['GetCashNote'] ) : null;
    $sender_email = isset( $_POST['GetCashSenderEmail'] ) ? getcash_sanitize( $_POST['GetCashSenderEmail'] ) : null;
    $sender_no = isset( $_POST['GetCashSenderNo'] ) ? getcash_sanitize( $_POST['GetCashSenderNo'] ) : null;
    $sender = isset( $_POST['GetCashSenderName'] ) ? getcash_sanitize( $_POST['GetCashSenderName'] ) : null;
    $payment_method = isset( $_POST['GetCashPaymentMethod'] ) ? getcash_sanitize( $_POST['GetCashPaymentMethod'] ) : null;
    $currency = isset( $_POST['GetCashCurrency'] ) ? getcash_sanitize( $_POST['GetCashCurrency'] ) : null;
    $amount = isset( $_POST['GetCashAmount'] ) ? getcash_sanitize( $_POST['GetCashAmount'] ) : null;

    if ( $receiver && $sender && $payment_method && $currency && $amount ) {
        if ( post_type_exists( 'gc-transactions' ) ) {
          $subject = "Pending: {$amount} {$currency} from {$sender} via {$payment_method}";
          $message = "
            <ul>
              <li>Sender: {$sender}</li>
              <li>Sender Email: {$sender_email}</li>
              <li>Sender Phone: {$sender_no}</li>
              <li>Payment Method: {$payment_method}</li>
              <li>Amount: {$amount}</li>
              <li>Currency: {$currency}</li>
              <li>Note: {$note}</li>
              <br>
              <li>Receiver: {$receiver}</li>
              <li>Receiver Email: {$receiver_email}</li>
              <li>Receiver Phone: {$receiver_no}</li>
            </ul>";
            $get_cash_transaction = array(
            'post_title' => $subject,
            'post_content' => $message,
            'post_type' => 'gc-transactions',
            'post_status' => 'private',
            );
            wp_mail( $receiver_email, $subject, $message, array('Content-Type: text/html; charset=UTF-8', "Cc: $sender <$sender_email>", "Reply-To: $sender_email") );
            $transaction_POST_id = wp_insert_POST( $get_cash_transaction );
            if ($transaction_POST_id) {
                echo json_encode(array(
                    'status' => 'success',
                    'message' => 'Transaction recorded successfully',
                    'receiver' => $receiver,
                    'receiver_email' => $receiver_email,
                    'receiver_no' => $receiver_no,
                    'sender' => $sender,
                    'sender_email' => $sender_email,
                    'sender_no' => $sender_no,
                    'payment_method' => $payment_method,
                    'currency' => $currency,
                    'amount' => $amount,
                    'note' => $note,
                ));
                http_response_code(201);
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Recording transaction Failed',
                ));
                http_response_code(500);
            }
        }
    } else {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Recording transaction Failed',
        ));
        http_response_code(403);
    }
}

function getcash_sanitize( $input ) {
  return wp_kses_POST(htmlspecialchars(strip_tags(sanitize_text_field( $input ))));
}
