<?php
namespace CatFolders\Models;

use TenQuality\WP\Database\Abstracts\DataModel;
use TenQuality\WP\Database\Traits\DataModelTrait;
use TenQuality\WP\Database\QueryBuilder;

use CatFolders\Core\Base;

defined( 'ABSPATH' ) || exit;

class FolderModel extends DataModel {
	use DataModelTrait;

	const TABLE              = Base::CAT_FOLDERS_TABLE;
	const TABLE_RELATIONSHIP = Base::CAT_FOLDERS_TABLE_POSTS;

	protected $table       = self::TABLE;
	protected $primary_key = 'id';

	protected $attributes = array(
		'title',
		'parent',
		'type',
		'ord',
		'created_by',
	);

	protected function protected_properties() {
		return array( 'created_at', 'updated_at' );
	}

	public static function createFolder( $attributes = array() ) {
		$defaults = array(
			'id'         => null,
			'title'      => '',
			'parent'     => 0,
			'type'       => 'attachment',
			'ord'        => null,
			'created_by' => null,
		);

		$attributes = wp_parse_args( $attributes, $defaults );
		$ordMax     = self::builder()
		->select( 'IFNULL((MAX(ord) + 1),0)' )
		->where(
			array(
				'parent'     => $attributes['parent'],
				'created_by' => apply_filters( 'catf_folder_created_by', 0 ),
			)
		)
		->value();

		$insertArr = array(
			'title'      => $attributes['title'],
			'parent'     => $attributes['parent'],
			'type'       => $attributes['type'],
			'ord'        => is_null( $attributes['ord'] ) ? $ordMax : $attributes['ord'],
			'created_by' => is_null( $attributes['created_by'] ) ? apply_filters( 'catf_folder_created_by', 0 ) : $attributes['created_by'],
		);

		if ( ! is_null( $attributes['id'] ) ) {
			$insertArr['id'] = $attributes['id'];
		}

		$insert = self::insert( $insertArr );

		return array(
			'title'       => $attributes['title'],
			'id'          => strval( $insert->id ),
			'key'         => strval( $insert->id ),
			'value'       => strval( $insert->id ),
			'type'        => $attributes['type'],
			'parent'      => $attributes['parent'],
			'children'    => array(),
			'data-count'  => 0,
			'data-parent' => $attributes['parent'],
			'data-id'     => strval( $insert->id ),
		);
	}

	public static function updateToMaxOrd( $folder_id, $parent ) {
		$ordMax = self::builder()
		->select( 'MAX(ord) + 1' )
		->where(
			array(
				'parent'     => $parent,
				'created_by' => apply_filters( 'catf_folder_created_by', 0 ),
			)
		)
		->value();

		self::builder()
		->set( array( 'ord' => $ordMax ) )
		->where( array( 'id' => $folder_id ) )
		->update();
		// $wpdb->query(
		// 	$wpdb->prepare( "UPDATE {$wpdb->prefix}catf_folder SET ord = (SELECT MAX(ord) + 1 FROM {$wpdb->prefix}catf_folder where parent = %d) where id = %d", $parentId, $nodeId )
		// );
	}

	public static function create_or_get( $name, $parent ) {
		$check = self::detail( $name, $parent );
		if ( is_null( $check ) ) {
			$new = self::createFolder(
				array(
					'title'  => $name,
					'parent' => $parent,
					'ord'    => 0,
				)
			);
			return $new['id'];
		} else {
			return (int) $check->id;
		}
	}

	public static function updateFolder( $id, $attrs ) {
		return self::builder()
		->set( $attrs )
		->where( array( 'id' => $id ) )
		->update();
	}

	public static function updatePositionAndParent( $folder_id, $new_position, $new_parent ) {
		$old_data = self::builder()
		->select( 'parent, ord' )
		->where(
			array(
				'id' => $folder_id,
			)
		)
		->first();
		if ( isset( $old_data->parent ) && isset( $old_data->ord ) ) {
			$update_data = array();
			if ( $old_data->ord != $new_position ) {
				$update_data['ord'] = $new_position;
			}
			if ( $old_data->parent != $new_parent ) {
				$update_data['parent'] = $new_parent;
			}

			if ( count( $update_data ) > 0 ) {
				// Update that folder first
				self::builder()
				->set( $update_data )
				->where( array( 'id' => $folder_id ) )
				->update();
			}

			if ( $old_data->ord != $new_position ) {
				// Then update ord of folders related to that folder
				$raw_update = '';
				$where_ord  = array();
				if ( $new_position < $old_data->ord ) {
					$raw_update = 'ord = ord + 1';
					$where_ord  = array(
						'operator' => 'BETWEEN',
						'key'      => $new_position,
						'key_b'    => $old_data->ord,
					);
				} elseif ( $new_position > $old_data->ord ) {
					$raw_update = 'ord = ord - 1';
					$where_ord  = array(
						'operator' => 'BETWEEN',
						'key'      => $old_data->ord,
						'key_b'    => $new_position,
					);
				}
				if ( '' != $raw_update ) {
					self::builder()
					->set(
						array(
							'raw' => $raw_update,
						)
					)
					->where(
						array(
							'ord'        => $where_ord,
							'id'         => array(
								'operator' => '<>',
								'value'    => $folder_id,
							),
							'created_by' => apply_filters( 'catf_folder_created_by', 0 ),
						)
					)
					->update();
				}
			}
		}
		return false;
	}

	public static function deleteFolder( $ids ) {
		if ( is_array( $ids ) && count( $ids ) > 0 ) {
			$ids = array_map( 'intval', $ids );

			// Get children
			$all_ids = array_merge( $ids, self::get_children_ids( $ids ) );

			if ( count( $all_ids ) > 0 ) {
				$all_ids = implode( ',', $all_ids );

				//delete folder
				self::builder()
				->from( self::TABLE )
				->where(
					array( 'raw' => 'id IN (' . $all_ids . ')' )
				)
				->delete();

				//delete relationship
				self::builder()
				->from( self::TABLE_RELATIONSHIP )
				->where(
					array( 'raw' => 'folder_id IN (' . $all_ids . ')' )
				)
				->delete();
			}
		}
		return self::get_folders_counter( true );
	}

	public static function get_children_ids( $folder_ids ) {
		if ( is_array( $folder_ids ) ) {
			$folder_ids = implode( ',', $folder_ids );
		}
		$res = array();

		$children = self::builder()
		->select( 'id' )
		->from( self::TABLE )
		->where(
			array( 'raw' => 'parent IN (' . $folder_ids . ')' )
		)
		->col();
		if ( count( $children ) > 0 ) {
			$res = array_merge( $children, self::get_children_ids( $children ) );
		}

		return $res;
	}

	public static function getFolderCount() {
		return self::builder()
			->select( 'COUNT(id)' )
			->from( self::TABLE )
			->where(
				array(
					'created_by' => apply_filters( 'catf_folder_created_by', 0 ),
				)
			)
			->value();
	}

	public static function get_folders_counter( $format = false ) {
		$catf_table       = self::TABLE;
		$catf_posts_table = self::TABLE_RELATIONSHIP;

		$query = self::builder()
		->select( 'folder_id, count(post_id) as counter' )
		->from( "{$catf_posts_table} as `catf_posts`" )
		->join(
			"{$catf_table} as `catf`",
			array(
				array(
					'key_a' => 'catf_posts.folder_id',
					'key_b' => 'catf.id',
				),
				array(
					'key'   => 'catf.created_by',
					'value' => apply_filters( 'catf_folder_created_by', 0 ),
				),
			),
			'INNER'
		)
		->group_by( 'folder_id' );

		$query = apply_filters( 'catf_folder_counter_query', $query );

		$counters = $query->get( OBJECT_K );

		if ( $format ) {
			$countersFormat = array();
			foreach ( $counters as $counter ) {
				$countersFormat[ $counter->folder_id ] = $counter->counter;
			}
			return $countersFormat;
		}

		return $counters;
	}

	public static function get_all_counter() {
		$query = QueryBuilder::create();

		$query = $query->select( 'COUNT(ID)' )
		->from( 'posts as `posts`' )
		->where(
			array(
				'posts.post_type' => 'attachment',
				'raw'             => "(posts.post_status = 'inherit' OR posts.post_status = 'private')",
			)
		);

		$query = apply_filters( 'catf_all_counter_query', $query );

		return $query->value();
	}

	public static function group_parent_folders( $tree, $counters ) {
		$group = array();
		foreach ( $tree as $node ) {
			$node->key                = $node->id;
			$node->{'data-count'}     = isset( $counters[ $node->id ] ) ? $counters[ $node->id ]->counter : 0;
			$node->{'data-parent'}    = $node->parent;
			$node->{'data-id'}        = $node->id;
			$node->value              = $node->id;
			$group[ $node->parent ][] = $node;
		}

		return $group;
	}

	public static function get_all( $order = null, $flat = false ) {
		$results = self::builder()
		->select( '*' )
		->where(
			array(
				'created_by' => apply_filters( 'catf_folder_created_by', 0 ),
			)
		)
		->order_by( 'ord+0' )
		->get();

		if ( is_array( $order ) && isset( $order['orderBy'] ) && isset( $order['orderType'] ) ) {
			if ( 'name' == $order['orderBy'] ) {
				if ( 'asc' == $order['orderType'] ) {
					usort( $results, array( __CLASS__, 'sort_natural_asc' ) );
				}
				if ( 'desc' == $order['orderType'] ) {
					usort( $results, array( __CLASS__, 'sort_natural_desc' ) );
				}
			}
		}

		$tree = array();

		if ( $flat ) {
			foreach ( $results as $result ) {
				$result->key = $result->id;
				$tree[]      = $result;
			}
			return array(
				'tree' => $tree,
			);
		}

		$counters = self::get_folders_counter();
		$group    = self::group_parent_folders( $results, $counters );

		$tree = self::get_nested_tree( $group, 0, $counters );
		$json = array(
			'allCounter' => self::get_all_counter(),
			'tree'       => $tree,
		);

		return $json;
	}
	private static function sort_natural_asc( $a, $b ) {
		return strnatcasecmp( $a->title, $b->title );
	}
	private static function sort_natural_desc( $a, $b ) {
		return strnatcasecmp( $a->title, $b->title ) * -1;
	}
	public static function set_attachments( $folderId, $imgIds, $getCounter = true ) {
		global $wpdb;

		$imgIds = apply_filters( 'catf_attachment_ids_to_folder', $imgIds );

		if ( is_array( $imgIds ) && is_numeric( $folderId ) ) {
			$attachmentIds = implode( ',', $imgIds );
			//get folders of these attachment ids
			$old_folder_ids = self::builder()
			->select( 'folder_id' )
			->from( self::TABLE_RELATIONSHIP )
			->where(
				array(
					'raw' => 'post_id IN (' . $attachmentIds . ')',
				)
			)
			->col();
			if ( count( $old_folder_ids ) > 0 ) {
				$folder_ids_in_this_mode = self::builder()
				->select( 'id' )
				->where(
					array(
						'raw'        => 'id IN (' . implode( ',', $old_folder_ids ) . ')',
						'created_by' => apply_filters( 'catf_folder_created_by', 0 ),
					)
				)
				->col();
				if ( count( $folder_ids_in_this_mode ) > 0 ) {
					self::builder()->from( self::TABLE_RELATIONSHIP )
					->where(
						array(
							'raw' => 'post_id IN (' . $attachmentIds . ') AND folder_id IN (' . implode( ',', $folder_ids_in_this_mode ) . ')',
						)
					)
					->delete();
				}
			}

			if ( $folderId > 0 ) {
				$prepareInsert = '';
				foreach ( $imgIds as $imgId ) {
					$prepareInsert .= "( $folderId, $imgId ),";
				}
				$prepareInsert = rtrim( $prepareInsert, ',' );

				// For performance reasons, we have to use raw function instead of self::insert
				self::builder()->raw( 'INSERT INTO ' . $wpdb->prefix . self::TABLE_RELATIONSHIP . " ( folder_id, post_id ) VALUES $prepareInsert" );
			}
		}

		if ( $getCounter ) {
			$folderCounters = self::get_folders_counter( true );
			return $folderCounters;
		}
	}

	public static function unset_attachment( $postId ) {

		self::builder()
		->from( self::TABLE_RELATIONSHIP )
		->where(
			array( 'post_id' => (int) $postId )
		)
		->delete();
	}

	private static function get_nested_tree( $group, $parent = 0 ) {
		$tree = array();

		if ( empty( $group ) ) {
			return $tree;
		}

		if ( isset( $group[ $parent ] ) ) {
			foreach ( $group[ $parent ] as $node ) {
				$node->children = isset( $group[ $node->id ] ) ? self::get_nested_tree( $group, $node->id ) : array();
				$tree[]         = $node;
			}
		}

		return $tree;
	}

	public static function getRelationsWithFolderUser( $clauses ) {
		global $wpdb;

		$attachment_in_folder = $wpdb->prepare(
			"SELECT post_id
			FROM {$wpdb->prefix}catfolders_posts AS catf_af
			JOIN {$wpdb->prefix}catfolders AS catf ON catf_af . folder_id = catf . id
			GROUP BY post_id
			HAVING FIND_IN_SET(%d, GROUP_CONCAT( created_by ) )",
			apply_filters( 'catf_folder_created_by', 0 )
		);

		$clauses['where'] .= " AND {$wpdb->posts} . ID NOT IN( $attachment_in_folder ) ";

		return $clauses;
	}

	public static function getAttachmentRelationship() {
		$res = array();

		$relations = self::builder()
			->select( 'post_id' )
			->select( 'GROUP_CONCAT(`folder_id`) as folders' )
			->from( self::TABLE_RELATIONSHIP )
			->group_by( 'folder_id' )
			->get();

		foreach ( $relations as $k => $v ) {
			$res[ $v->post_id ] = array_map( 'intval', explode( ',', $v->folders ) );
		}
		return $res;
	}

	public static function detail( $name, $parent ) {
		return self::builder()
		->select( '*' )
		->where(
			array(
				'title'      => $name,
				'parent'     => $parent,
				'created_by' => apply_filters( 'catf_folder_created_by', 0 ),
			)
		)
		->first();
	}

	public static function isFolderExist( $folderId ) {
		$isExist = self::builder()
		->from( self::TABLE )
		->where(
			array( 'id' => (int) $folderId )
		)
		->value();
		return is_null( $isExist ) ? false : true;
	}

	public static function getFolderFromPostId( $postId ) {
		return self::builder()
		->select( 'folder_id' )
		->from( self::TABLE_RELATIONSHIP )
		->where(
			array( 'post_id' => intval( $postId ) )
		)->group_by( 'folder_id' )->value();
	}

	public static function getPostIdsFromFolder( $folderIds ) {
		$folderIds = \implode( ',', $folderIds );
		$query     = self::builder()
		->select( 'post_id' )
		->from( self::TABLE_RELATIONSHIP )
		->where(
			array( 'raw' => 'folder_id IN (' . $folderIds . ')' )
		);
		return $query->get( ARRAY_A );
	}

	/**
	 * Static function isExistingFolderName
	 *
	 * @param  mixed $folderId if $folderId = 0, it would be created a new folder, else it would be update an exist folder
	 * @param  mixed $title
	 * @param  mixed $parent
	 * @return void
	 */
	public static function isExistingFolderName( $folderId, $title, $parent ) {
		$isExist = self::builder()
			->from( self::TABLE )
			->where(
				array(
					'title'  => $title,
					'parent' => intval( $parent ),
					'id'     => array(
						'operator' => '<>',
						'value'    => $folderId,
					),
				)
			)
			->value();

		return is_null( $isExist ) ? false : true;
	}
}
