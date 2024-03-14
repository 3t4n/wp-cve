<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;


use WPRuby_CAA\Core\Dto\User;

class Bulk_Actions_Endpoint extends Abstract_Endpoint {

	public function callback( $data ) {
		$action = sanitize_text_field($data['bulk_action']);
		$user_ids = array_map(function ($user) {
			return intval($user);
		}, $data['users']);

		switch ($action) {
			case 'delete':
				$this->deleteUsers($user_ids);
				break;
			case 'activate':
				$this->activateUsers($user_ids);
				break;
			case 'deactivate':
				$this->deactivateUsers($user_ids);
				break;
		}

		$this->output(['success' => true]);
	}

	public function action()
	{
		return 'caa_bulk_action';
	}

	private function deleteUsers($user_ids)
	{
		foreach ($user_ids as $user_id) {
			if ($user_id === get_current_user_id()) continue;
			wp_delete_user($user_id);
		}
	}

	private function activateUsers($user_ids)
	{
		foreach ($user_ids as $user_id) {
			if ($user_id === get_current_user_id()) continue;
			$user = new User($user_id);
			$user->setIsDeactivated(false);
		}
	}

	private function deactivateUsers($user_ids)
	{
		foreach ($user_ids as $user_id) {
			if ($user_id === get_current_user_id()) continue;
			$user = new User($user_id);
			$user->setIsDeactivated(true);
		}
	}
}
