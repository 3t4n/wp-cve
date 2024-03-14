<?php

namespace Pagup\Twitter\Controllers;

use  Pagup\Twitter\Core\Option ;
use  Pagup\Twitter\Core\Plugin ;
use  Pagup\Twitter\Core\Request ;
class SettingsController
{
    public function add_settings()
    {
        add_options_page(
            'Add Twitter Pixel Settings',
            'Add Twitter Pixel',
            'manage_options',
            'add-twitter-pixel',
            array( &$this, 'page' )
        );
    }
    
    public function page()
    {
        $safe = [
            "remove_settings",
            "enable_on_products",
            "atp-settings",
            "atp-faq"
        ];
        $success = '';
        
        if ( isset( $_POST['update'] ) ) {
            if ( function_exists( 'current_user_can' ) && !current_user_can( 'manage_options' ) ) {
                die( 'Sorry, not allowed...' );
            }
            check_admin_referer( 'atp-settings', 'atp-nonce' );
            if ( !isset( $_POST['atp-nonce'] ) || !wp_verify_nonce( $_POST['atp-nonce'], 'atp-settings' ) ) {
                die( 'Sorry, not allowed. Nonce doesn\'t verify' );
            }
            $options = [
                'twitter_id'      => ( Request::check( 'twitter_id' ) ? sanitize_text_field( $_POST['twitter_id'] ) : '' ),
                'remove_settings' => Request::post( 'remove_settings', $safe ),
            ];
            update_option( 'add-twitter-pixel', $options );
            // update options
            echo  '<div class="notice atp-notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings saved.' ) . '</strong></p></div>' ;
        }
        
        $options = new Option();
        $text_domain = Plugin::domain();
        $notification = new \Pagup\Twitter\Controllers\NotificationController();
        echo  $notification->support() ;
        //set active class for navigation tabs
        $active_tab = ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], $safe ) ? sanitize_key( $_GET['tab'] ) : 'atp-settings' );
        //Plugin::dd($_POST);
        //var_dump(Option::all());
        // purchase notification
        $purchase_url = "options-general.php?page=add-twitter-pixel-pricing";
        $get_pro = sprintf( wp_kses( __( '<a href="%s">Get Pro version</a> to enable', $text_domain ), array(
            'a' => array(
            'href'   => array(),
            'target' => array(),
        ),
        ) ), esc_url( $purchase_url ) );
        // Return Views
        if ( $active_tab == 'atp-settings' ) {
            return Plugin::view( 'settings', compact(
                'active_tab',
                'options',
                'text_domain',
                'get_pro',
                'success'
            ) );
        }
        if ( $active_tab == 'atp-faq' ) {
            return Plugin::view( "faq", compact( 'active_tab', 'text_domain' ) );
        }
    }

}
$settings = new SettingsController();