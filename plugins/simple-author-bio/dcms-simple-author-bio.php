<?php
 /*
 * Plugin Name:       Simple Author Bio
 * Plugin URI:        https://decodecms.com
 * Description:       This plugin shows the author's biography in articles
 * Version:           1.0.7
 * Author:            Jhon Marreros Guzmán
 * Author URI:        https://decodecms.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-author-bio
 * Domain Path:       /languages
 */

//if this file is called directly, abort
if ( ! defined('WPINC') ) die();


//Define constants
define('DCMS_SAB_PATH_TEMPLATE', plugin_dir_path( __FILE__ ).'/template/box-author-bio.txt');
define('DCMS_SAB_PATH_INCLUDE',	 plugin_dir_path( __FILE__ ).'includes/');
define('DCMS_SAB_PATH_LANGUAGE', 'simple-author-bio/languages');
define('DCMS_SAB_PATH_PLUGIN',	__FILE__);


require_once DCMS_SAB_PATH_INCLUDE.'class-dcms-simple-author-bio.php';

new Dcms_Simple_Author_Bio();

