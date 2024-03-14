<?php

class LpageryMapper
{

    public static function lpagery_map_post($post)
    {
        return array("id" => $post->ID, "title" => $post->post_title);
    }

    public static function lpagery_map_post_extended($post)
    {
        if (!is_array($post)) {
            $post = (array)$post;
        }

        return array(
            "id" => $post["ID"],
            "title" => $post["post_title"],
            "process_id" => $post["process_id"],
            "permalink" => get_permalink($post["ID"]),
            "modified" => boolval($post["modified"])
        );
    }

    public static function lpagery_map_process($lpagery_process)
    {
        $user = get_user_by("id", $lpagery_process->user_id);
        $phpdate = strtotime($lpagery_process->created);
        $mysqldate = date('Y-m-d', $phpdate);
        if (empty($lpagery_process->purpose)) {
            $post_type = ucfirst(get_post_type($lpagery_process->post_id));
            $purpose_text = $post_type . " Creation by " . $user->display_name . " at " . $mysqldate;
        } else {
            $purpose_text = $lpagery_process->purpose . " by " . $user->display_name;
        }
        [$next_sync, $last_sync, $status] = self::get_google_sheet_sync_details($lpagery_process);

        return array(
            "id" => $lpagery_process->id,
            "post_id" => $lpagery_process->post_id,
            "user" => array("name" => $user->display_name, "email" => $user->user_email),
            "post_count" => $lpagery_process->count,
            "display_purpose" => $purpose_text,
            "google_sheet_data" => maybe_unserialize($lpagery_process->google_sheet_data),
            "raw_purpose" => $lpagery_process->purpose,
            "google_sheet_sync_error" => $lpagery_process->google_sheet_sync_error,
            "next_google_sheet_sync" => $next_sync,
            "last_google_sheet_sync" => $last_sync,
            "google_sheet_sync_status" => $status,
            "google_sheet_sync_enabled" => filter_var($lpagery_process->google_sheet_sync_enabled, FILTER_VALIDATE_BOOLEAN),
            "created" => $mysqldate
        );
    }

    public static function lpagery_map_process_search($lpagery_process)
    {
        $phpdate = strtotime($lpagery_process->created);
        $user = get_user_by("id", $lpagery_process->user_id);

        $mysqldate = date('Y-m-d', $phpdate);
        $post = get_post($lpagery_process->post_id);
        if ($post) {
            $post_array = array(
                "title" => $post->post_title,
                "permalink" => get_permalink($post),
                "type" => get_post_type($post),
                "deleted" => false
            );
        } else {
            $post_array = array(
                "title" => "Deleted (ID: " . $lpagery_process->post_id . ")",
                "deleted" => true
            );
        }

        [$next_sync, $last_sync, $status] = self::get_google_sheet_sync_details($lpagery_process);
        $google_sheet_data = null;
        if(isset( $lpagery_process->google_sheet_data)) {
            $unserialize = maybe_unserialize($lpagery_process->google_sheet_data);
            if($unserialize)
            $google_sheet_data = $unserialize["url"];
        }
        return array(
            "id" => $lpagery_process->id,
            "post_id" => $lpagery_process->post_id,
            "user_id" => $lpagery_process->user_id,
            "user" => array("name" => $user->display_name, "email" => $user->user_email),
            "post_count" => $lpagery_process->count,
            "display_purpose" => $lpagery_process->purpose,
            "google_sheet_sync_enabled" => filter_var($lpagery_process->google_sheet_sync_enabled, FILTER_VALIDATE_BOOLEAN),
            "created" => $mysqldate,
            "next_google_sheet_sync" => $next_sync,
            "last_google_sheet_sync" => $last_sync,
            "google_sheet_sync_status" => $status,
            "google_sheet_url" => $google_sheet_data,
            "post" => $post_array
        );
    }


    public static function lpagery_map_process_update_details($lpagery_process, $data)
    {
        $mapped_process = self::lpagery_map_process($lpagery_process);
        $mapped_data = array_map(function ($element) {

            $unserialized = maybe_unserialize($element->data);
            if (property_exists($element, "permalink")) {
                $unserialized['permalink'] = ($element->permalink);
            }

            return $unserialized;
        }, $data);

        return array("process" => $mapped_process,
            "data" => $mapped_data,
            "config_data" => maybe_unserialize($lpagery_process->data),
            "google_sheet_sync_enabled" => $lpagery_process->google_sheet_sync_enabled,
            "google_sheet_data" => maybe_unserialize($lpagery_process->google_sheet_data),
        );
    }

    private static function get_google_sheet_sync_details($process)
    {
        $status = $process->google_sheet_sync_status;
        $next_sync = wp_next_scheduled("lpagery_sync_google_sheet");
        $last_sync = get_date_from_gmt($process->last_google_sheet_sync, 'U');

        $current_time = current_time('U', true);
        $interval = wp_get_schedules()[get_option("lpagery_google_sheet_sync_interval", "hourly")]["interval"];


        $time_difference_next_sync = $next_sync - $current_time;
        $time_difference_last_sync = $current_time - $last_sync;

        if (!$status) {
            $status = "PLANNED";
        }

        // if the last sync happened more than 15 minutes ago
        if ($time_difference_last_sync > 900) {
            // 15 minutes
            $past_due_threshold = $interval + 900;
            if ($time_difference_next_sync >= 0 || !$process->google_sheet_sync_enabled) {
                return [$next_sync, $last_sync, $status];
            }
            if ($time_difference_next_sync < 0 && abs($time_difference_next_sync) <= $past_due_threshold) {
                $status = "PLANNED";
            } elseif ($time_difference_next_sync < 0 && abs($time_difference_next_sync) > $past_due_threshold) {
                $status = "PAST_DUE";
            }
        }

        return [$next_sync, $last_sync, $status];

    }

}