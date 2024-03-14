<?php
namespace CatFolders\Rest\Controllers;

use CatFolders\Blocks\GalleryBlock;

defined( 'ABSPATH' ) || exit;

class BlockController {
	public function register_routes() {
		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/get-attachments-from-folders',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_attachments_from_folders' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);
	}

	public function permission_callback() {
		return true;
	}

	public function get_attachments_from_folders( \WP_REST_Request $request ) {
		$folders = explode( ',', $request->get_param( 'folders' ) );

		$data = GalleryBlock::instance()->get_attachments( array( 'folders' => $folders ) );

		return new \WP_REST_Response( $data );
	}
}
