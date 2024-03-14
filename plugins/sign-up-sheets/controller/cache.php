<?php
/**
 * Cache Class
 */

namespace FDSUS\Controller;

use FDSUS\Model\Data;

class Cache
{

    public $data;

    public function __construct()
    {
        $this->data = new Data();

        add_action('fdsus_after_add_signup', array(&$this, 'clearSignupCache'), 9, 2);
        add_action('fdsus_after_update_signup', array(&$this, 'clearSignupCache'), 9, 2);
        add_action('fdsus_after_delete_signup', array(&$this, 'clearSignupCache'), 9, 2);
    }

    /**
     * Clears the cache for the sheet the signup is associated with
     *
     * @param int $signupId
     * @param int $taskId
     *
     * @return void
     */
    public function clearSignupCache($signupId, $taskId = 0)
    {
        if ($signupId) {

            // Breeze
            do_action('breeze_clear_all_cache');

            if (!$taskId) {
                $taskId = wp_get_post_parent_id($signupId);
            }
            if ($taskId) {
                $sheetId = wp_get_post_parent_id($taskId);
                if ($sheetId) {

                    // W3 Total Cache
                    if (function_exists('w3tc_flush_post')) {
                        w3tc_flush_post($sheetId);
                        w3tc_flush_post($taskId);
                        w3tc_flush_post($signupId);
                    }
                    if (function_exists('w3tc_dbcache_flush')) {
                        w3tc_dbcache_flush();
                    }

                    // WP Super Cache
                    if (function_exists('wpsc_delete_post_cache')) {
                        wpsc_delete_post_cache($sheetId);
                        wpsc_delete_post_cache($taskId);
                        wpsc_delete_post_cache($signupId);
                    }

                    // WP-Optimize
                    if (class_exists('WPO_Page_Cache')) {
                        \WPO_Page_Cache::delete_single_post_cache($sheetId);
                        \WPO_Page_Cache::delete_single_post_cache($taskId);
                        \WPO_Page_Cache::delete_single_post_cache($signupId);
                    }

                    // LiteSpeed Cache
                    do_action('litespeed_purge_post', $sheetId);
                    do_action('litespeed_purge_post', $taskId);
                    do_action('litespeed_purge_post', $signupId);

                    // WP Fastest Cache
                    if (function_exists('wpfc_clear_post_cache_by_id')) {
                        wpfc_clear_post_cache_by_id($sheetId);
                        wpfc_clear_post_cache_by_id($taskId);
                        wpfc_clear_post_cache_by_id($signupId);
                    }

                }
            }
        }
    }

}
