<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\User;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Enumerate extends LegacyApi\Internal\Base {

	public function process() :LegacyApi\ApiResponse {
		if ( !\function_exists( 'get_users' ) ) {
			include( ABSPATH.'wp-includes/user.php' );
		}
		return $this->success( [
			'wpusers'  => $this->enumUsers(),
			'wp_roles' => $this->enumRoles(),
		] );
	}

	/**
	 * @return array[]
	 */
	private function enumUsers() :array {
		$fields = [
			'ID',
			'user_login',
			'display_name',
			'user_email',
			'user_registered',
			'roles'
		];

		$query = [];
		if ( !empty( $this->getActionParam( 'role' ) ) ) {
			$query[ 'role__in' ] = [ $this->getActionParam( 'role' ) ];
		}

		$users = [];
		foreach ( get_users( $query ) as $user ) {
			/** @var \WP_User $user */
			$data = [];
			foreach ( $fields as $file ) {
				$data[ $file ] = $user->{$file};
			}
			$users[ $user->ID ] = $data;
		}

		return $users;
	}

	/**
	 * @return string[]
	 */
	private function enumRoles() :array {
		global $wp_roles;
		return \array_map(
			function ( $aRoleAttr ) {
				return $aRoleAttr[ 'name' ];
			},
			\is_object( $wp_roles ) ? $wp_roles->roles : []
		);
	}
}