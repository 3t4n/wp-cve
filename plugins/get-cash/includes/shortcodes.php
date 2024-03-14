<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
add_action( 'wp_enqueue_scripts', 'get_cash_qrcode_scripts' );
function get_cash_qrcode_scripts()
{
    $get_cash_options = get_option( 'get_cash_option_name' );
    // Array of All Options
    // print_r($get_cash_options);
    $qrcode_styling = 'qr-code-styling.min.js';
    
    if ( !wp_script_is( $qrcode_styling, 'enqueued' ) ) {
        wp_register_script( $qrcode_styling, GET_CASH_PLUGIN_DIR_URL . 'includes/js/' . $qrcode_styling );
        wp_enqueue_script( $qrcode_styling );
        // wp_enqueue_script( 'get_cash_qrcode_styling', GET_CASH_PLUGIN_DIR_URL . 'includes/js/qr-code-styling.min.js' );
    }
    
    wp_enqueue_script(
        'get_cash_qrcode',
        GET_CASH_PLUGIN_DIR_URL . 'includes/js/qrcode.js',
        array( 'jquery', $qrcode_styling ),
        null,
        true
    );
    $copy_js = 'copy.js';
    
    if ( !wp_script_is( $copy_js, 'enqueued' ) ) {
        wp_register_script(
            $copy_js,
            GET_CASH_PLUGIN_DIR_URL . 'includes/js/' . $copy_js,
            array( 'jquery', 'get_cash_qrcode' ),
            null,
            true
        );
        wp_enqueue_script( $copy_js );
        // wp_enqueue_script( 'get_cash_copy', GET_CASH_PLUGIN_DIR_URL . 'includes/js/copy.js', array('jquery'), null, true);
    }
    
    $get_cash_qrcode = array(
        "url"               => GET_CASH_PLUGIN_DIR_URL . "images/",
        "width"             => $get_cash_options['QRwidth'] ?? 150,
        "height"            => $get_cash_options['QRheight'] ?? 150,
        "darkcolor"         => $get_cash_options['QRdarkcolor'] ?? '#000000',
        "lightcolor"        => $get_cash_options['QRlightcolor'] ?? '#ffffff',
        "dotsType"          => $get_cash_options['QRdotsType'] ?? 'dots',
        "cornersSquareType" => $get_cash_options['QRcornersSquareType'] ?? 'extra-rounded',
        "cornersDotType"    => $get_cash_options['QRcornersDotType'] ?? 'square',
        "backgroundcolor"   => $get_cash_options[' QRbackgroundcolor'] ?? '#ffffff',
    );
    wp_localize_script( 'get_cash_qrcode', 'get_cash_qrcode', $get_cash_qrcode );
    // // jquery-dialog on checkout/thankyou with countdown https://jqueryui.com/demos/dialog/
    // wp_enqueue_script( 'jquery-ui-dialog' );
}

// is_array($get_cash_options) && array_key_exists($option, $get_cash_options)
add_shortcode( 'cashapp', 'get_cash_cashapp_shortcode' );
function get_cash_cashapp_shortcode( $atts )
{
    $get_cash_options = get_option( 'get_cash_option_name' );
    
    if ( !is_array( $get_cash_options ) || empty($get_cash_options['receiver_cash_app']) ) {
        $receiver_cash_app = '';
        return __( 'Get Cash plugin information missing', GET_CASH_PLUGIN_TEXT_DOMAIN );
    } else {
        $receiver_cash_app = $get_cash_options['receiver_cash_app'];
    }
    
    $brand = 'cashapp';
    $display = 'display: inline-block;';
    $shadow = 'box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);';
    $button_text = esc_html__( 'Cash App Me', GET_CASH_PLUGIN_TEXT_DOMAIN );
    $url = 'https://cash.app/' . esc_attr( wp_kses_post( $receiver_cash_app ) );
    $src = "https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=" . urlencode( $url );
    $css = $display . $shadow;
    return get_full_shortcode_html(
        $url,
        $src,
        $brand,
        $button_text,
        $css,
        $qr
    );
}

add_shortcode( 'venmo', 'get_cash_venmo_shortcode' );
function get_cash_venmo_shortcode( $atts )
{
    $get_cash_options = get_option( 'get_cash_option_name' );
    // Array of All Options
    
    if ( !is_array( $get_cash_options ) || empty($get_cash_options['receiver_venmo']) ) {
        $receiver_venmo = '';
        return __( 'Get Cash plugin information missing', GET_CASH_PLUGIN_TEXT_DOMAIN );
    } else {
        $receiver_venmo = $get_cash_options['receiver_venmo'];
    }
    
    $brand = 'venmo';
    $display = 'display: inline-block;';
    $shadow = 'box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);';
    $button_text = esc_html__( 'Venmo Me', GET_CASH_PLUGIN_TEXT_DOMAIN );
    $url = 'https://venmo.com/' . esc_attr( wp_kses_post( $receiver_venmo ) ) . '?txn=pay&note=Thank you';
    $src = "https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=" . urlencode( $url );
    $css = $display . $shadow;
    return get_full_shortcode_html(
        $url,
        $src,
        $brand,
        $button_text,
        $css,
        $qr
    );
}

add_shortcode( 'paypal', 'get_cash_paypal_shortcode' );
function get_cash_paypal_shortcode( $atts )
{
    $get_cash_options = get_option( 'get_cash_option_name' );
    // Array of All Options
    
    if ( !is_array( $get_cash_options ) || empty($get_cash_options['receiver_paypal']) ) {
        $receiver_paypal = '';
        return __( 'Get Cash plugin information missing', GET_CASH_PLUGIN_TEXT_DOMAIN );
    } else {
        $receiver_paypal = $get_cash_options['receiver_paypal'];
    }
    
    $brand = 'paypal';
    $display = 'display: inline-block;';
    $shadow = 'box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);';
    $button_text = esc_html__( 'Paypal Me', GET_CASH_PLUGIN_TEXT_DOMAIN );
    $url = 'https://paypal.me/' . esc_attr( wp_kses_post( $receiver_paypal ) );
    $src = "https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=" . urlencode( $url );
    $css = $display . $shadow;
    return get_full_shortcode_html(
        $url,
        $src,
        $brand,
        $button_text,
        $css,
        $qr
    );
}

add_shortcode( 'zelle', 'get_cash_zelle_shortcode' );
function get_cash_zelle_shortcode( $atts )
{
    $get_cash_options = get_option( 'get_cash_option_name' );
    // Array of All Options
    $receiver_zelle_number = $get_cash_options['receiver_no'];
    $receiver_zelle_name = $get_cash_options['receiver_owner'];
    $receiver_zelle_email = $get_cash_options['receiver_email'];
    if ( empty($receiver_zelle_number) && empty($receiver_zelle_email) ) {
        return __( 'Get Cash plugin information missing', GET_CASH_PLUGIN_TEXT_DOMAIN );
    }
    $display = 'display: inline-block;';
    $shadow = 'box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);';
    $css = $display . $shadow;
    $zelle_html = '<div class="get-cash-zelle" style="padding: 1em; max-width: 75%; text-align: center; border-radius: 10px;' . esc_html( $css ) . '">' . '<p>' . wp_kses_post( sprintf( __( 'Send %s via %s or from your bank', GET_CASH_PLUGIN_TEXT_DOMAIN ), $amount, '<a style="color: #6d1fd4" href="https://zellepay.com/" target="_blank">Zelle</a>' ) ) . '.</p>' . '<p>' . esc_html__( 'Here are the Zelle details you should know for the transfer', GET_CASH_PLUGIN_TEXT_DOMAIN ) . ':</p>' . '<p>' . '<div>' . sprintf( esc_html__( '%s Name', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverName" type="text" readonly value="' . esc_html( $receiver_zelle_name ) . '"><span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span><br></div>';
    if ( !empty($receiver_zelle_email) ) {
        $zelle_html .= '<div>' . sprintf( esc_html__( '%s Email', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverEmail" type="text" readonly value="' . esc_html( $receiver_zelle_email ) . '"><span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span><br></div>';
    }
    if ( !empty($receiver_zelle_number) ) {
        $zelle_html .= '<div>' . sprintf( esc_html__( '%s Phone', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverNo" type="text" readonly value="' . esc_html( $receiver_zelle_number ) . '"><span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span></div>';
    }
    // $zelle_html .= sprintf(  esc_html__( '%s Name', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <strong>'. esc_html( $receiver_zelle_name ). '</strong><br>' .
    // sprintf(  esc_html__( '%s Email', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <strong>'. esc_html( $receiver_zelle_email ). '</strong><br>' .
    // sprintf(  esc_html__( '%s Phone', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <strong>'. esc_html( $receiver_zelle_number ). '</strong>';
    $zelle_html .= '</p></div>';
    return $zelle_html;
}

add_shortcode( 'get-cash', 'get_all_cash_shortcode' );
function get_all_cash_shortcode( $atts )
{
    $get_cash_options = get_option( 'get_cash_option_name' );
    return '<p style="padding: 2em; text-align: center; font-weight: bold">' . __( 'Upgrade to unlock this shortcode', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '</p>';
    $paypal = null;
    $cashapp = null;
    $venmo = null;
    $zelle = null;
    $display = 'display: inline-block;';
    $shadow = 'box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);';
    return '<p style="padding: 2em; text-align: center; font-weight: bold">' . __( 'Please upgrade to the PRO version to use the shortcode [get-cash]', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '</p>';
    $css = $display . $shadow;
    $paypal_html = '';
    $venmo_html = '';
    $cashapp_html = '';
    $zelle_html = '';
    $receiver_paypal = $get_cash_options['receiver_paypal'];
    
    if ( $receiver_paypal ) {
        $text = esc_html__( 'Paypal Me', GET_CASH_PLUGIN_TEXT_DOMAIN );
        $brand = 'paypal';
        $url = 'https://paypal.me/' . esc_attr( wp_kses_post( $receiver_paypal ) );
        $src = "https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=" . urlencode( $url );
        $paypal_html = get_shortcode_html(
            $url,
            $src,
            $text,
            $brand,
            $paypalqr
        );
    }
    
    $receiver_venmo = $get_cash_options['receiver_venmo'];
    
    if ( $receiver_venmo ) {
        $text = esc_html__( 'Venmo Me', GET_CASH_PLUGIN_TEXT_DOMAIN );
        $brand = 'venmo';
        $url = 'https://venmo.com/' . esc_attr( wp_kses_post( $receiver_venmo ) ) . '?txn=pay&note=Thank you';
        $src = "https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=" . urlencode( $url );
        $venmo_html = get_shortcode_html(
            $url,
            $src,
            $text,
            $brand,
            $venmoqr
        );
    }
    
    $receiver_cash_app = $get_cash_options['receiver_cash_app'];
    
    if ( $receiver_cash_app ) {
        $text = esc_html__( 'Cash App Me', GET_CASH_PLUGIN_TEXT_DOMAIN );
        $brand = 'cashapp';
        $url = 'https://cash.app/' . esc_attr( wp_kses_post( $receiver_cash_app ) );
        $src = "https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=" . urlencode( $url );
        $cashapp_html = get_shortcode_html(
            $url,
            $src,
            $text,
            $brand,
            $cashappqr
        );
    }
    
    $receiver_zelle_name = $get_cash_options['receiver_owner'];
    $receiver_zelle_email = $get_cash_options['receiver_email'];
    $receiver_zelle_number = $get_cash_options['receiver_no'];
    
    if ( $receiver_zelle_number || $receiver_zelle_email ) {
        $zelle_html = '<div style="display: block; margin: 0 auto; padding: 1em; max-width: 75%; text-align: center; border-radius: 10px;">' . '<p>' . wp_kses_post( sprintf( __( 'Send %s via %s or from your bank', GET_CASH_PLUGIN_TEXT_DOMAIN ), esc_attr( wp_kses_post( $zelle ) ), '<a style="color: #6d1fd4" href="https://zellepay.com/" target="_blank">Zelle</a>' ) ) . '.</p>' . '<p>' . esc_html__( 'Here are the Zelle details you should know for the transfer', GET_CASH_PLUGIN_TEXT_DOMAIN ) . ':</p>' . '<p>' . '<div>' . sprintf( esc_html__( '%s Name', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverName" type="text" readonly value="' . esc_html( $receiver_zelle_name ) . '"><span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span><br></div>';
        if ( !empty($receiver_zelle_email) ) {
            $zelle_html .= '<div>' . sprintf( esc_html__( '%s Email', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverEmail" type="text" readonly value="' . esc_html( $receiver_zelle_email ) . '"><span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span><br></div>';
        }
        if ( !empty($receiver_zelle_number) ) {
            $zelle_html .= '<div>' . sprintf( esc_html__( '%s Phone', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverNo" type="text" readonly value="' . esc_html( $receiver_zelle_number ) . '"><span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span></div>';
        }
        // $zelle_html .= sprintf(  esc_html__( '%s Name', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <strong>'. esc_html( $receiver_zelle_name ). '</strong><br>' .
        // sprintf(  esc_html__( '%s Email', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <strong>'. esc_html( $receiver_zelle_email ). '</strong><br>' .
        // sprintf(  esc_html__( '%s Phone', GET_CASH_PLUGIN_TEXT_DOMAIN ), 'Zelle' ) . ': <strong>'. esc_html( $receiver_zelle_number ). '</strong>';
        $zelle_html .= '</p></div>';
    }
    
    return '<div class="get-cash" style="max-width: 75%; padding: 1em; margin: auto; text-align: center; border-radius: 10px;' . esc_html( $css ) . '">' . '<h3>' . wp_kses_post( $title ) . '</h3><p>' . $cashapp_html . $venmo_html . $paypal_html . '</p>' . $zelle_html . '</div>';
}

add_shortcode( 'get-cash-form', 'get_cash_form_shortcode' );
function get_cash_form_shortcode( $atts )
{
    return '<p style="padding: 2em; text-align: center; font-weight: bold">' . __( 'Upgrade to unlock this shortcode', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '</p>';
    $get_cash_options = get_option( 'get_cash_option_name' );
    // Array of All Options
    // $receiver = $get_cash_options['receiver']; // ' . $receiver . '
    $receiver_no = $get_cash_options['receiver_no'];
    $receiver_email = $get_cash_options['receiver_email'];
    $cashapp = $get_cash_options['receiver_cash_app'];
    $paypal = $get_cash_options['receiver_paypal'];
    $venmo = $get_cash_options['receiver_venmo'];
    $zelle = $get_cash_options['receiver_owner'];
    extract( shortcode_atts( array(
        'title'           => __( 'Send Cash', GET_CASH_PLUGIN_TEXT_DOMAIN ),
        'subtitle'        => __( 'Send Cash', GET_CASH_PLUGIN_TEXT_DOMAIN ),
        'amount'          => '',
        'currency'        => 'USD',
        'receiver'        => '',
        'receiver_method' => '',
        'payment_options' => 'Cash App, Venmo, Paypal, Zelle',
        'button'          => 'Generate Payment Link',
        'class'           => '',
    ), $atts ) );
    $all_payments = explode( ',', $payment_options );
    $form_html = '';
    $form_html .= '<fieldset class="get-cash-form">' . '<h3 class="get-cash-form-fields get-cash-form-title ' . $class . '">' . $title . '</h3>' . '<p class="get-cash-form-fields get-cash-form-subtitle">' . $subtitle . '</p>' . '<form id="get-cash-form-id" class="get-cash-form-fields get-cash-form-box form-row form-row-wide" action="' . site_url() . '/wp-json/get-cash/v1/form" method="post">
         <h4 class="get-cash-form-fields get-cash-form-section-title">' . esc_html__( 'Transaction Details', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '</h4>
          <div class="form-row form-row-wide">
             <label class="get-cash-form-fields get-cash-form-label">' . esc_html__( 'Amount to send', GET_CASH_PLUGIN_TEXT_DOMAIN ) . ': <span class="required">*</span></label>
              <input class="get-cash-form-fields get-cash-form-input" name="GetCashAmount" type="number" placeholder="' . esc_html__( 'Insert Amount', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '" value="' . $amount . '" required>
              <input class="get-cash-form-fields get-cash-form-input" name="GetCashCurrency" type="text" placeholder="' . esc_html__( 'Insert Currency', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '" value="' . $currency . '" required>
         </div>

         <div class="form-row form-row-wide">
             <label class="get-cash-form-fields get-cash-form-label" for="GetCashPaymentMethod">' . esc_html__( 'Please select a payment method you would like to use', GET_CASH_PLUGIN_TEXT_DOMAIN ) . ' <span class="required">*</span></label>

             <div class="get-cash-d-flex get-cash-align-items-center get-cash-flex-wrap">';
    foreach ( $all_payments as $payment_option ) {
        $form_html .= '<div class="get-cash-form-check get-cash-d-flex get-cash-align-items-center get-cash-me-3" data-bs-toggle="tooltip" title="' . esc_attr( ucfirst( trim( $payment_option ) ) ) . '">
                     <input id="' . esc_attr( strtolower( str_replace( ' ', '', $payment_option ) ) ) . '"
                     data-receiver="' . $get_cash_options['receiver_' . esc_attr( strtolower( str_replace( " ", "_", trim( $payment_option ) ) ) )] . '"
                     class="get-cash-form-check-input get-cash-me-2"
                     type="radio" name="GetCashPaymentMethod" required
                     value="' . esc_attr( strtolower( str_replace( ' ', '', $payment_option ) ) ) . '" />
                     <label class="get-cash-form-check-label" for="' . esc_attr( strtolower( str_replace( ' ', '', $payment_option ) ) ) . '"><img class="get-cash-brand_logo" src="' . esc_url( GET_CASH_PLUGIN_DIR_URL . 'images/' . esc_attr( strtolower( str_replace( ' ', '', $payment_option ) ) ) . '.png' ) . '" width="50px" height="50px" /></label>
                 </div>';
        //  $form_html .= '<div class="get-cash-form-check get-cash-d-flex get-cash-align-items-center get-cash-me-3" data-bs-toggle="tooltip" title="' . esc_attr(ucfirst(trim($payment_option))) . '"><input id="' . esc_attr(strtolower(str_replace(' ', '', $payment_option))) . '" class="get-cash-form-check-input get-cash-me-2" type="radio" name="GetCashPaymentMethod" required value="' . esc_attr(strtolower(str_replace(' ', '', $payment_option))) . '" /><label class="get-cash-form-check-label" for="' . esc_attr(strtolower(str_replace(' ', '', $payment_option))) . '"><img class="get-cash-brand_logo" src="' . esc_url( GET_CASH_PLUGIN_DIR_URL . 'images/' . esc_attr(strtolower(str_replace(' ', '', $payment_option))) . '.png' ) . '" width="50px" height="50px" /></label></div>';
    }
    $form_html .= '</div>

         </div>

         <h4 class="get-cash-form-fields get-cash-form-section-title">' . esc_html__( 'Sender Information', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '</h4>
          <div class="form-row form-row-wide">
              <label class="get-cash-form-fields get-cash-form-label" id="get-cash-sender">' . esc_html__( 'Sender', GET_CASH_PLUGIN_TEXT_DOMAIN ) . ' <span class="required">*</span></label>
              <input class="get-cash-form-fields get-cash-form-input" name="GetCashSenderName" type="text" placeholder="' . esc_html__( 'Insert Sender', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '" required>
              </div>
          <div class="form-row form-row-wide">
              <label class="get-cash-form-fields get-cash-form-label">' . esc_html__( 'Sender Phone Number', GET_CASH_PLUGIN_TEXT_DOMAIN ) . ' <span class="required">*</span></label>
              <input class="get-cash-form-fields get-cash-form-input" name="GetCashSenderNo" type="text" min="111111" size="12" placeholder="+1234567890" required>
              </div>
          <div class="form-row form-row-wide">
              <label class="get-cash-form-fields get-cash-form-label">' . esc_html__( 'Sender Email', GET_CASH_PLUGIN_TEXT_DOMAIN ) . ' <span class="required">*</span></label>
              <input class="get-cash-form-fields get-cash-form-input" name="GetCashSenderEmail" type="email" placeholder="email@example.com" required>
              </div>
          <div class="form-row form-row-wide">
              <label class="get-cash-form-fields get-cash-form-label">' . esc_html__( 'Reference/Transaction Note or ID', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '</label>
                  <input class="get-cash-form-fields get-cash-form-input" name="GetCashNote" type="text" placeholder="' . esc_html__( 'Insert Note', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '" required>
              </div>

         <h4 class="get-cash-form-fields get-cash-form-section-title">' . esc_html__( 'Receiver Information', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '</h4>
            <div id="get-cash-form-receiver"></div>';
    if ( !empty($receiver_no) ) {
        $form_html .= '<div class="form-row form-row-wide">
                <label class="get-cash-form-fields get-cash-form-label">' . esc_html__( 'Receiver Phone Number', GET_CASH_PLUGIN_TEXT_DOMAIN ) . ':</label>
                <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverNo" type="text" readonly value="' . $receiver_no . '">
                <span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span>
            </div>';
    }
    if ( !empty($receiver_email) ) {
        $form_html .= '<div class="form-row form-row-wide">
                <label class="get-cash-form-fields get-cash-form-label">' . esc_html__( 'Receiver Email', GET_CASH_PLUGIN_TEXT_DOMAIN ) . ':</label>
                <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverEmail" type="email" readonly value="' . $receiver_email . '">
                <span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span>
            </div>';
    }
    $form_html .= '<div class="form-row form-row-wide">';
    // $payment_method = ""; // "Cash App";
    // if ( $payment_method ) {
    //     $form_html .= '<button id="get-cash-form-submit" class="get-cash-form-fields button btn" type="submit">' . esc_html ( sprintf ( __( 'Generate via %s', GET_CASH_PLUGIN_TEXT_DOMAIN ), $payment_method )) . '</button>';
    // } else {
    //     $form_html .= '<button id="get-cash-form-submit" class="get-cash-form-fields button btn" type="submit">' . esc_html__( 'Generate', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '</button>';
    // }
    $form_html .= '<button id="get-cash-form-submit" class="get-cash-form-fields button btn" type="submit">' . esc_html( $button ) . '</button>
         </div>
    </form><div id="get-cash-form-result"></div></fieldset>';
    wp_enqueue_style( 'get-cash-form-css' );
    wp_enqueue_script( 'get-cash-form-js' );
    wp_add_inline_script( 'get-cash-form-js', 'const site_url = "' . site_url() . '"; const cashapp = "' . $cashapp . '"; const venmo = "' . $venmo . '"; const zelle = "' . $zelle . '"; const paypal = "' . $paypal . '"; ' );
    wp_localize_script( 'get-cash-form-js', 'get_cash_form_object', array(
        'site_url'        => site_url(),
        'cashapp'         => $cashapp,
        'venmo'           => $venmo,
        'zelle'           => $zelle,
        'paypal'          => $paypal,
        'title'           => $title,
        'subtitle'        => $subtitle,
        'amount'          => $amount,
        'currency'        => $currency,
        'receiver'        => $receiver,
        'receiver_method' => $receiver_method,
        'payment_options' => $payment_options,
        'button'          => $button,
        'class'           => $class,
    ) );
    wp_add_inline_script( 'get-cash-form-js', 'const get_cash_form_object = ' . json_encode( array(
        'site_url'        => site_url(),
        'cashapp'         => $cashapp,
        'venmo'           => $venmo,
        'zelle'           => $zelle,
        'paypal'          => $paypal,
        'title'           => $title,
        'subtitle'        => $subtitle,
        'amount'          => $amount,
        'currency'        => $currency,
        'receiver'        => $receiver,
        'receiver_method' => $receiver_method,
        'payment_options' => $payment_options,
        'button'          => $button,
        'class'           => $class,
    ) ), 'before' );
    return $form_html;
}

function get_full_shortcode_html(
    $url,
    $src,
    $brand,
    $button_text,
    $css,
    $qr = 'yes',
    $design_template = 1
)
{
    $html = '<a class="get_cash_qrcode" href="' . esc_attr( wp_kses_post( $url ) ) . '" target="_blank">' . '<img style="float: none!important; max-height:100px!important; max-width:100px!important;" alt="' . esc_attr( wp_kses_post( $brand ) ) . ' link" src="' . $src . '"></a>';
    return '<div class="get-cash-' . esc_attr( $brand ) . '" style="padding: 10px; max-width: 250px; text-align: center; border-radius: 10px;' . esc_html( $css ) . '"><p>' . wp_kses_post( $button_text ) . '</p>' . wp_kses_post( $html ) . '</div>';
    return $html;
}

function get_shortcode_html(
    $url,
    $src,
    $text,
    $brand,
    $qr = 'yes',
    $design_template = 1
)
{
    $html = '<p>' . wp_kses_post( $text ) . '</p>' . '<a class="get_cash_qrcode" href="' . esc_attr( wp_kses_post( $url ) ) . '" target="_blank">' . '<img style="float: none!important; max-height:100px!important; max-width:100px!important;" alt="' . esc_attr( wp_kses_post( $brand ) ) . ' link" src="' . $src . '">' . '</a></p>';
    return $html;
}
