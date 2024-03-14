<?php
/*
 * Plugin Name: Limit Login Attempts (Spam Protection)
 * Description: Limit the number of retry attempts when logging in per IP. Fully customizable and easy to use.
 * Version: 5.3
 * Author: wp-buy
 * Text Domain: codepressFailed_pro
 * Domain Path: /languages/
 * Author URI: https://www.wp-buy.com
 * License: GPL2
 */


define( 'WPLFLA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPLFLA_PLUGIN_URL', plugin_dir_url(__FILE__) );

if (!function_exists('WPLFLA_check_some_other_plugin')) {
    function WPLFLA_check_some_other_plugin()
    {
        if (is_plugin_active('wp-limit-failed-login-attempts-pro/failed_pro.php')) {
            deactivate_plugins('/wp-limit-failed-login-attempts-pro/failed_pro.php');
        }
    }
    add_action( 'admin_init', 'WPLFLA_check_some_other_plugin' );
}




// load translation file
add_action( 'init', 'WPLFLA_load_textdomain_pro' );
if (!function_exists('WPLFLA_load_textdomain_pro')) {
    function WPLFLA_load_textdomain_pro()
    {
        load_plugin_textdomain('codepressFailed_pro', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}


require_once( WPLFLA_PLUGIN_DIR . '/admin/menu.php' );
require_once( WPLFLA_PLUGIN_DIR . '/admin/setting.php' );
require_once( WPLFLA_PLUGIN_DIR . '/admin/dashboard_widget.php' );
require_once( WPLFLA_PLUGIN_DIR . '/admin/statistics.php' );

require_once( WPLFLA_PLUGIN_DIR . '/admin/range_ip.php' );

require_once( WPLFLA_PLUGIN_DIR . '/admin/log.php' );
require_once( WPLFLA_PLUGIN_DIR . '/admin/logblockip.php' );
require_once( WPLFLA_PLUGIN_DIR . '/admin/countries.php' );
require_once( WPLFLA_PLUGIN_DIR . '/login.php' );
if (!function_exists('WPLFLA_row_meta_pro')) {
    function WPLFLA_row_meta_pro($meta_fields, $file){
        if (strpos($file, 'failed_pro.php') == false) {
            return $meta_fields;
        }
        echo "<style>.pluginrows-rate-stars { display: inline-block; color: #ffb900; position: relative; top: 3px; }.pluginrows-rate-stars svg{ fill:#ffb900; } .pluginrows-rate-stars svg:hover{ fill:#ffb900 } .pluginrows-rate-stars svg:hover ~ svg{ fill:none; } </style>";

        $plugin_rate = esc_url("https://wordpress.org/support/plugin/wp-limit-failed-login-attempts/reviews/?rate=5#new-post");
        $plugin_filter = esc_url("https://wordpress.org/support/plugin/wp-limit-failed-login-attempts/reviews/?filter=5");
        $svg_xmlns = esc_url("https://www.w3.org/2000/svg");
        $svg_icon = '';

        for ($i = 0; $i < 5; $i++) {
            $svg_icon .= sprintf("<svg xmlns='" . esc_url("%s") . "' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>",$svg_xmlns);
        }

        // Set icon for thumbsup.
        $meta_fields[] = sprintf('<a href="' . esc_url("%s") . '" target="_blank"><span class="dashicons dashicons-thumbs-up"></span>' . __('Vote!', 'pluginrows') . '</a>',$plugin_filter);

        // Set icon for 5-star reviews. v1.1.22
        $meta_fields[] = sprintf("<a href='%s' target='_blank' title='%s'><i class='pluginrows-rate-stars'>%s</i></a>", esc_url($plugin_rate),esc_html__('Rate', 'pluginrows'),$svg_icon);

        return $meta_fields;
    }
}


if (!function_exists('WPLFLA_install_pro')) {
    function WPLFLA_install_pro()
    {
        if ( is_plugin_active( 'wp-limit-failed-login-attempts/failed.php' ) ) {
            deactivate_plugins( '/wp-limit-failed-login-attempts/failed.php' );
        }

        $def_data = array();
        $def_data['WPLFLA_status'] = 1;
        $def_data['WPLFLA_send_mail_status'] = 1;
        $def_data['WPLFLA_min'] = 30;
        $def_data['WPLFLA_allowed'] = 3;
        $def_data['WPLFLA_email'] = get_option('admin_email', '');

        add_option('WPLFLA_options', $def_data, '', 'yes');

        WPLFLA_create_table_pro('WPLFLA_login_failed', 'WPLFLA_login_failed');
        WPLFLA_create_table_pro('WPLFLA_log_block_ip', 'WPLFLA_block_ip');
        WPLFLA_create_table_range_ip_pro('WPLFLA_block_ip_range');
        WPLFLA_create_table_block_countries_pro('WPLFLA_block_countries');

        global $wpdb;
        $table_name = $wpdb->prefix . 'WPLFLA_login_failed';
        $row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$table_name."' AND column_name = 'status'"  );

         if(empty($row)){
             $wpdb->query("ALTER TABLE `".$table_name."` ADD `status` INT NOT NULL DEFAULT '0' AFTER `redirect_to`");
         }
    }
}
register_activation_hook( __FILE__, 'WPLFLA_install_pro' );

if (!function_exists('WPLFLA_create_table_pro')) {
    function WPLFLA_create_table_pro($create_table_name, $old_data = '')
    {

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . $create_table_name;

        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
		`id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(100) NOT NULL,
        `ip` varchar(15) NOT NULL,
        `country` varchar(60)  NULL,
        `latitude` varchar(60)  NULL,
        `longitude` varchar(60)  NULL,
        `country_code` varchar(60)  NULL,
        `city` varchar(60)  NULL,
        `password` varchar(255)  NULL,
        `redirect_to` varchar(255)  NULL,
        `date` datetime NOT NULL DEFAULT current_timestamp(),
        UNIQUE KEY id (id)
	) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option('WPLFLA_version', '1.0');
        if ($old_data == '') {
            return true;
        }
    }
}
if (!function_exists('WPLFLA_create_table_range_ip_pro')) {
    function WPLFLA_create_table_range_ip_pro($create_table_name)
    {

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . $create_table_name;


        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `start_ip` varchar(15) NOT NULL,
            `end_ip` varchar(15) NOT NULL,
            `type_intr` int(1) NOT NULL,
            `date` datetime NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option('WPLFLA_version', '1.0');

    }
}
if (!function_exists('WPLFLA_create_table_block_countries_pro')) {
    function WPLFLA_create_table_block_countries_pro($create_table_name)
    {

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . $create_table_name;


        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
		`id` int(11) NOT NULL AUTO_INCREMENT,
        `country` varchar(100) NOT NULL,
        `country_code` varchar(11) NOT NULL,
        `type_intr` int(1) NOT NULL,
        `date` datetime NOT NULL,
        UNIQUE KEY id (id)
	) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
        update_option('WPLFLA_version', '1.0');

    }
}
if (!function_exists('WPLFLA_filter_action_linkspro')) {
    function WPLFLA_filter_action_linkspro($links)
    {
        $links['settings'] = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=WPLFLA'), __('Settings', 'codepressMaintenance'));
        return $links;
    }
}
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'WPLFLA_filter_action_linkspro', 10, 1 );
add_filter( 'plugin_row_meta', 'WPLFLA_row_meta_pro', 10, 4 );

