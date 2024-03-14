<?php

if ( ! class_exists( 'WFFN_Gutenberg_Importer' ) ) {
	class WFFN_Gutenberg_Importer implements WFFN_Import_Export {

		public function __construct() {
			add_action( 'woofunnels_module_template_removed', [ $this, 'delete_oxy_data' ] );
		}

		public function import( $module_id, $content = '' ) {
			if ( ! empty( $content ) ) {
				$data = json_decode( $content, true );


				$post_content = $data['post_content'];
				$meta_data    = $data['meta_data'];

				$content = $post_content;
				foreach ( $meta_data as $meta_key => $meta_value ) {
					update_post_meta( $module_id, $meta_key, trim( $meta_value ) );
				}
			}
			$post               = get_post( $module_id );
			$post->post_content = $content;
			wp_update_post( $post );

			return [ 'success' => true ];
		}

		public function import_template_single( $module_id, $content ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

		}

		public function export( $module_id, $slug ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
			return get_post_meta( $module_id, 'ct_builder_shortcodes', true );
		}

		public function delete_oxy_data( $post_id ) {
			wp_update_post( [ 'ID' => $post_id, 'post_content' => '' ] );
		}



		public function download_image( $url ) {
			// Extract the file name and extension from the url.
			require_once WFFN_PLUGIN_DIR . '/importer/class-wffn-image-importer.php';
			$importer       = new WFFN_Image_Importer();
			$new_attachment = $importer->import( [ 'url' => $url ] );

			return $new_attachment['url'];
		}


	}

	if ( class_exists( 'WFFN_Gutenberg_Importer' ) ) {
		WFFN_Template_Importer::register( 'gutenberg', new WFFN_Gutenberg_Importer() );
	}
}