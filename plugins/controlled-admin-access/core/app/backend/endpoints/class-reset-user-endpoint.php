<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;


use WPRuby_CAA\Core\Dto\User;

class Reset_User_Endpoint extends Abstract_Endpoint {

	public function callback( $data ) {
		$user_id = intval($data['user_id']);

		$user = new User($user_id);

		$user->reset_user();

		$this->ok();
	}

	public function action() {
		return 'caa_reset_user';
	}
}
