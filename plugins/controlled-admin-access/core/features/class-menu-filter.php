<?php

namespace WPRuby_CAA\Core\Features;


use WPRuby_CAA\Core\Dto\User;

class Menu_Filter {

	protected static $_instance = null;

	public static function boot()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
		add_filter('parent_file', [$this, 'filter_the_menu'], 99, 1);
		add_filter('admin_head',[$this, 'hide_admin_bar_in_admin_area'], 10);
		add_filter('wp_head', [$this, 'hide_admin_bar_in_admin_area'], 10);
	}

	public function filter_the_menu($parent_file)
	{
		global $menu, $submenu;
		$user = new User(get_current_user_id());
		$restricted_menu = $user->getRestrictedMenu();

		if (! $user->isCaaAccount()) {
			return $parent_file;
		}

		$restricted_menu[] = 'controlled-admin-access';
		foreach ($menu as $id => $item) {
			if (in_array($item[2], $restricted_menu)) {
				unset($menu[$id]);
			}

			if (!empty($submenu[$item[2]])) {
				$this->filter_sub_menu($item[2] , $user);
			}
		}

		return $parent_file;
	}


	/**
	 * @param $parent
	 * @param User $user
	 */
	private function filter_sub_menu($parent, $user) {
		global $submenu;
		$user_settings_sub = $user->getRestrictedMenu();

		$user_settings_sub[] = 'users-user-role-editor.php';

		foreach ($submenu[$parent] as $id => $item) {
			if (in_array($item[2], $user_settings_sub)) {
				unset($submenu[$parent][$id]);
			}
		}

	}

	public function hide_admin_bar_in_admin_area()
	{
		$user = new User(get_current_user_id());

		if ($user->getId() === 0) {
			return;
		}

		if ($user->isAdminBarHidden()) {
			echo '
					<style>
						#wp-admin-bar-root-default { display: none !important; }
					</style>
				';
		}
	}


}
