<?php
/**
 * Plugin Name: Responsive video embed
 * Description: Embed videos to your content responsively.
 * Version: 0.5
 * Author: Luuptek
 * Author URI: https://www.luuptek.fi
 * License: GPLv2
 */

/**
 * Security Note:
 * Consider blocking direct access to your plugin PHP files by adding the following line at the top of each of them,
 * or be sure to refrain from executing sensitive standalone PHP code before calling any WordPress functions.
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Class to build the whole plugin
 */
class Rve {

	protected static $instance = null;

	private $rve_text_domain = 'rve';

	function __construct() {
		add_action( 'init', [ $this, 'initialize_hooks' ] );
		add_action( 'plugins_loaded', [ $this, 'load_rve_text_domain' ] );
	}

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}
	}

	/**
	 * Create hooks here
	 */
	public function initialize_hooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'get_styles' ] );
		add_shortcode( 'rve', [ $this, 'embed_shortcode' ] );
		add_action( 'admin_head', [ $this, 'register_tinymce_buttons' ] );
		add_filter( 'embed_oembed_html', [ $this, 'register_embed_html' ], 99, 4 );
		add_filter('mce_external_languages', [$this, 'rve_tinymce_locales']);
	}

	/**
	 * Register translations file for tinyMCE
	 */
	public function rve_tinymce_locales() {
		$locales ['Rve-Tinymce-Plugin'] = plugin_dir_path ( __FILE__ ) . 'rve-tinymce-plugin-langs.php';
		return $locales;
	}

	/**
	 * Load text domain for lang versioning
	 */
	public function load_rve_text_domain() {
		load_plugin_textdomain( $this->rve_text_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Style register_setting
	 */
	public function get_styles() {
		wp_enqueue_style( 'wrve-css', plugins_url( 'css/rve.min.css', __FILE__ ) );
	}

	/**
	 * Create the actual shortcode
	 */
	public function embed_shortcode( $atts, $content = null ) {
		$src    = isset( $atts['src'] ) ? $atts['src'] : '';
		$ratio  = isset( $atts['ratio'] ) && ( $atts['ratio'] == '16by9' || $atts['ratio'] == '4by3' || $atts['ratio'] == '21by9' || $atts['ratio'] == '1by1' ) ? $atts['ratio'] : '16by9';
		$markUp = '';

		$markUp = <<<EOT
		<div class="rve-embed-responsive rve-embed-responsive-${ratio}">
			<iframe class="rve-embed-responsive-item" src="${src}" allowfullscreen></iframe>
		</div>
EOT;

		return $markUp;
	}

	/**
	 * Register TinyMCE buttons
	 */
	public function register_tinymce_buttons() {
		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', [ $this, 'set_rve_button_js' ] );
			add_filter( 'mce_buttons', [ $this, 'register_buttons' ] );
		}
	}

	public function set_rve_button_js() {
		$plugin_array['rve_button'] = plugins_url( 'js/rve-button.min.js', __FILE__ );

		return $plugin_array;
	}

	public function register_buttons( $buttons ) {
		array_push( $buttons, "rve_button" );

		return $buttons;
	}

	/**
	 * Function to wrap video into embed-container
	 */
	function register_embed_html( $html, $url, $attr, $post_id ) {
		return '<div class="rve-embed-responsive rve-embed-responsive-16by9">' . $html . '</div>';
	}

}

Rve::get_instance();
