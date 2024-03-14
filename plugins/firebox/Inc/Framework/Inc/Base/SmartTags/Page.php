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

class Page extends SmartTag
{
    /**
     * Returns the page title
     * 
     * @return  string
     */
    public function getTitle()
    {
        return $this->factory->getSiteData()->get('title');
    }

    /**
     * Returns the locale
     * 
     * @return  string
     */
    public function getLang()
    {
        return $this->factory->getSiteData()->get('language');
    }

    /**
     * Returns the language code used in URLs
     * 
     * @return  string
     */
    public function getLangURL()
    {
        return $this->factory->getLanguage()->get('url');
    }

    /**
     * Returns the browser title
     * 
     * @return  string
     */
    public function getBrowserTitle()
    {
        return $this->factory->getSiteData()->get('browser_title');
    }
}