<?php
/**
 * The enqueue functionality of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/base
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\base;

use \THWWC\base\THWWC_Base_Controller;
use \THWWC\base\THWWC_Utils;

if (!class_exists('THWWC_Enqueue')) :
    /**
     * Enqueue class
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Enqueue extends THWWC_Base_Controller
    {
        /**
         * Hook that run for enqueue scripts and style in admin panel and frontend.
         *
         * @return void
         */
        public function register()
        {
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts_style'));
            add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts_style'));
        }

        /**
         * Add debug mode for admin script and style.
         *
         * @return void
         */
        public function admin_enqueue_scripts_style($hook)
        {
            if(strpos($hook, 'toplevel_page_th_wishlist_settings') !== false || strpos($hook, 'wishlist-comparison_page_th_compare_settings') !== false) {
                $debug_mode = apply_filters('thwwc_debug_mode', false);
                $suffix = $debug_mode ? '' : '.min';
                
                $this->enqueue_styles($suffix); 
                $this->enqueue_scripts($suffix);
            }
        }

        /**
         * Styling files for admin panel.
         *
         * @param string $suffix The suffix of style sheets
         *
         * @return void
         */
        public function enqueue_styles($suffix)
        {
            wp_enqueue_style('thwwc-admin-style', THWWC_URL.'assets/admin/css/thwwac-admin'. $suffix .'.css');
        }

        /**
         * Script files for admin panel.
         *
         * @param string $suffix The suffix of js file
         *
         * @return void
         */
        public function enqueue_scripts($suffix)
        {
            wp_register_script('thwwc-vuejs', THWWC_URL.'assets/admin/js/thwwac-vue'. $suffix .'.js');
            wp_register_script('thwwc-vueajax', THWWC_URL.'assets/admin/js/thwwac-axios'. $suffix .'.js');
            wp_register_script('thwwc-vuesort', THWWC_URL.'assets/admin/js/thwwac-sortable'. $suffix .'.js');
            wp_register_script('thwwc-vuedrag', THWWC_URL.'assets/admin/js/thwwac-vuedraggable'. $suffix .'.js');
            wp_register_script('thwwc-vueqs', THWWC_URL.'assets/admin/js/thwwac-vueqs'. $suffix .'.js');
            wp_enqueue_style( 'wp-color-picker' );

            $dep = array('jquery','wp-color-picker','thwwc-vuejs','thwwc-vueajax','thwwc-vuesort','thwwc-vuedrag','thwwc-vueqs');
            wp_enqueue_script('thwwac-admin-script', THWWC_URL.'assets/admin/js/thwwac-admin'. $suffix .'.js', $dep, '1.0.0.0', true);
            
            
            $script_var = array(
                'admin_url' => admin_url(),
                'admin_path'=> plugins_url('/', __FILE__),
                'ajaxurl'   => admin_url('admin-ajax.php'),
                'error_msg' => apply_filters('thwwc_save_error_msg',__('There seems to be an error with this save.','wishlist-and-compare')),
                'reset_msg' => apply_filters('thwwc_reset_msg',__('Are you sure? all your changes will be deleted.','wishlist-and-compare')),
                'ajaxnonce' => wp_create_nonce('thwwac_ajax_security'),
                'shopnonce' => wp_create_nonce('thwwac_shop_security'),
                'productnonce' => wp_create_nonce('thwwac_product_security'),
                'wishpagenonce' => wp_create_nonce('thwwac_wishpage_security'),
                'counternonce' => wp_create_nonce('thwwac_counter_security'),
                'socialnonce' => wp_create_nonce('thwwac_social_security'),
                'comparenonce' => wp_create_nonce('thwwac_compare_security'),
                'tablenonce' => wp_create_nonce('thwwac_table_security'),
                'resetnonce' => wp_create_nonce('thwwac_reset_security'),
                'site_url'    => site_url(),
                'wwmac_pageid' => __($this->pagedata(), 'wishlist-and-compare'),
            );
            wp_localize_script('thwwac-admin-script', 'thwwac_var', $script_var); 
            
        }

        /**
         * Add debug mode for frontend script and style.
         *
         * @return void
         */
        public function wp_enqueue_scripts_style()
        {
            // if(is_shop() || is_product() || is_product_category() || is_archive() || apply_filters('thwwc_enqueue_public_scripts', false)){
                $debug_mode = apply_filters('thwwc_debug_mode', false);
                $suffix = $debug_mode ? '' : '.min';
                
                $this->enqueue_styles_wp($suffix); 
                $this->enqueue_scripts_wp($suffix);
            // }
        }

        /**
         * Styling files for frontend.
         *
         * @param string $suffix The suffix of stylesheet
         *
         * @return void
         */
        public function enqueue_styles_wp($suffix)
        {
            wp_enqueue_style('thwwac-public-style', THWWC_URL.'assets/public/css/thwwac-public'. $suffix .'.css');
            wp_enqueue_style('thwwac-feather', THWWC_URL . 'assets/libs/feather/feather.css');

            $options = THWWC_Utils::thwwc_get_general_settings();
            $custom_css = isset($options['custom_css_wishlist']) ? $options['custom_css_wishlist'] : '';
            
            wp_add_inline_style( 'thwwac-public-style', $custom_css );

        }

        /**
         * Script files for frontend.
         *
         * @param string $suffix The suffix of js file
         *
         * @return void
         */
        public function enqueue_scripts_wp($suffix)
        {
            $dep = array( 'jquery', 'jquery-ui-sortable' );
            wp_enqueue_script('thwwac-public-script', THWWC_URL.'assets/public/js/thwwac-public'. $suffix .'.js', $dep, '1.0.0.0', false);
            $general_settings = THWWC_Utils::thwwc_get_general_settings();
            $remove_on_second_click = isset($general_settings['remove_on_second_click']) && $general_settings['remove_on_second_click'] == 'true' ? true : false;

            $script_var = array(
                'admin_url' => admin_url(),
                'admin_path'=> plugins_url('/', __FILE__),
                'ajaxurl'   => admin_url('admin-ajax.php'),
                'site_url'  => site_url(),
                'addwishnonce' => wp_create_nonce('thwwac_addwish_security'),
                'removewishnonce' => wp_create_nonce('thwwac_removewish_security'),
                'backclicknonce' => wp_create_nonce('thwwac_backclick_security'),
                'allcartnonce' => wp_create_nonce('thwwac_allcart_security'),
                'multiactionnonce' => wp_create_nonce('thwwac_multiaction_security'),
                'cartremovenonce' => wp_create_nonce('thwwac_cartremove_security'),
                'filternonce' => wp_create_nonce('thwwac_filter_security'),
                'pdctdetailsnonce' => wp_create_nonce('thwwac_pdctdetails_security'),
                'addcmpnonce' => wp_create_nonce('thwwac_addcmp_security'),
                'upcmpnonce' => wp_create_nonce('thwwac_upcmp_security'),
                'remcmpnonce' => wp_create_nonce('thwwac_remcmp_security'),
                'cmpcartnonce' => wp_create_nonce('thwwac_cmpcart_security'),
                'cmphsnonce' => wp_create_nonce('thwwac_cmphs_security'),
                'variationnonce' => wp_create_nonce('thwwac_variation_security'),
                'remove_on_second_click' => apply_filters('thwwc_remove_wishlist_second_click', $remove_on_second_click),
            );
            wp_localize_script('thwwac-public-script', 'thwwac_var', $script_var); 
        }

        /**
         * Pagedata returned for script variable for vue page options.
         *
         * @return array
         */
        public function pagedata()
        {
            $pages = get_pages();
            foreach ($pages as $page_data) {
                $title['id'][] = $page_data->ID;
                $title['title'][] = $page_data->post_title;
            }
            return $title;
        }

    }
endif;