<?php

namespace OCM;

class OCM_BackgroundHelper
{
    public static function delete_all_batch_process()
    {
        global $wpdb;

        $table  = $wpdb->options;
        $column = 'option_name';

        if ( is_multisite() ) {
            $table  = $wpdb->sitemeta;
            $column = 'meta_key';
        }

        $wpdb->get_results($wpdb->prepare("DELETE FROM {$table} where {$column} LIKE 'wp_ocm_background_%_batch_%'", []));
        $wpdb->get_results($wpdb->prepare("DELETE FROM {$table} where {$column} LIKE '_transient_%'", []));
        $wpdb->get_results($wpdb->prepare("DELETE FROM {$table} where {$column} LIKE '_site_transient_%'", []));
        $wpdb->get_results($wpdb->prepare("DELETE FROM {$table} where {$column} = 'backup_steps'", []));
        $wpdb->get_results($wpdb->prepare("DELETE FROM {$table} where {$column} = 'restore_steps'", []));
    }

    public static function delete_deprecated_batch_process()
    {
        global $wpdb;

        $table  = $wpdb->options;
        $column = 'option_name';

        if ( is_multisite() ) {
            $table  = $wpdb->sitemeta;
            $column = 'meta_key';
        }

        $wpdb->get_results("DELETE FROM {$table} where {$column} LIKE 'wp_ocm_background_upload_batch_%' OR {$column} LIKE 'wp_ocm_background_backup_batch_%' OR {$column} LIKE 'wp_ocm_background_download_batch_%'");
    }
}
