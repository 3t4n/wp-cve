<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class LSOW_Admin_Ajax {

    // Instance of this class.
    protected $plugin_slug = 'livemesh_so_widgets';
    protected $ajax_data;
    protected $ajax_msg;


    public function __construct() {

        // retrieve all ajax string to localize
        $this->localize_strings();
        $this->init_hooks();

    }

    public function init_hooks() {

        // Register backend ajax action
        add_action('wp_ajax_lsow_admin_ajax', array($this, 'lsow_admin_ajax'));
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

    public function lsow_check_nonce() {

        // retrieve nonce
        $nonce = (isset($_POST['nonce'])) ? $_POST['nonce'] : $_GET['nonce'];

        // nonce action for the grid
        $action = 'lsow_admin_nonce';

        // check ajax nounce
        if (!wp_verify_nonce($nonce, $action)) {
            // build response
            $response = $this->ajax_response(false, __('Sorry, an error occurred. Please refresh the page.', 'livemesh-so-widgets'));
            // die and send json error response
            wp_send_json($response);
        }

    }

    public function lsow_admin_ajax() {

        // check the nonce
        $this->lsow_check_nonce();

        // retrieve data
        $this->ajax_data = (isset($_POST)) ? $_POST : $_GET;

        // retrieve function
        $func = $this->ajax_data['func'];

        switch ($func) {
            case 'lsow_save_settings':
                $response = $this->save_settings_callback();
                break;
            case 'lsow_reset_settings':
                $response = $this->save_settings_callback();
                break;
            default:
                $response = ajax_response(false, __('Sorry, an unknown error occurred...', 'livemesh-so-widgets'), null);
                break;
        }

        // send json response and die
        wp_send_json($response);

    }

    public function save_settings_callback() {

        // retrieve data from jquery
        $setting_data = $this->ajax_data['setting_data'];

        lsow_update_options($setting_data);

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
                'before' => '<i class="tg-info-box-icon dashicons dashicons-admin-generic"></i>',
                'success' => '<i class="tg-info-box-icon dashicons dashicons-yes"></i>',
                'error' => '<i class="tg-info-box-icon dashicons dashicons-no-alt"></i>'
            ),
            'box_messages' => array(

                'lsow_save_settings' => array(
                    'before' => __('Saving plugin settings', 'livemesh-so-widgets'),
                    'success' => __('Plugin settings Saved', 'livemesh-so-widgets'),
                    'error' => __('Sorry, an error occurs while saving settings...', 'livemesh-so-widgets')
                ),
                'lsow_reset_settings' => array(
                    'before' => __('Resetting plugin settings', 'livemesh-so-widgets'),
                    'success' => __('Plugin settings resetted', 'livemesh-so-widgets'),
                    'error' => __('Sorry, an error occurred while resetting settings', 'livemesh-so-widgets')
                ),
            )
        );

    }

    public function admin_nonce() {

        return array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lsow_admin_nonce')
        );

    }

    public function enqueue_admin_scripts() {

        $screen = get_current_screen();

        // enqueue only in grid panel
        if (strpos($screen->id, $this->plugin_slug) !== false) {
            // merge nonce to translatable strings
            $strings = array_merge($this->admin_nonce(), $this->ajax_msg);

            // Use minified libraries if LSOW_SCRIPT_DEBUG is turned off
            $suffix = (defined('LSOW_SCRIPT_DEBUG') && LSOW_SCRIPT_DEBUG) ? '' : '.min';

            // register and localize script for ajax methods
            wp_register_script('lsow-admin-ajax-scripts', LSOW_PLUGIN_URL . 'admin/assets/js/lsow-admin-ajax' . $suffix . '.js', array(), LSOW_VERSION, true);
            wp_enqueue_script('lsow-admin-ajax-scripts');

            wp_localize_script('lsow-admin-ajax-scripts', 'lsow_admin_global_var', $strings);

        }
    }

}

new LSOW_Admin_Ajax;