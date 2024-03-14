<?php

/**
 * Fires during plugin activation
 *
 * @link       https://www.jssor.com
 * @since      1.0.0
 * @author jssor
 */

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

class WP_Jssor_Slider_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        WP_Jssor_Slider_Activator::ensure_instance_id();

        WP_Jssor_Slider_Activator::ensure_resources();
        WP_Jssor_Slider_Activator::migrate_dbs();
        WP_Jssor_Slider_Activator::check_slider_script();

        WP_Jssor_Slider_Activator::remove_crons();
	}

    /**
     * update when load plugins
     *
     * @return void
     */
    public static function update()
    {
        WP_Jssor_Slider_Activator::ensure_instance_id();
        WP_Jssor_Slider_Activator::check_slider_script();
    }

    /**
     * common functions called on activate & update plugin.
     *
     * @return void
     */
    private static function ensure_instance_id()
    {
        if (null === get_option('wp_jssor_slider_instance_id', null)) {
            update_option('wp_jssor_slider_instance_id', WP_Jssor_Slider_Utils::create_guid());
        }
    }

    /**
     * no crons since 3.1.0
     */
    private static function remove_crons() {
        $cur_ver = get_option('wp_jssor_slider_db_version', '1.0.0');

        if (version_compare($cur_ver, '3.1.0') < 0) {
            //no crons since 3.1.0
            wp_clear_scheduled_hook('wjssl_check_slider_files_hook');
        }
    }

    /**
     * create upload folders for jssor-slider
     *
     * @return void
     */
    private static function ensure_resources()
    {
        $upload = wp_upload_dir();

        $src_dir = realpath(WP_JSSOR_SLIDER_PATH . WP_Jssor_Slider_Globals::DIR_RESOURCES_UPLOAD);
        $dst_dir = $upload['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM;

        $dirs_to_copy = array(
            //'demos',
            'script',
            'theme/svg'
            );

        foreach($dirs_to_copy as $dir_to_copy) {
            $dst = $dst_dir . '/' . $dir_to_copy;
            if (! is_dir($dst)) {
                wp_mkdir_p($dst);
            }
            $src = $src_dir . '/' . $dir_to_copy;
            WP_Jssor_Slider_Activator::rcopy($src, $dst);
        }
    }

    /**
     * @return boolean
     */
    public static function check_slider_script()
    {
        $upload_dir = wp_upload_dir();
        $script_path = $upload_dir['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_SCRIPTS;
        $script_name = 'jssor.slider' . '-' . WP_JSSOR_MIN_JS_VERSION . '.min.js';
        $script_file = $script_path . '/' . $script_name;

        if (file_exists($script_file)) {
            return true;
        }

        $src_dir = realpath(WP_JSSOR_SLIDER_PATH . WP_Jssor_Slider_Globals::DIR_RESOURCES_SCRIPT);
        $target_file = $src_dir . '/' . $script_name;
        if ($src_dir && file_exists($target_file)) {
            WP_Jssor_Slider_Activator::rcopy($target_file, $script_file);
            return true;
        }
        return false;
    }

    private static function migrate_dbs() {
        global $wpdb;
        $cur_ver = get_option('wp_jssor_slider_db_version', '1.0.0');

        $charset_collate = $wpdb->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        #region create sliders table

        $sliders_table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;

        $sql = "CREATE TABLE IF NOT EXISTS $sliders_table_name (
            id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
            file_name varchar(100) NOT NULL default '',
            file_path varchar(255) NOT NULL default '',
            code_path varchar(255) NOT NULL default '',
            html_path varchar(255) NOT NULL default '',
            thumb_path text,
            grid_thumb_path text,
            list_thumb_path text,
            created_at datetime,
            updated_at datetime,
            PRIMARY KEY  (id),
            UNIQUE KEY file_name (file_name)
        ) $charset_collate;";

        dbDelta( $sql );

        #endregion

        #region create transactions table

        $trans_table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_TRANSACTIONS;

        $sql = "CREATE TABLE IF NOT EXISTS $trans_table_name (
            id varchar(32) NOT NULL,
            type mediumint(9) unsigned NOT NULL,
            meta text NOT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        dbDelta( $sql );

        #endregion

        if (version_compare($cur_ver, '3.1.0') < 0) {
            $wpdb->query(
                "UPDATE $sliders_table_name SET `grid_thumb_path` = '', `list_thumb_path` = ''"
            );
        }

        update_option('wp_jssor_slider_db_version', WP_JSSOR_SLIDER_VERSION);
    }

    // copies files and non-empty directories
    private static function rcopy($src, $dst) {
        if (is_dir($src)) {
            wp_mkdir_p($dst);
            $files = scandir($src);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    WP_Jssor_Slider_Activator::rcopy("$src/$file", "$dst/$file");
                }
            }
        } else if (file_exists($src)) {
            copy($src, $dst);
        }
    }
}
