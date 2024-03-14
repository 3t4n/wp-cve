<?php
/**
 * The file deletes the options from the wp_options in the database.
 *
 * @package miniorange-login-security
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// if uninstall not called from WordPress exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

	delete_site_option( 'mo2f_customerKey' );
	delete_site_option( 'Momls_Api_key' );
	delete_site_option( 'momls_wpns_customer_token' );
	delete_site_option( 'mo2f_app_secret' );
	delete_site_option( 'momls_wpns_message' );
	delete_site_option( 'momls_wpns_transactionId' );
	delete_site_option( 'momls_wpns_registration_status' );
	delete_site_option( 'mo2f_login_policy' );
	delete_site_option( 'mo2f_show_loginwith_phone' );
	delete_site_option( 'momls_wpns_show_remaining_attempts' );
	delete_site_option( 'momls_wpns_company' );
	delete_site_option( 'momls_wpns_firstName' );
	delete_site_option( 'momls_wpns_lastName' );
	delete_site_option( 'momls_wpns_password' );
	delete_site_option( 'mo2f_email' );
	delete_site_option( 'momls_wpns_admin_phone' );

	delete_site_option( 'momls_wpns_registration_status' );
	delete_site_option( 'momls_wpns_block_chrome' );
	delete_site_option( 'momls_wpns_block_firefox' );
	delete_site_option( 'momls_wpns_block_ie' );
	delete_site_option( 'momls_wpns_block_safari' );
	delete_site_option( 'momls_wpns_block_opera' );
	delete_site_option( 'momls_wpns_block_edge' );

	delete_site_option( 'momls_wpns_enable_htaccess_blocking' );
	delete_site_option( 'momls_wpns_enable_user_agent_blocking' );
	delete_site_option( 'momls_wpns_countrycodes' );
	delete_site_option( 'momls_wpns_referrers' );
	delete_site_option( 'protect_wp_config' );
	delete_site_option( 'prevent_directory_browsing' );
	delete_site_option( 'disable_file_editing' );
	delete_site_option( 'momls_wpns_enable_comment_spam_blocking' );
	delete_site_option( 'momls_wpns_enable_comment_recaptcha' );

	delete_site_option( 'momls_wpns_slow_down_attacks' );
	delete_site_option( 'mo2f_enforce_strong_passswords' );
	delete_site_option( 'mo2f_enforce_strong_passswords_for_accounts' );

	delete_site_option( 'mo2f_activate_plugin' );

	delete_site_option( 'mo2f_remember_device' );
	delete_site_option( 'momls_wpns_activate_recaptcha' );

	delete_site_option( 'momls_wpns_activate_recaptcha_for_login' );
	delete_site_option( 'momls_wpns_activate_recaptcha_for_registration' );
	delete_site_option( 'momls_wpns_activate_recaptcha_for_woocommerce_login' );
	delete_site_option( 'momls_wpns_activate_recaptcha_for_woocommerce_registration' );
	delete_site_option( 'momls_wpns_recaptcha_site_key' );
	delete_site_option( 'momls_wpns_recaptcha_secret_key' );

	delete_site_option( 'momls_wpns_enable_fake_domain_blocking' );
	delete_site_option( 'momls_wpns_enable_advanced_user_verification' );
	delete_site_option( 'mo_customer_validation_wp_default_enable' );
	delete_site_option( 'momls_wpns_enable_social_integration' );

	delete_site_option( 'momls_wpns_scan_plugins' );
	delete_site_option( 'momls_wpns_scan_themes' );
	delete_site_option( 'momls_wpns_check_vulnerable_code' );
	delete_site_option( 'momls_wpns_check_sql_injection' );
	delete_site_option( 'momls_wpns_scan_wp_files' );
	delete_site_option( 'momls_wpns_skip_folders' );
	delete_site_option( 'momls_wpns_check_external_link' );
	delete_site_option( 'momls_wpns_scan_files_with_repo' );
	delete_site_option( 'momls_wpns_files_scanned' );
	delete_site_option( 'momls_wpns_infected_files' );

	delete_site_option( 'momls_wpns_dbversion' );


	delete_site_option( 'mo_file_backup_plugins' );
	delete_site_option( 'mo_file_backup_themes' );
	delete_site_option( 'mo_file_backup_wp_files' );
	delete_site_option( 'mo2f_cron_file_backup_hours' );
	delete_site_option( 'mo2f_cron_hours' );
	delete_site_option( 'file_backup_created' );
	delete_site_option( 'db_backup_created' );
	delete_site_option( 'scheduled_file_backup' );
	delete_site_option( 'scheduled_db_backup' );
	delete_site_option( 'file_backup_created_time' );
	delete_site_option( 'db_backup_created_time' );

	delete_site_option( 'mo_database_backup' );
	delete_site_option( 'momls_wpns_backup_time' );
	delete_site_option( 'enable_backup_schedule' );
	delete_site_option( 'momls_wpns_dbversion' );
	delete_site_option( 'backup_created_time' );

	delete_site_option( 'mo2f_visit_waf' );
	delete_site_option( 'mo2f_visit_login_and_spam' );
	delete_site_option( 'mo2f_visit_malware' );
	delete_site_option( 'mo2f_visit_backup' );
	delete_site_option( 'mo2f_two_factor' );
	delete_site_option( 'mo_file_manual_backup_plugins' );
	delete_site_option( 'mo_file_manual_backup_themes' );
	delete_site_option( 'mo_schedule_database_backup' );

if ( get_site_option( 'is_onprem' ) ) {
	$users = get_users( array() );
	foreach ( $users as $user ) {
		delete_user_meta( $user->ID, 'currentMethod' );
		delete_user_meta( $user->ID, 'email' );
		delete_user_meta( $user->ID, 'mo2f_2FA_method_to_configure' );
		delete_user_meta( $user->ID, 'Security Questions' );
		delete_user_meta( $user->ID, 'Email Verification' );
		delete_user_meta( $user->ID, 'mo2f_kba_challenge' );
		delete_user_meta( $user->ID, 'mo2f_2FA_method_to_test' );
		delete_user_meta( $user->ID, 'kba_questions_user' );
		delete_user_meta( $user->ID, 'Google Authenticator' );
		delete_user_meta( $user->ID, 'mo2f_gauth_key' );
		delete_user_meta( $user->ID, 'mo2f_get_auth_rnd_string' );
	}
}

	$users = get_users( array() );
foreach ( $users as $user ) {
	delete_user_meta( $user->ID, 'phone_verification_status' );
	delete_user_meta( $user->ID, 'mo2f_test_2FA' );
	delete_user_meta( $user->ID, 'mo2f_2FA_method_to_configure' );
	delete_user_meta( $user->ID, 'configure_2FA' );
	delete_user_meta( $user->ID, 'mo2f_2FA_method_to_test' );
	delete_user_meta( $user->ID, 'mo2f_phone' );
	delete_user_meta( $user->ID, 'mo_2factor_user_registration_status' );
	delete_user_meta( $user->ID, 'mo2f_external_app_type' );
	delete_user_meta( $user->ID, 'mo2f_user_login_attempts' );
}

	// drop custom db tables.
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_transactions" );  //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_blocked_ips" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_whitelisted_ips" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_email_sent_audit" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_malware_scan_report" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_malware_scan_report_details" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_malware_skip_files" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_malware_hash_file" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename..
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_attack_logs" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_ip_rate_details" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.

	// Remove all values of 2FA on deactivate.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mo2f_user_details" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mo2f_user_login_info" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mo2f_network_blocked_ips" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mo2f_network_email_sent_audit" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename. 
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mo2f_network_transactions" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename. 
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mo2f_network_whitelisted_ips" ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching -- Ignoring complex schema change query as it is used for tablename. 

if ( ! is_multisite() ) {
	delete_site_option( 'mo2f_email' );
	delete_site_option( 'mo2f_host_name' );
	delete_site_option( 'user_phone' );
	delete_site_option( 'mo2f_customerKey' );
	delete_site_option( 'Momls_Api_key' );
	delete_site_option( 'mo2f_customer_token' );
	delete_site_option( 'mo2f_message' );
	delete_site_option( 'mo_2factor_admin_registration_status' );
	delete_site_option( 'mo2f_login_message' );
	delete_site_option( 'mo_2f_login_type_enabled' );
	delete_site_option( 'mo2f_admin_disabled_status' );
	delete_site_option( 'mo2f_disabled_status' );
	delete_site_option( 'mo2f_miniorange_admin' );
	delete_site_option( 'mo2f_enable_forgotphone' );
	delete_site_option( 'mo2f_enable_login_with_2nd_factor' );
	delete_site_option( 'mo2f_activate_plugin' );
	delete_site_option( 'mo2f_remember_device' );
	delete_site_option( 'mo2f_app_secret' );
	delete_site_option( 'mo2f_enable_custom' );
	delete_site_option( 'mo2f_show_sms_transaction_message' );
	delete_site_option( 'mo2f_admin_first_name' );
	delete_site_option( 'mo2_admin_last_name' );
	delete_site_option( 'mo2f_admin_company' );
	delete_site_option( 'mo2f_proxy_host' );
	delete_site_option( 'mo2f_port_number' );
	delete_site_option( 'mo2f_proxy_username' );
	delete_site_option( 'mo2f_proxy_password' );
	delete_site_option( 'mo2f_auth_methods_for_users' );
	delete_site_option( 'mo2f_enable_mobile_support' );
	delete_site_option( 'mo2f_login_policy' );
	delete_site_option( 'mo2f_show_loginwith_phone' );
	delete_site_option( 'mo2f_loginwith_phone' );
	delete_site_option( 'mo2f_msg_counter' );
	delete_site_option( 'mo2f_modal_display' );
	delete_site_option( 'mo2f_disable_poweredby' );
	delete_site_option( 'mo2f_new_customer' );
	delete_site_option( 'mo2f_enable_2fa_for_users' );
	delete_site_option( 'mo2f_phone' );
	delete_site_option( 'mo2f_existing_user_values_updated' );
	delete_site_option( 'mo2f_login_option_updated' );
	delete_site_option( 'mo2f_dbversion' );
	delete_site_option( 'mo2f_bug_fix_done' );
	delete_site_option( 'mo2f_feedback_form' );
	delete_site_option( 'mo2f_enable_2fa_prompt_on_login_page' );
	delete_site_option( 'mo2f_configured_2_factor_method' );
	delete_site_option( 'mo2f_enable_2fa' );
	delete_site_option( 'kba_questions' );
	delete_site_option( 'mo2f_admin_first_name' );
	delete_site_option( 'mo2_admin_last_name' );
	delete_site_option( 'mo2f_admin_company' );
	delete_site_option( 'mo2f_db_option_updated' );
	delete_site_option( 'mo2f_login_option_updated' );
	delete_site_option( 'mo2f_encryption_key' );
	delete_site_option( 'mo2f_google_appname' );
	// Network Security.
	delete_site_option( 'mo2f_ns_whitelist_ip' );
	delete_site_option( 'mo2f_enable_brute_force' );
	delete_site_option( 'mo2f_show_remaining_attempts' );
	delete_site_option( 'mo2f_ns_blocked_ip' );
	delete_site_option( 'mo2f_allwed_login_attempts' );
	delete_site_option( 'mo2f_time_of_blocking_type' );
	delete_site_option( 'mo2f_network_features' );


	delete_site_option( 'mo2f_custom_plugin_name' );
	delete_site_option( 'SQLInjection' );
	delete_site_option( 'WAFEnabled' );
	delete_site_option( 'XSSAttack' );
	delete_site_option( 'RFIAttack' );
	delete_site_option( 'LFIAttack' );
	delete_site_option( 'RCEAttack' );
	delete_site_option( 'actionRateL' );
	delete_site_option( 'Rate_limiting' );
	delete_site_option( 'Rate_request' );
	delete_site_option( 'limitAttack' );
	delete_site_option( 'skip_tour' );
	delete_site_option( 'momls_wpns_new_registration' );
	delete_site_option( 'mo2f_is_NC' );

	delete_site_option( 'momls_wpns_enable_log_requests' );
	delete_site_option( 'mo2f_data_storage' );
	delete_site_option( 'momls_wpns_scan_files_extensions' );
	delete_site_option( 'donot_show_feedback_message' );
	delete_site_option( 'login_page_url' );
	delete_site_option( 'momls_wpns_scan_mode' );
	delete_site_option( 'momls_wpns_malware_scan_in_progress' );
	delete_site_option( 'scan_failed' );
	delete_site_option( 'recovery_mode_email_last_sent' );
	delete_site_option( 'mo2f_is_NNC' );


	// delete all stored key-value pairs for the roles.
	global $wp_roles;
	foreach ( $wp_roles->role_names as $user_id => $name ) {
		delete_site_option( 'mo2fa_' . $user_id );
		delete_site_option( 'mo2fa_' . $user_id . '_login_url' );
	}
}
	delete_site_option( 'mo_2factor_admin_mobile_registration_status' );
	delete_site_option( 'mo_2factor_registration_status' );
	delete_site_option( 'mo_2factor_temp_status' );
	delete_site_option( 'mo2f_login_username' );
	delete_site_option( 'mo2f-login-qrCode' );
	delete_site_option( 'mo2f_transactionId' );
	delete_site_option( 'mo_2factor_login_status' );
	delete_site_option( 'mo2f_configured_2_factor_method' );
	delete_site_option( 'mo2f_enable_2fa' );
	delete_site_option( 'kba_questions' );
	delete_site_option( 'mo2f_customerKey' );

	delete_site_option( 'mo_2f_switch_waf' );
	delete_site_option( 'mo_2f_switch_loginspam' );
	delete_site_option( 'mo_2f_switch_backup' );
	delete_site_option( 'mo_2f_switch_malware' );
	delete_site_option( 'mo_2f_switch_adv_block' );
	delete_site_option( 'mo_2f_switch_reports' );
	delete_site_option( 'mo_2f_switch_notif' );

	delete_site_option( 'momls_wpns_last_themes' );
	delete_site_option( 'momls_wpns_last_plugins' );
	delete_site_option( 'momls_wpns_last_scan_time' );
	delete_site_option( 'infected_dismiss' );
	delete_site_option( 'weekly_dismiss' );
	delete_site_option( 'donot_show_infected_file_notice' );
	delete_site_option( 'donot_show_new_plugin_theme_notice' );
	delete_site_option( 'donot_show_weekly_scan_notice' );
