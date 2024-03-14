<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Setup_Model_HC_MVC extends _HC_MVC
{
	public function get_old_query_builder()
	{
		$q = $this->app->db->query_builder();

		$old_version = $this->get_old_version();

		$dbprefix_version = isset($this->app->app_config['dbprefix_version']) ? $this->app->app_config['dbprefix_version'] : '';
		$current_prefix = $this->app->db->prefix();
		$core_dbprefix = substr( $current_prefix, 0, -strlen($dbprefix_version)-1 );
		$old_prefix = strlen($old_version) ? $core_dbprefix . $old_version . '_' :  $core_dbprefix;

		$q->set_prefix( $old_prefix );
		return $q;
	}

	public function get_old_version()
	{
		$return = NULL;

		$dbprefix_version = isset($this->app->app_config['dbprefix_version']) ? $this->app->app_config['dbprefix_version'] : '';
		$dbprefix = $this->app->db->prefix();

		if( strlen($dbprefix_version) ){
			$core_dbprefix = substr( $dbprefix, 0, -strlen($dbprefix_version)-1 );
			$old_prefixes = array();

			$my_version = substr($dbprefix_version, 1);
			$old_version = $my_version - 1;
			while( $old_version >= 1 ){
				$old_prefixes[] = 'v' . $old_version;
				$old_version--;
			}
			$old_prefixes[] = '';

			foreach( $old_prefixes as $op ){
				$test_prefix = strlen($op) ? $core_dbprefix . $op . '_' :  $core_dbprefix;
				$this->app->db->set_prefix( $test_prefix );

				if( $this->app->db->table_exists('conf') ){
					$return = $op;
					break;
				}
			}
		}

		$this->app->db->set_prefix( $dbprefix );
		return $return;
	}
}