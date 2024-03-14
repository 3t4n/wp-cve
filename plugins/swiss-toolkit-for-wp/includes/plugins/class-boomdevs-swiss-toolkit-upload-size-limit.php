<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include the plugin setting class
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * Class BDSTFW_Swiss_Toolkit_Upload_Size_Limit
 *
 * This class handles the upload file size limit and maximum execution time settings.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Upload_Size_Limit')) {
    class BDSTFW_Swiss_Toolkit_Upload_Size_Limit
    {
        /**
         * The single instance of the class.
         */
        protected static $instance;

        /**
         * Returns the single instance of the class.
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
         * Initializes the class and sets up filters for upload size limit.
         */
        public function __construct()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();
            if (isset($settings['boomdevs_swiss_upload_file_size_and_execution_time_switcher']) && $settings['boomdevs_swiss_upload_file_size_and_execution_time_switcher'] === '1') {
                add_filter('upload_size_limit', [$this, 'boomdevs_increase_upload_file_size_limit'], 20);
            }
        }

        /**
         * Increase the upload file size limit.
         *
         * @param int $max_upload_size The maximum upload size in bytes.
         * @return int The modified maximum upload size.
         */
        public function boomdevs_increase_upload_file_size_limit($max_upload_size)
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();

            if (isset($settings['boomdevs_swiss_upload_file_size_and_execution_time_switcher']) && isset($settings['boomdevs_swiss_maximum_upload_file_size'])) {
                $upload_file_and_execution_time_switcher = $settings['boomdevs_swiss_upload_file_size_and_execution_time_switcher'];
                $max_upload_file_size = sanitize_text_field($settings['boomdevs_swiss_maximum_upload_file_size']);

                if ($upload_file_and_execution_time_switcher == '1') {
                    // Check if the max_upload_file_size is a valid numeric value before conversion
                    if (is_numeric($max_upload_file_size)) {
                        $max_upload_size = $max_upload_file_size * 1024 * 1024;
                    } else {
                        $max_upload_size = 64 * 1024 * 1024; // Default value if invalid max_upload_file_size
                    }

                    // Set the maximum execution time
                    $this->set_max_execution_time();
                    $this->set_custom_memory_limit();
                }
            }

            return $max_upload_size;
        }

        /**
         * Set the maximum execution time.
         */
        public function set_max_execution_time()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();

            if (isset($settings['boomdevs_swiss_upload_file_size_and_execution_time_switcher']) && isset($settings['boomdevs_swiss_max_execution_time'])) {

                $upload_file_and_execution_time_switcher = $settings['boomdevs_swiss_upload_file_size_and_execution_time_switcher'];
                $max_execution_time = $settings['boomdevs_swiss_max_execution_time'];

                if ($upload_file_and_execution_time_switcher == '1' && is_numeric($max_execution_time)) {
                    set_time_limit($max_execution_time);
                }
            }
        }

        public function set_custom_memory_limit()
        {
            ini_set('memory_limit', '-1');
        }
    }

    // Initialize the BDSTFW_Upload_Size_Limit class
    BDSTFW_Swiss_Toolkit_Upload_Size_Limit::get_instance();
}