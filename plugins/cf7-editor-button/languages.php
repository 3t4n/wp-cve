<?php
if ( ! defined( 'ABSPATH' ) )
    exit;

if ( ! class_exists( '_WP_Editors' ) )
    require( ABSPATH . WPINC . '/class-wp-editor.php' );

function ari_cf7_editor_button_tinymce_plugin_translation() {
    $strings = array(
        'dialog_title' => __( 'Contact Form 7 Shortcode', 'contact-form-7-editor-button' ),

        'cancel' => __( 'Cancel', 'contact-form-7-editor-button' ),

        'select_item' => __( 'Select form', 'contact-form-7-editor-button' ),

        'button_title' => __( 'CF7', 'contact-form-7-editor-button' ),

        'default_form_item' => __( '- Select form -', 'contact-form-7-editor-button' ),

        'button_tooltip' => __( 'Insert Contact Form 7 item', 'contact-form-7-editor-button' ),

        'howto' => __( 'Select a form to insert shortcode into editor.', 'contact-form-7-editor-button' ),
    );

    $translated = 'tinyMCE.addI18n("' . _WP_Editors::$mce_locale . '.ari_cf7_button", ' . json_encode( $strings ) . ");\n";

    return $translated;
}

$strings = ari_cf7_editor_button_tinymce_plugin_translation();