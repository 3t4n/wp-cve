<?php

/**
 * YouTube License Tier
 * 
 * @since 2.1
 */
namespace Smashballoon\Customizer;

use SmashBalloon\YoutubeFeed\Vendor\Smashballoon\Framework\Packages\License_Tier\License_Tier;
class YouTube_License_Tier extends License_Tier
{
    /**
     * This gets the license key 
     */
    public $license_key_option_name = 'sby_license_key';
    /**
     * This gets the license status
     */
    public $license_status_option_name = 'sby_license_status';
    /**
     * This gets the license data
     */
    public $license_data_option_name = 'sby_license_data';
    public $item_id_basic = 762236;
    public $item_id_plus = 762320;
    public $item_id_elite = 762322;
    public $item_id_all_access = 789157;
    public $license_tier_basic_name = 'personal';
    public $license_tier_plus_name = 'business';
    public $license_tier_elite_name = 'developer';
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * This defines the features list of the plugin
     * 
     * @return void
     */
    public function features_list()
    {
        $features_list = ['personal' => ['channel_feeds', 'favorites_feeds', 'playlist_feeds', 'carousel_feeds', 'combine_feeds', 'performance_optimization', 'downtime_prevention_system', 'gbpr_compliant', 'call_to_actions', 'search_feeds', 'single_feeds', 'feeds_templates', 'convert_videos_to_cpt', 'live_feeds', 'video_filtering', 'feed_themes'], 'business' => ['call_to_actions', 'search_feeds', 'single_feeds', 'feeds_templates', 'convert_videos_to_cpt'], 'developer' => ['live_feeds', 'video_filtering', 'feed_themes']];
        $this->plugin_features = $features_list;
    }
}
