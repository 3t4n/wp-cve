<?php

/*
Plugin Name: HQ Rental Software
Plugin URI: https://hqrentalsoftware.com/knowledgebase/wordpress-plugin/
Description: This plugin is to easily integrate HQ Rental Software with your website which will allow your rental business to receive reservations directly from your site.
Version: 1.5.29
Author: HQ Rental Software
Author URI: https://hqrentalsoftware.com
Text Domain: hq-wordpress
*/

namespace HQRentalsPlugin;

define('HQ_RENTALS_PLUGIN_VERSION', '1.5.29');
define('HQ_RENTALS_TEXT_DOMAIN', 'hq-wordpress');

require_once('includes/autoloader.php');
// If this file is accessed directory, then abort.
if (!defined('WPINC')) {
    die;
}

use HQRentalsPlugin\HQRentalsBakery\HQRentalsBakeryBoostrap;
use HQRentalsPlugin\HQRentalsBootstrap\HQRentalsBootstrapPlugin;
use HQRentalsPlugin\HQRentalsElementor\HQRentalsElementorBoostrap;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsBootstrap;
use HQRentalsPlugin\HQRentalsThemes\HQRentalsThemeCustomizer;

$bootstraper = new HQRentalsBootstrapPlugin();
$themeCustomizer = new HQRentalsThemeCustomizer();
/*
 * Activation Routine
 * @return void
 */
function hq_rentals_wordpress_activation()
{
    $boot = new HQRentalsBootstrap();
    $boot->onPluginActivation();
}

register_activation_hook(__FILE__, __NAMESPACE__ . '\hq_rentals_wordpress_activation');
$elementor = new HQRentalsElementorBoostrap();
$elementor->boostrapElementor();
$bakery = new HQRentalsBakeryBoostrap();
$bakery->boostrapBakery();
