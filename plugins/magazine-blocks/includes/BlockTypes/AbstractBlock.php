<?php
/**
 * Abstract block.
 *
 * @package Magazine Blocks
 */

namespace MagazineBlocks\BlockTypes;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract block.
 */
abstract class AbstractBlock {

	/**
	 * Block namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'magazine-blocks';

	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = '';

	/**
	 * Constructor.
	 *
	 * @param string $block_name Block name.
	 */
	public function __construct( $block_name = '' ) {
		$this->block_name = empty( $block_name ) ? $this->block_name : $block_name;
		$this->register();
	}

	/**
	 * Register.
	 *
	 * @return void
	 */
	protected function register() {
		if ( empty( $this->block_name ) ) {
			_doing_it_wrong( __CLASS__, esc_html__( 'Block name is not set.', 'magazine-blocks' ), 'x.x.x' );
			return;
		}

		$metadata = $this->get_metadata_base_dir() . "/$this->block_name/block.json";

		if ( ! file_exists( $metadata ) ) {
			_doing_it_wrong(
				__CLASS__,
				/* Translators: 1: Block name */
				esc_html( sprintf( __( 'Metadata file for %s block does not exist.', 'magazine-blocks' ), $this->block_name ) ),
				'x.x.x'
			);
			return;
		}

		register_block_type_from_metadata(
			$metadata,
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * Get base metadata path.
	 *
	 * @return string
	 */
	protected function get_metadata_base_dir() {
		return MAGAZINE_BLOCKS_PLUGIN_DIR . '/dist';
	}

	/**
	 * Get block type.
	 *
	 * @return string
	 */
	protected function get_block_type() {
		return "$this->namespace/$this->block_name";
	}

	/**
	 * Render callback.
	 *
	 * @param array     $attributes Block attributes.
	 * @param string    $content Block content.
	 * @param \WP_Block $block Block object.
	 *
	 * @return string
	 */
	public function render( $attributes, $content, $block ) {
		return $content;
	}
}
