<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php
/*
*  Copyright (c) 2013-2024, Squirrly Limited.
*  The copyrights to the software code in this file are licensed under the (revised) BSD open source license.
*
*  Plugin Name: StarBox
*  Author: Squirrly UK
*  Description: Starbox is the Author Box for Humans. Professional Themes to choose from, HTML5, Social Media Profiles, Google Authorship
*  Version: 3.5.0
*  Author URI: https://www.squirrly.co/wordpress-seo-by-squirrly
*  License:     GPLv2 or later
*  License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*  Text Domain: starbox
*  Domain Path: /languages
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*/

/* SET THE CURRENT VERSION ABOVE AND BELOW */
define('ABH_VERSION', '3.5.0');

if (!defined('ABHP_VERSION')) {

    /* Call config files */
    require(dirname(__FILE__) . '/config/config.php');

    /* important to check the PHP version */
    if (PHP_VERSION_ID >= 5100) {
        /* inport main classes */
        require_once(_ABH_CLASSES_DIR_ . 'ObjController.php');
        require_once(_ABH_CLASSES_DIR_ . 'BlockController.php');

        /* Main class call */
        ABH_Classes_ObjController::getController('ABH_Classes_FrontController')->run();

        if (!is_admin())
            ABH_Classes_ObjController::getController('ABH_Controllers_Frontend');
    } else {
        /* Main class call */
        add_action('admin_notices', array(ABH_Classes_ObjController::getController('ABH_Classes_FrontController'), 'phpVersionError'));
    }

// --

    // Upgrade StarBox call.
    register_activation_hook(__FILE__, 'abh_upgrade');

    function abh_upgrade() {
        set_transient('abh_upgrade', true, 30);
    }
}


