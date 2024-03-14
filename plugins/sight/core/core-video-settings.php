<?php
/**
 * The Video Settings.
 *
 * @link    https://codesupply.co
 * @since   1.0.0
 *
 * @package Sight
 */

/**
 * The initialize block.
 */
class Sight_Video_Settings {
	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_video_settings' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ), 100 );
	}

	/**
	 * Enqueue assets for gutenberg panels
	 */
	public function enqueue_block_editor_assets() {
		$post_id = get_the_ID();

		if ( ! $post_id ) {
			return;
		}

		// Data.
		$panels_data = array(
			'postType' => get_post_type( $post_id ),
		);

		// Enqueue scripts.
		wp_enqueue_script(
			'sight-video-settings',
			SIGHT_URL . 'gutenberg/jsx/video-panel.js',
			array(
				'wp-i18n',
				'wp-blocks',
				'wp-edit-post',
				'wp-element',
				'wp-editor',
				'wp-components',
				'wp-data',
				'wp-plugins',
				'wp-edit-post',
				'wp-hooks',
			),
			filemtime( SIGHT_PATH . 'gutenberg/jsx/video-panel.js' ),
			true
		);

		// Localize scripts.
		wp_localize_script( 'sight-video-settings', 'sightVideoSettings', $panels_data );
	}

	/**
	 * Register video settings
	 */
	public function register_video_settings() {

		register_post_meta(
			'sight-projects',
			'sight_post_video_url',
			array(
				'show_in_rest'  => true,
				'type'          => 'string',
				'single'        => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_post_meta(
			'sight-projects',
			'sight_post_video_bg_start_time',
			array(
				'show_in_rest'  => true,
				'type'          => 'number',
				'single'        => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_post_meta(
			'sight-projects',
			'sight_post_video_bg_end_time',
			array(
				'show_in_rest'  => true,
				'type'          => 'number',
				'single'        => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
new Sight_Video_Settings();
