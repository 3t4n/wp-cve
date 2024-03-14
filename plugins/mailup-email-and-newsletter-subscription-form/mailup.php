<?php

declare(strict_types=1);

/**
 * The plugin bootstrap file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @see    https://integrations.mailup.com/it/wordpress/
 * @since   1.2.6
 *
 * @wordpress-plugin
 * Plugin Name:       MailUp - Email and Newsletter Subscription Form
 * Plugin URI:        https://integrations.mailup.com/it/wordpress/
 * // * Description:       The MailUp plugin for WordPress makes it easy to add a subscription form to a WordPress website and, to collect recipient for your email and sms campaigns. With the MailUp plugin you can easily create a sign-up form, personalise required fields and make it available your website or blog in few clicks.
 * Version:           1.2.6
 * Author:            MailUp
 * Author URI:        https://www.mailup.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mailup
 * Domain Path:       /languages/
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    exit;
}

/*
 * Currently plugin version.
 * Start at version 1.2.6
 */

define('WPMUP_PLUGIN_VERSION', '1.2.6');

define('WPMUP_PLUGIN', __FILE__);

define('WPMUP_PLUGIN_BASENAME', plugin_basename(WPMUP_PLUGIN));

define('WPMUP_PLUGIN_NAME', trim(dirname(WPMUP_PLUGIN_BASENAME), '/'));

define('WPMUP_PLUGIN_DIR', untrailingslashit(dirname(WPMUP_PLUGIN)));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mailup-activator.php.
 */
function activate_mailup(): void
{
    include_once plugin_dir_path(__FILE__).'includes/class-mailup-activator.php';
    Mailup_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mailup-deactivator.php.
 */
function deactivate_mailup(): void
{
    include_once plugin_dir_path(__FILE__).'includes/class-mailup-deactivator.php';
    Mailup_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_mailup');
register_deactivation_hook(__FILE__, 'deactivate_mailup');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-mailup.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.2.6
 */
function run_mailup(): void
{
    $plugin = new Mailup();
    $plugin->run();
}

run_mailup();
