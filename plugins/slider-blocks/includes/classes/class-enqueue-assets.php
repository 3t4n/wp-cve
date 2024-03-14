<?php
/**
 * Enqueue Assets 
 * @package GutSliderBlocks
 */
 
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

 if( ! class_exists( 'GutSlider_Assets' ) ) {

    class GutSlider_Assets {
        
        /**
         * Constructor
         * return void 
         */
        public function __construct() {
            add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ], 1 );
            add_action( 'enqueue_block_assets', [ $this, 'enqueue_assets' ] );
        }

        /**
         * Enqueue Block Assets [ Editor Only ]
         * return void
         */
        public function enqueue_editor_assets(){

            // enqueue patterns 
            // if( file_exists( trailingslashit( GUTSLIDER_DIR_PATH ) . './build/patterns/patterns.asset.php' ) ) {
            //     $patterns_dependency_file = include_once trailingslashit( GUTSLIDER_DIR_PATH ) . './build/patterns/patterns.asset.php';
            // }

            // if( is_array( $patterns_dependency_file ) && ! empty( $patterns_dependency_file ) ) {
            //     wp_enqueue_script(
            //         'gutslider-blocks-patterns-script',
            //         trailingslashit( GUTSLIDER_URL ) . './build/patterns/patterns.js',
            //         isset( $patterns_dependency_file['dependencies'] ) ? $patterns_dependency_file['dependencies'] : [],
            //         isset( $patterns_dependency_file['version'] ) ? $patterns_dependency_file['version'] : GUTSLIDER_VERSION,
            //         true
            //     );

            //     wp_enqueue_style(
            //         'gutslider-blocks-patterns-style',
            //         trailingslashit( GUTSLIDER_URL ) . './build/patterns/style-patterns.css',
            //         [],
            //         GUTSLIDER_VERSION
            //     );
            // }

            // global
            if( file_exists( trailingslashit( GUTSLIDER_DIR_PATH ) . './build/global/global.asset.php' ) ){
                $dependency_file = include_once trailingslashit( GUTSLIDER_DIR_PATH ) . './build/global/global.asset.php';
            }
    
            if( is_array( $dependency_file ) && ! empty( $dependency_file ) ) {
                wp_enqueue_script(
                    'gutslider-blocks-global-script',
                    trailingslashit( GUTSLIDER_URL ) . './build/global/global.js',
                    isset( $dependency_file['dependencies'] ) ? $dependency_file['dependencies'] : [],
                    isset( $dependency_file['version'] ) ? $dependency_file['version'] : GUTSLIDER_VERSION,
                    true
                );
            }
    
            wp_enqueue_style(
                'gutslider-blocks-global-style',
                trailingslashit( GUTSLIDER_URL ) . './build/global/global.css',
                [],
                GUTSLIDER_VERSION
            );

            // modules
            if( file_exists( trailingslashit( GUTSLIDER_DIR_PATH ) . './build/modules/modules.asset.php' ) ) {
                $modules_dependency_file = include_once trailingslashit( GUTSLIDER_DIR_PATH ) . './build/modules/modules.asset.php';
            }

            if( is_array( $modules_dependency_file ) && ! empty( $modules_dependency_file ) ) {
                wp_enqueue_script(
                    'gutslider-blocks-modules-script',
                    trailingslashit( GUTSLIDER_URL ) . './build/modules/modules.js',
                    isset( $modules_dependency_file['dependencies'] ) ? $modules_dependency_file['dependencies'] : [],
                    isset( $modules_dependency_file['version'] ) ? $modules_dependency_file['version'] : GUTSLIDER_VERSION,
                    false
                );
            }

            // wp localize script 
            wp_localize_script(
                'gutslider-blocks-modules-script',
                'gutslider_preview',
                [
                    'content'            => trailingslashit( GUTSLIDER_URL ) . 'assets/images/content.svg',
                    'photo_carousel'     => trailingslashit( GUTSLIDER_URL ) . 'assets/images/photo.svg',
                    'testimonial_slider' => trailingslashit( GUTSLIDER_URL ) . 'assets/images/testimonial.svg',
                    'before_after'       => trailingslashit( GUTSLIDER_URL ) . 'assets/images/ba.svg',
                ]
            );
        }

        /**
         * Enqueue Block Assets [ Editor + Frontend ]
         * return void 
         */
        public function enqueue_assets() {

            if( is_admin() ) {
                // swiper style
                wp_enqueue_style(
                    'gutslider-swiper-style',
                    trailingslashit( GUTSLIDER_URL ) . 'assets/css/swiper-bundle.min.css',
                    [],
                    GUTSLIDER_VERSION
                );

                // swiper script
                wp_enqueue_script(
                    'gutslider-swiper-script',
                    trailingslashit( GUTSLIDER_URL ) . 'assets/js/swiper-bundle.min.js',
                    [],
                    GUTSLIDER_VERSION,
                    true
                );
            }

            // enqueue frontend scripts 
			if( ! is_admin() && ( has_block( 'gutsliders/content-slider' ) || has_block( 'gutsliders/any-content' ) || has_block( 'gutsliders/testimonial-slider'  ) || has_block( 'gutsliders/post-slider' ) || has_block( 'gutsliders/photo-carousel' ) || has_block( 'gutsliders/logo-carousel' ) || has_block( 'gutsliders/videos-carousel' ) ) ) {
                // swiper style
                wp_enqueue_style(
                    'gutslider-swiper-style',
                    trailingslashit( GUTSLIDER_URL ) . 'assets/css/swiper-bundle.min.css',
                    [],
                    GUTSLIDER_VERSION
                );

                // swiper script
                wp_enqueue_script(
                    'gutslider-swiper-script',
                    trailingslashit( GUTSLIDER_URL ) . 'assets/js/swiper-bundle.min.js',
                    [],
                    GUTSLIDER_VERSION,
                    true
                );
            }
            if( ! is_admin() && ( has_block( 'gutsliders/photo-carousel' ) )) {
                // lightbox 
                wp_enqueue_script(
                    'gutslider-fslightbox',
                    trailingslashit( GUTSLIDER_URL ) . 'assets/js/fslightbox.js',
                    [],
                    GUTSLIDER_VERSION,
                    true
                );
            }
        }

    }

 }

    new GutSlider_Assets();    // Initialize the class