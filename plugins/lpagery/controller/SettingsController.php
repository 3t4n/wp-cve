<?php
require_once(plugin_dir_path(__FILE__) . '../data/LPageryDao.php');

class LPagerySettingsController
{

    static function lpagery_filter_post_type($type)
    {
        return !in_array($type, array(
            "post",
            "page",
            "attachment",
            "revision",
            "nav_menu_item",
            "custom_css",
            "customize_changeset",
            "oembed_cache",
            "user_request",
            "wp_block",
            "wp_template",
            "wp_template_part",
            "wp_global_styles",
            "wp_navigation"
        ));
    }

    public static function lpagery_get_post_types()
    {
        return array_filter(get_post_types(array(
            'public' => true
        )), [
            __CLASS__,
            "lpagery_filter_post_type"
        ]);
    }

    public static function lpagery_save_settings($spintax, $post_types, $image_processing_enabled, $author_id, $google_sheet_sync_interval,  $next_google_sheet_sync )
    {
        $settings = array(
            'spintax' => $spintax,
            'image_processing' => $image_processing_enabled,
            'custom_post_types' => $post_types,
            'author_id' => $author_id
        );

        update_user_option(get_current_user_id(), 'lpagery_settings', serialize($settings), false);

        $option = get_option("lpagery_google_sheet_sync_interval");
        if (!$option) {
            add_option("lpagery_google_sheet_sync_interval", $google_sheet_sync_interval);
            do_action('lpagery_google_sheet_schedule_changed', $next_google_sheet_sync);
        } else {
            $old_interval = get_option("lpagery_google_sheet_sync_interval", $google_sheet_sync_interval);
            $old_timestamp = wp_next_scheduled("lpagery_sync_google_sheet");
            if ($old_interval !== $google_sheet_sync_interval ||$old_timestamp != $next_google_sheet_sync) {
                update_option("lpagery_google_sheet_sync_interval", $google_sheet_sync_interval);
                do_action('lpagery_google_sheet_schedule_changed',  $next_google_sheet_sync);
            }
        }
    }

    public static function lpagery_get_settings()
    {
        return json_encode(maybe_unserialize(self::lpagery_get_settings_internal()));
    }

    public static function lpagery_get_spintax_enabled($process_id)
    {
        $settings = unserialize(get_user_option('lpagery_settings', self::get_user_id($process_id)));

        if ($settings == null) {
            return filter_var(self::lpagery_get_default_settings()['spintax'], FILTER_VALIDATE_BOOLEAN);
        }

        return filter_var($settings['spintax'], FILTER_VALIDATE_BOOLEAN);
    }

    public static function lpagery_get_image_processing_enabled($process_id = null)
    {
        if(!$process_id) {
            $user_id = get_current_user_id();
        } else {
        $user_id = self::get_user_id($process_id);
        }
        $settings = unserialize(get_user_option('lpagery_settings', $user_id));

        if ($settings == null) {
            return filter_var(self::lpagery_get_default_settings()['image_processing'], FILTER_VALIDATE_BOOLEAN);
        }

        return filter_var($settings['image_processing'], FILTER_VALIDATE_BOOLEAN);
    }

    public static function lpagery_get_author_id($process_id)
    {
        $settings = unserialize(get_user_option('lpagery_settings', self::get_user_id($process_id)));

        if ($settings == null) {
            return filter_var(self::lpagery_get_default_settings()['author_id'], FILTER_VALIDATE_INT);
        }

        return filter_var($settings['author_id'], FILTER_VALIDATE_INT);
    }

    private static function get_user_id($process_id)
    {
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            $process = LPageryDao::lpagery_get_process_by_id($process_id);
            if (empty($process)) {
                return 0;
            }
            return $process->user_id;
        }
        return $current_user_id;
    }


    private static function lpagery_get_settings_internal()
    {
        if (lpagery_fs()->is_free_plan() || lpagery_fs()->is_plan_or_trial("standard", true)) {
            return self::lpagery_get_default_settings();
        }
        $user_options = maybe_unserialize(get_user_option('lpagery_settings', get_current_user_id()));
        if ($user_options == null) {
            return self::lpagery_get_default_settings();
        }
        if (!isset($user_options["author_id"])) {
            $user_options["author_id"] = strval(get_current_user_id());
        }
        $user_options["google_sheet_sync_interval"] = get_option("lpagery_google_sheet_sync_interval", "hourly");
        $user_options["next_google_sheet_sync"] = get_date_from_gmt(date('Y-m-d\TH:i:s.Z\Z', wp_next_scheduled("lpagery_sync_google_sheet")), 'Y-m-d\TH:i'); ;

        return $user_options;
    }

    private static function lpagery_get_default_settings()
    {
        return array(
            "spintax" => false,
            'image_processing' => false,
            'author_id' => get_current_user_id(),
            "next_google_sheet_sync" => get_date_from_gmt(date('Y-m-d\TH:i:s.Z\Z', wp_next_scheduled("lpagery_sync_google_sheet")), 'Y-m-d\TH:i'),
            "google_sheet_sync_interval" => get_option("lpagery_google_sheet_sync_interval", "hourly"),

        );
    }

}