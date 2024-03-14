<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Acl_Roles_HC_MVC extends _HC_MVC
{
	public function has_role( $user_id, $check_role, $on = NULL )
	{
		$return = FALSE;
		$bits = 0;

		if( $on ){
		}
		else {
			if( $user_id ){
				$user = $this->app->make('/users/commands/read')
					->execute( array($user_id) )
					;
				if( $user && isset($user['roles']) ){
					$bits = $user['roles'];
				}
			}
		}

		$user_roles = $this->get_roles( $bits );
		if( in_array($check_role, $user_roles) ){
			$return = TRUE;
		}

		return $return;
	}

	public function roles()
	{
		$return = array(
			'admin'		=> __( 'Administrator', 'locatoraid' ),
			);

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}

	public function get_bits( $role_names )
	{
		$return = 0;
		if( ! is_array($role_names) ){
			$role_names = array( $role_names );
		}

		$roles = $this->roles();
		$keys = array_keys( $roles );

		$masks = array();
		for( $bit = 0; $bit < count($keys); $bit++ ){
			$masks[ $keys[$bit] ] = pow(2, $bit);
		}

		$role_names = array_unique( $role_names );
		reset( $role_names );
		foreach( $role_names as $role_name ){
			if( ! array_key_exists($role_name, $masks) ){
				continue;
			}
			$return += $masks[$role_name];
		}

		return $return;
	}

	public function get_roles( $bits )
	{
		$roles = $this->roles();
		$keys = array_keys( $roles );

		$return = array();
		for( $ii = 0; $ii < count($keys); $ii++ ){
			if( $bits & pow(2, $ii) ){
				$return[] = $keys[$ii];
			}
		}
		return $return;
	}

	public function render( $roles )
	{
		$this_roles = $this->get_roles( $roles );

		if( $this_roles ){
			$roles = $this->roles();
			$out = array();
			reset( $this_roles );
			foreach( $this_roles as $tr ){
				$out[] = $roles[$tr];
			}
			$out = join(', ', $out);
		}
		else {
			$out = array();

			$out[] = $this->app->make('/html/element')->tag('span')
				->add( $this->app->make('/html/icon')->icon('exclamation') )
				->add_attr('class', 'hc-red')
				;

			$out[] = __('No Role', 'locatoraid');
			$out = join('', $out);
		}

		return $out;
	}
}