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

namespace FPFramework\Libs;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Functions
{
    /**
     * Increments the session
     * 
     * @return  void
     */
    public static function incrementSession()
    {
        $factory = new \FPFramework\Base\Factory();
        $session = $factory->getSession();
        $currentValue = intval($session->get('fpf.session.counter', 0));
        $newValue = $currentValue + 1;
        $session->set('fpf.session.counter', $newValue);
    }
}