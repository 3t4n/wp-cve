<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
// add meta fields
class ORMRelations_Migration_2_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( $this->app->db->field_exists('meta1', 'relations') ){
			return;
		}

		$dbforge = $this->app->db->dbforge();

		for( $ii = 1; $ii <= 3; $ii++ ){
			$dbforge->add_column(
				'relations',
				array(
					'meta' . $ii => array(
						'type'	=> 'VARCHAR(64)',
						'null'	=> TRUE,
						),
					)
				);
		}
	}
}