<?php
/**
 * UpStream_Model_Task
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
 * Class UpStream_Model_Task
 */
class UpStream_Model_Task extends UpStream_Model_Meta_Object {

	/**
	 * Status Code
	 *
	 * @var undefined
	 */
	protected $statusCode = null; // phpcs:ignore

	/**
	 * Progress
	 *
	 * @var int
	 */
	protected $progress = 0;

	/**
	 * Milestone Id
	 *
	 * @var int
	 */
	protected $milestoneId = 0; // phpcs:ignore

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
	 * Notes
	 *
	 * @var string
	 */
	protected $notes = '';

	/**
	 * Reminders
	 *
	 * @var array
	 */
	protected $reminders = array();

	/**
	 * Time Records
	 *
	 * @var array
	 */
	protected $timeRecords = array(); // phpcs:ignore

	/**
	 * Metadata Key
	 *
	 * @var string
	 */
	protected $metadataKey = '_upstream_project_tasks'; // phpcs:ignore

	/**
	 * Type
	 *
	 * @var undefined
	 */
	protected $type = UPSTREAM_ITEM_TYPE_TASK;


	/**
	 * Construct
	 *
	 * @param  mixed $parent Parent.
	 * @param  mixed $item_metadata Item Metadata.
	 * @return void
	 */
	public function __construct( $parent, $item_metadata ) {
		parent::__construct( $parent, $item_metadata );
	}

	/**
	 * Load From Array
	 *
	 * @param  mixed $item_metadata Item Metadata.
	 * @return void
	 */
	protected function loadFromArray( $item_metadata ) {
		parent::loadFromArray( $item_metadata );

		$this->statusCode  = ! empty( $item_metadata['status'] ) ? $item_metadata['status'] : null; // phpcs:ignore
		$this->progress    = ! empty( $item_metadata['progress'] ) ? $item_metadata['progress'] : 0;
		$this->startDate   = UpStream_Model_Object::loadDate( $item_metadata, 'start_date' ); // phpcs:ignore
		$this->endDate     = UpStream_Model_Object::loadDate( $item_metadata, 'end_date' ); // phpcs:ignore
		$this->milestoneId = ! empty( $item_metadata['milestone'] ) ? $item_metadata['milestone'] : null; // phpcs:ignore
		$this->notes       = ! empty( $item_metadata['notes'] ) ? $item_metadata['notes'] : '';

		if ( ! empty( $item_metadata['reminders'] ) ) {
			foreach ( $item_metadata['reminders'] as $reminder_data ) {
				$exception = null;
				try {
					$d                 = json_decode( $reminder_data, true );
					$reminder          = new UpStream_Model_Reminder( $d );
					$this->reminders[] = $reminder;
				} catch ( \Exception $e ) {
					$exception = $e; // don't add anything else.
				}
			}
		}

		if ( ! empty( $item_metadata['records'] ) ) {
			foreach ( $item_metadata['records'] as $tr_data ) {
				$exception = null;
				try {
					$d                   = json_decode( $tr_data, true );
					$time_record         = new UpStream_Model_TimeRecord( $d );
					$this->timeRecords[] = $time_record; // phpcs:ignore
				} catch ( \Exception $e ) {
					$exception = $e; // don't add anything else.
				}
			}
		}

	}

	/**
	 * Store To Array
	 *
	 * @param  mixed $item_metadata Item Metadata.
	 * @return void
	 */
	public function storeToArray( &$item_metadata ) {
		parent::storeToArray( $item_metadata );

		// Phpcs ignore camelCase methods and object properties.
		// phpcs:disable
		if ( null !== $this->statusCode ) {
			$item_metadata['status'] = $this->statusCode;
		}
		if ( $this->progress >= 0 ) {
			$item_metadata['progress'] = $this->progress;
		}
		if ( null !== $this->startDate ) {
			$item_metadata['start_date'] = UpStream_Model_Object::ymdToTimestamp( $this->startDate );
		}
		if ( null !== $this->endDate ) {
			$item_metadata['end_date'] = UpStream_Model_Object::ymdToTimestamp( $this->endDate );
		}
		if ( null !== $this->startDate ) {
			$item_metadata['start_date.YMD'] = $this->startDate;
		}
		if ( null !== $this->endDate ) {
			$item_metadata['end_date.YMD'] = $this->endDate;
		}
		if ( null !== $this->notes ) {
			$item_metadata['notes'] = $this->notes;
		}
		if ( $this->milestoneId > 0 ) {
			$item_metadata['milestone'] = $this->milestoneId;
		}
		// phpcs:enable

		$item_metadata['reminders'] = array();

		foreach ( $this->reminders as $reminder ) {
			$r = array();
			$reminder->storeToArray( $r );
			$item_metadata['reminders'][] = json_encode( $r );
		}

		$item_metadata['time_records'] = array();

		foreach ( $this->timeRecords as $tr ) { // phpcs:ignore
			$r = array();
			$tr->storeToArray( $r );
			$item_metadata['records'][] = json_encode( $r );
		}
	}

	/**
	 * Calculate Elapsed Time
	 */
	public function calculateElapsedTime() {
		$total = 0;

		foreach ( $this->timeRecords as $tr ) { // phpcs:ignore
			$total += $tr->elapsedTime; // phpcs:ignore
		}

		return $total;
	}

	/**
	 * Calculate Budgeted
	 */
	public function calculateBudgeted() {
		$total = 0;

		foreach ( $this->timeRecords as $tr ) { // phpcs:ignore
			$total += $tr->budgeted;
		}

		return $total;
	}

	/**
	 * Calculate Spent
	 */
	public function calculateSpent() {
		$total = 0;

		foreach ( $this->timeRecords as $tr ) { // phpcs:ignore
			$total += $tr->spent;
		}

		return $total;
	}

	/**
	 * Get Milestone
	 */
	public function getMilestone() {
		if ( $this->milestoneId ) { // phpcs:ignore
			$exception = null;
			try {
				return \UpStream_Model_Manager::get_instance()->getByID(
					UPSTREAM_ITEM_TYPE_MILESTONE,
					$this->milestoneId, // phpcs:ignore
					UPSTREAM_ITEM_TYPE_PROJECT,
					$this->parent->id
				);
			} catch ( \Exception $e ) {
				$exception = $e;
			}
		}

		return null;
	}

	/**
	 * Set Milestone
	 *
	 * @param  mixed $milestone Milestone.
	 * @return void
	 */
	public function setMilestone( $milestone ) {
		$this->milestoneId = $milestone->id; // phpcs:ignore
	}

	/**
	 * Get
	 *
	 * @param  mixed $property Property.
	 */
	public function __get( $property ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'milestone':
				return $this->getMilestone();

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
			case 'notes':
			case 'milestoneId':
			case 'progress':
			case 'timeRecords':
			case 'startDate':
			case 'endDate':
				return $this->{$property};

			default:
				return parent::__get( $property );
		}
	}

	/**
	 * Set
	 *
	 * @param  mixed $property Property.
	 * @param  mixed $value Value.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public function __set( $property, $value ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'milestone':
				if ( ! $value instanceof UpStream_Model_Milestone ) {
					throw new UpStream_Model_ArgumentException( __( 'Argument must be of type milestone.', 'upstream' ) );
				} elseif ( 0 === $value->id ) {
					throw new UpStream_Model_ArgumentException( __( 'Milestone must be stored before setting.', 'upstream' ) );
				}

				return $this->setMilestone( $value );

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

			case 'notes':
				$this->notes = wp_kses_post( $value );
				break;

			case 'milestoneId':
				$milestone         = UpStream_Model_Manager::get_instance()->getByID( UPSTREAM_ITEM_TYPE_MILESTONE, $value, UPSTREAM_ITEM_TYPE_PROJECT, $this->parent->id );
				$this->milestoneId = $milestone->id; // phpcs:ignore
				break;

			case 'progress':
				if ( ! filter_var( $value, FILTER_VALIDATE_INT ) || (int) $value < 0 || (int) $value > 100 || ( (int) $value ) % 5 !== 0 ) {
					throw new UpStream_Model_ArgumentException( __( 'Argument must be a multiple of 5 between 0 and 100.', 'upstream' ) );
				}

				$this->{$property} = $value;
				break;

			case 'startDate':
			case 'endDate':
				if ( ! self::isValidDate( $value ) ) {
					throw new UpStream_Model_ArgumentException( __( 'Argument is not a valid date of the form YYYY-MM-DD.', 'upstream' ) );
				}

				$this->{$property} = $value;
				break;

			case 'timeRecords':
				if ( ! is_array( $value ) ) {
					throw new UpStream_Model_ArgumentException( __( 'Argument must be an array of UpStream_Model_TimeRecord objects.', 'upstream' ) );
				}

				foreach ( $value as $item ) {
					if ( ! $item instanceof UpStream_Model_TimeRecord ) {
						throw new UpStream_Model_ArgumentException( __( 'Argument must be an array of UpStream_Model_TimeRecord objects.', 'upstream' ) );
					}
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

		$fields['notes']      = array(
			'type'    => 'text',
			'title'   => __( 'Notes' ),
			'search'  => true,
			'display' => true,
		);
		$fields['startDate']  = array(
			'type'    => 'date',
			'title'   => __( 'Start Date' ),
			'search'  => true,
			'display' => true,
		);
		$fields['endDate']    = array(
			'type'    => 'date',
			'title'   => __( 'End Date' ),
			'search'  => true,
			'display' => true,
		);
		$fields['statusCode'] = array(
			'type'       => 'select',
			'title'      => __( 'Status' ),
			'search'     => true,
			'display'    => true,
			'options_cb' => 'UpStream_Model_Task::getStatuses',
		);
		$fields['progress']   = array(
			'type'    => 'number',
			'title'   => __( 'Progress (%)' ),
			'search'  => true,
			'display' => true,
		);

		unset( $fields['description'] );

		$fields = self::customFields( $fields, UPSTREAM_ITEM_TYPE_TASK );

		return $fields;
	}

	/**
	 * Get Statuses
	 */
	public static function getStatuses() {
		$option   = get_option( 'upstream_tasks' );
		$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';
		$array    = array();
		if ( $statuses ) {
			foreach ( $statuses as $status ) {
				$array[ $status['id'] ] = $status['name'];
			}
		}

		return $array;
	}

	/**
	 * Create
	 *
	 * @param  mixed $parent Parent.
	 * @param  mixed $title Title.
	 * @param  mixed $created_by Created By.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public static function create( $parent, $title, $created_by ) {
		if ( get_userdata( $created_by ) === false ) {
			throw new UpStream_Model_ArgumentException( __( 'User ID does not exist.', 'upstream' ) );
		}

		$item_metadata =
			array(
				'title'      => sanitize_text_field( $title ),
				'created_by' => $created_by,
			);

		return new self( $parent, $item_metadata );
	}

}
