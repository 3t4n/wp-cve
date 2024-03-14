<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Commands_Manager_HC_MVC extends _HC_MVC
{
	protected $errors = array();
	protected $results = array();
	protected $before = array();

	public function single_instance()
	{
	}

	public function set_errors( $command_obj, $errors )
	{
		$key = $this->_key( $command_obj );
		$this->errors[ $key ] = $errors;
		return $this;
	}

	public function set_results( $command_obj, $results )
	{
		$key = $this->_key( $command_obj );
		$this->results[ $key ] = $results;
		return $this;
	}

	public function set_before( $command_obj, $before )
	{
		$key = $this->_key( $command_obj );
		$this->before[ $key ] = $before;
		return $this;
	}

	public function errors( $command_obj )
	{
		$return = NULL;

		$key = $this->_key( $command_obj );
		if( array_key_exists($key, $this->errors) ){
			$return = $this->errors[ $key ];
		}

		return $return;
	}

	public function results( $command_obj )
	{
		$return = NULL;

		$key = $this->_key( $command_obj );
		if( array_key_exists($key, $this->results) ){
			$return = $this->results[ $key ];
		}

		return $return;
	}

	public function before( $command_obj )
	{
		$return = NULL;

		$key = $this->_key( $command_obj );
		if( array_key_exists($key, $this->before) ){
			$return = $this->before[ $key ];
		}

		return $return;
	}

	protected function _key( $command_obj )
	{
		$return = get_class($command_obj);
		return $return;
	}
}