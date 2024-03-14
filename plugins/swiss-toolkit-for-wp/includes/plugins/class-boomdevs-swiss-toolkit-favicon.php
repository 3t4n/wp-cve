<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * Manages favicon functionality for the WP Swiss Toolkit plugin.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Favicon')) {
    class BDSTFW_Swiss_Toolkit_Favicon
    {
        /**
         * The single instance of the class.
         */
        protected static $instance;

        /**
         * Returns the single instance of the class.
         *
         * @return BDSTFW_Swiss_Toolkit_Favicon Singleton instance.
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
         * Initializes the favicon functionality if enabled in settings.
         */
        public function __construct()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();

            if (
                isset($settings['boomdevs_swiss_favicon_uploader_switch'])
                && isset($settings['boomdevs_swiss_favicon_uploader_image'])
            ) {
                if ($settings['boomdevs_swiss_favicon_uploader_switch'] === '1') {
                    add_action('admin_head', [$this, 'swiss_favicon_filter']);
                    add_action('wp_head', [$this, 'swiss_favicon_filter']);
                }
            }
        }

        /**
         * Outputs the favicon link in the site's head if a favicon image is configured.
         */
        public function swiss_favicon_filter()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();
            $favicon_url = esc_url($settings['boomdevs_swiss_favicon_uploader_image']['url']);

            if (!empty($favicon_url)) {
                echo '<link rel="icon" href="' . $favicon_url . '" type="image/x-icon">';
            }
        }
    }

    // Initialize the BDSTFW_Favicon class
    BDSTFW_Swiss_Toolkit_Favicon::get_instance();
}
