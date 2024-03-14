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

namespace FPFramework\Base\Factory;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Cookie
{
    /**
     * Cookie Name
     * 
     * @var  string
     */
    private $cookie_name;

    /**
     * Cookie
     * 
     * @var  Cookie
     */
    private $cookie;
    
    public function __construct($cookie_name = null)
    {
        $this->cookie_name = $cookie_name;
        $this->cookie = new \FPFramework\Libs\Cookie($this->cookie_name);
    }

    /**
     * Gets cookie value
     * 
     * @return  mixed
     */
    public function get()
    {
        if (!$this->cookie->exist($this->cookie_name))
        {
            return null;
        }

        return $this->cookie->get($this->cookie_name);
    }

    /**
     * Sets cookie key, value
     * 
     * @return  void
     */
    public function set($key, $value)
    {
        return $this->cookie->set($key, $value);
    }
}