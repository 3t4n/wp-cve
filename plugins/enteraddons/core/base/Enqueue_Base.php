<?php
namespace Enteraddons\Core\Base;
/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !defined( 'WPINC' ) ) {
    die;
}

if( !class_exists('Enqueue_Base') ) {

    class Enqueue_Base {

        public $assetsUrl;

        public $style;

        public $script;

        public $localize;
        
        function __construct() {
            $this->assets_dir_url();
            $this->set_scripts();
            add_action( 'elementor/frontend/after_register_scripts', [$this, 'init_scripts'] );
        }

        public function set_scripts() {}

        public function init_scripts() {
            // Style
            $this->enqueue_style();
            // Scripts
            $this->enqueue_scripts();
            // Localize Script
            $this->localize_script();
        }

        public function enqueue_style() {

            if( !empty( $this->style ) ) {

                foreach( $this->style as $style ) {
                    $deps    = !empty( $style['dependency'] ) ? $style['dependency'] : array();
                    $version = !empty( $style['version'] ) ? $style['version'] : ENTERADDONS_VERSION;
                    $media   = !empty( $style['media'] ) ? $style['media'] : 'all';

                    if( !empty( $style['register'] ) && $style['register'] == true ) {
                        wp_register_style( $style['handle'], $style['url'], $deps,$version , $media );
                    } else {
                        wp_enqueue_style( $style['handle'], $style['url'], $deps, $version, $media );
                    }
   
                }

            }

        }

        public function enqueue_scripts() {

            if( !empty( $this->script ) ) {

                foreach( $this->script as $script ) {

                    $deps    = !empty( $script['dependency'] ) ? $script['dependency'] : array();
                    $version = !empty( $script['version'] ) ? $script['version'] : ENTERADDONS_VERSION;
                    $in_footer   = !empty( $script['in_footer'] ) ? $script['in_footer'] : true;

                    if( !empty( $script['register'] ) && $script['register'] == true ) {
                        wp_register_script( $script['handle'], $script['url'], $deps, $version, $in_footer );
                    } else {
                        wp_enqueue_script( $script['handle'], $script['url'], $deps, $version, $in_footer );
                    }

                }

            }

        }
        
        public function localize_script() {
            
            if( !empty( $this->localize ) ) {
                foreach( $this->localize as $script ) {
                    wp_localize_script( $script['handle'], $script['object_name'], $script['data'] );
                } 
            }

        }

        public function assets_dir_url() {}

    }

}