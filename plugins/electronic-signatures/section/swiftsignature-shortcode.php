<?php

/*
 *       Signature plugin's shortcodes
 */

/**
 *      shortcode: swiftsign_capture_name
 *      Capture name from swiftform return url and display it
 *          - No attributes
 *          - swiftform callback url: URL?c=123456&confirm=1&firstname=FirstNameHere
 */
add_shortcode('swiftsign_capture_name', 'ssign_capture_name_callback');

function ssign_capture_name_callback() {
    if (isset($_GET['c']) && !empty($_GET['c']) && isset($_GET['confirm']) && !empty($_GET['confirm']) && $_GET['confirm'] == 1) {
        return ucfirst(strtolower(sanitize_text_field($_GET['firstname'])));
    }
}


/**
 *      shortcode: [swiftsign_pdf doc=""]
 *      Display pdf image with link
 *      - doc : doc id
 */
add_shortcode('swiftsign_pdf', 'swiftsign_pdf_callback');

function swiftsign_pdf_callback($atts) {
    global $wpdb;
    $tbl_pdf = $wpdb->prefix . 'ssign_pdfs';

    $op = "";
    $a = shortcode_atts(
            array(
        'doc' => '',
            ), $atts);
    extract($a);

    $get_pdf_data = $wpdb->get_row("SELECT * FROM `$tbl_pdf` WHERE `pdf_id`=$doc");

    if (!empty($get_pdf_data)) {
        $pdf_name = !empty($get_pdf_data->pdf_name) ? $get_pdf_data->pdf_name : "PDF";

        $op.='<a href="' . $get_pdf_data->pdf_url . '">' . $pdf_name . '</a>';
    }
    return $op;
}