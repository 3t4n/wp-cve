<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * Manages the insertion of header and footer scripts using settings from WP Swiss Toolkit.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Header_Footer_Scripts')) {
    class BDSTFW_Swiss_Toolkit_Header_Footer_Scripts
    {
        /**
         * The single instance of the class.
         */
        protected static $instance;

        /**
         * Returns the single instance of the class.
         *
         * @return BDSTFW_Swiss_Toolkit_Header_Footer_Scripts Singleton instance.
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
         * Initializes actions for inserting header and footer scripts.
         */
        public function __construct()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();

            if (isset($settings['boomdevs_swiss_insert_header_footer_switch'])) {
                if ($settings['boomdevs_swiss_insert_header_footer_switch'] === '1') {
                    add_action('wp_head', [$this, 'swiss_insert_header'], 10);
                    add_action('wp_footer', [$this, 'swiss_insert_footer'], 10);
                }
            }
        }

        public function execute_header_footer_code($id)
        {
            $language = get_post_meta($id, 'bdstfw_code_snippets_language', true);
            $snippet_code = get_post_meta($id, 'bdstfw_code_snippets_textarea', true);

            if (preg_match('/^<|script/', $snippet_code)) {
                $cleanedHtml = preg_replace('/<!--\?php (.*?) \?-->/s', '<?php $1 ?>', $snippet_code);
                eval('?>' . $cleanedHtml . '<?php ');
            } else {
                if ($language === 'php') {
                    eval($snippet_code);
                } else if ($language === 'html') {
                    $allowed_tags = wp_kses_allowed_html('post');
                    echo wp_kses($snippet_code, $allowed_tags);
                } else if ($language === 'js') {
                    $allowed_tags = array(
                        'script' => array(
                            'type' => array(),
                            'src' => array(),
                            'async' => array(),
                            'defer' => array(),
                        )
                    );

                    $script_string = '<script>' . $snippet_code . '</script>';
                    echo wp_kses($script_string, $allowed_tags);
                } else {
                    $allowed_tags = array(
                        'style' => array(
                            'type' => array(),
                            'media' => array(),
                        )
                    );

                    $style_string = '<style>' . $snippet_code . '</style>';
                    echo wp_kses($style_string, $allowed_tags);
                }
            }
        }

        /**
         * Inserts header scripts based on configured snippets.
         */
        public function swiss_insert_header()
        {
            $args = array(
                'post_type' => 'swiss_snippets',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_key' => 'bdstfw_code_snippets_toggle',
                'meta_value' => 'on'
            );

            $snippet_posts = get_posts($args);
            foreach ($snippet_posts as $snippet_post) {
                $location = get_post_meta($snippet_post->ID, 'bdstfw_code_snippets_location', true);
                if ($location === 'header') {
                    $this->execute_header_footer_code($snippet_post->ID);
                }
            }
        }

        /**
         * Inserts footer scripts based on configured snippets.
         */
        public function swiss_insert_footer()
        {
            $args = array(
                'post_type' => 'swiss_snippets',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_key' => 'bdstfw_code_snippets_toggle',
                'meta_value' => 'on'
            );

            $snippet_posts = get_posts($args);
            foreach ($snippet_posts as $snippet_post) {
                $location = get_post_meta($snippet_post->ID, 'bdstfw_code_snippets_location', true);
                if ($location === 'footer') {
                    $this->execute_header_footer_code($snippet_post->ID);
                }
            }
        }
    }

    // Initialize the BDSTFW_Header_Footer_Scripts class
    BDSTFW_Swiss_Toolkit_Header_Footer_Scripts::get_instance();
}
