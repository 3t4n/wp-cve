<?php

// Create a helper function for easy SDK access.
function premmerce_re_fs(){
	global $premmerce_re_fs;

	if(!isset($premmerce_re_fs)){
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/freemius/start.php';

		$premmerce_re_fs = fs_dynamic_init([
			'id'             => '1521',
			'slug'           => 'premmerce-users-roles',
			'type'           => 'plugin',
			'public_key'     => 'pk_0d501c86b002abe30935c91a125d1',
			'is_premium'     => false,
			'has_addons'     => false,
			'has_paid_plans' => false,
			'menu'           => [
				'slug'    => 'premmerce-users-roles',
				'account' => false,
				'contact' => false,
				'support' => false,
				'parent'  => [
					'slug' => 'premmerce',
				],
			],
		]);
	}

	return $premmerce_re_fs;
}

// Init Freemius.
premmerce_re_fs();
// Signal that SDK was initiated.
do_action('premmerce_re_fs_loaded');