<?php
/**
 * This file is used for creating sidebar menus.
 *
 * @author  Tech Banker
 * @package captcha-bank/lib
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}// Exit if accessed directly
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
		$flag = 0;

		$roles_and_capabilities_data             = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'roles_and_capabilities'
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
				$privileges = 'administrator_privileges';
				$flag       = $roles[0];
				break;

			case 'author':
				$privileges = 'author_privileges';
				$flag       = $roles[1];
				break;

			case 'editor':
				$privileges = 'editor_privileges';
				$flag       = $roles[2];
				break;

			case 'contributor':
				$privileges = 'contributor_privileges';
				$flag       = $roles[3];
				break;

			case 'subscriber':
				$privileges = 'subscriber_privileges';
				$flag       = $roles[4];
				break;

			default:
				$privileges = 'other_privileges';
				$flag       = $roles[5];
		}
		foreach ( $roles_and_capabilities_data_unserialize as $key => $value ) {
			if ( $privileges === $key ) {
				$privileges_value = $value;
				break;
			}
		}

		$full_control = explode( ',', $privileges_value );
		if ( ! defined( 'FULL_CONTROL' ) ) {
			define( 'FULL_CONTROL', "$full_control[0]" );
		}
		if ( ! defined( 'CAPTCHA_SETTINGS_CAPTCHA_BANK' ) ) {
			define( 'CAPTCHA_SETTINGS_CAPTCHA_BANK', "$full_control[1]" );
		}
		if ( ! defined( 'GENERAL_SETTINGS_CAPTCHA_BANK' ) ) {
			define( 'GENERAL_SETTINGS_CAPTCHA_BANK', "$full_control[2]" );
		}
		if ( ! defined( 'WHITELIST_CAPTCHA_BANK' ) ) {
			define( 'WHITELIST_CAPTCHA_BANK', "$full_control[3]" );
		}
		if ( ! defined( 'BLACKLIST_CAPTCHA_BANK' ) ) {
			define( 'BLACKLIST_CAPTCHA_BANK', "$full_control[4]" );
		}
		if ( ! defined( 'BLOCKAGE_SETTINGS_CAPTCHA_BANK' ) ) {
			define( 'BLOCKAGE_SETTINGS_CAPTCHA_BANK', "$full_control[5]" );
		}
		if ( ! defined( 'BLOCK_UNBLOCK_COUNTRIES_CAPTCHA_BANK' ) ) {
			define( 'BLOCK_UNBLOCK_COUNTRIES_CAPTCHA_BANK', "$full_control[6]" );
		}
		if ( ! defined( 'OTHER_SETTINGS_CAPTCHA_BANK' ) ) {
			define( 'OTHER_SETTINGS_CAPTCHA_BANK', "$full_control[7]" );
		}
		if ( ! defined( 'SYSTEM_INFORMATION_CAPTCHA_BANK' ) ) {
			define( 'SYSTEM_INFORMATION_CAPTCHA_BANK', "$full_control[8]" );
		}
		$check_captcha_bank_wizard = get_option( 'captcha-bank-wizard-set-up' );
		if ( '1' === $flag ) {
			if ( $check_captcha_bank_wizard ) {
				add_menu_page( $cpb_captcha_bank_title, $cpb_captcha_bank_title, 'read', 'captcha_bank', '', plugins_url( 'assets/global/img/icon.png', dirname( __FILE__ ) ) );
			} else {
				add_menu_page( $cpb_captcha_bank_title, $cpb_captcha_bank_title, 'read', 'captcha_bank_wizard', '', plugins_url( 'assets/global/img/icon.png', dirname( __FILE__ ) ) );
				add_submenu_page( $cpb_captcha_bank_title, $cpb_captcha_bank_title, '', 'read', 'captcha_bank_wizard', 'captcha_bank_wizard' );
			}
			add_submenu_page( 'captcha_bank', $cpb_captcha_wizard_label, $cpb_captcha_wizard_label, 'read', 'captcha_bank', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank' );

			add_submenu_page( 'captcha_bank', $cpb_notification_setup_label, $cpb_general_settings_menu, 'read', 'captcha_bank_notifications_setup', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_notifications_setup' );
			add_submenu_page( $cpb_message_settings_label, $cpb_message_settings_label, '', 'read', 'captcha_bank_message_settings', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_message_settings' );
			add_submenu_page( $cpb_email_templates_menu, $cpb_email_templates_menu, '', 'read', 'captcha_bank_email_templates', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_email_templates' );
			add_submenu_page( $cpb_roles_and_capabilities_menu, $cpb_roles_and_capabilities_menu, '', 'read', 'captcha_bank_roles_capabilities', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_roles_capabilities' );

			add_submenu_page( 'captcha_bank', $cpb_blockage_settings_label, $cpb_blockage_settings_label, 'read', 'captcha_bank_blockage_settings', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_blockage_settings' );
			add_submenu_page( 'captcha_bank', $cpb_ip_address_range, $cpb_ip_address_range, 'read', 'captcha_bank_block_unblock_ip_addresses', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_block_unblock_ip_addresses' );
			add_submenu_page( 'captcha_bank', $cpb_whitelist_ip_address, $cpb_whitelist_ip_address, 'read', 'captcha_bank_whitelist_ip_addresses', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_whitelist_ip_addresses' );
			add_submenu_page( 'captcha_bank', $cpb_block_unblock_countries_label, $cpb_block_unblock_countries_label, 'read', 'captcha_bank_block_unblock_countries', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_block_unblock_countries' );
			add_submenu_page( 'captcha_bank', $cpb_other_settings_menu, $cpb_other_settings_menu, 'read', 'captcha_bank_other_settings', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_other_settings' );
			add_submenu_page( 'captcha_bank', $cpb_feature_requests, $cpb_feature_requests, 'read', 'https://wordpress.org/support/plugin/captcha-bank' );
			add_submenu_page( 'captcha_bank', $cpb_system_information_menu, $cpb_system_information_menu, 'read', 'captcha_bank_system_information', false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : 'captcha_bank_system_information' );
			add_submenu_page( 'captcha_bank', $cpb_upgrade, $cpb_upgrade, 'read', 'https://tech-banker.com/captcha-bank/pricing/' );

		}
		if ( ! function_exists( 'captcha_bank_wizard' ) ) {
			/**
			 * Function Name: captcha_bank_wizard
			 * Parameters: No
			 * Description: This function is used to create wizard menu.
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_wizard() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/wizard/wizard.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/wizard/wizard.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank' ) ) {
			/**
			 * Function Name: captcha_bank
			 * Parameters: No
			 * Description: This function is used to create captcha setup menu.
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/captcha-setup/captcha-setup.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/captcha-setup/captcha-setup.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_notifications_setup' ) ) {
			/**
			 * Function Name: captcha_bank_notifications_setup
			 * Parameters: No
			 * Description: This function is used to create notification setup menu .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_notifications_setup() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/general-settings/notification-setup.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/general-settings/notification-setup.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_message_settings' ) ) {
			/**
			 * Function Name: captcha_bank_message_settings
			 * Parameters: No
			 * Description: This function is used to create message settings menu .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_message_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/general-settings/message-settings.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/general-settings/message-settings.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_email_templates' ) ) {
			/**
			 * Function Name: captcha_bank_email_templateS
			 * Parameters: No
			 * Description: This function is used to create email templates menu .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_email_templates() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/general-settings/email-templates.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/general-settings/email-templates.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_roles_capabilities' ) ) {
			/**
			 * Function Name: captcha_bank_roles_capabilities
			 * Parameters: No
			 * Description: This function is used to create roles and capabilities menu .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_roles_capabilities() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/general-settings/roles-and-capabilities.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/general-settings/roles-and-capabilities.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_whitelist_ip_addresses' ) ) {
			/**
			 * Function Name: captcha_bank_display_settings
			 * Parameters: No
			 * Description: This function is used to create display settings menu.
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_whitelist_ip_addresses() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/whitelist-ip-addresses/whitelist-ip-addresses.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/whitelist-ip-addresses/whitelist-ip-addresses.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_other_settings' ) ) {
			/**
			 * Function Name: captcha_bank_other_settings
			 * Parameters: No
			 * Description: This function is used to create other settings menu .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_other_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();

				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/other-settings/other-settings.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/other-settings/other-settings.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_blockage_settings' ) ) {
			/**
			 * Function Name: captcha_bank_blockage_settings
			 * Parameters: No
			 * Description: This function is used to create blockage settings menu .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_blockage_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/blockage-settings/blockage-settings.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/blockage-settings/blockage-settings.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_block_unblock_ip_addresses' ) ) {
			/**
			 * Function Name: captcha_bank_block_unblock_ip_addresses
			 * Parameters: No
			 * Description: This function is used to create block unblock ip address menu .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_block_unblock_ip_addresses() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/block-unblock-ip-address/block-unblock-ip-address.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/block-unblock-ip-address/block-unblock-ip-address.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_block_unblock_countries' ) ) {
			/**
			 * Function Name: captcha_bank_block_unblock_countries
			 * Parameters: No
			 * Description: This function is used to create block unblock country menu .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_block_unblock_countries() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/block-unblock-countries/block-unblock-countries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/block-unblock-countries/block-unblock-countries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_feature_requests' ) ) {
			/**
			 * Function Name: captcha_bank_feature_requests
			 * Parameters: No
			 * Description: This function is used to create feature request menu .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_feature_requests() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/feature-requests/feature-requests.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/feature-requests/feature-requests.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
		if ( ! function_exists( 'captcha_bank_system_information' ) ) {
			/**
			 * Function Name: captcha_bank_system_information
			 * Parameters: No
			 * Description: This function is used to create system information .
			 * Created On: 11-04-2017  10:53
			 * Created By: Tech Banker Team
			 */
			function captcha_bank_system_information() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_bank();

				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'views/system-information/system-information.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'views/system-information/system-information.php';
				}
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BANK_DIR_PATH . 'includes/footer.php';
				}
			}
		}
	}
}
