<?php
/*
 * Plugin Name: [凹凸曼]百度推送百度收录SEO
 * Plugin URI: http://www.apoyl.com/?p=2940
 * Description: 实现文章内容推送到百度里，让百度第一时间抓取你的内容
 * Version:     2.1.0
 * Author:      凹凸曼
 * Author URI:  http://www.apoyl.com/
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: apoyl-baidupush
 * Domain Path: /languages
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define('APOYL_BAIDUPUSH_VERSION','2.1.0');
define('APOYL_BAIDUPUSH_PLUGIN_FILE',plugin_basename(__FILE__));
define('APOYL_BAIDUPUSH_URL',plugin_dir_url( __FILE__ ));
define('APOYL_BAIDUPUSH_DIR',plugin_dir_path( __FILE__ ));

function activate_apoyl_baidupush(){
    require plugin_dir_path(__FILE__).'includes/class-apoyl-baidupush-activator.php';
    Apoyl_Baidupush_Activator::activate();
    Apoyl_Baidupush_Activator::install_db();
}
register_activation_hook(__FILE__, 'activate_apoyl_baidupush');

function uninstall_apoyl_baidupush(){
    require plugin_dir_path(__FILE__).'includes/class-apoyl-baidupush-uninstall.php';
    Apoyl_Baidupush_Uninstall::uninstall();
}

register_uninstall_hook(__FILE__,'uninstall_apoyl_baidupush');

require plugin_dir_path(__FILE__).'includes/class-apoyl-baidupush.php';

function run_apoyl_baidupush(){
    $plugin=new Apoyl_Baidupush();
    $plugin->run();
}

function apoyl_baidupush_file($filename)
{
	
    $file = WP_PLUGIN_DIR . '/apoyl-common/v1/apoyl-baidupush/components/' . $filename . '.php';
    if (file_exists($file))
        return $file;
        return '';
}
run_apoyl_baidupush();


?>