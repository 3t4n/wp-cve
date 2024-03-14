<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFACP_Importer
 * Handles Importing of Aero Checkout Pages Export JSON file
 */
#[AllowDynamicProperties]

  class WFACP_Importer {

	private static $ins = null;
	public $is_imported = false;

	public function __construct() {
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		//@todo: Warning! For Development Purpose Only. Delete it when going to Production level
		//remove flag that prevent local WP sites (Same IP, Hosts) to download images from each other
		add_filter( 'http_request_host_is_external', '__return_true' );

		add_action( 'admin_init', [ $this, 'maybe_import' ] );
	}

	/**
	 * @return WFACP_Importer|null
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	/**
	 * Import our exported file
	 *
	 * @since 1.1.4
	 */
	function maybe_import() {
		if ( empty( $_POST['wfacp-action'] ) || 'import' != $_POST['wfacp-action'] ) {
			return;
		}
		$nonce = filter_input( INPUT_POST, 'wfacp-action-nonce', FILTER_UNSAFE_RAW );
		if ( ! wp_verify_nonce( $nonce, 'wfacp-action-nonce' ) ) {
			return;
		}

		$user = WFACP_Core()->role->user_access( 'checkout', 'write' );
		if ( false === $user ) {
			return;
		}
		if ( ! isset( $_FILES['file']['name'] ) ) {
			return;
		}
		$filename  = wc_clean( $_FILES['file']['name'] );
		$file_info = explode( '.', $filename );
		$extension = end( $file_info );

		if ( 'json' != $extension ) {
			wp_die( __( 'Please upload a valid .json file', 'woofunnels-aero-checkout' ) );
		}
		if ( ! isset( $_FILES['file']['tmp_name'] ) ) {
			return;
		}
		$file = wc_clean( $_FILES['file']['tmp_name'] );

		if ( empty( $file ) ) {
			wp_die( __( 'Please upload a file to import', 'woofunnels-aero-checkout' ) );
		}

		// Retrieve the settings from the file and convert the JSON object to an array.
		$acps = json_decode( file_get_contents( $file ), true );

		$this->import_from_json_data( $acps );

		$this->is_imported = true;
	}

	public function import_from_json_data( $acps ) {
		$imported_acps = [];

		foreach ( $acps as $acp ) {
			$acp_id = 0;
			if ( isset( $acp['id'] ) && ! empty( $acp['id'] ) ) {
				$acp_id = $acp['id'];
			}

			$acp_post = null;
			if ( $acp_id !== 0 ) {
				$acp_post = get_post( $acp_id );
			}


			$acp_title = $acp['title'];
			if ( null !== $acp_post && $acp_title === $acp_post->post_title ) {
				$acp_title = $acp_title . ' Copy';
			}

			$acp_post_args = array(
				'post_title'   => $acp_title,
				'post_type'    => WFACP_Common::get_post_type_slug(),
				'post_status'  => isset( $acp['post_status'] ) ? $acp['post_status'] : 'published',
				'post_content' => isset( $acp['post_content'] ) ? $acp['post_content'] : '',
			);
			$acp_id        = wp_insert_post( $acp_post_args );

			if ( $acp_id !== 0 && isset( $acp['meta'] ) && is_array( $acp['meta'] ) ) {
				$acp_meta = $acp['meta'];

				//Import every Aero Checkout page meta
				$acp_meta = apply_filters( 'wfacp_json_importer_meta', $acp_meta );
				foreach ( $acp_meta as $meta_key => $meta_value ) {
					update_post_meta( $acp_id, $meta_key, $meta_value );
				}

				//Import the customizer setting
				$customizer_meta = isset( $acp['customizer_meta'] ) ? $acp['customizer_meta'][ WFACP_SLUG . '_c_' ] : [];
				$customizer_meta = apply_filters( 'wfacp_json_importer_customizer_meta', $customizer_meta );
				$customizer_meta = $this->import_customizer_image_urls( $customizer_meta );
				if ( isset( $customizer_meta ) && is_array( $customizer_meta ) ) {
					update_option( WFACP_SLUG . '_c_' . $acp_id, $customizer_meta, 'no' );
				}

				if ( isset( $acp_meta['_wfacp_selected_design'] ) && isset( $acp_meta['_wfacp_selected_design']['selected_type'] ) && 'elementor' === $acp_meta['_wfacp_selected_design']['selected_type'] ) {
					if ( class_exists( 'WFACP_Elementor_Importer' ) ) {
						if ( class_exists( '\Elementor\Plugin' ) && defined( 'ELEMENTOR_VERSION' ) ) {
							if ( version_compare( ELEMENTOR_VERSION, '3.1.0', '<=' ) ) {
								\Elementor\Plugin::$instance->db->set_is_elementor_page( $acp_id, true );
							} else {
								\Elementor\Plugin::$instance->documents->get( $acp_id )->set_is_built_with_elementor( true );
							}
						}

						$obj            = new WFACP_Elementor_Importer();
						$elementor_data = is_string( $acp_meta['_elementor_data'] ) ? $acp_meta['_elementor_data'] : json_encode( $acp_meta['_elementor_data'] );
						$obj->import_aero_template( $acp_id, $elementor_data );
					}
				}

				if ( isset( $acp_meta['_wfacp_selected_design'] ) && isset( $acp_meta['_wfacp_selected_design']['selected_type'] ) && 'gutenberg' === $acp_meta['_wfacp_selected_design']['selected_type'] ) {
					$temp_post               = get_post( $acp_id );
					$temp_post->post_content = $acp['post_content'];
					wp_update_post( $temp_post );
				}
				do_action( 'wfacp_acp_imported', $acp_meta, $customizer_meta );

				$imported_acps[] = $acp_id;
			}
		}

		return $imported_acps;
	}

	// Import Images from URLs

	protected function import_customizer_image_urls( $data ) {

		$image_keys = array(
			'wfacp_testimonials_0_section_testimonials'  => array( 'timage' ),
			'wfacp_assurance_0_section_mwidget_listw'    => array( 'mwidget_image' ),
			'wfacp_promises_0_section_promise_icon_text' => array( 'promises_icon' ),
			'wfacp_header_section_logo',
			'wfacp_product_section_product_image',
			'wfacp_gbadge_section_layout_1_custom_list_image',
			'wfacp_customer_0_section_supporter_image',
			'wfacp_customer_0_section_supporter_signature_image'
		);


		foreach ( $image_keys as $key => $value ) {
			if ( is_array( $value ) ) {
				$img_key = $value[0];

				if ( isset( $data[ $key ] ) && is_array( $data[ $key ] ) ) {
					foreach ( $data[ $key ] as $k => $v ) {
						$img = $v[ $img_key ];

						if ( ! empty( $img ) ) {
							$data[ $key ][ $k ][ $img_key ] = $this->import_image( $img );
						}
					}
				}

			} else if ( is_string( $value ) ) {
				$img_key = $value;

				if ( ! empty( $data[ $img_key ] ) ) {
					$data[ $img_key ] = $this->import_image( $data[ $img_key ] );
				}
			}

		}

		return $data;
	}

	protected function import_image( $url ) {

		$saved_image = $this->get_saved_image( $url );

		if ( $saved_image ) {
			return $saved_image;
		}

		// Extract the file name and extension from the url.
		$filename = basename( $url );

		$file_content = wp_remote_retrieve_body( wp_safe_remote_get( $url ) );

		if ( empty( $file_content ) ) {
			return false;
		}

		$upload = wp_upload_bits( $filename, null, $file_content );

		if ( ! empty( $upload['error'] ) ) {
			return $url;
		}

		$post = [
			'post_title' => $filename,
			'guid'       => $upload['url'],
		];

		$info = wp_check_filetype( $upload['file'] );
		if ( $info ) {
			$post['post_mime_type'] = $info['type'];
		} else {
			// For now just return the origin attachment
			return $url;
			// return new \WP_Error( 'attachment_processing_error', __( 'Invalid file type.', 'elementor' ) );
		}

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		$post_id = wp_insert_attachment( $post, $upload['file'] );
		wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );
		update_post_meta( $post_id, '_bwf_source_image_hash', $this->get_hash_image( $url ) );

		$new_attachment = $upload['url'];

		return $new_attachment;
	}

	private function get_saved_image( $url ) {

		global $wpdb;
		$post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT `post_id` FROM `' . $wpdb->postmeta . '`
					WHERE `meta_key` = \'_bwf_source_image_hash\'
						AND `meta_value` = %s
				;', $this->get_hash_image( $url ) ) );

		if ( $post_id ) {
			$new_attachment = wp_get_attachment_url( $post_id );


			return $new_attachment;
		}

		return false;
	}

	private function get_hash_image( $attachment_url ) {
		return sha1( $attachment_url );
	}

	public function imported_successfully() {
		?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Imported Successfully!', 'woofunnels-order-acp' ); ?></p>
        </div>
		<?php
	}


}


if ( class_exists( 'WFACP_Core' ) ) {
	WFACP_Core::register( 'import', 'WFACP_Importer' );
}
