<?php

if(!defined('ABSPATH')) exit;

class WPSpeed404_SettingsController {
    private static $_instance = null;
    public static function instance() {
        if (self::$_instance == null) {
            self::$_instance = new WPSpeed404_SettingsController();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('admin_init', array($this, 'save'));
        add_action('admin_menu', array($this, 'add_settings_menu'));
        add_action('admin_head', array($this, 'fix_icon_size'));
        add_action('admin_notices', array($this, 'admin_notices'));
    }

    public function notice($type, $message){
        printf('<div class="notice notice-%s is-dismissible">', $type);
        printf('<p>%s</p>', esc_html($message));
        printf('</div>');
    }

    function admin_notices() {
        if(isset($_GET['page']) && $_GET['page'] == WPSpeed404::$slug) {
            if(!WPSpeed404_Engine::is_supported()){
                $this->notice('error', __('WARNING: WP Speed 404 is only properly supported on Apache based environments.
                Please be advised that some features may not work', 'wp-speed-404')
                );
            }

            if(isset($_GET['success']) && isset($_GET['message']) && strlen($_GET['message']) > 0) {
                $type = $_GET['success'] == '1' ? 'success' : 'error';
                $message = stripslashes($_GET['message']);
                $this->notice($type, $message);
            }
        }
    }

    function add_settings_menu() {
        add_menu_page(
            __(WPSpeed404::$title, 'wp-speed-404'),
            __(WPSpeed404::$title, 'wp-speed-404'),
            'manage_options',
            WPSpeed404::$slug,
            array($this, 'render'),
            WPSpeed404::asset_url('icon.png')
        );
    }

    function fix_icon_size() {
        ?>
        <style>
            .toplevel_page_wp-speed-404 .wp-menu-image img {
                height: 25px;
                top: -5px;
                position: relative;
            }
        </style>
        <?php
    }

    public function redirect($success, $message){
        $get = stripslashes_deep($_GET);
        $get['success'] = $success ? 1 : 0;
        $get['message'] = $message;
        wp_redirect('?' . http_build_query($get));
        exit;
    }

    public function save() {
        if(!current_user_can('manage_options')){
            return;
        }

        if(!(isset($_POST['action']) && isset($_GET['page']) && isset($_POST['_wpnonce']))){
            return;
        }

        if(!($_POST['action'] == 'update' && $_GET['page'] == WPSpeed404::$slug)){
            return;
        }

        if (!wp_verify_nonce($_POST['_wpnonce'], 'update')) {
            $this->redirect(false, __('Invalid Nonce', 'wp-speed-404'));
        }

        $post = stripslashes_deep($_POST);
        if(array_key_exists('save', $post)) {
            $settings = WPSpeed404_Settings::instance();

            if($post['notify_email'] !== '' && !filter_var($post['notify_email'], FILTER_VALIDATE_EMAIL)) {
                $this->redirect(false, __('Invalid Email Address', 'wp-speed-404'));
            }

            if(!array_key_exists($post['mode'], WPSpeed404_Engine::$modes)){
                $this->redirect(false, __('Invalid Mode', 'wp-speed-404'));
            }

            $settings->mode = $post['mode'];
            $settings->include_wp_includes = array_key_exists('include_wp_includes', $post);
            $settings->include_wp_admin = array_key_exists('include_wp_admin', $post);
            $settings->notify_email = $post['notify_email'];

            WPSpeed404_engine::instance()->flush();

            $this->redirect(true, __('Changes Saved', 'wp-speed-404'));
        }

        if(isset($post['clear'])) {
            WPSpeed404_Log::instance()->clear();
            $this->redirect(true, __('Log Cleared', 'wp-speed-404'));
        }
    }

    public function render() {
        $settings = WPSpeed404_Settings::instance();
        require WPSpeed404::$view_path . 'settings.php';
    }
}