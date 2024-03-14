<?php

namespace WPRuby_CAA\Core\Features;

use WPRuby_CAA\Core\Dto\User;
use WPRuby_CAA\Core\Helpers\Helper;

class Menu_Blocker {

	private $pages_with_multiple_queries = ['edit.php', 'post-new.php'];

	protected static $_instance = null;

	public static function boot() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_action('current_screen', [$this, 'block']);
	}

	public function block($current_screen)
	{
		$user_id = get_current_user_id();
		$user = new User($user_id);

		$this->block_editing_main_admin($user);

		$restricted_items = $user->getRestrictedMenu();

		if (count($restricted_items) === 0) {
			return;
		}

		$restricted_items[] = "controlled-admin-access";

		$page = (isset($_GET['page']))? strtolower(sanitize_key($_GET['page'])) : null;

		if ($page !== null && in_array($page, $restricted_items)) {
			Helper::block_access();
		}

		$url = parse_url((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
		$file_name = strtolower(basename($_SERVER['SCRIPT_NAME']));

		if ($file_name === 'options.php') {
			if (isset($_POST['option_page']) && strtolower($_POST['option_page']) === 'options') {
				Helper::block_access();
			}
		}

		$query = (isset($url['query']) && $url['query']!='')?'?' . $url['query']:'';
		$slug = urldecode($file_name . $query);

		if (in_array($slug, $restricted_items) || (in_array($file_name, $restricted_items) && !in_array($file_name, $this->pages_with_multiple_queries))){
			Helper::block_access();
		}

	}

	private function block_editing_main_admin( User $user ) {
		global $pagenow;

		if ($pagenow !== 'user-edit.php') {
			return;
		}

		if (!isset($_GET['user_id'])) {
			return;
		}

		$edited_user_id = intval($_GET['user_id']);

		if ($edited_user_id === $user->getCreatedBy()) {
			Helper::block_access();
		}

	}

}
