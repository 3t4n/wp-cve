<?php

/*
 * Plugin Name: Custom Tweaks for Jetpack by BarryCarlyon
 * Plugin URI: http://barrycarlyon.co.uk/wordpress/wordpress-plugins/jetpack-extras/
 * Description: Extends WordPress.com's JetPack to include Additional Features
 * Author: Barry Carlyon
 * Version: 3.3
 * Author URI: http://barrycarlyon.co.uk/wordpress/
 * License: GPL2+
 * Text Domain: jetpack
 */

$plugin_name = plugin_basename(__FILE__);
$plugin_dir_path = plugin_dir_path( __FILE__ );
$plugin_dir_url = plugin_dir_url( __FILE__ );

define( 'JETPACK_META_BASENAME', $plugin_name );
define( 'JETPACK_EXTRAS_PLUGIN_DIR_URL', $plugin_dir_url );
define( 'JETPACK_EXTRAS_PLUGIN_DIR_PATH', $plugin_dir_path );

add_action( 'init', 'jetpack_extras_init', 20 );

/**
Load extra sharing sources
*/
function jetpack_extras_init() {
    if (class_exists('Sharing_Source')) {
        add_filter('plugin_action_links' , 'jetpack_extras_action_link', 10, 2);

        if ( is_admin() ) {
            // admin
            require_once( JETPACK_EXTRAS_PLUGIN_DIR_PATH . 'modules/sharedaddy/admin.php' );
        } else {
            require_once( JETPACK_EXTRAS_PLUGIN_DIR_PATH . 'modules/sharedaddy/sharing-display.php' );

            // remove core
            remove_filter( 'the_content', 'sharing_display', 19 );
            remove_filter( 'the_excerpt', 'sharing_display', 19 );

            // add extra
            add_filter( 'the_content', 'sharing_display_extra', 19 );
            add_filter( 'the_excerpt', 'sharing_display_extra', 19 );

            // sharing extras
            require_once( JETPACK_EXTRAS_PLUGIN_DIR_PATH . 'modules/sharedaddy/sharing-extras.php' );
        }
    } else {
        add_action('after_plugin_row_' . JETPACK_META_BASENAME, 'jetpack_extras_after_plugin_row', 10, 3);
    }
}

/**
Nag
*/
function jetpack_extras_after_plugin_row($plugin_file, $plugin_data, $plugin_status) {
    if ( $plugin_file != JETPACK_META_BASENAME )
        return;
    echo '<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message">' . __('JetPack Extras Requires, <a href="http://wordpress.org/extend/jetpack/">JetPack</a> to be installed and the Sharing Service Module to be Enabled', 'jetpack') . '</div></td></tr>';
    return;
}
function jetpack_extras_action_link($links, $file){
    if ($file == JETPACK_META_BASENAME)
        array_unshift($links, '<a href="' . admin_url('options-general.php?page=sharing') . '" title="Settings">' . __('Settings', 'jetpack') . '</a>');
    return $links;
}
