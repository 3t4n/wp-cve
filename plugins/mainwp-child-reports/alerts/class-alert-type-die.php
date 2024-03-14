<?php
/**
 * Used for debugging.
 *
 * @package WP_MainWP_Stream
 */

namespace WP_MainWP_Stream;

/**
 * Class Alert_Type_Die
 *
 * @package WP_MainWP_Stream
 *
 * @uses \WP_MainWP_Stream\Alert_Type
 */
class Alert_Type_Die extends Alert_Type {

	/**
	 * Alert type name
	 *
	 * @var string
	 */
	public $name = 'Die Notifier';

	/**
	 * Alert type slug
	 *
	 * @var string
	 */
	public $slug = 'die';

	/**
	 * Triggers a script exit when an alert is triggered. Debugging use only.
	 *
	 * @param int   $record_id Record that triggered notification.
	 * @param array $recordarr Record details.
	 * @param array $options Alert options.
	 * @return void
	 */
	public function alert( $record_id, $recordarr, $options ) {
		echo '<pre>';
		print_r( $recordarr ); // @codingStandardsIgnoreLine debug not loaded in production
		echo '</pre>';
		die( 'You have been notified!' );
	}
}
