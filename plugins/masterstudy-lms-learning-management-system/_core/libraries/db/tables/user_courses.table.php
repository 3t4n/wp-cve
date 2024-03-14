<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_user_courses' );

function stm_lms_user_courses() {
	global $wpdb;

	$table_name = stm_lms_user_courses_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$locale = get_locale();

	$sql = "CREATE TABLE $table_name (
		user_course_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint NOT NULL,
		course_id mediumint(9) NOT NULL,
		current_lesson_id mediumint(9),
		progress_percent mediumint(9) NOT NULL,
		status varchar(45) NOT NULL DEFAULT '',
		lng_code varchar(45) NOT NULL DEFAULT '$locale',
		subscription_id mediumint(9),
		enterprise_id float(9) DEFAULT 0,
		instructor_id float(9) DEFAULT 0,
		bundle_id float(9) DEFAULT 0,
		start_time INT NOT NULL,
		end_time INT DEFAULT 0,
		PRIMARY KEY (user_course_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
