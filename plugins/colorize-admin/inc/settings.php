<?php  /* No direct access */
if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );
           /* Add Main Menu item */
           if ( is_admin() ) { if (get_option('_colorthemeadminmain') == '') {add_action('admin_menu', 'my_color_admin_menu');}else{add_action( 'admin_menu', 'setin_page_menu' );}}
           function my_color_admin_menu(){add_menu_page( 'Colorize Admin', __('Colorize Admin', 'colorize-admin' ), 'manage_options', 'color-admin', 'color_admin_theme','dashicons-image-filter'); }
           function setin_page_menu() { add_options_page( 'Colorize Admin Options', __('Colorize Admin', 'colorize-admin' ), 'manage_options', 'color-admin', 'color_admin_theme' );}
           /* Add actions link */
           add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'wpvote_color_admin_theme', 10, 1);
           function wpvote_color_admin_theme($links) {
	       $links[] = '<a href="'.admin_url('options-general.php?page=color-admin').'"><span class="dashicons dashicons-admin-settings"></span> '.__('Settings', 'colorize-admin').'</a>';
	       return $links;
           }
           /* Branding turn of and turn on */
           function annointed_admin_bar_remove() { global $wp_admin_bar; $wp_admin_bar->remove_menu('wp-logo'); }
           if (is_admin()){if (get_option('_colorthemeadminwptop') == 'off') {add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);}}
           /* Admin bar item add */
           function wp_admin_plugin_url() { global $wp_admin_bar, $wpdb; if ( !is_super_admin() || !is_admin_bar_showing() ) return;
           $url = admin_url( 'admin.php?page=color-admin' );
           $wp_admin_bar->add_menu( array( 'id' => 'colorize_admin_url', 'title' => __('Colorize Admin', 'colorize-admin' ), 'href' => $url ) );}
           /* Turn on and torn off top menu */
           if (is_admin()){if (get_option('_colorthemeadmintop') == '') {add_action( 'admin_bar_menu', 'wp_admin_plugin_url', 1000 );}}
