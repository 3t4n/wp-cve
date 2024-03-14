<?php

defined('ABSPATH' ) or die('No direct access');

class Pushly_Admin
{
    public static function init()
    {
        $self = new self();

        add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
        add_action('admin_init', array(__CLASS__, 'init_settings'));

        return $self;
    }

    public static function add_settings_page()
    {
        add_menu_page(
            'Pushly',
            'Pushly',
            'manage_options',
            'pushly',
            array(__CLASS__, 'settings_menu_html')
        );
    }

    public static function settings_menu_html()
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        if (isset($_GET['settings-updated'])) {
            add_settings_error('pushly_messages', 'pushly_message', __('Settings Saved', 'pushly' ), 'updated' );
        }

        require_once PUSHLY_PLUGIN_PATH_ROOT . 'views/templates/settings.php';
    }

    public static function init_settings()
    {
        register_setting('pushly', 'pushly_options');

        add_settings_section(
            'pushly_section_settings',
            __('Settings', 'pushly'),
            null,
            'pushly'
        );

        add_settings_field(
            'pushly_domain_key',
            __('Domain Key', 'pushly'),
            array(__CLASS__, 'field_domain_key'),
            'pushly',
            'pushly_section_settings',
            array(
                'label_for' => 'pushly_domain_key',
            )
        );
    }

    public static function field_domain_key($args)
    {
        $options = get_option('pushly_options');
        require_once PUSHLY_PLUGIN_PATH_ROOT . 'views/fields/domain_key.php';
    }
}
