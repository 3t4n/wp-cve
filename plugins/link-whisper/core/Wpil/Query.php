<?php

/**
 * Work with DB queries
 */
class Wpil_Query
{
    /**
     * Get post statuses query row
     *
     * @param string $table
     * @return string
     */
    public static function postStatuses($table = '')
    {
        $query = "";
        $statuses = Wpil_Settings::getPostStatuses();
        $statuses = array('publish'); // todo remove if we ever make this a thing
        if (!empty($statuses)) {
            $query = " AND " . (!empty($table) ? $table."." : "") . "post_status IN ('" . implode("', '", $statuses) . "') ";
        }

        return $query;
    }

    /**
     * Get post types query row
     *
     * @param string $table
     * @return string
     */
    public static function postTypes($table = '')
    {
        $query = "";
        $post_types = Wpil_Settings::getPostTypes();
        if (!empty($post_types)) {
            $query = " AND " . ((!empty($table)) ? $table . ".post_type" : "`post_type`") . " IN ('" . implode("', '", $post_types) . "') ";
        }

        return $query;
    }

    /**
     * Get term taxonomy query row
     *
     * @param string $table
     * @return string
     */
    public static function taxonomyTypes($table = '')
    {
        $query = "";
        $taxonomies = Wpil_Settings::getTermTypes();
        if (!empty($taxonomies)) {
            $query = " AND taxonomy IN ('" . implode("', '", $taxonomies) . "')";
        }

        return $query;
    }

    /**
     * Get posts IDs for report query
     * Currently only gets the orphaned post ids if we're loading the Orphaned Report
     *
     * @param false $orphaned
     * @return string
     */
    public static function reportPostIds($orphaned = false)
    {
        global $wpdb;

        if(!$orphaned){
            return "";
        }else{
            $post_status = self::postStatuses('a');
            $post_types = self::postTypes('a');

            $ids1 = $wpdb->get_col("SELECT a.ID FROM {$wpdb->posts} a LEFT JOIN {$wpdb->postmeta} b ON a.ID = b.post_id WHERE 1=1 {$post_status} {$post_types} AND b.meta_key = 'wpil_sync_report3' AND b.meta_value = '1'");
            $ids2 = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'wpil_links_inbound_internal_count' AND meta_value = '0'");
            $ids = array_intersect($ids1, $ids2);

            // remove any links that are on the ignore orphan list
            $ignored = Wpil_Settings::getItemTypeIds(Wpil_Settings::getIgnoreOrphanedPosts(), 'post');
            $ids = array_diff($ids, $ignored);

            // also remove any posts that are hidden by redirects
            $redirected = Wpil_Settings::getRedirectedPosts();
            $ids = array_diff($ids, $redirected);
        }

        return !empty($ids) ? " AND p.ID IN (" . implode(',', $ids) . ")" : " AND 1 = 0";
    }

    /**
     * Get terms IDs for report query
     *
     * @param false $orphaned
     * @return string
     */
    public static function reportTermIds($orphaned = false, $hide_noindex = false)
    {
        global $wpdb;

        $ids = $wpdb->get_col("SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key = 'wpil_sync_report3' AND meta_value = '1'");

        if(!empty($ids)){
            $taxonomies = self::taxonomyTypes();
            $ids2 = implode(',', $ids);
            $ids = array_intersect($ids, $ids = $wpdb->get_col("SELECT term_id FROM {$wpdb->term_taxonomy} WHERE term_id IN ({$ids2}) {$taxonomies}"));
        }

        return implode(',', $ids);
    }
}
