<?php

/**
 * Elementor template library local source.
 *
 * Elementor template library local source handler class is responsible for
 * handling local Elementor templates saved by the user locally on his site.
 *
 * @since 1.0.0
 */

#[AllowDynamicProperties]

  class WFACP_Customizer_Embed_Form_Importer implements WFACP_Import_Export {
	private $builder = 'embed_forms';
	private $settings_file = '';

	public function __construct() {

	}

	public function import( $aero_id, $slug, $is_multi = 'no' ) {
		WFACP_Common::delete_page_layout( $aero_id );
		$data                = WFACP_Core()->importer->get_remote_template( $slug, 'pre-built' );
		$templates           = WFACP_Core()->template_loader->get_templates( $this->builder );
		$this->settings_file = $templates[ $slug ]['settings_file'];


		if ( isset( $data['error'] ) ) {
			return $data;
		}
		wp_update_post( [ 'ID' => $aero_id, 'post_content' => '[wfacp_forms]' ] );

		if ( isset( $templates[ $slug ]['settings_file'] ) ) {
			$file_path = __DIR__ . '/checkout-settings/' . $this->settings_file;
			WFACP_Common::import_checkout_settings( $aero_id, $file_path );
		}

		update_post_meta( $aero_id, 'ct_other_template', '-1' );
		update_post_meta( $aero_id, '_wp_page_template', 'wfacp-full-width.php' );

		return [ 'status' => true ];
	}


	public function export( $aero_id, $slug ) {
		return [];
	}


}

if ( class_exists( 'WFACP_Template_Importer' ) ) {
	WFACP_Template_Importer::register( 'embed_forms', new WFACP_Customizer_Embed_Form_Importer() );
}