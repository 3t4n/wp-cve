<?php

/**
 * Elementor template library local source.
 *
 * Elementor template library local source handler class is responsible for
 * handling local Elementor templates saved by the user locally on his site.
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'ET_Core_Portability' ) ) {
	include_once ET_BUILDER_PLUGIN_DIR . '/core/components/Portability.php';
}

#[AllowDynamicProperties]

  class WFACP_Divi_Importer extends ET_Core_Portability {
	private $slug = '';
	private $post_id = 0;
	private $settings_file = '';
	private $builder = 'divi';
	public $delete_page_meta = true;

	public function __construct( $context ) {
		//Dont Need To call Parent Constructor because of some time other divi addon created fatal error Like Monarch Plugin.
	}

	public function import_child( $aero_id, $slug, $is_multi = 'no' ) {
		set_time_limit( 0 );
		$this->slug    = $slug;
		$this->post_id = $aero_id;
		$this->update_product_switcher_settings();
		update_post_meta( $aero_id, '_et_pb_use_builder', 'on' );
		if ( 'divi_1' === $slug ) {
			wp_update_post( [ 'ID' => $this->post_id, 'post_content' => '' ] );
			$this->delete_template_data( $this->post_id );
			update_post_meta( $this->post_id, '_wp_page_template', 'wfacp-canvas.php' );

			return [ 'status' => true ];
		}


		$templates = WFACP_Core()->template_loader->get_templates( $this->builder );

		if ( isset( $templates[ $slug ]['settings_file'] ) ) {
			$this->settings_file = $templates[ $slug ]['settings_file'];
		}

		if ( $templates[ $slug ] && isset( $templates[ $slug ]['build_from_scratch'] ) ) {
			$this->save_data( $aero_id );

			return [ 'status' => true ];
		}

		$data = WFACP_Core()->importer->get_remote_template( $slug, 'divi' );


		if ( isset( $data['error'] ) ) {
			return $data;
		}

		$content = $data['data'];
		if ( ! empty( $content ) ) {
			$status = $this->import_aero_template( $content );
			$this->save_data( $this->post_id );

			return [ 'status' => $status ];
		}


		return [ 'status' => true ];
	}


	private function save_data( $post_id, $content = '' ) {
		if ( true == $this->delete_page_meta ) {
			$this->delete_template_data( $post_id );
		}

		$post = get_post( $post_id );
		WFACP_Common::update_label_meta( $post_id, $post->post_content );
		update_post_meta( $post_id, '_wp_page_template', 'wfacp-canvas.php' );
		if ( ! empty( $this->settings_file ) ) {
			$file_path = __DIR__ . '/checkout-settings/' . $this->settings_file;
			WFACP_Common::import_checkout_settings( $post_id, $file_path );
		}


	}

	private function delete_template_data( $post_id ) {
		WFACP_Common::delete_page_layout( $post_id );
	}

	public function import_aero_template( $content ) {

		$success = $this->import( $content );

		return $success;
	}

	public function import( $content = 'upload' ) {
		$this->prevent_failure();
		self::$_doing_import = true;
		$import              = json_decode( $content, true );
		$data                = $import['data'];
		// Pass the post content and let js save the post.

		$data    = reset( $data );
		$success = true;
		$result  = wp_update_post( [ 'ID' => $this->post_id, 'post_content' => $data ] );
		if ( $result instanceof WP_Error ) {
			$success = false;
		}

		return $success;
	}


	public function update_product_switcher_settings() {
		if ( false !== strpos( $this->slug, 'divi_' ) ) {
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

	/**
	 * Serialize images in chunks.
	 *
	 * @param array $images
	 * @param string $method Method applied on images.
	 * @param string $id Unique ID to use for temporary files.
	 * @param integer $chunk
	 *
	 * @return array
	 * @since 4.0
	 *
	 */
	protected function chunk_images( $images, $method, $id, $chunk = 0 ) {
		$images_per_chunk = 100;
		$chunks           = 1;

		/**
		 * Filters whether or not images in the file being imported should be paginated.
		 *
		 * @param bool $paginate_images Default `true`.
		 *
		 * @since 3.0.99
		 *
		 */
		$paginate_images = apply_filters( 'et_core_portability_paginate_images', true );

		if ( $paginate_images && count( $images ) > $images_per_chunk ) {
			$chunks       = ceil( count( $images ) / $images_per_chunk );
			$slice        = $images_per_chunk * $chunk;
			$images       = array_slice( $images, $slice, $images_per_chunk );
			$images       = $this->$method( $images );
			$filesystem   = $this->get_filesystem();
			$temp_file_id = sanitize_file_name( "images_{$id}" );
			$temp_file    = $this->temp_file( $temp_file_id, 'et_core_export' );
			$temp_images  = json_decode( $filesystem->get_contents( $temp_file ), true );

			if ( is_array( $temp_images ) ) {
				$images = array_merge( $temp_images, $images );
			}

			if ( $chunk + 1 < $chunks ) {
				$filesystem->put_contents( $temp_file, wp_json_encode( (array) $images ) );
			} else {
				$this->delete_temp_files( 'et_core_export', array( $temp_file_id => $temp_file ) );
			}
		} else {
			$images = $this->$method( $images );
		}

		return array(
			'ready'  => $chunk + 1 >= $chunks,
			'chunks' => $chunks,
			'images' => $images,
		);
	}
}

if ( class_exists( 'WFACP_Template_Importer' ) ) {

	WFACP_Template_Importer::register( 'divi', new WFACP_Divi_Importer( 'et_theme_builder' ) );
}