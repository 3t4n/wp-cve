<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.scribit.it/
 * @since             1.0.0
 * @package           shortcodes-finder
 *
 * @wordpress-plugin
 * Plugin Name:       Shortcodes Finder
 * Plugin URI:        https://www.scribit.it/en/wordpress-plugins/find-wordpress-website-shortcodes-in-one-click/
 * Description:       Find, test, disable, clean and get informations about the shortcodes in your Wordpress website posts, pages and custom contents (also in multisite network).
 * Version:           1.5.6
 * Author:            Scribit
 * Author URI:        https://www.scribit.it/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shortcodes-finder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

require_once plugin_dir_path(__FILE__) . 'shortcodes-finder-consts.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-shortcodes-finder-activator.php
 */
function activate_shortcodes_finder()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-shortcodes-finder-activator.php';
    Shortcodes_Finder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-shortcodes-finder-deactivator.php
 */
function deactivate_shortcodes_finder()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-shortcodes-finder-deactivator.php';
    Shortcodes_Finder_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_shortcodes_finder');
register_deactivation_hook(__FILE__, 'deactivate_shortcodes_finder');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-shortcodes-finder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_shortcodes_finder()
{
    $plugin = new ShortcodeFinder();
    $plugin->run();
}
run_shortcodes_finder();

function shortcodes_finder_actions_links($links)
{
    $settings_link = '<a href="tools.php?page='. SHORTCODES_FINDER_PLUGIN_SLUG .'"><span style="color:#C60;font-weight:bold">' . __('Find Shortcodes', 'shortcodes-finder') . '</span></a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_'. plugin_basename(__FILE__), 'shortcodes_finder_actions_links');

function shortcodes_finder_footer_text()
{
    // Show footer only in plugin pages
    if (!strpos(get_current_screen()->id, SHORTCODES_FINDER_PLUGIN_SLUG)) {
        return;
    }

    $url = 'https://www.scribit.it';
    echo '<span class="scribit_credit">'.sprintf('%s <a href="%s" target="_blank">Scribit</a>', esc_html(__('Shortcodes Finder is powered by', 'shortcodes-finder')), esc_url($url)).'</span>';
}
add_filter('admin_footer_text', 'shortcodes_finder_footer_text');
