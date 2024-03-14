<?php

namespace Pagup\Pctag\Controllers;

use  Pagup\Pctag\Core\Option ;
use  Pagup\Pctag\Core\Plugin ;
use  Pagup\Pctag\Core\Request ;
class SettingsController
{
    protected  $safe = array(
        "enable_pctag",
        "search_event",
        "addtocart_event",
        "checkout_event",
        "viewCategory_event",
        "pctag-bigta",
        "pctag-mobilook",
        "pctag-vidseo",
        "boost-alt",
        "boost-robot",
        'pctag-settings',
        'pctag-faq',
        'pctag-recs',
        "pctag_remove_settings"
    ) ;
    public function add_settings()
    {
        add_menu_page(
            'Pinterest Conversion Tags Settings',
            'Pinterest Tags',
            'manage_options',
            'pctag',
            array( &$this, 'page' ),
            'dashicons-pinterest'
        );
    }
    
    public function page()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you are not allowed to access this page.', "add-pinterest-conversion-tags" ) );
        }
        // only users with `unfiltered_html` can edit scripts.
        if ( !current_user_can( 'unfiltered_html' ) ) {
            wp_die( __( 'Sorry, you are not allowed to edit this page. Ask your administrator for assistance.', "add-pinterest-conversion-tags" ) );
        }
        $success = '';
        
        if ( isset( $_POST['update'] ) ) {
            if ( function_exists( 'current_user_can' ) && !current_user_can( 'manage_options' ) && !current_user_can( 'unfiltered_html' ) ) {
                die( 'Sorry, not allowed...' );
            }
            check_admin_referer( 'pctag-settings', 'pctag-nonce' );
            if ( !isset( $_POST['pctag-nonce'] ) || !wp_verify_nonce( $_POST['pctag-nonce'], 'pctag-settings' ) ) {
                die( 'Sorry, not allowed. Nonce doesn\'t verify' );
            }
            $options = [
                'enable_pctag'          => Request::post( 'enable_pctag', $this->safe ),
                'pctag_id'              => ( Request::check( 'pctag_id' ) ? sanitize_text_field( $_POST['pctag_id'] ) : '' ),
                'pctag_remove_settings' => Request::post( 'pctag_remove_settings', $this->safe ),
                'boost-robot'           => Request::post( 'boost-robot', $this->safe ),
                'boost-alt'             => Request::post( 'boost-alt', $this->safe ),
                'pctag-mobilook'        => Request::post( 'pctag-mobilook', $this->safe ),
                'pctag-bigta'           => Request::post( 'pctag-bigta', $this->safe ),
                'pctag-vidseo'          => Request::post( 'pctag-vidseo', $this->safe ),
            ];
            update_option( 'pctag', $options );
            // update options
            echo  '<div class="notice pctag-notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings saved.' ) . '</strong></p></div>' ;
        }
        
        $options = new Option();
        $notification = new \Pagup\Pctag\Controllers\NotificationController();
        echo  $notification->support() ;
        //set active class for navigation tabs
        $active_tab = ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], $this->safe ) ? sanitize_key( $_GET['tab'] ) : 'pctag-settings' );
        //Plugin::dd($_POST);
        //var_dump(Option::all());
        // purchase notification
        $purchase_url = "options-general.php?page=pctag-pricing";
        $get_pro = sprintf( wp_kses( __( '<a href="%s">Get Pro version</a> to enable', "add-pinterest-conversion-tags" ), array(
            'a' => array(
            'href'   => array(),
            'target' => array(),
        ),
        ) ), esc_url( $purchase_url ) );
        // Return Views
        if ( $active_tab == 'pctag-settings' ) {
            return Plugin::view( 'settings', compact(
                'active_tab',
                'options',
                'get_pro',
                'success'
            ) );
        }
        if ( $active_tab == 'pctag-faq' ) {
            return Plugin::view( "faq", compact( 'active_tab' ) );
        }
        if ( $active_tab == 'pctag-recs' ) {
            return Plugin::view( "recommendations", compact( 'active_tab' ) );
        }
    }

}
$settings = new SettingsController();