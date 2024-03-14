<?php

defined('ABSPATH') or exit;

final class Yandex_SMTP
{

    public function __construct()
    {
        add_action('init', [$this, 'init']);
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'admin_init']);
        extension_loaded('openssl') or add_action('admin_notices', [$this, 'ssl_required_notice']);
        add_filter('plugin_action_links', [$this, 'action_links'],  10, 4);
    }

    public function init()
    {
        load_plugin_textdomain('yandex-smtp', false, basename(__DIR__) . '/languages');
        $this->options = get_option('yandex_smtp_settings');
    }

    public function admin_menu()
    {
        $title = __('Yandex Mail SMTP', 'yandex-smtp'); 
        add_options_page($title, $title, 'manage_options', 'yandex-smtp', [$this, 'admin_page']);
    }

    public function admin_init()
    {
        register_setting('yandex_smtp_settings', 'yandex_smtp_settings');
        add_settings_section('default', false, false, 'yandex-smtp');
        add_settings_field('from', __('E-mail From Name', 'yandex-smtp'), [$this, 'field_callback'],
            'yandex-smtp', 'default', ['type' => 'text', 'name' => 'from']);
        add_settings_field('login', __('E-mail Address', 'yandex-smtp'), [$this, 'field_callback'],
            'yandex-smtp', 'default', ['type' => 'mail', 'name' => 'login']);
        add_settings_field('password', __('Password', 'yandex-smtp'), [$this, 'field_callback'],
            'yandex-smtp', 'default', ['type' => 'password', 'name' => 'password']);
        add_settings_field('copy', __('Send yourself a copy', 'yandex-smtp'), [$this, 'copy_callback'],
            'yandex-smtp', 'default');
    }

    public function admin_page()
    {

        echo '<div class="wrap"><div class="wrap-inside">'
            . '<div class="section-title">'
            . '<h2>' . __('Yandex Mail SMTP Server for WordPress', 'yandex-smtp') . '</h2>'
            . '</div>'
            . '<form method="post" action="options.php">';
        settings_fields('yandex_smtp_settings');
        do_settings_sections('yandex-smtp');
        submit_button(); 
        echo '</form></div></div>';
        include YANDEX_SMTP_DIR . 'admin-styles.css.php';

    }

    public function field_callback($args)
    {
        echo '<input type="' . $args['type'] . '" name="yandex_smtp_settings[' . $args['name'] . ']" value="'
            . esc_attr($this->options[$args['name']]) . '" required="required" autocomplete="false" />';
    }

    public function copy_callback()
    {   

        echo '<input type="checkbox" name="yandex_smtp_settings[copy]" value="1"
        ' . checked(isset($this->options['copy']), 1, false) . ' />';
    }
    
    public function ssl_required_notice()
    {
        echo '<div class="error"><p>'
            . __('The mod_ssl module is required', 'yandex-smtp')
            . '</p></div>';
    }

    public function action_links($actions, $plugin_file)
    {
        if (false === strpos($plugin_file, 'yandex-smtp')) {
            return $actions;
        }
        $settings = '<a href="' . esc_url(menu_page_url('yandex-smtp', false))
            . '">' . __('Settings', 'yandex-smtp') . '</a>';
        array_unshift($actions, $settings);
        return $actions;
    }

}

new Yandex_SMTP;
