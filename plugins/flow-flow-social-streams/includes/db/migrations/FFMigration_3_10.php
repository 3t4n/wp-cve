<?php namespace flow\db\migrations;
if ( ! defined( 'WPINC' ) ) die;

use flow\db\FFDB;
use flow\db\LADBManager;
use flow\db\SafeMySQL;
use la\core\db\migrations\ILADBMigration;

/**
 * Flow-Flow
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
class FFMigration_3_10 implements ILADBMigration {

	public function version() {
		return '3.10';
	}

	/**
	 * @param SafeMySQL $conn
	 * @param LADBManager $manager
	 */
	public function execute( $conn, $manager ) {
		if (!FFDB::existColumn($manager->cache_table_name, 'boosted')){
			$conn->query('ALTER TABLE ?n ADD ?n VARCHAR(4) DEFAULT \'nope\'', $manager->cache_table_name, 'boosted');
		}
	}
}