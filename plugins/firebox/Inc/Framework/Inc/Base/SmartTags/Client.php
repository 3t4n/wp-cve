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

namespace FPFramework\Base\SmartTags;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\WebClient;

class Client extends SmartTag
{
    /**
     * Returns the client's device
     * 
     * @return  string
     */
    public function getDevice()
    {
        return $this->factory->getDevice();
    }

    /**
     * Returns the client's OS
     * 
     * @return  string
     */
    public function getOS()
    {
        return $this->factory->getOS();
    }

    /**
     * Returns the client's browser
     * 
     * @return  string
     */
    public function getBrowser()
    {
        return $this->factory->getBrowser()['name'];
    }

    /**
     * Returns the client's User Agent
     * 
     * @return  string
     */
    public function getUserAgent()
    {
        return $this->factory->getUserAgent();
    }
}