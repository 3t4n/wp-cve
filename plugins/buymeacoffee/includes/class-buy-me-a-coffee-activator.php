<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.buymeacoffee.com
 * @since      1.0.0
 *
 * @package    Buy_Me_A_Coffee
 * @subpackage Buy_Me_A_Coffee/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Buy_Me_A_Coffee
 * @subpackage Buy_Me_A_Coffee/includes
 * @author     Buymeacoffee <hello@buymeacoffee.com>
 */
class Buy_Me_A_Coffee_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        global $wpdb;
        $table_name      = $wpdb->prefix . 'bmc_plugin';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);

        $sql = "CREATE TABLE $table_name (
               id mediumint(9) NOT NULL AUTO_INCREMENT,
               created_on datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
               slug VARCHAR(70),
               name VARCHAR(30),
               background_color VARCHAR(30),
               text_color VARCHAR(20),
               widget_text VARCHAR(50),
               font_family VARCHAR(100),
               type TINYINT DEFAULT 1,
               admin_email VARCHAR(100),
               button_isactive TINYINT DEFAULT 0,
               PRIMARY KEY  (id)
               ) $charset_collate;";


        $table_name      = $wpdb->prefix . 'bmc_widget_plugin';
        $sqlWidget = "CREATE TABLE $table_name (
                     id mediumint(9) NOT NULL AUTO_INCREMENT,
                     created_on datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                     name TEXT,
                     description TEXT,
                     message TEXT,
                     widget_color TEXT,
                     align TEXT,
                     side_spacing TEXT,
                     bottom_spacing TEXT,
                     admin_email TEXT,
                     widget_isactive TEXT,
                     PRIMARY KEY  (id)
                     ) $charset_collate;";


        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        dbDelta($sqlWidget);

        update_option('bmc_plugin_activated', 0);
    }
}
