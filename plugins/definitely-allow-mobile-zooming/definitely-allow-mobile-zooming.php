<?php
/**
 *
 * @package   Definitely_allow_mobile_zooming
 * @author    Kybernetik Services <wordpress@kybernetik.com.de>
 * @license   GPL-2.0+
 * @link      https://www.kybernetik-services.com
 * @copyright Kybernetik Services
 *
 * @wordpress-plugin
 * Plugin Name:       Definitely allow mobile zooming
 * Plugin URI:        http://wordpress.org/plugins/definitely-allow-mobile-zooming/
 * Description:       This tiny plugin adds the viewport meta tag with zooming permission to give your users the ability to zoom in your website with mobile browsers.
 * Version:           1.6.0
 * Requires at least: 1.2.0
 * Requires PHP:      5.2
 * Author:            Kybernetik Services
 * Author URI:        https://www.kybernetik-services.com/?utm_source=wordpress_org&utm_medium=plugin&utm_campaign=definitely-allow-mobile-zooming&utm_content=author
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

add_action( 'after_setup_theme', 'definitely_allow_mobile_zooming_add_viewport');
function definitely_allow_mobile_zooming_add_viewport()
{
    /*
     * Set viewport for OceanWP
     */
    if ( class_exists('OCEANWP_Theme_Class') ) {

        add_filter( 'ocean_meta_viewport', 'definitely_allow_mobile_zooming_oceanwp' );

    }
    /*
     * Set viewport for Flatsome
     */
    elseif ( class_exists( 'Flatsome_Default' ) ) {

        // Remove Flatsome viewport
        function definitely_allow_mobile_zooming_flatsome_remove_viewport() {
            remove_action('wp_head','flatsome_viewport_meta', 1);
        }
        add_action( 'init', 'definitely_allow_mobile_zooming_flatsome_remove_viewport' );

        // Add our viewport
        add_action( 'wp_head', 'definitely_allow_mobile_zooming_default', 9999999 );
    }
    /*
     * Set viewport by default
     */
    else {

        add_action( 'wp_head', 'definitely_allow_mobile_zooming_default', 9999999 );

    }
}

function definitely_allow_mobile_zooming_default() {
    print "\n";
    print '<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=0.1, maximum-scale=10.0">';
    print "\n";
}

function definitely_allow_mobile_zooming_oceanwp( $viewport ) {
    $viewport   = '<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=0.1, maximum-scale=10.0">';
    return $viewport;
}

