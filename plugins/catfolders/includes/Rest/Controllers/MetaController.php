<?php
namespace CatFolders\Rest\Controllers;

use CatFolders\Internals\Modules\MediaMeta;
use CatFolders\Classes\Helpers;

class MetaController {
	public function register_routes() {
		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/generate-sizes',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'generate_size_api' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);
	}

	public function permission_callback() {
		return current_user_can( 'upload_files' );
	}

	public function generate_size_api( \WP_REST_Request $request ) {
		$page = intval( $request->get_param( 'page' ) );
		if ( $page < 1 ) {
			$page = 1;
		}

		$result = array();

		$args = array(
			'post_type'      => 'attachment',
			'posts_per_page' => 50,
			'post_status'    => 'inherit',
			'fields'         => 'ids',
			'paged'          => $page,
		);

		$query = new \WP_Query( $args );
		$ids   = $query->posts;

		wp_reset_postdata();

		if ( is_array( $ids ) && count( $ids ) > 0 ) {
			foreach ( $ids as $id ) {
				$bytes = Helpers::get_bytes( $id );
				if ( $bytes ) {
					update_post_meta( $id, MediaMeta::SIZE_KEY, $bytes );
				}
			}
			$result['success'] = true;
			$result['next']    = '1';
		} else {
			$result['success'] = true;
			$result['next']    = '0';
		}
		return new \WP_REST_Response( $result );
	}
}
