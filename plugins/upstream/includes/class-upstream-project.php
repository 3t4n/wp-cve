<?php
/**
 * UpStream_Project Class
 *
 * @package UpSteam
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// This include shouldn't be necessary however the wp_check_post_lock call fails.
// in the frontend edit module which is odd.
@require_once ABSPATH . '/wp-admin/includes/post.php';

/**
 * UpStream_Project Class
 *
 * @since 1.0.0
 */
class UpStream_Project {

	/**
	 * The project ID
	 *
	 * @var int
	 * @since 1.0.0
	 */
	public $ID = 0;

	/**
	 * Meta key prefix
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $meta_prefix = '_upstream_project_';

	/**
	 * Project Meta keys
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $meta = array(
		'milestones',
		'tasks',
		'bugs',
		'status',
		'owner',
		'client',
		'client_users',
		'start',
		'end',
		'files',
		'progress',
		'members',
		'comments',
		'activity',
	);

	/**
	 * Declare the default properties in WP_Post as we can't extend it.
	 * Anything we've declared above has been removed.
	 */

	/**
	 * Post_author
	 *
	 * @var int
	 */
	public $post_author = 0;

	/**
	 * Post_date
	 *
	 * @var string
	 */
	public $post_date = '0000-00-00 00:00:00';

	/**
	 * Variable $post_date_gmt
	 *
	 * @var string
	 */
	public $post_date_gmt = '0000-00-00 00:00:00';

	/**
	 * Variable $post_content
	 *
	 * @var string
	 */
	public $post_content = '';

	/**
	 * Variable $post_title
	 *
	 * @var string
	 */
	public $post_title = '';

	/**
	 * Variable $post_excerpt
	 *
	 * @var string
	 */
	public $post_excerpt = '';

	/**
	 * Variable $post_status
	 *
	 * @var string
	 */
	public $post_status = 'publish';

	/**
	 * Variable $comment_status
	 *
	 * @var string
	 */
	public $comment_status = 'open';

	/**
	 * Variable $ping_status
	 *
	 * @var string
	 */
	public $ping_status = 'open';

	/**
	 * Variable $post_password
	 *
	 * @var string
	 */
	public $post_password = '';

	/**
	 * Variable $post_name
	 *
	 * @var string
	 */
	public $post_name = '';

	/**
	 * Variable $to_ping
	 *
	 * @var string
	 */
	public $to_ping = '';

	/**
	 * Variable $pinged
	 *
	 * @var string
	 */
	public $pinged = '';

	/**
	 * Variable $post_modified
	 *
	 * @var string
	 */
	public $post_modified = '0000-00-00 00:00:00';

	/**
	 * Variable $post_modified_gmt
	 *
	 * @var string
	 */
	public $post_modified_gmt = '0000-00-00 00:00:00';

	/**
	 * Variable $post_content_filtered
	 *
	 * @var string
	 */
	public $post_content_filtered = '';

	/**
	 * Variable $post_parent
	 *
	 * @var string
	 */
	public $post_parent = 0;

	/**
	 * Variable $guid
	 *
	 * @var string
	 */
	public $guid = '';

	/**
	 * Variable $menu_order
	 *
	 * @var string
	 */
	public $menu_order = 0;

	/**
	 * Variable $post_mime_type
	 *
	 * @var string
	 */
	public $post_mime_type = '';

	/**
	 * Variable $comment_count
	 *
	 * @var string
	 */
	public $comment_count = 0;

	/**
	 * Variable $filter
	 *
	 * @var string
	 */
	public $filter;

	/**
	 * Get things going
	 *
	 * @param int   $_id Post id.
	 * @param array $_args Arguments.
	 * @since 1.0.0
	 */
	public function __construct( $_id = 0, $_args = array() ) {
		// if no id is sent, then go through the varous ways of getting the id.
		if ( ! $_id ) {
			$_id = get_the_ID();
		}

		// validity will be checked in setup_project.
		$project = WP_Post::get_instance( absint( $_id ) );

		return $this->setup_project( $project );
	}

	/**
	 * Given the project data, let's set the variables
	 *
	 * @param object $project The Project Object.
	 *
	 * @return bool             If the setup was successful or not
	 * @since  1.0.0
	 */
	public function setup_project( $project ) {
		if ( ! is_object( $project ) ) {
			return false;
		}

		if ( ! is_a( $project, 'WP_Post' ) ) {
			return false;
		}

		if ( 'project' !== $project->post_type ) {
			return false;
		}

		// sets the value of each key.
		foreach ( $project as $key => $value ) {
			switch ( $key ) {
				default:
					$this->$key = $value;
					break;
			}
		}

		$this->init();

		return true;
	}

	/**
	 * Init class
	 */
	public function init() {
		// RSD: commented b/c this doesn't ever get called because init was already executed.
		// add_action('init', [$this, 'hooks']).

		$this->hooks();
	}

	/**
	 * Hooks of the class
	 */
	public function hooks() {
		add_action( 'wp_insert_post', array( $this, 'update_project_meta_admin' ), 1, 3 );
	}

	/**
	 * Get the clients name
	 *
	 * @return string|null
	 * @since 1.0.0
	 */
	public function get_client_name() {
		if ( ! $this->get_meta( 'client' ) ) {
			return;
		}
		$client = get_post( (int) $this->get_meta( 'client' ) );
		if ( $client->ID === $this->ID ) {
			return;
		}

		return $client->post_title;
	}

	/**
	 * Get a meta value
	 *
	 * @param string $meta the meta field (without prefix).
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_meta( $meta ) {
		$result = get_post_meta( $this->ID, $this->meta_prefix . $meta, true );
		if ( ! $result ) {
			$result = null;
		}

		return $result;
	}

	/**
	 * Get an item (milestone, task or bug) by it's id
	 *
	 * @param int    $item_id Item id.
	 * @param string $type Item type.
	 *
	 * @return array|null
	 * @throws Exception Exception.
	 * @since 1.0.0
	 */
	public function get_item_by_id( $item_id, $type ) {
		if ( ! $item_id ) {
			return;
		}

		if ( is_array( $type ) ) {
			$type = reset( $type );
		}

		if ( 'milestones' === $type ) {
			try {
				$milestone         = \UpStream\Factory::get_milestone( $item_id )->convertToLegacyRowset();
				$milestone['type'] = $type;
			} catch ( \UpStream\Exception $e ) {
				$milestone = null;
			}

			return $milestone;
		}

		$data = $this->get_meta( $type );
		if ( ! $data ) {
			return;
		}

		foreach ( $data as $key => $item ) {
			if ( $item_id === $item['id'] ) {
				$item['type'] = $type;

				return $item;
			}
		}
	}

	/**
	 * Get an item (milestone, task or bug) by it's id
	 *
	 * @param int    $item_id Item id.
	 * @param string $type Item type.
	 * @param string $field Option name.
	 */
	public function get_item_colors( $item_id, $type, $field ) {
		if ( ! $item_id || ! $type || ! $field ) {
			return;
		}

		$data = $this->get_meta( $type );
		if ( ! $data ) {
			return;
		}

		$option_name = 'status' === $field ? $field . 'es' : $field;
		$option      = get_option( "upstream_{$type}" );
		$colors      = wp_list_pluck( $option[ $option_name ], 'color', 'name' );

		foreach ( $data as $key => $item ) {
			if ( $item_id === $item['id'] ) {
				if ( isset( $item[ $field ] ) ) {
					$field_name = $item[ $field ];
					if ( isset( $field_name ) && ! empty( $field_name ) ) {
						return $colors[ $field_name ];
					}
				}
			}
		}

		return null;
	}

	/**
	 * Get the current count of statuses for a particular item type
	 *
	 * @param string $type the type of item (milestone, task or bug).
	 *
	 * @return array|null
	 * @since 1.0.0
	 */
	public function get_statuses_counts( $type ) {
		if ( ! $this->get_statuses( $type ) ) {
			return;
		}
		$counts = array_filter( $this->get_statuses( $type ) ); // remove entries with blank statuses.
		$counts = array_count_values( $counts );

		return $counts;
	}

	/**
	 * Get the current statuses used in the project for a particular item type
	 *
	 * @param string $type the type of item (milestone, task or bug).
	 *
	 * @return array|null
	 * @since 1.0.0
	 */
	public function get_statuses( $type ) {
		$found = false;

		$meta = $this->get_meta( $type );

		if ( ! $meta ) {
			return;
		}

		$statuses = array();
		foreach ( $meta as $key => $value ) {
			if ( array_key_exists( 'status', $value ) ) {
				$statuses[] = $value['status'];
			}
		}

		return $statuses;
	}

	/**
	 * Get project status type.
	 */
	public function get_project_status_type() {
		if ( ! $this->get_meta( 'status' ) ) {
			return;
		}
		$result   = null;
		$option   = get_option( 'upstream_projects' );
		$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';

		if ( ! $statuses ) {
			return null;
		}

		$types = array();
		foreach ( $statuses as $s ) {
			if ( isset( $s['name'] ) && isset( $s['type'] ) ) {
				$types[ $s['name'] ] = $s['type'];
			}
		}

		foreach ( $types as $key => $value ) {
			if ( $key === $this->get_meta( 'status' ) ) {
				$result = $value;
			}
		}

		return $result;
	}

	/**
	 * Update a project with various missing meta values (this runs from admin only via wp_insert_post action)
	 *
	 * @param int    $post_id Post id.
	 * @param object $post Post object.
	 * @param bool   $update Update status.
	 * @return null
	 * @since 1.0.0
	 */
	public function update_project_meta_admin( $post_id, $post, $update ) {
		// RSD: performance enhancement test.
		static $has_run_for_post = array();
		if ( in_array( $post_id, $has_run_for_post ) ) {
			return;
		}
		$has_run_for_post[] = $post_id;

		// If this is an auto draft.
		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		// If this is a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$nonce     = isset( $post_data['upstream_admin_project_form_nonce'] ) ? $post_data['upstream_admin_project_form_nonce'] : null;

		// Verify that the nonce is valid.
		// Previously, there is a function to check whether the data is form the "Quick edit" form or not.
		// Since it was not save, then I replaced it with this nonce verification.
		if ( ! wp_verify_nonce( $nonce, 'upstream_admin_project_form' ) ) {
			return;
		}

		$this->update_project_meta();
	}


	/**
	 * Loop through the meta keys and update with missing meta values
	 * This runs from admin and is also called directly if updating via frontend
	 *
	 * @param mixed $frontend Array the posted data from the front end.
	 *
	 * @since 1.0.0
	 */
	public function update_project_meta( $frontend = null ) {
		$meta_keys = array(
			'milestones',
			'tasks',
			'bugs',
			'files',
			'discussion',
		);

		foreach ( $meta_keys as $meta_key ) {
			$this->update_missing_meta( $meta_key, $frontend );
		}

		$this->update_tasks_milestones();

		$this->update_project_members();

		do_action( 'upstream:project.update_project_meta', $this->ID, $frontend );
	}

	/**
	 * Update our missing meta data
	 * Ran on every project update & when items are added/edited
	 *
	 * @param string $meta_key Post meta key.
	 * @param mixed  $frontend Array the posted data from the front end.
	 *
	 * @since 1.0.0
	 */
	public function update_missing_meta( $meta_key, $frontend = null ) {
		// if no posted_data from frontend, set it as $_POST.
		if ( ! $frontend ) {
			$data = $this->get_meta( $meta_key );
		} else {
			$data = $this->get_meta( $meta_key );
		}

		$meta_key = $this->meta_prefix . $meta_key;

		// if we have data.
		if ( $data ) {
			foreach ( $data as $i => $value ) {
				// add unique id.
				if ( ! isset( $data[ $i ]['id'] ) || empty( $data[ $i ]['id'] ) ) {
					$data[ $i ]['id'] = upstream_admin_set_unique_id();
				}

				// add the user id who created this.
				if ( ! isset( $data[ $i ]['created_by'] ) || empty( $data[ $i ]['created_by'] ) ) {
					$data[ $i ]['created_by'] = upstream_current_user_id();
				}

				// add the created date.
				if ( ! isset( $data[ $i ]['created_time'] )
					|| empty( $data[ $i ]['created_time'] )
				) {
					// Prior to v1.15.1, 'created_time' was stored as a non-gmt timestamp,
					// which doesn't make sense since local time might change.

					// Stores 'created_time' as a UTC/GMT value.
					$data[ $i ]['created_time'] = (int) time();
					// Flag indicating that 'created_time' is in UTC.
					// Useful to convert old 'created_time' data into UTC/GMT in the future.
					$data[ $i ]['created_time__in_utc'] = '1';
				}
			}
		}

		$data = apply_filters( 'upstream:project.on_before_update_missing_meta', $data, $this->ID, $meta_key );

		$updated = update_post_meta( $this->ID, $meta_key, $data );
	}

	/**
	 * Update task on milestone
	 *
	 * @throws \Exception Exception.
	 */
	public function update_tasks_milestones() {
		$tasks      = $this->get_meta( 'tasks' );
		$milestones = \UpStream\Milestones::getInstance()->get_milestones_from_project_no_perms( $this->ID );

		$wp_lock_check = false;
		if ( function_exists( 'wp_check_post_lock' ) ) {
			$wp_lock_check = wp_check_post_lock( $this->ID );
		}

		$options        = get_option( 'upstream_general' );
		$allow_override = isset( $options['override_locking'] ) ? (bool) $options['override_locking'] : false;

		if ( $wp_lock_check && ! $allow_override ) {
			$user_info = get_userdata( $wp_lock_check );
			throw new \Exception(
				sprintf(
					// translators: %s: Editor name.
					__( 'This project is being edited by %s. The other user must save their work.', 'upstream' ),
					$user_info->user_login
				)
			);
		} else {
			delete_post_meta( $this->ID, '_edit_lock' );
		}

		$i      = 0;
		$totals = array();

		$counted_tids = array();

		$percentage_project = 0;
		$sum_project        = 0;
		$count_project      = 0;

		if ( ! empty( $milestones ) ) {

			// loop through each milestone.
			foreach ( $milestones as $milestone ) {
				// ^ add reference to make changes.
				$milestone = \UpStream\Factory::get_milestone( $milestone );

				$sum   = 0;
				$count = 0;
				$open  = 0;

				if ( $tasks ) {
					// loop through each task.
					foreach ( $tasks as $task ) {
						// if a milestone has a task assigned to it.
						if ( isset( $task['milestone'] ) && (int) $task['milestone'] === $milestone->getId() ) { // if it matches.

							$counted_tids[] = $task['id'];

							$sum += isset( $task['progress'] ) ? (int) $task['progress'] : 0; // add task progress to get the sum progress of all tasks.
							$count++; // count.

							// add open tasks count to the milestone.
							if ( ( ! isset( $task['status'] ) || empty( $task['status'] ) ) || ( isset( $task['status'] ) && $this->is_open_tasks( $task['status'] ) ) ) {
								$open++;
							}
							$sum_project += isset( $task['progress'] ) ? (int) $task['progress'] : 0;
							$count_project++;
						}
					}
				}

				// maths to work out total percentage of this milestone.
				$percentage         = $count > 0 ? $sum / ( $count * 100 ) * 100 : 0;
				$percentage_project = $count_project > 0 ? $sum_project / ( $count_project * 100 ) * 100 : 0;

				$milestone->setProgress( round( $percentage, 1 ) ); // add the percentage into our new progress key.
				$milestone->setTaskCount( $count ); // add the number of tasks in this milestone.

				if ( isset( $open ) ) {
					$milestone->setTaskOpen( $open++ );
				} // add the number of open tasks in this milestone.

				// make sure the milestone has at lea   st 1 task assigned otherwise it doesn't count.
				if ( $count > 0 ) {
					$totals[ $milestone->getId() ]['count']    = $count;
					$totals[ $milestone->getId() ]['progress'] = $percentage;
				}

				$i++;
			}
		}

		if ( ! empty( $tasks ) ) {

			foreach ( $tasks as $task ) {

				if ( ! in_array( $task['id'], $counted_tids ) ) {

					$sum_project += isset( $task['progress'] ) ? (int) $task['progress'] : 0;
					$count_project++;
					$percentage_project = $count_project > 0 ? $sum_project / ( $count_project * 100 ) * 100 : 0;
				}
			}
		}

		update_post_meta( $this->ID, '_upstream_project_tasks', $tasks );

		// maths for the total project progress.
		// do it down here out of the way.
		$project_progress = $percentage_project;

		update_post_meta( $this->ID, '_upstream_project_progress', round( $project_progress, 1 ) );
	}


	/*
	 * Create/update list of registered project users for easy retrieval later and easy permission checking
	 * Includes WP and client users
	 */

	/**
	 * Returns the count of open tasks
	 *
	 * @param bool $task_status Task Status.
	 * @return null|int
	 */
	public function is_open_tasks( $task_status ) {
		if ( ! $task_status ) {
			return;
		}

		$option   = get_option( 'upstream_tasks' );
		$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';

		if ( ! $statuses ) {
			return;
		}

		$types = wp_list_pluck( $statuses, 'type', 'id' );

		foreach ( $types as $name => $type ) {
			if ( 'open' === $type && $task_status == $name ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Update project member.
	 */
	public function update_project_members() {
		$owner = $this->get_meta( 'owner' );
		$tasks = $this->get_meta( 'tasks' );
		$bugs  = $this->get_meta( 'bugs' );
		$files = $this->get_meta( 'files' );

		$milestones = \UpStream\Milestones::getInstance()->get_milestones_from_project_no_perms( $this->ID );

		$users = array(); // start with fresh array.

		if ( $owner ) {
			$users[] = $owner;
		}

		$current_user = get_current_user_id();

		if ( $this->post_author === $current_user ) {
			$users[] = $current_user;
		}

		if ( $tasks ) :
			foreach ( $tasks as $task ) {
				if ( isset( $task['created_by'] ) ) {
					$users[] = $task['created_by'];
				}
				if ( isset( $task['assigned_to'] ) ) {
					if ( is_array( $task['assigned_to'] ) ) {
						$users = array_merge( $users, $task['assigned_to'] );
					} else {
						$users[] = $task['assigned_to'];
					}
				}
			}
		endif;

		if ( $milestones ) :
			foreach ( $milestones as $milestone ) {

				$milestone = \UpStream\Factory::get_milestone( $milestone );

				$c = $milestone->getCreatedBy();
				if ( isset( $c ) ) {
					$users[] = $c;
				}

				$c = $milestone->getAssignedTo();
				if ( isset( $c ) ) {
					if ( is_array( $c ) ) {
						$users = array_merge( $users, $c );
					} else {
						$users[] = $c;
					}
				}
			}
		endif;

		if ( $bugs ) :
			foreach ( $bugs as $bug ) {
				if ( isset( $bug['created_by'] ) ) {
					$users[] = $bug['created_by'];
				}
				if ( isset( $bug['assigned_to'] ) ) {
					if ( is_array( $bug['assigned_to'] ) ) {
						$users = array_merge( $users, $bug['assigned_to'] );
					} else {
						$users[] = $bug['assigned_to'];
					}
				}
			}
		endif;

		if ( $files ) :
			foreach ( $files as $file ) {
				if ( isset( $file['created_by'] ) ) {
					$users[] = $file['created_by'];
				}
				if ( isset( $file['assigned_to'] ) ) {
					if ( is_array( $file['assigned_to'] ) ) {
						$users = array_merge( $users, $file['assigned_to'] );
					} else {
						$users[] = $file['assigned_to'];
					}
				}
			}
		endif;

		// some tidying up.
		$users = array_unique( $users );
		$users = array_values( array_filter( $users ) );

		// do the updating.
		update_post_meta( $this->ID, '_upstream_project_members', $users );
	}
}
