<?php

namespace ECFFW\App\Controllers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use ECFFW\App\Controllers\Admin\FormBuilder;

class Assets
{
    /**
     * Assets construct.
     */
    public function __construct() 
    {
        add_action('admin_enqueue_scripts', array($this, 'adminAssets'));
        add_action('wp_enqueue_scripts', array($this, 'frontendAssets'));
    }

    /**
     * Admin Assets.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function adminAssets()
    {
        // Register and Enqueue Styles
        wp_register_style('ecffw-admin-css', esc_url(ECFFW_PLUGIN_URL . '/Assets/CSS/ecffw-admin.css'), array(), ECFFW_PLUGIN_VERSION);
        wp_enqueue_style('ecffw-admin-css');

        // Register and Enqueue Scripts
        wp_register_script('ecffw-admin-js', esc_url(ECFFW_PLUGIN_URL . '/Assets/JS/ecffw-admin.js'), array('jquery'), ECFFW_PLUGIN_VERSION);
        wp_register_script('ecffw-form-builder-js', esc_url(ECFFW_PLUGIN_URL . '/Assets/JS/form-builder.min.js'), array('jquery'), ECFFW_PLUGIN_VERSION);
        wp_enqueue_script('ecffw-admin-js');
        wp_enqueue_script('ecffw-form-builder-js');

        // Localize Scripts
        $config = FormBuilder::config();
        wp_localize_script('ecffw-admin-js', 'ecffwObject', $config);
    }

    /**
     * Frontend Assets.
     * 
     * @since 1.0.0
     * @return void
     */
    public static function frontendAssets()
    {
        if (!is_admin())
        {
            // Register and Enqueue Styles
            wp_register_style('ecffw-frontend-css', esc_url(ECFFW_PLUGIN_URL . '/Assets/CSS/ecffw-frontend.css'), array(), ECFFW_PLUGIN_VERSION);
            wp_enqueue_style('ecffw-frontend-css');

            // Register and Enqueue Scripts
            wp_register_script('ecffw-frontend-js', esc_url(ECFFW_PLUGIN_URL . '/Assets/JS/ecffw-frontend.js'), array('jquery'), ECFFW_PLUGIN_VERSION);
            wp_enqueue_script('ecffw-frontend-js');
        }
    }
}
