<?php

namespace OXI_FLIP_BOX_PLUGINS\Classes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Installation
 *
 * @author biplo
 */
class Installation {

    protected static $lfe_instance = NULL;

    /**
     * Constructor of Flipbox Plugins
     *
     * @since 2.0.0
     */
    public function __construct() {

    }

    /**
     * Access plugin instance. You can create further instances by calling
     */
    public static function get_instance() {
        if (NULL === self::$lfe_instance)
            self::$lfe_instance = new self;

        return self::$lfe_instance;
    }
 /**
     * Plugin upgrade hook
     *
     * @since 1.0.0
     */
    public function plugin_upgrade_hook($upgrader_object, $options) {
        if ($options['action'] == 'update' && $options['type'] == 'plugin') {
            if (isset($options['plugins'][OXI_FLIP_BOX_TEXTDOMAIN])) {

                $this->Flip_Datatase();
            }
        }
    }

    /**
     * Plugin activation hook
     *
     * @since 3.1.0
     */
    public function plugin_activation_hook() {

        $this->Flip_Datatase();
        // Redirect to options page
        set_transient('oxi_flip_box_activation_redirect', true, 30);
    }
    public function Flip_Datatase() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'oxi_div_style';
        $table_list = $wpdb->prefix . 'oxi_div_list';
        $table_import = $wpdb->prefix . 'oxi_div_import';
        $charset_collate = $wpdb->get_charset_collate();
        $sql1 = "CREATE TABLE $table_name (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                name varchar(50) NOT NULL,
                type varchar(50) NOT NULL,
                style_name varchar(40) NOT NULL,
                css text,
		PRIMARY KEY  (id)
	) $charset_collate;";

        $sql2 = "CREATE TABLE $table_list (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                styleid mediumint(6) NOT NULL,
                type varchar(50),
                files text,
                css text,
		PRIMARY KEY  (id)
	) $charset_collate;";
        $sql3 = "CREATE TABLE $table_import (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                type varchar(100) NOT NULL,
                name varchar(50) NOT NULL,
		PRIMARY KEY  (id),
                UNIQUE unique_index (type, name)
	) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql1);
        dbDelta($sql2);
        dbDelta($sql3);
        add_option('oxilab_flip_box_version', OXI_FLIP_BOX_PLUGIN_VERSION);
        $wpdb->query("INSERT INTO {$table_import} (name, type) VALUES
        (1, 'flip'),
        (2, 'flip'),
        (3, 'flip'),
        (4, 'flip'),
        (5, 'flip')");
    }

   
}
