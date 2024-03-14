<?php
/**
 * The admin add menu and submenu with pages.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/admin
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\admin;

use \THWWC\admin\THWWC_Admin_Settings;

if (!class_exists('THWWC_Admin_Pages')) :
    /**
     * Admin class to add menu and submenu
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Admin_Pages
    {
        /**
         * Function to add pages for menu and submenu redirect.
         *
         * @return void
         */
        public function register()
        {
            add_action('admin_menu', array($this, 'add_admin_menu'));
        }

        /**
         * Function to add page and subpage to admin from the array.
         *
         * @return array
         */
        public function add_admin_menu()
        {
            $plugin_admin = new THWWC_Admin_Settings();

            add_menu_page('Wishlist', __('Wishlist-Comparison', 'wishlist-and-compare'), 'manage_options', 'th_wishlist_settings', array($this, 'wishlist_settings'), 'dashicons-heart', 56);

            add_submenu_page('th_wishlist_settings', 'Wishlist', __('Wishlist', 'wishlist-and-compare'), 'manage_options', 'th_wishlist_settings', array($this, 'wishlist_settings'));

            add_submenu_page('th_wishlist_settings', 'Comparison', __('Comparison', 'wishlist-and-compare'), 'manage_options', 'th_compare_settings', array($this, 'compare_settings'));

            add_submenu_page(null, 'Admin Notice', 'Admin Notice',  'manage_options', 'th_admin_notice_page', array($plugin_admin, 'hide_thwwc_admin_notice'));
        }
        /**
         * Function to wishlist settings page.
         *
         * @return void
         */
        public function wishlist_settings()
        {
            include_once THWWC_PATH."/templates/thwwac-wishlist.php";
        }

        /**
         * Function to compare settings page.
         *
         * @return void
         */
        public function compare_settings()
        {
            include_once THWWC_PATH."/templates/thwwac-compare.php";
        }
    }
endif;