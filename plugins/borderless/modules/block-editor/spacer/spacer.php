<?php
/**
* Block Editor / Spacer.
*/

// don't load directly
defined( 'ABSPATH' ) || exit;

function borderless_spacer_script_register() {
    wp_enqueue_script(
        'borderless-block-editor-spacer',
        plugin_dir_url(__FILE__).'spacer.js',
        array('wp-blocks','wp-i18n','wp-editor')
    );
    
    wp_enqueue_style(
        'borderless-block-editor-spacer-backend',
        plugin_dir_url(__FILE__).'spacer-backend.css',
        array()
    );
}
add_action('enqueue_block_editor_assets','borderless_spacer_script_register');


?>