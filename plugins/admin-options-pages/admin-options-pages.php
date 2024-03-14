<?php
/**
 * Plugin Name: Admin Options Pages
 * Plugin URI:  https://adminoptionspages.com
 * Description: Create and edit your own options pages with ease.
 * Version:     0.9.7
 * Author:      Johannes van Poelgeest
 * Author URI:  https://poolghost.com
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('WPINC')) {
    die;
}

if (!is_admin()) {
    return;
}

require plugin_dir_path(__FILE__) . 'bootstrap/autoloader.php';
require plugin_dir_path(__FILE__) . 'bootstrap/Requirements.php';

$minPhpVersion = '5.6.20';
$minWpVersion  = '5.3';

$requirements = new \Requirements($minPhpVersion, $minWpVersion, __FILE__);

if ($requirements->notCorrect()) {
    return $requirements->notCorrectAction();
}

include plugin_dir_path(__FILE__) . 'bootstrap/bootstrap.php';
