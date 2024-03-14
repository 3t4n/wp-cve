<?php

/**
 * Plugin Name:    Unbloater
 * Description:    Remove unnecessary code, nags and bloat from WordPress core and certain plugins.
 * Version:        1.6.1
 * Author:         Christoph Rado
 * Author URI:     https://christophrado.de/
 * Tested up to:   6.2
 * Text Domain:    unbloater
 */

Namespace Unbloater;

defined( 'ABSPATH' ) || die();

class Unbloater {
    
    /**
     * Class constructor
     */
    public function __construct() {
        
        add_action( 'init', array( $this, 'i18n' ) );
        
        register_activation_hook( __FILE__, array( $this, 'activation' ) );
        
        require plugin_dir_path( __FILE__ ) . 'classes/ub-init.php';
        new Unbloater_Init();
        
    }
    
    /**
     * Plugin i18n / load textdomain
     */
    public function i18n() {
        load_plugin_textdomain( 'unbloater' );
    }
    
    /**
     * Plugin activation function
     */
    public function activation( $network_wide ) {
        if( is_multisite() && $network_wide ) {
            add_network_option( null, 'unbloater_settings', '' );
        } else {
            add_option( 'unbloater_settings', '' );
        }
    }
    
}

$unbloater = new Unbloater();