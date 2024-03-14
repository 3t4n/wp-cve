<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Redux')) {
    return;
}

/**
 * Include the user setting class for the Swiss Toolkit.
 */
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * Class BDSTFW_Swiss_Toolkit_Setting_User
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Setting_User')) {
    class BDSTFW_Swiss_Toolkit_Setting_User
    {
        /**
         * Plugin settings prefix.
         *
         * @var string
         */
        public static $prefix = 'swiss-toolkit-user-settings';

        /**
         * The single instance of the class.
         *
         * @var BDSTFW_Swiss_Toolkit_Setting_User|null
         */
        protected static $instance;

        /**
         * Returns a single instance of the class.
         *
         * @return BDSTFW_Swiss_Toolkit_Setting_User
         */
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Constructor.
         * Initializes the Generate_URL class.
         */
        public function __construct()
        {
            add_action('plugins_loaded', function () {
                $default_setting = BDSTFW_Swiss_Toolkit_Settings::get_settings();
                $swiss_knife_avatar_toggle_permission = $default_setting && array_key_exists('boomdevs_swiss_avatar_uploader_switcher', $default_setting) && $default_setting['boomdevs_swiss_avatar_uploader_switcher'] !== '' ? $default_setting['boomdevs_swiss_avatar_uploader_switcher'] : null;
                $swiss_knife_user_permission = $default_setting && array_key_exists('boomdevs_swiss_avatar_uploader_switcher', $default_setting) && $default_setting['boomdevs_swiss_avatar_uploader_switcher'] !== '' && array_key_exists('boomdevs_swiss_avatar_uploader_permissions', $default_setting) ? $default_setting['boomdevs_swiss_avatar_uploader_permissions'] : [];
                $current_user_role = count(wp_get_current_user()->roles) > 0 ? wp_get_current_user()->roles[0] : '';

                if ($swiss_knife_avatar_toggle_permission === '1' && $current_user_role !== null) {
                    if (($current_user_role === 'administrator') || (array_key_exists($current_user_role, $swiss_knife_user_permission) && $swiss_knife_user_permission[$current_user_role] === '1')) {
                        $this->user_profile_option();
                    }
                }
            });
        }

        /**
         * Initialize user profile options.
         */
        public function user_profile_option()
        {
            Redux_Users::set_Args(
                BDSTFW_Swiss_Toolkit_Setting_User::$prefix,
                array(
                    'user_priority' => 50,
                )
            );

            Redux_Users::set_profile(
                BDSTFW_Swiss_Toolkit_Setting_User::$prefix,
                array(
                    'id' => 'boomdevs_swiss_avatar_uploader_profile',
                    'style' => 'wp',
                    'sections' => array(
                        array(
                            'fields' => array(
                                array(
                                    'id' => 'boomdevs_swiss_avatar_uploader_image',
                                    'type' => 'media',
                                    'alt' => 'avatar',
                                    'title' => esc_html__('Change Avatar', 'swiss-toolkit-for-wp'),
                                    'preview' => true,
                                    'default' => array(
                                        'url' => BDSTFW_SWISS_TOOLKIT_URL . '/admin/img/default-avatar.png'
                                    ),
                                    'url' => false,
                                    'mode' => 'image',
                                    'class' => 'boomdevs_swiss_avatar_uploader_image',
                                    'width' => '120',
                                    'height' => '120',
                                ),
                            )
                        ),
                    )
                )
            );
        }

        /**
         * Return all plugin settings.
         *
         * @return string|array Settings values.
         */
        public static function get_settings()
        {
            return get_option(BDSTFW_Swiss_Toolkit_Setting_User::$prefix);
        }
    }

    new BDSTFW_Swiss_Toolkit_Setting_User();
}