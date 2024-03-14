<?php

/**
 * I Agree! Popups
 *
 * @package   I_Agree_Popups
 * @license   GPLv2 or later
**/

/**
 * Some admin bits (Glancer, hide 'Edit Popup')
 *
 * @package I_Agree_Popups
**/

class I_Agree_Admin {

    protected $registration_handler;

    public function __construct( $registration_handler ) {
        
        $this->registration_handler = $registration_handler;
        
    }
    
    // Initialise function
    public function init() {

        add_action( 'dashboard_glance_items', array( $this, 'add_glance_counts' ) );
        add_action('admin_head', array( $this, 'popup_admin_css' ));

    }

    /**
     * Add counts to "At a Glance" dashboard widget in WP 3.8+
     *
     * @since 1.0
    **/
    public function add_glance_counts() {
        
        $glancer = new Gamajo_Dashboard_Glancer;
        $glancer->add( $this->registration_handler->post_type, array( 'publish', 'pending' ) );
        
    }
    
    /**
     * Hides 'Edit Popup' from the toolbar when in Admin
     *
     * @since 1.0
    **/
    public function popup_admin_css() {
        
        echo '<style>#wpadminbar #wp-admin-bar-i-agree-popups, .post-type-i-agree-popup .misc-pub-curtime, .post-type-i-agree-popup .misc-pub-visibility {display:none!important;}</style>';

    }

}