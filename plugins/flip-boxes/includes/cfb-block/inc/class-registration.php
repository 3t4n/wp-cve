<?php
namespace CoolPlugins\GutenbergBlocks;

/**
 * Class Registration.
 */
class Registration {

	/**
	 * The main instance var.
	 *
	 * @var Registration|null
	 */
	public static $instance = null;

	/**
	 * Flag to list all the blocks.
	 *
	 * @var array
	 */
	public static $blocks = array();


	/**
	 * Initialize the class
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_blocks' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) ); // Don't change the priority or else Blocks CSS will stop working.
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_assets' ) );
	}

	/**
	 * Get block metadata from file.
	 *
	 * @param string $metadata_file Metadata file link.
	 *
	 * @return mixed
	 * @since   2.0.0
	 * @access public
	 */
	public function get_metadata( $metadata_file ) {
		if ( ! file_exists( $metadata_file ) ) {
			return false;
		}

		$metadata = array();

		$metadata = json_decode( file_get_contents( $metadata_file ), true );

		if ( ! is_array( $metadata ) || empty( $metadata['name'] ) ) {
			return false;
		}

		return $metadata;
	}

	/**
	 * Load Gutenberg blocks.
	 *
	 * @since   2.0.0
	 * @access  public
	 */
	public function enqueue_block_editor_assets() {
		$asset_file = include CFB_DIR_PATH . 'includes/cfb-block/build/index.asset.php';

		$current_screen = get_current_screen();

		wp_enqueue_script(
			'cfb-blocks',
			CFB_URL . 'includes/cfb-block/build/index.js',
			array_merge(
				$asset_file['dependencies']
			),
			$asset_file['version'],
			true
		);

		wp_localize_script(
			'cfb-blocks',
			'cfbBlockGutenbergObject',
			array(
				'isBlockEditor' => 'post' === $current_screen->base,
				'cfbBlockIcon'  => CFB_URL . 'assets/images/flip-icon-90x90.png',
				'cfbBlockUrl'   => CFB_URL,
			)
		);

		wp_enqueue_style( 'cfb-block-editor', CFB_URL . 'includes/cfb-block/build/index.css', array( 'wp-edit-blocks' ), $asset_file['version'] );
		wp_enqueue_style( 'cfb-block-fontawesome', CFB_URL . 'assets/fontawesome/css/font-awesome.min.css', array(), CFB_VERSION );
	}

	/**
	 * Load frontend assets for our blocks.
	 *
	 * @since   2.0.0
	 * @access  public
	 */
	public function enqueue_block_assets() {

		if ( is_admin() ) {
			return;
		}

		if ( is_singular() ) {
			$this->enqueue_block_styles();
		}

	}

	/**
	 * Enqueue block styles.
	 *
	 * @since   2.0.0
	 * @param null $post Current post.
	 * @access  public
	 */
	public function enqueue_block_styles( $post = null ) {
		if ( has_block( 'cp/cool-flipbox-block', $post ) ) {
			$block_path = CFB_DIR_PATH . 'includes/cfb-block/build';

			$metadata_file = trailingslashit( $block_path ) . 'block.json';
			$style_file    = trailingslashit( $block_path ) . 'style-index.css';
			$metadata      = $this->get_metadata( $metadata_file );
			// $metadata_file = CFB_URL . 'includes/cfb-block/build/' . 'block.json';
			$style_path = CFB_URL . 'includes/cfb-block/build/' . 'style-index.css';
			if ( false !== $metadata ) {
				$asset_file = include $block_path . '/index.asset.php';

				if ( file_exists( $style_file ) && ! empty( $metadata['style'] ) ) {
					wp_register_style(
						$metadata['style'],
						$style_path,
						array(),
						$asset_file['version']
					);

					wp_style_add_data( $metadata['style'], 'path', $style_path );
				}
			}
		}
	}

	/**
	 * Blocks Registration.
	 *
	 * @since   2.0.0
	 * @access  public
	 */
	public function register_blocks() {

			$block_path   = CFB_DIR_PATH . 'includes/cfb-block/build/';
			$editor_style = CFB_URL . 'includes/cfb-block/build/index.css';

			$metadata_file = trailingslashit( $block_path ) . 'block.json';

			$metadata = $this->get_metadata( $metadata_file );

			$asset_file = include CFB_DIR_PATH . 'includes/cfb-block/build/index.asset.php';
			$deps       = array();
		if ( file_exists( $editor_style ) && ! empty( $metadata['editorStyle'] ) ) {
			wp_register_style(
				$metadata['editorStyle'],
				$editor_style,
				$deps,
				$asset_file['version']
			);
		}

		register_block_type_from_metadata( $metadata_file );
	}

	/**
	 * The instance method for the static class.
	 * Defines and returns the instance of the static class.
	 *
	 * @static
	 * @since 1.0.0
	 * @access public
	 * @return Registration
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'cfb-blocks' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'cfb-blocks' ), '1.0.0' );
	}
}
