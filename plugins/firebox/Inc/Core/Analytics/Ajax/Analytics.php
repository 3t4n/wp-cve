<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Analytics\Ajax;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use \FireBox\Core\Helpers\BoxHelper;

class Analytics
{
    use Shared;

    public function __construct()
    {
        add_action('wp_ajax_firebox_analytics_most_popular_campaigns', [$this, 'firebox_analytics_most_popular_campaigns']);
        add_action('wp_ajax_nopriv_firebox_analytics_most_popular_campaigns', [$this, 'firebox_analytics_most_popular_campaigns']);

        add_action('wp_ajax_firebox_analytics_get_campaign', [$this, 'firebox_analytics_get_campaign']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_campaign', [$this, 'firebox_analytics_get_campaign']);

        add_action('wp_ajax_firebox_analytics_get_popular_view_items', [$this, 'firebox_analytics_get_popular_view_items']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_popular_view_items', [$this, 'firebox_analytics_get_popular_view_items']);

        add_action('wp_ajax_firebox_analytics_get_day_of_the_week', [$this, 'firebox_analytics_get_day_of_the_week']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_day_of_the_week', [$this, 'firebox_analytics_get_day_of_the_week']);

        add_action('wp_ajax_firebox_analytics_get_shared_data', [$this, 'firebox_analytics_get_shared_data']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_shared_data', [$this, 'firebox_analytics_get_shared_data']);

        add_action('wp_ajax_firebox_analytics_get_referrers', [$this, 'firebox_analytics_get_referrers']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_referrers', [$this, 'firebox_analytics_get_referrers']);

        add_action('wp_ajax_firebox_analytics_get_conversions_data', [$this, 'firebox_analytics_get_conversions_data']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_conversions_data', [$this, 'firebox_analytics_get_conversions_data']);
    }

    /**
     * Most Popular Campaigns
     * 
     * @return  void
     */
    public function firebox_analytics_most_popular_campaigns()
    {
		if (!current_user_can('manage_options'))
		{
			return;
        }
        
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
            return false;
		}

        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

        if (!$start_date || $start_date === 'false' || !$end_date || $end_date === 'false')
        {
            return;
        }

        

        
        echo wp_json_encode([
            'pro' => true
        ]);
        wp_die();
        
    }

    

    /**
     * Get single campaign data
     * 
     * @return  void
     */
    public function firebox_analytics_get_campaign()
    {
		if (!current_user_can('manage_options'))
		{
			return;
        }
        
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
            return false;
		}

        $campaign = isset($_POST['campaign']) ? intval($_POST['campaign']) : '';
        if (!$campaign)
        {
            return;
        }

        // Get campaign
        $campaign = BoxHelper::getBoxData($campaign);
        if (!$campaign)
        {
            return;
        }

        // Get campaign meta
        $campaign->params = BoxHelper::getMeta($campaign->ID);

        // Get last date viewed
        $campaign->last_date_viewed = BoxHelper::getCampaignLastDateViewed($campaign->ID);

        echo wp_json_encode([
            'error' => false,
            'campaign' => $campaign
        ]);
        wp_die();
    }

    /**
     * Get popular view times data
     * 
     * @return  void
     */
    public function firebox_analytics_get_popular_view_items()
    {
		if (!current_user_can('manage_options'))
		{
			return;
        }
        
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
            return false;
		}

        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
        $weekday = isset($_POST['weekday']) ? intval($_POST['weekday']) : false;

        if (!$start_date || $start_date === 'false' || !$end_date || $end_date === 'false')
        {
            return;
        }

        

        
        echo wp_json_encode([
            'pro' => true
        ]);
        wp_die();
        
    }

    /**
     * Get Day of the week data
     * 
     * @return  void
     */
    public function firebox_analytics_get_day_of_the_week()
    {
		if (!current_user_can('manage_options'))
		{
			return;
        }
        
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
            return false;
		}

        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

        if (!$start_date || $start_date === 'false' || !$end_date || $end_date === 'false')
        {
            return;
        }

        

        
        echo wp_json_encode([
            'pro' => true
        ]);
        wp_die();
        
    }

    /**
     * Get shared data.
     * 
     * Countries
     * Referrers
     * Devices
     * Events
     * Pages
     * 
     * @return  void
     */
    public function firebox_analytics_get_shared_data()
    {
		if (!current_user_can('manage_options'))
		{
			return;
        }
        
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
            return false;
		}

        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

        if (!$start_date || $start_date === 'false' || !$end_date || $end_date === 'false')
        {
            return;
        }

        
        
        
        echo wp_json_encode([
            'pro' => true
        ]);
        wp_die();
        
    }

    /**
     * Get conversions and conversion rate data per popup.
     * 
     * @return  void
     */
    public function firebox_analytics_get_conversions_data()
    {
		if (!current_user_can('manage_options'))
		{
			return;
        }
        
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
            return false;
		}

        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

        if (!$start_date || $start_date === 'false' || !$end_date || $end_date === 'false')
        {
            return;
        }

        

        
        echo wp_json_encode([
            'pro' => true
        ]);
        wp_die();
        
    }
}