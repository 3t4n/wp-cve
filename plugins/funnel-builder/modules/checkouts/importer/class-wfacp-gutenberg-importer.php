<?php

/**
 * Gutenburg template library local source.
 *
 * Gutenburg template library local source handler class is responsible for
 * handling local Gutenburg templates saved by the user locally on his site.
 *
 * @since 1.0.0
 */

#[AllowDynamicProperties]

  class WFACP_Gutenberg_Importer implements WFACP_Import_Export {
	private $slug = '';
	private $post_id = 0;
	private $settings_file = '';
	private $builder = 'gutenberg';
	public $delete_page_meta = true;

	public function __construct() {
	}


	public function import( $aero_id, $slug, $is_multi = 'no' ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

		$this->slug    = $slug;
		$this->post_id = $aero_id;
		$this->update_product_switcher_settings();
		$templates = WFACP_Core()->template_loader->get_templates( $this->builder );
		if ( isset( $templates[ $slug ]['settings_file'] ) ) {
			$this->settings_file = $templates[ $slug ]['settings_file'];
		}

		if ( $templates[ $slug ] && isset( $templates[ $slug ]['build_from_scratch'] ) ) {
			$this->delete_page_meta = true;
			$this->save_data( $aero_id, '' );
			update_post_meta( $aero_id, '_wp_page_template', 'wfacp-canvas.php' );

			return [ 'status' => true ];
		}

		$data = WFACP_Core()->importer->get_remote_template( $slug, $this->builder );

		if ( isset( $data['error'] ) ) {
			return $data;
		}
		$content = $data['data'];
		if ( ! empty( $content ) ) {
			$contents = json_decode( $content, true );
			if ( ! is_array( $contents ) || empty( $contents ) ) {
				return [ 'status' => false ];
			}
			$post_content = $contents['post_content'];
			$meta_data    = $contents['meta_data'];

			$this->save_data( $this->post_id, $post_content );
			foreach ( $meta_data as $meta_key => $meta_value ) {
				update_post_meta( $aero_id, $meta_key, trim( $meta_value ) );
			}

			return [ 'status' => true ];
		}

		return [ 'status' => true ];
	}


	public function update_product_switcher_settings() {
		if ( false !== strpos( $this->slug, 'gutenberg_' ) ) {
			$pageProductSetting = [
				'coupons'                             => '',
				'enable_coupon'                       => 'false',
				'disable_coupon'                      => 'false',
				'hide_quantity_switcher'              => 'false',
				'enable_delete_item'                  => 'false',
				'hide_product_image'                  => 'false',
				'is_hide_additional_information'      => 'true',
				'additional_information_title'        => '',
				'hide_quick_view'                     => 'false',
				'hide_you_save'                       => 'true',
				'hide_best_value'                     => 'false',
				'best_value_product'                  => '',
				'best_value_text'                     => 'Best Value',
				'best_value_position'                 => 'above',
				'enable_custom_name_in_order_summary' => 'false',
				'autocomplete_enable'                 => 'false',
				'autocomplete_google_key'             => '',
				'preferred_countries_enable'          => 'false',
				'preferred_countries'                 => '',
				'product_switcher_template'           => 'default',
			];

			$product_settings                     = [];
			$product_settings['settings']         = $pageProductSetting;
			$product_settings['products']         = [];
			$product_settings['default_products'] = [];
			if ( is_array( $product_settings ) && count( $product_settings ) > 0 ) {
				update_post_meta( $this->post_id, '_wfacp_product_switcher_setting', $product_settings );
			}
		}
	}

	private function save_data( $post_id, $content = '' ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
		$this->delete_other_builder_data( $post_id );
		if ( true === $this->delete_page_meta ) {
			$this->delete_template_data( $post_id );
		}

		$post               = get_post( $this->post_id );
		$post->post_content = $content;
		wp_update_post( $post );
		WFACP_Common::update_label_meta( $post_id, $content );

		$file_path = WFACP_PLUGIN_DIR . '/importer/checkout-settings/' . $this->settings_file;

		WFACP_Common::import_checkout_settings( $post_id, $file_path );

	}

	private function delete_template_data( $post_id ) {
		WFACP_Common::delete_page_layout( $post_id );
	}

	public function export( $aero_id, $slug ) {
		$data = get_post_meta( $aero_id, '_elementor_data', true );

		return $data;
	}



	public function download_image( $url ) {
		require_once WFACP_PLUGIN_DIR . '/importer/class-wfacp-image-importer.php';
		$importer       = new WFACP_Image_Importer();
		$new_attachment = $importer->import( [ 'url' => $url ] );

		return $new_attachment['url'];
	}


	public function delete_other_builder_data( $post_id ) {
		delete_post_meta( $post_id, '_et_pb_use_builder' );
		delete_post_meta( $post_id, 'ct_other_template' );
		update_post_meta( $post_id, 'ct_builder_shortcodes', ' ' );
		update_post_meta( $post_id, 'ct_builder_json', ' ' );
		delete_post_meta( $post_id, '_elementor_edit_mode' );
		delete_post_meta( $post_id, '_elementor_data' );
	}
}

if ( class_exists( 'WFACP_Template_Importer' ) ) {
	WFACP_Template_Importer::register( 'gutenberg', new WFACP_Gutenberg_Importer() );
}
