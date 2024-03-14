<?php
namespace CatFolder_Document_Gallery\Engine;

use CatFolder_Document_Gallery\Utils\SingletonTrait;
use CatFolders\Models\FolderModel;
use CatFolder_Document_Gallery\Helpers\Helper;

class RestAPI {

	use SingletonTrait;

	/**
	 * The Constructor that load the engine classes
	 */
	protected function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/get-all-tree-folders',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'get_all_tree_folders' ),
					'permission_callback' => array( $this, 'resPermissionsCheck' ),
				),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/get-attachments-folders',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'get_attachments_folders' ),
					'permission_callback' => '__return_true',
				),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/get-folders-shortcode-data',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'get_folder_shortcode_data' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

	public function resPermissionsCheck() {
		return current_user_can( 'upload_files' );
	}

	public function get_all_tree_folders( \WP_REST_Request $request ) {

		$orderBy   = sanitize_key( $request->get_param( 'orderby' ) );
		$orderType = sanitize_key( $request->get_param( 'ordertype' ) );

		//Get all folders
		$result = FolderModel::get_all(
			array(
				'orderBy'   => $orderBy,
				'orderType' => $orderType,
			)
		);

		//Return the response as Json format
		return new \WP_REST_Response( $result );
	}

	public function get_attachments_folders( \WP_REST_Request $request ) {
		$params = $request->get_params();
		$data   = Helper::get_attachments( $params );

		return new \WP_REST_Response( $data );
	}

	public function get_folder_shortcode_data( \WP_REST_Request $request ) {
		try {
			$params = $request->get_params();
			$data   = Helper::get_shortcode_data( $params );
		} catch ( \Exception $exc ) {
			$data = $exc->getMessage();
		}
		return new \WP_REST_Response( $data );
	}
}
