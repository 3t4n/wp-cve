<?php
/**
 * @link              https://pontetlabs.com
 * @since             1.0.0
 * @package           Hide_Admin_Notices
 *
 * @wordpress-plugin
 * Plugin Name:       Hide Admin Notices
 * Plugin URI:        https://pontetlabs.com/hide-admin-notices
 * Description:       New & improved! Hide – or show – WordPress Dashboard Notices, Messages, Update Nags etc. ... for everything!
 * Version:           2.1
 * Author:            PontetLabs
 * Author URI:        https://pontetlabs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hide-admin-notices
 * Domain Path:       /languages
 */

/** @noinspection PhpDefineCanBeReplacedWithConstInspection */

use Pontet_Labs\Hide_Admin_Notices\Hide_Admin_Notices;

// Exit if accessed directly.
defined('ABSPATH') or exit;

// Define core constants.
define('HIDE_ADMIN_NOTICES_VERSION', '2.1');
define('HIDE_ADMIN_NOTICES_DIR', plugin_dir_path(__FILE__));
define('HIDE_ADMIN_NOTICES_URL', plugin_dir_url(__FILE__));
define('HIDE_ADMIN_NOTICES_BASENAME', plugin_basename(__FILE__));

require_once HIDE_ADMIN_NOTICES_DIR . 'includes/HideAdminNotices.php';

function autoload_classes()
{
    $class_map = array_merge(
        include HIDE_ADMIN_NOTICES_DIR . 'vendor/composer/autoload_classmap.php',
    );

    spl_autoload_register(
        function ($class) use ($class_map) {
            if (
                isset($class_map[$class])
                && (
                    str_starts_with($class, 'Pontet_Labs')
                    || str_starts_with($class, 'Twig')
                    || file_exists($class_map[$class])
                )
            ) {
                require_once $class_map[$class];
            }
        },
        true,
        true
    );
}

autoload_classes();

function hide_admin_notices(): bool
{
    if (null !== Hide_Admin_Notices::$instance) {
        return false;
    }

    Hide_Admin_Notices::$instance = new Hide_Admin_Notices();
    Hide_Admin_Notices::$instance->init();

    return true;
}

hide_admin_notices();
