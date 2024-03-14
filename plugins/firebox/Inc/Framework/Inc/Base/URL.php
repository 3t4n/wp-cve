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

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class URL
{
	private $path;

	private $factory;

    /**
     *  Class constructor
     */
    public function __construct($path, $factory = null)
    {
        $this->path = trim($path);
        $this->factory = $factory ? $factory : new Factory();
    }

    public function getInstance()
    {
        return clone new URL($this->path);
    }

	public function getHost()
	{
		$parse = wp_parse_url($this->path);

        if (!isset($parse['host']))
        {
            return;
        }

        return $parse['host'];
	}

    public function getDomainName()
    {
        return strtolower(str_ireplace('www.', '', $this->getInstance()->getHost()));
    }

    public function isInternal()
    {
        if (!$this->path)
        {
			return false;
        }
		
		$host = $this->getInstance()->getHost();

        if (is_null($host))
        {
            return true;
        }

		$site_parse = wp_parse_url($this->factory->getURL());
        $siteHost = $site_parse['host'];

        return preg_match('#' . preg_quote($siteHost, '#') . '#', $host) ?  true : false;
    }
}