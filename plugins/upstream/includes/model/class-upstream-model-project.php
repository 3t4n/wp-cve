<?php
/**
 * UpStream_Model_Project
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
 * Class UpStream_Model_Project
 */
class UpStream_Model_Project extends UpStream_Model_Post_Object {

	/**
	 * Tasks
	 *
	 * @var array
	 */
	protected $tasks = array();

	/**
	 * Bugs
	 *
	 * @var array
	 */
	protected $bugs = array();

	/**
	 * Files
	 *
	 * @var array
	 */
	protected $files = array();

	/**
	 * Start Date
	 *
	 * @var undefined
	 */
	protected $startDate = null; // phpcs:ignore

	/**
	 * End Date
	 *
	 * @var undefined
	 */
	protected $endDate = null; // phpcs:ignore

	/**
	 * Client User Ids
	 *
	 * @var array
	 */
	protected $clientUserIds = array(); // phpcs:ignore

	/**
	 * Member User Ids
	 *
	 * @var array
	 */
	protected $memberUserIds = array(); // phpcs:ignore

	/**
	 * Client Id
	 *
	 * @var int
	 */
	protected $clientId = 0; // phpcs:ignore

	/**
	 * Progress
	 *
	 * @var int
	 */
	protected $progress = 0;

	/**
	 * Status Code
	 *
	 * @var undefined
	 */
	protected $statusCode = null; // phpcs:ignore

	/**
	 * Post Type
	 *
	 * @var string
	 */
	protected $postType = 'project'; // phpcs:ignore

	/**
	 * Type
	 *
	 * @var undefined
	 */
	protected $type = UPSTREAM_ITEM_TYPE_PROJECT;

	/**
	 * UpStream_Model_Project constructor.
	 *
	 * @param  mixed $id id.
	 * @return void
	 */
	public function __construct( $id ) {
		if ( $id > 0 ) {
			parent::__construct(
				$id,
				array(
					'clientUserIds' => function ( $m ) {
						$arr = isset( $m['_upstream_project_client_users'][0] ) ? unserialize( $m['_upstream_project_client_users'][0] ) : null;
						$arr = is_array( $arr ) ? $arr : array();
						$arr = array_filter( $arr );
						return $arr;
					},
					'memberUserIds' => function ( $m ) {
						$arr = isset( $m['_upstream_project_members'][0] ) ? unserialize( $m['_upstream_project_members'][0] ) : null;
						$arr = is_array( $arr ) ? $arr : array();
						$arr = array_filter( $arr );
						return $arr;
					},
					'clientId'      => '_upstream_project_client',
					'progress'      => '_upstream_project_progress',
					'statusCode'    => '_upstream_project_status',
					'description'   => '_upstream_project_description',
					'startDate'     => function ( $m ) {
						if ( ! empty( $m['_upstream_project_start.YMD'][0] ) ) {
							return $m['_upstream_project_start.YMD'][0];
						} elseif ( ! empty( $m['_upstream_project_start'][0] ) ) {
							return UpStream_Model_Object::timestampToYMD( $m['_upstream_project_start'][0] );
						}
					},
					'endDate'       => function ( $m ) {
						if ( ! empty( $m['_upstream_project_end.YMD'][0] ) ) {
							return $m['_upstream_project_end.YMD'][0];
						} elseif ( ! empty( $m['_upstream_project_end'][0] ) ) {
							return UpStream_Model_Object::timestampToYMD( $m['_upstream_project_end'][0] );
						}
					},
					'assignedTo'    => function ( $m ) {
						return ! empty( $m['_upstream_project_owner'][0] ) ? $m['_upstream_project_owner'] : array();
					},
				)
			);

			$this->loadChildren();
			$this->loadCategories();
		} else {
			parent::__construct( 0, array() );
		}

		$this->type = UPSTREAM_ITEM_TYPE_PROJECT;
	}

	/**
	 * Load Children
	 *
	 * @return void
	 */
	protected function loadChildren() {
		// TODO: check if these are disabled.
		$itemset = get_post_meta( $this->id, '_upstream_project_tasks' );
		if ( $itemset && count( $itemset ) === 1 && is_array( $itemset[0] ) ) {
			foreach ( $itemset[0] as $item ) {
				$this->tasks[] = new UpStream_Model_Task( $this, $item );
			}
		}

		$itemset = get_post_meta( $this->id, '_upstream_project_bugs' );
		if ( $itemset && count( $itemset ) === 1 && is_array( $itemset[0] ) ) {
			foreach ( $itemset[0] as $item ) {
				$this->bugs[] = new UpStream_Model_Bug( $this, $item );
			}
		}

		$itemset = get_post_meta( $this->id, '_upstream_project_files' );
		if ( $itemset && count( $itemset ) === 1 && is_array( $itemset[0] ) ) {
			foreach ( $itemset[0] as $item ) {
				$this->files[] = new UpStream_Model_File( $this, $item );
			}
		}
	}

	/**
	 * Load Categories
	 */
	protected function loadCategories() {
		if ( upstream_is_project_categorization_disabled() ) {
			return array();
		}

		$categories = wp_get_object_terms( $this->id, 'project_category' );

		$category_ids = array();
		if ( ! isset( $categories->errors ) ) {
			foreach ( $categories as $category ) {
				$category_ids[] = $category->term_id;
			}
		}

		$this->categoryIds = $category_ids; // phpcs:ignore
	}

	/**
	 * Calculate Elapsed Time
	 */
	public function calculateElapsedTime() {
		$total = 0;

		foreach ( $this->tasks as $task ) {
			$total += $task->calculateElapsedTime();
		}

		foreach ( $this->bugs as $bug ) {
			$total += $bug->calculateElapsedTime();
		}

		return $total;
	}

	/**
	 * Calculate Budgeted
	 */
	public function calculateBudgeted() {
		$total = 0;

		foreach ( $this->tasks as $task ) {
			$total += $task->calculateBudgeted();
		}

		foreach ( $this->bugs as $bug ) {
			$total += $bug->calculateBudgeted();
		}

		return $total;
	}

	/**
	 * Calculate Spent
	 */
	public function calculateSpent() {
		$total = 0;

		foreach ( $this->tasks as $task ) {
			$total += $task->calculateSpent();
		}

		foreach ( $this->bugs as $bug ) {
			$total += $bug->calculateSpent();
		}

		return $total;
	}

	/**
	 * Store Categories
	 */
	protected function storeCategories() {
		if ( upstream_is_project_categorization_disabled() ) {
			return;
		}

		$res      = wp_set_object_terms( $this->id, $this->categoryIds, 'project_category' ); // phpcs:ignore
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

		// Phpcs ignore camelCase methods and object properties.
		// phpcs:disable

		if ( $this->clientId > 0 ) {
			update_post_meta( $this->id, '_upstream_project_client', $this->clientId );
		}
		if ( null !== $this->clientUserIds ) {
			update_post_meta( $this->id, '_upstream_project_client_users', $this->clientUserIds );
		}
		if ( null !== $this->statusCode ) {
			update_post_meta( $this->id, '_upstream_project_status', $this->statusCode );
		}
		if ( null !== $this->description ) {
			update_post_meta( $this->id, '_upstream_project_description', $this->description );
		}
		if ( count( $this->assignedTo ) > 0 ) {
			update_post_meta( $this->id, '_upstream_project_owner', $this->assignedTo[0] );
		}
		if ( null !== $this->startDate ) {
			update_post_meta( $this->id, '_upstream_project_start.YMD', $this->startDate );
		}
		if ( null !== $this->endDate ) {
			update_post_meta( $this->id, '_upstream_project_end.YMD', $this->endDate );
		}
		if ( null !== $this->startDate ) {
			update_post_meta( $this->id, '_upstream_project_start', UpStream_Model_Object::ymdToTimestamp( $this->startDate ) );
		}
		if ( null !== $this->endDate ) {
			update_post_meta( $this->id, '_upstream_project_end', UpStream_Model_Object::ymdToTimestamp( $this->endDate ) );
		}
		if ( null !== $this->progress ) {
			update_post_meta( $this->id, '_upstream_project_progress', $this->progress );
		}

		// phpcs:enable

		$items = array();
		foreach ( $this->tasks as $item ) {
			$r = array();
			$item->storeToArray( $r );
			$items[] = $r;
		}
		update_post_meta( $this->id, '_upstream_project_tasks', $items );

		$items = array();
		foreach ( $this->bugs as $item ) {
			$r = array();
			$item->storeToArray( $r );
			$items[] = $r;
		}
		update_post_meta( $this->id, '_upstream_project_bugs', $items );

		$items = array();
		foreach ( $this->files as $item ) {
			$r = array();
			$item->storeToArray( $r );
			$items[] = $r;
		}
		update_post_meta( $this->id, '_upstream_project_files', $items );

		$this->storeCategories();

		$project_object = new UpStream_Project( $this->id );
		$project_object->update_project_meta();
	}

	/**
	 * Has Meta Object
	 *
	 * @param  mixed $item item.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public function hasMetaObject( $item ) {
		if ( ! ( $item instanceof \UpStream_Model_Meta_Object ) ) {
			throw new UpStream_Model_ArgumentException( __( 'Argument must be of type UpStream_Model_Meta_Object', 'upstream' ) );
		} elseif ( $item instanceof UpStream_Model_Task ) {
			foreach ( $this->tasks() as $task ) {
				if ( $task->id === $item->id ) {
					return true;
				}
			}
		} elseif ( $item instanceof UpStream_Model_File ) {
			foreach ( $this->files() as $file ) {
				if ( $file->id === $item->id ) {
					return true;
				}
			}
		} elseif ( $item instanceof UpStream_Model_Bug ) {
			foreach ( $this->bugs() as $bug ) {
				if ( $bug->id === $item->id ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Add Meta Object
	 *
	 * @param  mixed $item item.
	 * @throws UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	public function addMetaObject( $item ) {
		if ( ! ( $item instanceof \UpStream_Model_Meta_Object ) ) {
			throw new UpStream_Model_ArgumentException( __( 'Can only add objects of type UpStream_Model_Meta_Object', 'upstream' ) );
		} elseif ( $item instanceof UpStream_Model_Task ) {
			$this->tasks[] = $item;
		} elseif ( $item instanceof UpStream_Model_File ) {
			$this->files[] = $item;
		} elseif ( $item instanceof UpStream_Model_Bug ) {
			$this->bugs[] = $item;
		}
	}

	/**
	 * Tasks
	 */
	public function &tasks() {
		return $this->tasks;
	}

	/**
	 * Bugs
	 */
	public function &bugs() {
		return $this->bugs;
	}

	/**
	 * Files
	 */
	public function &files() {
		return $this->files;
	}

	/**
	 * Add Task
	 *
	 * @param string $title title.
	 * @param string $created_by created_by.
	 */
	public function &addTask( $title, $created_by ) {
		$item          = \UpStream_Model_Task::create( $this, $title, $created_by );
		$this->tasks[] = $item;

		return $item;
	}

	/**
	 * Add Bug
	 *
	 * @param string $title title.
	 * @param string $created_by created_by.
	 */
	public function &addBug( $title, $created_by ) {
		$item         = \UpStream_Model_Bug::create( $this, $title, $created_by );
		$this->bugs[] = $item;

		return $item;
	}

	/**
	 * Add File
	 *
	 * @param string $title title.
	 * @param string $created_by created_by.
	 */
	public function &addFile( $title, $created_by ) {
		$item          = \UpStream_Model_File::create( $this, $title, $created_by );
		$this->files[] = $item;

		return $item;
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

			case 'status':
				$s = $this->getStatuses();

				foreach ( $s as $s_key => $s_value ) {
					if ( $this->statusCode === $s_key ) { // phpcs:ignore
						return $s_value;
					}
				}
				return '';

			case 'elapsedTime':
				return $this->calculateElapsedTime();
			case 'budgeted':
				return $this->calculateBudgeted();
			case 'spent':
				return $this->calculateSpent();

			case 'statusCode':
			case 'clientId':
			case 'clientUserIds':
			case 'memberUserIds':
			case 'startDate':
			case 'endDate':
			case 'categoryIds':
				return $this->{$property};

			case 'progress':
				return round( $this->{$property} );

			case 'categories':
				$categories = array();
				foreach ( $this->categoryIds as $tid ) { // phpcs:ignore
					$term         = get_term_by( 'id', $tid, 'project_category' );
					$categories[] = $term;
				}
				return $categories;

			case 'tasks':
			case 'bugs':
			case 'files':
				throw new UpStream_Model_ArgumentException( __( 'Not implemented. Use &tasks(), &files(), or &bugs().', 'upstream' ) );
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

			case 'categoryIds':
				if ( ! is_array( $value ) ) {
					$value = array( $value );
				}

				$category_ids = array();
				foreach ( $value as $tid ) {
					$term = get_term_by( 'id', $tid, 'project_category' );
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

			case 'status':
				$s  = $this->getStatuses();
				$sc = null;

				foreach ( $s as $s_key => $s_value ) {
					if ( $value === $s_value ) {
						$sc = $s_key;
						break;
					}
				}

				if ( null === $sc ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
						// translators: %s: status.
							__( 'Status %s is invalid.', 'upstream' ),
							$value
						)
					);
				}

				$this->statusCode = $sc; // phpcs:ignore

				break;

			case 'statusCode':
				$s  = $this->getStatuses();
				$sc = null;

				foreach ( $s as $s_key => $s_value ) {
					if ( $value === $s_key ) {
						$sc = $s_key;
						break;
					}
				}

				if ( null === $sc ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
						// translators: %s: status code.
							__( 'Status code %s is invalid.', 'upstream' ),
							$value
						)
					);
				}

				$this->statusCode = $sc; // phpcs:ignore

				break;

			case 'assignedTo':
			case 'assignedTo:byUsername':
			case 'assignedTo:byEmail':
				if ( is_array( $value ) && count( $value ) !== 1 ) {
					throw new UpStream_Model_ArgumentException( __( 'For projects, assignedTo must be an array of length 1.', 'upstream' ) );
				}

				parent::__set( $property, $value );
				break;

			case 'clientId':
				// this will throw a model exception if the client doesn't exist.
				$client         = \UpStream_Model_Manager::get_instance()->getByID( UPSTREAM_ITEM_TYPE_CLIENT, $value );
				$this->clientId = $client->id; // phpcs:ignore
				break;

			case 'clientUserIds':
				if ( 0 === $this->clientId ) { // phpcs:ignore
					throw new UpStream_Model_ArgumentException( __( 'Cannot assign client users if the project has no client.', 'upstream' ) );
				}

				if ( ! is_array( $value ) ) {
					throw new UpStream_Model_ArgumentException( __( 'Client user IDs must be an array.', 'upstream' ) );
				}

				if ( count( array_unique( $value ) ) !== count( $value ) ) {
						throw new UpStream_Model_ArgumentException( __( 'Input cannot contain duplicates.', 'upstream' ) );
				}

				$client      = \UpStream_Model_Manager::get_instance()->getByID( UPSTREAM_ITEM_TYPE_CLIENT, $this->clientId ); // phpcs:ignore
				$count_value = count( $value );
				for ( $i = 0; $i < $count_value; $i++ ) {
					if ( ! $client->includesUser( $value[ $i ] ) ) {
						throw new UpStream_Model_ArgumentException(
							sprintf(
							// translators: %s: user id.
								__( 'User ID %s does not exist in this client.', 'upstream' ),
								$value[ $i ]
							)
						);
					}
				}
				$this->clientUserIds = $value; // phpcs:ignore

				break;

			case 'startDate':
			case 'endDate':
				if ( ! self::isValidDate( $value ) ) {
					throw new UpStream_Model_ArgumentException( __( 'Argument is not a valid date of the form YYYY-MM-DD.', 'upstream' ) );
				}

				$this->{$property} = $value;
				break;

			default:
				parent::__set( $property, $value );
				break;

		}
	}

	/**
	 * Fields
	 */
	public static function fields() {
		$fields = parent::fields();

		$fields['statusCode']    = array(
			'type'       => 'select',
			'title'      => __( 'Status' ),
			'search'     => true,
			'display'    => true,
			'options_cb' => 'UpStream_Model_Project::getStatuses',
		);
		$fields['categoryIds']   = array(
			'type'       => 'select',
			'title'      => __( 'Categories' ),
			'search'     => true,
			'display'    => true,
			'options_cb' => 'UpStream_Model_Project::getCategories',
			'is_array'   => 'true',
		);
		$fields['clientUserIds'] = array(
			'type'     => 'user_id',
			'is_array' => true,
			'title'    => __( 'Selected Client Users' ),
			'search'   => true,
			'display'  => true,
		);
		$fields['memberUserIds'] = array(
			'type'     => 'user_id',
			'is_array' => true,
			'title'    => __( 'Members' ),
			'search'   => true,
			'display'  => true,
		);
		$fields['startDate']     = array(
			'type'    => 'date',
			'title'   => __( 'Start Date' ),
			'search'  => true,
			'display' => true,
		);
		$fields['endDate']       = array(
			'type'    => 'date',
			'title'   => __( 'End Date' ),
			'search'  => true,
			'display' => true,
		);
		$fields['progress']      = array(
			'type'    => 'number',
			'title'   => __( 'Progress (%)' ),
			'search'  => true,
			'display' => true,
		);

		$fields = self::customFields( $fields, UPSTREAM_ITEM_TYPE_PROJECT );

		return $fields;
	}


	/**
	 * Find Milestones
	 */
	public function findMilestones() {
		$posts = get_posts(
			array(
				'post_type'      => 'upst_milestone',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_key'       => 'upst_project_id',
				'meta_value'     => $this->id,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		);

		$milestones = array();

		foreach ( $posts as $post ) {
			$milestone    = \UpStream_Model_Manager::get_instance()->getByID(
				UPSTREAM_ITEM_TYPE_MILESTONE,
				$post->ID,
				UPSTREAM_ITEM_TYPE_PROJECT,
				$this->id
			);
			$milestones[] = $milestone;
		}

		return $milestones;
	}

	/**
	 * Get Categories
	 */
	public static function getCategories() {
		$tid_to_term = array();
		$terms       = get_terms(
			array(
				'taxonomy'   => 'project_category',
				'hide_empty' => true,
			)
		);

		foreach ( $terms as $term ) {
			$tid_to_term[ $term->term_id ] = $term->name;
		}

		return $tid_to_term;
	}

	/**
	 * Get Statuses
	 */
	public static function getStatuses() {
		$option   = get_option( 'upstream_projects' );
		$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';
		$array    = array();
		if ( $statuses ) {
			foreach ( $statuses as $status ) {
				if ( isset( $status['type'] ) ) {
					$array[ $status['id'] ] = $status['name'];
				}
			}
		}

		return $array;
	}

	/**
	 * Create
	 *
	 * @param  mixed $title title.
	 * @param  mixed $created_by created_by.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public static function create( $title, $created_by ) {
		if ( get_userdata( $created_by ) === false ) {
			throw new UpStream_Model_ArgumentException( __( 'User ID does not exist.', 'upstream' ) );
		}

		$item = new \UpStream_Model_Project( 0 );

		$item->title     = sanitize_text_field( $title );
		$item->createdBy = $created_by; // phpcs:ignore

		return $item;
	}

}
