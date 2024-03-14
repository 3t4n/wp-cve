<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * Manages All file format support for the WP Swiss Toolkit plugin.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Extension_Supports')) {
    class BDSTFW_Swiss_Toolkit_Extension_Supports
    {
        /**
         * The single instance of the class.
         */
        protected static $instance;

        /**
         * Returns the single instance of the class.
         *
         * @return BDSTFW_Swiss_Toolkit_AVIF Singleton instance.
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
         * Initializes All file format support if enabled in settings.
         */
        public function __construct()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();

            if (isset($settings['boomdevs_swiss_extension_supports_toggle'])) {
                $extensions_support = sanitize_text_field($settings['boomdevs_swiss_extension_supports_toggle']);

                if ($extensions_support === '1') {
                    add_filter('wp_check_filetype_and_ext', [$this, 'upload_avif_files'], 10, 4);
                    add_filter('upload_mimes', [$this, 'swiss_add_avif']);
                }
            }
        }

        /**
         * Filters the allowed file types to include all.
         *
         * @param array $types An array containing the file type and extension.
         * @param string $file The name of the file.
         * @param string $filename The name of the uploaded file.
         * @param array $mimes An array of allowed MIME types.
         * @return array Modified array of allowed file types.
         */
        public function upload_avif_files($types, $file, $filename, $mimes)
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();
            $supports = $settings['boomdevs_swiss_extension_supports_textarea'];

            $extensions_array = explode(', ', $supports);

            $mime_types = [
                'svg' => 'image/svg+xml',
                'ico' => 'image/ico',
                'webp' => 'image/webp',
                'avif' => 'image/avif',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'bpm' => 'image/bmp',
                'mp3' => 'audio/mpeg',
                'ogg' => 'audio/ogg',
                'wav' => 'audio/wav',
                'flac' => 'audio/flac',
                'mp4' => 'video/mp4',
                'webm' => 'video/webm',
                'pdf' => 'application/pdf',
                'msword' => 'application/msword',
                'msexcel' => 'application/vnd.ms-excel',
                'mspowerpoint' => 'application/vnd.ms-powerpoint',
                'plain' => 'text/plain'
            ];

            foreach ($extensions_array as $extension) {
                if(array_key_exists($extension, $mime_types)) {
                    if (false !== strpos($filename, '.'.$extension)) {
                        $types['ext'] = $extension;
                        $types['type'] = $mime_types[$extension];
                    }
                }
            }

            return $types;
        }

        /**
         * Adds All MIME type support.
         *
         * @param array $mimes An array of allowed MIME types.
         * @return array Modified array of allowed MIME types.
         */
        public function swiss_add_avif($mimes)
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();
            $supports = $settings['boomdevs_swiss_extension_supports_textarea'];

            $extensions_array = explode(', ', $supports);

            $mime_types = [
                'svg' => 'image/svg+xml',
                'ico' => 'image/ico',
                'webp' => 'image/webp',
                'avif' => 'image/avif',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'bpm' => 'image/bmp',
                'mp3' => 'audio/mpeg',
                'ogg' => 'audio/ogg',
                'wav' => 'audio/wav',
                'flac' => 'audio/flac',
                'mp4' => 'video/mp4',
                'webm' => 'video/webm',
                'pdf' => 'application/pdf',
                'msword' => 'application/msword',
                'msexcel' => 'application/vnd.ms-excel',
                'mspowerpoint' => 'application/vnd.ms-powerpoint',
                'plain' => 'text/plain'
            ];

            foreach ($extensions_array as $extension) {
                if(array_key_exists($extension, $mime_types)) {
                    $mimes[$extension] = $mime_types[$extension];
                }
            }

            return $mimes;
        }
    }

    // Initialize the BDSTFW_Swiss_Toolkit_Extension_Supports class
    BDSTFW_Swiss_Toolkit_Extension_Supports::get_instance();
}
