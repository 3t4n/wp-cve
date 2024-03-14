<?php

class MPG_CacheModel
{

    public static function mpg_get_current_caching_type($project_id)
    {
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT `cache_type` FROM {$wpdb->prefix}" .  MPG_Constant::MPG_PROJECTS_TABLE . " WHERE id=%d", $project_id)
        );

        return $results[0]->cache_type;
    }

    public static function mpg_set_current_caching_type($project_id, $type){
        global $wpdb;

        try {

            $fields_array['updated_at'] = time();
            $fields_array['cache_type'] = $type;


            $wpdb->update($wpdb->prefix . MPG_Constant::MPG_PROJECTS_TABLE, $fields_array, ['id' => $project_id]);

            return true;
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, sprintf( 'Can\'t update cache type by project ID. Details: %s', $e->getMessage() ), 'debug', __FILE__, __LINE__ );

            throw new Exception(__('Can\'t update cache type by project ID. Details:', 'mpg') . $e->getMessage());
        }
    }


    public static function mpg_get_row_from_database_cache($project_id, $url)
    {
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT `cached_string` FROM {$wpdb->prefix}" .  MPG_Constant::MPG_CACHE_TABLE . " WHERE `project_id`=%d AND url=%s", [$project_id, $url])
        );

        return $results? $results[0]->cached_string: null;
    }

    public static function  mpg_set_row_to_database_cache($project_id, $url, $cached_string){
        global $wpdb;

        $wpdb->insert($wpdb->prefix . MPG_Constant::MPG_CACHE_TABLE, array(
            'project_id' => $project_id,
            'url' => $url,
            'cached_string' => $cached_string
        ));

    }

    public static function mpg_flush_disk_cache($path)
    {
        if (is_dir($path)) {
            $files = glob($path . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

            foreach ($files as $file) {
                self::mpg_flush_disk_cache($file);
            }

            rmdir($path);
        } elseif (is_file($path)) {
            unlink($path);
        }
    }

    public static function mpg_delete_cached_records_from_db($project_id){

        global $wpdb;

        $wpdb->delete(  $wpdb->prefix . MPG_Constant::MPG_CACHE_TABLE, ['project_id' => $project_id]);
    }

    public static function mpg_count_cached_pages_by_project_id($project_id){

        global $wpdb;
        $table = $wpdb->prefix . MPG_Constant::MPG_CACHE_TABLE;

        $rows_in_cache = $wpdb->get_var("SELECT COUNT('id') FROM `$table` WHERE project_id = '$project_id'");

        return $rows_in_cache;
    }
}
