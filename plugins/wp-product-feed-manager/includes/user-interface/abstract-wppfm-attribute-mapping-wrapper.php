<?php

/**
 * WPPFM Attribute Mapping Wrapper Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Attribute_Mapping_Wrapper' ) ) :

	abstract class WPPFM_Attribute_Mapping_Wrapper {

		abstract public function display();

		protected function attribute_mapping_wrapper_table_start( $display = 'none' ) {
			return '<section class="wppfm-feed-editor-section wppfm-attribute-mapping-wrapper" id="wppfm-attribute-map" style="display:' . $display . ';">';
		}

		protected function attribute_mapping_wrapper_table_header() {
			return '<div class="wppfm-feed-editor-section__header" id="wppfm-feed-editor-attribute-mapping-header"><h3>' . __( 'Attribute Mapping', 'wp-product-feed-manager' ) . ':</h3></div>';
		}

		protected function attribute_mapping_wrapper_table_end() {
			return '</section>';
		}
	}

	// end of WPPFM_Attribute_Mapping_Wrapper class

endif;
