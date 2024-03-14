<?php

namespace WP_Rplg_Google_Reviews\Includes;

use WP_Rplg_Google_Reviews\Includes\Core\Connect_Google;

class Reviews_Cron {

    private $connect_google;
    private $feed_deserializer;

    public function __construct(Connect_Google $connect_google, Feed_Deserializer $feed_deserializer) {
        $this->connect_google = $connect_google;
        $this->feed_deserializer = $feed_deserializer;
    }

    public function register() {
        add_action('grw_revupd_schedule', array($this, 'update_schedule'));

        $revupd_cron = get_option('grw_revupd_cron');
        $next_cron_run = wp_next_scheduled('grw_revupd_schedule');

        if ($revupd_cron !== '0' && !$next_cron_run) {
            $start_at = rand(0, 60 * 60 * 12);
            wp_schedule_event($start_at, 'daily', 'grw_revupd_schedule');
        } elseif ($next_cron_run) {
            update_option('grw_revupd_cron_timeout', $next_cron_run - time());
        }
    }

    public function update_schedule() {

        $start_time       = floor(microtime(true) * 1000);
        $end_time         = 0;
        $feed_updated_ids = array();

        $feed_conn_cache  = array();

        $feed_ids         = get_option('grw_feed_ids');
        $ids              = explode(",", $feed_ids);
        $ids_count        = count($ids);

        if ($ids_count > 0) {

            // Trying to walk by all reviews feed to update these
            for ($i = 0; $i < $ids_count; $i++) {

                // Get next reviews feed ID
                $id = array_shift($ids);

                // Get next reviews feed
                $feed = $this->feed_deserializer->get_feed($id);
                if ($feed != false && strlen($feed->post_content) > 0) {

                    // Parse json content to obtain reviews connectors
                    $json = json_decode($feed->post_content);
                    if ($json->connections && count($json->connections) > 0) {

                        // Loop by all connectors without repeatable
                        foreach ($json->connections as $conn) {

                            // Create pair 'place_id:lang' to update it
                            $arg_local_img = isset($conn->local_img) ? $conn->local_img : 'false';

                            $args = array($conn->id, $conn->lang, $arg_local_img);
                            $args_key = implode(":", $args);

                            // If not met, update
                            if (!in_array($args_key, $feed_conn_cache)) {
                                $this->connect_google->grw_refresh_reviews($args);
                                array_push($feed_conn_cache, $args_key);
                            }
                        }

                        // Put reviews feed ID to log
                        array_push($feed_updated_ids, $id);

                        // Put reviews feed ID to the end of feed_ids option
                        array_push($ids, $id);
                        update_option('grw_feed_ids', implode(",", $ids));

                        // Clear feed cache
                        delete_transient('grw_feed_' . GRW_VERSION . '_' . $id . '_reviews', false);

                        // Check execution time
                        $end_time = floor(microtime(true) * 1000) - $start_time;
                        if ($end_time > 500) {
                            break;
                        }

                    }
                }
            }

        }

        // Log information
        $now = floor(microtime(true) * 1000);
        update_option('grw_revupd_cron_log', 'Executed at ' . $now . ' in ' . $end_time . 'ms for feeds: ' . implode(", ", $feed_updated_ids));
    }

    public function deactivate() {
        $next_scheduled = wp_next_scheduled('grw_revupd_schedule');
        if ($next_scheduled) {
            wp_unschedule_event($next_scheduled, 'grw_revupd_schedule');
            update_option('grw_revupd_cron_timeout', '');
        }
    }
}