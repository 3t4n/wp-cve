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

  class WFACP_Elementor_Importer extends Elementor\TemplateLibrary\Source_Local implements WFACP_Import_Export {

	private $is_multi = 'no';
	private $slug = '';
	public $delete_page_meta = true;
	private $post_id = 0;
	private $settings_file = '';
	private $builder = 'elementor';

	public function __construct() {
		//DO NOT DELETE
	}

	public function import( $aero_id, $slug, $is_multi = 'no' ) {
		$this->slug     = $slug;
		$this->is_multi = $is_multi;
		if ( 'elementor_1' === $slug ) {
			wp_update_post( [ 'ID' => $aero_id, 'post_content' => '' ] );
			delete_post_meta( $aero_id, '_elementor_data' );
			$this->delete_template_data( $aero_id );

			update_post_meta( $aero_id, '_wp_page_template', 'wfacp-canvas.php' );

			return [ 'status' => true ];
		}


		$templates           = WFACP_Core()->template_loader->get_templates( $this->builder );
		$this->settings_file = $templates[ $slug ]['settings_file'];
		if ( $templates[ $slug ] && isset( $templates[ $slug ]['build_from_scratch'] ) ) {
			$this->save_data( $aero_id );

			return [ 'status' => true ];
		}

		$data = WFACP_Core()->importer->get_remote_template( $slug, $this->builder );

		if ( isset( $data['error'] ) ) {
			return $data;
		}

		$content = $data['data'];

		if ( ! empty( $content ) ) {
			$status = $this->import_aero_template( $aero_id, $content );


			return [ 'status' => $status ];
		}

		return [ 'error' => __( 'Something Went wrong', 'woofunnels-aero-checkout' ) ];
	}


	public function export( $aero_id, $slug ) {
		$data = get_post_meta( $aero_id, '_elementor_data', true );

		return $data;
	}


	/**
	 *  Import single template
	 *
	 * @param int $post_id post ID.
	 */
	public function import_aero_template( $post_id, $content ) {


		wp_update_post( [ 'ID' => $post_id, 'post_content' => '' ] );
		delete_post_meta( $post_id, '_elementor_data' );


		if ( empty( $content ) ) {
			$this->clear_cache();

			return true;
		}
		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.3.0', '>' ) ) {
			$content = str_replace( 'hidden-phone', 'hidden-mobile', $content );
		}
		$content = json_decode( $content, true );

		if ( ! is_array( $content ) ) {
			return false;
		}


		if ( isset( $content['content'] ) && ! empty( $content['content'] ) ) {
			$content = $content['content'];
		}

		if ( empty( $content ) ) {
			return false;
		}
		$this->save_data( $post_id, $content );

		return true;
	}


	private function save_data( $post_id, $content = '' ) {
		if ( true == $this->delete_page_meta ) {
			$this->delete_template_data( $post_id );
		}
		if ( '' !== $content ) {
            $content = wp_slash( wp_json_encode( $content ) );
			update_post_meta( $post_id, '_elementor_data', $content );
			WFACP_Common::update_label_meta( $post_id, $content );
		}
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_wp_page_template', 'wfacp-canvas.php' );

		if ( ! empty( $this->settings_file ) ) {
			$file_path = __DIR__ . '/checkout-settings/' . $this->settings_file;
			WFACP_Common::import_checkout_settings( $post_id, $file_path );
		}

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			update_post_meta( $post_id, '_elementor_version', ELEMENTOR_VERSION );
		}
		$this->clear_cache();
	}

	private function delete_template_data( $post_id ) {
		WFACP_Common::delete_page_layout( $post_id );
	}

	public function clear_cache() {
		$this->generate_kit();
		Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	public function generate_kit() {
		if ( is_null( Elementor\Plugin::$instance ) || ! Elementor\Plugin::$instance->kits_manager instanceof Elementor\Core\Kits\Manager ) {
			return;
		}
		$kit = Elementor\Plugin::$instance->kits_manager->get_active_kit();
		if ( $kit->get_id() ) {
			return;
		}
		$created_default_kit = Elementor\Plugin::$instance->kits_manager->create_default();
		if ( ! $created_default_kit ) {
			return;
		}
		update_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, $created_default_kit );
	}


}

if ( class_exists( 'WFACP_Template_Importer' ) ) {
	WFACP_Template_Importer::register( 'elementor', new WFACP_Elementor_Importer() );
}