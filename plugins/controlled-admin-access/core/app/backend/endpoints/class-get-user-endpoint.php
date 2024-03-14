<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;


use WPRuby_CAA\Core\Dto\User;

class Get_User_Endpoint extends Abstract_Endpoint {

	public function callback( $data )
	{
		$user_id = intval($data['user_id']);
		$user = new User($user_id);
		$this->output($user->toArray());
	}

	public function action()
	{
		return 'caa_get_user';
	}
}
