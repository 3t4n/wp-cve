<?php

/**
 * Plugin Name: Responsive Google MAP
 * Plugin URI: https://webomnizz.com
 * Description: Responsive Google MAP provides you an easiest way to add Google Map anywhere in your Website. You can easily customize your google map with custom markers, locations, descriptions, images and link.
 * Version: 3.1.5
 * Donate link: https://paypal.me/jogesh06
 * Author: Jogesh Sharma
 * Author URI: https://webomnizz.com
 * License: GPLv3 or later
 * Text Domain: wrg_rgm
 * Domain Path: /languages
 */


/*
Copyright 2014 Jogesh Sharma (email: jogesh at webomnizz.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


// Prevent Direct Access
if( ! defined('WPINC') ) die;

define( 'WRG_RGM_VERSION',       '3.1.5' );
define( 'WRG_RGM_PLUGIN_DIR',    plugin_dir_path( __FILE__ ) );
define( 'WRG_RGM_PLUGIN_URL',    plugin_dir_url( __FILE__ ) );

define( 'WRG_RGM_WP_VERSION',    '5.0' );
define( 'WRG_RGM_PHP_VERSION',   '5.6' );

require WRG_RGM_PLUGIN_DIR . 'includes/class-wrgrgm-notice.php';
require WRG_RGM_PLUGIN_DIR . 'includes/class-wrgrgm-requirements.php';
require WRG_RGM_PLUGIN_DIR . 'includes/admin/classes/rgm-settings.php';

final class WRG_RGM {

    private static $instance;

    public $map_styles;

    public static function load() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WRG_RGM ) ) {
            self::$instance = new self;
            self::$instance->init();
            self::$instance->includes();
            self::$instance->shortcode();

            add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ), 10 );
            add_action( 'plugins_loaded', array( self::$instance, 'objects' ), 10 );
        }

        return self::$instance;
    }

    private function includes() {

        require WRG_RGM_PLUGIN_DIR . 'includes/class-wrgrgm-map.php';
        require WRG_RGM_PLUGIN_DIR . 'includes/class-wrgrgm-map-styles.php';
        require WRG_RGM_PLUGIN_DIR . 'includes/admin/classes/map-settings.php';
        require WRG_RGM_PLUGIN_DIR . 'includes/admin/class-marker.php';
        require WRG_RGM_PLUGIN_DIR . 'includes/class-wrgrgm-shortcode.php';
        require WRG_RGM_PLUGIN_DIR . 'includes/class-wrgrgm-map-widget.php';
        
        if ( is_admin() ) {
            
            require WRG_RGM_PLUGIN_DIR . 'includes/admin/class-wrgrgm-menu.php';
            require WRG_RGM_PLUGIN_DIR . 'includes/admin/class-wrgrgm-settings.php';
            require WRG_RGM_PLUGIN_DIR . 'includes/admin/class-wrgrgm-map-settings.php';
            require WRG_RGM_PLUGIN_DIR . 'includes/admin/class-wrgrgm-advance-settings.php';
            require WRG_RGM_PLUGIN_DIR . 'includes/admin/class-wrgrgm-admin-init.php';
            require WRG_RGM_PLUGIN_DIR . 'includes/admin/class-wrgrgm-review.php';
        }
    }

    private function init() {

        add_action( 'wp_enqueue_scripts', array( $this, 'rgm_map_script' ) );

        if ( empty( RGM_Settings::get_key() ) ) {
            add_action( 'admin_notices', array( $this, 'wrg_rgm_gmap_key_required' ) );
        }

        add_action( 'widgets_init', array( $this, 'register_wrgrgm_map_widget' ) );
    }

    /**
     * Load text domain for the plugins
     *
     * @return void
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'wrg_rgm', false, dirname( plugin_basename( WRG_RGM_PLUGIN_DIR ) ) . '/languages/' );
    }

    public function register_wrgrgm_map_widget() {
        register_widget( 'WRGRGM_Map_Widget' );
    }

    public function objects() {

        $this->map_styles = new WRGRGM_Map_Styles();
    }

    public function rgm_map_script() {
        
        wp_enqueue_script( 'rgm_map_script', WRG_RGM_PLUGIN_URL . 'dist/front.bundle.js', array(), WRG_RGM_VERSION, true );
        wp_localize_script( 'rgm_map_script', 'WRG_RGM', array(
            'GMAP_API_KEY' => RGM_Settings::get_key()
        ));
    }

    public function wrg_rgm_gmap_key_required() {
        if ( ! current_user_can( 'manage_options' ) ) {
			return;
        }

        WRGRGM_Notice::push(array(
            'id' => 'wrgrgm-php-not-compatible',
            'type' => 'error',
            'title' => 'Responsive Google Map',
            'message' => __( 'To make this plugin working, please <a href="'. admin_url('edit.php?post_type=wrg_rgm&page=wrg_rgm_settings') .'">enter Google Map API key.</a>', 'wrg_rgm' )
        ));
    }

    private function shortcode() {
        add_shortcode( 'wrg_rgm', array( 'WRGRGM_Shortcode', 'init' ) );
    }
}

function wrg_rgm() {
    return WRG_RGM::load();
}

$wrg_rgm_requirements = new WRGRGM_Requirements([
    'plugin_name'   => 'Responsive Google Map',
    'wp_version'    => WRG_RGM_WP_VERSION,
    'php_version'   => WRG_RGM_PHP_VERSION,
]);

if ( $wrg_rgm_requirements->check() ) {
    wrg_rgm();
}

unset( $wrg_rgm_requirements );
?>