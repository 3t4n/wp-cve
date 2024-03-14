<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;

use WPRuby_CAA\Core\Dto\User;

class Deactivate_User_Endpoint extends Abstract_Endpoint {

	public function callback( $data )
	{
		$user_id = intval($data['user_id']);

		if ($user_id === intval(get_current_user_id())) {
			$this->output(['error' => __('You can not deactivate yourself.', 'controlled-admin-access')]);
		}

		$user = new User($user_id);
		$user->setIsDeactivated(true);

		$this->output([
			'deactivated' => true,
		]);
	}

	public function action()
	{
		return 'caa_deactivate_user';
	}
}
