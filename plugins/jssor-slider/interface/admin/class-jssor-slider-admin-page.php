<?php

/**
 * class_jssor_slider_admin_page short summary.
 *
 * class_jssor_slider_admin_page description.
 *
 * @version 1.0
 * @author jssor
 */
class WP_Jssor_Slider_Admin_Page
{
    private static $error_messages = array();
    private static $sliders_data;

	/**
     * Register the stylesheets for the admin area.
     *
     * @since    3.1.0
     */
	public static function enqueue_styles() {
        wp_enqueue_style( WP_JSSOR_SLIDER_PLUGIN_NAME . 'new', WP_JSSOR_SLIDER_URL . 'interface/admin/css/jssor-slider-admin.css', array(), WP_JSSOR_SLIDER_VERSION, 'all' );
        wp_enqueue_style( 'jssor-slideo-eidtor-css', WP_JSSOR_SLIDER_URL . 'public/content/slideo.editor/css/slideo.editor.min.css', array(), WP_JSSOR_SLIDER_VERSION, 'all' );

	}

	/**
     * Register the JavaScript for the admin area.
     *
     * @since    3.1.0
     */
	public static function enqueue_scripts() {

        $admin_main_script_name = 'jssor-slider-admin-init-script';
        WP_Jssor_Slider_Condition::enqueue_admin_init_script();

        $title_nonce = wp_create_nonce( 'wjssl-add-slider' );
        wp_localize_script($admin_main_script_name, 'wjssl_ajax_new_slider_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => $title_nonce,
            'action' => 'wjssl_add_new_slider',
            'delete_action' => 'wjssl_delete_slider',
            'duplicate_action' => 'wjssl_duplicate_slider',
        ) );

        $nonce = wp_create_nonce('wjssl-purchase');
        wp_localize_script($admin_main_script_name, 'wjssl_ajax_purchase_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => $nonce,
            'activate_action' => 'wjssl_activate_plugin',
            'deactivate_action' => 'wjssl_deactivate_plugin',
        ) );

        $nonce = wp_create_nonce('wjssl-update');
        wp_localize_script($admin_main_script_name, 'wjssl_ajax_update_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => $nonce,
            'update_now_action' => 'wjssl_update_now',
            'check_for_updates_action' => 'wjssl_check_for_updates',
        ) );

        $nonce = wp_create_nonce('wjssl-requirements');
        wp_localize_script($admin_main_script_name, 'wjssl_ajax_requirements_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => $nonce,
            'connect_jssor_com_server_action' => 'wjssl_connect_jssor_com_server',
        ) );

        $nonce = wp_create_nonce('wjssl-status');
        wp_localize_script($admin_main_script_name, 'wjssl_ajax_status_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => $nonce,
            'check_status' => 'wjssl_check_status',
        ) );

        wp_localize_script($admin_main_script_name, 'wjssl_slider_nonces_obj', array(
            'preview'    => wp_create_nonce('wjssl-preview'),
            'import'    => wp_create_nonce('wjssl-import'),
            'retrieve'    => wp_create_nonce('wjssl-retrieve'),
            'save'    => wp_create_nonce('wjssl-save'),
        ) );

        wp_localize_script($admin_main_script_name, 'wjssl_settings_nonces_obj', array(
            'savemyfontlibrary'    => wp_create_nonce('wjssl-savemyfontlibrary')
        ) );

		if(function_exists("wp_enqueue_media"))
			wp_enqueue_media();
	}

    public static function admin_notices() {
        foreach(WP_Jssor_Slider_Admin_Page::$error_messages as $error_message) {
            echo '<div class="error"><p>' . htmlentities($error_message) . '</p></div>';
        }
    }

    public static function get_all_slider_data() {
        return WP_Jssor_Slider_Admin_Page::$sliders_data;
    }

    public static function initialize() {
        add_action( 'admin_enqueue_scripts', 'WP_Jssor_Slider_Admin_Page::enqueue_styles' );
        add_action( 'admin_enqueue_scripts', 'WP_Jssor_Slider_Admin_Page::enqueue_scripts' );
        add_action( 'admin_notices', 'WP_Jssor_Slider_Admin_Page::admin_notices' );

        #region make sure jssor slider installed correctly

        Jssor_Slider_Dispatcher::load_once('includes/bll/class-jssor-slider-activator.php');
        WP_Jssor_Slider_Activator::update();
        Jssor_Slider_Bll::do_cleanup_transactions();

        #endregion

        #region prepare sliders data

        $sliders_data = Jssor_Slider_Dal::get_all_slider_data($error_message);

        if(is_null($sliders_data)) {
            $sliders_data = array();
        }

        WP_Jssor_Slider_Admin_Page::$sliders_data = $sliders_data;

        if(!is_null($error_message)) {
            WP_Jssor_Slider_Admin_Page::$error_messages[] = $error_message;
        }

        #endregion
    }

    public static function load_page() {
        Jssor_Slider_Dispatcher::load_once('includes/bll/class-jssor-slider-slider.php');
        Jssor_Slider_Dispatcher::load('interface/admin/partials/jssor-slider-admin-display.php');
    }
}
