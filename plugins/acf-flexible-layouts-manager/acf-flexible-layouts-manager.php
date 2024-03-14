<?php 
/*
Plugin Name: ACF Flexible Layouts Manager
Description: Add "Copy", "Duplicate", "Import" & "Paste" options for layout in ACF Flexible Content
Author: Valentin PELLEGRIN
Text Domain: acf-flexible-layouts-manager
Version: 1.1.6
Author URI: https://github.com/valentin-pellegrin
Licence: GPLv2
*/

if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

defined('ACF_FLM_PATH') || define('ACF_FLM_PATH', plugin_dir_path(__FILE__));
defined('ACF_FLM_URL') || define( 'ACF_FLM_URL', plugin_dir_url(__FILE__));

add_action('plugins_loaded', 'acf_flm_acf_exist');
function acf_flm_acf_exist(){

    if(!class_exists('acf_field'))
        return;
    
    load_plugin_textdomain('acf-flexible-layouts-manager', false, basename(dirname( __FILE__ )) . '/languages/');
    
    // Helpers
    require_once(ACF_FLM_PATH . 'helpers/add-button.php');
    
    // Ajax
    require_once(ACF_FLM_PATH . 'includes/ajax/ajax-copy.php');
    require_once(ACF_FLM_PATH . 'includes/ajax/ajax-duplicate.php');
    require_once(ACF_FLM_PATH . 'includes/ajax/ajax-paste.php');
    require_once(ACF_FLM_PATH . 'includes/ajax/ajax-select.php');
    
    // Includes
    require_once(ACF_FLM_PATH . 'includes/paste.php');
    require_once(ACF_FLM_PATH . 'includes/select.php');
    
    // Enqueue
    add_action('admin_enqueue_scripts', 'acf_flm_template_enqueue_admin_style');
    function acf_flm_template_enqueue_admin_style(){
        // Style
        wp_enqueue_style('_acf-flm-admin-style',                ACF_FLM_URL . 'assets/css/admin.css' );
        
        // Scripts
        wp_enqueue_script('_acf-flm-script-add-single-layout',  ACF_FLM_URL . 'assets/js/add-auto-single-layout.js',    array('jquery'), null);
        wp_enqueue_script('_acf-flm-script-move-button',        ACF_FLM_URL . 'assets/js/move-button.js',               array('jquery'), null);
        wp_enqueue_script('_acf-flm-script-copy',               ACF_FLM_URL . 'assets/js/copy.js',                      array('jquery'), null);
        wp_enqueue_script('_acf-flm-script-duplicate',          ACF_FLM_URL . 'assets/js/duplicate.js',                 array('jquery'), null);
        wp_enqueue_script('_acf-flm-script-paste',              ACF_FLM_URL . 'assets/js/paste.js',                     array('jquery'), null);
        wp_enqueue_script('_acf-flm-script-select',             ACF_FLM_URL . 'assets/js/select.js',                    array('jquery'), null);
    }
    
    // Languages
    require_once(ACF_FLM_PATH . 'includes/languages.php');
}