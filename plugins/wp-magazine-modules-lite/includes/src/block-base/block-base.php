<?php
/**
 * Handles the overall blocks and common functionalities.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if( ! class_exists( 'Wpmagazine_Modules_Lite_Blocks_Base' ) ) :
    class Wpmagazine_Modules_Lite_Block_Base {
        /**
         * run the class 
         *
         */
        public function __construct() {
            add_action( 'init', array( $this, 'register_and_render' ) );
        }

        /**
         * Register the blocks and renders the content if block requires.
         * 
         * @return void
         */
        public function register_and_render() {
            $args = array();
            if( method_exists( $this, 'render_callback' ) ) {
                $args = array(
                    'render_callback' => array( $this, 'render_callback' ),
                );
                
                $attributes = $this->get_all_attributes();
                $args['attributes'] = $attributes;
            }
            register_block_type( 'wpmagazine-modules/'.$this->block_name, $args );
        }

        /**
         * Merges the atttribute of the block with "common attributes"
         * 
         * @return array
         */
        public function get_all_attributes() {
            $common_attributes = $this->get_common_attributes();
            if( method_exists( $this, 'get_attributes' ) ) {
                $block_attributes = $this->get_attributes();
                $all_attrs = wp_parse_args( $block_attributes, $common_attributes );
            } else {
                $all_attrs = $common_attributes;
            }
            return apply_filters( 'wpmagazine_modules_lite_block_all_attributes', $all_attrs );
        }

        /**
         * Common attributes to all blocks
         * 
         * @return array
         */
        public function get_common_attributes() {
            $common_attrs = array(
                'align' => array(
                    'type'      => 'string',
                    'default'   => 'wide'
                ),
                'blockID'   => array(
                    'type'      => 'string',
                    'default'   => ''
                ),
                'blockTitle'    => array(
                    'type'      => 'string',
                    'default'   => esc_html__( 'Block Title', 'wp-magazine-modules-lite' )
                ),
                'blockTitleLayout'  => array(
                    'type'      => 'string',
                    'default'   => 'default'
                ),
                'blockTitleAlign' => array(
                    'type'      => 'string',
                    'default'   => 'left'
                ),
                'permalinkTarget' => array(
                    'type'      => 'string',
                    'default'   => '_blank'
                ),
                'blockLayout' => array(
                    'type'      => 'string',
                    'default'   => 'layout-default'
                ),
                'blockPrimaryColor' => array(
                    'type'      => 'string',
                    'default'   => '#029FB2'
                ),
                'blockHoverColor'   => array(
                    'type'      => 'string',
                    'default'   => '#029FB2'
                ),
                'typographyOption' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'blockTitleFontFamily' => array(
                    'type' => 'string',
                    'default' => 'Yanone Kaffeesatz'
                ),
                'blockTitleFontWeight' => array(
                    'type' => 'string',
                    'default' => '700'
                ),
                'blockTitleFontSize' => array(
                    'type' => 'number',
                    'default' => 32
                ),
                'blockTitleFontStyle' => array(
                    'type' => 'string',
                    'default' => 'normal'
                ),
                'blockTitleTextTransform' => array(
                    'type' => 'string',
                    'default' => 'Uppercase'
                ),
                'blockTitleTextDecoration' => array(
                    'type' => 'string',
                    'default' => 'none'
                ),
                'blockTitleColor' => array(
                    'type' => 'string',
                    'default' => '#3b3b3b'
                ),
                'blockTitleLineHeight' => array(
                    'type' => 'number',
                    'default' => 1.5
                ),
                'blockTitleBorderColor' => array(
                    'type' => 'string',
                    'default' => '#f47e00'
                ),
                'blockDynamicCss'   => array(
                    'type'  => 'string'
                ),
            );
            return apply_filters( 'wpmagazine_modules_lite_block_common_attributes', $common_attrs );
        }
    }
endif;