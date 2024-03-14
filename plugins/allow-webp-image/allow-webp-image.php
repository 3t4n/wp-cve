<?php
/**
 *
 * @link              http://www.topinfosoft.com/
 * @since             1.0.1
 * @package           Allow_Webp_Image
 *
 * @wordpress-plugin
 * Plugin Name:       Allow Webp Image
 * Plugin URI:        http://www.topinfosoft.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.1
 * Author:            Top Infosoft
 * Author URI:        http://www.topinfosoft.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       allow-webp-image
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ALLOW_WEBP_IMAGE_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-allow-webp-image-activator.php
 */
function activate_allow_webp_image() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-allow-webp-image-activator.php';
	Allow_Webp_Image_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-allow-webp-image-deactivator.php
 */
function deactivate_allow_webp_image() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-allow-webp-image-deactivator.php';
	Allow_Webp_Image_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_allow_webp_image' );
register_deactivation_hook( __FILE__, 'deactivate_allow_webp_image' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-allow-webp-image.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function run_allow_webp_image() {

	$plugin = new Allow_Webp_Image();
	$plugin->run();

}
run_allow_webp_image();



//** *Enable upload for webp image files.*/
function topawi_upload_mimes($existing_mimes) {
    $existing_mimes['webp'] = 'image/webp';
    return $existing_mimes;
}
add_filter('mime_types', 'topawi_upload_mimes');

//** * Enable preview / thumbnail for webp image files.*/
function topawi_is_displayable($result, $path) {
    if ($result === false) {
        $displayable_image_types = array( IMAGETYPE_WEBP );
        $info = @getimagesize( $path );

        if (empty($info)) {
            $result = false;
        } elseif (!in_array($info[2], $displayable_image_types)) {
            $result = false;
        } else {
            $result = true;
        }
    }

    return $result;
}
add_filter('file_is_displayable_image', 'topawi_is_displayable', 10, 2);