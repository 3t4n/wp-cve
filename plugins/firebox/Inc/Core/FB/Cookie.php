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

class Cookie
{
    /**
     * The box instance
     *
     * @var object
     */
    private $box;

    /**
     * The cookie instance
     * 
     * @var object
     */
    private $cookie;

    /**
     * The value of the cookie
     *
     * @var int
     */
    private $cookie_value = 1;

    /**
     * The path of the cookie. It should be available in the whole domain.
     *
     * @var string
     */
    private $cookie_path = '/';

    /**
     * Class constructor
     *
     * @param integer $box_id   The box's ID
     */
    public function __construct($box)
    {
        $this->box = $box;
        $this->cookie = new \FPFramework\Libs\Cookie();
    }

    /**
     * Get the name of the cookie
     *
     * @return string
     */
    private function getName()
    {
        return isset($this->box->ID) ? 'firebox_' . $this->box->ID : '';
    }

    /**
     * Store cookie in the browser 
     *
     * @return void
     */
    public function set()
    {
        $cookie_type = $this->box->params->get('assign_cookietype', 'days');

        switch ($cookie_type)
        {
            case 'never':
                return;
                
            case 'ever':
                $expire = strtotime('now +20 years'); // forever
                break;

            case 'custom':
                $assign_cookietype_param_custom_period_times = (int) $this->box->params->get('assign_cookietype_param_custom_period_times', 1);
                $assign_cookietype_param_custom_period = $this->box->params->get('assign_cookietype_param_custom_period', 'days');
                
                $expire = strtotime('now +' . (int) $assign_cookietype_param_custom_period_times . ' ' . $assign_cookietype_param_custom_period);
                break;

            default:
                $expire = 0; // session
        }

        $this->cookie->set($this->getName(), $this->cookie_value, $expire, $this->cookie_path, '', true);
    }
    
    /**
     * Check if the cookie of the box exist
     *
     * @return bool
     */
    public function exist()
    {
        return $this->cookie->get($this->getName());
    }

    /**
     * Removes the cookie from the browser
     *
     * @return void
     */
    public function remove()
    {
        $this->cookie->set($this->getName(), $this->cookie_value, strtotime('-1 day'), $this->cookie_path);
    }
}