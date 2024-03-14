<?php
/**
 * YITH_WCAS_Gb_Search_Block is class to initialize Results Block
 *
 * @author  YITH
 * @package YITH/Builders/Gutenberg
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;


if ( ! class_exists( 'YITH_WCAS_Gb_Search_Block' ) ) {
	/**
	 * Class YITH_WCAS_Gb_Search_Block
	 */
	class YITH_WCAS_Gb_Search_Block extends Abstract_YITH_WCAS_Gb_Block {
		/**
		 * Block name.
		 *
		 * @var string
		 */
		protected $block_name = 'search-block';

		/**
		 * Chunks build folder.
		 *
		 * @var string
		 */
		protected $chunks_folder = 'search-blocks';

		/**
		 * Render the block.
		 *
		 * @param array    $attributes Block attributes.
		 * @param string   $content    Block content.
		 * @param WP_Block $block      Block instance.
		 * @return string Rendered block type output.
		 */
		protected function render( $attributes, $content, $block ) {
			return $content;
		}

		/**
		 * Register script and style assets for the block type before it is registered.
		 *
		 * This registers the scripts; it does not enqueue them.
		 */
		protected function register_block_type_assets() {
			parent::register_block_type_assets();
			$chunks        = $this->get_chunks_paths( $this->chunks_folder );
			$vendor_chunks = $this->get_chunks_paths( 'vendors--search-blocks' );
			$shared_chunks = [];
			$this->register_chunk_translations( array_merge( $chunks, $vendor_chunks, $shared_chunks ) );
		}

		/**
		 * Returns the appropriate asset path for current builds.
		 *
		 * @param string $filename Filename for asset path (without extension).
		 * @param string $type File type (.css or .js).
		 *
		 * @return  string             The generated path.
		 */
		public function get_block_asset_build_path( $filename, $type = 'js' ) {
			if( 'css' !== $type ){
				return  parent::get_block_asset_build_path($filename, $type );
			}
			return "assets/css/$filename.$type";
		}
		/**
		 * Get list of Search block & its inner-block types.
		 *
		 * @return array;
		 */
		public static function get_search_block_types() {
			return [
				'Search_Block',
				'Input_Block',
				'Filled_Block',
				'Product_Results_Block',
			];
		}

	}
}
