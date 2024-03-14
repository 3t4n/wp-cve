<?php

namespace ImageComply;

class Dashboard
{

	public function __construct()
	{
		// Add the admin menu page
		add_action('admin_menu', [$this, 'register_dashboard_page']);
	}

	public function register_dashboard_page()
	{

		add_menu_page(
			__('ImageComply', 'imagecomply'),
			__('ImageComply', 'imagecomply'),
			'manage_options',
			'imagecomply',
			[$this, 'render_dashboard_page'],
			IMAGECOMPLY_PLUGIN_DIR_URL . 'assets/icons/menu-icon.svg',
			11
		);

		add_submenu_page(
			'options-general.php',
			__('ImageComply', 'imagecomply'),
			__('ImageComply', 'imagecomply'),
			'manage_options',
			'imagecomply',
			[$this, 'render_dashboard_page']
		);

		add_submenu_page(
			'upload.php',
			__('ImageComply', 'imagecomply'),
			__('ImageComply', 'imagecomply'),
			'manage_options',
			'imagecomply',
			[$this, 'render_dashboard_page']
		);
	}



	public function render_dashboard_page()
	{
		require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'templates/dashboard.php';
	}
}

new Dashboard();
