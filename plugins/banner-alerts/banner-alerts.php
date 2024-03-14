<?php
/**
 * Plugin Name: Banner Alerts
 * Plugin URI: https://www.banneralertsplugin.com/
 * Description: Provides an easy interface for creating and displaying alerts or notices as a banner on a website
 * Version: 1.4.1
 * Author: Valice
 * Author URI: https://www.valice.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: banner-alerts
 * Domain Path: /languages
 */

use Plugin\BannerAlerts;

require __DIR__ . '/src/BannerAlerts.php';

$bannerAlertsPlugin = new BannerAlerts\BannerAlerts(__FILE__);

register_activation_hook(__FILE__, array($bannerAlertsPlugin, 'onActivate'));
register_deactivation_hook(__FILE__, array($bannerAlertsPlugin, 'onDeactivate'));

function getBannerAlerts ()
{
	global $bannerAlertsPlugin;
	return $bannerAlertsPlugin;
}
