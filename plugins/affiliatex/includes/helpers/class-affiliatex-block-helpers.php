<?php
/**
 * AFFILIATE Block Helper.
 *
 * @package AFFILIATE
 */
if ( ! class_exists( 'AffiliateX_Block_Helper' ) ) {

	/**
	 * Class AffiliateX_Block_Helper.
	 */
	class AffiliateX_Block_Helper {

		public static function get_block_css( $blockname, $attr, $id ) {
			$block_css     = '';
			$blockfilename = sanitize_title( $blockname );
			if ( file_exists(  plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/includes/helpers/block-helpers/class-' . $blockfilename . '-styles.php') ) {
				include_once plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/includes/helpers/block-helpers/class-' . $blockfilename . '-styles.php';
				$blockname = str_replace( ' ', '_', $blockname );
				$class     = "AffiliateX_{$blockname}_Styles";
				if ( class_exists( $class ) ) {
					$block_class = new $class();
					$block_css   = $block_class::block_css( $attr, $id);
				}
			}
			return $block_css;
		}

		public static function get_block_fonts( $blockname, $attr ) {
			$block_fonts   = array();
			$blockfilename = sanitize_title( $blockname );
			if ( file_exists(  plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/includes/helpers/block-helpers/class-' . $blockfilename . '-styles.php') ) {
				include_once plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/includes/helpers/block-helpers/class-' . $blockfilename . '-styles.php';
				$blockname = str_replace( ' ', '_', $blockname );
				$class     = "AffiliateX_{$blockname}_Styles";
				if ( class_exists( $class ) ) {
					$block_class = new $class();
					$block_fonts = $block_class::block_fonts( $attr);
				}
			}
			return $block_fonts;
		}

	}
}
