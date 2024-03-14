<?php
/**
 * The desctivation of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/base
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\base;

if (!class_exists('THWWC_Deactivate')) :
    /**
     * Deactivate class
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Deactivate
    {
        /**
         * Function to run on acivation of plugin.
         *
         * @return void
         */
        public static function deactivate()
        {
            flush_rewrite_rules();
        }
    }
endif;