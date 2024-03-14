<?php

namespace WPPayForm\Database\Migrations;

class SubmissionActivity
{
    public static function migrate($forced = false)
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'wpf_submission_activities';

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            form_id int(11) NOT NULL,
            submission_id int(11) NOT NULL,
            type varchar(255),
            created_by varchar(255),
            created_by_user_id int(11),
            title varchar(255),
            content text,
            created_at timestamp NULL,
            updated_at timestamp NULL,
            PRIMARY  KEY  (id)
        ) $charset_collate;";

        if ($forced) {
            return MigrateHelper::runForceSQL($sql, $table_name);
        }

        return MigrateHelper::runSQL($sql, $table_name);
    }
}
