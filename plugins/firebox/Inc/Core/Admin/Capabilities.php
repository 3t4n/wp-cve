<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Admin;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Capabilities
{
	public function __construct()
	{
		$this->setup();
	}

	private function setup()
	{
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		$capabilities = [];

		// get all CPTs
		$cpts = [
			'firebox' => [
				'singular' => 'firebox',
				'plural' => 'fireboxes'
			]
		];

		foreach ($cpts as $name => $data)
		{
			$singular = $data['singular'];
			$plural = $data['plural'];

			$capabilities[strtolower($name)] = [
				'edit_post'      		 => "edit_$singular",
				'read_post'      		 => "read_$singular",
				'delete_post'        	 => "delete_$singular",
				'edit_posts'         	 => "edit_$plural",
				'edit_others_posts'  	 => "edit_others_$plural",
				'publish_posts'      	 => "publish_$plural",
				'read_private_posts'     => "read_private_$plural",
				'read'                   => "read_$singular",
				'delete_posts'           => "delete_$plural",
				'delete_private_posts'   => "delete_private_$plural",
				'delete_published_posts' => "delete_published_$plural",
				'delete_others_posts'    => "delete_others_$plural",
				'edit_private_posts'     => "edit_private_$plural",
				'edit_published_posts'   => "edit_published_$plural",
				'create_posts'           => "edit_$plural",
			];

			// also add taxonomies
			if (isset($data['taxonomies']) && is_array($data['taxonomies']) && count($data['taxonomies']))
			{
				foreach ($data['taxonomies'] as $tax)
				{
					$tmp_tax = $name . '_' . $tax;
					
					$capabilities[strtolower($name)]["manage_{$name}_{$tmp_tax}_terms"] = "manage_{$name}_{$tmp_tax}_terms";
					$capabilities[strtolower($name)]["edit_{$name}_{$tmp_tax}_terms"] = "edit_{$name}_{$tmp_tax}_terms";
					$capabilities[strtolower($name)]["assign_{$name}_{$tmp_tax}_terms"] = "assign_{$name}_{$tmp_tax}_terms";
					$capabilities[strtolower($name)]["delete_{$name}_{$tmp_tax}_terms"] = "delete_{$name}_{$tmp_tax}_terms";
				}
			}
		}

		//Set capabilities to all roles with cap:"".
		$roles = $wp_roles->get_names();

		// add capabilities to all roles
		foreach ( $capabilities as $name => $capabilities) {
			$capabilities_data = array_values($capabilities);
			
			// all roles
			foreach ( $roles as $current_role => $role_name ) {
				if ( isset( $wp_roles->roles[ $current_role ][ 'capabilities' ][ 'manage_options' ] ) ) {
					$role = get_role( $current_role );
					if ( isset( $role ) && is_object( $role ) ) {
						for ( $i = 0, $caps_limit = count( $capabilities_data ); $i < $caps_limit; $i ++ ) {
							if ( ! isset( $wp_roles->roles[ $current_role ][ 'capabilities' ][ $capabilities_data[ $i ] ] ) ) {
								$role->add_cap( $capabilities_data[ $i ] );
							}
						}
					}

				}
			}

			// all admins
			$user_admins = get_users( array(
				'role' => 'administrator'
			) );

			if ( is_multisite() ) {
				$super_admins = get_super_admins();

				foreach( $super_admins as $admin ) {
					$super_admin = new \WP_User( $admin );

					if ( ! in_array( $super_admin, $user_admins, true ) ) {
						$user_admins[] = $super_admin;
					}
				}
			}

			foreach ( $user_admins as $user ) {
				if ( $user->exists() ) {
					for ( $i = 0, $caps_limit = count( $capabilities_data ); $i < $caps_limit; $i ++ ) {
						$user->add_cap( $capabilities_data[ $i ] );
					}
				}
			}
		}
	}
}