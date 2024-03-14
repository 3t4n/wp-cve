<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Conf_Migration_1_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( $this->app->db->table_exists('conf') ){
			return;
		}

		$dbforge = $this->app->db->dbforge();

	// conf
		$dbforge->add_field(
			array(
				'id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
					),
				'name' => array(
					'type' => 'VARCHAR(255)',
					'null' => FALSE,
					),
				'value' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					),
				)
			);
		$dbforge->add_key('id', TRUE);
		$dbforge->create_table('conf');
	}
}