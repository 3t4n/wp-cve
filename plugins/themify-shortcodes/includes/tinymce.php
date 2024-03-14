<?php
/**
 * Shortcode Generator in TinyMCE
 */
class Themify_Shortcodes_TinyMCE {

	public function __construct() {
		if ( current_user_can( 'publish_posts' ) && get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( __CLASS__, 'add_plugin' ) );
			add_filter( 'mce_buttons', array( __CLASS__, 'add_button' ) );
			add_action( 'wp_enqueue_editor', array( __CLASS__, 'localize' ) );
			add_action( 'print_media_templates', array( __CLASS__, 'print_media_templates' ) );
		}
	}

	/**
	 * Add button to WP Editor.
	 *
	 * @param array $mce_buttons
	 * @return mixed
	 */
	public static function add_button( $mce_buttons ) {
		array_push( $mce_buttons, 'separator', 'btnthemifyMenu' );
		return $mce_buttons;
	}

	/**
	 * Add plugin JS file to list of external plugins.
	 *
	 * @param array $mce_external_plugins
	 * @return mixed
	 */
	public static function add_plugin( $mce_external_plugins ) {
		global $wp_version;
		$mce_external_plugins['themifyMenu'] = THEMIFY_SHORTCODES_URI . 'assets/tinymce.js';

		return $mce_external_plugins;
	}

	/**
	 * Get list of Themify shortcodes and their config
	 *
	 * @since 2.7.6
	 */
	public static function get_shortcodes() {
		$shortcodes = include( dirname( __FILE__ ) . '/shortcodes.php' );

		return $shortcodes;
	}

	/**
	 * Pass strings to JS to set the labels of the WP Editor shortcode button and menu.
	 *
	 * @since 1.8.9
	 */
	public static function localize() {
		wp_localize_script( 'editor', 'themifyEditor', array(
			'nonce' => wp_create_nonce( 'themify-editor-nonce' ),
			'shortcodes' => self::get_shortcodes(),
			'editor' => array(
				'menuTooltip' => __('Shortcodes', 'themify'),
				'menuName' => __('Shortcodes', 'themify'),
				'icon' => THEMIFY_SHORTCODES_URI . 'assets/icon.png',
			)
		));
	}

	/**
	 * Print template files that will generate the shortcode code
	 *
	 * @since 2.7.6
	 */
	public static function print_media_templates( $shortcodes = null ) {
		if ( $shortcodes == null ) {
			$shortcodes = self::get_shortcodes();
		}
		foreach ( $shortcodes as $key => $shortcode ) {
			if ( isset( $shortcode['menu'] ) ) {
				self::print_media_templates( $shortcode['menu'] );
			} else {
				echo '<script type="text/html" id="tmpl-themify-shortcode-' . $key . '">';
				if ( isset( $shortcode['template'] ) ) {
					echo $shortcode['template'];
				}
				echo '</script>';
			}
		}
	}
}