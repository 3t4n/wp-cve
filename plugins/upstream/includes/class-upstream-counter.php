<?php
/**
 * Class Upstream_Counter
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Upstream_Counter
 */
class Upstream_Counter {

	/**
	 * Current user data.
	 *
	 * @var array
	 */
	protected $current_user_data;

	/**
	 * Projects.
	 *
	 * @var array
	 */
	protected $projects = array();

	/**
	 * Project IDs.
	 *
	 * @var array
	 */
	protected $project_ids = array();

	/**
	 * Items.
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Upstream_Counter constructor.
	 *
	 * @param int|array $project_ids Project IDs.
	 */
	public function __construct( $project_ids = null ) {
		$this->project_ids = $project_ids;
	}

	/**
	 * Return the total of items from specific type.
	 *
	 * @param string $item_type Item type.
	 *
	 * @return int
	 */
	public function get_total_items_of_type( $item_type ) {
		$items = $this->get_items_of_type( $item_type );

		return count( $items );
	}

	/**
	 * Retrieve items from the project.
	 *
	 * @param string $item_type Item type.
	 *
	 * @return array|mixed
	 */
	public function get_items_of_type( $item_type ) {
		$projects = $this->get_projects();

		if ( empty( $projects ) ) {
			return array();
		}

		if ( ! isset( $this->items[ $item_type ] ) ) {
			$items = array();

			foreach ( $projects as $project ) {
				// Check if the item type is disabled for this project.
				$disabled = get_post_meta( $project->ID, '_upstream_project_disable_' . $item_type, true ) === 'on';
				if ( $disabled ) {
					continue;
				}

				// If milestones, don't use the metadata, but the milestone classes instead.
				if ( 'milestones' === $item_type ) {
					$milestones_util = \UpStream\Milestones::getInstance();
					$data_set        = $milestones_util->get_milestones_from_project( $project->ID, true );
				} else {
					$data_set = get_post_meta( $project->ID, '_upstream_project_' . $item_type, true );
				}

				// RSD: added if statement to fix count bug 873, which appears due to merge.
				if ( $data_set && count( $data_set ) > 0 ) {
					$items = array_merge( (array) $items, (array) $data_set );
				}
			}

			$this->items[ $item_type ] = $items;
		}

		return $this->items[ $item_type ];
	}

	/**
	 * Retrieve all tasks from projects.
	 *
	 * @return array
	 */
	public function get_projects() {
		return $this->get_projects_cached();
	}

	/**
	 * Retrieve all tasks from cached projects.
	 *
	 * @return array
	 */
	public function get_projects_cached() {
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

		$this->projects = array();
		if ( ! empty( $this->project_ids ) ) {
			if ( is_array( $this->project_ids ) ) {
				foreach ( $this->project_ids as $id ) {
					$this->projects[] = $allprojects[ $id ];
				}
			} else {
				$this->projects[] = $allprojects[ $this->project_ids ];
			}
		}

		return $this->projects;
	}

	/**
	 * Returns the total count of open items
	 *
	 * @param string $item_type Item type.
	 *
	 * @return null|int
	 */
	public function get_total_open_items_of_type( $item_type ) {
		$total = 0;
		$items = $this->get_items_of_type( $item_type );

		if ( count( $items ) > 0 ) {
			// Milestones doesn't have state so they are always open.
			if ( 'milestones' === $item_type ) {
				return count( $items );
			}

			$options  = (array) get_option( "upstream_{$item_type}" );
			$statuses = isset( $options['statuses'] ) ? $options['statuses'] : '';

			if ( empty( $statuses ) ) {
				return 0;
			}

			// TODOPERM: is this right.
			$statuses = wp_list_pluck( $statuses, 'type', 'id' );

			foreach ( $items as $item ) {
				if ( ! isset( $item['status'] ) ) {
					continue;
				}

				$item_status = $item['status'];
				if ( isset( $statuses[ $item_status ] ) && 'open' === $statuses[ $item_status ] ) {
					$total++;
				}
			}
		}

		return $total;
	}

	/**
	 * Get the count of items assigned to the current user.
	 *
	 * @param string $item_type The item type to be searched. I.e.: tasks, bugs, etc.
	 *
	 * @return  integer
	 */
	public function get_total_assigned_to_current_user_of_type( $item_type ) {
		$rowset = $this->get_items_of_type( $item_type );
		if ( count( $rowset ) === 0 ) {
			return 0;
		}

		$user_data            = $this->get_current_user_data();
		$current_user_id      = (int) $user_data['id'];
		$assigned_items_count = 0;

		foreach ( $rowset as $row ) {
			if ( ! isset( $row['assigned_to'] ) ) {
				continue;
			}

			$assignees = array_unique( array_filter( array_map( 'intval', (array) $row['assigned_to'] ) ) );

			if ( in_array( $current_user_id, $assignees ) ) {
				$assigned_items_count++;
			}
		}

		return $assigned_items_count;
	}

	/**
	 * Get current user data.
	 *
	 * @return array|void|null
	 */
	protected function get_current_user_data() {
		if ( empty( $this->current_user_data ) ) {
			$this->current_user_data = upstream_user_data();
		}

		return $this->current_user_data;
	}

	/**
	 * Returns the count of OPEN tasks for the current user.
	 *
	 * @param string $item_type Item type.
	 */
	public function get_total_open_items_of_type_for_current_user( $item_type ) {
		$items = $this->get_items_of_type( $item_type );

		if ( empty( $items ) ) {
			return 0;
		}

		// Milestones doesn't have state so they are always opened.
		if ( 'milestones' === $item_type ) {
			return count( $items );
		}

		$option   = get_option( 'upstream_' . $item_type );
		$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';

		if ( empty( $statuses ) ) {
			return 0;
		}

		$item_types = wp_list_pluck( $statuses, 'type', 'id' );
		$user_data  = $this->get_current_user_data();

		$count = 0;
		foreach ( $items as $key => $item ) {
			$item = (array) $item;

			if ( ! isset( $item['assigned_to'] ) ) {
				continue;
			}

			if (
				( is_array( $item['assigned_to'] ) && ! in_array( $user_data['id'], $item['assigned_to'] ) )
				|| ( is_numeric( $item['assigned_to'] && $item['assigned_to'] !== $user_data['id'] ) )
			) {
				continue;
			}

			$item_status = isset( $item['status'] ) ? $item['status'] : '';

			if ( ( isset( $item_types[ $item_status ] ) && 'open' === $item_types[ $item_status ] ) || '' === $item_status ) {
				$count++;
			}
		}

		return $count;
	}
}


