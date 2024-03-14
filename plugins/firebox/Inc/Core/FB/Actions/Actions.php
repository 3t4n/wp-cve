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

namespace FireBox\Core\FB\Actions;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

abstract class Actions
{
    /**
     * The Actions
     *
     * @var mixed
     */
    protected $actions;

    /**
     * Returns the actions
     * 
     * @return  mixed
     */
    public function get_actions()
    {
        return $this->actions;
    }

    public function clear()
    {
        $this->actions = [];
    }
}