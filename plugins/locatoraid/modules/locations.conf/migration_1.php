<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Conf_Migration_1_LC_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( ! $this->app->db->table_exists('conf') ){
			return;
		}

		$q = $this->app->db->query_builder();

		$q->where('name', 'address_format');
		$sql = $q->get_compiled_select('conf');
		$results = $this->app->db->query( $sql );

		$insert = array();
		foreach( $results as $row ){
			if( $row && $row['value'] ){
				$q->where('name', 'locations_address:format');
				$sql = $q->get_compiled_select('conf');
				$results2 = $this->app->db->query($sql);

				if( ! $results2 ){
					$insert = array(
						'name'	=> 'locations_address:format',
						'value'	=> $row['value'],
						);
				}
				break;
			}
		}

		if( $insert ){
			$q->set( $insert );
			$sql = $q->get_compiled_insert('conf');
			$this->app->db->query($sql);
		}
	}
}