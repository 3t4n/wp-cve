<?php
/**
 * @author  mideal
 * @package Question answer
 * @version 1.2.3
 * 
 * Plugin Name: Question answer
 * Plugin URI: http://mideal.ru/contacts/
 * Description: Question answer, ajax, bootstrap plugin with gravatar avatar and Google reCaptcha 2. Looks like chat.
 * Author: Mideal
 * Version: 1.2.3
 * Requires at least: 3.0
 * Tested up to: 4.9.8
 * Author URI: http://mideal.ru/
 * 
 * Text Domain: question-answer-faq
 * Domain Path: /languages/
*/
/*  Copyright 2018  mideal  (email: midealf@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) || exit;


if ( ! class_exists( 'MidealQA' ) ) :
    global $wpdb;
    final class MidealQA {

        public function __construct() {
            $this->define_constants();
            $this->includes();
            $this->init_hooks();
            $this->register();
            add_action( 'init', array( $this, 'create_mideal_faq' ));
        }

        private function define_constants() {
            $this->define( 'MQA_PLUGIN_URL', plugins_url( '', __FILE__ ) );
            $this->define( 'MQA_ABSPATH', plugin_dir_path(__FILE__));

        }

        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        public function register()
        {
            add_action( 'admin_menu', array( $this, 'mideal_faq_create_menu' ));
            $plugin = plugin_basename( __FILE__ );
            add_filter( "plugin_action_links_{$plugin}", array( $this, 'mideal_faq_add_settings_link' ));
        }

        public function mideal_faq_create_menu() {
            add_submenu_page( 'edit.php?post_type=mideal_faq', 'Question answer setting', __( 'Settings' ), 'manage_options', 'settings', array( $this, 'mideal_faq_settings_page' ) );
        }

        public function mideal_faq_settings_page() {
            include_once( MQA_ABSPATH . 'templates/admin.php' );
        }
        
        // --------------------add setting linc in admin panel----------------------------
        public function mideal_faq_add_settings_link( $links ) {
            $settings_link = '<a href="edit.php?post_type=mideal_faq&page=settings">' . __( 'Settings' ) . '</a>';
            array_push( $links, $settings_link );
            return $links;
        }
        
        public function includes() {
            if ( $this->is_request( 'admin' ) ) {
                add_action('admin_enqueue_scripts', array( $this, 'register_admin_scripts' ));
                include_once( MQA_ABSPATH . 'includes/admin/admin.php' );
            }

            if ( $this->is_request( 'frontend' ) ) {
                add_action('wp_enqueue_scripts', array( $this, 'register_frontend_scripts' ));
                include_once( MQA_ABSPATH . 'includes/frontend.php' );
            }
        }

        private function init_hooks() {
            add_action( 'plugins_loaded', array( $this, 'init_plugin' ));
        }

        public function init_plugin() {

            // ------------------------------------ Add translate------------------------------------
            load_plugin_textdomain( 'question-answer-faq', false, basename( dirname( __FILE__ ) ) . '/languages/' );
        }



        /**
         * Check type request
         *
         * @param  string $type admin, ajax, cron or frontend.
         * @return bool
         */
        private function is_request( $type ) {
            switch ( $type ) {
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined( 'DOING_AJAX' );
                case 'cron' :
                    return defined( 'DOING_CRON' );
                case 'frontend' :
                    return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
            }
        }




        public function register_frontend_scripts() {
            wp_enqueue_style( 'mideal-faq-style', MQA_PLUGIN_URL.'/css/style.css',false,'1.0','all' );

            if(get_option( 'mideal_faq_setting_avatar_smallsize' )){
                wp_enqueue_style( 'mideal-faq-avatar_small', MQA_PLUGIN_URL.'/css/small_size.css',false,'1.0','all' );
            }else {
                wp_enqueue_style( 'mideal-faq-avatar_big', MQA_PLUGIN_URL.'/css/big_size.css',false,'1.0','all' );
            }

            if(get_option( 'mideal_faq_setting_recaptcha' )){
                wp_enqueue_script( 'mideal-faq-google_recaptcha', 'https://www.google.com/recaptcha/api.js', array( ),1.0,true );
            }

            if(!get_option( 'mideal_faq_setting_dont_connect_bootstrap' )){
                wp_enqueue_style( 'mideal-faq-bootstrap', MQA_PLUGIN_URL.'/css/bootstrap.css',false,'1.0','all' );
            }

            // --------------------add script plugin, check jquery-----------------------------
            wp_enqueue_script( 'mideal-faq-base', MQA_PLUGIN_URL.'/js/app.js', array( 'jquery' ),1.0,true );


            // --------------------------------- Add support ajax----------------------------------
            wp_localize_script('mideal-faq-base', 'midealfaqajax', 
                array(
                    'url' => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'midealfaqajax-nonce' )
                )
            );  


            // ------------------------------- Add script translate---------------------------------
            $translation_array = array( 
             'errorajax' => __( 'Unfortunately, an error occurred. Try again later please', "question-answer-faq" ),
             'okajax' => __( 'Thank you for your question. It will appear after moderation', "question-answer-faq" ),
             'publish' => __("Publish", "question-answer-faq"),
             'unpublish' => __("Unpublish", "question-answer-faq"),
             'edit' => __("Edit", "question-answer-faq"),
             'save' => __("Save", "question-answer-faq"),
             'nogooglecapcha' => __("Google capcha check error", "question-answer-faq"),
             'nameanswer' => esc_attr( get_option( 'mideal_faq_setting_answer_name', __("Answer", "question-answer-faq")) ),
             'backgroundanswer' => get_option( 'mideal_faq_setting_answer_background',"#3cb868"),
             'coloranswer' => get_option( 'mideal_faq_setting_answer_color_text','#FFFFFF'),
            );
            if(get_option("mideal_faq_setting_answer_image")){
                $translation_array['imageanswer'] = get_option("mideal_faq_setting_answer_image");
            }else{
                $translation_array['imageanswer'] = MQA_PLUGIN_URL."/img/avatar-default.png";
            } 
            wp_localize_script( 'mideal-faq-base', 'mideal_faq_l10n', $translation_array );
        }

        public function register_admin_scripts() {

            wp_enqueue_style( 'mideal-faq-admin-style', MQA_PLUGIN_URL.'/css/admin.css',false,'1.0','all' );
            wp_enqueue_style( 'mideal-faq-assets-colorpicker', MQA_PLUGIN_URL.'/assets/bootstrap-colorpicker-master/css/bootstrap-colorpicker.min.css',false,'1.0','all' );
            wp_enqueue_script( 'mideal-faq-assets-colorpicker', MQA_PLUGIN_URL.'/assets/bootstrap-colorpicker-master/js/bootstrap-colorpicker.min.js', array( 'jquery' ),1.0,true );
            wp_enqueue_script( 'mideal-faq-admin', MQA_PLUGIN_URL.'/js/admin.js', array( 'jquery' ),1.0,true );
        }

        //------------------------------- New type post --------------------------------------------
        public function create_mideal_faq() {
            register_post_type( 'mideal_faq',
                array(
                    'labels' => array(
                    'name'               => __("Question", "question-answer-faq"),
                    'singular_name'      => __("Question", "question-answer-faq"),
                    'add_new'            => __("Add question", "question-answer-faq"),
                    'add_new_item'       => __("Add question", "question-answer-faq"),
                    'edit_item'          => __("Edit question", "question-answer-faq"),
                    'new_item'           => __("New question", "question-answer-faq"),
                    'menu_name'          => __("Question", "question-answer-faq"),
                    ),
                    'public' => true,
                    'menu_position' => 15,
                    'supports' => array( 'title', 'editor' ),
                   // 'menu_icon' => plugins_url( 'img/icon.png', __FILE__ ),
                )
            );
        }

        public function plugin_activate() {
            require_once MQA_ABSPATH.'includes/plugin-activate.php';
            // $this->create_mideal_faq();
            // flush_rewrite_rules();
            QAPluginActivate::activate();
        }

    }


    $MidealQA = new MidealQA();

    require_once MQA_ABSPATH.'includes/plugin-deactivate.php';
    register_activation_hook( __FILE__, array( $MidealQA, 'plugin_activate' ) );
    register_deactivation_hook( __FILE__, array( 'QAPluginDeactivate', 'deactivate' ) );

endif;