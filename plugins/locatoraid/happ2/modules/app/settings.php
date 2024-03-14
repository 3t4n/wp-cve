<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Settings_HC_MVC extends _HC_MVC
{
	private $db = NULL;
	private $config_loader = NULL;
	private $settings = array();
	private $defaults = array();

	public function single_instance()
	{
	}

	public function set_config_loader( $config_loader )
	{
		$this->settings = array();
		$this->defaults = array();

		$settings = $config_loader->get('settings');
// _print_r( $settings );
		$this->settings = array_merge( $this->settings, $settings );
		$this->defaults = array_merge( $this->defaults, $settings );
	}

	public function is_modified( $pname = NULL ){
		$return = TRUE;
		if( $pname !== NULL ){
			if( isset($this->settings[$pname]) && isset($this->defaults[$pname]) && ($this->settings[$pname] == $this->defaults[$pname]) ){
				$return = FALSE;
			}
		}
		return $return;
	}

	public function reload()
	{
		if( ! $this->db ){
			return $return;
		}

		$settings = $this->_get_all();

		foreach( $settings as $k => $v ){
			if( 
				array_key_exists($k, $this->settings) && 
				is_array($this->settings[$k])
				){
				if( is_array($v) ){
					$this->settings[$k] = $v;
				}
				else {
					$this->settings[$k] = array($v);
				}
			}
			else {
				$this->settings[$k] = $v;
			}
		}
	}

	public function set_db( $db )
	{
		$this->db = $db;
		return $this->reload();
	}

	public function get( $pname = NULL )
	{
		$return = NULL;
		if( $pname === NULL ){
			$pnames = array_keys($this->settings);
			$return = array();
			foreach( $pnames as $pname2 ){
				$return[ $pname2 ] = $this->get( $pname2 );
			}
		}
		else {
			if( 'translate:' == substr($pname, 0, strlen('translate:')) ){
				$pname = str_replace( ' ', '_', $pname );
			}

			if( isset($this->settings[$pname]) ){
				$return = $this->settings[$pname];
			}

			// $wpret = get_option( 'locatoraid_' . $pname );
			// if( false !== $wpret ){
				// $return = $wpret;
			// }
		}

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $pname )
			;

		return $return;
	}

	public function get_default( $pname = NULL )
	{
		$return = NULL;
		if( $pname === NULL ){
			$return = $this->defaults;
		}
		else {
			if( isset($this->defaults[$pname]) ){
				$return = $this->defaults[$pname];
			}
		}
		return $return;
	}

	public function set( $pname, $pvalue )
	{
		$this->_save( $pname, $pvalue );
		return $this;
	}

	public function reset( $pname )
	{
// delete_option( 'locatoraid_' . $pname );
		return $this->_delete( $pname );
	}

	private function _get_all( )
	{
		$return	= array();
		if( ! $this->db ){
			return $return;
		}

		if( ! $this->db->table_exists('conf') ){
			return $return;
		}

		$q = $this->db->query_builder();
		$q->select( array('name', 'value') );
		$sql = $q->get_compiled_select('conf');
		$results = $this->db->query($sql);

		foreach( $results as $i ){
			if( isset($return[$i['name']]) ){
				if( ! is_array($return[$i['name']]) )
					$return[$i['name']] = array( $return[$i['name']] );
				if( ! in_array($i['value'], $return[$i['name']]) )
					$return[$i['name']][] = $i['value'];
			}
			else {
				$return[$i['name']] = $i['value'];
			}
		}
		return $return;
	}

	private function _save( $pname, $pvalue )
	{
		$return	= TRUE;
// update_option( 'locatoraid_' . $pname, $pvalue );
// return $return;


		if( ! $this->db ){
			return $return;
		}

		$q = $this->db->query_builder();

		if( is_array($pvalue) ){

			$q
				->where('name', $pname)
				->select( array('name', 'value') )
				;
			$sql = $q->get_compiled_select('conf');

			$results = $this->db->query( $sql );

			$current = array();
			foreach($results as $i){
				$current[] = $i['value'];
			}

			$to_delete = array_diff( $current, $pvalue );
			$to_add = array_diff( $pvalue, $current );
			foreach( $to_add as $v ){
				$item = array(
					'name'	=> $pname,
					'value'	=> $v
					);
				$q->set( $item );
				$sql = $q->get_compiled_insert('conf');
				$this->db->query( $sql );
			}
			foreach( $to_delete as $v ){
				$q
					->where( 'name', $pname )
					->where( 'value', $v )
					;
				$sql = $q->get_compiled_delete('conf');
				$this->db->query( $sql );
			}
		}
		else
		{
			$q->where('name', $pname);
			$sql = $q->get_compiled_select('conf');

			$exists = $this->db->query($sql);

			if( $exists ){
				$item = array(
					'value'	=> $pvalue
					);
				$q
					->set( $item )
					->where( 'name', $pname )
					;
				$sql = $q->get_compiled_update('conf');
			}
			else {
				$item = array(
					'name'	=> $pname,
					'value'	=> $pvalue
					);
				$q->set( $item );
				$sql = $q->get_compiled_insert('conf');
			}

			$this->db->query( $sql );
		}
	}

	private function _delete( $pname )
	{
// delete_option( 'locatoraid_' . $pname );
		$return	= TRUE;
		if( ! $this->db ){
			return $return;
		}

		$q = $this->db->query_builder();

		$q->where('name', $pname);
		$sql = $q->get_compiled_delete('conf');
		$this->db->query( $sql );
	}
}