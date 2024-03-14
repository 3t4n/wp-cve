<?php

/* http://codex.wordpress.org/Function_Reference/wp_insert_user
 * When performing an update operation, user_pass should be the hashed password and not the plain text password
 'ID' - if updating
	'user_email' => $user_email,
	'user_login' => $user_login,
	'user_pass' => $user_pass,
	'role' => $role,
	'first_name' => $user_pass,
	'last_name' => $user_pass,
	'dislay_name' =>
	'user_url' =>
	'user_registered' => $user_registered,
	'display_name' => $display_name,
 */

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\User;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Create extends LegacyApi\Internal\Base {

	public function process() :LegacyApi\ApiResponse {

		$params = $this->getActionParams();
		$user = $params[ 'user' ];
		if ( $user[ 'role' ] == 'default' ) {
			$user[ 'role' ] = get_option( 'default_role' );
		}

		$mNewUserId = $this->loadWpUsers()->createUser(
			$user,
			isset( $params[ 'send_notification' ] ) && $params[ 'send_notification' ]
		);

		if ( is_wp_error( $mNewUserId ) ) {
			return $this->fail( 'Could not create user with error: '.$mNewUserId->get_error_message() );
		}

		return $this->success( [
			'new_user_id'   => $mNewUserId,
			'new_user_data' => $user,
		] );
	}
}