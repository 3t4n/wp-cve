<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class BEA_Admin_Ajax {
    // Instance of this class.
    protected $plugin_slug = 'better_el_addons';
    protected $ajax_data;
    protected $ajax_msg;

    public function __construct() {
        // retrieve all ajax string to localize
        $this->localize_strings();
        $this->init_hooks();
    }

    public function init_hooks() {

        // Register backend ajax action
        add_action('wp_ajax_bea_admin_ajax', array($this, 'bea_admin_ajax'));
        // Load admin ajax js script
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

    }

    public function ajax_response($success = true, $message = null, $content = null) {

        $response = array(
            'success' => $success,
            'message' => $message,
            'content' => $content
        );

        return $response;

    }

    public function bea_check_nonce() {
        // Retrieve nonce
        $nonce = (isset($_POST['nonce'])) ? $_POST['nonce'] : (isset($_GET['nonce']) ? $_GET['nonce'] : '');

        // Nonce action for verification
        $action = 'bea_admin_nonce';

        // Check AJAX nonce
        if (!wp_verify_nonce($nonce, $action)) {
            // Build response for nonce failure
            wp_send_json($this->ajax_response(false, __('Sorry, your nonce did not verify.', 'better-el-addons')));
        }

        // Check if the current user has the 'manage_options' capability
        if (!current_user_can('manage_options')) {
            // Build response for capability failure
            wp_send_json($this->ajax_response(false, __('Sorry, you are not allowed to perform this action.', 'better-el-addons')));
        }

        // If nonce and capability checks pass, proceed
    }


    public function bea_admin_ajax() {
        // Check the nonce first
        $this->bea_check_nonce();

        // Now, check if the current user has the 'manage_options' capability
        if (!current_user_can('manage_options')) {
            wp_send_json($this->ajax_response(false, __('Sorry, you are not allowed to perform this action.', 'better-el-addons')));
            return; // Exit the function to prevent further execution
        }

        // retrieve data
        $this->ajax_data = (isset($_POST)) ? $_POST : $_GET;

        // retrieve function
        $func = $this->ajax_data['func'];

        // Based on the function, perform the action
        switch ($func) {
            case 'bea_save_settings':
                $response = $this->save_settings_callback();
                break;
            case 'bea_reset_settings':
                $response = $this->save_settings_callback();
                break;
            default:
                $response = $this->ajax_response(false, __('Sorry, an unknown error occurred...', 'better-el-addons'), null);
                break;
        }

        // send json response and die
        wp_send_json($response);
    }


    public function save_settings_callback() {

        // retrieve data from jquery
        $setting_data = $this->ajax_data['setting_data'];

        bea_update_options($setting_data);

        $template = false;
        // get new restore global settings panel
        if ($this->ajax_data['reset']) {
            ob_start();
            require_once('views/settings.php');
            $template = ob_get_clean();
        }

        $response = $this->ajax_response(true, $this->ajax_data['reset'], $template);
        return $response;

    }

    public function localize_strings() {
        
        $this->ajax_msg = array(
            'box_icons' => array(
                'before' => '<i class="bea-info-box-icon dashicons dashicons-admin-generic"></i>',
                'success' => '<i class="bea-info-box-icon dashicons dashicons-yes"></i>',
                'error' => '<i class="bea-info-box-icon dashicons dashicons-no-alt"></i>'
            ),
            'box_messages' => array(

                'bea_save_settings' => array(
                    'before' => __('Saving plugin settings', 'better-el-addons'),
                    'success' => __('Plugin settings Saved', 'better-el-addons'),
                    'error' => __('Sorry, an error occurs while saving settings...', 'better-el-addons')
                ),
                'bea_reset_settings' => array(
                    'before' => __('Resetting plugin settings', 'better-el-addons'),
                    'success' => __('Plugin settings resetted', 'better-el-addons'),
                    'error' => __('Sorry, an error occurred while resetting settings', 'better-el-addons')
                ),
            )
        );

    }

    public function admin_nonce() {

        return array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bea_admin_nonce')
        );

    }



    public function enqueue_admin_scripts() {


            // merge nonce to translatable strings
            $strings = array_merge($this->admin_nonce(), $this->ajax_msg);

            // Use minified libraries if BEA_SCRIPT_DEBUG is turned off
            $suffix = (defined('BEA_SCRIPT_DEBUG') && BEA_SCRIPT_DEBUG) ? '' : '.min';

            // register and localize script for ajax methods
            wp_register_script('bea-admin-ajax-scripts', BEA_PLUGIN_URL . 'admin/assets/js/bea-admin-ajax.js', array(), BEA_VERSION, true);
            wp_enqueue_script('bea-admin-ajax-scripts');

            wp_localize_script('bea-admin-ajax-scripts', 'bea_admin_global_var', $strings);

        
    }
}
new BEA_Admin_Ajax;