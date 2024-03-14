<?php if (!defined('ABSPATH')) {
    exit;
}

abstract class Kemail_Tinymce_Editor_Ext
{
    public static function register()
    {
        if (is_admin() && get_option('ke_wpform_api_username') && get_option('ke_wpform_api_token')) {
            add_action('init', array(self::class, 'setup_tinymce_plugin'));
        }
    }

    public static function setup_tinymce_plugin()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if (get_user_option('rich_editing') !== 'true') {
            return;
        }

        add_filter('mce_external_plugins', array(self::class, 'add_tinymce_plugin'));
        add_filter('mce_buttons', array(self::class, 'add_tinymce_toolbar_button'));

    }

    public static function add_tinymce_plugin($plugin_array)
    {
        $plugin_array['kirimemail_wpform_button'] = get_asset('js/tinymce-kirimemail.js');

        return $plugin_array;
    }

    public static function add_tinymce_toolbar_button($buttons)
    {
        array_push($buttons, ' &nbsp; | &nbsp; ', 'kirimemail_wpform_button');

        return $buttons;
    }
}
