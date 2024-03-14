<?php
/**
 * Plugin Name: 	      CoolAuthorBox
 * Plugin URI:		      https://wordpress.org/plugins/hm-cool-author-box-widget/
 * Description: 	      This plugin displays an author bio box in your widget area or in a single post/page with social media links.
 * Version: 		        2.9.6
 * Author: 			        HM Plugin
 * Author URI: 		      https://hmplugin.com
 * Requires at least:   5.2
 * Requires PHP:        7.2
 * Tested up to:        6.4.3
 * Text Domain:         hm-cool-author-box-widget
 * Domain Path:         /languages/
 * License:             GPLv2 or later
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( function_exists( 'hcabw_fs ' ) ) {

  hcabw_fs ()->set_basename( false, __FILE__ );

} else {

  if ( ! class_exists('HMCABW_Master') ) {

    define( 'HMCABW_PATH', plugin_dir_path( __FILE__ ) );
    define( 'HMCABW_ASSETS', plugins_url( '/assets/', __FILE__ ) );
    define( 'HMCABW_LANG', plugins_url( '/languages/', __FILE__ ) );
    define( 'HMCABW_SLUG', plugin_basename( __FILE__ ) );
    define( 'HMCABW_PREFIX', 'hmcabw_' );
    define( 'HMCABW_CLASSPREFIX', 'cls-hmcab-' );
    define( 'HMCABW_TXT_DOMAIN', 'hm-cool-author-box-widget' );
    define( 'HMCABW_VERSION', '2.9.6' );

    require_once HMCABW_PATH . '/lib/freemius-integrator.php';
    require_once HMCABW_PATH . 'inc/' . HMCABW_CLASSPREFIX . 'master.php';
    $hmcabw = new HMCABW_Master();
    $hmcabw->hmcabw_run();

    // Donate link to plugin description
    function hmcab_display_donation_link_to_plugin_meta( $links, $file ) {

        if ( HMCABW_SLUG === $file ) {
            $row_meta = array(
              'hmcab_donation'  => '<a href="' . esc_url( 'https://www.paypal.me/mhmrajib/' ) . '" target="_blank" aria-label="' . __( 'Donate us', HMCABW_TXT_DOMAIN ) . '" style="color:green; font-weight: bold;">' . __( 'Donate us', HMCABW_TXT_DOMAIN ) . '</a>'
            );
    
            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }
    add_filter( 'plugin_row_meta', 'hmcab_display_donation_link_to_plugin_meta', 10, 2 );


    function cab_fs_uninstall_cleanup() {

      global $wpdb;
      $option_name    = 'wporg_option';
      $tbl            = $wpdb->prefix . 'options';
      $search_string  = HMCABW_PREFIX . '%';

      $sql            = $wpdb->prepare("SELECT option_name FROM $tbl WHERE option_name LIKE %s", $search_string);
      $options        = $wpdb->get_results( $sql, OBJECT );

      if ( is_array( $options ) && count( $options ) ) {
          foreach ( $options as $option ) {
              delete_option( $option->option_name );
              delete_site_option( $option->option_name );
          }
      }
    }
    hcabw_fs()->add_action('after_uninstall', 'cab_fs_uninstall_cleanup');

  }
}