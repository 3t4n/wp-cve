<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;

require_once ABSPATH . WPINC . '/user.php';

class Delete_User_Endpoint extends Abstract_Endpoint {

	public function callback( $data )
	{
		$user_id = intval($data['user_id']);
		$reassign_to = intval(get_current_user_id());

		if ($user_id === $reassign_to) {
			$this->output(['error' => __('You can not delete yourself.', 'controlled-admin-access')]);
		}

		$this->output([
			'deleted' => wp_delete_user($user_id, $reassign_to),
		]);
	}

	public function action()
	{
		return 'caa_delete_user';
	}
}
