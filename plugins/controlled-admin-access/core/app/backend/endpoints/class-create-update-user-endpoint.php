<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;

use WPRuby_CAA\Core\Dto\User;
use WPRuby_CAA\Core\Helpers\Random_String_Generator;

class Create_Update_User_Endpoint extends Abstract_Endpoint {

	public function callback( $data )
	{
		$data = $data['user'];

		$user_id = intval($data['id']);

		$action = ($user_id === -1)? 'created': 'updated';


		$user_email = sanitize_email( $data['email'] );
		$user_login = $user_email;

		$restricted_menu_items = array_map(
			function ($menu_item) {
				return sanitize_text_field($menu_item);
			},
			$data['restricted_menu_items']
		);


		$userdata = [
			'user_login' => $user_login,
			'user_email' => $user_email,
			'role'		=> 'administrator'
		];

		if ($action === 'created') {
			$userdata['user_pass'] = (isset($data['password'])) ? $data['password']: (new Random_String_Generator())->generate(14);
		} else {
			if(isset($data['password']) && strlen(trim($data['password'])) > 0) {
				$userdata['user_pass'] = $data['password'];
			}
		}

		if ($action === 'created') {
			$user = wp_insert_user(  $userdata  );
		} else {
			$userdata['ID'] = $user_id;
			$user = wp_update_user(  $userdata  );
		}

		if ($user instanceof \WP_Error) {
			$this->output(['errors' => $user->errors]);
		}

		$userObject = new User($user);
		$userObject->setRestrictedMenu($restricted_menu_items);
		$userObject->setExpiringIn(intval($data['expiring_in']));
		$userObject->setIsAdminBarHidden($data['hide_admin_bar']);
		$userObject->setIsCaaAccount(true);
		$userObject->setCreatedBy(get_current_user_id());
		$userObject->setCreatedAt(time());

		$this->output(['user' => $userObject->toArray()]);
	}

	public function action()
	{
		return 'caa_create_update_user';
	}
}
