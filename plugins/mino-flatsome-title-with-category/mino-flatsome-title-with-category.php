<?php

/**
 *
 * @link              https://mino.vn/
 * @since             1.0.0
 * @package           Mino_Flatsome_Title_With_Category
 *
 * @wordpress-plugin
 * Plugin Name:       Mino Flatsome Title With Category
 * Plugin URI:        https://mino.vn/
 * Description:       Add title with product category element for flatsome theme.
 * Version:           1.0.0
 * Author:            Mino
 * Author URI:        https://mino.vn
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mino-flatsome-title-with-category
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
define('MINO_FLATSOME_TITLE_CATEGORY_DIR', plugin_dir_path(__FILE__));
define('MINO_FLATSOME_TITLE_CATEGORY_ASSETS', plugin_dir_url( __FILE__ ) . 'assets/');

include( MINO_FLATSOME_TITLE_CATEGORY_DIR . 'inc/functions.php');
new FlatsomeTitleCategory();