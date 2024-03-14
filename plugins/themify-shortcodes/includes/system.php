<?php

class Themify_Shortcodes {

	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
		return null == self::$instance ? self::$instance = new self : self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'load_dependencies' ), 1 );
		add_action( 'init', array( $this, 'admin' ), 9 );
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 11 );
	}

	private static function i18n() {
		load_plugin_textdomain( 'themify-shortcodes', false, plugin_basename( THEMIFY_SHORTCODES_DIR ) . '/languages' );
	}

	/**
	 * Returns true if the active theme is using Themify framework
	 *
	 * @return bool
	 */
	public function is_using_themify_theme() {
		return is_file( get_template_directory() . '/themify/themify-utils.php' );
	}

	/**
	 * Check if shortcodes are deprecated in Themify framework (3.1.3+)
	 *
	 * @return bool
	 */
	function enable_deprecated_shortcodes() {
		$flags = get_option( 'themify_flags', array() );
		return !isset( $flags['deprecate_shortcodes'] );
	}

	public function load_dependencies() {
		if ( ! defined( 'THEMIFY_DIR' ) ) {
			include( THEMIFY_SHORTCODES_DIR . 'includes/theme-options.php' );
		}
		
		include( THEMIFY_SHORTCODES_DIR . 'includes/functions.php' );

		if ( ! function_exists( 'themify_shortcode' ) ) {
			include( THEMIFY_SHORTCODES_DIR . 'includes/themify-shortcodes.php' );
		}

		include THEMIFY_SHORTCODES_DIR . 'includes/tinymce.php';
		new Themify_Shortcodes_TinyMCE;
	}

	/**
	 * Load plugin assets for frontend
	 */
	public function enqueue() {

		wp_deregister_style( 'themify-framework' );

		wp_enqueue_style( 'themify-shortcodes', THEMIFY_SHORTCODES_URI . 'assets/styles.css' );
		wp_register_style( 'themify-icons', THEMIFY_SHORTCODES_URI . 'assets/themify-icons/themify-icons.css' );
		wp_register_style( 'themify-font-icons-css', THEMIFY_SHORTCODES_URI . 'assets/fontawesome/css/font-awesome.min.css' );

		$options = get_option( 'themify_shortcodes', array() );
		wp_register_script( 'themify-shortcodes', THEMIFY_SHORTCODES_URI . 'assets/scripts.js', array( 'jquery' ), THEMIFY_SHORTCODES_VERSION, true );

		$map = '';
		if ( method_exists( 'Themify_Builder_Model', 'getMapKey' ) ) {
			$map = Themify_Builder_Model::getMapKey();
                }
                elseif(isset($options['gmap_api_key'])) {
			$map = $options['gmap_api_key'];
		}

		wp_localize_script( 'themify-shortcodes', 'themifyShortcodes', apply_filters( 'themify_shortcodes_script_vars', array(
			'url' => THEMIFY_SHORTCODES_URI,
			'includesURL' => trailingslashit( includes_url() ),
			'map_key' => $map
		) ) );
	}

	/**
	 * Admin actions
	 * Setups the plugin's options page
	 *
	 * @since 1.0.0
	 */
	public function admin() {
		if ( is_admin() ) {
			include( THEMIFY_SHORTCODES_DIR . 'includes/admin.php' );
			new Builder_Shortcodes_Admin();
		}
	}

	/**
	 * Register shortcodes in the plugin
	 *
	 * @since 1.0.3
	 */
	public function register() {
		self::i18n();
		$shortcodes = array(
			'is_logged_in' => 'themify_shortcode',
            'is_guest'     => 'themify_shortcode',
            'button'       => 'themify_shortcode',
            'quote'        => 'themify_shortcode',
            'col'          => 'themify_shortcode',
            'sub_col'      => 'themify_shortcode',
            'img'          => 'themify_shortcode',
            'hr'           => 'themify_shortcode',
            'map'          => 'themify_shortcode',
            'list_posts'   => 'themify_shortcode_list_posts',
            'twitter'      => 'themify_shortcode_twitter',
            'box'          => 'themify_shortcode_box',
            'post_slider'  => 'themify_shortcode_post_slider',
            'slider'       => 'themify_shortcode_slider',
            'slide'        => 'themify_shortcode_slide',
            'author_box'   => 'themify_shortcode_author_box',
            'icon'         => 'themify_shortcode_icon',
            'list'         => 'themify_shortcode_icon_list',
		);

		$options = get_option( 'themify_shortcodes', array() );
		$disable_legacy_shortcodes = isset( $options['disable_legacy'] ) && 'yes' === $options['disable_legacy'];

		foreach ( $shortcodes as $code => $callback ) {
			add_shortcode( "themify_{$code}", $callback );
			if ( ! $disable_legacy_shortcodes ) {
				add_shortcode( $code, $callback );
			}
		}

		if ( ! $disable_legacy_shortcodes ) {
			add_shortcode( 'themify_video', 'wp_video_shortcode' );
		}
	}
}
