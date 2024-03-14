<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( realpath( __DIR__ . '/DatasetController.php' ) );
require_once( realpath( __DIR__ . '/ProjectController.php' ) );
require_once( realpath( __DIR__ . '/AdvancedSettingsController.php' ) );
require_once( realpath( __DIR__ . '/SearchController.php' ) );
require_once( realpath( __DIR__ . '/ProjectsListManage.php' ) );
require_once( realpath( __DIR__ . '/ProjectsListTable.php' ) );


class MPG_MenuController {

	public static function init() {
		add_action( 'admin_menu', 'mpg_main_sidebar_menu', 9, 0 );

		function mpg_main_sidebar_menu() {

			$role = 'edit_pages';

			add_menu_page( 'MPG', 'MPG', $role, 'mpg-dataset-library', array( 'MPG_DatasetController', 'get_all' ), plugin_dir_url( __FILE__ ) . '/../../frontend/images/logo_mpg.svg' );

			add_submenu_page( 'mpg-dataset-library', __( 'Create new', 'mpg' ), __( 'Create New +', 'mpg' ), $role, 'mpg-dataset-library', array( 'MPG_DatasetController', 'get_all' ) );

			$hook = add_submenu_page( 'mpg-dataset-library', __( 'All Projects', 'mpg' ), __( 'All Projects', 'mpg' ), $role, 'mpg-project-builder', array( 'MPG_ProjectController', 'builder' ) );
			add_action( 'load-' . $hook, array( 'MPG_ProjectController', 'handle_project_builder' ) );

			add_submenu_page( 'mpg-dataset-library', __( 'Advanced settings', 'mpg' ), __( 'Advanced settings', 'mpg' ), $role, 'mpg-advanced-settings', array( 'MPG_AdvancedSettingsController', 'render' ) );

			add_submenu_page( 'mpg-dataset-library', __( 'Search settings', 'mpg' ), __( 'Search settings', 'mpg' ), $role, 'mpg-search-settings', array( 'MPG_SearchController', 'render' ) );

		}
	}
}
