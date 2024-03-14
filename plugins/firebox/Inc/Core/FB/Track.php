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

namespace FireBox\Core\FB;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Track
{
    /**
     * Factory
     * 
     * @var  Factory
     */
    private $factory;

    public function __construct($factory = null)
    {
        if (!$factory)
        {
            $factory = new \FPFramework\Base\Factory();
        }
        $this->factory = $factory;

        $this->trackConversions();
        
		$this->setupAjax();
    }
    
	/**
	 * Setup ajax requests
	 * 
	 * @return  void
	 */
	public function setupAjax()
	{
		// FB Track event AJAX
		add_action('wp_ajax_firebox_trackevent', [$this, 'firebox_trackevent']);
        add_action('wp_ajax_nopriv_firebox_trackevent', [$this, 'firebox_trackevent']);

        // FB Track conversion AJAX
        add_action('wp_ajax_firebox_trackconversion', [$this, 'firebox_trackconversion']);
        add_action('wp_ajax_nopriv_firebox_trackconversion', [$this, 'firebox_trackconversion']);
    }

    /**
     * Track Conversions
     * 
     * @return  void
     */
    public function trackConversions()
    {
        $key = 'firebox_conversions_tracker';
        
        // Get conversions
        $conversions = isset($_COOKIE[$key]) ? json_decode(wp_unslash($_COOKIE[$key]), true) : false;
        if (!$conversions)
        {
            return;
        }

        // Delete cookie
        unset($_COOKIE[$key]);
        setcookie($key, '', time() - 3600, "/");

        // Track conversions in db
        foreach ($conversions as $campaign_id => $data)
        {
            if (!isset($data['box_log_id']) || !$data['box_log_id'])
            {
                continue;
            }

            if (!isset($data['source']))
            {
                continue;
            }

            if (!isset($data['label']))
            {
                continue;
            }

            $data = [
                'log_id' => $data['box_log_id'],
                'event' => 'conversion',
                'event_source' => $data['source'],
                'event_label' => $data['label'],
                'date' => $this->factory->getDate()->format('Y-m-d H:i:s')
            ];

            firebox()->tables->boxlogdetails->insert($data);
        }
    }
    
    /**
     * Box Track Event
     * 
     * @return  string
     */
    public function firebox_trackevent()
    {
        $nonce = isset($_GET['nonce']) ? sanitize_text_field($_GET['nonce']) : '';

        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fbox_js_nonce'))
        {
            return false;
        }

		$event = isset($_GET['event']) ? sanitize_text_field($_GET['event']) : '';
        $box_id = isset($_GET['box']) ? sanitize_text_field($_GET['box']) : '';
        $page = '';
        $referrer = '';
        $box_log_id = '';

        $response['success'] = false;

        // ensure non-empty values
        if (empty($event) || empty($box_id))
        {
            echo wp_json_encode($response);
            wp_die();
        }

        // ensure valid event
        if (!in_array($event, ['open', 'close']))
        {
            echo wp_json_encode($response);
            wp_die();
        }

        // If its a close event, it should also provide the box log ID in order to add close date of box
        if ($event == 'close')
        {
            if (!isset($_GET['box_log_id']))
            {
                return;
            }

            $box_log_id = sanitize_text_field($_GET['box_log_id']);
        }
        else if ($event == 'open')
        {
            if (!isset($_GET['page']) && !isset($_GET['referrer']))
            {
                return;
            }

            $page = sanitize_text_field($_GET['page']);
            $referrer = sanitize_text_field($_GET['referrer']);
        }
        
        // Load box settings
        if (!$box = firebox()->box->get($box_id))
        {
            return;
        }

        // Don't track events on unpublished campaigns
        $track_unpublished = apply_filters('firebox/track_unpublished', false);
        if (!$track_unpublished && $box->post_status !== 'publish')
        {
            return;
        }

        /**
         * Trigger Open & Close Event.
         */
        do_action('firebox/box/on_' . $event, $box);

        $response['success'] = true;

        if ($event == 'open')
        {
            $response['box_log_id'] = $this->handleOpenEvent($box, $box_id, $page, $referrer);
        }
        
        // Log impression in the database
        if ($event == 'close')
        {
            // get options
            $options = isset($_GET['options']) ? wp_unslash($_GET['options']) : [];
            
            $this->handleCloseEvent($box, $box_id, $box_log_id, $options, $response);
        }

        echo wp_json_encode($response);
        wp_die();
    }
    
    /**
     * Handle Open Event
     * 
     * @param   object  $box
     * @param   int     $box_id
     * @param   string  $page
     * @param   string  $referrer
     * 
     * @return  void
     */
    protected function handleOpenEvent($box, $box_id, $page, $referrer)
    {
        // Do not track when box is on test mode
        if (!$box->params->get('testmode'))
        {
            return firebox()->box->logOpenEvent($box_id, $page, $referrer);
        }
    }

    /**
     * Handle Close Event
     * 
     * @param   object  $box
     * @param   int     $box_id
     * @param   int     $box_log_id
     * @param   array   $options
     * @param   array   $response
     * 
     * @return  void
     */
    protected function handleCloseEvent($box, $box_id, $box_log_id, $options, &$response)
    {
        // Do not track when box is on test mode
        if (!$box->params->get('testmode'))
        {
            firebox()->box->logCloseEvent($box_id, $box_log_id);
        }

        // Do not set any cookie if box is on test mode
        if (!$box->params->get('testmode') && !isset($options['temporary']))
        {
            // allow to prevent cookie from being set
            if (!apply_filters('firebox/box/close/cookie_set', true, $box_id))
            {
                return;
            }

            if ($this->cookieExist($box_id))
            {
                $response['action'] = 'stop';
            }
        }
    }

    /**
     * Check whether the cookie exists.
     * 
     * @param   int   $box_id
     * 
     * @return  bool
     */
    protected function cookieExist($box_id)
    {
        $cookie = new \FireBox\Core\FB\Cookie(firebox()->box->get($box_id));
        $cookie->set();
        
        return $cookie->exist();
    }

    /**
     * Box Track Conversion
     * 
     * @return  string
     */
    public function firebox_trackconversion()
    {
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fbox_js_nonce'))
        {
            return false;
        }

        $this->trackConversions();
    }
}