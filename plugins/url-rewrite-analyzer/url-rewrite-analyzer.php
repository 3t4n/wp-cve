<?php
/**
 * Plugin Name:       Url Rewrite Analyzer
 * Plugin URI:        https://wordpress.org/plugins/url-rewrite-analyzer/
 * Description:       Analyze your rewrites rules with regex system
 * Version:           1.3.2
 * Tags:              library, media, files, download, downloader, WordPress
 * Requires at least: 5.0 or higher
 * Requires PHP:      5.6
 * Tested up to:      6.2.1
 * Stable tag:        1.3.3
 * Author:            Michael Revellin-Clerc
 * License:           GPL v2 or later
 * Domain Path:       /languages
 * Text Domain:       url-rewrite-analyzer
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Contributors:      Michael Revellin-Clerc
 * Donate link:       https://ko-fi.com/devloper
 */

if ( !isset( $rewrite_analyzer_file ) ) {
    $rewrite_analyzer_file = __FILE__;
    if ( isset( $network_plugin ) ) {
        $rewrite_analyzer_file = $network_plugin;
    }
    if ( isset( $plugin ) ) {
        $rewrite_analyzer_file = $plugin;
    }
}

if ( is_admin() ) {
    include_once dirname( __FILE__ ) . '/include/class-urap-url-rewrite-analyzer.php';
    add_action( 'plugins_loaded', 'rewrite_analyzer_load' );
}

function rewrite_analyzer_load() {
    $GLOBALS['Rewrite_Analyzer_instance'] = new Urap_Url_Rewrite_Analyzer( $GLOBALS['rewrite_analyzer_file'] );
}
