<?php

	//if uninstall not called from WordPress exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
		exit();

	delete_option('mo_lla_admin_customer_key');
	delete_option('mo_lla_admin_api_key');
	delete_option('mo_lla_customer_token');
	delete_option('mo_lla_app_secret');
	delete_option('mo_lla_message');
	delete_option('mo_lla_transactionId');
	delete_option('mo_lla_registration_status');
	delete_option('mo_lla_enable_brute_force');
	delete_option('mo_lla_show_remaining_attempts');
	delete_option('mo_lla_enable_ip_blocked_email_to_admin');
	delete_option('mo_lla_enable_unusual_activity_email_to_user');
	delete_option( 'mo_lla_company');
    delete_option( 'mo_lla_firstName' );
 	delete_option( 'mo_lla_lastName');
 	delete_option( 'mo_lla_password');
 	delete_option( 'mo_lla_admin_email');
 	delete_option( 'mo_lla_admin_phone');
 	delete_option( 'mo_lla_registration_status');
 	delete_option( 'mo_lla_block_chrome');
 	delete_option( 'mo_lla_block_firefox');
 	delete_option( 'mo_lla_block_ie');
    delete_option( 'mo_lla_block_safari');
    delete_option( 'mo_lla_block_opera');
    delete_option( 'mo_lla_block_edge');
    delete_option( 'mo_lla_enable_user_agent_blocking');
    delete_option( 'mo_lla_countrycodes');
    delete_option( 'mo_lla_referrers');
    delete_option( 'protect_wp_config');
    delete_option( 'prevent_directory_browsing');
    delete_option( 'disable_file_editing');
    delete_option( 'mo_lla_enable_comment_spam_blocking');
    delete_option( 'mo_lla_activate_recaptcha_for_comments');
    delete_option( 'mo_lla_slow_down_attacks');
	delete_option( 'mo_lla_activate_recaptcha');
	delete_option( 'mo_lla_activate_recaptcha_for_login');
	delete_option( 'mo_lla_activate_recaptcha_for_registration');
	delete_option( 'mo_lla_activate_recaptcha_for_woocommerce_login');
	delete_option( 'mo_lla_activate_recaptcha_for_woocommerce_registration');
	delete_option( 'mo_lla_recaptcha_site_key');
 	delete_option( 'mo_lla_recaptcha_secret_key');
 	delete_option('custom_user_template');
 	delete_option('custom_admin_template');
 	delete_option( 'mo_lla_enable_fake_domain_blocking');
 	delete_option( 'mo_lla_enable_advanced_user_verification');
 	delete_option('mo_customer_validation_wp_default_enable');
 	delete_option('mo_lla_dbversion');
	delete_site_option("lla_dont_show_enable_brute_force");
 	delete_option('limitlogin_activated_time');
 	delete_option('SQLInjection');
 	delete_option('WAFEnabled');
 	delete_option('XSSAttack');
 	delete_option('RFIAttack');
 	delete_option('LFIAttack');
 	delete_option('RCEAttack');
 	delete_option('actionRateL');
 	delete_option('Rate_limiting');
 	delete_option('Rate_request');
 	delete_option('limitAttack');
 	delete_option('mo_inactive_logout_duration');
 	delete_option('mo_lla_activate_recaptcha_for_buddypress_registration');
 	delete_option('mo_lla_login_page_url');
 	delete_option('mo_lla_enable_rename_login_url');
	delete_option('mo_wpns_dbversion');
	
	//drop custom db tables
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_transactions" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_blocked_ips" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_whitelisted_ips" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_email_sent_audit" );
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_attack_logs" );
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wpns_ip_rate_details" );
?>