<?php if (!defined('ABSPATH')) {exit;}
/*
Version: 1.0.0
Date: 20-02-2022
Author: Maxim Glazunov
Author URI: https://icopydoc.ru 
License: GPLv2
Description: This code helps ensure backward compatibility with older versions of the plugin.
*/

// gupfw_DIR contains /home/p135/www/site.ru/wp-content/plugins/myplagin/
define('gupfw_DIR', plugin_dir_path(__FILE__)); 
// gupfw_URL contains http://site.ru/wp-content/plugins/myplagin/
define('gupfw_URL', plugin_dir_url(__FILE__));
// gupfw_UPLOAD_DIR contains /home/p256/www/site.ru/wp-content/uploads
$upload_dir = (object)wp_get_upload_dir();
define('gupfw_UPLOAD_DIR', $upload_dir->basedir);
// gupfw_UPLOAD_DIR contains /home/p256/www/site.ru/wp-content/uploads/gift-upon-purchase-for-woocommerce
$name_dir = $upload_dir->basedir.'/gift-upon-purchase-for-woocommerce'; 
define('gupfw_NAME_DIR', $name_dir);
$gupfw_keeplogs = gupfw_optionGET('gupfw_keeplogs');
define('gupfw_KEEPLOGS', $gupfw_keeplogs);
define('gupfw_VER', '1.2.5');
?>