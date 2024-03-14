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

class URL extends SmartTag
{
    /**
     * Returns the URL
     * 
     * @return  string
     */
    public function getURL()
    {
        return $this->factory->getURL();
    }

    /**
     * Returns the URL encoded
     * 
     * @return  string
     */
    public function getEncoded()
    {
        return urlencode($this->factory->getURL());
    }

    /**
     * Returns the URL path
     * 
     * @return  string
     */
    public function getPath()
    {
        $path = $this->factory->getSiteData()->get('url');

        if (is_admin())
        {
            $path = get_admin_url();
        }
        
        return $path;
    }
}