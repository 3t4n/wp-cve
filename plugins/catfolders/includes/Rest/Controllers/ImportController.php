<?php
namespace CatFolders\Rest\Controllers;

use CatFolders\Models\FolderModel;
use CatFolders\Classes\Helpers;

defined( 'ABSPATH' ) || exit;

class ImportController {
	private $plugins_to_import = array();

	public function __construct() {
		$this->plugins_to_import = array(
			'FB'      => array(
				'plugin_name'    => 'FileBird',
				'author'         => 'NinjaTeam',
				'folder_counter' => 0,
				'custom_db'      => 'fbv',
				'imported'       => false,
			),
			'WF'      => array(
				'plugin_name'    => 'Wicked Folders',
				'author'         => 'Wicked Plugins',
				'folder_counter' => 0,
				'taxonomy'       => 'wf_attachment_folders',
				'imported'       => false,
			),
			'EML'     => array(
				'plugin_name'    => 'Enhanced Media Library',
				'author'         => 'wpUXsolutions',
				'folder_counter' => 0,
				'taxonomy'       => 'media_category',
				'imported'       => false,
			),
			'MLA'     => array(
				'plugin_name'    => 'Media Library Assistant',
				'author'         => 'David Lingren',
				'folder_counter' => 0,
				'taxonomy'       => 'attachment_category',
				'imported'       => false,
			),

			'WMLF'    => array(
				'plugin_name'    => 'WordPress Media Library Folders',
				'author'         => 'Max Foundry',
				'folder_counter' => 0,
				'post_type'      => 'mgmlp_media_folder',
				'imported'       => false,
			),

			'WPMF'    => array(
				'plugin_name'    => 'WP Media folder',
				'author'         => 'Joomunited',
				'folder_counter' => 0,
				'taxonomy'       => 'wpmf-category',
				'imported'       => false,
			),

			'RML'     => array(
				'plugin_name'    => 'WP Real Media Library',
				'author'         => 'devowl.io',
				'folder_counter' => 0,
				'custom_db'      => 'realmedialibrary',
				'imported'       => false,
			),

			'HP'      => array(
				'plugin_name'    => 'HappyFiles',
				'author'         => 'Codeer',
				'prefix'         => 'HP',
				'folder_counter' => 0,
				'taxonomy'       => 'happyfiles_category',
				'imported'       => false,
			),

			'Folders' => array(
				'plugin_name'    => 'Folders',
				'author'         => 'Premio',
				'folder_counter' => 0,
				'taxonomy'       => 'media_folder',
				'imported'       => false,
			),
		);
	}

	public function detect_import() {
		$plugin_can_import = array();

		foreach ( $this->plugins_to_import as $key => $plugin ) {
			$has_import = get_option( $this->get_prefix_option( $key, 'success_import' ) );

			$counter = $this->get_folder_counter( $key );
			if ( \intval( $counter ) > 0 ) {
				$this->plugins_to_import[ $key ]['folder_counter'] = $has_import ? -1 : \intval( $counter );
				$this->plugins_to_import[ $key ]['prefix']         = $key;
				array_push( $plugin_can_import, $this->plugins_to_import[ $key ] );
			}
		}

		return $plugin_can_import;
	}

	public function get_folder_counter( $prefix ) {
		global $wpdb;
		if ( in_array( $prefix, array( 'EML', 'HP', 'Folders', 'WPMF', 'MLA', 'WF' ), true ) ) {
			return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(term_taxonomy_id) FROM $wpdb->term_taxonomy WHERE taxonomy = %s", $this->plugins_to_import[ $prefix ]['taxonomy'] ) );
		}

		if ( 'WMLF' === $prefix ) {
			return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = %s", $this->plugins_to_import[ $prefix ]['post_type'] ) );
		}

		if ( 'RML' === $prefix || 'FB' === $prefix ) {
			$table = $wpdb->prefix . $this->plugins_to_import[ $prefix ]['custom_db'];
			if ( $this->table_exist( $table ) ) {
				return $wpdb->get_var( 'SELECT COUNT(id) FROM ' . $table );
			}
			return 0;
		}
	}

	public function register_routes() {
		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/import/get-folder-structure/(?P<prefix>[a-zA-Z]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_folder_structure' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/import/get-attachment-in-folder/(?P<prefix>[a-zA-Z]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_attachment_in_folder' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/import/process',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'process' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/clean-db',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'clean_db' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			CATF_ROUTE_NAMESPACE,
			'/import-csv',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'import_csv' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

	}

	public function permission_callback() {
		return current_user_can( 'upload_files' );
	}

	public function import_csv( \WP_REST_Request $request ) {
		$params  = $request->get_file_params();
		$handle  = \fopen( $params['file']['tmp_name'], 'r' );
		$data    = array();
		$columns = array();
		$bom     = "\xef\xbb\xbf";
		if ( false !== $handle ) {
			$count = 1;
			while ( 1 ) {
				$row = fgetcsv( $handle, 0 );
				if ( 1 === $count ) {
					$columns = $row;
					$count++;
					continue;
				}
				if ( false === $row ) {
					break;
				}
				foreach ( $columns as $key => $col ) {
					$tmp[ $col ] = $row[ $key ];
				}
				$data[] = $tmp;
			}
		}
		\fclose( $handle );

		$this->restore_folders( $data );

		return new \WP_REST_Response( array( 'success' => true ) );
	}

	public function get_prefix_option( $prefix = '', $option_name = '' ) {
		$catfPrefix = '_catf_';

		return $catfPrefix . $prefix . '_' . $option_name;
	}

	public function get_folder_structure( \WP_REST_Request $request ) {
		$prefix = Helpers::sanitize_array( $request->get_param( 'prefix' ) );

		if ( isset( $this->plugins_to_import[ $prefix ] ) ) {
			if ( in_array( $prefix, array( 'EML', 'HP', 'Folders', 'WPMF', 'MLA', 'WF' ), true ) ) {
				$folders = $this->get_term_folders( 0, $this->plugins_to_import[ $prefix ]['taxonomy'] );
			}

			if ( 'WMLF' === $prefix ) {
				$folders = $this->get_wmlf_folders( 0, $this->plugins_to_import[ $prefix ]['post_type'] );
			}

			if ( 'RML' === $prefix ) {
				$folders = $this->get_rml_folders( -1, $this->plugins_to_import[ $prefix ]['custom_db'] );
			}

			if ( 'FB' === $prefix ) {
				$folders = $this->get_fb_folders( 0, $this->plugins_to_import[ $prefix ]['custom_db'] );
			}

			update_option( $this->get_prefix_option( $prefix, 'folders_import' ), $folders, 'no' );
			return new \WP_REST_Response( array( 'result' => true ) );
		}

		return new \WP_Error( '500', __( 'Plugin does not support!', 'catfolders' ) );
	}

	public function get_fb_folders( $parent, $custom_db, $check_table_exist = true ) {
		global $wpdb;
		if ( $check_table_exist && ! $this->table_exist( $wpdb->prefix . $custom_db ) ) {
			return array();
		}
		$query = $wpdb->prepare( "SELECT id AS term_id, name FROM {$wpdb->prefix}$custom_db WHERE parent = %d", $parent );

		$folders = $wpdb->get_results( $query, ARRAY_A );

		foreach ( $folders as $key => $folder ) {
			$folders[ $key ]['children'] = $this->get_fb_folders( $folder['term_id'], $custom_db, false );
		}

		return $folders;
	}

	public function get_rml_folders( $parent, $custom_db, $check_table_exist = true ) {
		global $wpdb;

		if ( $check_table_exist && ! $this->table_exist( $wpdb->prefix . $custom_db ) ) {
			return array();
		}

		$query = $wpdb->prepare( "SELECT id AS term_id, name FROM {$wpdb->prefix}$custom_db WHERE parent = %d", $parent );

		$folders = $wpdb->get_results( $query, ARRAY_A );

		foreach ( $folders as $key => $folder ) {
			$folders[ $key ]['children'] = $this->get_rml_folders( $folder['term_id'], $custom_db, false );
		}

		return $folders;
	}

	public function get_wmlf_folders( $parent, $post_type, $check_table_exist = true ) {
		global $wpdb;

		$table = $wpdb->prefix . 'mgmlp_folders';

		if ( $check_table_exist && ! $this->table_exist( $table ) ) {
			return array();
		}

		$query = $wpdb->prepare(
			"SELECT ID as term_id, post_title as name
			from {$wpdb->prefix}posts
			LEFT JOIN {$table} ON( {$wpdb->prefix}posts.ID = {$table}.post_id )
			where post_type = %s and {$table}.folder_id = %d
			order by folder_id",
			$post_type,
			$parent
		);

		$folders = $wpdb->get_results( $query, ARRAY_A );

		foreach ( $folders as $key => $folder ) {
			$folders[ $key ]['children'] = $this->get_wmlf_folders( $folder['term_id'], $post_type, false );
		}

		return $folders;
	}

	public function get_term_folders( $parent = 0, $taxonomy = '' ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT term_taxonomy.term_id, terms.name, term_taxonomy.term_taxonomy_id FROM $wpdb->term_taxonomy as `term_taxonomy`
			JOIN $wpdb->terms as `terms`
			ON term_taxonomy.term_taxonomy_id = terms.term_id
			WHERE taxonomy = %s and parent = %d",
			$taxonomy,
			$parent
		);

		$folders = $wpdb->get_results( $query, ARRAY_A );

		foreach ( $folders as $key => $folder ) {
			$folders[ $key ]['children'] = $this->get_term_folders( $folder['term_id'], $taxonomy );
		}

		return $folders;
	}

	public function get_wmlf_attachments( $folders, $post_type ) {
		global $wpdb;

		$query = "SELECT {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts
		LEFT JOIN {$wpdb->prefix}postmeta as pm ON pm.post_id = {$wpdb->prefix}posts.ID
		LEFT JOIN {$wpdb->prefix}mgmlp_folders ON( {$wpdb->prefix}posts.ID = {$wpdb->prefix}mgmlp_folders.post_id )
		WHERE post_type   = 'attachment'
		and pm.meta_key = '_wp_attached_file'
		and folder_id     = %d";

		foreach ( $folders as $folder ) {
			$attachments[ $folder['term_id'] ] = $wpdb->get_col( $wpdb->prepare( $query, $folder['term_id'] ) );
			if ( count( $folder['children'] ) > 0 ) {
				$attachments = $attachments + $this->get_wmlf_attachments( $folder['children'], $post_type );
			}
		}

		return $attachments;
	}

	public function get_term_attachments( $folders, $taxonomy ) {
		global $wpdb;
		$attachments = array();

		$query = "SELECT term_relationships.object_id
			FROM $wpdb->term_relationships as `term_relationships`
			JOIN $wpdb->term_taxonomy as `term_taxonomy`
			ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id
			WHERE taxonomy                           = %s and term_id = %d";

		foreach ( $folders as $folder ) {
			$attachments[ $folder['term_id'] ] = $wpdb->get_col( $wpdb->prepare( $query, $taxonomy, $folder['term_id'] ) );

			if ( count( $folder['children'] ) > 0 ) {
				$attachments = $attachments + $this->get_term_attachments( $folder['children'], $taxonomy );
			}
		}

		return $attachments;
	}

	public function get_fb_attachments( $folders ) {
		global $wpdb;
		$attachments = array();

		$query = "SELECT attachment_id FROM {$wpdb->prefix}fbv_attachment_folder WHERE folder_id = %d";

		foreach ( $folders as $folder ) {
			$attachments[ $folder['term_id'] ] = $wpdb->get_col( $wpdb->prepare( $query, $folder['term_id'] ) );

			if ( count( $folder['children'] ) > 0 ) {
				$attachments = $attachments + $this->get_fb_attachments( $folder['children'] );
			}
		}

		return $attachments;
	}

	public function get_rml_attachments( $folders, $check_table_exist = true ) {
		global $wpdb;

		$table = $wpdb->prefix . 'realmedialibrary_posts';

		$attachments = array();

		if ( $check_table_exist && ! $this->table_exist( $table ) ) {
			return $attachments;
		}
		$query = "SELECT attachment FROM {$table} WHERE fid = %d";

		foreach ( $folders as $folder ) {
			$attachments[ $folder['term_id'] ] = $wpdb->get_col( $wpdb->prepare( $query, $folder['term_id'] ) );

			if ( count( $folder['children'] ) > 0 ) {
				$attachments = $attachments + $this->get_rml_attachments( $folder['children'], false );
			}
		}

		return $attachments;
	}

	public function get_attachment_in_folder( \WP_REST_Request $request ) {
		$prefix = Helpers::sanitize_array( $request->get_param( 'prefix' ) );
		if ( isset( $this->plugins_to_import[ $prefix ] ) ) {
			$folders = get_option( $this->get_prefix_option( $prefix, 'folders_import' ), array() );
			if ( in_array( $prefix, array( 'EML', 'HP', 'Folders', 'WPMF', 'MLA', 'WF' ), true ) ) {
				$attachments_in_folder = $this->get_term_attachments( $folders, $this->plugins_to_import[ $prefix ]['taxonomy'] );
			}

			if ( 'WMLF' === $prefix ) {
				$attachments_in_folder = $this->get_wmlf_attachments( $folders, $this->plugins_to_import[ $prefix ]['post_type'] );
			}

			if ( 'RML' === $prefix ) {
				$attachments_in_folder = $this->get_rml_attachments( $folders );
			}

			if ( 'FB' === $prefix ) {
				$attachments_in_folder = $this->get_fb_attachments( $folders );
			}

			update_option( $this->get_prefix_option( $prefix, 'attachments_import' ), $attachments_in_folder, 'no' );
			return new \WP_REST_Response( array( 'result' => true ) );
		}
		return new \WP_Error( '500', __( 'Plugin does not support!', 'catfolders' ) );
	}

	public function create_recursive_tree( $folders = array(), $attachments = array(), $parent = 0 ) {
		$folders_created = array();

		foreach ( $folders as $folder ) {
			$new_folder = FolderModel::createFolder(
				array(
					'title'  => $folder['name'],
					'parent' => $parent,
					'type'   => 'attachment',
				)
			);
			array_push( $folders_created, $folder['term_id'] );

			if ( isset( $attachments[ $folder['term_id'] ] )
			&& count( $attachments[ $folder['term_id'] ] ) > 0
			&& false !== $new_folder ) {
				FolderModel::set_attachments( $new_folder['id'], $attachments[ $folder['term_id'] ], false );
			}

			if ( count( $folder['children'] ) > 0 ) {
				$new_child_folders = $this->create_recursive_tree( $folder['children'], $attachments, $new_folder['id'] );
				$folders_created   = array_merge( $folders_created, $new_child_folders );
			}
		}

		return $folders_created;
	}

	public function process( \WP_REST_Request $request ) {
		$plugin_prefix = Helpers::sanitize_array( $request->get_param( 'prefix' ) );

		$folders     = get_option( '_catf_' . $plugin_prefix . '_folders_import' );
		$attachments = get_option( '_catf_' . $plugin_prefix . '_attachments_import' );

		$folders_created = $this->create_recursive_tree( $folders, $attachments );
		update_option( $this->get_prefix_option( $plugin_prefix, 'folder_created' ), $folders_created, 'no' );
		update_option( $this->get_prefix_option( $plugin_prefix, 'success_import' ), 1 );

		return new \WP_REST_Response( array( 'result' => true ) );
	}

	public function clean_db() {
		global $wpdb;
		// Delete all import folders
		foreach ( $this->plugins_to_import as $key => $value ) {
			delete_option( $this->get_prefix_option( $key, 'success_import' ) );
			delete_option( $this->get_prefix_option( $key, 'folders_import' ) );
			delete_option( $this->get_prefix_option( $key, 'folder_created' ) );
			delete_option( $this->get_prefix_option( $key, 'attachments_import' ) );
		}

		//Clear Cat folder && Cat Relationships Post

		$cat_folder            = $wpdb->prefix . FolderModel::TABLE;
		$cat_attachment_folder = $wpdb->prefix . FolderModel::TABLE_RELATIONSHIP;
		$wpdb->query( "DELETE FROM {$cat_folder}" );
		$wpdb->query( "DELETE FROM {$cat_attachment_folder}" );

		return new \WP_REST_Response( array( 'result' => true ) );
	}

	public function restore_folders( $data ) {
		global $wpdb;
		$cat_folder            = $wpdb->prefix . FolderModel::TABLE;
		$cat_attachment_folder = $wpdb->prefix . FolderModel::TABLE_RELATIONSHIP;
		$wpdb->query( "DELETE FROM {$cat_folder}" );
		$wpdb->query( "DELETE FROM {$cat_attachment_folder}" );

		foreach ( $data as $key => $folder ) {
			$new_folder = FolderModel::createFolder(
				array(
					'title'      => $folder['title'],
					'parent'     => $folder['parent'],
					'type'       => $folder['type'],
					'ord'        => $folder['ord'],
					'created_by' => $folder['created_by'],
					'id'         => $folder['id'],
				)
			);
			if ( '' !== $folder['attachments'] && false !== $new_folder ) {
				FolderModel::set_attachments( $new_folder['id'], explode( ',', $folder['attachments'] ), false );
			}
		}

	}
	private function table_exist( $table ) {
		global $wpdb;
		return $wpdb->get_var( "show tables like '$table'" ) == $table;
	}
}
