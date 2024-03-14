<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';

	//Redirect on activate plugin
	add_action('activated_plugin', 'iwp_activation_redirect');
	function iwp_activation_redirect($plugin) {
		if($plugin === IWP_PLUGIN_BASENAME) {
			add_option(iwpPluginOptions::DO_ACTIVATION_REDIRECT, true);
			exit(wp_redirect(admin_url('admin.php?page=indigitall-push')));
		}
	}

