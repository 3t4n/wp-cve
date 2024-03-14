<?php

/**
 * Elementor template library local source.
 *
 * Elementor template library local source handler class is responsible for
 * handling local Elementor templates saved by the user locally on his site.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'WFFN_WP_editor_Importer' ) ) {
	class WFFN_WP_editor_Importer implements WFFN_Import_Export {

		public function import( $post_id, $export_content = '' ) {

			wp_update_post( [
				'ID'           => $post_id,
				'post_content' => '',
			] );

			return true;

		}

		public function export( $module_id, $slug ) {
			//do something
		}


		public function import_template_single( $module_id, $content ) {
		}
	}

	if ( class_exists( 'WFFN_Template_Importer' ) ) {
		WFFN_Template_Importer::register( 'wp_editor', new WFFN_WP_editor_Importer() );

	}
}
