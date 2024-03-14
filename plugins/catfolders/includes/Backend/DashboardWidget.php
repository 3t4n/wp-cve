<?php

namespace CatFolders\Backend;

defined( 'ABSPATH' ) || exit;

class DashboardWidget {
	public function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'wp_dashboard_setup' ) );
	}

	public function wp_dashboard_setup() {
		wp_add_dashboard_widget(
			'catf_dashboard_widget',
			__( 'CatFolders Overview', 'catfolders' ),
			array( $this, 'add_dashboard_widget' )
		);
	}

	public function add_dashboard_widget() {
		require CATF_PLUGIN_PATH . 'includes/Views/dashboard-widget.php';
	}
}
