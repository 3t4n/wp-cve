<?php

namespace Pagup\MetaTags\Controllers;

use  Pagup\MetaTags\Core\Option ;
use  Pagup\MetaTags\Core\Plugin ;
use  Pagup\MetaTags\Core\Request ;
class SettingsController
{
    public function add_settings()
    {
        add_menu_page(
            'Meta Tags for SEO Settings',
            'Meta Tags for SEO',
            'manage_options',
            'meta-tags-for-seo',
            array( &$this, 'page' ),
            'dashicons-pressthis'
        );
    }
    
    public function page()
    {
        $safe = [
            "remove_settings",
            "enable_on_products",
            "pmt-settings",
            "pmt-faq"
        ];
        $progress_bar = '';
        
        if ( isset( $_POST['update'] ) ) {
            if ( function_exists( 'current_user_can' ) && !current_user_can( 'manage_options' ) ) {
                die( 'Sorry, not allowed...' );
            }
            check_admin_referer( 'pmt__settings', 'pmt__nonce' );
            if ( !isset( $_POST['pmt__nonce'] ) || !wp_verify_nonce( $_POST['pmt__nonce'], 'pmt__settings' ) ) {
                die( 'Sorry, not allowed. Nonce doesn\'t verify' );
            }
            $options = [
                'meta_tags'       => ( isset( $_POST['meta_tags'] ) && !empty($_POST['meta_tags']) ? Option::sanitize_array( $_POST['meta_tags'] ) : "" ),
                'remove_settings' => Request::post( 'remove_settings', $safe ),
            ];
            update_option( 'meta-tags-for-seo', $options );
            // update options
            echo  '<div class="notice pmt-notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings saved.' ) . '</strong></p></div>' ;
            $progress_bar = '<div class="pmt-meter pmt-animate"><span style="width: 100%"><span>All Done</span></span></div>';
        }
        
        $options = new Option();
        $site_title = get_bloginfo( 'name' );
        $notification = new \Pagup\MetaTags\Controllers\NotificationController();
        echo  $notification->support() ;
        //set active class for navigation tabs
        $active_tab = ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], $safe ) ? sanitize_key( $_GET['tab'] ) : 'pmt-settings' );
        //Plugin::dd($_POST);
        //var_dump(Option::all());
        // purchase notification
        $purchase_url = "admin.php?page=meta-tags-for-seo-pricing";
        $get_pro = sprintf( wp_kses( __( '<a href="%s">Get Pro version</a> to enable', "meta-tags-for-seo" ), array(
            'a' => array(
            'href'   => array(),
            'target' => array(),
        ),
        ) ), esc_url( $purchase_url ) );
        // Send options data to app.js
        $options_data = Option::all();
        if ( !empty($options_data) ) {
            // wp_localize_script( 'pmt__script', 'options', $options_data);
            wp_add_inline_script( 'pmt__script', 'const options = ' . json_encode( $options_data ), 'before' );
        }
        $post_types = $this->cpts( array( 'attachment' ) );
        // Return Views
        if ( $active_tab == 'pmt-settings' ) {
            return Plugin::view( 'settings', compact(
                'active_tab',
                'options',
                'get_pro',
                'post_types',
                'site_title',
                'progress_bar'
            ) );
        }
        if ( $active_tab == 'pmt-faq' ) {
            return Plugin::view( "faq", compact( 'active_tab' ) );
        }
    }
    
    public function cpts( $excludes )
    {
        // All CPTs.
        $post_types = get_post_types( array(
            'public' => true,
        ), 'objects' );
        // remove Excluded CPTs from All CPTs.
        foreach ( $excludes as $exclude ) {
            unset( $post_types[$exclude] );
        }
        return $post_types;
    }

}
$settings = new SettingsController();