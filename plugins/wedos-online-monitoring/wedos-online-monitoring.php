<?php
namespace WEDOS\Mon\WP;

/**
 * WEDOS OnLine monitoring
 *
 * @author      Petr Stastny <petr@stastny.eu>
 * @copyright   WEDOS Internet, a.s.
 * @license     GPLv3
 *
 * @wordpress-plugin
 * Plugin Name: WEDOS OnLine monitoring
 * Plugin URI:  https://www.wedos.online/
 * Description: WEDOS OnLine monitoring plugin. It allows you to link your website with WEDOS OnLine free monitoring solution.
 * Version:     1.0.10
 * Author:      WEDOS
 * Author URI:  https://www.wedos.online/
 */

define('WEDOSONLINE_VERSION', '1.0.10');

// If this file is called directly, abort.
if (!defined('WPINC')) {
    return;
}

require_once __DIR__.'/vendor/phpf/wp/lib/Core/Init.php';

\PHPF\WP\Core\Init::bootstrap();
\PHPF\WP\Core\Autoload::addPath(__DIR__.'/lib', 'WEDOS\\Mon\\WP');

WedosOnline::init();
