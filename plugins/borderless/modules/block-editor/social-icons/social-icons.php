<?php
/**
* Block Editor / Contact Information.
*/

// don't load directly
defined( 'ABSPATH' ) || exit;

function borderless_social_icons_script_register() {
    wp_enqueue_script(
        'borderless-block-editor-social-icons',
        plugin_dir_url(__FILE__).'social-icons.js',
        array('wp-blocks','wp-i18n','wp-editor')
    );
    
    wp_enqueue_style(
        'borderless-block-editor-social-icons-backend',
        plugin_dir_url(__FILE__).'social-icons-backend.css',
        array()
    );
}
add_action('enqueue_block_editor_assets','borderless_social_icons_script_register');

// Load assets for frontend
function borderless_social_icons_script_register_frontend() {

    wp_enqueue_style(
       'borderless-block-editor-social-icons-frontend',
       plugin_dir_url(__FILE__).'social-icons-frontend.css',
       array()
    );
 }
 add_action( 'wp_enqueue_scripts', 'borderless_social_icons_script_register_frontend' );


?>