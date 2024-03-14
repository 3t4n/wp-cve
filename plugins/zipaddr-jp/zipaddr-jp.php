<?php
/*
Plugin Name: zipaddr-jp
Plugin URI: https://zipaddr2.com/wordpress/
Description: The input convert an address from a zip code automatically.
Version: 1.38
Author: Tatsuro, Terunuma
Author URI: https://pierre-soft.com/
*/
define('zipaddr_VERS', '1.38');
define('zipaddr_KEYS', 'zipaddr-config');
define('zipaddr_SYS',  'sys_');
define('zipaddr_COM',  'https://zipaddr.com/');
define('zipaddr2COM',  'https://zipaddr2.com/');
define('zipaddr_git',  'https://zipaddr.github.io/');
define('zipaddr_DEFINE', 'zipaddr_define');
define('zipaddrMei', plugin_basename(dirname(__FILE__)));
define('zipaddr_PLUGIN_DIR', plugin_dir_path(__FILE__)); // /myplugin/
define('zipaddr_FILE1', str_replace("/".zipaddrMei."/","",zipaddr_PLUGIN_DIR)."/zipaddr_define.txt"); //‹Œ
define('zipaddr_FILE2', zipaddr_PLUGIN_DIR."include/zipaddrjp_define.php"); //V

	$plugin_name= "";
	if( isset($_GET['page']) ){
		$plugin_name= trim($_GET['page']);
		$plugin_name= htmlspecialchars($plugin_name, ENT_QUOTES);
		$plugin_name= substr($plugin_name,0,6);
	}
	require_once zipaddr_PLUGIN_DIR.'include/zipaddrjp_config.php';

if( is_admin() && $plugin_name == "usces_" ){     // welcart
	define( 'zipaddr_IDENT', '3');
	require_once zipaddr_PLUGIN_DIR.'zipaddr.php';
	add_filter('usces_filter_apply_admin_addressform', 'zipaddr_jp_usces', 99999, 3);// welcart
}
else
if( is_admin() ){                                 // admin
	define( 'zipaddr_IDENT', '2');
	require_once zipaddr_PLUGIN_DIR.'admin.php';
	add_action('admin_menu', 'zipaddr_admin_menu');
	if( function_exists('register_uninstall_hook') ) {register_uninstall_hook( __FILE__, 'zipaddr_uninstall' );} // uninstallŒÄo‚µ
}
else {                                            // user
	define( 'zipaddr_IDENT', '1');
	require_once zipaddr_PLUGIN_DIR.'zipaddr.php';
	add_filter('usces_filter_apply_addressform', 'zipaddr_jp_usces', 99999, 3);// welcart
	add_filter('usces_filter_cart_delivery_script','zipaddr_jp_welcart', 99999, 3);// welcart
	add_filter('the_content', 'zipaddr_jp_change', 99999); // html change
}
?>
