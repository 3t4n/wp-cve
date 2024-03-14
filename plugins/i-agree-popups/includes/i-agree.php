<?php

/**
 * I Agree! Popups
 *
 * @package   I_Agree_Popups
 * @license   GPLv2 or later
**/

class I_Agree_Popups {

    const VERSION = '1.0';

    const PLUGIN_SLUG = 'i-agree-popups';

    protected $registration_handler;

    public function __construct( $registration_handler ) {

        $this->registration_handler = $registration_handler;
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );;

    }

    public function activate() {
        
        $this->registration_handler->register();
        flush_rewrite_rules();
        
    }

    public function deactivate() {
        
        flush_rewrite_rules();
        
    }

    public function load_plugin_textdomain() {
        
        $domain = self::PLUGIN_SLUG;
        load_plugin_textdomain( $domain, FALSE, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages' );
        
    }

}