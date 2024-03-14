<?php
/**
 * This file is used for admin bar menu.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/lib
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	foreach ( $user_role_permission as $permission ) {
		if ( current_user_can( $permission ) ) {
			$access_granted = true;
			break;
		}
	}

	if ( ! $access_granted ) {
		return;
	} else {
		$flag                                     = 0;
		$role_capabilities                        = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value from ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'roles_and_capabilities'
			)
		);// db call ok; no-cache ok.
		$roles_and_capabilities_unserialized_data = maybe_unserialize( $role_capabilities );
		$capabilities                             = explode( ',', $roles_and_capabilities_unserialized_data['roles_and_capabilities'] );
		if ( is_super_admin() ) {
			$cpb_role = 'administrator';
		} else {
			$cpb_role = check_user_roles_captcha_booster();
		}
		switch ( $cpb_role ) {
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
					'id'    => 'captcha_booster',
					'title' => '<img src= "' . plugins_url( 'assets/global/img/icons.png', dirname( __FILE__ ) ) .
					"\" width=\"16\" height=\"16\" style=\"vertical-align:text-top; display:inline-block; margin-right:5px;\"./> $cpb_captcha_booster_breadcrumb",
					'href'  => admin_url( 'admin.php?page=cpb_captcha_booster' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_booster',
					'id'     => 'cpb_captcha_setup',
					'title'  => $cpb_captcha_setup_menu,
					'href'   => admin_url( 'admin.php?page=cpb_captcha_booster' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_booster',
					'id'     => 'cpb_logs',
					'title'  => $cpb_logs_menu,
					'href'   => admin_url( 'admin.php?page=cpb_live_traffic' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_booster',
					'id'     => 'cpb_advance_security',
					'title'  => $cpb_advance_security_menu,
					'href'   => admin_url( 'admin.php?page=cpb_blocking_options' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_booster',
					'id'     => 'cpb_general_settings',
					'title'  => $cpb_general_settings_menu,
					'href'   => admin_url( 'admin.php?page=cpb_alert_setup' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_booster',
					'id'     => 'cpb_email_templates',
					'title'  => $cpb_email_templates_menu,
					'href'   => admin_url( 'admin.php?page=cpb_email_templates' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_booster',
					'id'     => 'cpb_roles_and_capabilities',
					'title'  => $cpb_roles_and_capabilities_menu,
					'href'   => admin_url( 'admin.php?page=cpb_roles_and_capabilities' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_booster',
					'id'     => 'cpb_support_forum',
					'title'  => $cpb_support_forum,
					'href'   => 'https://wordpress.org/support/plugin/wp-captcha-booster',
					'meta'   => array( 'target' => '_blank' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_booster',
					'id'     => 'cpb_system_information',
					'title'  => $cpb_system_information_menu,
					'href'   => admin_url( 'admin.php?page=cpb_system_information' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_booster',
					'id'     => 'cpb_premium_editions',
					'title'  => $cpb_premium,
					'href'   => 'https://tech-banker.com/captcha-booster/pricing/',
					'meta'   => array( 'target' => '_blank' ),
				)
			);
		}
	}
}
