<?php

namespace WPRuby_CAA\Core\Helpers;

class Helper {

	public static function block_access()
	{
		$args = ['back_link' => true];
		wp_die(
			__('You do not have a permission to access this page.', 'controlled-admin-access'),
			__('Access Denied', 'controlled-admin-access'),
			$args);
	}

}
