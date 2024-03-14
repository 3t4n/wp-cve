<?php
/**
 * Cognito Forms WordPress Plugin.
 *
 * The Cognito Forms WordPress Plugin is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * The Cognito Forms WordPress Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Plugin Name:   Cognito Forms
 * Plugin URI:    http://wordpress.org/plugins/cognito-forms/
 * Description:   Cognito Forms is a free online form builder that integrates seamlessly with WordPress. Create contact forms, registrations forms, surveys, and more!
 * Version:       2.0.4
 * Author:        Cognito Apps
 * Author URI:    https://www.cognitoforms.com
 * License:       GPL v2 or later
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Plugin
 */

if ( !class_exists( 'CognitoFormsPlugin' ) ) {
	require_once dirname( __FILE__ ) . '/api.php';

	class CognitoFormsPlugin {
		// Initialization actions
		private static $actions = array(
			'admin_init',
			'init',
			'wp_ajax_cognito_tinymce_dialog'
		);

		// Supported shortcodes
		private static $shortcodes = array(
			'CognitoForms' => 'render_cognito_shortcode',
			'cognitoforms' => 'render_cognito_shortcode'
		);

		// Registers plug-in actions
		private function add_actions( $actions ) {
			foreach ( $actions as $action )
				add_action( $action, array( $this, $action ) );
		}

		// Registers shortcodes
		private function add_shortcodes( $shortcodes ) {
			foreach ( $shortcodes as $tag => $func )
				add_shortcode( $tag, array( $this, $func ) );
		}

		// Checks if an option exists in the database
		private function option_exists( $option_name, $site_wide = false ) {
			global $wpdb;
			return $wpdb->query( $wpdb->prepare( "SELECT * FROM ". ($site_wide ? $wpdb->base_prefix : $wpdb->prefix). "options WHERE option_name ='%s' LIMIT 1", $option_name ) );
		}

		// Removes a list of options if they exist
		private function remove_options( $options ) {
			foreach ($options as $option) {
				if ( $this->option_exists( $option ) ) {
					delete_option( $option );
				}
			}
		}

		// Entrypoint
		public function __construct() {
			$this->add_actions( self::$actions );
			$this->add_shortcodes( self::$shortcodes );
		}

		public function init() {
			// Initialize Gutenberg Block
			$this->block_init();
			// Add support for oEmbed
			$this->oembed_init();
		}

		// Initialize plug-in
		public function admin_init() {
			if( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) )
				return;

			add_option( 'cognito_public_key' );

			add_option( 'cognito_dev_environment' );

			// Remove old API keys from the database
			$this->remove_options( array(
				'cognito_api_key',
				'cognito_admin_key',
				'cognito_organization'
			) );

			// If the flag to delete options was passed in, delete them
			if ( isset( $_GET['cog_clear'] ) && $_GET['cog_clear'] == '1' ) {
				delete_option( 'cognito_public_key' );
			}

			// Initialize TinyMCE Plugin
			$this->tinymce_init();
		}

		// Initialize block
		public function block_init() {
			$asset_file = include( plugin_dir_path( __FILE__ ) . 'dist/index.asset.php' );

			// Register global block styles
			wp_register_style(
				'cognito-block-global-css', // Handle.
				plugins_url( 'dist/style-main.css', __FILE__ ), // Public CSS
				is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
				$asset_file['version']
			);

			// Register block editor script for backend
			wp_register_script(
				'cognito-block-editor-js',
				plugins_url( 'dist/index.js', __FILE__ ),
				$asset_file['dependencies'],
				$asset_file['version']
			);

			wp_add_inline_script(
				'cognito-block-editor-js',
				'window.COGNITO_BASEURL = "' . CognitoAPI::$formsBase . '";',
				'before'
			);

			// Register block editor styles for backend.
			wp_register_style(
				'cognito-block-editor-css', // Handle.
				plugins_url( 'dist/main.css', __FILE__ ), // Block editor CSS.
				array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
				$asset_file['version']
			);

			register_block_type(
				'cognito-forms/cognito-embed', array(
					// Enqueue global block styles on both frontend and backend
					'style'         => 'cognito-block-global-css',
					// Enqueue block js in the editor only
					'editor_script' => 'cognito-block-editor-js',
					// Enqueue editor block styles in the editor only
					'editor_style'  => 'cognito-block-editor-css'
				)
			);
		}

		// Initialize classic editor (TinyMCE)
		public function tinymce_init() {
			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				add_filter( 'mce_buttons', array( $this, 'tinymce_buttons' ) );
				add_filter( 'mce_external_plugins', array( $this, 'tinymce_external_plugins' ) );
			}
		}

		// Set up TinyMCE buttons
		public function tinymce_buttons( $buttons ) {
			array_push($buttons, '|', 'cognito');
			return $buttons;
		}

		// Set up TinyMCE plug-in
		public function tinymce_external_plugins( $plugin_array ) {
			$plugin_array['cognito_mce_plugin'] = plugins_url( '/tinymce/plugin.js', __FILE__ );
			return $plugin_array;
		}

		public function wp_ajax_cognito_tinymce_dialog() {
			include 'tinymce/dialog.php';
			wp_die();
		}

		// Called when a 'CognitoForms' shortcode is encountered, renders form embed script
		public function render_cognito_shortcode( $atts, $content = null, $code = '' ) {
			// Default to key setting, unless overridden in shortcode (allows for modules from multiple orgs)
			$key = empty( $atts['key'] ) ? get_option( 'cognito_public_key' ) : $atts['key'];
			if ( empty( $atts['id'] ) || empty( $key ) ) return '';

			return CognitoAPI::get_form_embed_script( $key, $atts['id'] );
		}

		// Add support for oEmbed using the generic Gutenberg Embed block
		public function oembed_init() {
			wp_oembed_add_provider( '#https?://(.*\.)?cognitoforms\.com/.*#i', CognitoAPI::$formsBase . '/f/oembed/', true );
		}
	}

	new CognitoFormsPlugin;
}
