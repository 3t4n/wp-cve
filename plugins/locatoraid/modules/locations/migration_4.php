<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Migration_4_LC_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( $this->app->db->field_exists('mapicon', 'locations') ){
			return;
		}

		$dbforge = $this->app->db->dbforge();

		$dbforge->add_column(
			'locations',
			array(
				'mapicon' => array(
					'type' 		=> 'VARCHAR(255)',
					'null'		=> TRUE,
					'default'	=> ''
					),
				)
			);
	}

	public function down()
	{
	}
}