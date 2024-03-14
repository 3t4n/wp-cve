<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
namespace Glossary\Backend;

use  Glossary\Engine ;
/**
 * Create the settings page in the backend
 */
class Settings_Page extends Engine\Base
{
    /**
     * Initialize the class.
     *
     * @return bool
     */
    public function initialize()
    {
        if ( !parent::initialize() ) {
            return false;
        }
        // Add the options page and menu item.
        \add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
        // Add an action link pointing to the options page.
        
        if ( \realpath( __DIR__ ) !== false ) {
            $plugin_basename = \plugin_basename( \plugin_dir_path( \realpath( __DIR__ ) ) . GT_SETTINGS . '.php' );
            \add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
        }
        
        \add_action( 'admin_init', array( $this, 'purge_transients' ) );
        return true;
    }
    
    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since 2.0
     * @return void
     */
    public function add_plugin_admin_menu()
    {
        \add_submenu_page(
            'edit.php?post_type=glossary',
            \__( 'Settings', GT_TEXTDOMAIN ),
            \__( 'Settings', GT_TEXTDOMAIN ),
            'manage_options',
            GT_SETTINGS,
            array( $this, 'display_plugin_admin_page' )
        );
    }
    
    /**
     * Render the settings page for this plugin.
     *
     * @since 2.0
     * @return void
     */
    public function display_plugin_admin_page()
    {
        include_once GT_PLUGIN_ROOT . 'backend/views/admin.php';
    }
    
    /**
     * Add settings action link to the plugins page.
     *
     * @since 2.0
     * @param array $links Array of links.
     * @return array
     */
    public function add_action_links( array $links )
    {
        return \array_merge( array(
            'settings' => '<a href="' . \admin_url( 'edit.php?post_type=glossary' ) . '">' . \__( 'Settings', GT_TEXTDOMAIN ) . '</a>',
        ), $links );
    }
    
    /**
     * Force a transient purge only for Glossary
     *
     * @return void
     */
    public function purge_transients()
    {
        if ( empty($_GET['gl_purge_transient']) ) {
            //phpcs:ignore WordPress.Security.NonceVerification
            return;
        }
        if ( !\current_user_can( 'manage_options' ) ) {
            return;
        }
        global  $wpdb ;
        // Add our prefix after concating our prefix with the _transient prefix
        $prefix = $wpdb->esc_like( '_transient_glossary_' );
        // Build up our SQL query
        $sql = "SELECT `option_name` FROM {$wpdb->options} WHERE `option_name` LIKE '%s'";
        // Execute our query
        $transients = $wpdb->get_results( $wpdb->prepare( $sql, $prefix . '%' ), ARRAY_A );
        //phpcs:ignore WordPress.DB
        // If if looks good, pass it back
        if ( $transients && !\is_wp_error( $transients ) ) {
            foreach ( $transients as $transient ) {
                \delete_option( $transient['option_name'] );
                \delete_transient( $transient['option_name'] );
            }
        }
        \wpdesk_wp_notice( \__( 'Transient purged successfully', GT_TEXTDOMAIN ), 'updated', true );
    }

}