<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Migration_1_LC_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( $this->app->db->table_exists('locations') ){
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
				'name' => array(
					'type' => 'VARCHAR(100)',
					'null' => FALSE,
					),
				'street1' => array(
					'type' => 'VARCHAR(255)',
					),
				'street2' => array(
					'type' => 'VARCHAR(255)',
					),
				'city' => array(
					'type' => 'VARCHAR(50)',
					'null' => TRUE,
					),
				'state' => array(
					'type' => 'VARCHAR(20)',
					'null' => TRUE,
					),
				'zip' => array(
					'type' => 'VARCHAR(20)',
					'null' => TRUE,
					),
				'country' => array(
					'type' => 'VARCHAR(50)',
					'null' => TRUE,
					),

				'phone' => array(
					'type' => 'VARCHAR(30)',
					'null' => TRUE,
					),
				'website' => array(
					'type' => 'VARCHAR(100)',
					'null' => TRUE,
					),
				'priority' => array(
					'type' 		=> 'INT',
					'null'		=> FALSE,
					'default'	=> 0
					),
				'mapicon' => array(
					'type' 		=> 'VARCHAR(255)',
					'null'		=> TRUE,
					'default'	=> ''
					),
				)
			);
		$dbforge->add_key('id', TRUE);
		$dbforge->create_table('locations');
	}
}