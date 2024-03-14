<?php namespace flow\db\migrations;
use flow\db\FFDB;
use la\core\db\migrations\ILADBMigration;
use flow\db\LADBManager;
use flow\db\SafeMySQL;

if ( ! defined( 'WPINC' ) ) die;
/**
 * Flow-Flow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
class FFMigration_2_18 implements ILADBMigration{

	public function version() {
		return '2.18';
	}

	/**
	 * @param SafeMySQL $conn
	 * @param LADBManager $manager
	 */
	public function execute( $conn, $manager ) {
		if (!FFDB::existColumn($manager->cache_table_name, 'system_enabled')){
			$sql = "ALTER TABLE ?n ADD COLUMN ?n TINYINT(1) DEFAULT 1";
			$conn->query($sql, $manager->cache_table_name, 'system_enabled');
		}
	}
}