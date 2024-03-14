<?php
namespace CatFolders\Rest\Controllers;

defined( 'ABSPATH' ) || exit;

class ExportController {
	public function register_routes() {
		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/export-csv',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_csv' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);
	}

	public function permission_callback() {
		return current_user_can( 'upload_files' );
	}

	public function get_csv() {
		global $wpdb;
		$folders = $wpdb->get_results( "Select * from {$wpdb->prefix}catfolders", ARRAY_A );
		$tree    = $this->get_nested_tree( $folders, 0, true );
		return new \WP_REST_Response( array( 'folders' => $tree ) );
	}

	public function get_attachments( $folderId ) {
		global $wpdb;

		$attachments = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->prefix}catfolders_posts WHERE folder_id = %d", $folderId ) );

		return $attachments;
	}

	public function get_nested_tree( $folders, $parent = 0, $flat = false ) {
		$tree = array();

		if ( $flat ) {
			foreach ( $folders as $node ) {
				$node['attachments'] = $this->get_attachments( $node['id'] );
				$tree[]              = $node;
			}
		} else {
			foreach ( $folders as $node ) {
				if ( $node['parent'] == $parent ) {
					$node['children']    = $this->get_nested_tree( $folders, $node['id'] );
					$node['attachments'] = $this->get_attachments( $node['id'] );
					$tree[]              = $node;
				}
			}
		}

		return $tree;
	}
}
