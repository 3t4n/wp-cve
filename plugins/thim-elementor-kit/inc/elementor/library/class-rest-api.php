<?php
namespace Thim_EL_Kit\Elementor\Library;

use Thim_EL_Kit\SingletonTrait;

class Rest_API {
	use SingletonTrait;

	const NAMESPACE = 'thim-ekit';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	public function register_endpoints() {
		register_rest_route(
			self::NAMESPACE,
			'/get-templates',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_templates' ),
				'permission_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/import',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'import' ),
				'permission_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	public function get_templates( $request ) {
		$theme = wp_get_theme();

		if ( is_child_theme() ) {
			$theme = wp_get_theme( $theme->parent()->template );
		}

		$response = wp_remote_get( 'https://updates.thimpress.com/wp-json/thim_em/v1/thim-kit/get-library?theme=' . $theme->get( 'TextDomain' ) );

		$raw = ! is_wp_error( $response ) ? json_decode( wp_remote_retrieve_body( $response ), true ) : array();

		return $raw;
	}

	public function import( $request ) {
		$id      = $request->get_param( 'id' );
		$type    = $request->get_param( 'type' );
		$post_id = $request->get_param( 'postID' );
		$theme   = $request->get_param( 'theme' );

		try {
			if ( empty( $post_id ) ) {
				throw new \Exception( 'Post ID is required.' );
			}

			if ( empty( $id ) ) {
				throw new \Exception( 'Template ID is required.' );
			}

			if ( empty( $type ) ) {
				throw new \Exception( 'Template type is required.' );
			}

			$body = array(
				'id'    => $id,
				'type'  => $type === 'pages' ? 'page' : 'templates',
				'theme' => $theme,
			);

			if ( class_exists( '\Thim_Product_Registration' ) ) {
				$site_key = \Thim_Product_Registration::get_site_key();
				$code     = thim_core_generate_code_by_site_key( $site_key );

				if ( ! empty( $site_key ) ) {
					$body['code'] = $code ? $code : '';
				} else {
					$purchase_token = '';

					if ( method_exists( '\Thim_Product_Registration', 'get_data_theme_register' ) ) {
						$purchase_token = \Thim_Product_Registration::get_data_theme_register( 'purchase_token' );
					}

					$body['purchase_token'] = $purchase_token;
				}
			}

			$response = wp_remote_post(
				'https://updates.thimpress.com/wp-json/thim_em/v1/thim-kit/import-library',
				array(
					'body' => $body,
				)
			);

			if ( is_wp_error( $response ) ) {
				throw new \Exception( $response->get_error_message() );
			}

			$api_body = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( $api_body['code'] === 'no_code' ) {
				throw new \Exception( class_exists( '\Thim_Product_Registration' ) ? 'Please <a href="' . admin_url( '/admin.php?page=thim-dashboard' ) . '" target="_blank" rel="noopener">active theme</a> to continue.' : 'Please register your site to use this feature.' );
			}

			// go to thim license page.
			if ( $api_body['code'] === 'no_purchase_code' ) {
				throw new \Exception( class_exists( '\Thim_Product_Registration' ) ? 'Please <a href="' . admin_url( '/admin.php?page=thim-license' ) . '" target="_blank" rel="noopener">add purchase code</a> to continue.' : 'Please register your site to use this feature.' );
			}

			if ( $api_body['code'] === 'invalid_purchase_code' ) {
				throw new \Exception( class_exists( '\Thim_Product_Registration' ) ? 'Please <a href="' . admin_url( '/admin.php?page=thim-license' ) . '" target="_blank" rel="noopener">add valid purchase code</a> to continue.' : 'Please register your site to use this feature.' );
			}

			// if is error.
			if ( ! empty( $api_body['code'] ) ) {
				throw new \Exception( $api_body['message'] ?? 'Something went wrong.' );
			}

			$import = new Import();

			$import_data = $import->import( $post_id, $api_body['content'] );

			return rest_ensure_response(
				array(
					'success' => true,
					'data'    => $import_data,
				)
			);
		} catch ( \Throwable $th ) {
			return array(
				'success' => false,
				'message' => $th->getMessage(),
			);
		}
	}
}
Rest_API::instance();
