<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Ajax;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class FPGeoIP
{
    /**
     *  GeoIP Class
     *
     *  @var  object
     */
    private $geoIP;

    public function __construct()
    {
        // Geo IP AJAX
        add_action('wp_ajax_fpf_on_geoip_ajax', [$this, 'fpf_on_geoip_ajax']);
        add_action('wp_ajax_nopriv_fpf_on_geoip_ajax', [$this, 'fpf_on_geoip_ajax']);
    }

    /**
     * Geo IP Ajax
     * 
     * @return  void
     */
    public function fpf_on_geoip_ajax()
    {
		if (!current_user_can('manage_options'))
		{
			return;
        }
        
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf-geo-lookup-nonce'))
        {
            return false;
		}

        $this->loadGeoIP();

        $task = isset($_POST['task']) ? sanitize_text_field($_POST['task']) : '';
        $license_key = isset($_POST['license_key']) ? sanitize_text_field($_POST['license_key']) : '';

        $this->geoIP->setKey($license_key);

        switch ($task)
        {
            // Update database and redirect
            case 'update-red': 
                $result = $this->geoIP->updateDatabase();

                if ($result === true)
                {
                    echo wp_json_encode('refresh');
                }
                else
                {
                    echo wp_json_encode($result);
                }
                break;
            // Update database
            case 'update':
                echo wp_json_encode($this->geoIP->updateDatabase());
                break;
            // IP Lookup
            case 'get':
                $ip = isset($_POST['ip']) ? sanitize_text_field($_POST['ip']) : '';
                echo wp_json_encode($this->geoIP->setIP($ip)->getRecord());
                break;
        }
		wp_die();
    }
    
    /**
     *  Load GeoIP Classes
     *
     *  @return  void
     */
    private function loadGeoIP()
    {
        $this->geoIP = new \FPFramework\Libs\Vendors\GeoIP\GeoIP();
    }
}