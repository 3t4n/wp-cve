<?php
/**
 * This File is used for displaying Topbar menu.
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
				$flag = $capabilities[0];
				break;

			case 'author':
				$flag = $capabilities[1];
				break;

			case 'editor':
				$flag = $capabilities[2];
				break;

			case 'contributor':
				$flag = $capabilities[3];
				break;

			case 'subscriber':
				$flag = $capabilities[4];
				break;

			default:
				$flag = $capabilities[5];
				break;
		}

		if ( '1' === $flag ) {
			$wp_admin_bar->add_menu(
				array(
					'id'    => 'clean_up_optimizer',
					'title' => '<img style="width:16px; height:16px; vertical-align:middle; margin-right:3px; display:inline-block;" src=' . plugins_url( 'assets/global/img/icon.png', dirname( __FILE__ ) ) . "> $cpo_clean_up_optimizer",
					'href'  => admin_url( 'admin.php?page=cpo_dashboard' ),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'clean_up_optimizer',
					'id'     => 'cpo_dashboard',
					'title'  => $cpo_dashboard,
					'href'   => admin_url( 'admin.php?page=cpo_dashboard' ),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'clean_up_optimizer',
					'id'     => 'cpo_logs',
					'title'  => $cpo_logs_label,
					'href'   => admin_url( 'admin.php?page=cpo_login_logs' ),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'clean_up_optimizer',
					'id'     => 'cpo_cron_jobs',
					'title'  => $cpo_cron_jobs_label,
					'href'   => admin_url( 'admin.php?page=cpo_core_jobs' ),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'clean_up_optimizer',
					'id'     => 'cpo_general_settings',
					'title'  => $cpo_general_settings_label,
					'href'   => admin_url( 'admin.php?page=cpo_notifications_setup' ),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'clean_up_optimizer',
					'id'     => 'cpo_security_settings',
					'title'  => $cpo_security_settings,
					'href'   => admin_url( 'admin.php?page=cpo_blockage_settings' ),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'clean_up_optimizer',
					'id'     => 'cpo_other_settings',
					'title'  => $cpo_general_other_settings,
					'href'   => admin_url( 'admin.php?page=cpo_other_settings' ),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'clean_up_optimizer',
					'id'     => 'cpo_feature_requests',
					'title'  => $cpo_feature_request_label,
					'href'   => 'https://wordpress.org/support/plugin/wp-clean-up-optimizer',
					'meta'   => array( 'target' => '_blank' ),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'clean_up_optimizer',
					'id'     => 'cpo_system_information',
					'title'  => $cpo_system_information_label,
					'href'   => admin_url( 'admin.php?page=cpo_system_information' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'clean_up_optimizer',
					'id'     => 'cpo_premium_edition',
					'title'  => $cpo_upgrade,
					'href'   => 'https://tech-banker.com/clean-up-optimizer/pricing/',
					'meta'   => array( 'target' => '_blank' ),
				)
			);
		}
	}
}
