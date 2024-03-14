<?php
/**
 * Class Milestone
 *
 * @package UpStream
 */

namespace UpStream;

// Prevent direct access.

use UpStream\Traits\PostMetadata;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Milestone
 *
 * @since   1.24.0
 */
class Milestone extends Struct {

	use PostMetadata;

	/**
	 * The Post Type for milestones.
	 */
	const POST_TYPE = 'upst_milestone';

	/**
	 * Project ID meta key.
	 */
	const META_PROJECT_ID = 'upst_project_id';

	/**
	 * Assigned To meta key.
	 */
	const META_ASSIGNED_TO = 'upst_assigned_to';

	/**
	 * Start date meta key.
	 */
	const META_START_DATE = 'upst_start_date';

	/**
	 * End date meta key.
	 */
	const META_END_DATE = 'upst_end_date';

	/**
	 * Order meta key.
	 */
	const META_ORDER = 'upst_order';

	/**
	 * Progress meta key.
	 */
	const META_PROGRESS = 'upst_progress';

	/**
	 * Color meta key.
	 */
	const META_COLOR = 'upst_color';

	/**
	 * Legacy ID meta key.
	 */
	const META_LEGACY_ID = 'upst_legacy_id';

	/**
	 * Legacy code used to link tasks to milestones.
	 */
	const META_LEGACY_MILESTONE_CODE = 'upst_legacy_milestone_code';

	/**
	 * Legacy flag for when the created time was set in UTC.
	 */
	const META_CREATED_TIME_IN_UTC = 'upst_created_time_in_utc';

	/**
	 * Task count meta key.
	 */
	const META_TASK_COUNT = 'upst_task_count';

	/**
	 * Task open meta key.
	 */
	const META_TASK_OPEN = 'upst_task_open';

	/**
	 * The default color.
	 */
	const DEFAULT_COLOR = '#cccccc';

	/**
	 * WP Post
	 *
	 * @var \WP_Post
	 */
	protected $post;

	/**
	 * Project ID
	 *
	 * @var int
	 */
	protected $project_id;

	/**
	 * Assigned to
	 *
	 * @var array
	 */
	protected $assigned_to;

	/**
	 * Start date in MySQL timestamp.
	 *
	 * @var string
	 */
	protected $start_date;

	/**
	 * End date in MySQL timestamp.
	 *
	 * @var string
	 */
	protected $end_date;

	/**
	 * Notes
	 *
	 * @var string
	 */
	protected $notes;

	/**
	 * Order
	 *
	 * @var int
	 */
	protected $order;

	/**
	 * Created By
	 *
	 * @var int
	 */
	protected $created_date;

	/**
	 * Created on date, in MySQL format
	 *
	 * @var string
	 */
	protected $created_on;

	/**
	 * Progress
	 *
	 * @var float
	 */
	protected $progress;

	/**
	 * Color
	 *
	 * @var string
	 */
	protected $color;

	/**
	 * Legacy ID
	 *
	 * @var string
	 */
	protected $legacy_id;

	/**
	 * Legacy_milestone_code
	 *
	 * @var string
	 */
	protected $legacy_milestone_code;

	/**
	 * Created Time In Utc
	 *
	 * @deprecated only for storing the legacy value from the old architecture.
	 *
	 * @var bool
	 */
	protected $created_time_in_utc;

	/**
	 * Task Count
	 *
	 * @var int
	 */
	protected $task_count;

	/**
	 * Task Open
	 *
	 * @var int
	 */
	protected $task_open;

	/**
	 * Categories
	 *
	 * @var array
	 */
	protected $categories;

	/**
	 * End date in YMD
	 *
	 * @var string
	 */
	protected $end_date_ymd;

	/**
	 * Start date in YMD
	 *
	 * @var string
	 */
	protected $start_date_ymd;


	/**
	 * Milestone constructor.
	 *
	 * @param int|\WP_Post|string $post Post object.
	 *
	 * @throws \Exception Exception.
	 */
	public function __construct( $post ) {
		if ( empty( $post ) ) {
			throw new Exception( 'Invalid milestone post ID' );
		}

		if ( is_object( $post ) ) {
			if ( self::POST_TYPE !== $post->post_type ) {
				throw new Exception( __( 'Invalid Post Type for the given post.', 'upstream' ) );
			}

			$this->post_id = $post->ID;
			$this->post    = $post;
		}

		if ( is_numeric( $post ) ) {
			$this->post_id = $post;
			$this->post    = $this->getPost();
		}

		// Are we filtering by the legacy ID?
		if ( is_string( $post ) && ! is_numeric( $post ) ) {
			$this->post = $this->getPost( $post );

			if ( empty( $this->post ) ) {
				throw new Exception( 'Milestone not found' );
			}

			$this->post_id = $this->post->ID;
		}
	}

	/**
	 * Get Post data.
	 *
	 * @param string|null $legacy_id Legacy ID.
	 *
	 * @return \WP_Post|false Post data or false.
	 *
	 * @throws Exception Exception.
	 */
	public function getPost( $legacy_id = null ) {
		if ( empty( $this->post ) ) {
			if ( empty( $this->post_id ) && empty( $legacy_id ) ) {
				return false;
			}

			if ( empty( $legacy_id ) ) {
				$this->post = get_post( $this->post_id );
			} else {
				$posts = get_posts(
					array(
						'post_type'      => self::POST_TYPE,
						'status'         => 'publish',
						'posts_per_page' => -1,
						'meta_key'       => self::META_LEGACY_ID,
						'meta_value'     => sanitize_text_field( $legacy_id ),
					)
				);

				if ( ! empty( $posts ) ) {
					$this->post = $posts[0];
				} else {
					throw new Exception( 'Milestone not found' );
				}
			}
		}

		return $this->post;
	}

	/**
	 * Set milestone name
	 *
	 * @param string $new_name New name.
	 *
	 * @return Milestone
	 */
	public function setName( $new_name ) {
		$this->post->post_title = $new_name;

		$milestones = $this->getMilestonesInstance();

		remove_action( 'save_post', array( $milestones, 'save_post' ) );
		wp_update_post(
			array(
				'ID'         => $this->post_id,
				'post_title' => $new_name,
			)
		);
		add_action( 'save_post', array( $milestones, 'save_post' ) );

		return $this;
	}

	/**
	 * Get Milestones Instance
	 *
	 * @return \UpStream\Milestones
	 */
	protected function getMilestonesInstance() {
		return Milestones::getInstance();
	}

	/**
	 * Get Legacy Id
	 *
	 * @return string|null
	 */
	public function getLegacyId() {
		if ( ! empty( $this->legacy_id ) ) {
			return $this->legacy_id;
		}

		$this->legacy_id = $this->get_metadata( self::META_LEGACY_ID, true );

		return $this->legacy_id;
	}

	/**
	 * Set Legacy Id
	 *
	 * @param string $new_legacy_id New legacy ID.
	 *
	 * @return Milestone
	 */
	public function setLegacyId( $new_legacy_id ) {
		$this->legacy_id = sanitize_text_field( $new_legacy_id );

		$this->update_metadata( array( self::META_LEGACY_ID => $new_legacy_id ) );

		return $this;
	}

	/**
	 * Get Legacy Milestone Code
	 *
	 * @return string|null
	 */
	public function getLegacyMilestoneCode() {
		if ( ! empty( $this->legacy_milestone_code ) ) {
			return $this->legacy_milestone_code;
		}

		$this->legacy_milestone_code = $this->get_metadata( self::META_LEGACY_MILESTONE_CODE, true );

		return $this->legacy_milestone_code;
	}

	/**
	 * Set Legacy Milestone Code
	 *
	 * @param string $new_legacy_milestone_code New legacy milestone code.
	 *
	 * @return Milestone
	 */
	public function setLegacyMilestoneCode( $new_legacy_milestone_code ) {
		$this->legacy_milestone_code = sanitize_text_field( $new_legacy_milestone_code );

		$this->update_metadata( array( self::META_LEGACY_MILESTONE_CODE => $new_legacy_milestone_code ) );

		return $this;
	}

	/**
	 * GetCreatedTimeInUtc
	 *
	 * @return bool|null
	 */
	public function getCreatedTimeInUtc() {
		if ( ! empty( $this->created_time_in_utc ) ) {
			return $this->created_time_in_utc;
		}

		$this->created_time_in_utc = $this->get_metadata( self::META_CREATED_TIME_IN_UTC, true );

		return $this->created_time_in_utc;
	}

	/**
	 * SetCreatedTimeInUtc
	 *
	 * @param bool $new_created_time_in_utc New Created Time In Utc.
	 *
	 * @return Milestone
	 */
	public function setCreatedTimeInUtc( $new_created_time_in_utc ) {
		$this->created_time_in_utc = sanitize_text_field( $new_created_time_in_utc );

		$this->update_metadata( array( self::META_CREATED_TIME_IN_UTC => $this->created_time_in_utc ) );

		return $this;
	}

	/**
	 * Delete the milestone.
	 */
	public function delete() {
		global $wpdb;

		try {
			$wpdb->query( 'START TRANSACTION' );

			$project_id = $this->getProjectId();

			$tasks = (array) get_post_meta( $project_id, '_upstream_project_tasks', true );
			if ( count( $tasks ) > 0 ) {
				$updated = false;

				foreach ( $tasks as &$task ) {
					if ( isset( $task['milestone'] ) && $task['milestone'] === $this->getId() ) {
						$task['milestone'] = '';

						$updated = true;
					}
				}
				unset( $task );

				if ( $updated ) {
					update_post_meta( $project_id, '_upstream_project_tasks', $tasks );
				}
			}

			wp_trash_post( $this->getId() );

			$activity = Factory::get_activity();
			$activity->add_activity(
				$project_id,
				'_upstream_project_milestones',
				'remove',
				$this->convertToLegacyRowset()
			);

			$wpdb->query( 'COMMIT' );
		} catch ( Exception $e ) {
			$wpdb->query( 'ROLLBACK' );
		}
	}

	/**
	 * GetProjectId
	 *
	 * @return int
	 */
	public function getProjectId() {
		if ( null === $this->project_id ) {
			$this->project_id = (int) $this->get_metadata( self::META_PROJECT_ID, true );
		}

		return (int) $this->project_id;
	}

	/**
	 * SetProjectId
	 *
	 * @param int $project_id Project ID.
	 *
	 * @return Milestone
	 */
	public function setProjectId( $project_id ) {
		$this->project_id = (int) $project_id;

		// Update the metadata.
		$this->update_metadata( array( self::META_PROJECT_ID => $project_id ) );

		$milestones = $this->getMilestonesInstance();

		// Update the post parent so we can use it to filter and group project items.
		$this->post->post_parent = $project_id;
		remove_action( 'save_post', array( $milestones, 'save_post' ) );
		wp_update_post(
			array(
				'ID'          => $this->getId(),
				'post_parent' => $project_id,
			)
		);
		add_action( 'save_post', array( $milestones, 'save_post' ) );

		return $this;
	}

	/**
	 * Get Id
	 *
	 * @return int
	 */
	public function getId() {
		return $this->post_id;
	}

	/**
	 * ConvertToLegacyRowset
	 *
	 * @return array
	 * @throws Exception Exception.
	 */
	public function convertToLegacyRowset() {
		$assignees  = $this->getAssignedTo();
		$categories = $this->getCategories();

		if ( ! empty( $categories ) ) {
			$categories_ids = array_map( array( $this, 'convertCategoryToLegacyRowset' ), $categories );
		} else {
			$categories_ids = array();
		}

		$row = array(
			'id'               => $this->getId(),
			'milestone'        => $this->getName(),
			'milestone_order'  => $this->getOrder(),
			'created_by'       => $this->getCreatedBy(),
			'created_time'     => $this->getCreatedOn( 'unix' ),
			'assigned_to'      => $assignees,
			'progress'         => $this->getProgress(),
			'notes'            => $this->getNotes(),
			'start_date'       => $this->getStartDate( 'unix' ),
			'end_date'         => $this->getEndDate( 'unix' ),
			'start_date.YMD'   => $this->getStartDate__YMD(),
			'end_date.YMD'     => $this->getEndDate__YMD(),
			'task_count'       => $this->getTaskCount(),
			'task_open'        => $this->getTaskOpen(),
			'color'            => $this->getColor(),
			'categories'       => $categories_ids,
			'categories_order' => $this->getMilestonesInstance()->get_categories_names( $categories ),
		);

		if ( ! empty( $assignees ) ) {
			// Get the name of assignees to fix ordering.
			$row['assigned_to_order'] = upstream_get_users_display_name( $assignees );
		}

		$row = apply_filters( 'upstream_milestone_converting_legacy_rowset', $row );

		return $row;
	}

	/**
	 * GetAssignedTo
	 *
	 * @return array
	 */
	public function getAssignedTo() {
		if ( null === $this->assigned_to ) {
			$this->assigned_to = $this->get_metadata( self::META_ASSIGNED_TO, false );
		}

		return (array) $this->assigned_to;
	}

	/**
	 * SetAssignedTo
	 *
	 * @param array $assigned_to Assigned to.
	 *
	 * @return Milestone
	 */
	public function setAssignedTo( $assigned_to ) {
		if ( ! is_array( $assigned_to ) ) {
			$assigned_to = array();
		}

		$this->assigned_to = $this->sanitizeArrayOfIds( $assigned_to );

		$this->update_non_unique_metadata( self::META_ASSIGNED_TO, $this->assigned_to );

		return $this;
	}

	/**
	 * GetCategories
	 *
	 * @param bool $only_keys Only keys boolean.
	 *
	 * @return array
	 */
	public function getCategories( $only_keys = false ) {
		if ( upstream_disable_milestone_categories() ) {
			return array();
		}

		if ( ! isset( $this->categories ) ) {
			$this->categories = wp_get_object_terms( $this->post_id, 'upst_milestone_category' );
		}

		if ( isset( $this->categories->errors ) ) {
			return array();
		}

		if ( ! $only_keys ) {
			$categories = $this->categories;
		} else {
			if ( empty( $this->categories ) ) {
				$categories = array();
			} else {
				$categories = array_map( array( $this, 'convertCategoryToLegacyRowset' ), $this->categories );
			}
		}

		return (array) $categories;
	}

	/**
	 * This method accepts array of integers (term_id) or instances of WP_Term.
	 *
	 * @param array $categories Milestone categories.
	 *
	 * @return Milestone
	 *
	 * @throws Exception Exception.
	 */
	public function setCategories( $categories ) {
		if ( ! is_array( $categories ) ) {
			$categories = array();
		}

		$new_categories = array();

		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				if ( is_object( $category ) && get_class( $category ) === 'WP_Term' ) {
					$category = $category->term_id;
				}

				$new_categories[] = (int) $category;
			}
		}

		wp_set_object_terms( $this->post_id, $new_categories, 'upst_milestone_category' );

		return $this;
	}

	/**
	 * Get Name
	 *
	 * @return string
	 *
	 * @throws Exception Exception.
	 */
	public function getName() {
		if ( isset( $this->getPost()->post_title ) ) {
			return $this->getPost()->post_title;
		}
		return '';
	}

	/**
	 * Get Order
	 *
	 * @return string|null
	 */
	public function getOrder() {
		if ( ! empty( $this->order ) ) {
			return $this->order;
		}

		$this->order = $this->get_metadata( self::META_ORDER, true );

		return $this->order;
	}

	/**
	 * SetOrder
	 *
	 * @param string $order Order.
	 *
	 * @return Milestone
	 * @throws Exception Exception.
	 */
	public function setOrder( $order ) {
		if ( empty( $order ) ) {
			$order = $this->getName();
		}

		$this->order = $order;

		// Assume it is on MySQL date format.
		$this->update_metadata( array( self::META_ORDER => $order ) );

		return $this;
	}

	/**
	 * GetCreatedBy
	 *
	 * @return int|null
	 *
	 * @throws Exception Exception.
	 */
	public function getCreatedBy() {
		if ( ! empty( $this->created_date ) ) {
			return $this->created_date;
		}

		$this->created_date = (int) $this->getPost()->post_author;

		return $this->created_date;
	}

	/**
	 * GetCreatedOn
	 *
	 * @param string $format Format data.
	 *
	 * @return int|null
	 */
	public function getCreatedOn( $format = 'mysql' ) {
		if ( ! empty( $this->created_on ) ) {
			return $this->created_on;
		}

		$this->created_on = get_the_date( 'Y-m-d', $this->post_id );

		return $this->getDateOnFormat( $this->created_on, $format );
	}

	/**
	 * GetDateOnFormat
	 *
	 * @param string $date Date.
	 * @param string $format Format.
	 *
	 * @return false|int|mixed
	 */
	protected function getDateOnFormat( $date, $format ) {
		if ( 'unix' === $format ) {
			return strtotime( $date );
		}

		if ( 'upstream' === $format ) {
			if ( ! preg_match( '/^\d+$/', $date ) ) {
				$date = strtotime( $date );
			}

			return upstream_format_date( $date );
		}

		return $date;
	}

	/**
	 * GetProgress
	 *
	 * @return float|int
	 */
	public function getProgress() {
		if ( ! empty( $this->progress ) ) {
			return $this->progress;
		}

		$this->progress = $this->get_metadata( self::META_PROGRESS, true );

		if ( empty( $this->progress ) ) {
			$this->progress = 0.00;
		}

		return (float) $this->progress;
	}

	/**
	 * SetProgress
	 *
	 * @param float $new_progress New progress.
	 *
	 * @return Milestone
	 */
	public function setProgress( $new_progress ) {
		$this->progress = (float) $new_progress;

		$this->update_metadata( array( self::META_PROGRESS => $new_progress ) );

		return $this;
	}

	/**
	 * GetNotes
	 *
	 * @return string|null
	 *
	 * @throws Exception Exception.
	 */
	public function getNotes() {
		if ( ! empty( $this->notes ) ) {
			return $this->notes;
		}

		$this->notes = $this->getPost()->post_content;

		return $this->notes;
	}

	/**
	 * SetNotes
	 *
	 * @param string $notes Notes.
	 *
	 * @return Milestone
	 */
	public function setNotes( $notes ) {
		$this->notes = $notes;

		$milestones = $this->getMilestonesInstance();

		remove_action( 'save_post', array( $milestones, 'save_post' ) );
		wp_update_post(
			array(
				'ID'           => $this->post_id,
				'post_content' => $notes,
			)
		);
		add_action( 'save_post', array( $milestones, 'save_post' ) );

		return $this;
	}

	/**
	 * GetStartDate
	 *
	 * @param string $format mysql, unix, upstream.
	 *
	 * @return string
	 */
	public function getStartDate( $format = 'mysql' ) {
		if ( null === $this->start_date ) {
			$this->start_date = $this->get_metadata( self::META_START_DATE, true );
		}

		return $this->getDateOnFormat( $this->start_date, $format );
	}

	/**
	 * SetStartDate
	 *
	 * @param int|string $start_date Start date.
	 *
	 * @return Milestone
	 */
	public function setStartDate( $start_date ) {
		$start_date = $this->getMySQLDate( $start_date );

		$this->start_date = $start_date;

		// Assume it is on MySQL date format.
		$this->update_metadata( array( self::META_START_DATE => $start_date ) );

		return $this;
	}

	/**
	 * SetStartDate__YMD
	 *
	 * @param string $start_date_ymd Start date ymd.
	 */
	public function setStartDate__YMD( $start_date_ymd ) {
		$this->update_metadata( array( 'upst_start_date.YMD' => $start_date_ymd ) );
		return $this;
	}

	/**
	 * GetEndDate__YMD
	 */
	public function getEndDate__YMD() {
		if ( empty( $this->end_date_ymd ) && is_array( $this->get_metadata( 'upst_end_date.YMD' ) ) ) {
			$r = $this->get_metadata( 'upst_end_date.YMD' );
			if ( ! empty( $r ) && is_array( $r ) && count( $r ) > 0 ) {
				$this->end_date_ymd = $r[0];
			}
		}
		return $this->end_date_ymd;
	}

	/**
	 * GetStartDate__YMD
	 */
	public function getStartDate__YMD() {
		if ( empty( $this->start_date_ymd ) && is_array( $this->get_metadata( 'upst_start_date.YMD' ) ) ) {
			$r = $this->get_metadata( 'upst_start_date.YMD' );
			if ( ! empty( $r ) && is_array( $r ) && count( $r ) > 0 ) {
				$this->start_date_ymd = $r[0];
			}
		}
		return $this->start_date_ymd;
	}

	/**
	 * SetEndDate__YMD
	 *
	 * @param string $end_date_ymd End date ymd.
	 */
	public function setEndDate__YMD( $end_date_ymd ) {
		$this->update_metadata( array( 'upst_end_date.YMD' => $end_date_ymd ) );
		return $this;
	}

	/**
	 * GetEndDate
	 *
	 * @param string $format mysql, unix, upstream.
	 *
	 * @return string
	 */
	public function getEndDate( $format = 'mysql' ) {
		if ( null === $this->end_date ) {
			$this->end_date = $this->get_metadata( self::META_END_DATE, true );
		}

		return $this->getDateOnFormat( $this->end_date, $format );
	}

	/**
	 * SetEndDate
	 *
	 * @param int|string $end_date End date.
	 *
	 * @return Milestone
	 */
	public function setEndDate( $end_date ) {
		$end_date = $this->getMySQLDate( $end_date );

		$this->end_date = $end_date;

		// Assume it is on MySQL date format.
		$this->update_metadata( array( self::META_END_DATE => $end_date ) );

		return $this;
	}

	/**
	 * GetTaskCount
	 *
	 * @return int|null
	 */
	public function getTaskCount() {
		if ( ! empty( $this->task_count ) ) {
			return $this->task_count;
		}

		$this->task_count = $this->get_metadata( self::META_TASK_COUNT, true );

		return $this->task_count;
	}

	/**
	 * SetTaskCount
	 *
	 * @param int $new_task_count New Task Count.
	 *
	 * @return Milestone
	 */
	public function setTaskCount( $new_task_count ) {
		$this->task_count = sanitize_text_field( $new_task_count );

		$this->update_metadata( array( self::META_TASK_COUNT => $new_task_count ) );

		return $this;
	}

	/**
	 * GetTaskOpen
	 *
	 * @return int|null
	 */
	public function getTaskOpen() {
		if ( ! empty( $this->task_open ) ) {
			return $this->task_open;
		}

		$this->task_open = $this->get_metadata( self::META_TASK_OPEN, true );

		return $this->task_open;
	}

	/**
	 * SetTaskOpen
	 *
	 * @param int $new_task_open New Task Open.
	 *
	 * @return Milestone
	 */
	public function setTaskOpen( $new_task_open ) {
		$this->task_open = sanitize_text_field( $new_task_open );

		$this->update_metadata( array( self::META_TASK_OPEN => $new_task_open ) );

		return $this;
	}

	/**
	 * GetColor
	 *
	 * @return string|null
	 */
	public function getColor() {
		if ( ! empty( $this->color ) ) {
			return $this->color;
		}

		$this->color = $this->get_metadata( self::META_COLOR, true );

		if ( empty( $this->color ) ) {
			// Check if the category (if set) has any default color.
			$categories = $this->getCategories();
			if ( ! empty( $categories ) ) {
				$first_category = $categories[0];

				$category_default_color = get_term_meta( $first_category->term_id, 'color', true );

				if ( ! empty( $category_default_color ) ) {
					$this->color = $category_default_color;
				}
			}

			if ( empty( $this->color ) ) {
				$this->color = self::DEFAULT_COLOR;
			}
		}

		return $this->color;
	}

	/**
	 * SetColor
	 *
	 * @param string $new_color New color.
	 *
	 * @return Milestone
	 */
	public function setColor( $new_color ) {
		$this->color = sanitize_text_field( $new_color );

		$this->update_metadata( array( self::META_COLOR => $new_color ) );

		return $this;
	}

	/**
	 * SanitizeArrayOfIds
	 *
	 * @param array $array Array data.
	 *
	 * @return array array
	 */
	protected function sanitizeArrayOfIds( $array ) {
		if ( ! empty( $array ) ) {
			$array = array_map( 'intval', $array );

			$array = $this->removeEmptyValuesFromArray( $array );
		}

		return $array;
	}

	/**
	 * RemoveEmptyValuesFromArray
	 *
	 * @param array $array Array data.
	 *
	 * @return array $array
	 */
	protected function removeEmptyValuesFromArray( $array ) {
		if ( ! empty( $array ) ) {
			$array = array_unique( $array );
			$array = array_filter( $array );
		}

		return $array;
	}

	/**
	 * GetMySQLDate
	 *
	 * @param mixed $date Date.
	 *
	 * @return false|mixed|string
	 */
	protected function getMySQLDate( $date ) {
		if ( ! $this->dateIsMySQLDateFormat( $date ) ) {
			if ( ! $this->dateIsUnixTime( $date ) ) {
				// Convert to unix time.
				$date = upstream_date_unixtime( $date );
			}

			// Assume it is in unix time format and convert to MySQL date format.
			if ( ! empty( $date ) ) {
				$date = gmdate( 'Y-m-d', $date );
			}
		}

		return $date;
	}

	/**
	 * DateIsMySQLDateFormat
	 *
	 * @param int|string $date Date.
	 *
	 * @return bool
	 */
	protected function dateIsMySQLDateFormat( $date ) {
		return preg_match( '/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date );
	}

	/**
	 * DateIsUnixTime
	 *
	 * @param string $date Date.
	 *
	 * @return bool
	 */
	protected function dateIsUnixTime( $date ) {
		return preg_match( '/^\d+$/', $date );
	}

	/**
	 * ConvertCategoryToLegacyRowset
	 *
	 * @param string $category Category.
	 */
	public function convertCategoryToLegacyRowset( $category ) {
		return $category->term_id;
	}

	/**
	 * GetComments
	 *
	 * @return array
	 */
	public function getComments() {
		$comments = get_comments(
			array(
				'post_id'    => $this->getProjectId(),
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => 'type',
						'value'   => 'milestone',
						'compare' => '=',
					),
					array(
						'key'     => 'id',
						'value'   => $this->getId(),
						'compare' => '=',
					),
				),
			)
		);

		return $comments;
	}

	/**
	 * IsInProgress
	 *
	 * @return bool
	 */
	public function isInProgress() {
		$progress = $this->getProgress();

		return $progress > 0 && $progress < 100;
	}

	/**
	 * IsUpcoming
	 *
	 * @return bool
	 */
	public function isUpcoming() {
		return ( $this->getStartDate( 'unix' ) < time() ) && ! $this->isCompleted();
	}

	/**
	 * IsCompleted
	 *
	 * @return bool
	 */
	public function isCompleted() {
		return $this->getProgress() === 100;
	}
}
