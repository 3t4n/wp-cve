<?php

// Create a helper function for easy SDK access.
function premmerce_wcm_fs(){
	global $premmerce_wcm_fs;

	if(!isset($premmerce_wcm_fs)){
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/freemius/start.php';

		$premmerce_wcm_fs = fs_dynamic_init([
			'id'             => '1479',
			'slug'           => 'woo-customers-manager',
			'type'           => 'plugin',
			'public_key'     => 'pk_70e798f19f8a1bb908150e9484054',
			'is_premium'     => false,
			'has_addons'     => false,
			'has_paid_plans' => false,
			'menu'           => [
				'first-path' => 'plugins.php',
				'support'    => false,
			],
		]);
	}

	return $premmerce_wcm_fs;
}

// Init Freemius.
premmerce_wcm_fs();
// Signal that SDK was initiated.
do_action('premmerce_wcm_fs_loaded');