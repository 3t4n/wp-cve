<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Migration_3_LC_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( $this->app->db->field_exists('priority', 'locations') ){
			return;
		}

		$dbforge = $this->app->db->dbforge();

		$dbforge->add_column(
			'locations',
			array(
				'priority' => array(
					'type' 		=> 'INT',
					'null'		=> FALSE,
					'default'	=> 0
					),
				)
			);
	}

	public function down()
	{
	}
}