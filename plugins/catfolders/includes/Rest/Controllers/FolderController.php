<?php
namespace CatFolders\Rest\Controllers;

use CatFolders\Models\FolderModel;
use CatFolders\Core\Base;
use CatFolders\Classes\Helpers;

class FolderController extends Base {
	public function __construct() {
		parent::initialize();
	}

	public function register_routes() {
		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/folders',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_folders' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'new_folder' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/folders/(?P<folderId>\d+)',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_folder' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/folder-position/(?P<folderId>\d+)',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_folder_position' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/delete-folders',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'delete_folder' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/attachment-to-folder',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'set_attachment_to_folder' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/sort-folders',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'sort_folders' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/set-settings',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'set_settings' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/folder-property/(?P<folderId>\d+)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_folder_property' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				),
			)
		);
	}

	public function permission_callback() {
		return current_user_can( 'upload_files' );
	}

	public function get_folders( \WP_REST_Request $request ) {
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

	public function sort_folders( \WP_REST_Request $request ) {
		global $wpdb;

		$parentId      = \intval( $request->get_param( 'toParentId' ) );
		$nodeId        = \intval( $request->get_param( 'nodeId' ) );
		$nextSiblingId = $request->get_param( 'nextSiblingId' );
		$nextSiblingId = isset( $nextSiblingId ) ? \intval( $nextSiblingId ) : null;

		FolderModel::updateFolder( $nodeId, array( 'parent' => $parentId ) );

		if ( is_null( $nextSiblingId ) ) {
			FolderModel::updateToMaxOrd( $nodeId, $parentId );
		} else {
			//TODO test this case
			$oldList = FolderModel::builder()
			->select( 'id, ord' )
			->where( array( 'parent' => $parentId ) )
			->order_by( 'ord+0' )
			->get();

			$ord = 0;
			$q   = '';
			foreach ( $oldList as $folder ) {
				if ( $folder->id == $nodeId ) {
					continue;
				}
				if ( $folder->id == $nextSiblingId ) {
					$q .= "($nodeId,$ord),(" . $folder->id . ',' . ( ++$ord ) . '),';
				} else {
					$q .= "($folder->id,$ord),";
				}
				$ord++;
			}
			$q = rtrim( $q, ',' );

			FolderModel::builder()->query( 'INSERT INTO ' . $wpdb->prefix . self::CAT_FOLDERS_TABLE . ' (id, ord) VALUES ' . $q . ' ON DUPLICATE KEY UPDATE ord=VALUES(ord)' );
		}
		return new \WP_REST_Response( array( 'success' => true ) );
	}

	public function new_folder( \WP_REST_Request $request ) {
		$title  = Helpers::sanitize_array( $request->get_param( 'title' ) );
		$parent = \intval( $request->get_param( 'parent' ) );

		//TODO type must be 'attachment' for default
		$type = Helpers::sanitize_array( $request->get_param( 'type' ) );

		if ( '' === trim( $title ) || '' === $parent ) {
			return new \WP_Error( 500, __( 'Validation failed', 'catfolders' ) );
		}

		if ( FolderModel::isExistingFolderName( 0, $title, $parent ) ) {
			return new \WP_Error( 500, __( 'A folder with this name already exists. Please choose another one.', 'catfolders' ) );
		}

		$result = FolderModel::createFolder(
			array(
				'title'  => $title,
				'parent' => $parent,
				'type'   => $type,
			)
		);
		return new \WP_REST_Response( $result );
	}

	public function update_folder( \WP_REST_Request $request ) {
		$folderId = \intval( $request->get_param( 'folderId' ) );
		$parent   = \intval( $request->get_param( 'parent' ) );
		$title    = Helpers::sanitize_array( $request->get_param( 'title' ) );

		if ( '' === trim( $title ) ) {
			return new \WP_Error( 500, __( 'Validation failed', 'catfolders' ) );
		}

		if ( FolderModel::isExistingFolderName( $folderId, $title, $parent ) ) {
			return new \WP_Error( 500, __( 'A folder with this name already exists. Please choose another one.', 'catfolders' ) );
		}

		FolderModel::updateFolder(
			$folderId,
			array(
				'title' => $title,
			)
		);

		$folder = FolderModel::find( $folderId );

		if ( is_null( $folder ) ) {
			return new \WP_Error( 500, __( 'Something is wrong! Please try again', 'catfolders' ) );
		}

		return new \WP_REST_Response( $folder->attributes );
	}

	public function update_folder_position( \WP_REST_Request $request ) {
		$folderId = \intval( $request->get_param( 'folderId' ) );
		$position = \intval( $request->get_param( 'position' ) );
		$parent   = \intval( $request->get_param( 'parent' ) );

		FolderModel::updatePositionAndParent( $folderId, $position, $parent );
		return new \WP_REST_Response();
	}

	public function delete_folder( \WP_REST_Request $request ) {
		$ids    = $request->get_param( 'ids' );
		$ids    = is_array( $ids ) ? array_map( 'intval', $ids ) : intval( $ids );
		$result = FolderModel::deleteFolder( $ids );
		return new \WP_REST_Response( $result );
	}

	public function set_attachment_to_folder( \WP_REST_Request $request ) {
		$folderId = intval( $request->get_param( 'folderId' ) );
		$imgIds   = array_map( 'intval', $request->get_param( 'imgIds' ) );

		$result = FolderModel::set_attachments( $folderId, $imgIds );
		return new \WP_REST_Response( $result );
	}

	public function get_inside_folder_detail( $folderId ) {
		$children = FolderModel::get_children_ids( array( $folderId ) );

		$totalChildren = count( $children );
		$totalItems    = count( FolderModel::getPostIdsFromFolder( array_merge( $children, array( $folderId ) ) ) );

		return array(
			'totalChildren' => $totalChildren,
			'totalItems'    => $totalItems,
		);
	}

	public function get_folder_property( \WP_REST_Request $request ) {
		$folderId = \intval( $request->get_param( 'folderId' ) );
		$folder   = FolderModel::find_where( array( 'id' => $folderId ) );

		$counters = $this->get_inside_folder_detail( $folderId );

		if ( ! empty( $folder ) ) {
			$createdBy         = $folder->attributes['created_by'];
			$user              = get_userdata( $createdBy );
			$user_display_name = '';

			if ( '0' === $createdBy ) {
				$user_display_name = __( 'All Accounts', 'catfolders' );
			} else {
				if ( empty( $user ) ) {
					return new \WP_Error( '400', __( 'Invalid user request!', 'catfolders' ) );
				}
				$user_display_name = $user->data->display_name;
			}

			$userAvatarUrl = get_avatar_url( $createdBy );
			$postTypeName  = get_post_type_object( $folder->attributes['type'] )->labels->singular_name;

			$result = array(
				'user_avatar_url'   => $userAvatarUrl,
				'user_display_name' => $user_display_name,
				'type_name'         => $postTypeName,
				'folder_name'       => $folder->attributes['title'],
				'total_children'    => $counters['totalChildren'],
				'total_items'       => $counters['totalItems'],
			);
			return new \WP_REST_Response( $result );
		}
		return new \WP_Error( '400', __( 'Folder doesn\'t exist!', 'catfolders' ) );
	}

	public function set_settings( \WP_REST_Request $request ) {
		$userSettings = array(
			'sortFolder'    => Helpers::sanitize_array( $request->get_param( 'sortFolder' ) ),
			'sortFile'      => Helpers::sanitize_array( $request->get_param( 'sortFile' ) ),
			'startupFolder' => Helpers::sanitize_array( $request->get_param( 'startupMode' ) ),
		);

		$this->updateUserSettings( $userSettings );

		wp_send_json_success();
	}
}
