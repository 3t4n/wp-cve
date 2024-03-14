<?php namespace flow\db\migrations;
use flow\db\FFDB;
use la\core\db\migrations\ILADBMigration;

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
class FFMigration_2_13 implements ILADBMigration{

	public function version() {
		return '2.13';
	}

	public function execute($conn, $manager) {
		if (!FFDB::existColumn($manager->posts_table_name, 'post_additional')){
			$conn->query("ALTER TABLE ?n ADD COLUMN ?n VARCHAR(300)", $manager->posts_table_name, 'post_additional');
		}
	}
}