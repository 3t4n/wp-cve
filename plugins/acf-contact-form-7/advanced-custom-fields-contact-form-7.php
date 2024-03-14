<?php
/*
Plugin Name: Advanced Custom Fields Contact Form 7
Description: Adds a new 'Contact Form 7' field to the popular Advanced Custom Fields plugin.
Version: 1.1.6
Author: Beetle
Author URI: http://beetle.net.ua/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: acf-contact-form-7
Domain Path: /lang
*/

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('acf_plugin_contact_form_7') ) {
    class acf_plugin_contact_form_7
    {
        var $settings;

        function __construct()
        {
            $this->settings = [
                'version' => '1.0.0',
                'url' => plugin_dir_url(__FILE__),
                'path' => plugin_dir_path(__FILE__)
            ];

            add_action('acf/include_field_types', [$this, 'include_field']);
        }

        function include_field($version = 5)
        {
            load_plugin_textdomain('acf-contact-form-7', false, plugin_basename(dirname(__FILE__)) . '/lang');
            include_once('fields/acf-contact-form-7-v'.$version.'.php');
        }
    }

    new acf_plugin_contact_form_7();
}
?>