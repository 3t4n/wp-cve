<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class ORMRelations_Migration_1_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( $this->app->db->table_exists('relations') ){
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
				'from_id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'unsigned' => TRUE, 
					),
				'to_id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'unsigned' => TRUE, 
					),
				'relation_name' => array(
					'type' => 'VARCHAR(64)',
					'null' => TRUE,
					),
				)
			);

		$dbforge->add_key('id', TRUE);
		$dbforge->create_table('relations');

		$sql = 'ALTER TABLE ' . $this->app->db->prefix() . 'relations' . ' ADD INDEX (`relation_name`)';
		$this->app->db->query($sql);
		$sql = 'ALTER TABLE ' . $this->app->db->prefix() . 'relations' . ' ADD INDEX (`from_id`)';
		$this->app->db->query($sql);
		$sql = 'ALTER TABLE ' . $this->app->db->prefix() . 'relations' . ' ADD INDEX (`to_id`)';
		$this->app->db->query($sql);
	}
}