<?php

namespace Pagup\BetterRobots\Controllers;

use  Pagup\BetterRobots\Core\Option ;
use  Pagup\BetterRobots\Traits\Sitemap ;
use  Pagup\BetterRobots\Traits\RobotsHelper ;
use  Pagup\BetterRobots\Traits\SettingHelper ;
class SettingsController
{
    use  RobotsHelper, SettingHelper, Sitemap ;
    protected  $get_pro = '' ;
    protected  $yoast_sitemap_url = '' ;
    protected  $xml_sitemap_url = '' ;
    public function __construct()
    {
        $this->get_pro = sprintf( wp_kses( __( '<a href="%s">Get Pro version</a> to enable', "better-robots-txt" ), array(
            'a' => array(
            'href'   => array(),
            'target' => array(),
        ),
        ) ), esc_url( "admin.php?page=better-robots-txt-pricing" ) );
        $this->yoast_sitemap_url = home_url() . '/sitemap_index.xml';
        $this->xml_sitemap_url = home_url() . '/sitemap.xml';
    }
    
    public function add_settings()
    {
        add_menu_page(
            __( 'Better Robots.txt Settings', 'better-robots-txt' ),
            __( 'Better Robots.txt', 'better-robots-txt' ),
            'manage_options',
            'better-robots-txt',
            array( &$this, 'page' ),
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2aWV3Qm94PSIwIDAgNjQwIDUxMiI+ICAgIDxwYXRoIGQ9Ik0zMiAyMjRoMzJ2MTkySDMyYTMxLjk2MiAzMS45NjIgMCAwIDEtMzItMzJWMjU2YTMxLjk2MiAzMS45NjIgMCAwIDEgMzItMzJ6bTUxMi00OHYyNzJhNjQuMDYzIDY0LjA2MyAwIDAgMS02NCA2NEgxNjBhNjQuMDYzIDY0LjA2MyAwIDAgMS02NC02NFYxNzZhNzkuOTc0IDc5Ljk3NCAwIDAgMSA4MC04MGgxMTJWMzJhMzIgMzIgMCAwIDEgNjQgMHY2NGgxMTJhNzkuOTc0IDc5Ljk3NCAwIDAgMSA4MCA4MHptLTI4MCA4MGE0MCA0MCAwIDEgMC00MCA0MGEzOS45OTcgMzkuOTk3IDAgMCAwIDQwLTQwem0tOCAxMjhoLTY0djMyaDY0em05NiAwaC02NHYzMmg2NHptMTA0LTEyOGE0MCA0MCAwIDEgMC00MCA0MGEzOS45OTcgMzkuOTk3IDAgMCAwIDQwLTQwem0tOCAxMjhoLTY0djMyaDY0em0xOTItMTI4djEyOGEzMS45NjIgMzEuOTYyIDAgMCAxLTMyIDMyaC0zMlYyMjRoMzJhMzEuOTYyIDMxLjk2MiAwIDAgMSAzMiAzMnoiIGZpbGw9ImN1cnJlbnRDb2xvciI+PC9wYXRoPjwvc3ZnPg=='
        );
    }
    
    public function page()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you are not allowed to access this page.', "better-robots-txt" ) );
        }
        // only users with `unfiltered_html` can edit scripts.
        if ( !current_user_can( 'unfiltered_html' ) ) {
            wp_die( __( 'Sorry, you are not allowed to edit this page. Ask your administrator for assistance.', "better-robots-txt" ) );
        }
        // Get Options
        $get_options = new Option();
        $options = $get_options::all();
        // Unserialize 'backlinks_bots' array if it's set.
        if ( isset( $options['backlinks_bots'] ) && !empty($options['backlinks_bots']) ) {
            $options['backlinks_bots'] = maybe_unserialize( $options['backlinks_bots'] );
        }
        wp_localize_script( 'robots__main', 'data', array(
            'assets'          => plugins_url( 'assets', dirname( __FILE__ ) ),
            'options'         => $options,
            'onboarding'      => get_option( 'robots_tour' ),
            'pro'             => rtf_fs()->can_use_premium_code__premium_only(),
            'plugins'         => $this->installable_plugins(),
            'language'        => get_locale(),
            'nonce'           => wp_create_nonce( 'rt__nonce' ),
            'purchase_url'    => rtf_fs()->get_upgrade_url(),
            'recommendations' => $this->recommendations_list(),
            'robots_url'      => $this->robotsTxtURL(),
        ) );
        if ( ROBOTS_PLUGIN_MODE !== "prod" ) {
            echo  $this->devNotification() ;
        }
        echo  '<div id="rt__app"></div>' ;
    }
    
    public function save_options()
    {
        // check the nonce
        
        if ( check_ajax_referer( 'rt__nonce', 'nonce', false ) == false ) {
            wp_send_json_error( "Invalid nonce", 401 );
            wp_die();
        }
        
        
        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( "Unauthorized user", 403 );
            wp_die();
        }
        
        $safe = [
            "allow",
            "disallow",
            "yes",
            "no",
            "remove_settings",
            "wordpress",
            "yoast",
            "aioseo",
            "custom"
        ];
        $options = $this->sanitize_options( $_POST['options'], $safe );
        $result = update_option( 'robots_txt', $options );
        
        if ( $result ) {
            wp_send_json_success( [
                'options' => $options,
                'message' => "Saved Successfully",
            ] );
        } else {
            wp_send_json_error( [
                'options' => $options,
                'message' => "Error Saving Options",
            ] );
        }
        
        wp_die();
    }
    
    public function onboarding()
    {
        // check the nonce
        
        if ( check_ajax_referer( 'rt__nonce', 'nonce', false ) == false ) {
            wp_send_json_error( "Invalid nonce", 401 );
            wp_die();
        }
        
        
        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( "Unauthorized user", 403 );
            wp_die();
        }
        
        $closed = ( isset( $_POST['closed'] ) ? $_POST['closed'] === 'true' || $_POST['closed'] === true : false );
        $result = update_option( 'robots_tour', $closed );
        
        if ( $result ) {
            wp_send_json_success( [
                'robots_tour' => get_option( 'robots_tour' ),
                'message'     => "Tour closed value saved successfully",
            ] );
        } else {
            wp_send_json_error( [
                'robots_tour' => get_option( 'robots_tour' ),
                'message'     => "Error Saving Tour closed value",
            ] );
        }
    
    }
    
    /**
     * Get the fields, including both free and premium fields if applicable.
     *
     * @param array $safe The array of safe values used for validation.
     * @return array The merged array of free and premium fields, if premium is available.
     */
    public function getFields( array $safe ) : array
    {
        $fields = [
            'feed_protector'  => $safe,
            'user_agents'     => 'textarea',
            'crawl_delay'     => 'text',
            'personalize'     => 'textarea',
            'boost-alt'       => $safe,
            'ads-txt'         => $safe,
            'app-ads-txt'     => $safe,
            'remove_settings' => $safe,
        ];
        $premium_fields = [];
        return array_merge( $fields, $premium_fields );
    }

}
$settings = new SettingsController();