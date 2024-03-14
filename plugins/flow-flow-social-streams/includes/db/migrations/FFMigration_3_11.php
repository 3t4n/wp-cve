<?php namespace flow\db\migrations;

use flow\db\FFDB;
use flow\db\LADBManager;
use flow\db\SafeMySQL;
use la\core\db\migrations\ILADBMigration;

if ( ! defined( 'WPINC' ) ) die;

/**
 * Flow-Flow
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
class FFMigration_3_11 implements ILADBMigration {

	public function version() {
		return '3.11';
	}

	/**
	 * @param SafeMySQL $conn
	 * @param LADBManager $manager
	 */
	public function execute( $conn, $manager ) {
		if (!FFDB::existColumn($manager->cache_table_name, 'send_email')){
			$conn->query('ALTER TABLE ?n ADD ?n INT DEFAULT 0 NOT NULL', $manager->cache_table_name, 'send_email');
		}
	}
}