<?php

/** This file intentionally without namespace */

use SGI\Fitvids\Core\Bootstrap;

/**
 * Returns main plugin class, and initializes if not constructed
 *
 * @return Bootstrap Main plugin class instance
 * @since 3.0
 */
function RSFitvids() : Bootstrap
{

    return Bootstrap::getInstance();

}