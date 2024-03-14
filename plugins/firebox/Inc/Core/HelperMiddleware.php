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

namespace FireBox\Core;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class HelperMiddleware
{
    public $factory;
    public $wpdb;

    

    public function __construct($factory = null)
    {
        if (empty($factory))
        {
            $factory = new \FPFramework\Base\Factory();
        }

        $this->factory = $factory;
        $this->wpdb = $this->factory->getDbo();

        
    }
}