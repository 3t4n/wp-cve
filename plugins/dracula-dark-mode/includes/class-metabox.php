<?php

defined( 'ABSPATH' ) || exit();

class Dracula_Metabox {

	private static $instance = null;

	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'dark_mode_meta_box' ] );

		// Save dark mode settings
		add_action( 'save_post', [ $this, 'save_meta_settings' ] );
	}

	public function save_meta_settings( $post_id ) {

		if ( empty( $_POST['dracula_settings'] ) ) {
			if ( get_option( 'dracula_settings_' . $post_id ) ) {
				delete_option( 'dracula_settings_' . $post_id );
			}

			return;
		}

		$settings = json_decode( base64_decode( sanitize_text_field( $_POST['dracula_settings'] ) ), true );

		// Save settings
		update_option( 'dracula_settings_' . $post_id, $settings );

	}

	public function dark_mode_meta_box() {
		$post_types = get_post_types( array( 'public' => true ) );

		foreach ( $post_types as $post_type ) {
			add_meta_box(
				'dark_mode_meta_box',
				__( 'Dark Mode Settings', 'dracula-dark-mode' ),
				[ $this, 'render_settings_meta_box' ],
				$post_type,
				'side'
			);
		}
	}

	public function render_settings_meta_box( $post ) { ?>
        <div id="dracula-settings-metabox" class="dracula-live-edit-wrap"></div>
	<?php }

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

Dracula_Metabox::instance();