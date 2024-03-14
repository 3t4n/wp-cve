<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    SoftHopper
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Default_Compat' ) ) {
    class Soft_template_Default_Compat {
        /**
         *  Initiator
         */
        public function __construct() {
            add_action( 'wp', [ $this, 'hooks' ] );
        }

        /**
         *  Run all the Actions / Filters.
         */

        public function hooks() {
            // Get Conditions Data
            $header_template_status = soft_template_core()->locations->get_location_status('header');
            $footer_template_status = soft_template_core()->locations->get_location_status('footer');

            $single_template_status = soft_template_core()->locations->get_location_status('single');
            $page_template_status = soft_template_core()->locations->get_location_status('page');
            $archive_template_status = soft_template_core()->locations->get_location_status('archive');

            if ( $header_template_status ) {
                // Replace header.php template.
                add_action( 'get_header', [ $this, 'override_header' ] );
            }
            if ( $footer_template_status ) {
                // Replace footer.php template.
                add_action( 'get_footer', [ $this, 'override_footer' ] );
            }

            if( $single_template_status ) {
                add_filter( 'template_include', [ $this, 'single_template_include' ], 11 ); 
            }            
            
            if( $page_template_status ) {
                add_filter( 'template_include', [ $this, 'page_template_include' ], 11 ); 
            }         
            
            if( $archive_template_status ) {
                add_filter( 'template_include', [ $this, 'archive_template_include' ], 11 ); 
            }

            //add_filter( 'template_include', [ $this, 'template_include' ], 11 ); // 11 = after WooCommerce.
        }

        /**
         * Function for overriding the header in the elmentor way.
         *
         * @since 1.2.0
         *
         * @return void
         */
        public function override_header() {
            require soft_template_core()->plugin_path('overrides/header-override.php');
            
            $templates   = [];
            $templates[] = 'header.php';
            // Avoid running wp_head hooks again.
            remove_all_actions( 'wp_head' );
            ob_start();
            locate_template( $templates, true );
            ob_get_clean();
        }

        /**
         * Function for overriding the footer in the elmentor way.
         *
         * @since 1.2.0
         *
         * @return void
         */
        public function override_footer() {
            require soft_template_core()->plugin_path('overrides/footer-override.php');
            $templates   = [];
            $templates[] = 'footer.php';
            // Avoid running wp_footer hooks again.
            remove_all_actions( 'wp_footer' );
            ob_start();
            locate_template( $templates, true );
            ob_get_clean();
        }        
        
        /**
         * Function for overriding single template
         *
         * @since 1.2.0
         *
         * @return void
         */
        public function single_template_include( $template  ) {
          
            if ( is_singular('post') ) {
                $template = soft_template_core()->plugin_path('overrides/single-override.php');
            } 

            return $template;
        }        
        /**
         * Function for overriding page template
         *
         * @since 1.2.0
         *
         * @return void
         */
        public function page_template_include( $template  ) {
           
            if ( is_singular('page') ) {
                $template = soft_template_core()->plugin_path('overrides/single-page-override.php');
            }      
              
            return $template;
        }      
        /**
         * Function for overriding archive template
         *
         * @since 1.2.0
         *
         * @return void
         */
        public function archive_template_include( $template  ) {
           
            if ( 'posts' == get_option( 'show_on_front' ) || is_archive() || is_tax() || is_home() || is_search() ) {
                $template = soft_template_core()->plugin_path('overrides/archive-override.php');
            }       
              
            return $template;
        }

        public function add_header_template() {
            $done = soft_template_core()->locations->do_location( 'header');
            return $done;
        }
    }
}