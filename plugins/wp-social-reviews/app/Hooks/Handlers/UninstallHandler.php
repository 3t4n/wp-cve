<?php
namespace WPSocialReviews\App\Hooks\Handlers;
use WPSocialReviews\App\Services\Platforms\PlatformManager;
use WPSocialReviews\Framework\Support\Arr;

class UninstallHandler
{
    public function handle()
    {
        $manager = new PlatformManager();
        if (!current_user_can('manage_options')) {
            return;
        }

        $advanceSettings = get_option('advance_settings');
        
        if(Arr::get($advanceSettings, 'preserve_plugin_data') === 'true') {
            return;
        }

        // add settings option condition
        $reviewsPlatforms = $manager->reviewsPlatforms();
        foreach ($reviewsPlatforms as $platform){
            delete_option('wpsr_reviews_' . $platform . '_settings');
            delete_option('wpsr_reviews_' . $platform . '_business_info');
            delete_option('wpsr_' . $platform . '_global_settings');
        }

        // delete google reviews accounts and locations settings from options table
        delete_option('wpsr_reviews_google_connected_accounts');
        delete_option('wpsr_reviews_google_locations_list');

        // delete fluent forms wpsr settings
        delete_option('wpsr_fluent_forms_global_settings');

        // delete custom reviews option from option table
        delete_option('wpsr_reviews_custom_business_info');

        // delete facebook reviews settings from options table
        delete_option('wpsr_reviews_facebook_pages_list');

        // delete facebook feeds settings from options table
        delete_option('wpsr_facebook_feed_verification_configs');
        delete_option('wpsr_facebook_feed_connected_sources_config');
        delete_option('wpsr_facebook_feed_authorized_sources');
        delete_option('wpsr_facebook_feed_global_settings');

        // delete instagram feeds settings from options table
        delete_option('wpsr_instagram_verification_configs');
        delete_option('wpsr_instagram_global_settings');

        // delete twitter feeds settings from options table
        delete_option('wpsr_twitter_verification_configs');
        delete_option('wpsr_twitter_global_settings');

        // delete youtube feeds settings from options table
        delete_option('wpsr_youtube_verification_configs');
        delete_option('wpsr_youtube_global_settings');

        global $wpdb;
        //remove wpsr_caches table
        $wpsr_caches_table = $wpdb->prefix .'wpsr_caches';
        $wpsr_caches_table_query = "DROP TABLE IF EXISTS `{$wpsr_caches_table}`;";
        $wpdb->query($wpsr_caches_table_query); // phpcs:ignore

        //remove wpsr_reviews table
        $wpsr_reviews_table = $wpdb->prefix .'wpsr_reviews';
        $wpsr_reviews_table_query = "DROP TABLE IF EXISTS `{$wpsr_reviews_table}`;";
        $wpdb->query($wpsr_reviews_table_query); // phpcs:ignore

        //remove wpsr all template meta from postmeta table
        $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_wpsr\_%';"); // phpcs:ignore
    }
}
