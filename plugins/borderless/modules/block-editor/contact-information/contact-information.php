<?php
/**
* Block Editor / Contact Information.
*/

// don't load directly
defined( 'ABSPATH' ) || exit;

function borderless_block_script_register() {
    wp_enqueue_script(
        'borderless-block-editor-contact-information',
        plugin_dir_url(__FILE__).'contact-information.js',
        array('wp-blocks','wp-i18n','wp-editor')
    );
    
    wp_enqueue_style(
        'borderless-block-editor-contact-information',
        plugin_dir_url(__FILE__).'contact-information.css',
        array()
    );
}
add_action('enqueue_block_editor_assets','borderless_block_script_register');

// Load assets for frontend
function borderless_block_script_register_frontend() {

    wp_enqueue_style(
       'borderless-block-editor-contact-information-frontend',
       plugin_dir_url(__FILE__).'contact-information-frontend.css',
       array()
    );
 }
 add_action( 'wp_enqueue_scripts', 'borderless_block_script_register_frontend' );


?>