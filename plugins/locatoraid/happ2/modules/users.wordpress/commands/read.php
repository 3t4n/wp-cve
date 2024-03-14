<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_Wordpress_Commands_Read_HC_MVC extends _HC_MVC
{
	static $query_cache = array();
	protected $use_cache = TRUE;

	public function which()
	{
		$return = array(
			// array('id', '>', 1),
			// array('status', 'IN', array(1, 2))
		);

		$return = array();
		return $return;
	}

	public function execute( $args = array() )
	{
		$return = array();

		if( ! is_array($args) ){
			$args = array( $args );
		}

		if( $this->use_cache ){
			$cache_key = json_encode( $args );
			if( isset(self::$query_cache[$cache_key]) ){
				// echo "ON CACHE: '$cache_key'<br>";
				$return = self::$query_cache[$cache_key];
				return $return;
			}
		}

		$wp_args = array();
		$return_one = FALSE;

		reset( $args );
		foreach( $args as $arg ){
		// id is supplied
			if( ! is_array($arg) ){
				$return_one = TRUE;
				break;
			}
		}

		$wp_role_mapping = $this->app->make('/acl.wordpress/roles')
			->roles_mapping()
			;

		$wp_role_in = array();

	// check if we have 'roles' argument
		$want_bits = 0;
		for( $ii = 0; $ii < count($args); $ii++ ){
			if( ! is_array($args[$ii]) ){
				continue;
			}
			if( $args[$ii][0] != 'roles' ){
				continue;
			}
		// already set
			if( count($args[$ii]) != 2 ){
				continue;
			}

			$want_roles = $args[$ii][1];
			$acl = $this->app->make('/acl/roles');
			$want_bits = $acl->get_bits( $want_roles );
		}

	// include only those that can access our plugin
		reset( $wp_role_mapping );
		foreach( $wp_role_mapping as $wp_role => $our_bits ){
			if( ! $our_bits ){
				continue;
			}
			if( $want_bits && ( ! ($want_bits & $our_bits) ) ){
				continue;
			}

			$wp_role_in[] = $wp_role;
		}
 
		if( $want_bits && (! $wp_role_in) ){
			return $return;
		}

		if( $wp_role_in ){
			$wp_args['role__in'] = $wp_role_in;
		}

		// $allowed_compares = array('=', '<>', '>=', '<=', '>', '<', 'IN', 'NOTIN', 'LIKE', '&');
		$allowed_compares = array('=', 'IN', 'NOTIN', 'LIKE', '&');
		reset( $args );
		foreach( $args as $arg ){
		// id is supplied
			if( ! is_array($arg) ){
				$k = 'id';
				$v = $arg;
				$compare = '=';
			}
			else {
				if( count($arg) == 3 ){
					list( $k, $compare, $v ) = $arg;
					$compare = strtoupper( $compare );
				}
				else {
					list( $k, $v ) = $arg;
					$compare = '=';
				}
			}

			if( ! in_array($compare, $allowed_compares) ){
				echo "COMPARING BY '$compare' IS NOT ALLOWED!<br>";
				exit;
			}

			switch( $k ){
				case 'limit':
					if( $v == 1 ){
						$return_one = TRUE;
					}
					break;

				case 'id':
					if( ! is_array($v) ){
						$v = array($v);
					}

					switch( $compare ){
						case '=':
						case 'IN':
							if( ! isset($wp_args['include']) ){
								$wp_args['include'] = array();
							}
							$wp_args['include'] = array_merge( $wp_args['include'], $v );
							break;

						case 'NOTIN':
							if( ! isset($wp_args['exclude']) ){
								$wp_args['exclude'] = array();
							}
							$wp_args['exclude'] = array_merge( $wp_args['exclude'], $v );
							break;
					}
					break;
			}
		}

		$wp_users = get_users( $wp_args );

		foreach( $wp_users as $userdata ){
			$array = $this->_from_userdata( $userdata );
			$array['_wp_userdata'] = $userdata;
			$id = $array['id'];

		// set role bits
			$our_roles_bits = 0;

			if( isset($userdata->roles) ){
				$wp_roles = $userdata->roles;
				reset( $wp_roles );
				foreach( $wp_roles as $wp_role ){
					if( isset($wp_role_mapping[$wp_role]) ){
						$this_bits = $wp_role_mapping[$wp_role];
						$our_roles_bits = $our_roles_bits | $this_bits;
					}
				}
			}
			$array['roles'] = $our_roles_bits;

		// return
			if( $return_one ){
				$return = $array;
				break;
			}
			else {
				$return[ $id ] = $array;
			}
		}

		$return = $this->app
			->after( $this, $return )
			;

		if( $this->use_cache ){
			self::$query_cache[$cache_key] = $return;
		}

		return $return;
	}

	private function _from_userdata( $userdata )
	{
		$return = array(
			'id'			=> $userdata->ID,
			'email'			=> $userdata->user_email,
			'display_name'	=> $userdata->display_name,
			'username'		=> $userdata->user_login,
			);
		return $return;
	}
}