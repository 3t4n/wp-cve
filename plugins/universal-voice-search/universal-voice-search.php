<?php

/**
 * Plugin Name: Universal Voice Search
 * Description: Allows any serach box on the page to be searchable via voice.
 * Version:     3.1.2
 * Author:      speak2web
 * Author URI:  https://speak2web.com/
 * Text Domain: universal-voice-search
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2023 speak2web
 *
 * Universal Voice Search is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * Universal Voice Search is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Universal Voice Search; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

defined('WPINC') or die;

include(dirname(__FILE__) . '/lib/requirements-check.php');

$universal_voice_search_requirements_check = new Universal_Voice_Search_Requirements_Check(
    array(
        'title' => 'Universal Voice Search',
        'php' => '5.3',
        'wp' => '2.6',
        'file' => __FILE__,
    )
);
class Uvs_Elementor_widget
{

    private static $instance = null;


    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    private function include_widgets_files()
    {
        require_once(__DIR__ . '/widgets/oembed-widget.php');
    }

    public function register_widgets()
    {
        // It's now safe to include Widgets files.
        $this->include_widgets_files();

        // Register the plugin widget classes.
        \Elementor\Plugin::instance()->widgets_manager->register(new \Uvs_Elementor_Floating_Mic_Widget());
    }

    public function register_categories($elements_manager)
    {
        $elements_manager->add_category(
            'speak2web',
            [
                'title' => __('Speak2web', 'myWidget'),
                'icon' => 'fa fa-plug'
            ]
        );
    }

    public function __construct()
    {
        // Register the widgets.
        add_action('elementor/widgets/register', array($this, 'register_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'register_categories'));
    }
}
Uvs_Elementor_widget::instance();

if ($universal_voice_search_requirements_check->passes()) {
    $uvs_client_info = array(
        'chrome' => false,
        'firefox' => false,
        'edge' => false,
        'ie' => false,
        'macSafari' => false,
        'iosSafari' => false,
        'opera' => false
    );

    // Chrome
    if (stripos($_SERVER['HTTP_USER_AGENT'], 'chrome') !== false) {
        $uvs_client_info['chrome'] = true;
    }

    // Firefox
    if (stripos($_SERVER['HTTP_USER_AGENT'], 'firefox') !== false) {
        $uvs_client_info['firefox'] = true;
    }

    // Edge
    if (stripos($_SERVER['HTTP_USER_AGENT'], 'edge') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'edg') !== false) {
        $uvs_client_info['edge'] = true;
    }

    // IE
    if (stripos($_SERVER['HTTP_USER_AGENT'], 'msie') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'trident') !== false) {
        $uvs_client_info['ie'] = true;
    }

    // Mac Safari
    if (stripos($_SERVER['HTTP_USER_AGENT'], 'macintosh') !== false && stripos($_SERVER['HTTP_USER_AGENT'], 'chrome') === false && stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false) {
        $uvs_client_info['macSafari'] = true;
    }

    // iOS
    if ((stripos($_SERVER['HTTP_USER_AGENT'], 'iphone') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'ipad') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'ipod') !== false) && stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false) {
        $uvs_client_info['iosSafari'] = true;
    }

    // Opera
    if (stripos($_SERVER['HTTP_USER_AGENT'], 'opera') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'opr') !== false) {
        $uvs_client_info['opera'] = true;
    }

    if ($uvs_client_info['chrome'] === true && ($uvs_client_info['opera'] === true || $uvs_client_info['edge'] === true)) {
        $uvs_client_info['chrome'] = false;
    }

    define('UVS_CLIENT', $uvs_client_info);

    // To get all active plugins.
    $uvs_all_active_plugins = (array) null;

    // Get selected language from DB and load local translation library
    $uvs_selected_language = get_option('uvs_selected_language', 'en-US');
    $uvs_selected_language = empty($uvs_selected_language) ? 'en-US' : trim($uvs_selected_language);
    $uvs_language_file_name = $uvs_selected_language == 'de-DE' ? 'uvs_de_DE' : 'uvs_en_EN';
    include(dirname(__FILE__) . '/classes/plugin-languages/' . $uvs_language_file_name . '.php');

    try {
        switch ($uvs_selected_language) {
            case 'de-DE':
                define('UVS_LANGUAGE_LIBRARY', uvs_de_DE::UVS_LANGUAGE_LIB);
                break;
            default:
                define('UVS_LANGUAGE_LIBRARY', uvs_en_EN::UVS_LANGUAGE_LIB);
        }
    } catch (\Exception $e) {
        // Do nothing for now
    }

    define(
        'UVS_PLUGIN',
        array(
            'ABS_PATH' => plugin_dir_path(__FILE__),
            'ABS_URL' => plugin_dir_url(__FILE__),
            'BASE_NAME' => plugin_basename(__FILE__),
            'SHORT_PHRASES' => array('root' => 'short_phrases/', 'general' => 'general/', 'random' => 'random/')
        )
    );

    // Pull in the plugin classes and initialize
    include(dirname(__FILE__) . '/lib/wp-stack-plugin.php');
    include(dirname(__FILE__) . '/classes/uvs-admin-notices.php');
    include(dirname(__FILE__) . '/classes/languages/languages.php');
    include(dirname(__FILE__) . '/classes/plugin.php');
    include(dirname(__FILE__) . '/classes/settings-page.php');

    Universal_Voice_Search_Plugin::start(__FILE__);

    // Inline plugin notices
    $path = plugin_basename(__FILE__);

    // Hook into plugin activation
    register_activation_hook(__FILE__, function () {
        $uvs_setting_update_ts = Universal_Voice_Search_Settings_Page::uvs_settings_modified_timestamp('set');
        unset($uvs_setting_update_ts);

        // Get active plugins
        $uvs_all_active_plugins = get_option('active_plugins');

        // Get higher version active plugins path
        $wcva_path = uvs_get_active_plugin_path('voice-shopping-for-woocommerce', $uvs_all_active_plugins);
        $vdn_path = uvs_get_active_plugin_path('voice-dialog-navigation', $uvs_all_active_plugins);
        $dvc_path = uvs_get_active_plugin_path('dynamic-voice-command', $uvs_all_active_plugins);
        $vf_path = uvs_get_active_plugin_path('voice-forms', $uvs_all_active_plugins);
        $vswc_path = uvs_get_active_plugin_path('voice-search-for-woocommerce', $uvs_all_active_plugins);

        $uvs_plugin_url = plugin_dir_url(__FILE__);

        // Display activation denied notice and stop activating this plugin
        if (
            (!empty($wcva_path) && is_plugin_active($wcva_path))
            || (!empty($vdn_path) && is_plugin_active($vdn_path))
            || (!empty($dvc_path) && is_plugin_active($dvc_path))
            || (!empty($vf_path) && is_plugin_active($vf_path))
            || (!empty($vswc_path) && is_plugin_active($vswc_path))
        ) {
            wp_die(Uvs_Admin_Notices::uvs_denied_activation_notice($uvs_plugin_url));
        }

        //###########################################################################################################################################
        // Transition code to preserve admin's language choice before upgrading/updating to additional 130 language support feature 
        // 
        // Here admin's language choice is check against fallback array which maps the old way of storing language name as value with language code
        //###########################################################################################################################################
        $uvs_selected_language = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['selected_language'], 'en-US');
        $uvs_selected_language = isset($uvs_selected_language) && !empty($uvs_selected_language) ? $uvs_selected_language : 'en-US';
        $uvs_lang_code = 'en-US';

        if (in_array($uvs_selected_language, Universal_Voice_Search_Plugin::$uvs_fallback_lang_map)) {
            $uvs_lang_code = array_search($uvs_selected_language, Universal_Voice_Search_Plugin::$uvs_fallback_lang_map);
            update_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['selected_language'], $uvs_lang_code);
        } else {
            $uvs_lang_code = $uvs_selected_language;
        }

        // Register plugin
        Universal_Voice_Search_Plugin::uvs_register_plugin();

        $uvs_general = UVS_PLUGIN['ABS_PATH'] . UVS_PLUGIN['SHORT_PHRASES']['root'] . UVS_PLUGIN['SHORT_PHRASES']['general'];

        // Get language from database as 'Universal_Voice_Search_Plugin::uvs_register_plugin()' could have set auto detected language
        $uvs_lang_code = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['selected_language'], 'en-US');

        if (!file_exists($uvs_general . $uvs_lang_code)) {
            Universal_Voice_Search_Settings_Page::uvs_inject_short_audio_phrases($uvs_lang_code);
        }
    });

    /**
     * Function to get path of active plugin
     *
     * @param $uvs_plugin_file_name  String  Name of the plugin file (Without extension)
     * @param $uvs_active_plugins  Array  Array of active plugins path
     *
     * @return $uvs_active_plugin_path  String  Path of active plugin otherwise NULL
     *
     */
    function uvs_get_active_plugin_path($uvs_plugin_file_name = "", $uvs_active_plugins = array())
    {
        $uvs_active_plugin_path = null;

        try {
            if (!!$uvs_active_plugins && !!$uvs_plugin_file_name) {
                $uvs_plugin_file_name = trim($uvs_plugin_file_name);

                foreach ($uvs_active_plugins as $key => $active_plugin) {
                    $plugin_name_pos = stripos($active_plugin, $uvs_plugin_file_name . ".php");

                    if ($plugin_name_pos !== false) {
                        $uvs_active_plugin_path = $active_plugin;
                        break;
                    }
                }
            }
        } catch (\Exception $ex) {
            $uvs_active_plugin_path = null;
        }

        return $uvs_active_plugin_path;
    }

    // Define the uninstall function
    function uvs_remove_version_from_db()
    {
        delete_option('uvs_version');
    }

    // Register the uninstall hook
    register_uninstall_hook(__FILE__, 'uvs_remove_version_from_db');
}

unset($universal_voice_search_requirements_check);
