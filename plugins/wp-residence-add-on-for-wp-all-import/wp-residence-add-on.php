<?php

/*
Plugin Name: WP All Import - WP Residence Add-On
Plugin URI: http://www.wpallimport.com/
Description: Supporting imports into the WP Residence theme.
Version: 1.3.0
Author: Soflyy
*/

if ( ! class_exists( 'WPAI_WP_Residence_Add_On' ) ) {

    final class WPAI_WP_Residence_Add_On {

        protected static $instance;

        protected $add_on;
        
        public $post_type = '';

        static public function get_instance() {
            if ( self::$instance == NULL ) {
                self::$instance = new self();
            }
            
            return self::$instance;
        }

        protected function __construct() {
            $this->constants();
            $this->includes();
            $this->hooks();

            $this->add_on = new RapidAddon( 'WP Residence Add-On', 'realhomes_addon' );

            add_action( 'init', array( $this, 'init' ) );
        }

        public function init() {
            // Helper functions to get post type and other things.
            $helper = new WPAI_WP_Residence_Add_On_Helper();
            $this->post_type = $helper->get_post_type();
            
            // We have to check the post type to output different fields.
			switch ( $this->post_type ) {
				// Importing 'Agents'
				case 'estate_agent':
					$this->estate_agent_fields();
				    break;

				case 'estate_property':
					$this->property_fields();
					break;
            }

            $this->add_on->set_import_function( array( $this, 'import' ) );

            $this->add_on->run( array(
                'themes'     => array( 'WpResidence' ),
                'post_types' => array( 'estate_agent', 'estate_property' )
            ) );

            $notice_message = 'The WPResidence Add-On requires WP All Import <a href="http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wpresidence" target="_blank">Pro</a> or <a href="https://wordpress.org/plugins/wp-all-import/">Free</a>, and the <a href="https://themeforest.net/item/wp-residence-real-estate-wordpress-theme/7896392" target="_blank">WPResidence Theme</a>.';

            $this->add_on->admin_notice( $notice_message, array( 'themes' => array( 'WpResidence' ) ) );
		
        }

        public function get_add_on() {
            return $this->add_on;
        }
        
        public function get_post_type() {
            return $this->post_type;
        }

        public function estate_agent_fields() {
            $fields = new WPAI_WP_Residence_Agents_Field_Factory( $this->add_on );

            $fields->add_field( 'agent_text_fields' );
            $fields->add_field( 'agent_options_field' );			
        }

        public function property_fields() {
            $fields = new WPAI_WP_Residence_Property_Fields( $this->add_on );

            $fields->add_field( 'property_images' );
            $fields->add_field( 'text_image_custom_details' );
            $fields->add_field( 'property_location' );
            $fields->add_field( 'advanced_options' );
         }    
        
        public function import( $post_id, $data, $import_options, $article ) {
            
            $importer = new WPAI_WP_Residence_Add_On_Importer( $this->add_on, $this->post_type );
            $importer->import( $post_id, $data, $import_options, $article );

        }
        
        public function hooks() {
            add_action( 'pmxi_before_xml_import', array( $this, 'sync_import_options' ), 10, 1 );
        }

        public function includes() {
            include WPAI_WPRES_PLUGIN_DIR_PATH . 'rapid-addon.php';
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            include_once( WPAI_WPRES_PLUGIN_DIR_PATH . 'classes/class-field-factory-agents.php' );            
            include_once( WPAI_WPRES_PLUGIN_DIR_PATH . 'classes/class-field-factory-properties.php' );
            include_once( WPAI_WPRES_PLUGIN_DIR_PATH . 'classes/class-helper.php' ); 
            include_once( WPAI_WPRES_PLUGIN_DIR_PATH . 'classes/class-importer.php' );
            include_once( WPAI_WPRES_PLUGIN_DIR_PATH . 'classes/class-importer-properties.php' );;
            include_once( WPAI_WPRES_PLUGIN_DIR_PATH . 'classes/class-importer-agents.php' );            
            include_once( WPAI_WPRES_PLUGIN_DIR_PATH . 'classes/class-importer-location.php' );
        }

        public function constants() {
            if ( ! defined( 'WPAI_WPRES_PLUGIN_DIR_PATH' ) ) {
                // Dir path
                define( 'WPAI_WPRES_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
            }
            
            if ( ! defined( 'WPAI_WPRES_ROOT_DIR' ) ) {
                // Root directory for the plugin.
                define( 'WPAI_WPRES_ROOT_DIR', str_replace( '\\', '/', dirname( __FILE__ ) ) );
            }

            if ( ! defined( 'WPAI_WPRES_PLUGIN_PATH' ) ) {
                // Path to the main plugin file.
                define( 'WPAI_WPRES_PLUGIN_PATH', WPAI_WPRES_ROOT_DIR . '/' . basename( __FILE__ ) );
            }

            if ( ! defined( 'WPAI_WPRES_ADDON_FIELD_PREFIX' ) ) {
                define( 'WPAI_WPRES_ADDON_FIELD_PREFIX', 'wpai_wpres_addon_' );
            }
        }

        public function sync_import_options( $import_id ) {
                $import = new PMXI_Import_Record();
                $import->getById( $import_id );

                if ( ! $import->isEmpty() ) {
                    $options = $import->options;
                    $property_images_key = 'pmxi_property_imagesdo_not_remove_images';
                    $regular_images_key = 'do_not_remove_images';

                    if ( array_key_exists( $property_images_key, $options ) && array_key_exists( $regular_images_key, $options ) ) {
                        $options[ $regular_images_key ] = $options[ $property_images_key ];
                        $import->set( array( 'options' => $options ) )->save();
                    }
                }
            }
        }
    WPAI_WP_Residence_Add_On::get_instance();
}
