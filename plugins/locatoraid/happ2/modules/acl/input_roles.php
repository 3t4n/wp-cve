<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Acl_Input_Roles_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	protected $readonly_options = array();

	public function set_readonly_options( $readonly_options )
	{
		$this->readonly_options = $readonly_options;
		return $this;
	}

	public function render( $name, $value = NULL )
	{
	// convert bits to role names
		$role_manager = $this->app->make('/acl/roles');

		$value = $role_manager->get_roles( $value );

		$options = $role_manager->roles();
		$input = $this->app->make('/form/checkbox-set')
			->set_options( $options )
			;

		if( $this->readonly_options ){
			$input
				->set_readonly_options( $this->readonly_options )
				;
		}

		$return = $input
			->render( $name, $value )
			;

		return $return;
	}

	public function grab( $name, $post )
	{
		$return = $this->app->make('/form/checkbox-set')
			->grab( $name, $post )
			;

	// convert role names to bits
		$role_manager = $this->app->make('/acl/roles');
		$return = $role_manager->get_bits( $return );
		return $return;
	}
}