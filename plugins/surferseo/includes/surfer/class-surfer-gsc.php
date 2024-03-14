<?php
/**
 *  Object that exports content to Surfer.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer;

use SurferSEO\Surfer\GSC\{
	Surfer_GSC_Common,
	Surfer_GSC_Notifications,
	Surfer_GSC_Drop_Monitor,
	Surfer_GSC_Data_Migration,
	Surfer_GSC_Posts_List
};


/**
 * Content exporter object.
 */
class Surfer_GSC {

	use Surfer_GSC_Common;

	/**
	 * Notifications
	 *
	 * @var Surfer_GSC_Notifications
	 */
	private $notifications = null;

	/**
	 * Drop monitor
	 *
	 * @var Surfer_GSC_Drop_Monitor
	 */
	private $drop_monitor = null;

	/**
	 * Data Migration
	 *
	 * @var Surfer_GSC_Data_Migration
	 */
	private $data_migration = null;

	/**
	 * Posts List
	 *
	 * @var Surfer_GSC_Posts_List
	 */
	private $posts_list = null;


	/**
	 * Object construct.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );

		$this->notifications  = new Surfer_GSC_Notifications();
		$this->drop_monitor   = new Surfer_GSC_Drop_Monitor();
		$this->data_migration = new Surfer_GSC_Data_Migration();
		$this->posts_list     = new Surfer_GSC_Posts_List();
	}

	/**
	 * Init function.
	 */
	public function init() {
	}


	/**
	 * Checks if any admin has disabled GSC column.
	 *
	 * @return array
	 */
	public function check_if_admin_hide_gsc_column() {
		$args  = array(
			'role'    => 'Administrator',
			'orderby' => 'user_nicename',
			'order'   => 'ASC',
		);
		$users = get_users( $args );

		foreach ( $users as $user ) {
			$user_meta = maybe_unserialize( get_user_meta( $user->ID, 'manageedit-postcolumnshidden', true ) );

			if ( is_array( $user_meta ) && in_array( 'surfer_gsc_traffic_data', $user_meta, true ) ) {
				$hidden[] = true;
			} else {
				$hidden[] = false;
			}
		}

		return $hidden;
	}

	/**
	 * Migrates data to new format
	 *
	 * @return void
	 */
	public function transfer_gsc_data_to_new_format() {
		$this->data_migration->transfer_gsc_data_to_new_format();
	}
}
