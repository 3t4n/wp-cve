<?php
namespace GSBEH;

// if direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Represents as a database utilites.
 * 
 * @since 2.0.12
 */
class Database {

    /**
     * Returns the plugin database behance data table name.
     * 
     * @since  2.0.12
     * @return string Database table name.
     */
    public function get_data_table() {
        global $wpdb;
        return $wpdb->prefix . 'gsbehance';
    }

    /**
     * Returns the plugin database shortcodes table name.
     * 
     * @since  2.0.12
     * @return string Database table name.
     */
    public function get_shortcodes_table() {
        global $wpdb;
        return $wpdb->prefix . 'gsbeh_shortcodes';
    }

    /**
     * Returns the database charset.
     * 
     * @since  2.0.12
     * @return string Database table name.
     */
    public function get_charset() {
        global $wpdb;
        return $wpdb->get_charset_collate();
    }

    /**
     * Create database tables on plugin activation.
     * 
     * @since  2.0.12
     * @return void
     */
    public function migration() {
        plugin()->db->create_data_table();
        plugin()->db->create_shortcodes_table();
    }

    /**
     * Creates a database table for storing behance data.
     * 
     * @since  2.0.12
     * @return void
     */
    public function create_data_table() {
        
        if ( get_option('gs_behance_data_table_created') == true ) return;

        $tableName = plugin()->db->get_data_table();
        $charset = plugin()->db->get_charset();

        $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (
            id int(9) NOT NULL AUTO_INCREMENT,
            beid int(20) NOT NULL,
            beusername tinytext,
            name tinytext NOT NULL,
            url varchar(100) DEFAULT '' NOT NULL,
            big_img varchar(255) DEFAULT '',
            thum_image varchar(255) DEFAULT '',
            blike int(9),
            bview int(9),
            bcomment int(9),
            bfields longtext,
            time datetime NOT NULL,
            PRIMARY KEY  (id)
        ) $charset;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $sql );

        update_option( 'gs_behance_data_table_created', true );

    }

    /**
     * Creates a database table for storing shortcodes data.
     * 
     * @since  2.0.12
     * @return void
     */
    public function create_shortcodes_table() {

        if ( get_option('gs_behance_shortcode_table_version') == true ) return;

        $tableName = plugin()->db->get_shortcodes_table();
        $charset = plugin()->db->get_charset();

        $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (
            id BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
            shortcode_name TEXT NOT NULL,
            shortcode_settings LONGTEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        update_option( 'gs_behance_shortcode_table_version', true );

    }
    
}
