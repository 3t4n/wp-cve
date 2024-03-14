<?php

/**
 * TB Admin.
 *
 * @package TB
 */
if (!class_exists('TB_Admin')) {

    /**
     * Class TB_Admin.
     */
    final class TB_Admin {

        /**
         * Calls on initialization
         *
         * @since 0.0.1
         */
        public static function init() {

            // Activation hook.
        }

        /**
         * Filters and Returns a list of allowed tags and attributes for a given context.
         *
         * @param Array  $allowedposttags Array of allowed tags.
         * @param String $context Context type (explicit).
         * @since 1.8.0
         * @return Array
         */
        public static function add_data_attributes($allowedposttags, $context) {
            $allowedposttags['a']['data-repeat-notice-after'] = true;

            return $allowedposttags;
        }

        /**
         * Enqueues the needed CSS/JS for the builder's admin settings page.
         *
         * @since 1.0.0
         */
        public static function styles_scripts() {

            $localize = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('tb-block-nonce'),
            );

            wp_localize_script('tb-admin-settings', 'tb', apply_filters('tb_js_localize', $localize));
        }

    }

    TB_Admin::init();
}
