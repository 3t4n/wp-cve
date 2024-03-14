<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * Manages custom avatars for the WP Swiss Toolkit plugin.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Avatar')) {
    class BDSTFW_Swiss_Toolkit_Avatar
    {
        /**
         * The single instance of the class.
         */
        protected static $instance;

        /**
         * Returns the single instance of the class.
         *
         * @return BDSTFW_Swiss_Toolkit_Avatar Singleton instance.
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
         * Initializes custom avatar functionality if enabled in settings.
         */
        public function __construct()
        {
            $default_setting = BDSTFW_Swiss_Toolkit_Settings::get_settings();
            if (isset($default_setting['boomdevs_swiss_avatar_uploader_switcher']) && sanitize_text_field($default_setting['boomdevs_swiss_avatar_uploader_switcher']) === '1') {
                add_filter('get_avatar', [$this, 'swiss_avatar_filter'], 10, 5);
                add_filter('get_avatar_url', [$this, 'custom_change_avatar_url'], 10, 5);
            }
        }

        /**
         * Filters the user's avatar with a custom avatar image if available.
         *
         * @param string $avatar The avatar HTML.
         * @param int|string|WP_User|WP_Post $id_or_email The user ID or email.
         * @param int $size The avatar size.
         * @param string $default Default avatar URL.
         * @param string $alt Alternative text for the avatar.
         * @return string Modified avatar HTML.
         */
        public function swiss_avatar_filter($avatar, $id_or_email, $size, $default, $alt): string
        {
            $avatar_img = get_user_meta($this->get_user_id($id_or_email), 'boomdevs_swiss_avatar_uploader_image', true);
            if (!empty($avatar_img['boomdevs_swiss_avatar_uploader_image'])) {
                $avatar = '<img src="' . esc_url($avatar_img['url']) . '" class="avatar avatar-' . $size . ' photo" width="' . $size . '" height="' . $size . '" alt="' . esc_attr($alt) . '">';
            }

            return $avatar;
        }

        /**
         * Filters the user's avatar URL with a custom avatar URL if available.
         *
         * @param string $avatar_url The avatar URL.
         * @param int|string|WP_User|WP_Post $id_or_email The user ID or email.
         * @return string Modified avatar URL.
         */
        public function custom_change_avatar_url($avatar_url, $id_or_email): string
        {
            $avatar_img = get_user_meta($this->get_user_id($id_or_email), 'boomdevs_swiss_avatar_uploader_image', true);
            if (!empty($avatar_img['url'])) {
                return $avatar_img['url'];
            }

            return $avatar_url;
        }

        /**
         * Gets the user ID from various input types.
         *
         * @param int|string|WP_User|WP_Post $id_or_email The user ID or email.
         * @return int|false User ID or false if not found.
         */
        public function get_user_id($id_or_email)
        {
            $user_id = false;

            if (is_numeric($id_or_email)) {
                $user_id = (int) $id_or_email;
            } elseif (is_object($id_or_email) && !empty($id_or_email->user_id)) {
                $user_id = (int) $id_or_email->user_id;
            } elseif ($id_or_email instanceof WP_User) {
                $user_id = $id_or_email->ID;
            } elseif ($id_or_email instanceof WP_Post && !empty($id_or_email->post_author)) {
                $user_id = (int) $id_or_email->post_author;
            } elseif (is_string($id_or_email)) {
                $user    = get_user_by('email', $id_or_email);
                $user_id = $user ? $user->ID : '';
            }

            return $user_id;
        }
    }

    // Initialize the BDSTFW_Avatar class
    BDSTFW_Swiss_Toolkit_Avatar::get_instance();
}
