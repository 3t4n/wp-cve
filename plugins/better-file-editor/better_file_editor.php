<?php
/**
 * Plugin Name: Better File Editor
 * Plugin URI:  https://wordpress.org/plugins/better-file-editor/
 * Description: Adds line numbers, syntax highlighting, code folding, and lots more to the theme and plugin editors in the admin panel.
 * Version:     2.3.1
 * Author:      Bryan Petty <bryan@ibaku.net>
 * Author URI:  https://profiles.wordpress.org/bpetty/
 * License:     GPLv2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: bfe
 * Domain Path: /languages
 */

/**
 * Main Better File Editor class.
 *
 * Handles plugin initialization and primary actions and filters.
 */
class BetterFileEditorPlugin {

	function BetterFileEditorPlugin() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_print_scripts-theme-editor.php', array( $this, 'admin_print_scripts' ) );
		add_action( 'admin_print_scripts-plugin-editor.php', array( $this, 'admin_print_scripts' ) );
	}

	function init() {
		load_plugin_textdomain( 'bfe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		wp_register_style( 'better-file-editor.min',
			plugins_url( 'assets/css/better-file-editor.min.css', __FILE__ ));

		wp_register_script( 'bfe-ace',
			plugins_url( 'assets/js/ace/ace.js', __FILE__ ),
			array(), '1.2.0' );
		wp_register_script( 'bfe-ace-ext-modelist',
			plugins_url( 'assets/js/ace/ext-modelist.js', __FILE__ ),
			array( 'bfe-ace' ), '1.2.0' );
		wp_register_script( 'better-file-editor',
			plugins_url( 'assets/js/better-file-editor.js', __FILE__ ),
			array( 'bfe-ace', 'bfe-ace-ext-modelist' ), '2.3.1' );

		wp_localize_script( 'better-file-editor', 'bfe', array(
			'theme_label'        => __( 'Theme:', 'bfe' ),
			'theme_bright_label' => __( 'Bright', 'bfe' ),
			'theme_dark_label'   => __( 'Dark', 'bfe' ),
			'font_size_label'    => __( 'Font Size:', 'bfe' ),
			'show_ruler_label'   => __( 'Show Ruler', 'bfe' ),
			'show_gutter_label'  => __( 'Show Gutter', 'bfe' ),
			'whitespace_label'   => __( 'Visible Whitespace', 'bfe' )
		));
	}

	function admin_print_scripts( $page ) {
		wp_enqueue_style( 'better-file-editor.min' );
		wp_enqueue_script( 'better-file-editor' );
	}

}

$bfe_plugin = new BetterFileEditorPlugin();
