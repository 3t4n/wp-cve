<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Image Gallery Element
 *
 */
class Powerfolio_Shortcode_Generator {	

    public function __construct() {
        // allow users to disable this feature if needed
        $shortcode_generator_enabled = apply_filters( 'powerfolio_shortcode_generator_enabled', true );

        if ( $shortcode_generator_enabled == true ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_shortcode_generator_scripts' ), 10, 2 );
            add_action('admin_head', array( $this, 'create_shortcode_button' ), 10, 2 );
        }        
    }
	
    public function enqueue_shortcode_generator_scripts($hook) {
        if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
            return;
        }

        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' );

        // Enqueue Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

        // Enqueue the shortcode generator script
        wp_enqueue_script( 'powerfolio-shortcode-generator', plugin_dir_url(__FILE__) . '../assets/js/powerfolio-shortcode-generator.js', array( 'jquery', 'wp-tinymce' ), false, true );

        // Prepare hover_options and column_options
        $hover_options = Powerfolio_Common_Settings::get_hover_options();
        $formatted_hover_options = array();
        foreach ($hover_options as $key => $value) {
            if (!is_bool($value)) {
                $formatted_hover_options[] = array('text' => $value, 'value' => $key);
            }
        }

        // Columns
        $column_options = Powerfolio_Common_Settings::get_column_options();
        $formatted_column_options = array();
        foreach ($column_options as $key => $value) {
            if (!is_bool($value)) {
                $formatted_column_options[] = array('text' => $value, 'value' => $key);
            }
        }

        // Prepare style_options
        $style_options = Powerfolio_Common_Settings::get_grid_options();
        $formatted_style_options = array();
        foreach ($style_options as $key => $value) {
            if (!is_bool($value)) {
                $formatted_style_options[] = array('text' => $value, 'value' => $key);
            }
        }

        // Prepare linkto_options
        $linkto_options = Powerfolio_Common_Settings::get_lightbox_options();
        $formatted_linkto_options = array();
        foreach ($linkto_options as $key => $value) {
            if (!is_bool($value)) {
                $formatted_linkto_options[] = array('text' => $value, 'value' => $key);
            }
        }

        // Call the Powerfolio_Common_Settings::get_upgrade_message() method and store the HTML content in a variable
        $upgrade_message = Powerfolio_Common_Settings::get_upgrade_message('shortcode');

        // Localize the script to pass data from PHP to JavaScript
        wp_localize_script('powerfolio-shortcode-generator', 'powerfolio_settings', array(
            'hover_options' => $formatted_hover_options,
            'column_options' => $formatted_column_options,
            'style_options' => $formatted_style_options,
            'linkto_options' => $formatted_linkto_options,
            'upgrade_message' => $upgrade_message, 
        ));

    }

    
    public function create_shortcode_button() {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }
        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins',  array( $this, 'add_powerfolio_tinymce_plugin' ) );
            add_filter('mce_buttons', array( $this, 'register_powerfolio_shortcode_button' ) );
        }
    }

    public function add_powerfolio_tinymce_plugin($plugin_array) {
        $plugin_array['powerfolio_button'] = plugin_dir_url(__FILE__) . '../assets/js/powerfolio-shortcode-generator.js';
        return $plugin_array;
    }

    public function register_powerfolio_shortcode_button($buttons) {
        array_push($buttons, 'powerfolio_button');
        return $buttons;
    }
}

new Powerfolio_Shortcode_Generator();