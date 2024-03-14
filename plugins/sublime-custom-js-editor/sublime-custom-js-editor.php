<?php 
/**
 * Plugin Name: Sublime Custom JS Editor
 * Plugin URI: http://a1lrsrealtyservices.com/wpdemo/
 * Description: Sublime Text Custom Wordpress JavaScript Editor. You can easily write your custom any javascript by using this plugin.
 * Author: Jillur Rahman, AsianCoders
 * Author URI: http://asiancoders.com
 * Version: 1.0
 * License: GPL2
 * Text Domain: sublimejsedit
 */

defined('ABSPATH') or die("Restricted access!");

/**
 * Text domain
 */
function sublimejsedit_textdomain() {
	load_plugin_textdomain( 'sublimejsedit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'sublimejsedit_textdomain' );

/**
 * Settings link
 */
function sublimejsedit_settings_link( $links ) {
	$settings_page = '<a href="' . admin_url( 'themes.php?page=sublime-custom-js-editor.php' ) .'">' . __( 'Settings', 'sublimejsedit' ) . '</a>';
	array_unshift( $links, $settings_page );
	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'sublimejsedit_settings_link' );


/**
 * Register Appearance" Admin Menu
 */
function sublimejsedit_register_submenu_page() {
	add_theme_page( __( 'Sublime Custom JavaScript Editor', 'sublimejsedit' ), __( 'Sublime Custom js', 'sublimejsedit' ), 'edit_theme_options', basename( __FILE__ ), 'sublimejsedit_render_submenu_page' );
}
add_action( 'admin_menu', 'sublimejsedit_register_submenu_page' );

//  inc/sublimejsedit_function.php'; 
require_once( plugin_dir_path( __FILE__ ) . 'inc/sublimejsedit_function.php' );

function sublimejsedit_register_settings() {
	register_setting( 'sublimejsedit_settings_group', 'sublimejsedit_settings' );
}
add_action( 'admin_init', 'sublimejsedit_register_settings' );

// admin_enqueue_scripts
function sublimejsedit_enqueue_editor_scripts($hook) {

    // Return if the page is not a settings page of this plugin
    if ( 'appearance_page_sublime-custom-js-editor' != $hook ) {
        return;
    }

    // Style sheet
    wp_enqueue_style('codemirror-css', plugin_dir_url(__FILE__) . 'inc/css/codemirror.css');
    wp_enqueue_style('foldgutter-css', plugin_dir_url(__FILE__) . 'inc/css/addon/foldgutter.css');
    wp_enqueue_style('dialog-css', plugin_dir_url(__FILE__) . 'inc/css/addon/dialog.css');
    wp_enqueue_style('show-hint-css', plugin_dir_url(__FILE__) . 'inc/css/addon/show-hint.css');
    wp_enqueue_style('lint-css', plugin_dir_url(__FILE__) . 'inc/css/addon/lint.css');
    wp_enqueue_style('monokai_theme', plugin_dir_url(__FILE__) . 'inc/css/theme/monokai.css');
    wp_enqueue_style('sublimejsedit-css', plugin_dir_url(__FILE__) . 'inc/css/sublimejsedit.css');

    // js
    wp_enqueue_script('codemirror-js', plugin_dir_url(__FILE__) . 'inc/js/codemirror.js');
    wp_enqueue_script('javascript-js', plugin_dir_url(__FILE__) . 'inc/js/javascript.js');

    wp_enqueue_script('searchcursor-js', plugin_dir_url(__FILE__) . 'inc/js/addon/searchcursor.js');
    wp_enqueue_script('search-js', plugin_dir_url(__FILE__) . 'inc/js/addon/search.js');
    wp_enqueue_script('dialog-js', plugin_dir_url(__FILE__) . 'inc/js/addon/dialog.js');
    wp_enqueue_script('matchbrackets-js', plugin_dir_url(__FILE__) . 'inc/js/addon/matchbrackets.js');
    wp_enqueue_script('closebrackets-js', plugin_dir_url(__FILE__) . 'inc/js/addon/closebrackets.js');
    wp_enqueue_script('comment-js', plugin_dir_url(__FILE__) . 'inc/js/addon/comment.js');
    wp_enqueue_script('hardwrap-js', plugin_dir_url(__FILE__) . 'inc/js/addon/hardwrap.js');
    wp_enqueue_script('foldcode-js', plugin_dir_url(__FILE__) . 'inc/js/addon/foldcode.js');
    wp_enqueue_script('brace-fold-js', plugin_dir_url(__FILE__) . 'inc/js/addon/brace-fold.js');
    wp_enqueue_script('active-line-js', plugin_dir_url(__FILE__) . 'inc/js/addon/active-line.js');
    wp_enqueue_script('show-hint-js', plugin_dir_url(__FILE__) . 'inc/js/addon/show-hint.js');
    wp_enqueue_script('javascript-hint-js', plugin_dir_url(__FILE__) . 'inc/js/addon/javascript-hint.js');
    wp_enqueue_script('lint-js', plugin_dir_url(__FILE__) . 'inc/js/addon/lint.js');
    wp_enqueue_script('javascript-lint-js', plugin_dir_url(__FILE__) . 'inc/js/addon/javascript-lint.js');
    wp_enqueue_script('jshintandlint-js', plugin_dir_url(__FILE__) . 'inc/js/addon/jshintandlint.js');
    wp_enqueue_script('sublime-js', plugin_dir_url(__FILE__) . 'inc/js/sublime.js');

}
add_action( 'admin_enqueue_scripts', 'sublimejsedit_enqueue_editor_scripts' );


/**
 * Include javascript in footer
 */
function sublimejsedit_add_javascript_footer() {
    // Read variables from BD
    $options = get_option( 'sublimejsedit_settings' );
    $content = $options['sublimejsedit-content'];
    
    // Cleaning
    $content = trim( $content );
    
    // Styling
    if (!empty($content)) {
        echo '<script type="text/javascript">' . "\n";
        echo $content . "\n";
        echo '</script>' . "\n";
    }
}
add_action( 'wp_footer', 'sublimejsedit_add_javascript_footer' );

/**
 * Delete options on uninstall
 */
function sublimejsedit_uninstall() {
    delete_option( 'sublimejsedit_settings' );
}
register_uninstall_hook( __FILE__, 'sublimejsedit_uninstall' );