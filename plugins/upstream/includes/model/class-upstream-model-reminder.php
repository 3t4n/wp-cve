<?php
/**
 * UpStream_Model_Reminder
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
 * Class UpStream_Model_Reminder
 */
class UpStream_Model_Reminder {

	/**
	 * Id
	 *
	 * @var int
	 */
	public $id = 0;

	/**
	 * Interval Id
	 *
	 * @var int
	 */
	public $intervalId = 0; // phpcs:ignore

	/**
	 * Timestamp
	 *
	 * @var int
	 */
	public $timestamp = 0;

	/**
	 * Sent At
	 *
	 * @var int
	 */
	public $sentAt = 0; // phpcs:ignore


	/**
	 * Construct
	 *
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	public function __construct( $item_metadata ) {
		$this->loadFromArray( $item_metadata );
	}

	/**
	 * Load From Array
	 *
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	protected function loadFromArray( $item_metadata ) { // phpcs:ignore
		// Phpcs ignore camelCase methods and object properties.
		// phpcs:disable
		$this->id         = ! empty( $item_metadata['id'] ) ? $item_metadata['id'] : 0;
		$this->intervalId = ! empty( $item_metadata['reminder'] ) && $item_metadata['reminder'] <= 1000 ? $item_metadata['reminder'] : 0;
		$this->timestamp  = ! empty( $item_metadata['reminder'] ) && $item_metadata['reminder'] > 1000 ? $item_metadata['reminder'] : 0;
		$this->sentAt     = ! empty( $item_metadata['sent_at'] ) && null !== $item_metadata['sent_at'] ? $item_metadata['sent_at'] : 0;
		// phpcs:enable
	}

	/**
	 * Store To Array
	 *
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	public function storeToArray( &$item_metadata ) { // phpcs:ignore
		if ( ! empty( $this->id ) ) {
			$item_metadata['id'] = $this->id;
		}
		if ( $this->intervalId > 0 ) { // phpcs:ignore
			$item_metadata['reminder'] = $this->intervalId; // phpcs:ignore
		}
		if ( $this->timestamp > 0 ) {
			$item_metadata['reminder'] = $this->timestamp;
		}
		if ( $this->sentAt > 0 ) { // phpcs:ignore
			$item_metadata['sent']    = true;
			$item_metadata['sent_at'] = $this->sentAt; // phpcs:ignore
		} else {
			$item_metadata['sent'] = false;
		}
	}
}
