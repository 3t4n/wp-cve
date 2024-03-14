<?php
//include 'wp_objects_pdf_functions.php';
//$wp_objects_pdf = new wppg_objects_pdf_class;
if (isset($_POST['pdf-save']) && !empty($_POST['pdf-save']) && wp_verify_nonce('pdf_save_nonce', 'pdf_save_nonce')) {
	$wp_objects_type = isset( $_POST['pdf-choose'] ) ? sanitize_text_field($_POST['pdf-choose']) : '';
    $wp_objects_pdf_data = '';
    if ('PDF Button Image' == $wp_objects_type) {
		$wp_objects_pdf_data = isset($_POST['pdf-btn']) ? sanitize_text_field($_POST['pdf-btn']) : '';
    } else {
		$wp_objects_pdf_data = isset($_POST['pdf-text']) ? sanitize_text_field($_POST['pdf-text']) : '';
    }
    update_option('wp_objects_pdf_type', $wp_objects_type);
    update_option('wp_objects_pdf_data', $wp_objects_pdf_data);
    //$wp_objects_pdf->wp_objects_save_format('wp_objects_pdf_type',$wp_objects_type);
    //$wp_objects_pdf->wp_objects_save_format('wp_objects_pdf_data',$wp_objects_pdf_data);
}
if (!get_option('wp_objects_pdf_type') || !get_option('wp_objects_pdf_data')) {
    update_option('wp_objects_pdf_type', 'PDF_text');
    update_option('wp_objects_pdf_data', 'Download as PDF');
}