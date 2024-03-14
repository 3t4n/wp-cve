<?php
/**
 * Magazine Blocks Library Data Controller.
 *
 * @package Magazine Blocks
 */

namespace MagazineBlocks\RestApi\Controllers;

defined( 'ABSPATH' ) || exit;

/**
 * LibraryData controller.
 */
class LibraryDataController extends \WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = 'magazine-blocks/v1';

	/**
	 * The base of this controller's route.
	 *
	 * @var string
	 */
	protected $rest_base = 'library-data';

	/**
	 * {@inheritDoc}
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => array(
						'refresh' => array(
							'default'           => false,
							'sanitize_callback' => 'rest_sanitize_boolean',
							'required'          => false,
						),
					),
				),
			)
		);
	}

	/**
	 * Check if a given request has access to get items.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return true|\WP_Error
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new \WP_Error(
				'rest_forbidden',
				esc_html__( 'You are not allowed to access this resource.', 'magazine-blocks' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}
		return true;
	}

	/**
	 * Get library data.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_items( $request ) {
		$refresh = $request->get_param( 'refresh' ) ?? false;

		$refresh && delete_transient( '_magazine_blocks_library_data' );

		$data = get_transient( '_magazine_blocks_library_data' );

		if ( empty( $data ) ) {
			$response = wp_remote_get(
				'https://wpblockart.com/wp-json/magazine-blocks-library/v1/all',
				array(
					'timeout' => 120,
				)
			);

			if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
				return new \WP_Error(
					'rest_forbidden',
					esc_html__( 'You are not allowed to access this resource.', 'magazine-blocks' ),
					array( 'status' => rest_authorization_required_code() )
				);
			}

			$data = wp_remote_retrieve_body( $response );

			set_transient( '_magazine_blocks_library_data', $data, WEEK_IN_SECONDS );
		}

		return new \WP_REST_Response( $data, 200 );
	}
}
