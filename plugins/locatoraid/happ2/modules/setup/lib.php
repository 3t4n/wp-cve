<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Setup_Lib_HC_MVC extends _HC_MVC
{
	public function is_setup()
	{
		if( ! isset($this->app->db) ){
			$return = FALSE;
			return $return;
		}

	// check if not setup then don't run bootstraps
		$prefix = $this->app->db->prefix();
		$tables = $this->app->db->list_tables();

		$my_tables = array();
		foreach( $tables as $tbl ){
			if( substr($tbl, 0, strlen($prefix)) == $prefix ){
				$my_tbl = substr($tbl, strlen($prefix));
				$my_tables[$my_tbl] = $my_tbl;
			}
		}

		unset( $my_tables['migrations'] );
		if( $my_tables ){
			$return = TRUE;
		}
		else {
			$return = FALSE;
		}

		return $return;
	}
}
