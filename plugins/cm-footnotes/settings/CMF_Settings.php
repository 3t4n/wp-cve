<?php

namespace com\cminds\footnotes\settings;

include_once plugin_dir_path(__FILE__) . 'Settings.php';

class CMF_Settings extends Settings {

    protected static $abbrev = 'cmf';
    protected static $dir = __DIR__;
    protected static $settingsURL;
    protected static $settingsPageSlug;

    // Should be called on init hook
    public static function init() {

        // Mandatory
        self::load_config();
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueueAssets']);
        add_action(self::abbrev('_save_options_after_on_save'), [__CLASS__, 'addSuccessSaveMessage'], 10, 2);
        add_action('admin_menu', [__CLASS__, 'add_settings_page']);

        // Optional
        add_action(self::abbrev('-settings-item-class'), [__CLASS__, 'settingItemClass'], 1, 3);
    }

    public static function add_settings_page() {
        self::$settingsPageSlug = add_submenu_page(CMF_MENU_OPTION, 'Footnote Options', 'Settings', 'manage_options', CMF_MENU_OPTION, [__CLASS__, 'render']);
    }

    public static function getMenuSlug() {
        return '-settings';
    }

    public static function render() {
        echo parent::render();
        echo "<style>
        .full-width{
        width: 100% !important;
        }
        .full-width th{
        font-size: 18px;
        font-weight: bold;
        }
        .full-width .cm_field_help{
        right: 52%;
        font-size: 14px;
        }
        .disable-help-field .cm_field_help{
        display:none;
        }
        </style>";
    }

    // May be used for adding custom class to the option HTML container
    public static function settingItemClass($class, $setting, $config) {
        $class = '';
        if (stripos($setting, '_separator'))
            $class .= ' full-width';
        if (empty($config['description'])) {
            $class .= ' disable-help-field';
        }
        return $class;
    }

    // Show success message after settings saving
    public static function addSuccessSaveMessage($post, $message) {
        $message[0] = __('Settings Saved', 'cmf');
    }

    // Put helper functions used in config.php
}
