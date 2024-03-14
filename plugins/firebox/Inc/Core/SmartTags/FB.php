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

namespace FireBox\Core\SmartTags;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use \FPFramework\Base\SmartTags\SmartTag;

class FB extends SmartTag
{
    /**
     * Returns the ID of the box
     * 
     * @return  string
     */
    public function getID()
    {
        if (!isset($this->data->ID))
        {
            return;
        }
        
        return $this->data->ID;
    }

    /**
     * Returns the title of the box
     * 
     * @return  string
     */
    public function getTitle()
    {
        if (!isset($this->data->post_title))
        {
            return;
        }

        return $this->data->post_title;
    }
}