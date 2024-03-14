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

class Site extends SmartTag
{
    /**
     * Returns the site email
     * 
     * @return  string
     */
    public function getEmail()
    {
        return $this->factory->getSiteData()->get('email');
    }

    /**
     * Returns the site name
     * 
     * @return  string
     */
    public function getName()
    {
        return $this->factory->getSiteData()->get('name');
    }

    /**
     * Returns the site URL
     * 
     * @return  string
     */
    public function getURL()
    {
        return $this->factory->getSiteData()->get('url');
    }
}