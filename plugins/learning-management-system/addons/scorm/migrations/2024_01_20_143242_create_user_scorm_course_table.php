<?php
/**
 * Create user scorm course table.
 *
 * @since 1.8.3
 */

use Masteriyo\Database\Migration;

class CreateUserScormCourseTable extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since 1.8.3
	 */
	public function up() {
		$sql = "CREATE TABLE {$this->prefix}masteriyo_user_scorm_course (
			id BIGINT UNSIGNED AUTO_INCREMENT,
			user_course_id BIGINT NOT NULL,
			parameter varchar(45) NOT NULL,
			value TEXT NOT NULL,
			PRIMARY KEY  (id)
		) $this->charset_collate;";

		dbDelta( $sql );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since 1.8.3
	 */
	public function down() {
		$this->connection->query( "DROP TABLE IF EXISTS {$this->prefix}masteriyo_user_scorm_course;" );
	}
}
