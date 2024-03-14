<?php
/**
 * Handle installation functions.
 *
 * @package UpStream.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Install
 *
 * Runs on plugin install by setting up the post types, custom taxonomies,
 * flushing rewrite rules to initiate the new 'downloads' slug and also
 * creates the plugin and populates the settings fields for those plugin
 * pages. After successful install, the user is redirected to the UpStream Welcome
 * screen.
 *
 * @param bool  $network_side If the plugin is being network-activated
 *
 * @return void
 * @global      $upstream_options
 * @global      $wp_version
 *
 * @since 1.0
 * @global      $wpdb
 */

/**
 * Check UpStream minimum requirements: PHP and WordPress versions.
 * This function calls wp_die() if any of the minimum requirements is not satisfied.
 *
 * @since   1.10.1
 *
 * @uses    wp_die()
 */
function upstream_check_min_requirements() {
	global $wp_version;

	$min_wpversion_required  = '4.5';
	$min_phpversion_required = '5.6.20';

	// Check PHP version.
	if ( version_compare( PHP_VERSION, $min_phpversion_required, '<' ) ) {
		$error_message = sprintf(
			'<p>' .
			// translators: %s: PHP_VERSION.
			__( 'It seems you are running an outdated version of PHP: <code>%s</code>.', 'upstream' ) .
			'</p>' .
			'<p>' .
			// translators: $s: min php version required.
			__(
				'For security reasons, UpStream requires at least PHP version <code>%s</code> to run.',
				'upstream'
			) . '</p>' .
			'<p>' . __( 'Please, consider upgrading your PHP version.', 'upstream' ) . '</p><br /><br />',
			PHP_VERSION,
			$min_phpversion_required
		);
	} elseif ( version_compare( $wp_version, $min_wpversion_required, '<' ) ) { // Check WordPress version.
		$error_message = sprintf(
			'<p>' .
			// translators: %s: wp_version.
			__(
				'It seems you are running an outdated version of WordPress: <code>%s</code>.',
				'upstream'
			) . '</p>' .
			'<p>' .
			// translators: %s: min wp_version required.
			__(
				'For security reasons, UpStream requires at least version <code>%s</code> to run.',
				'upstream'
			) . '</p>' .
			'<p>' . __( 'Please, consider upgrading your WordPress.', 'upstream' ) . '</p><br /><br />',
			$wp_version,
			$min_wpversion_required
		);
	}

	if ( isset( $error_message ) ) {
		$error_message .= '<a class="button" href="javascript:history.back();">' . __( 'Go Back', 'upstream' ) . '</a>';

		wp_die( wp_kses_post( $error_message ) );
	}
}

/**
 * Upstream_install_debug
 *
 * @param string $message Error message.
 */
function upstream_install_debug( $message ) {
	$message = sprintf( '[%s] %s', gmdate( 'Y-m-d H:i:s T O' ), $message ) . "\n";
	error_log( $message, 3, str_replace( '//', '/', WP_CONTENT_DIR . '/debug-upstream.log' ) );
}

/**
 * Upstream_install
 *
 * @param bool $network_wide Is network wide.
 */
function upstream_install( $network_wide = false ) {
	global $wpdb;

	upstream_install_debug( 'upstream_check_min_requirements' );
	upstream_check_min_requirements();

	if ( is_multisite() && $network_wide ) {
		upstream_install_debug( 'is_multisite/network wide' );

		foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) {
			upstream_install_debug( 'handling blog' . $blog_id );
			switch_to_blog( $blog_id );

			upstream_install_debug( 'upstream_run_install' );
			upstream_run_install();

			upstream_install_debug( 'restore_current_blog' );
			restore_current_blog();
		}
	} else {
		upstream_install_debug( 'upstream_run_install' );
		upstream_run_install();
	}

	upstream_install_debug( 'flush_rewrite_rules' );
	flush_rewrite_rules();
}

register_activation_hook( UPSTREAM_PLUGIN_FILE, 'upstream_install' );
register_deactivation_hook( UPSTREAM_PLUGIN_FILE, 'upstream_uninstall' );
add_action( 'upstream_update_data', 'upstream_update_data', 10, 2 );

/**
 * Run the UpStream Install process
 *
 * @return void
 * @since  2.5
 */
function upstream_run_install() {
	upstream_install_debug( 'wp_get_current_user' );
	$user = wp_get_current_user();

	upstream_install_debug( 'user->add_cap' );
	$user->add_cap( 'manage_upstream' );

	// Setup the Downloads Custom Post Type.
	upstream_install_debug( 'upstream_setup_post_types' );
	upstream_setup_post_types();

	// Setup the Download Taxonomies.
	upstream_install_debug( 'upstream_setup_taxonomies' );
	upstream_setup_taxonomies();

	// Add the default options.
	upstream_install_debug( 'upstream_add_default_options' );
	upstream_add_default_options();

	// Clear the permalinks.
	upstream_install_debug( 'flush_rewrite_rules' );
	flush_rewrite_rules( false );

	// Add upgraded_from option.
	upstream_install_debug( 'current_version = get_option' );
	$current_version = get_option( 'upstream_version', false );
	$fresh_install   = empty( $current_version );

	if ( ! $fresh_install ) {
		update_option( 'upstream_version_upgraded_from', $current_version );
	}

	upstream_install_debug( 'update_option' );
	update_option( 'upstream_version', UPSTREAM_VERSION );

	// Create UpStream roles.
	upstream_install_debug( 'roles = new UpStream_Roles' );
	$roles = new UpStream_Roles();

	upstream_install_debug( 'roles->add_roles' );
	$roles->add_roles();

	if ( $fresh_install ) {
		upstream_install_debug( 'upstream_run_fresh_install' );
		upstream_run_fresh_install();

		// Make sure we don't redirect if activating from network, or bulk.
		if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
			// Add the transient to redirect.
			upstream_install_debug( 'set_transient' );
			set_transient( '_upstream_activation_redirect', true, 30 );
		}
	} else {
		upstream_install_debug( 'upstream_run_reinstall' );
		upstream_run_reinstall();

		upstream_install_debug( 'do_action' );
		do_action( 'upstream_update_data', $current_version, UPSTREAM_VERSION );
	}
}

/**
 * Run the fresh UpStream Install process
 *
 * @return void
 * @since  2.5
 */
function upstream_run_fresh_install() {
	// Add default capabilities for roles.
	$roles = new UpStream_Roles();
	$roles->add_default_caps();
}

/**
 * Run the UpStream Reinstall process
 *
 * @return void
 * @since  2.5
 */
function upstream_run_reinstall() {
}

/**
 * Runs the UpStream uninstall process.
 */
function upstream_uninstall() {
	flush_rewrite_rules();

	// RSD: deactivate any child plugins.
	if ( is_plugin_active( 'UpStream-Reports-PDF/upstream-reports-pdf.php' ) ||
		is_plugin_active( 'upstream-reports-pdf/upstream-reports-pdf.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_reports_pdf' );
	}
	if ( is_plugin_active( 'UpStream-Reports/upstream-reports.php' ) ||
		is_plugin_active( 'upstream-reports/upstream-reports.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_reports' );
	}
	if ( is_plugin_active( 'UpStream-Copy-Project/upstream-copy-project.php' ) ||
		is_plugin_active( 'upstream-copy-project/upstream-copy-project.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_copy_project' );
	}
	if ( is_plugin_active( 'UpStream-Custom-Fields/upstream-custom-fields.php' ) ||
		is_plugin_active( 'upstream-custom-fields/upstream-custom-fields.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_custom_fields' );
	}
	if ( is_plugin_active( 'UpStream-Customizer/upstream-customizer.php' ) ||
		is_plugin_active( 'upstream-customizer/upstream-customizer.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_customizer' );
	}
	if ( is_plugin_active( 'UpStream-Email-Notifications/upstream-email-notifications.php' ) ||
		is_plugin_active( 'upstream-email-notifications/upstream-email-notifications.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_email_notifications' );
	}
	if ( is_plugin_active( 'UpStream-Calendar-View/upstream-calendar-view.php' ) ||
		is_plugin_active( 'upstream-calendar-view/upstream-calendar-view.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_calendar_view' );
	}
	if ( is_plugin_active( 'UpStream-Frontend-Edit/upstream-frontend-edit.php' ) ||
		is_plugin_active( 'upstream-frontend-edit/upstream-frontend-edit.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_frontend_edit' );
	}
	if ( is_plugin_active( 'UpStream-Project-Timeline/upstream-project-timeline.php' ) ||
		is_plugin_active( 'upstream-project-timeline/upstream-project-timeline.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_project_timeline' );
	}

	if ( is_plugin_active( 'UpStream-Advanced-Permissions/upstream-advanced-permissions.php' ) ||
		is_plugin_active( 'upstream-advanced-permissions/upstream-advanced-permissions.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_advanced_permissions' );
	}

	if ( is_plugin_active( 'UpStream-API/upstream-api.php' ) ||
		is_plugin_active( 'upstream-api/upstream-api.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_api' );
	}

	if ( is_plugin_active( 'UpStream-Forms/upstream-forms.php' ) ||
		is_plugin_active( 'upstream-forms/upstream-forms.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_forms' );
	}

	if ( is_plugin_active( 'UpStream-Shortcodes/upstream-shortcodes.php' ) ||
		is_plugin_active( 'upstream-shortcodes/upstream-shortcodes.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_shortcodes' );
	}

	if ( is_plugin_active( 'UpStream-Naming/upstream-name-replacement.php' ) ||
		is_plugin_active( 'upstream-name-replacement/upstream-name-replacement.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_name_replacement' );
	}

	if ( is_plugin_active( 'UpStream-Reporting/upstream-reporting.php' ) ||
		is_plugin_active( 'upstream-reporting/upstream-reporting.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_reporting' );
	}

	if ( is_plugin_active( 'UpStream-Time-Tracking-and-Budgeting/upstream-time-tracking.php' ) ||
		is_plugin_active( 'upstream-time-tracking/upstream-time-tracking.php' )
	) {
		add_action( 'update_option_active_plugins', 'upstream_deactivate_dependency_time_tracking_budgeting' );
	}
}


/**
 * Upstream_deactivate_dependency_shortcodes.
 */
function upstream_deactivate_dependency_shortcodes() {
	deactivate_plugins(
		array(
			'UpStream-Shortcodes/upstream-shortcodes.php',
			'upstream-shortcodes/upstream-shortcodes.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_forms.
 */
function upstream_deactivate_dependency_forms() {
	deactivate_plugins(
		array(
			'UpStream-Forms/upstream-forms.php',
			'upstream-forms/upstream-forms.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_api.
 */
function upstream_deactivate_dependency_api() {
	deactivate_plugins(
		array(
			'UpStream-API/upstream-api.php',
			'upstream-api/upstream-api.php',
		)
	);
}


/**
 * Upstream_deactivate_dependency_advanced_permissions.
 */
function upstream_deactivate_dependency_advanced_permissions() {
	deactivate_plugins(
		array(
			'UpStream-Advanced-Permissions/upstream-advanced-permissions.php',
			'upstream-advanced-permissions/upstream-advanced-permissions.php',
		)
	);
}




/**
 * Upstream_deactivate_dependency_calendar_view.
 */
function upstream_deactivate_dependency_calendar_view() {
	deactivate_plugins(
		array(
			'UpStream-Calendar-View/upstream-calendar-view.php',
			'upstream-calendar-view/upstream-calendar-view.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_reports_pdf.
 */
function upstream_deactivate_dependency_reports_pdf() {
	deactivate_plugins(
		array(
			'UpStream-Reports-PDF/upstream-reports-pdf.php',
			'upstream-reports-pdf/upstream-reports-pdf.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_reports.
 */
function upstream_deactivate_dependency_reports() {
	deactivate_plugins(
		array(
			'UpStream-Reports/upstream-reports.php',
			'upstream-reports/upstream-reports.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_copy_project.
 */
function upstream_deactivate_dependency_copy_project() {
	deactivate_plugins(
		array(
			'UpStream-Copy-Project/upstream-copy-project.php',
			'upstream-copy-project/upstream-copy-project.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_project_timeline.
 */
function upstream_deactivate_dependency_project_timeline() {
	deactivate_plugins(
		array(
			'UpStream-Project-Timeline/upstream-project-timeline.php',
			'upstream-project-timeline/upstream-project-timeline.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_frontend_edit.
 */
function upstream_deactivate_dependency_frontend_edit() {
	deactivate_plugins(
		array(
			'UpStream-Frontend-Edit/upstream-frontend-edit.php',
			'upstream-frontend-edit/upstream-frontend-edit.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_email_notifications.
 */
function upstream_deactivate_dependency_email_notifications() {
	deactivate_plugins(
		array(
			'UpStream-Email-Notifications/upstream-email-notifications.php',
			'upstream-email-notifications/upstream-email-notifications.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_customizer.
 */
function upstream_deactivate_dependency_customizer() {
	deactivate_plugins(
		array(
			'UpStream-Customizer/upstream-customizer.php',
			'upstream-customizer/upstream-customizer.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_custom_fields.
 */
function upstream_deactivate_dependency_custom_fields() {
	deactivate_plugins(
		array(
			'UpStream-Custom-Fields/upstream-custom-fields.php',
			'upstream-custom-fields/upstream-custom-fields.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_name_replacement.
 */
function upstream_deactivate_dependency_name_replacement() {
	deactivate_plugins(
		array(
			'UpStream-Naming/upstream-name-replacement.php',
			'upstream-name-replacement/upstream-name-replacement.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_reporting.
 */
function upstream_deactivate_dependency_reporting() {
	deactivate_plugins(
		array(
			'UpStream-Reporting/upstream-reporting.php',
			'upstream-reporting/upstream-reporting.php',
		)
	);
}

/**
 * Upstream_deactivate_dependency_time_tracking_budgeting.
 */
function upstream_deactivate_dependency_time_tracking_budgeting() {
	deactivate_plugins(
		array(
			'UpStream-Time-Tracking-and-Budgeting/upstream-time-tracking.php',
			'upstream-time-tracking/upstream-time-tracking.php',
		)
	);
}

/**
 * Upstream_add_default_options.
 */
function upstream_add_default_options() {
	// general options.
	$general = get_option( 'upstream_general' );
	if ( ! $general || empty( $general ) ) {
		$general['project']['single']            = 'Project';
		$general['project']['plural']            = 'Projects';
		$general['client']['single']             = 'Client';
		$general['client']['plural']             = 'Clients';
		$general['milestone']['single']          = 'Milestone';
		$general['milestone']['plural']          = 'Milestones';
		$general['milestone_category']['single'] = 'Milestone Category';
		$general['milestone_category']['plural'] = 'Milestone Categories';
		$general['task']['single']               = 'Task';
		$general['task']['plural']               = 'Tasks';
		$general['bug']['single']                = 'Bug';
		$general['bug']['plural']                = 'Bugs';
		$general['file']['single']               = 'File';
		$general['file']['plural']               = 'Files';

		$general['login_heading'] = 'Project Login';
		$general['admin_email']   = get_bloginfo( 'admin_email' );

		update_option( 'upstream_general', $general );
	}

	$cached_ids         = array();
	$generate_random_id = function () use ( &$cached_ids ) {
		do {
			$random_id = upstream_generate_random_string( 5, 'abcdefghijklmnopqrstuvwxyz0123456789' );
		} while ( isset( $cached_ids[ $random_id ] ) ); // Isset is faster than in_array in this case.

		$cached_ids[ $random_id ] = null;

		return $random_id;
	};

	// project options.
	$projects = get_option( 'upstream_projects' );
	if ( ! $projects || empty( $projects ) ) {
		$projects['statuses'][0]['name']  = 'In Progress';
		$projects['statuses'][0]['color'] = '#5cbfd1';
		$projects['statuses'][0]['type']  = 'open';
		$projects['statuses'][0]['id']    = $generate_random_id();

		$projects['statuses'][1]['name']  = 'Overdue';
		$projects['statuses'][1]['color'] = '#d15d5c';
		$projects['statuses'][1]['type']  = 'open';
		$projects['statuses'][1]['id']    = $generate_random_id();

		$projects['statuses'][2]['name']  = 'Closed';
		$projects['statuses'][2]['color'] = '#6b6b6b';
		$projects['statuses'][2]['type']  = 'closed';
		$projects['statuses'][2]['id']    = $generate_random_id();

		update_option( 'upstream_projects', $projects );
	}

	// task options.
	$tasks = get_option( 'upstream_tasks' );
	if ( ! $tasks || empty( $tasks ) ) {
		$tasks['statuses'][0]['name']  = 'In Progress';
		$tasks['statuses'][0]['color'] = '#5cbfd1';
		$tasks['statuses'][0]['type']  = 'open';
		$tasks['statuses'][0]['id']    = $generate_random_id();

		$tasks['statuses'][1]['name']  = 'Overdue';
		$tasks['statuses'][1]['color'] = '#d15d5c';
		$tasks['statuses'][1]['type']  = 'open';
		$tasks['statuses'][1]['id']    = $generate_random_id();

		$tasks['statuses'][2]['name']  = 'Completed';
		$tasks['statuses'][2]['color'] = '#5cd165';
		$tasks['statuses'][2]['type']  = 'closed';
		$tasks['statuses'][2]['id']    = $generate_random_id();

		$tasks['statuses'][3]['name']  = 'Closed';
		$tasks['statuses'][3]['color'] = '#6b6b6b';
		$tasks['statuses'][3]['type']  = 'closed';
		$tasks['statuses'][3]['id']    = $generate_random_id();

		update_option( 'upstream_tasks', $tasks );
	}

	// bug options.
	$bugs = get_option( 'upstream_bugs' );
	if ( ! $bugs || empty( $bugs ) ) {
		$bugs['statuses'][0]['name']  = 'In Progress';
		$bugs['statuses'][0]['color'] = '#5cbfd1';
		$bugs['statuses'][0]['type']  = 'open';
		$bugs['statuses'][0]['id']    = $generate_random_id();

		$bugs['statuses'][1]['name']  = 'Overdue';
		$bugs['statuses'][1]['color'] = '#d15d5c';
		$bugs['statuses'][1]['type']  = 'open';
		$bugs['statuses'][1]['id']    = $generate_random_id();

		$bugs['statuses'][2]['name']  = 'Completed';
		$bugs['statuses'][2]['color'] = '#5cd165';
		$bugs['statuses'][2]['type']  = 'closed';
		$bugs['statuses'][2]['id']    = $generate_random_id();

		$bugs['statuses'][3]['name']  = 'Closed';
		$bugs['statuses'][3]['color'] = '#6b6b6b';
		$bugs['statuses'][3]['type']  = 'closed';
		$bugs['statuses'][3]['id']    = $generate_random_id();

		$bugs['severities'][0]['name']  = 'Critical';
		$bugs['severities'][0]['color'] = '#d15d5c';
		$bugs['severities'][0]['id']    = $generate_random_id();

		$bugs['severities'][1]['name']  = 'Standard';
		$bugs['severities'][1]['color'] = '#d17f5c';
		$bugs['severities'][1]['id']    = $generate_random_id();

		$bugs['severities'][2]['name']  = 'Minor';
		$bugs['severities'][2]['color'] = '#d1a65c';
		$bugs['severities'][2]['id']    = $generate_random_id();

		update_option( 'upstream_bugs', $bugs );
	}
}


if ( version_compare( get_bloginfo( 'version' ), '5.1', '>=' ) ) {

	/**
	 * Upstream_new_blog_created
	 *
	 * @param string $site Site name.
	 */
	function upstream_new_blog_created( $site ) {
		upstream_install_debug( 'upstream_new_blog_created ' . $site );

		$blog_id = $site->blog_id;

		if ( is_plugin_active_for_network( plugin_basename( UPSTREAM_PLUGIN_FILE ) ) ) {

			upstream_install_debug( 'switch_to_blog' );
			switch_to_blog( $blog_id );

			upstream_install_debug( 'upstream_install' );
			upstream_install();

			upstream_install_debug( 'restore_current_blog' );
			restore_current_blog();
		}
	}

	add_action( 'wp_insert_site', 'upstream_new_blog_created', 10, 6 );

} else {

	/**
	 * When a new Blog is created in multisite, see if UpStream is network activated, and run the installer
	 *
	 * @param int    $blog_id The Blog ID created.
	 * @param int    $user_id The User ID set as the admin.
	 * @param string $domain The URL.
	 * @param string $path Site Path.
	 * @param int    $site_id The Site ID.
	 * @param array  $meta Blog Meta.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function upstream_new_blog_created( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		up_debug();

		if ( is_plugin_active_for_network( plugin_basename( UPSTREAM_PLUGIN_FILE ) ) ) {
			switch_to_blog( $blog_id );
			upstream_install();
			restore_current_blog();
		}
	}

	add_action( 'wpmu_new_blog', 'upstream_new_blog_created', 10, 6 );

}

/**
 * Post-installation
 *
 * Runs just after plugin installation and exposes the
 * upstream_after_install hook.
 *
 * @return void
 * @since 1.0.0
 */
function upstream_after_install() {
	up_debug();

	if ( ! is_admin() ) {
		return;
	}

	$activated = get_transient( '_upstream_activation_redirect' );

	if ( false !== $activated ) {

		// add the default options.
		// upstream_add_default_project().
		delete_transient( '_upstream_activation_redirect' );

		if ( ! isset( $_GET['activate-multi'] ) ) {
			set_transient( '_upstream_redirected', true, 360 );
			wp_redirect( admin_url( 'post-new.php?post_type=project' ) );
			exit;
		}
	}
}

add_action( 'admin_init', 'upstream_after_install', 100 );


/**
 * Show a success notice after install and redirect.
 */
function upstream_install_success_notice() {
	up_debug();

	$redirected = get_transient( '_upstream_redirected' );
	$get_data   = isset( $_GET ) ? wp_unslash( $_GET ) : array();

	if ( false !== $redirected && isset( $get_data['page'] ) && sanitize_text_field( $get_data['page'] ) == 'upstream_general' ) {
		// Delete the transient.
		// delete_transient( '_upstream_redirected' ).

		$class    = 'notice notice-info is-dismissible';
		$message  = '<strong>' . __( 'Success! UpStream is up and running.', 'upstream' ) . '</strong><br>';
		$message .= __(
			'Step 1. Please go through each settings tab below and configure the options.',
			'upstream'
		) . '<br>';
		$message .= __(
			'Step 2. Add a new Client by navigating to <strong>Projects > New Client</strong>',
			'upstream'
		) . '<br>';
		$message .= __(
			'Step 3. Add your first Project by navigating to <strong>Projects > New Project</strong>',
			'upstream'
		) . '<br>';

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}
}

add_action( 'admin_notices', 'upstream_install_success_notice' );


/**
 * The original data update function. This is superceded by rev2 as of version 1.27.
 *
 * @param string $old_version The old_version.
 * @param string $new_version The new_version.
 * @throws Exception Exception.
 */
function upstream_update_data( $old_version, $new_version ) {
	up_debug();

	// Ignore if we are on the same version.
	if ( $old_version === $new_version ) {
		return;
	}

	if ( version_compare( $new_version, '1.22.0', '=' ) ) {
		// Make sure administrator and managers are able to.
		$roles = array(
			'upstream_manager',
			'administrator',
			'upstream_user',
		);

		foreach ( $roles as $role ) {
			$role = get_role( $role );

			if ( is_object( $role ) ) {
				$role->add_cap( 'project_title_field', true );
				$role->add_cap( 'project_status_field', true );
				$role->add_cap( 'project_owner_field', true );
				$role->add_cap( 'project_client_field', true );
				$role->add_cap( 'project_users_field', true );
				$role->add_cap( 'project_start_date_field', true );
				$role->add_cap( 'project_end_date_field', true );
			}
		}
	}

	if ( version_compare( $old_version, '1.22.1', '<' ) ) {
		// Force to fix bug statuses and severities with empty ID.
		delete_option( 'upstream:created_bugs_args_ids' );

		UpStream_Options_Bugs::create_bugs_statuses_ids();
	}

	$has_finished_migration = get_option( '_upstream_migration_finished_1.24.0', null );
	if ( empty( $has_finished_migration ) && version_compare( $old_version, '1.24.0', '<' ) ) {
		$this->upstreamMilestoneTags();

		// Default labels for Milestone Categories.
		$general = get_option( 'upstream_general' );

		$general['milestone_category']['single'] = 'Milestone Category';
		$general['milestone_category']['plural'] = 'Milestone Categories';

		update_option( 'upstream_general', $general );

		// Make sure administrator and managers are able to work with the new milestones.
		$roles = array(
			'upstream_manager',
			'administrator',
			'upstream_user',
		);

		foreach ( $roles as $role ) {
			$role = get_role( $role );

			if ( is_object( $role ) ) {
				$capabilities = array(
					// Post type.
					'edit_milestone',
					'read_milestone',
					'delete_milestone',
					'edit_milestones',
					'edit_others_milestones',
					'publish_milestones',
					'read_private_milestones',
					'delete_milestones',
					'delete_private_milestones',
					'delete_published_milestones',
					'delete_others_milestones',
					'edit_private_milestones',
					'edit_published_milestones',

					// Terms.
					'manage_milestone_terms',
					'edit_milestone_terms',
					'delete_milestone_terms',
					'assign_milestone_terms',
				);

				foreach ( $capabilities as $capability ) {
					$role->add_cap( $capability, true );
				}
			}
		}

		// If we have projects, create new milestones based on current ones.
		$projects = get_posts(
			array(
				'post_type'      => 'project',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => '_upstream_milestones_migrated',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => '_upstream_milestones_migrated',
						'value'   => 1,
						'compare' => '!=',
					),
				),
			)
		);

		if ( ! empty( $projects ) ) {
			// Migrate the milestones.
			$default_milestones = get_option( 'upstream_milestones', array() );

			if ( ! empty( $default_milestones ) ) {
				foreach ( $projects as $project ) {
					\UpStream\Milestones::migrate_legacy_milestones_for_project( $project->ID );
				}
			}
		}

		update_option( '_upstream_migration_finished_1.24.0', true );
	}

	$has_finished_migration = get_option( '_upstream_migration_finished_1.24.1', null );
	if ( empty( $has_finished_migration ) && version_compare( $old_version, '1.24.1', '<' ) ) {
		// If we have unpublished projects, create new milestones based on current ones.
		$projects = get_posts(
			array(
				'post_type'      => 'project',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => '_upstream_milestones_migrated',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => '_upstream_milestones_migrated',
						'value'   => 1,
						'compare' => '!=',
					),
				),
			)
		);

		if ( ! empty( $projects ) ) {
			// Migrate the milestones.
			$default_milestones = get_option( 'upstream_milestones', array() );

			if ( ! empty( $default_milestones ) ) {
				foreach ( $projects as $project ) {
					\UpStream\Milestones::migrate_legacy_milestones_for_project( $project->ID );
				}
			}
		}

		update_option( '_upstream_migration_finished_1.24.1', true );
	}

	$version                = '1.24.2';
	$migration_option       = '_upstream_migration_finished_' . $version;
	$has_finished_migration = get_option( $migration_option, null );
	if ( empty( $has_finished_migration ) && version_compare( $old_version, $version, '<' ) ) {
		// If we have unpublished projects, create new milestones based on current ones.
		$projects = get_posts(
			array(
				'post_type'      => 'project',
				'post_status'    => 'any',
				'posts_per_page' => -1,
			)
		);

		if ( ! empty( $projects ) ) {
			foreach ( $projects as $project ) {
				\UpStream\Milestones::fix_milestone_orders_on_project( $project->ID );
			}
		}

		update_option( $migration_option, true );
	}

	// do the first new migration to get everything to 1.27.

	$migration_id           = 'M0000001';
	$migration_option       = '_upstream_migration_finished_' . $migration_id;
	$has_finished_migration = get_option( $migration_option, null );
	if ( empty( $has_finished_migration ) ) {

		if ( ! class_exists( 'Upstream_Counts' ) ) {
			include_once UPSTREAM_PLUGIN_DIR . '/includes/class-upstream-counts.php';
		}

		$counts   = new Upstream_Counts( 0 );
		$projects = $counts->projects;

		if ( ! empty( $projects ) ) {
			foreach ( $projects as $project ) {
				$project_object = new UpStream_Project( $project->ID );
				$project_object->update_project_meta();
			}
		}

		update_option( $migration_option, true );
	}

}

/**
 * Show a message if the plugin needs to be reactivated
 */
function upstream_reactivate_notice() {
	up_debug();

	$class    = 'notice notice-info is-dismissible';
	$message  = '<strong>' . __( 'UpStream needs to be reactivated.', 'upstream' ) . '</strong><br>';
	$message .= __(
		'In order to complete the upgrade to this version, you will need to deactivate and re-activate Upstream. Make sure Remove Data under the UpStream menu is unchecked so you do not lose any data.',
		'upstream'
	) . '<br>';

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}


/**
 * This is a replacement for upstream_update_data(). It is used to update any data from the
 * 1.27 release and after.
 *
 * @since 1.27
 */
function upstream_update_data_rev_2() {
	if ( ! is_admin() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$current_version = get_option( 'upstream_version', false );

	if ( empty( $current_version ) ) {

		// if current version is not set, this is a new install.
		return;

	} elseif ( UPSTREAM_VERSION === $current_version ) {

		// already at current verison... check if last migration was done
		// (meaning all should have been done) otherwise return.

		$migration_id           = 'M0000002';
		$migration_option       = '_upstream_migration_finished_' . $migration_id;
		$has_finished_migration = get_option( $migration_option, null );

		if ( $has_finished_migration ) {
			return;
		} else {
			$has_finished_migration = false;
		}
	} elseif ( version_compare( $current_version, '1.26.0', '<' ) ) {

		// if current version is less than 1.26 don't use this function at all
		// wait until someone reactivates the plugin and then this function can run.
		add_action( 'admin_notices', 'upstream_reactivate_notice' );

		return;
	}

	$migration_id           = 'M0000001';
	$migration_option       = '_upstream_migration_finished_' . $migration_id;
	$has_finished_migration = get_option( $migration_option, null );
	if ( empty( $has_finished_migration ) ) {

		if ( ! class_exists( 'Upstream_Counts' ) ) {
			include_once UPSTREAM_PLUGIN_DIR . '/includes/class-upstream-counts.php';
		}

		$counts   = new Upstream_Counts( 0 );
		$projects = $counts->projects;

		if ( ! empty( $projects ) ) {
			foreach ( $projects as $project ) {
				$project_object = new UpStream_Project( $project->ID );
				$project_object->update_project_meta();
			}
		}

		update_option( $migration_option, true );
	}

	$migration_id           = 'M0000002';
	$migration_option       = '_upstream_migration_finished_' . $migration_id;
	$has_finished_migration = get_option( $migration_option, null );
	if ( empty( $has_finished_migration ) ) {

		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		if ( function_exists( 'maybe_create_table' ) ) {
			$sql = 'CREATE TABLE ' . $wpdb->prefix . 'upfs_files ( upfsid VARCHAR(60), orig_filename VARCHAR(255), saved_filename VARCHAR(255), mime_type VARCHAR(255), file_size INT, access_rules TEXT, PRIMARY KEY (upfsid) )';
			$r   = maybe_create_table( $wpdb->prefix . 'upfs_files', $sql );
		}

		update_option( $migration_option, true );
	}

	// if we made any migrations, update the upstream version to the current one.
	update_option( 'upstream_version_upgraded_from', $current_version );
	update_option( 'upstream_version', UPSTREAM_VERSION );

}

add_action( 'admin_init', 'upstream_update_data_rev_2', 90 );


/**
 * Upstream_change_role_name
 */
function upstream_change_role_name() {
	global $wp_roles;

	if ( ! isset( $wp_roles ) ) {
		$wp_roles_obj = new WP_Roles();
	} else {
		$wp_roles_obj = $wp_roles;
	}

	$wp_roles_obj->roles['upstream_client_user']['name'] = __( 'UpStream Client User', 'upstream' );
	$wp_roles_obj->role_names['upstream_client_user']    = __( 'UpStream Client User', 'upstream' );

}
add_action( 'init', 'upstream_change_role_name' );
