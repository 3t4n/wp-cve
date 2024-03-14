<?php

namespace WPRuby_CAA\Core\Migrations;


use WPRuby_CAA\Core\Dto\User;

class Migration_200 implements Interface_Migration
{

	public function migrate() {

		$this->migrate_old_user_metadata();
		return true;

	}

	private function migrate_old_user_metadata()
	{
		$caaOldUsers = get_users( 'orderby=ID&meta_key=caa_account&meta_value=true' );
		/** @var \WP_User $user */
		foreach ($caaOldUsers as $user){
			$newUser = new User($user->ID);

			// user expiring
			$userExpiring = get_user_meta($user->ID, 'caa_user_expiring', true);
			if ($userExpiring > 0) {
				if ($userExpiring == 15) {
					$userExpiring = 14; // to map to two weeks.
				}
				$userExpiring = $userExpiring * 24; // convert from days to hours.
			}
			$newUser->setExpiringIn($userExpiring);

			// is caa account
			$isCaaAccount = get_user_meta($user->ID, 'caa_account', true);
			$newUser->setIsCaaAccount($isCaaAccount === 'true');

			// account created date
			$caaCreated = get_user_meta($user->ID, 'caa_created', true);
			$newUser->setCreatedAt($caaCreated);

			// is deactivated
			$isDeactivated = get_user_meta($user->ID, 'caa_deactivated', true);
			$newUser->setIsDeactivated($isDeactivated === 'true');

			// hide admin bar
			$hideAdminBar = get_user_meta($user->ID, 'caa_hide_admin_bar', true);
			$newUser->setIsAdminBarHidden($hideAdminBar);


			$newUser->setCreatedBy(get_current_user_id());

			$mainMenu = get_user_meta($user->ID, 'caa_main_menu', true);
			$subMenu = get_user_meta($user->ID, 'caa_sub_menu', true);

			$restricted_items = is_array($mainMenu) ? $mainMenu : [];

			if (is_array($subMenu)) {
				$restricted_items = array_merge($subMenu, $restricted_items);
			}

			$newUser->setRestrictedMenu($restricted_items);

		}
	}

	/**
	 * @return mixed
	 */
	public function version() {
		return '2.0.0';
	}
}
