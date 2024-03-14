<?php
namespace GPLSCore\GPLS_PLUGIN_WGR;

/**
 * GIF Attachment Post Class.
 */
class GIF_Post {

	/**
	 * Plugin Info Array.
	 *
	 * @var array
	 */
	protected static $plugin_info;

	/**
	 * Core Object.
	 *
	 * @var object
	 */
	protected static $core;

	/**
	 * Singular Instance.
	 *
	 * @var GIF_Post
	 */
	private static $instance = null;


	/**
	 * Constructor.
	 *
	 * @param object $core
	 * @param array  $plugin_info
	 */
	private function __construct( $core, $plugin_info ) {
		self::$core        = $core;
		self::$plugin_info = $plugin_info;

		$this->hooks();
	}


	/**
	 * Singular Init.
	 *
	 * @param array  $plugin_info
	 * @param object $core
	 * @return GIF_Post
	 */
	public static function init( $plugin_info, $core ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $core, $plugin_info );
		}
		return self::$instance;
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	private function hooks() {
		add_action( 'add_meta_boxes', array( $this, 'gif_meta_boxes' ), 1000, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'metabox_assets' ) );
	}

	/**
	 * MetaBox Assets.
	 *
	 * @return void
	 */
	public function metabox_assets() {
	}

	/**
	 * GIF Meta Boxes.
	 *
	 * @param string   $post_type
	 * @param \WP_Post $post
	 * @return void
	 */
	public function gif_meta_boxes( $post_type, $post ) {
		if ( ! wp_attachment_is_image( $post ) || ( 'attachment' !== $post_type ) || ( 'image/gif' !== $post->post_mime_type ) ) {
			return;
		}
		// Create Static First Frame.
		add_meta_box(
			self::$plugin_info['name'] . '-gif-metabox-wrapper',
			esc_html__( 'WP GIF Editor [GrandPlugins]', 'wp-gif-editor' ),
			array( $this, 'gif_metabox' ),
			'attachment',
			'side',
			'high'
		);
	}

	/**
	 * GIF Metabox.
	 *
	 * @param \WP_Post $attachment_post
	 * @return void
	 */
	public function gif_metabox( $attachment_post ) {
		load_template(
			self::$plugin_info['path'] . 'templates/gif-post-metabox.php',
			true,
			array(
				'plugin_info' => self::$plugin_info,
				'core'        => self::$core,
			)
		);
	}
}
