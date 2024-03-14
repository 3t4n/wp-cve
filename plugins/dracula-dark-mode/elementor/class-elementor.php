<?php

defined( 'ABSPATH' ) || exit();

class Dracula_Elementor {

	private static $instance = null;

	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_scripts' ] );

		// Include dark mode settings
		include_once DRACULA_PATH . '/elementor/class-elementor-settings.php';
		add_action( 'elementor/editor/after_save', [ $this, 'save_dracula_settings' ] );

	}

	public function save_dracula_settings( $post_id ) {
		// Retrieve the Elementor settings for the post.
		$elementor_settings = get_post_meta( $post_id, '_elementor_page_settings', true );

		// Get dracula_settings or set as null if it's not set.
		$dracula_settings = $elementor_settings['dracula_settings'] ?? null;

		$option_key = sprintf( 'dracula_settings_%d', $post_id );

		if ( $dracula_settings ) {
			$dracula_settings = json_decode( base64_decode( $dracula_settings ), true );
			update_option( $option_key, $dracula_settings );
		} else {
			delete_option( $option_key );
		}
	}

	public function register_widgets( $widgets_manager ) {
		include_once DRACULA_PATH . '/elementor/class-elementor-widget.php';

		$widgets_manager->register( new Dracula_Elementor_Widget() );
	}

	public function editor_scripts() {
		wp_enqueue_style( 'dracula-elementor-editor', DRACULA_ASSETS . '/css/elementor-editor.css', [ 'wp-components'], DRACULA_VERSION );

		wp_style_add_data( 'dracula-elementor-editor', 'rtl', 'replace' );
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

new Dracula_Elementor();
