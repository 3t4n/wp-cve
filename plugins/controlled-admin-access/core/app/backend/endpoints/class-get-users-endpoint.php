<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;


use WPRuby_CAA\Core\Dto\User;

class Get_Users_Endpoint extends Abstract_Endpoint {

	public function callback( $data )
	{
		$admins = get_users( [
			'role__in' => [ 'administrator' ],
			'fields' => ['ID',  'user_email'],
			'orderby' => 'ID',
			'order' => 'DESC'
		] );

		$users = [];

		foreach ($admins as $admin) {

			$user = new User($admin->ID);
			$user->setEmail($admin->user_email);

			$users[] = $user->toArray();
		}

		$this->output($users);
	}

	public function action()
	{
		return 'caa_get_users';
	}
}
