<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_WordPress_Conf_Migration_1_HC_MVC extends _HC_MVC
{
	public function up()
	{
		if( ! $this->app->db->table_exists('conf') ){
			return;
		}

		$q = $this->app->db->query_builder();

		$sql = $q->get_compiled_select('conf');
		$results = $this->app->db->query($sql);

		$current_conf = array();
		foreach( $results as $row ){
			if( substr($row['name'], 0, strlen('wordpress_users:role_')) != 'wordpress_users:role_' ){
				continue;
			}
			$current_conf[ $row['name'] ] = $row['value'];
		}

		$change_to = array();

		reset( $current_conf );
		foreach( $current_conf as $k => $v ){
			switch( $v ){
				case 'none':
					$change_to[$k] = 0;
					break;
				case 'admin':
					$change_to[$k] = 1;
					break;
			}
		}

		reset( $change_to );
		foreach( $change_to as $k => $v ){
			$item = array(
				'value'	=> $v
				);
			$q
				->set( $item )
				->where('name', $k)
				;
			$sql = $q->get_compiled_update('conf');
			$this->app->db->query( $sql );
		}
	}

	public function down()
	{
	}
}