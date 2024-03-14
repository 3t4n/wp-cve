<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFACP_Exporter
 * Handles Exporting of Aero Checkout Pages into JSON Downloadable File
 */
class WFACP_Exporter {

	private static $ins = null;

	public function __construct() {
		add_action( 'admin_init', [ $this, 'maybe_export' ] );
		add_action( 'admin_init', [ $this, 'maybe_export_single' ] );
	}

	/**
	 * @return WFACP_Exporter|null
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function maybe_export() {
		if ( empty( $_POST['wfacp-action'] ) || 'export' != $_POST['wfacp-action'] ) {
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

		$args = array(
			'post_type'      => WFACP_Common::get_post_type_slug(),
			'post_status'    => 'any',
			'posts_per_page' => - 1,
		);

		$query_result = new WP_Query( $args );
		$acp_posts    = [];
		if ( $query_result instanceof WP_Query && $query_result->have_posts() ) {
			$acp_posts = $query_result->posts;
		}

		$acps_to_export = [];
		foreach ( $acp_posts as $post_key => $post ) {
			$acps_to_export[ $post_key ] = $this->get_acp_array_for_json( $post->ID );
		}

		$acps_to_export = apply_filters( 'wfacp_export_data', $acps_to_export );

		nocache_headers();

		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=wfacp-funnels-export-' . date( 'm-d-Y' ) . '.json' );
		header( 'Expires: 0' );

		echo wp_json_encode( $acps_to_export );
		exit;
	}

	public function get_acp_array_for_json( $post_id ) {
		$post = get_post( $post_id );
		if ( is_null( $post ) ) {
			return;
		}

		$acp_json                = [];
		$acp_json['id']          = $post_id;
		$acp_json['title']       = $post->post_title;
		$acp_json['post_status'] = $post->post_status;

		$acp_meta = get_post_meta( $post_id );


		$skips_keys = [ '_elementor_css', '_elementor_controls_usage' ];
		$skips_keys = apply_filters( 'wfacp_export_skip_keys', $skips_keys );
		$meta_data  = [];
		foreach ( $acp_meta as $meta_key => $meta_value ) {
			if ( in_array( $meta_key, $skips_keys ) ) {
				continue;
			}

			$meta_value      = $meta_value[0];
			$json_meta_value = maybe_unserialize( $meta_value );
			if ( $meta_value !== $json_meta_value ) {
				$meta_data[ $meta_key ] = $json_meta_value;
			} else {
				$meta_data[ $meta_key ] = $meta_value;
			}
		}
		$customizer_data = get_option( WFACP_SLUG . '_c_' . $post_id, [] );
		$customizer_data = $this->place_customizer_image_urls( $customizer_data );
		$customizer_meta = [
			WFACP_SLUG . '_c_' => $customizer_data
		];

		$acp_json['meta']            = $meta_data;
		$acp_json['customizer_meta'] = $customizer_meta;

		return $acp_json;
	}

	// Replace Image IDs with their URLs
	protected function place_customizer_image_urls( $data ) {

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
			//If nested object
			if ( is_array( $value ) ) {
				$img_key = $value[0];

				if ( isset( $data[ $key ] ) && is_array( $data[ $key ] ) ) {
					foreach ( $data[ $key ] as $k => $v ) {
						$img = $v[ $img_key ];

						if ( ! empty( $img ) && absint( $img ) ) {
							$data[ $key ][ $k ][ $img_key ] = $this->get_image_url( $img );
						}
					}
				}
				//If Direct String
			} else if ( is_string( $value ) ) {
				$img_key = $value;

				if ( ! empty( $data[ $img_key ] ) && absint( $data[ $img_key ] ) ) {
					$data[ $img_key ] = $this->get_image_url( $data[ $img_key ] );
				}
			}

		}

		return $data;
	}

	protected function get_image_url( $attachment_id ) {
		return wp_get_attachment_image_src( absint( $attachment_id ) )[0];
	}

	public function maybe_export_single() {
		if ( empty( $_GET['action'] ) || 'wfacp-export' != $_GET['action'] ) {
			return;
		}

		$_wpnonce = filter_input( INPUT_GET, '_wpnonce', FILTER_UNSAFE_RAW );
		if ( ! wp_verify_nonce( $_wpnonce, 'wfacp-export' ) ) {
			return;
		}

		$user = WFACP_Core()->role->user_access( 'checkout', 'write' );
		if ( false === $user ) {
			return;
		}
		$post_id           = filter_input( INPUT_GET, 'id', FILTER_UNSAFE_RAW );
		$acps_to_export    = [];
		$acps_to_export[0] = $this->get_acp_array_for_json( $post_id );
		$acps_to_export    = apply_filters( 'wfacp_export_data', $acps_to_export );

		nocache_headers();

		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=wfacp-funnels-export-' . date( 'm-d-Y' ) . '.json' );
		header( 'Expires: 0' );

		echo wp_json_encode( $acps_to_export );
		exit;


	}
}


if ( class_exists( 'WFACP_Core' ) ) {
	WFACP_Core::register( 'export', 'WFACP_Exporter' );
}
