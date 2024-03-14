<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_Wordpress_Index_View_HC_MVC extends _HC_MVC
{
	public function render( $entries )
	{
		if( ! $entries ){
			return;
		}

		$header = $this->header();

		$rows = array();
		reset( $entries );
		foreach( $entries as $e ){
			$rows[ $e['id'] ] = $this->row( $e );
		}

		$out = $this->app->make('/html/table-responsive')
			->set_header( $header )
			->set_rows( $rows )
			;

		return $out;
	}

	public function header()
	{
		$return = array();

		$return['wp_user']	= $this->app->make('/html/icon')->icon('wordpress') . __('Username') . ' / ' . __('Role') . ' / ' . __('ID');
		$return['roles']	= __('Plugin Role', 'locatoraid');

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function row( $e )
	{
		$row = array();

		$wp_roles = $e['_wp_userdata']->roles;

		$wp_roles_obj = new WP_Roles();
		$wordpress_roles_names = $wp_roles_obj->get_names();

		$wp_roles_view = array();
		reset( $wp_roles );
		foreach( $wp_roles as $wp_role ){
			$wp_role_name = isset($wordpress_roles_names[$wp_role]) ? $wordpress_roles_names[$wp_role] : $wp_role;
			$wp_roles_view[] = $wp_role_name;
		}

		$wp_roles_view = join(', ', $wp_roles_view);
		$wp_roles_view = $this->app->make('/html/element')->tag('span')
			->add( $wp_roles_view )
			->add_attr('class', 'hc-muted2')
			->add_attr('class', 'hc-fs2')
			;

		$id_view = $this->app->make('/html/element')->tag('span')
			->add_attr('class', 'hc-fs2')
			->add_attr('class', 'hc-muted2')
			->add( $e['id'] )
			;

		$wp_details_view = array();
		$wp_details_view[] = $wp_roles_view;
		$wp_details_view[] = $id_view;
		$wp_details_view = join( ' / ', $wp_details_view );

		$row['wp_user'] = $this->app->make('/html/list')
			->add( $e['username'] )
			->add( $wp_details_view )
			;

	// roles
		$role_manager = $this->app->make('/acl/roles');
		$row['roles_view'] = $role_manager->render( $e['roles'] );

		$return = $this->app
			->after( array($this, __FUNCTION__), $row, $e )
			;

		return $return;
	}
}