<?php
/**
 * UpStream_Model_Manager
 *
 * WordPress Coding Standart (WCS) note:
 * All camelCase methods and object properties on this file are not converted to snake_case,
 * because it being used (heavily) on another add-on plugins.
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UpStream_Model_Manager
 */
class UpStream_Model_Manager {

	/**
	 * Instance
	 *
	 * @var undefined
	 */
	protected static $instance;

	/**
	 * Objects
	 *
	 * @var array
	 */
	protected $objects = array();

	/**
	 * Reset
	 *
	 * @return void
	 */
	public function reset() {
		$this->objects = array();
	}

	/**
	 * GetByID
	 *
	 * @param  mixed $object_type object_type.
	 * @param  mixed $object_id object_id.
	 * @param  mixed $parent_type parent_type.
	 * @param  mixed $parent_id parent_id.
	 * @throws \UpStream_Model_ArgumentException Exception.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public function getByID( $object_type, $object_id, $parent_type = null, $parent_id = 0 ) { // phpcs:ignore
		if ( ! in_array( $object_type, array( UPSTREAM_ITEM_TYPE_CLIENT, UPSTREAM_ITEM_TYPE_PROJECT, UPSTREAM_ITEM_TYPE_MILESTONE, UPSTREAM_ITEM_TYPE_TASK, UPSTREAM_ITEM_TYPE_BUG, UPSTREAM_ITEM_TYPE_FILE ), true ) ) {
			throw new \UpStream_Model_ArgumentException(
				sprintf(
					// translators: %s: type name.
					__( 'Item type %s is not valid.', 'upstream' ),
					$object_type
				)
			);
		}

		if ( empty( $this->objects[ $object_type ] ) || empty( $this->objects[ $object_type ][ $object_id ] ) ) {
			$this->loadObject( $object_type, $object_id, $parent_type, $parent_id );
		}

		if ( empty( $this->objects[ $object_type ][ $object_id ] ) ) {
			throw new UpStream_Model_ArgumentException(
				sprintf(
					// translators: %1$s: ID.
					// translators: %2$s: TYPE.
					// translators: %3$s: PARENT.
					// translators: %4$s: PARENT TYPE.
					__( 'This (ID = %1$s, TYPE = %2$s, PARENT ID = %3$s, PARENT TYPE = %4$s) is not a valid object', 'upstream' ),
					$object_id,
					$object_type,
					$parent_id,
					$parent_type
				)
			);
		}

		return $this->objects[ $object_type ][ $object_id ];
	}

	/**
	 * LoadObject
	 *
	 * @param  mixed $object_type object_type.
	 * @param  mixed $object_id object_id.
	 * @param  mixed $parent_type parent_type.
	 * @param  mixed $parent_id parent_id.
	 * @return void
	 */
	protected function loadObject( $object_type, $object_id, $parent_type, $parent_id ) { // phpcs:ignore
		// TODO: add exceptions.
		if ( UPSTREAM_ITEM_TYPE_PROJECT === $object_type ) {

			$project                                     = new UpStream_Model_Project( $object_id );
			$this->objects[ $object_type ][ $object_id ] = $project;

			foreach ( $project->tasks() as $item ) {
				$this->objects[ UPSTREAM_ITEM_TYPE_TASK ][ $item->id ] = $item;
			}

			foreach ( $project->bugs() as $item ) {
				$this->objects[ UPSTREAM_ITEM_TYPE_BUG ][ $item->id ] = $item;
			}

			foreach ( $project->files() as $item ) {
				$this->objects[ UPSTREAM_ITEM_TYPE_FILE ][ $item->id ] = $item;
			}
		} elseif ( UPSTREAM_ITEM_TYPE_MILESTONE === $object_type ) {
			$this->objects[ $object_type ][ $object_id ] = new UpStream_Model_Milestone( $object_id );
		} elseif ( UPSTREAM_ITEM_TYPE_CLIENT === $object_type ) {
			$this->objects[ $object_type ][ $object_id ] = new UpStream_Model_Client( $object_id );
		} elseif ( UPSTREAM_ITEM_TYPE_TASK === $object_type ) {
			$this->loadObject( $parent_type, $parent_id, null, null );
		} elseif ( UPSTREAM_ITEM_TYPE_BUG === $object_type ) {
			$this->loadObject( $parent_type, $parent_id, null, null );
		} elseif ( UPSTREAM_ITEM_TYPE_FILE === $object_type ) {
			$this->loadObject( $parent_type, $parent_id, null, null );
		}
	}

	/**
	 * LoadAll
	 *
	 * @return void
	 */
	public function loadAll() { // phpcs:ignore
		$posts = get_posts(
			array(
				'post_type'   => 'project',
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		foreach ( $posts as $post ) {
			$this->getByID( UPSTREAM_ITEM_TYPE_PROJECT, $post->ID );
		}

		$posts = get_posts(
			array(
				'post_type'   => 'upst_milestone',
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		foreach ( $posts as $post ) {
			$this->getByID( UPSTREAM_ITEM_TYPE_MILESTONE, $post->ID );
		}

		$posts = get_posts(
			array(
				'post_type'   => 'client',
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		foreach ( $posts as $post ) {
			$this->getByID( UPSTREAM_ITEM_TYPE_CLIENT, $post->ID );
		}
	}

	/**
	 * FindAllByCallback
	 *
	 * @param  mixed $callback callback.
	 */
	public function findAllByCallback( $callback ) { // phpcs:ignore
		$return_items = array();

		if ( count( $this->objects ) === 0 ) {
			$this->loadAll();
		}

		foreach ( $this->objects as $type => $items ) {
			foreach ( $items as $id => $item ) {
				$r = $callback( $item );
				if ( $r ) {
					$return_items[] = $item;
				}
			}
		}

		return $return_items;
	}

	/**
	 * FindAccessibleProjects
	 */
	public function findAccessibleProjects() { // phpcs:ignore
		$return_items = array();

		if ( count( $this->objects ) === 0 ) {
			$this->loadAll();
		}

		foreach ( $this->objects[ UPSTREAM_ITEM_TYPE_PROJECT ] as $id => $item ) {
			$access = upstream_can_access_object(
				'view_project',
				UPSTREAM_ITEM_TYPE_PROJECT,
				$id,
				null,
				0,
				UPSTREAM_PERMISSIONS_ACTION_VIEW
			);
			if ( $access ) {
				$return_items[] = $item;
			}
		}

		usort(
			$return_items,
			function( $item1, $item2 ) {
				return strcasecmp( $item1->title, $item2->title );
			}
		);

		return $return_items;

	}

	/**
	 * CreateObject
	 *
	 * @param  mixed $object_type object_type.
	 * @param  mixed $title title.
	 * @param  mixed $created_by created_by.
	 * @param  mixed $parent_id parent_id.
	 * @throws \UpStream_Model_ArgumentException Exception.
	 */
	public function createObject( $object_type, $title, $created_by, $parent_id = 0 ) { // phpcs:ignore
		switch ( $object_type ) {

			case UPSTREAM_ITEM_TYPE_PROJECT:
				return \UpStream_Model_Project::create( $title, $created_by );

			case UPSTREAM_ITEM_TYPE_MILESTONE:
				return \UpStream_Model_Milestone::create( $title, $created_by, $parent_id );

			case UPSTREAM_ITEM_TYPE_CLIENT:
				return \UpStream_Model_Client::create( $title, $created_by );

			case UPSTREAM_ITEM_TYPE_TASK:
				$parent = $this->getByID( UPSTREAM_ITEM_TYPE_PROJECT, $parent_id );
				return \UpStream_Model_Task::create( $parent, $title, $created_by );

			case UPSTREAM_ITEM_TYPE_FILE:
				$parent = $this->getByID( UPSTREAM_ITEM_TYPE_PROJECT, $parent_id );
				return \UpStream_Model_File::create( $parent, $title, $created_by );

			case UPSTREAM_ITEM_TYPE_BUG:
				$parent = $this->getByID( UPSTREAM_ITEM_TYPE_PROJECT, $parent_id );
				return \UpStream_Model_Bug::create( $parent, $title, $created_by );

			default:
				throw new \UpStream_Model_ArgumentException(
					sprintf(
						// translators: %s: type name.
						__( 'Item type %s is not valid.', 'upstream' ),
						$object_type
					)
				);
		}
	}

	/**
	 * DeleteObject
	 *
	 * @param  mixed $object_type object_type.
	 * @param  mixed $object_id object_id.
	 * @param  mixed $parent_type parent_type.
	 * @param  mixed $parent_id parent_id.
	 * @throws \UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	public function deleteObject( $object_type, $object_id, $parent_type, $parent_id ) { // phpcs:ignore
		// throws exception if the object doesn't exist...
		$obj = $this->getByID( $object_type, $object_id, $parent_type, $parent_id );

		switch ( $object_type ) {

			case UPSTREAM_ITEM_TYPE_PROJECT:
				wp_delete_post( $object_id );
				break;

			case UPSTREAM_ITEM_TYPE_MILESTONE:
				if ( class_exists( '\UpStream\Factory' ) ) {
					$milestone = \UpStream\Factory::get_milestone( $object_id );

					if ( ! empty( $milestone ) ) {
						$milestone->delete();
					}
				}
				break;

			case UPSTREAM_ITEM_TYPE_CLIENT:
			case UPSTREAM_ITEM_TYPE_TASK:
			case UPSTREAM_ITEM_TYPE_FILE:
			case UPSTREAM_ITEM_TYPE_BUG:
			default:
				throw new \UpStream_Model_ArgumentException(
					sprintf(
						// translators: %s: type name.
						__( 'Item type %s is not valid.', 'upstream' ),
						$object_type
					)
				);
		}

		unset( $this->objects[ $object_type ][ $object_id ] );

	}

	/**
	 * Get_instance
	 */
	public static function get_instance() {
		if ( empty( static::$instance ) ) {
			$instance         = new self();
			static::$instance = $instance;
		}

		return static::$instance;
	}

}
