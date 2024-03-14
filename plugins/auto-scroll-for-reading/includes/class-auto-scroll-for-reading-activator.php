<?php
global $wpg_auto_scroll_db_version;
$wpg_auto_scroll_db_version = '1.0.0';

/**
 * Fired during plugin activation
 *
 * @link       https://wpglob.com/
 * @since      1.0.0
 *
 * @package    Auto_Scroll_For_Reading
 * @subpackage Auto_Scroll_For_Reading/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Auto_Scroll_For_Reading
 * @subpackage Auto_Scroll_For_Reading/includes
 * @author     WP Glob <info@wpglob.com>
 */
class Auto_Scroll_For_Reading_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        global $wpdb;
        global $wpg_auto_scroll_db_version;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$installed_ver = get_option( "wpg_auto_scroll_db_version" );
		$settings_table = $wpdb->prefix . 'wpgautoscroll_settings';
		$charset_collate = $wpdb->get_charset_collate();
		
		if($installed_ver != $wpg_auto_scroll_db_version)  {

            $sql = "CREATE TABLE `".$settings_table."` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `meta_key` TEXT NOT NULL DEFAULT '',
                `meta_value` TEXT NOT NULL DEFAULT '',
                `note` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$settings_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            update_option( 'wpg_auto_scroll_db_version', $wpg_auto_scroll_db_version );

			
        }

		$metas = array(
			"options"
		);
		foreach($metas as $meta_key){
			$meta_val = "";
			$sql = "SELECT COUNT(*) FROM `".$settings_table."` WHERE `meta_key` = '". esc_sql( $meta_key ) ."'";
			$result = $wpdb->get_var($sql);
			if(intval($result) == 0){
				$result = $wpdb->insert(
					$settings_table,
					array(
						'meta_key'    => $meta_key,
						'meta_value'  => $meta_val,
						'note'        => "",
						'options'     => ""
					),
					array( '%s', '%s', '%s', '%s' )
				);
			}
		}
	}
	
	public static function wpg_auto_scroll_update_db_check() {
        global $wpg_auto_scroll_db_version;
        if ( get_site_option( 'wpg_auto_scroll_db_version' ) != $wpg_auto_scroll_db_version ) {
            self::activate();
        }
    }

}
