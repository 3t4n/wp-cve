<?php

/**
 * Update CRM new roles and capabilities
 *
 * @since 1.0
 *
 * @return void
 */
function wpwphr_update_1_0_set_role() {
    remove_role( 'wphr_hr_manager' );
    remove_role( 'employee' );
    remove_role( 'wphr_crm_manager' );
    remove_role( 'wphr_crm_agent' );

    $installer = new \clsWP_HR_Installer();
    $installer->create_roles();
}

/**
 * Create and update table schema
 *
 * @since 1.0
 *
 * @return void
 */
function wpwphr_update_1_0_create_table() {
    global $wpdb;

    $collate = '';

    if ( $wpdb->has_cap( 'collation' ) ) {
        if ( ! empty($wpdb->charset ) ) {
            $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
        }

        if ( ! empty($wpdb->collate ) ) {
            $collate .= " COLLATE $wpdb->collate";
        }
    }

    $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_crm_save_email_replies` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` text,
              `subject` text,
              `template` longtext,
              PRIMARY KEY (`id`)
            ) $collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $schema );
}

/**
 * Create wphr_people_types table
 *
 * @since 1.0
 *
 * @return void
 */
function wpwphr_update_1_0_create_people_types_table() {
    global $wpdb;

    $collate = '';

    if ( $wpdb->has_cap( 'collation' ) ) {
        if ( ! empty($wpdb->charset ) ) {
            $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
        }

        if ( ! empty($wpdb->collate ) ) {
            $collate .= " COLLATE $wpdb->collate";
        }
    }

    $types_table = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_people_types` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(20) DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `name` (`name`)
            ) $collate;";

    $relations_table = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wphr_people_type_relations` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `people_id` bigint(20) unsigned DEFAULT NULL,
                `people_types_id` int(11) unsigned DEFAULT NULL,
                `deleted_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `people_id` (`people_id`),
                KEY `people_types_id` (`people_types_id`)
            ) $collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $types_table );
    dbDelta( $relations_table );

    // seed the types table
    $seed_types = 'INSERT INTO ' . $wpdb->prefix . "wphr_people_types (name) VALUES ('contact'), ('company'), ('customer'), ('vendor');";
    $wpdb->query( $seed_types );
}

/**
 * Clear exisiting crons and setup new ones
 *
 * @return void
 */
function wpwphr_update_1_0_schedules() {
    // clear legacy crons
    wp_clear_scheduled_hook( 'wphr_hr_policy_schedule' );
    wp_clear_scheduled_hook( 'wphr_crm_notification_schedule' );

    // setup new crons
    wp_schedule_event( time(), 'per_minute', 'wphr_per_minute_scheduled_events' );
    wp_schedule_event( time(), 'daily', 'wphr_daily_scheduled_events' );
    wp_schedule_event( time(), 'weekly', 'wphr_weekly_scheduled_events' );
}

/**
 * Drop the type column in people table
 *
 * @since 1.0
 *
 * @return void
 */
function wpwphr_update_1_0_drop_types_column() {
    global $wpdb;
    $wpdb->query( "ALTER TABLE {$wpdb->prefix}wphr_peoples DROP COLUMN `type`, `deleted_at`" );
}

wpwphr_update_1_0_set_role();
wpwphr_update_1_0_schedules();

wpwphr_update_1_0_create_table();
wpwphr_update_1_0_create_people_types_table();
wpwphr_update_1_0_drop_types_column();