<?php
/**
 * This file is used for creating admin bar menus.
 *
 * @author  Tech Banker
 * @package captcha-bank/lib
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
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
		$flag                                    = 0;
		$roles_and_capabilities_data             = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'roles_and_capabilities'
			)
		); // db call ok; no-cache ok.
		$roles_and_capabilities_data_unserialize = maybe_unserialize( $roles_and_capabilities_data );
		$roles                                   = explode( ',', $roles_and_capabilities_data_unserialize['roles_and_capabilities'] );
		if ( is_super_admin() ) {
			$cpb_role = 'administrator';
		} else {
			$cpb_role = check_user_roles_captcha_bank();
		}
		switch ( $cpb_role ) {
			case 'administrator':
				$flag = $roles[0];
				break;

			case 'author':
				$flag = $roles[1];
				break;

			case 'editor':
				$flag = $roles[2];
				break;

			case 'contributor':
				$flag = $roles[3];
				break;

			case 'subscriber':
				$flag = $roles[4];
				break;

			default:
				$flag = $roles[5];
		}

		if ( '1' === $flag ) {
			global $wp_version;
			$wp_admin_bar->add_menu(
				array(
					'id'    => 'captcha_bank',
					'title' => '<img src = "' . plugins_url( 'assets/global/img/icon.png', dirname( __FILE__ ) ) .
					"\" width=\"25\" height=\"10\" style=\"vertical-align:text-top; margin-top: 2px; display:inline-block; margin-right:5px;\"./>$cpb_captcha_bank_title",
					'href'  => admin_url( 'admin.php?page=captcha_bank' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_captcha_setup',
					'title'  => $cpb_captcha_wizard_label,
					'href'   => admin_url( 'admin.php?page=captcha_bank' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_general_settings',
					'title'  => $cpb_general_settings_menu,
					'href'   => admin_url( 'admin.php?page=captcha_bank_notifications_setup' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_blockage_settings',
					'title'  => $cpb_blockage_settings_label,
					'href'   => admin_url( 'admin.php?page=captcha_bank_blockage_settings' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_ip_address_range',
					'title'  => $cpb_ip_address_range,
					'href'   => admin_url( 'admin.php?page=captcha_bank_block_unblock_ip_addresses' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_whitelist_ip_addresses',
					'title'  => $cpb_whitelist_ip_address,
					'href'   => admin_url( 'admin.php?page=captcha_bank_whitelist_ip_addresses' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_block_unblock_countries',
					'title'  => $cpb_block_unblock_countries_label,
					'href'   => admin_url( 'admin.php?page=captcha_bank_block_unblock_countries' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_other_settings',
					'title'  => $cpb_other_settings_menu,
					'href'   => admin_url( 'admin.php?page=captcha_bank_other_settings' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_feature_requests',
					'title'  => $cpb_feature_requests,
					'href'   => 'https://wordpress.org/support/plugin/captcha-bank',
					'meta'   => array( 'target' => '_blank' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_system_information',
					'title'  => $cpb_system_information_menu,
					'href'   => admin_url( 'admin.php?page=captcha_bank_system_information' ),
				)
			);
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'captcha_bank',
					'id'     => 'captcha_bank_premium_edition',
					'title'  => $cpb_upgrade,
					'href'   => 'https://tech-banker.com/captcha-bank/pricing/',
					'meta'   => array( 'target' => '_blank' ),
				)
			);
		}
	}
}
