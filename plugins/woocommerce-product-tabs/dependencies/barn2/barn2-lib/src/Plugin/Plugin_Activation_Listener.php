<?php

namespace Barn2\Plugin\WC_Product_Tabs_Free\Dependencies\Lib\Plugin;

/**
 * Something which listens for plugin activation or deactivation events.
 *
 * @package   Barn2\barn2-lib
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
interface Plugin_Activation_Listener
{
    public function on_activate();
    public function on_deactivate();
}
