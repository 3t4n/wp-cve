<?php

/** Actions *******************************************************************/

// process wphr actions on admin_init
add_filter( 'login_redirect', 'wphr_login_redirect', 10, 3 );
add_action( 'init', 'wphr_process_init_actions' );
add_action( 'admin_init', 'wphr_process_actions' );
add_action( 'admin_footer', 'wphr_import_export_javascript' );
add_action( 'admin_init', 'wphr_process_import_export' );
add_action( 'admin_footer', 'wphr_email_settings_javascript' );
add_action( 'admin_notices', 'wphr_importer_notices' );
add_action( 'admin_init', 'wphr_import_export_download_sample_action' );

/** Filters *******************************************************************/

add_filter( 'map_meta_cap', 'wphr_map_meta_caps', 10, 4 );
add_filter( 'cron_schedules', 'wphr_cron_intervals', 10, 1 );
add_filter( 'ajax_query_attachments_args', 'wphr_show_users_own_attachments', 1, 1 );
add_filter( 'the_title', 'wphr_user_profile_label' );
add_filter( 'pre_wp_nav_menu', 'wphr_remove_title_filter' );
add_filter( 'wp_nav_menu', 'wphr_add_title_filter' );

