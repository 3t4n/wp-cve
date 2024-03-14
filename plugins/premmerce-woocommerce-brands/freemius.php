<?php

// Create a helper function for easy SDK access.
function premmerce_pwb_fs(){
	global $premmerce_pwb_fs;

	if(!isset($premmerce_pwb_fs)){
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/freemius/start.php';

		$premmerce_pwb_fs = fs_dynamic_init([
			'id'             => '1492',
			'slug'           => 'premmerce-woocommerce-brands',
			'type'           => 'plugin',
			'public_key'     => 'pk_74be3e0a5f62f80fe88dfc1742c94',
			'is_premium'     => false,
			'has_addons'     => false,
			'has_paid_plans' => false,
			'menu'           => [
				'first-path' => 'plugins.php',
				'support'    => false,
			],
		]);
	}

	return $premmerce_pwb_fs;
}

// Init Freemius.
premmerce_pwb_fs();
// Signal that SDK was initiated.
do_action('premmerce_pwb_fs_loaded');