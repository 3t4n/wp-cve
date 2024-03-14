<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_Wordpress_Conf_View_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$can_edit = FALSE;
		$wp_always_admin = $this->app->make('/acl.wordpress/roles')->always_admin();
		$wp_user = wp_get_current_user();
		if( array_intersect($wp_always_admin, (array) $wp_user->roles) ){
			$can_edit = TRUE;
		}
		// $can_edit = FALSE;

		// $values = $this->app->make('/app/settings')->get();
		$wp_roles_mapping = $this->app->make('/acl.wordpress/roles')->roles_mapping();
		$values = array();
		foreach( $wp_roles_mapping as $k => $v ){
			$this_k = 'wordpress_users:role_' . $k;
			$values[ $this_k ] = $v;
		}

		if( $can_edit ){
			$form = $this->app->make('/users.wordpress.conf/form');

			$helper = $this->app->make('/form/helper');
			$inputs_view = $helper->prepare_render( $form->inputs(), $values );
		}

		// $can_edit = FALSE;

		$rm = $this->app->make('/acl/roles');
		$our_roles = $rm->roles();

		$out_inputs = $this->app->make('/html/table-responsive')
			->set_no_footer(TRUE)
			;

		$header = array();
		$header['wp_role'] = $this->app->make('/html/icon')->icon('wordpress') . __('Role');
		$header['our_role'] = __('Plugin Role', 'locatoraid');

		$wp_roles = new WP_Roles();
		$wordpress_roles = $wp_roles->get_names();
		$wordpress_count_users = count_users();

		$rows = array();
		reset( $wordpress_roles );
		foreach( $wordpress_roles as $role_value => $role_name ){
			$this_row = array();

			$wp_role_view = $role_name;
			$this_role_count = ( isset($wordpress_count_users['avail_roles'][$role_value]) ) ? $wordpress_count_users['avail_roles'][$role_value] : 0;
			$wp_role_view .= ' [' . $this_role_count . ']';

			if( $this_role_count > 0 ){
				$wp_role_view = $this->app->make('/html/element')->tag('span')
					->add( $wp_role_view )
					->add_attr('class', 'hc-bold')
					;
			}

			$this_row['wp_role'] = $wp_role_view;

			$this_field_pname = 'wordpress_users:role_' . $role_value;
			if( $can_edit ){
				$this_row['our_role'] = $inputs_view[$this_field_pname];
			}
			else {
				$this_row['our_role'] = $rm->render( $values[$this_field_pname] );
			}

			$rows[] = $this_row;
		}

		$out_inputs
			->set_header( $header )
			->set_rows( $rows )
			;

		$help = __('Set how WordPress users can work with this plugin.', 'locatoraid');

		$out_inputs = $this->app->make('/html/list')
			->set_gutter(2)
			->add( $help )
			->add( $out_inputs )
			;

		if( $can_edit ){
			$out_buttons = $this->app->make('/html/list')
				->set_gutter(2)
				->add(
					$this->app->make('/html/element')->tag('input')
						->add_attr('type', 'submit')
						->add_attr('title', __('Save', 'locatoraid') )
						->add_attr('value', __('Save', 'locatoraid') )
						->add_attr('class', 'hc-theme-btn-submit')
						->add_attr('class', 'hc-theme-btn-primary')
						->add_attr('class', 'hc-block')
				);

			$link = $this->app->make('/http/uri')
				->url( '/users.wordpress-conf/update' )
				;
			$out = $helper
				->render( array('action' => $link) )
				->add( 
					$this->app->make('/html/grid')
						->set_gutter(2)
						->add( $out_inputs, 9, 12 )
						->add( $out_buttons, 3, 12 )
					)
				;
		}
		else {
			$out = $out_inputs;
		}

		return $out;
	}
}