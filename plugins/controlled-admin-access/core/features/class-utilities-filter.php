<?php

namespace WPRuby_CAA\Core\Features;


use WPRuby_CAA\Core\Dto\User;
use WPRuby_CAA\Core\Helpers\Helper;

class Utilities_Filter {

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
		// Remove actions for main admin from the Users table.
		add_filter('user_row_actions', [$this, 'filter_main_admin_actions'], 99, 2);
		// Prevent actions to be processed on main admin such as delete, sendresetpassword
		add_action('admin_init', [$this, 'filter_actions_on_main_admin'], 99, 2);
		// Remove main admin from the Users table.
		add_action('users_list_table_query_args', [$this, 'remove_main_admin_from_users_table'] );
		// Remove the plugin from plugins list.
		add_filter('all_plugins', [$this, 'filter_plugins_list']);
		// Prevent editing the plugin's code.
		add_action('admin_init', [$this, 'block_access_to_the_plugin_code_editor'], 99, 2);

	}

	/**
	 * @param $actions
	 * @param \WP_User $user_object
	 *
	 * @return mixed
	 */
	public function filter_main_admin_actions($actions, $user_object)
	{

		$user = new User(get_current_user_id());

		if ($user->getCreatedBy() === $user_object->ID) {
			return [];
		}

		return $actions;
	}

	public function filter_actions_on_main_admin()
	{
		global $pagenow;

		if ($pagenow !== 'users.php') {
			return;
		}


		if (!isset($_GET['action'])) {
			return;
		}

        $user = new User(get_current_user_id());

        if( !$user->isCaaAccount()) {
            return;
        }

		$action = sanitize_key(strtolower($_GET['action']));

		if (! in_array($action, ['switch_to_user', 'delete', 'resetpassword', 'spam'])) {
			return;
		}


		if ($user->getCreatedBy() !== intval($_GET['user'])) {
			return;
		}

		Helper::block_access();

	}

	public function remove_main_admin_from_users_table($args)
	{
		$user = new User(get_current_user_id());

		if ($user->getCreatedBy() > 0) {
			$args['exclude'] = [ $user->getCreatedBy() ];
		}

		return $args;
	}

	public function filter_plugins_list($plugins)
	{
		$user = new User(get_current_user_id());

		if (! $user->isCaaAccount()) {
			return $plugins;
		}


		if (isset($plugins['controlled-admin-access/controlled-admin-access.php'])) {
			unset($plugins['controlled-admin-access/controlled-admin-access.php']);
		}

		if (isset($plugins['controlled-admin-access-pro/controlled-admin-access-pro.php'])) {
			unset($plugins['controlled-admin-access-pro/controlled-admin-access-pro.php']);
		}

		return $plugins;

	}

	public function block_access_to_the_plugin_code_editor()
	{
		global $pagenow;

		if ($pagenow !== 'plugin-editor.php') {
			return;
		}

		if (!isset($_GET['plugin'])) {
			return;
		}

		$user = new User(get_current_user_id());

		if (!$user->isCaaAccount()) {
			return;
		}

		$plugin = strtolower(sanitize_text_field($_GET['plugin']));

		if ($plugin === 'controlled-admin-access/controlled-admin-access.php') {
			Helper::block_access();
		}

	}

}
