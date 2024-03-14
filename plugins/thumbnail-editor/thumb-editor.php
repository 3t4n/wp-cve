<?php
/*
Plugin Name: Thumbnail Editor
Plugin URI: https://wordpress.org/plugins/thumbnail-editor/
Description: Manually <strong>Crop</strong> and <strong>Resize</strong> thumbnail images that are uploaded in the Media section.
Version: 2.3.3
Text Domain: thumbnail-editor
Domain Path: /languages
Author: aviplugins.com
Author URI: https://www.aviplugins.com/
*/

/*
	  |||||   
	<(`0_0`)> 	
	()(afo)()
	  ()-()
*/

define('THE_PLUGIN_DIR', 'thumbnail-editor');
define('THE_PLUGIN_PATH', dirname(__FILE__));

function plug_load_thumbnail_editor() {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    if (is_plugin_active('thumbnail-editor-pro/thumb-editor.php')) {
        wp_die('It seems you have <strong>Thumbnail Editor PRO</strong> plugin activated. Please deactivate that to continue.');
        exit;
    }
    include_once THE_PLUGIN_PATH . '/includes/class-editor-process.php';
    include_once THE_PLUGIN_PATH . '/includes/class-scripts.php';
    include_once THE_PLUGIN_PATH . '/includes/class-settings.php';
    include_once THE_PLUGIN_PATH . '/includes/class-filters.php';
    include_once THE_PLUGIN_PATH . '/thumb-widget.php';
    include_once THE_PLUGIN_PATH . '/functions.php';

    new Thumbnail_Settings;
    new Thumbnail_Editor_Process;
    new Thumbnail_Editor_Scripts;
    new Thumbnail_Filters;
}

class Thumbnail_Editor_Load {
    public function __construct() {
        plug_load_thumbnail_editor();
    }
}

new Thumbnail_Editor_Load;

add_action('widgets_init', function () {register_widget('Thumbnail_Widget');});

add_action('plugins_loaded', 'thumb_editor_afo_text_domain');

add_shortcode('thumb_image', 'get_thumb_image_sc');
add_shortcode('thumb_image_src', 'get_thumb_image_src_sc');