<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Migration_1_LC_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( $this->app->db->table_exists('searchlog') ){
			return;
		}

		$dbforge = $this->app->db->dbforge();

		$dbforge->add_field(
			array(
				'id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
					),
				'ip_address' => array(
					'type' => 'VARCHAR(32)',
					'null' => FALSE,
					),
				'search_text' => array(
					'type' => 'VARCHAR(255)',
					'null' => TRUE,
					),
				'action_time' => array(
					'type' => 'INT',
					'null' => FALSE,
					),
				)
			);
		$dbforge->add_key('id', TRUE);
		$dbforge->create_table('searchlog');
	}
}