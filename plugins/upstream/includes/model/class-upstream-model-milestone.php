<?php
/**
 * UpStream_Model_Milestone
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
 * Class UpStream_Model_Milestone
 */
class UpStream_Model_Milestone extends UpStream_Model_Post_Object {

	/**
	 * Progress
	 *
	 * @var int
	 */
	protected $progress = 0;

	/**
	 * Start_date
	 *
	 * @var undefined
	 */
	protected $startDate = null; // phpcs:ignore

	/**
	 * End_date
	 *
	 * @var undefined
	 */
	protected $endDate = null; // phpcs:ignore

	/**
	 * Color
	 *
	 * @var undefined
	 */
	protected $color = null;

	/**
	 * Reminders
	 *
	 * @var array
	 */
	protected $reminders = array();

	/**
	 * PostType
	 *
	 * @var string
	 */
	protected $postType = 'upst_milestone'; // phpcs:ignore

	/**
	 * Type
	 *
	 * @var undefined
	 */
	protected $type = UPSTREAM_ITEM_TYPE_MILESTONE;

	/**
	 * UpStream_Model_Milestone constructor.
	 *
	 * @param  mixed $id id.
	 * @return void
	 */
	public function __construct( $id ) {
		if ( $id > 0 ) {
			parent::__construct(
				$id,
				array(
					'progress'  => 'upst_progress',
					'color'     => 'upst_color',
					'startDate' => 'upst_start_date',
					'endDate'   => 'upst_end_date',
					'parentId'  => 'upst_project_id',
				)
			);

			$this->loadCategories();

			$res = get_post_meta( $id, 'upst_assigned_to' );
			foreach ( $res as $r ) {
				$this->assignedTo[] = (int) $r; // phpcs:ignore
			}

			$res = get_post_meta( $id, 'upst_reminders' );
			if ( ! empty( $res ) ) {
				foreach ( $res as $reminder_data ) {
					$reminder          = new UpStream_Model_Reminder( (array) $reminder_data );
					$this->reminders[] = $reminder;
				}
			}
		} else {
			parent::__construct( 0, array() );
		}

		$this->type = UPSTREAM_ITEM_TYPE_MILESTONE;
	}

	/**
	 * LoadCategories
	 */
	protected function loadCategories() {
		if ( upstream_disable_milestone_categories() ) {
			return array();
		}

		$categories = wp_get_object_terms( $this->id, 'upst_milestone_category' );

		$category_ids = array();
		if ( ! isset( $categories->errors ) ) {
			foreach ( $categories as $category ) {
				$category_ids[] = $category->term_id;
			}
		}

		$this->categoryIds = $category_ids; // phpcs:ignore
	}

	/**
	 * StoreCategories
	 *
	 * @return void
	 */
	protected function storeCategories() {
		if ( upstream_disable_milestone_categories() ) {
			return;
		}

		$res      = wp_set_object_terms( $this->id, $this->categoryIds, 'upst_milestone_category' ); // phpcs:ignore
		$is_error = false;

		if ( $res instanceof \WP_Error ) {
			$is_error = true; // TODO: throw.
		}
	}

	/**
	 * Store
	 *
	 * @return void
	 */
	public function store() {
		parent::store();

		if ( $this->parentId > 0 ) { // phpcs:ignore
			update_post_meta( $this->id, 'upst_project_id', $this->parentId ); // phpcs:ignore
		}
		if ( $this->progress > 0 ) {
			update_post_meta( $this->id, 'upst_progress', $this->progress );
		}
		if ( null !== $this->color ) {
			update_post_meta( $this->id, 'upst_color', $this->color );
		}
		if ( null !== $this->startDate ) { // phpcs:ignore
			update_post_meta( $this->id, 'upst_start_date', $this->startDate ); // phpcs:ignore
		}
		if ( null !== $this->endDate ) { // phpcs:ignore
			update_post_meta( $this->id, 'upst_end_date', $this->endDate ); // phpcs:ignore
		}
		if ( null !== $this->startDate ) { // phpcs:ignore
			update_post_meta( $this->id, 'upst_start_date.YMD', $this->startDate ); // phpcs:ignore
		}
		if ( null !== $this->endDate ) { // phpcs:ignore
			update_post_meta( $this->id, 'upst_end_date.YMD', $this->endDate ); // phpcs:ignore
		}

		delete_post_meta( $this->id, 'upst_assigned_to' );
		foreach ( $this->assignedTo as $a ) { // phpcs:ignore
			add_post_meta( $this->id, 'upst_assigned_to', $a );
		}

		$this->storeCategories();
	}

	/**
	 * CalculateElapsedTime
	 */
	public function calculateElapsedTime() {
		$total = 0;
		$tasks = &$this->tasks();

		foreach ( $tasks as $task ) {
			$total += $task->calculateElapsedTime();
		}

		return $total;
	}

	/**
	 * CalculateBudgeted
	 */
	public function calculateBudgeted() {
		$total = 0;
		$tasks = &$this->tasks();

		foreach ( $tasks as $task ) {
			$total += $task->calculateBudgeted();
		}

		return $total;
	}

	/**
	 * CalculateSpent
	 */
	public function calculateSpent() {
		$total = 0;
		$tasks = &$this->tasks();

		foreach ( $tasks as $task ) {
			$total += $task->calculateSpent();
		}

		return $total;
	}

	/**
	 * Get
	 *
	 * @param  mixed $property property.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public function __get( $property ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'notes':
				return $this->description;

			case 'progress':
			case 'categoryIds':
			case 'startDate':
			case 'endDate':
			case 'color':
				return $this->{$property};

			case 'elapsedTime':
				return $this->calculateElapsedTime();
			case 'budgeted':
				return $this->calculateBudgeted();
			case 'spent':
				return $this->calculateSpent();

			case 'categories':
				$categories = array();
				foreach ( $this->categoryIds as $tid ) { // phpcs:ignore
					$term         = get_term_by( 'id', $tid, 'upst_milestone_category' );
					$categories[] = $term;
				}
				return $categories;

			case 'tasks':
				throw new UpStream_Model_ArgumentException( __( 'Not implemented. Use &tasks().', 'upstream' ) );

			default:
				return parent::__get( $property );

		}
	}

	/**
	 * Set
	 *
	 * @param  mixed $property property.
	 * @param  mixed $value value.
	 * @throws UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	public function __set( $property, $value ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'parentId':
				$project        = \UpStream_Model_Manager::get_instance()->getByID( UPSTREAM_ITEM_TYPE_PROJECT, $value );
				$this->parentId = $project->id; // phpcs:ignore
				break;

			case 'categoryIds':
				if ( ! is_array( $value ) ) {
					$value = array( $value );
				}

				$category_ids = array();
				foreach ( $value as $tid ) {
					$term = get_term_by( 'id', $tid, 'upst_milestone_category' );
					if ( false === $term ) {
						throw new UpStream_Model_ArgumentException(
							sprintf(
								// translators: %s: term id.
								__( 'Term ID %s is invalid.', 'upstream' ),
								$tid
							)
						);
					}
					$category_ids[] = $term->term_id;
				}

				$this->categoryIds = $category_ids; // phpcs:ignore

				break;

			case 'startDate':
			case 'endDate':
				if ( ! self::isValidDate( $value ) ) {
					throw new UpStream_Model_ArgumentException( __( 'Argument is not a valid date of the form YYYY-MM-DD.', 'upstream' ) );
				}

				$this->{$property} = $value;
				break;

			case 'color':
				if ( ! preg_match( '/\#[a-zA-Z0-9]{6}/', $value ) ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
							// translators: %s: hex string.
							__( '%s is not a valid hex string.', 'upstream' ),
							$value
						)
					);
				}

				$this->{$property} = $value;
				break;

			case 'notes':
				$this->description = wp_kses_post( $value );
				break;

			default:
				parent::__set( $property, $value );
				break;

		}
	}

	/**
	 * GetCategories
	 */
	public static function getCategories() {
		$tid_to_term = array();
		$terms       = get_terms(
			array(
				'taxonomy'   => 'upst_milestone_category',
				'hide_empty' => true,
			)
		);

		foreach ( $terms as $term ) {
			$tid_to_term[ $term->term_id ] = $term->name;
		}

		return $tid_to_term;
	}

	/**
	 * Tasks
	 */
	public function &tasks() {
		if ( 0 === $this->parentId ) { // phpcs:ignore
			return array();
		}

		$my_tasks = array();
		$project  = \UpStream_Model_Manager::get_instance()->getByID( UPSTREAM_ITEM_TYPE_PROJECT, $this->parentId ); // phpcs:ignore
		$tasks    = &$project->tasks();

		foreach ( $tasks as $task ) {
			if ( $task->milestoneId === $this->id ) { // phpcs:ignore
				$my_tasks[] = $task;
			}
		}

		return $my_tasks;
	}

	/**
	 * Fields
	 */
	public static function fields() {
		$fields = parent::fields();

		$fields['description'] = array(
			'type'    => 'text',
			'title'   => __( 'Notes' ),
			'search'  => true,
			'display' => true,
		);
		$fields['color']       = array(
			'type'    => 'color',
			'title'   => __( 'Color' ),
			'search'  => false,
			'display' => true,
		);
		$fields['startDate']   = array(
			'type'    => 'date',
			'title'   => __( 'Start Date' ),
			'search'  => true,
			'display' => true,
		);
		$fields['endDate']     = array(
			'type'    => 'date',
			'title'   => __( 'End Date' ),
			'search'  => true,
			'display' => true,
		);
		$fields['categoryIds'] = array(
			'type'       => 'select',
			'title'      => __( 'Categories' ),
			'search'     => true,
			'display'    => true,
			'options_cb' => 'UpStream_Model_Milestone::getCategories',
			'is_array'   => 'true',
		);
		$fields['progress']    = array(
			'type'    => 'number',
			'title'   => __( 'Progress (%)' ),
			'search'  => true,
			'display' => true,
		);

		$fields = self::customFields( $fields, UPSTREAM_ITEM_TYPE_MILESTONE );

		return $fields;
	}

	/**
	 * Create
	 *
	 * @param  mixed $title title.
	 * @param  mixed $created_by created_by.
	 * @param  mixed $parent_id parent_id.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public static function create( $title, $created_by, $parent_id = 0 ) {
		if ( get_userdata( $created_by ) === false ) {
			throw new UpStream_Model_ArgumentException( __( 'User ID does not exist.', 'upstream' ) );
		}

		$item = new \UpStream_Model_Milestone( 0 );

		$item->title     = sanitize_text_field( $title );
		$item->createdBy = $created_by; // phpcs:ignore

		if ( $parent_id > 0 ) {
			$project        = \UpStream_Model_Manager::get_instance()->getByID( UPSTREAM_ITEM_TYPE_PROJECT, $parent_id );
			$item->parentId = $project->id; // phpcs:ignore
		}

		return $item;
	}

}
