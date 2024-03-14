<?php

namespace Element_Ready\Base;
use Elementor\Core\Settings\Manager as SettingsManager;

/**
 * Elementor Helper Utilities Class
 * Page Meta Settings
 * Plugin Global Settings
 * @since 1.0
 * @author quomodosoft.com
 */
class Elementor_Helper {
        
		/**
		 * The singleton instance
         * @since 1.0
		 */
		static private $instance = null;
	
		/**
		 * No initialization allowed
		 */
		private function __construct() {
            
		}

		/**
		 * No cloning allowed
		 */
		private function __clone() {
		}

		static public function getInstance() {

			if ( self::$instance == null ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

        /**
         * Elementor Page Settings
        * param  1 | Page Settings key
        * param  2 | Page id ( optional )
        * @return mix string | array | int 
        */ 
        public static function page_settings( $key = false, $p_id = false ){
        
            if( !is_page() ){
                return false;
            }

            if( !$key ){
                return false;
            }

            $page_id = get_queried_object_id();

            if( is_numeric( $p_id ) ){
               $page_id = $p_id;
            }
        
            // Get the page settings manager $page->get_data( 'settings' )
            $page_settings_manager = SettingsManager::get_settings_managers( 'page' );
            $page_settings_model   = $page_settings_manager->get_model( $page_id );
            $settings_data         = $page_settings_model->get_settings( $key );
        
            return $settings_data; 
        }

         /**
         * Helper function to return a setting.
         *
         * Saves 2 lines to get kit, then get setting. Also caches the kit and setting.
         * @since 1.0
         * @author quomodsoft.com
         * @param string $setting_id
         * Plugin::$instance->kits_manager->get_active_kit_for_frontend()->get_settings_for_display('wr_login_redirect');
         * @return string|array same as the Elementor internal function does.
         */
        public static function get_global_setting( $setting_id, $default = '' ) {
            
            if(! did_action( 'elementor/loaded' )){
                return;
            }

            global $woo_ready_el_global_settings;
    
            $return = $default;
    
            if ( ! isset( $woo_ready_el_global_settings['kit_settings'] ) ) {
                $kit =  \Elementor\Plugin::$instance->documents->get( \Elementor\Plugin::$instance->kits_manager->get_active_id(), false );
                if(is_object( $kit )){
                    $woo_ready_el_global_settings['kit_settings'] = $kit->get_settings();
                }
               
            }
    
            if ( isset( $woo_ready_el_global_settings['kit_settings'][ $setting_id ] ) ) {
                $return = $woo_ready_el_global_settings['kit_settings'][ $setting_id ];
            }
           
            return apply_filters( 'element_ready_el_global_' . $setting_id, $return );
        }

        static function get_all_posts_url($posttype = 'post') {
            $args = array(
                'post_type' => $posttype,
                'post_status' => 'publish',
                'posts_per_page' => -1
            );
    
            $post_list = array();
            if ($data = get_posts($args)) {
                foreach ($data as $key) {
                    $post_list[$key->ID] = $key->post_title;
                }
            }
            return $post_list;
        }
   
        static function display_elementor_content($id = null){
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display(esc_attr($id),true);
        }
        

    }       