<?php
/**
 * Plugin Name: Gravity Forms Prevent Duplicates
 * Plugin URI: https://wordpress.org/plugins/gf-prevent-duplicates/
 * Description: Prevent duplicate submissions for all GF forms on your site.
 * Version: 1.2.1
 * Author: MaxiCharts
 * Author URI: https://maxicharts.com
 * Text Domain: gf-prevent-duplicates
 * Domain Path: /languages
 */
if (! defined('ABSPATH')) {
    die();
}

$path_to_class = sprintf("%s/admin/gfpd_admin_settings.php", dirname(__FILE__));
include_once ($path_to_class);

if (! class_exists('GF_Prevent_Duplicates')) {
    
    class GF_Prevent_Duplicates
    {
        
        function __construct()
        {
            add_action("wp_enqueue_scripts", array(
                $this,
                "gfpd_load_scripts"
            ));
            
            add_action('admin_menu', array(
                $this,
                'add_admin_menus'
            ));
        }
        
        function gfpd_load_scripts()
        {
            if (! is_admin()) {
                $jsScript = plugins_url("js/gfpreventduplicates.js", __FILE__);
                // wp_register_script( string $handle, string $src, array $deps = array(), string|bool|null $ver = false, bool $in_footer = false )
                
                wp_register_script('gfpd-js', $jsScript, array(
                    'jquery'
                ));
                
                // Localize the script with new data
                $translation_array = array(
                    'button_message' => esc_html__('Processing, please wait...', 'gf-prevent-duplicates'),
                    'currently_uploading' => esc_html__('Please wait for the uploading to complete', 'gf-prevent-duplicates'),
                    'excluded_form_ids' => get_option('gfpd_excluded_ids')
                    // 'a_value' => '10'
                );
                wp_localize_script( 'gfpd-js', 'gfpd_strings', $translation_array );
                
                wp_enqueue_script('gfpd-js');
            }
        }
        function add_admin_menus() {
            add_options_page ( 'GF Prevent Duplicates', 'GFPD', 'delete_others_pages', 'gfpd_settings', array (
                $this,
                'showGFPDSettings'
            ) );
        }
        function showGFPDSettings() {
            echo "<h2>Gravity Forms Prevent Duplicates Settings</h2>";
            GFPDADMIN::gfpd_admin_settings();
        }
    }
}
$obj = new GF_Prevent_Duplicates();