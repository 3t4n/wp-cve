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

class Main
{
    use Shared;
    
    public function __construct()
    {
        add_action('wp_ajax_firebox_analytics_stats', [$this, 'firebox_analytics_stats']);
        add_action('wp_ajax_nopriv_firebox_analytics_stats', [$this, 'firebox_analytics_stats']);

        add_action('wp_ajax_firebox_analytics_get_campaigns', [$this, 'firebox_analytics_get_campaigns']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_campaigns', [$this, 'firebox_analytics_get_campaigns']);

        add_action('wp_ajax_firebox_analytics_get_dropdown_campaigns', [$this, 'firebox_analytics_get_dropdown_campaigns']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_dropdown_campaigns', [$this, 'firebox_analytics_get_dropdown_campaigns']);

        add_action('wp_ajax_firebox_delete_campaign', [$this, 'firebox_delete_campaign']);
        add_action('wp_ajax_nopriv_firebox_delete_campaign', [$this, 'firebox_delete_campaign']);

        add_action('wp_ajax_firebox_duplicate_campaign', [$this, 'firebox_duplicate_campaign']);
        add_action('wp_ajax_nopriv_firebox_duplicate_campaign', [$this, 'firebox_duplicate_campaign']);

        add_action('wp_ajax_firebox_analytics_get_charts_data', [$this, 'firebox_analytics_get_charts_data']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_charts_data', [$this, 'firebox_analytics_get_charts_data']);

        add_action('wp_ajax_firebox_analytics_get_trending_templates', [$this, 'firebox_analytics_get_trending_templates']);
        add_action('wp_ajax_nopriv_firebox_analytics_get_trending_templates', [$this, 'firebox_analytics_get_trending_templates']);
    }

    /**
     * Analytics Counts
     * 
     * @return  void
     */
    public function firebox_analytics_stats()
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

        $date_period = isset($_POST['date_period']) ? sanitize_text_field($_POST['date_period']) : '';
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

        if (!$start_date || $start_date === 'false' || !$end_date || $end_date === 'false')
        {
            return;
        }
        
        $selected_campaign = isset($_POST['selected_campaign']) && $_POST['selected_campaign'] ? intval($_POST['selected_campaign']) : '';
        
        $data = new \FireBox\Core\Analytics\Data($start_date, $end_date);

        $metrics = [
            'views',
            'conversions',
            'conversionrate'
        ];
        $data->setMetrics($metrics);

        $filters = [];
        if ($selected_campaign)
        {
            $filters['campaign'] = [
                'value' => [$selected_campaign]
            ];
        }
        $data->setFilters($filters);

        $previousPeriodData = [
            'views' => 0,
            'conversions' => 0,
            'conversionrate' => 0
        ];
        
        // Calculate previous period data
        $start_date_ts = strtotime($start_date);
        $end_date_ts = strtotime($end_date);
        $days_between = ceil(abs($end_date_ts - $start_date_ts) / 86400);

        if ($previousPeriodDates = $this->getPreviousPeriodDates($start_date, $days_between))
        {
            $previousData = new \FireBox\Core\Analytics\Data($previousPeriodDates[0], $previousPeriodDates[1]);
    
            $metrics = [
                'views',
                'conversions',
                'conversionrate'
            ];
            $previousData->setMetrics($metrics);
    
            $filters = [];
            if ($selected_campaign)
            {
                $filters['campaign'] = [
                    'value' => [$selected_campaign]
                ];
            }
            $previousData->setFilters($filters);

            $previousPeriodData = $previousData->getData('count');
        }

        echo wp_json_encode([
            'current' => $data->getData('count'),
            'previous' => $previousPeriodData
        ]);
        wp_die();
    }

    /**
     * Analytics Get All Dropdown Campaigns
     * 
     * @return  void
     */
    public function firebox_analytics_get_dropdown_campaigns()
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

        $boxes = \FireBox\Core\Helpers\BoxHelper::getAllBoxes(['draft', 'publish']);
		$boxes = $boxes->posts;

		if (!count($boxes))
		{
            echo wp_json_encode([]);
            wp_die();
		}

		$data = [
            [
                'id' => null,
                'label' => 'All Campaigns'
            ]
        ];
		
		foreach ($boxes as $box)
		{
			$data[] = [
				'id' => $box->ID,
				'label' => $box->post_title
			];
		}

        echo wp_json_encode($data);
        wp_die();
    }

    /**
     * Analytics Get Chart Data
     * 
     * @return  void
     */
    public function firebox_analytics_get_charts_data()
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

        $filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : 'list';

        $start_date_ts = strtotime($start_date);
        $end_date_ts = strtotime($end_date);
        $days_between = ceil(abs($end_date_ts - $start_date_ts) / 86400);

        // prepare labels
        $labels = [];

        // We are fetching data for a single day
        if ($days_between == 1)
        {
            for ($hour = 0; $hour < 24; $hour++)
            {
                $start_hour = sprintf('%02d', $hour);
                $labels[] = $start_hour . ':00';
            }
        }
        // Multiple days
        else if ($days_between > 1)
        {
            switch ($filter)
            {
                case 'list':
                default:
                    $tmp_start_date_ts = $start_date_ts;
                    for ($i = 0; $i < $days_between; $i++)
                    {
                        $labels[] = gmdate('Y-m-d', $tmp_start_date_ts);
                        $tmp_start_date_ts = strtotime("+1 day", $tmp_start_date_ts);
                    }
                    break;
                
                case 'weekly':
                    $startDate = new \DateTime();
                    $startDate->setTimestamp($start_date_ts);

                    $endDate = new \DateTime();
                    $endDate->setTimestamp($end_date_ts);

                    // Find the nearest Monday to the start date
                    while ($startDate->format('N') != 1)
                    {
                        $startDate->modify('-1 day');
                    }

                    while ($startDate <= $endDate)
                    {
                        $labels[] = $startDate->format('d M y');
                        $startDate->modify('+1 week');
                    }
                    break;
                
                case 'monthly':
                    $startDate = new \DateTime();
                    $startDate->setTimestamp($start_date_ts);

                    $endDate = new \DateTime();
                    $endDate->setTimestamp($end_date_ts);

                    // Set the start date to the first day of the month
                    $startDate->modify('first day of this month');

                    while ($startDate <= $endDate)
                    {
                        $labels[] = $startDate->format('M Y');
                        $startDate->modify('+1 month');
                    }
                    break;
            }
        }
        
		$selected_campaign = isset($_POST['selected_campaign']) && $_POST['selected_campaign'] ? intval($_POST['selected_campaign']) : '';
        
        $data = new \FireBox\Core\Analytics\Data($start_date, $end_date);

        $metrics = [
            'views',
            'conversions',
            'conversionrate'
        ];
        $data->setMetrics($metrics);

        if ($selected_campaign)
        {
            $filters = [
                'campaign' => [
                    'value' => [$selected_campaign]
                ]
            ];
            $data->setFilters($filters);
        }

        $data = $data->getData($filter);

        echo wp_json_encode([
            'labels' => $labels,
            'data' => $data
        ]);
        wp_die();
    }

    /**
     * Analytics Get Latest 5 Campaigns
     * 
     * @return  void
     */
    public function firebox_analytics_get_campaigns()
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

        $boxes = \FireBox\Core\Analytics\Helpers\Campaigns::getRecentCampaignsList(5);

		if (!count($boxes->posts))
		{
            echo wp_json_encode([]);
            wp_die();
		}

        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
		
		$data = [];
		
		foreach ($boxes->posts as $box)
		{
            $meta = \FireBox\Core\Helpers\BoxHelper::getMeta($box->ID);

			$data[] = [
				'id' => $box->ID,
				'label' => $box->post_title,
				'status' => $box->post_status,
				'trigger' => isset($meta['triggermethod']) ? $meta['triggermethod'] : '',
				'position' => isset($meta['position']) ? $meta['position'] : '',
				'views' => $this->getBoxViews($box->ID, $start_date, $end_date)
			];
		}

        echo wp_json_encode($data);
        wp_die();
    }

    /**
     * Delete a campaign by ID.
     * 
     * @return  void
     */
    public function firebox_delete_campaign()
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

        $id = isset($_POST['id']) ? intval($_POST['id']) : '';
        if (!$id)
        {
            return;
        }
		
        $error = false;

        if (!wp_delete_post($id, true))
        {
            $error = true;
        }
        
        echo wp_json_encode([
            'error' => $error
        ]);
        wp_die();
    }

    /**
     * Duplicate a campaign by ID.
     * 
     * @return  void
     */
    public function firebox_duplicate_campaign()
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

        $id = isset($_POST['id']) ? intval($_POST['id']) : '';
        if (!$id)
        {
            return;
        }
		
        $error = false;

        if (!\FireBox\Core\Helpers\BoxHelper::duplicateBox($id))
        {
            $error = true;
        }
        
        echo wp_json_encode([
            'error' => $error
        ]);
        wp_die();
    }

    /**
     * Retrieve trending templates.
     * 
     * @return  void
     */
    public function firebox_analytics_get_trending_templates()
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

        $templates = \FPFramework\Helpers\Templates::getTemplates('firebox');

        if (!isset($templates->templates))
        {
            echo wp_json_encode([
                'templates' => []
            ]);
            wp_die();
        }

        $templates = $templates->templates;

        // Find top trending templates
        usort($templates, function($a, $b) {
            return $b->sort->trending - $a->sort->trending;
        });

        // First top 6 trending templates
        $templates = array_slice($templates, 0, 6);

        echo wp_json_encode($templates);
        wp_die();
    }

    private function getBoxViews($id = null, $start_date = null, $end_date = null)
    {
        if (!$id || !$start_date || !$end_date)
        {
            return;
        }

        $data = new \FireBox\Core\Analytics\Data($start_date, $end_date);

        $metrics = ['views'];
        $data->setMetrics($metrics);

    	$filters = [
            'campaign' => [
                'value' => [$id]
            ]
        ];
        $data->setFilters($filters);

        $data = $data->getData('count');

        return $data['views'];
    }
}