<?php
// Prevent direct access to files
if(!defined('ABSPATH')){
    exit;
}

function jbafe_addon_load_fail(){
    
	$plugin = 'elementor/elementor.php';

	if(jbafe_is_elementor_installed()){

		if (!current_user_can('activate_plugins')) {
			return;
		}

		$activation_url = wp_nonce_url('plugins.php?action=activate&plugin=' . $plugin . '&plugin_status=all&paged=1&s', 'activate-plugin_' . $plugin );
		$message = '<p><b>JSON Based Animation for Elementor </b> requires Elementor to be activated.</p>';
        $message .= '<p><a href="'. $activation_url .'" class="button-primary">Activate Elementor</a></p>';
        
	}else{
		if(!current_user_can('install_plugins')){
			return;
		}
		$install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
		$message = '<p><b>JSON Based Animation for Elementor</b> requires Elementor to be installed and activated.</p>';
		$message .= '<p><a href="'. $install_url .'" class="button-primary">Install Elementor</a></p>';
	}

	echo '<div class="notice notice-error"><p>' . $message . '</p></div>';
}

function jbafe_is_elementor_installed() {
    $ele_file_path = 'elementor/elementor.php';
    $get_installed_plugins = get_plugins();
    return isset($get_installed_plugins[$ele_file_path]);
}
