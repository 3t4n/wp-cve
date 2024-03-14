<?php

/**
 * Admin Settings & Setup file.
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Admin class.
 */
class Blockons_Admin
{
    /**
     * Constructor function.
     */
    public function __construct()
    {
        add_action(
            'admin_menu',
            array( $this, 'blockons_create_admin_menu' ),
            10,
            1
        );
        add_filter( 'plugin_action_links_blockons/blockons.php', array( $this, 'blockons_add_plugins_settings_link' ) );
        add_filter(
            'plugin_row_meta',
            array( $this, 'blockons_add_plugins_row_link' ),
            10,
            2
        );
        add_filter(
            'block_categories_all',
            array( $this, 'blockons_blocks_custom_category' ),
            10,
            2
        );
        add_filter( 'admin_body_class', array( $this, 'blockons_admin_body_classes' ) );
    }
    
    /**
     * Create an Admin Sub-Menu under WooCommerce
     */
    public function blockons_create_admin_menu()
    {
        $capability = 'manage_options';
        $slug = 'blockons-settings';
        add_submenu_page(
            'options-general.php',
            __( 'Blockons Settings', 'blockons' ),
            __( 'Blockons Settings', 'blockons' ),
            $capability,
            $slug,
            array( $this, 'blockons_menu_page_template' )
        );
    }
    
    /**
     * Create a Setting link on Plugins.php page
     */
    public function blockons_add_plugins_settings_link( $links )
    {
        $settings_link = '<a href="options-general.php?page=blockons-settings">' . esc_html__( 'Settings', 'blockons' ) . '</a>';
        array_push( $links, $settings_link );
        return $links;
    }
    
    /**
     * Create a Setting link on Plugins.php page
     */
    public function blockons_add_plugins_row_link( $plugin_meta, $plugin_file )
    {
        
        if ( strpos( $plugin_file, 'kaira-site-chat.php' ) !== false ) {
            $new_links = array(
                'Documentation' => '<a href="' . esc_url( 'https://blockons.com/documentation/' ) . '" target="_blank" aria-label="' . esc_attr__( 'View Blockons documentation', 'blockons' ) . '">' . esc_html__( 'Documentation', 'blockons' ) . '</a>',
                'FAQs'          => '<a href="' . esc_url( 'https://blockons.com/support/faqs/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Go to Blockons FAQ\'s', 'blockons' ) . '">' . esc_html__( 'FAQ\'s', 'blockons' ) . '</a>',
            );
            $plugin_meta = array_merge( $plugin_meta, $new_links );
        }
        
        return $plugin_meta;
    }
    
    /**
     * Create the Page Template html for React
     * Settings created in ../src/backend/settings/admin.js
     */
    public function blockons_menu_page_template()
    {
        $allowed_html = array(
            'div' => array(
            'class' => array(),
            'id'    => array(),
        ),
            'h2'  => array(),
        );
        $html = '<div class="wrap">' . "\n";
        $html .= '<h2> </h2>' . "\n";
        $html .= '<div id="blockons-root"></div>' . "\n";
        $html .= '</div>' . "\n";
        echo  wp_kses( $html, $allowed_html ) ;
    }
    
    /**
     * Create Blockons blocks Category
     */
    public function blockons_blocks_custom_category( $categories, $post )
    {
        return array_merge( $categories, array( array(
            "slug"  => "blockons-category",
            "title" => __( "Blockons Blocks", "blockons" ),
        ) ) );
    }
    
    /**
     * Function to check for active plugins
     */
    public static function blockons_is_plugin_active( $plugin_name )
    {
        // Get Active Plugin Setting
        $active_plugins = (array) get_option( 'active_plugins', array() );
        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, array_keys( get_site_option( 'active_sitewide_plugins', array() ) ) );
        }
        $plugin_filenames = array();
        foreach ( $active_plugins as $plugin ) {
            
            if ( false !== strpos( $plugin, '/' ) ) {
                // normal plugin name (plugin-dir/plugin-filename.php)
                list( , $filename ) = explode( '/', $plugin );
            } else {
                // no directory, just plugin file
                $filename = $plugin;
            }
            
            $plugin_filenames[] = $filename;
        }
        return in_array( $plugin_name, $plugin_filenames );
    }
    
    /**
     * Function to check for active plugins
     */
    public function blockons_admin_body_classes( $admin_classes )
    {
        $admin_classes .= ' ' . sanitize_html_class( 'blockons-free' );
        return $admin_classes;
    }

}
new Blockons_Admin();