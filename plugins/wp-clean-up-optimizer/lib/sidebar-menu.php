<?php
/**
 * This File is used for creating Sidebar Menu.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/lib
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	if ( isset( $user_role_permission ) && count( $user_role_permission ) > 0 ) {
		foreach ( $user_role_permission as $permission ) {
			if ( current_user_can( $permission ) ) {
				$access_granted = true;
				break;
			}
		}
	}
	if ( ! $access_granted ) {
		return;
	} else {
		$flag              = 0;
		$role_capabilities = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE  meta_key = %s', 'roles_and_capabilities'
			)
		);// WPCS: db call ok, cache ok.

		$roles_and_capabilities = maybe_unserialize( $role_capabilities );
		$capabilities           = explode( ',', $roles_and_capabilities['roles_and_capabilities'] );
		if ( is_super_admin() ) {
			$cpo_role = 'administrator';
		} else {
			$cpo_role = check_user_roles_for_clean_up_optimizer();
		}
		switch ( $cpo_role ) {
			case 'administrator':
				$privileges = 'administrator_privileges';
				$flag       = $capabilities[0];
				break;

			case 'author':
				$privileges = 'author_privileges';
				$flag       = $capabilities[1];
				break;

			case 'editor':
				$privileges = 'editor_privileges';
				$flag       = $capabilities[2];
				break;

			case 'contributor':
				$privileges = 'contributor_privileges';
				$flag       = $capabilities[3];
				break;

			case 'subscriber':
				$privileges = 'subscriber_privileges';
				$flag       = $capabilities[4];
				break;

			default:
				$privileges = 'other_privileges';
				$flag       = $capabilities[5];
				break;
		}

		$privileges_value = '';
		if ( isset( $roles_and_capabilities ) && count( $roles_and_capabilities ) > 0 ) {
			foreach ( $roles_and_capabilities as $key => $value ) {
				if ( $privileges === $key ) {
					$privileges_value = $value;
					break;
				}
			}
		}

		$full_control = explode( ',', $privileges_value );
		if ( ! defined( 'FULL_CONTROL' ) ) {
			define( 'FULL_CONTROL', "$full_control[0]" );
		}
		if ( ! defined( 'WORDPESS_OPTIMIZER_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'WORDPESS_OPTIMIZER_CLEAN_UP_OPTIMIZER', $full_control[1] );
		}
		if ( ! defined( 'SCHEDULE_OPTIMIZER_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'SCHEDULE_OPTIMIZER_CLEAN_UP_OPTIMIZER', $full_control[2] );
		}
		if ( ! defined( 'DATABASE_OPTIMIZER_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'DATABASE_OPTIMIZER_CLEAN_UP_OPTIMIZER', $full_control[3] );
		}
		if ( ! defined( 'SCHEDULE_DB_OPTIMIZER_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'SCHEDULE_DB_OPTIMIZER_CLEAN_UP_OPTIMIZER', $full_control[4] );
		}
		if ( ! defined( 'LOGS_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'LOGS_CLEAN_UP_OPTIMIZER', "$full_control[5]" );
		}
		if ( ! defined( 'CRON_JOBS_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'CRON_JOBS_CLEAN_UP_OPTIMIZER', "$full_control[6]" );
		}
		if ( ! defined( 'GENERAL_SETTINGS_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'GENERAL_SETTINGS_CLEAN_UP_OPTIMIZER', "$full_control[7]" );
		}
		if ( ! defined( 'SECURITY_SETTINGS_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'SECURITY_SETTINGS_CLEAN_UP_OPTIMIZER', "$full_control[8]" );
		}
		if ( ! defined( 'OTHER_SETTINGS_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'OTHER_SETTINGS_CLEAN_UP_OPTIMIZER', "$full_control[9]" );
		}
		if ( ! defined( 'SYSTEM_INFORMATION_CLEAN_UP_OPTIMIZER' ) ) {
			define( 'SYSTEM_INFORMATION_CLEAN_UP_OPTIMIZER', "$full_control[10]" );
		}
		$check_clean_up_wizard = get_option( 'clean-up-optimizer-wizard-set-up' );
		if ( '1' === $flag ) {
			if ( $check_clean_up_wizard ) {
				add_menu_page( $cpo_clean_up_optimizer, $cpo_clean_up_optimizer, 'read', 'cpo_dashboard', '', plugins_url( 'assets/global/img/icon.png', dirName( __FILE__ ) ) );
			} else {
				add_menu_page( $cpo_clean_up_optimizer, $cpo_clean_up_optimizer, 'read', 'cpo_wizard_optimizer', '', plugins_url( 'assets/global/img/icon.png', dirName( __FILE__ ) ) );
				add_submenu_page( $cpo_clean_up_optimizer, $cpo_clean_up_optimizer, '', 'read', 'cpo_wizard_optimizer', 'cpo_wizard_optimizer' );
			}
			add_submenu_page( 'cpo_dashboard', $cpo_wp_optimizer, $cpo_dashboard, 'read', 'cpo_dashboard', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_dashboard' );
			add_submenu_page( $cpo_dashboard, $cpo_schedule_wp_optimizer, '', 'read', 'cpo_schedule_optimizer', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_schedule_optimizer' );
			add_submenu_page( $cpo_dashboard, $cpo_database_optimizer, '', 'read', 'cpo_db_optimizer', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_db_optimizer' );
			add_submenu_page( $cpo_dashboard, $cpo_schedule_database_optimizer, '', 'read', 'cpo_schedule_db_optimizer', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_schedule_db_optimizer' );
			add_submenu_page( $cpo_dashboard, $cpo_view_records_label, '', 'read', 'cpo_database_view_records', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_database_view_records' );
			add_submenu_page( $cpo_dashboard, $cpo_add_new_scheduled_clean_up_label, '', 'read', 'cpo_add_new_wordpress_schedule', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_add_new_wordpress_schedule' );
			add_submenu_page( $cpo_dashboard, $cpo_add_new_scheduled_clean_up_label, '', 'read', 'cpo_add_new_database_schedule', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_add_new_database_schedule' );

			add_submenu_page( 'cpo_dashboard', $cpo_logs_recent_login_logs, $cpo_logs_label, 'read', 'cpo_login_logs', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_login_logs' );
			add_submenu_page( $cpo_logs_label, $cpo_logs_live_traffic, '', 'read', 'cpo_live_traffic', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_live_traffic' );
			add_submenu_page( $cpo_logs_label, $cpo_logs_visitor_logs, '', 'read', 'cpo_visitor_logs', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_visitor_logs' );

			add_submenu_page( 'cpo_dashboard', $cpo_cron_core_jobs_label, $cpo_cron_jobs_label, 'read', 'cpo_core_jobs', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_core_jobs' );
			add_submenu_page( $cpo_cron_custom_jobs_label, $cpo_cron_custom_jobs_label, '', 'read', 'cpo_custom_jobs', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_custom_jobs' );

			add_submenu_page( 'cpo_dashboard', $cpo_notifications_setup, $cpo_general_settings_label, 'read', 'cpo_notifications_setup', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_notifications_setup' );
			add_submenu_page( $cpo_general_settings_label, $cpo_message_settings, '', 'read', 'cpo_message_settings', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_message_settings' );
			add_submenu_page( $cpo_general_settings_label, $cpo_email_templates_label, $cpo_email_templates_label, 'read', 'cpo_email_templates', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_email_templates' );
			add_submenu_page( $cpo_general_settings_label, $cpo_roles_capabilities_label, $cpo_roles_capabilities_label, 'read', 'cpo_roles_and_capabilities', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_roles_and_capabilities' );

			add_submenu_page( 'cpo_dashboard', $cpo_blockage_settings, $cpo_security_settings, 'read', 'cpo_blockage_settings', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_blockage_settings' );
			add_submenu_page( $cpo_block_unblock_ip_addresses, $cpo_block_unblock_ip_addresses, '', 'read', 'cpo_ip_addresses', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_ip_addresses' );
			add_submenu_page( $cpo_block_unblock_ip_ranges, $cpo_block_unblock_ip_ranges, '', 'read', 'cpo_ip_ranges', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_ip_ranges' );
			add_submenu_page( $cpo_block_unblock_countries, $cpo_block_unblock_countries, '', 'read', 'cpo_block_unblock_countries', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_block_unblock_countries' );


			add_submenu_page( 'cpo_dashboard', $cpo_general_other_settings, $cpo_general_other_settings, 'read', 'cpo_other_settings', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_other_settings' );

			add_submenu_page( 'cpo_dashboard', $cpo_feature_request_label, $cpo_feature_request_label, 'read', 'https://wordpress.org/support/plugin/wp-clean-up-optimizer' );
			add_submenu_page( 'cpo_dashboard', $cpo_system_information_label, $cpo_system_information_label, 'read', 'cpo_system_information', false === $check_clean_up_wizard ? 'cpo_wizard_optimizer' : 'cpo_system_information' );
			add_submenu_page( 'cpo_dashboard', $cpo_upgrade, $cpo_upgrade, 'read', 'https://tech-banker.com/clean-up-optimizer/pricing/' );
		}

		if ( ! function_exists( 'cpo_wizard_optimizer' ) ) {
			/**
			 * Function Name: cpo_wizard_optimizer
			 * Parameters: No
			 * Description: This function is used to create cpo_wizard_optimizer
			 * Created On: 05-04-2017 17:19
			 * Created By: Tech Banker Team
			 */
			function cpo_wizard_optimizer() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/wizard/wizard.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/wizard/wizard.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_dashboard' ) ) {
			/**
			 * Function Name: cpo_dashboard
			 * Parameters: No
			 * Description: This function is used to create manual Clean Up Menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_dashboard() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/wordpress-optimizer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/wordpress-optimizer.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_schedule_optimizer' ) ) {
			/**
			 * Function Name: cpo_schedule_optimizer
			 * Parameters: No
			 * Description: This function is used to create scheduled Clean Up Menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_schedule_optimizer() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/schedule-optimizer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/schedule-optimizer.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_add_new_wordpress_schedule' ) ) {
			/**
			 * Function Name: cpo_add_new_wordpress_schedule
			 * Parameters: No
			 * Description: This function is used to create add new scheduled Clean Up Menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_add_new_wordpress_schedule() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/add-new-wordpress-schedule.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/add-new-wordpress-schedule.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_db_optimizer' ) ) {
			/**
			 * Function Name: cpo_add_new_wordpress_schedule
			 * Parameters: No
			 * Description: This function is used to create add new scheduled Clean Up Menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_db_optimizer() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/database-optimizer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/database-optimizer.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_database_view_records' ) ) {
			/**
			 * Function Name: cpo_database_view_records
			 * Parameters: No
			 * Description: This function is used to create Database view records manual Clean Up Menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_database_view_records() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/view-records.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/view-records.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_schedule_db_optimizer' ) ) {

			/**
			 * Function Name: cpo_schedule_db_optimizer
			 * Parameters: No
			 * Description: This function is used to create Database scheduled clean up menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_schedule_db_optimizer() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/schedule-db-optimizer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/schedule-db-optimizer.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_add_new_database_schedule' ) ) {
			/**
			 * Function Name: cpo_add_new_database_schedule
			 * Parameters: No
			 * Description: This function is used to create Add New Schedule clean up db menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_add_new_database_schedule() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/add-new-database-schedule.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/dashboard/add-new-database-schedule.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_live_traffic' ) ) {
			/**
			 * Function Name: cpo_live_traffic
			 * Parameters: No
			 * Description: This function is used to create cpo_live_traffic menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_live_traffic() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/logs/live-traffic.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/logs/live-traffic.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_login_logs' ) ) {
			/**
			 * Function Name: cpo_login_logs
			 * Parameters: No
			 * Description: This function is used to create cpo_login_logs menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_login_logs() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/logs/recent-login-logs.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/logs/recent-login-logs.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_visitor_logs' ) ) {
			/**
			 * Function Name: cpo_visitor_logs
			 * Parameters: No
			 * Description: This function is used to create Visitor logs menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_visitor_logs() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/logs/visitor-logs.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/logs/visitor-logs.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_notifications_setup' ) ) {
			/**
			 * Function Name: cpo_notifications_setup
			 * Parameters: No
			 * Description: TThis function is used to create cpo_notifications_setup menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_notifications_setup() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/general-settings/notifications-setup.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/general-settings/notifications-setup.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_message_settings' ) ) {
			/**
			 * Function Name: cpo_message_settings
			 * Parameters: No
			 * Description: This function is used to create cpo_message_settings menu
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_message_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/general-settings/message-settings.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/general-settings/message-settings.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_other_settings' ) ) {
			/**
			 * Function Name: cpo_other_settings
			 * Parameters: No
			 * Description: This function is used to create cpo_other_settings menu
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_other_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/other-settings/other-settings.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/other-settings/other-settings.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_blockage_settings' ) ) {
			/**
			 * Function Name: cpo_blockage_settings
			 * Parameters: No
			 * Description: This function is used to create cpo_blockage_settings menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_blockage_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/security-settings/blockage-settings.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/security-settings/blockage-settings.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_ip_addresses' ) ) {
			/**
			 * Function Name: cpo_ip_addresses
			 * Parameters: No
			 * Description: This function is used to create cpo_ip_addresses menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_ip_addresses() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/security-settings/block-unblock-ip-addresses.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/security-settings/block-unblock-ip-addresses.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_ip_ranges' ) ) {
			/**
			 * Function Name: cpo_ip_ranges
			 * Parameters: No
			 * Description: This function is used to create cpo_ip_ranges menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_ip_ranges() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/security-settings/block-unblock-ip-ranges.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/security-settings/block-unblock-ip-ranges.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_block_unblock_countries' ) ) {
			/**
			 * Function Name: cpo_block_unblock_countries
			 * Parameters: No
			 * Description: This function is used to create cpo_block_unblock_countries menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_block_unblock_countries() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/security-settings/block-unblock-countries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/security-settings/block-unblock-countries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_email_templates' ) ) {
			/**
			 * Function Name: cpo_email_templates
			 * Parameters: No
			 * Description: This function is used to create cpo_email_templates menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_email_templates() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/general-settings/email-templates.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/general-settings/email-templates.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_roles_and_capabilities' ) ) {
			/**
			 * Function Name: cpo_roles_and_capabilities
			 * Parameters: No
			 * Description: This function is used to create cpo_roles_and_capabilities menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_roles_and_capabilities() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/general-settings/roles-and-capabilities.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/general-settings/roles-and-capabilities.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_core_jobs' ) ) {
			/**
			 * Function Name: cpo_core_jobs
			 * Parameters: No
			 * Description: This function is used to create cpo_core_jobs menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_core_jobs() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/cron-jobs/core-jobs.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/cron-jobs/core-jobs.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_custom_jobs' ) ) {
			/**
			 * Function Name: cpo_custom_jobs
			 * Parameters: No
			 * Description: This function is used to create cpo_custom_jobs menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_custom_jobs() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/cron-jobs/custom-jobs.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/cron-jobs/custom-jobs.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpo_system_information' ) ) {
			/**
			 * Function Name: cpo_system_information
			 * Parameters: No
			 * Description: This function is used to create cpo_system_information menu.
			 * Created On: 23-09-2016 1:00
			 * Created By: Tech Banker Team
			 */
			function cpo_system_information() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/system-information/system-information.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'views/system-information/system-information.php';
				}
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/footer.php';
				}
			}
		}
	}
}
