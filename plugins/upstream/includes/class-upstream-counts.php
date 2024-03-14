<?php
/**
 * Class Upstream_Counts.
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Upstream_Counts
 *
 * @deprecated User Upstream_Counter instead
 */
class Upstream_Counts {

	/**
	 * Project data
	 *
	 * @var array
	 */
	public $projects = null;

	/**
	 * User data
	 *
	 * @var array
	 */
	public $user = null;


	/**
	 * Class constructor
	 *
	 * @param int $id Projects id.
	 */
	public function __construct( $id ) {
		$this->projects = (array) $this->get_projects( $id );
		$this->user     = upstream_user_data();
	}

	/**
	 * Retrieve all tasks from projects.
	 *
	 * @param int $id Projects id.
	 */
	public function get_projects( $id ) {
		return $this->get_projects_cached( $id );
	}

	/**
	 * Retrieve all tasks from cached projects.
	 *
	 * @param int $id Projects id.
	 */
	public function get_projects_cached( $id ) {
		$allprojects = Upstream_Cache::get_instance()->get( 'allprojectsbyid' );
		if ( false === $allprojects ) {
			$args        = array(
				'post_type'      => 'project',
				'post_status'    => 'any',
				'posts_per_page' => -1,
			);
			$projects    = (array) get_posts( $args );
			$allprojects = array();

			foreach ( $projects as $p ) {
				$allprojects[ $p->ID ] = $p;
			}

			Upstream_Cache::get_instance()->set( 'allprojectsbyid', $allprojects );
		}

		$rv = array();

		if ( 0 === $id ) {
			return $allprojects;
		} elseif ( isset( $allprojects[ $id ] ) ) {
			$rv[] = $allprojects[ $id ];
		}

		return $rv;
	}

	/**
	 * Returns the total count of open items
	 *
	 * @param string $type The item type to be searched. I.e.: tasks, bugs, etc.
	 */
	public function total_open( $type ) {
		$items_open_count = 0;

		$items = $this->get_items( $type );
		if ( count( $items ) > 0 ) {
			$option   = (array) get_option( "upstream_{$type}" );
			$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';

			if ( ! empty( $statuses ) ) {
				if ( 'milestones' === $type ) {
					return $this->total( $type );
				}

				return null;
			}

			$types = wp_list_pluck( $statuses, 'type', 'name' );

			foreach ( $items as $item ) {
				if ( ! isset( $item['status'] ) ) {
					continue;
				}
			}
		}

		return $items_open_count;
	}

	/**
	 * Retrieve all items from projects.
	 *
	 * @param string $type The item type to be searched. I.e.: tasks, bugs, etc.
	 */
	public function get_items( $type ) {
		$items = array();

		if ( count( $this->projects ) > 0 ) {
			foreach ( $this->projects as $i => $project ) {
				// Check if the items are disabled.
				$meta = get_post_meta( $project->ID, '_upstream_project_disable_' . $type, true );
				if ( 'on' === $meta ) {
					continue;
				}

				// If milestones, don't use the metadata, but the milestone classes instead.
				if ( 'milestones' === $type ) {
					$milestones_util = \UpStream\Milestones::getInstance();
					$data_set        = $milestones_util->get_milestones_from_project( $project->ID, true );
				} else {
					$data_set = get_post_meta( $project->ID, '_upstream_project_' . $type, true );
				}

				if ( ! empty( $data_set ) && is_array( $data_set ) ) {
					foreach ( $data_set as $value ) {
						$items[] = $value;
					}
				}
			};
		}

		return $items;
	}

	/**
	 * Get the count of items.
	 *
	 * @param string $type The item type to be searched. I.e.: tasks, bugs, etc.
	 */
	public function total( $type ) {
		$items       = (array) $this->get_items( $type );
		$items_count = count( $items );

		return $items_count;
	}

	/**
	 * Get the count of items assigned to the current user.
	 *
	 * @param string $item_type The item type to be searched. I.e.: tasks, bugs, etc.
	 *
	 * @return  integer
	 * @since   1.0.0
	 */
	public function assigned_to( $item_type ) {
		$rowset = $this->get_items( $item_type );
		if ( count( $rowset ) === 0 ) {
			return 0;
		}

		$current_user_id      = (int) $this->user['id'];
		$assigned_items_count = 0;

		foreach ( $rowset as $row ) {
			$assignees = isset( $row['assigned_to'] ) ? array_unique(
				array_filter(
					array_map(
						'intval',
						(array) $row['assigned_to']
					)
				)
			) : array();
			if ( in_array( $current_user_id, $assignees ) ) {
				$assigned_items_count++;
			}
		}

		return $assigned_items_count;
	}

	/**
	 * Returns the count of OPEN tasks for the current user
	 *
	 * @param string $type The item type to be searched. I.e.: tasks, bugs, etc.
	 */
	public function assigned_to_open( $type ) {
		$items = $this->get_items( $type );
		if ( ! $items ) {
			return '0';
		}

		$option   = get_option( 'upstream_' . $type );
		$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';

		if ( ! $statuses ) {
			if ( 'milestones' === $type ) {
				return $this->total( $type );
			} else {
				return null;
			}
		}

		// TODOPERM: should this be ID or name.
		$types = array();
		foreach ( $statuses as $s ) {
			if ( isset( $s['id'] ) && isset( $s['type'] ) ) {
				$types[ $s['id'] ] = $s['type'];
			}
		}

		$count = 0;
		foreach ( $items as $key => $item ) {
			$item = (array) $item;
			if ( ! isset( $item['assigned_to'] ) ) {
				continue;
			}

			if ( ! is_array( $item['assigned_to'] ) ) {
				$item['assigned_to'] = array( (int) $item['assigned_to'] );
			}

			if ( ! in_array( $this->user['id'], $item['assigned_to'] ) ) {
				continue;
			}

			$item_status = isset( $item['status'] ) ? $item['status'] : '';

			if ( ( isset( $types[ $item_status ] ) && 'open' === $types[ $item_status ] ) || '' === $item_status ) {
				$count++;
			}
		}

		return $count;
	}
}

