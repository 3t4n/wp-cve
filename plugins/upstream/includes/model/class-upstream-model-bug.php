<?php
/**
 * UpStream_Model_Bug
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
 * Class UpStream_Model_Bug
 */
class UpStream_Model_Bug extends UpStream_Model_Meta_Object {

	/**
	 * FileId
	 *
	 * @var int
	 */
	protected $fileId = 0; // phpcs:ignore

	/**
	 * SeverityCode
	 *
	 * @var undefined
	 */
	protected $severityCode = null; // phpcs:ignore

	/**
	 * StatusCode
	 *
	 * @var undefined
	 */
	protected $statusCode = null; // phpcs:ignore

	/**
	 * DueDate
	 *
	 * @var undefined
	 */
	protected $dueDate = null; // phpcs:ignore

	/**
	 * Reminders
	 *
	 * @var array
	 */
	protected $reminders = array();

	/**
	 * TimeRecords
	 *
	 * @var array
	 */
	protected $timeRecords = array(); // phpcs:ignore

	/**
	 * MetadataKey
	 *
	 * @var string
	 */
	protected $metadataKey = '_upstream_project_bugs'; // phpcs:ignore

	/**
	 * Type
	 *
	 * @var undefined
	 */
	protected $type = UPSTREAM_ITEM_TYPE_BUG;

	/**
	 * Constructor
	 *
	 * @param  mixed $parent parent.
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	public function __construct( $parent, $item_metadata ) {
		parent::__construct( $parent, $item_metadata );

		$this->type = UPSTREAM_ITEM_TYPE_BUG;
	}

	/**
	 * LoadFromArray
	 *
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	protected function loadFromArray( $item_metadata ) {
		parent::loadFromArray( $item_metadata );

		$this->statusCode   = ! empty( $item_metadata['status'] ) ? $item_metadata['status'] : null; // phpcs:ignore
		$this->severityCode = ! empty( $item_metadata['severity'] ) ? $item_metadata['severity'] : null; // phpcs:ignore
		$this->dueDate      = UpStream_Model_Object::loadDate( $item_metadata, 'due_date' ); // phpcs:ignore

		if ( ! empty( $item_metadata['file_id'] ) ) {
			$file = get_attached_file( $item_metadata['file_id'] );
			if ( false !== $file ) {
				$this->fileId = $item_metadata['file_id']; // phpcs:ignore
			}
		}

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
	 * StoreToArray
	 *
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	public function storeToArray( &$item_metadata ) {
		parent::storeToArray( $item_metadata );

		if ( null !== $this->statusCode ) { // phpcs:ignore
			$item_metadata['status'] = $this->statusCode; // phpcs:ignore
		}
		if ( null !== $this->severityCode ) { // phpcs:ignore
			$item_metadata['severity'] = $this->severityCode; // phpcs:ignore
		}
		if ( null !== $this->dueDate ) { // phpcs:ignore
			$item_metadata['due_date'] = UpStream_Model_Object::ymdToTimestamp( $this->dueDate ); // phpcs:ignore
		}
		if ( null !== $this->dueDate ) { // phpcs:ignore
			$item_metadata['due_date.YMD'] = $this->dueDate; // phpcs:ignore
		}

		if ( $this->fileId > 0 ) { // phpcs:ignore
			$url = wp_get_attachment_url( $this->fileId ); // phpcs:ignore

			if ( false !== $url ) {
				$item_metadata['file']    = $url;
				$item_metadata['file_id'] = $this->fileId; // phpcs:ignore
			}
		}

		$item_metadata['reminders'] = array();

		foreach ( $this->reminders as $reminder ) {
			$r = array();
			$reminder->storeToArray( $r );
			$item_metadata['reminders'][] = wp_json_encode( $r );
		}

		$item_metadata['time_records'] = array();

		foreach ( $this->timeRecords as $tr ) { // phpcs:ignore
			$r = array();
			$tr->storeToArray( $r );
			$item_metadata['records'][] = wp_json_encode( $r );
		}
	}

	/**
	 * CalculateElapsedTime
	 */
	public function calculateElapsedTime() {
		$total = 0;

		foreach ( $this->timeRecords as $tr ) { // phpcs:ignore
			$total += $tr->elapsedTime; // phpcs:ignore
		}

		return $total;
	}

	/**
	 * CalculateBudgeted
	 */
	public function calculateBudgeted() {
		$total = 0;

		foreach ( $this->timeRecords as $tr ) { // phpcs:ignore
			$total += $tr->budgeted;
		}

		return $total;
	}

	/**
	 * CalculateSpent
	 */
	public function calculateSpent() {
		$total = 0;

		foreach ( $this->timeRecords as $tr ) { // phpcs:ignore
			$total += $tr->spent;
		}

		return $total;
	}

	/**
	 * Get
	 *
	 * @param  mixed $property property.
	 */
	public function __get( $property ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'severity':
				$s = $this->getSeverities();

				foreach ( $s as $s_key => $s_value ) {
					if ( $this->severityCode === $s_key ) { // phpcs:ignore
						return $s_value;
					}
				}
				return '';

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

			case 'dueDate':
			case 'fileId':
			case 'timeRecords':
			case 'severityCode':
			case 'statusCode':
				return $this->{$property};

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

			case 'fileId':
				$file = get_attached_file( $value );
				if ( false === $file ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
							// translators: %s: file id.
							__( 'File ID %s is invalid.', 'upstream' ),
							$value
						)
					);
				}

				$this->fileId = $value; // phpcs:ignore
				break;

			case 'severity':
				$s  = $this->getSeverities();
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
							// translators: %s: Severity id.
							__( 'Severity %s is invalid.', 'upstream' ),
							$value
						)
					);
				}

				$this->severityCode = $sc; // phpcs:ignore
				break;

			case 'severityCode':
				$s  = $this->getSeverities();
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
							// translators: %s: Severity code.
							__( 'Severity code %s is invalid.', 'upstream' ),
							$value
						)
					);
				}

				$this->severityCode = $sc; // phpcs:ignore
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
							// translators: %s: Status name.
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
							// translators: %s: Status code.
							__( 'Status code %s is invalid.', 'upstream' ),
							$value
						)
					);
				}

				$this->statusCode = $sc; // phpcs:ignore
				break;

			case 'dueDate':
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

		$fields['statusCode']   = array(
			'type'       => 'select',
			'title'      => __( 'Status' ),
			'search'     => true,
			'display'    => true,
			'options_cb' => 'UpStream_Model_Bug::getStatuses',
		);
		$fields['severityCode'] = array(
			'type'       => 'select',
			'title'      => __( 'Severity' ),
			'search'     => true,
			'display'    => true,
			'options_cb' => 'UpStream_Model_Bug::getSeverities',
		);
		$fields['dueDate']      = array(
			'type'    => 'date',
			'title'   => __( 'Due Date' ),
			'search'  => true,
			'display' => true,
		);

		$fields = self::customFields( $fields, UPSTREAM_ITEM_TYPE_BUG );

		return $fields;
	}

	/**
	 * GetStatuses
	 */
	public static function getStatuses() {
		$option   = get_option( 'upstream_bugs' );
		$statuses = isset( $option['statuses'] ) ? $option['statuses'] : '';
		$array    = array();
		if ( $statuses ) {
			foreach ( $statuses as $status ) {
				if ( isset( $status['name'] ) ) {
					$array[ $status['id'] ] = $status['name'];
				}
			}
		}

		return $array;
	}

	/**
	 * GetSeverities
	 */
	public static function getSeverities() {
		$option     = get_option( 'upstream_bugs' );
		$severities = isset( $option['severities'] ) ? $option['severities'] : '';
		$array      = array();
		if ( $severities ) {
			foreach ( $severities as $severity ) {
				if ( isset( $severity['name'] ) ) {
					$array[ $severity['id'] ] = $severity['name'];
				}
			}
		}

		return $array;
	}

	/**
	 * Create
	 *
	 * @param  mixed $parent parent.
	 * @param  mixed $title title.
	 * @param  mixed $created_by created_by.
	 */
	public static function create( $parent, $title, $created_by ) {
		$item_metadata = array(
			'title'      => $title,
			'created_by' => $created_by,
		);

		return new self( $parent, $item_metadata );
	}

}
