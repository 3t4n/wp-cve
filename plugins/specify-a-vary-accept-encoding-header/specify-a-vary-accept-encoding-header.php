<?php
/**
 * Plugin Name: Specify a Vary: Accept-Encoding Header
 * Description: Fixes a "Vary: Accept-Encoding Header" message and boosts website performance.
 * Version: 1.0.0
 * Author: LithiumSixteen
 * Author URI: https://lithiumsixteen.com
 * License: GPL v2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Register activation hook to write the Vary: Accept-Encoding Header rule to .htaccess when plugin is activated
register_activation_hook( __FILE__, 'vaeh_vary_accept_encoding_header' );


// Register deactivation hook to comment out the added Vary: Accept-Encoding Header rule in .htaccess
register_deactivation_hook( __FILE__, 'vaeh_comment_out_vary_accept_encoding_header' );

/**
 * Adds the Vary: Accept-Encoding Header rule to .htaccess
 */
function vaeh_vary_accept_encoding_header(){
    
    // Get the path to the htaccess file
    $htaccess = get_home_path().".htaccess";

    // Add new rule to array
    $lines = array();
    $lines[0] = "<IfModule mod_headers.c>";
    $lines[1] = '   <FilesMatch ".(js|css|xml|gz|html)$">';
    $lines[2] = "      Header append Vary: Accept-Encoding";
    $lines[3] = "   </FilesMatch>";
    $lines[4] = "</IfModule>";
    
    // Write array to htaccess. This will replace rules with same markers
    insert_with_markers($htaccess, "Vary Accept Encoding Header", $lines);
    flush_rewrite_rules();
}



/**
 * Comments out the the Vary: Accept-Encoding Header rule in .htaccess
 */
function vaeh_comment_out_vary_accept_encoding_header(){
    
    // Get the path to the htaccess file
    $htaccess = get_home_path().".htaccess";

    // Add new rule to array
    $lines = array();
    $lines[0] = "# <IfModule mod_headers.c>";
    $lines[1] = '#    <FilesMatch ".(js|css|xml|gz|html)$">';
    $lines[2] = "#       Header append Vary: Accept-Encoding";
    $lines[3] = "#    </FilesMatch>";
    $lines[4] = "# </IfModule>";
    
    // Write array to htaccess. This will replace rules with same markers
    insert_with_markers($htaccess, "Vary Accept Encoding Header", $lines);
    flush_rewrite_rules();
}