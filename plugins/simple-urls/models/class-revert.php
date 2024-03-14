<?php
/**
 * Models
 *
 * @package Models
 */

namespace LassoLite\Models;

/**
 * Model
 */
class Revert extends Model {
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'lasso_lite_revert';

	/**
	 * Columns of the table
	 *
	 * @var array
	 */
	protected $columns = array(
		'id',
		'lasso_id',
		'post_data',
		'old_uri',
		'plugin',

		'revert_dt',
	);

	/**
	 * Primary key of the table
	 *
	 * @var string
	 */
	protected $primary_key = 'id';

	/**
	 * Create table
	 */
	public function create_table() {
		$columns_sql = '
			id bigint(10) NOT NULL AUTO_INCREMENT,
			lasso_id bigint(20) NOT NULL,
			post_data text NULL,
			old_uri varchar(500) NOT NULL,
			plugin varchar(200) NOT NULL,
			revert_dt datetime NOT NULL,
			PRIMARY KEY  (id)
		';
		$sql         = '
			CREATE TABLE ' . $this->get_table_name() . ' (
				' . $columns_sql . '
			) ' . $this->get_charset_collate();

		return $this->modify_table( $sql, $this->get_table_name() );
	}

	/**
	 * Update DB structure and data for v228
	 */
	public function update_for_v228() {
		// ? change datetime column to not null
		$query = '
			ALTER TABLE ' . $this->get_table_name() . ' 
				CHANGE `revert_dt` `revert_dt` DATETIME NOT NULL
		';
		self::query( $query );
	}
}
