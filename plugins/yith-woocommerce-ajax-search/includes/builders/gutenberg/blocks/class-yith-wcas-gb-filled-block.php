<?php
/**
 * YITH_WCAS_Gb_Filled_Block is class to initialize Results Block
 *
 * @author  YITH
 * @package YITH/Builders/Gutenberg
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;


if ( ! class_exists( 'YITH_WCAS_Gb_Filled_Block' ) ) {
	/**
	 * Class YITH_WCAS_Gb_Search_Block
	 */
	class YITH_WCAS_Gb_Filled_Block extends Abstract_YITH_WCAS_Gb_InnerBlock {
		/**
		 * Block name.
		 *
		 * @var string
		 */
		protected $block_name = 'filled-block';
	}
}
