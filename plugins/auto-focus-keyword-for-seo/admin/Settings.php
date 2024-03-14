<?php

namespace Pagup\AutoFocusKeyword;

use  Pagup\AutoFocusKeyword\Core\Asset ;
class Settings
{
    public function __construct()
    {
        $settings = new \Pagup\AutoFocusKeyword\Controllers\SettingsController();
        $keyword = new \Pagup\AutoFocusKeyword\Controllers\KeywordController();
        $metabox = new \Pagup\AutoFocusKeyword\Controllers\MetaboxController();
        // Bulk fetch
        add_action( 'wp_ajax_bulk_fetch', array( &$keyword, 'bulk_fetch' ) );
        // Bulk add
        add_action( 'wp_ajax_bulk_add', array( &$keyword, 'bulk_add' ) );
        // Delete item
        add_action( 'wp_ajax_delete_item', array( &$keyword, 'delete_item' ) );
        // Sync date
        add_action( 'wp_ajax_sync_date', array( &$keyword, 'sync_date' ) );
        // Add settings page
        add_action( 'admin_menu', array( &$settings, 'add_settings' ) );
        // Add setting link to plugin page
        $plugin_base = AFKW_PLUGIN_BASE;
        add_filter( "plugin_action_links_{$plugin_base}", array( &$this, 'setting_link' ) );
        // Add styles and scripts
        add_action( 'admin_enqueue_scripts', array( &$this, 'assets' ) );
        add_filter(
            'script_loader_tag',
            array( &$this, 'add_module_to_script' ),
            10,
            3
        );
    }
    
    public function setting_link( $links )
    {
        array_unshift( $links, '<a href="/wp-admin/admin.php?page=' . AFKW_NAME . '">Settings</a>' );
        return $links;
    }
    
    public function assets()
    {
        
        if ( isset( $_GET['page'] ) && !empty($_GET['page']) && $_GET['page'] === "auto-focus-keyword-for-seo" ) {
            Asset::style_remote( 'afkw__font', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap' );
            Asset::style( 'afkw__flexboxgrid', 'admin/assets/flexboxgrid.min.css' );
            Asset::style( 'afkw__styles', 'admin/assets/app.css' );
            Asset::style( 'afkw__multiselect', 'vendor/js/multiselect-2.6.2.css' );
            Asset::script(
                'ails__qs',
                'vendor/js/qs.min.js',
                array(),
                true
            );
            Asset::script(
                'afkw__axios',
                'vendor/js/axios.min.js',
                array(),
                true
            );
            Asset::script(
                'afkw__vuejs',
                'vendor/js/vue.min.js',
                array(),
                true
            );
            Asset::script(
                'afkw__multiselect',
                'vendor/js/multiselect-2.6.2.js',
                array(),
                true
            );
            Asset::script(
                'afkw__script',
                'admin/assets/app.js',
                array( 'moment' ),
                true
            );
        }
        
        Asset::style( 'afkw__styles', 'admin/assets/metabox.css' );
        Asset::script(
            'afkw__metabox-script',
            'admin/assets/metabox.js',
            array( 'jquery' ),
            true
        );
    }
    
    public function add_module_to_script( $tag, $handle )
    {
        
        if ( 'afkw__script' === $handle ) {
            $tag = str_replace( 'type="text/javascript"', "", $tag );
            $tag = str_replace( "type='text/javascript'", "", $tag );
            $tag = str_replace( ' src', ' type="module" src', $tag );
            return $tag;
        }
        
        return $tag;
    }

}
$settings = new Settings();