<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Migration_2_LC_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( $this->app->db->field_exists('latitude', 'locations') ){
			return;
		}

		$dbforge = $this->app->db->dbforge();
		$dbforge->add_column(
			'locations',
			array(
				'latitude' => array(
					'type' 	=> 'DOUBLE',
					'null'	=> TRUE,
					),
				'longitude' => array(
					'type' 	=> 'DOUBLE',
					'null'	=> TRUE,
					),
				)
			);
	}

	public function down()
	{
	}
}