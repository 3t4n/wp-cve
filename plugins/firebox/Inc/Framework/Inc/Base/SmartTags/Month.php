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

class Month extends SmartTag
{
    /**
     * Returns the current month
     * 
     * @return  string
     */
    public function getMonth()
    {
		$tz = wp_timezone();
		$date = $this->factory->getDate()->setTimezone($tz);
        
        return $date->format('n');
    }
}