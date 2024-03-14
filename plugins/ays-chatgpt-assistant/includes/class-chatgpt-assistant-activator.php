<?php
global $ays_chatgpt_assistant_db_version;
$ays_chatgpt_assistant_db_version = '1.0.5';
/**
 * Fired during plugin activation
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/includes
 * @author     Ays_ChatGPT Assistant Team <info@ays-pro.com>
 */
class Chatgpt_Assistant_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
        global $ays_chatgpt_assistant_db_version;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $installed_ver = get_option( "ays_chatgpt_assistant_db_version" );
        $data_table = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'data';
        $settings_table = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'settings';
        $front_settings = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'front_settings';
        $general_settings = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'general_settings';
        $rates_table = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'rates';
        $charset_collate = $wpdb->get_charset_collate();

        if( $installed_ver != $ays_chatgpt_assistant_db_version )  {

            $sql = "CREATE TABLE `".$data_table."` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `api_key` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$data_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if( empty( $results ) ){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

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

            $sql = "CREATE TABLE `".$front_settings."` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `meta_key` TEXT NOT NULL DEFAULT '',
                `meta_value` TEXT NOT NULL DEFAULT '',
                `note` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$front_settings."' ";
            $results = $wpdb->get_results($sql_schema);

            if( empty( $results ) ){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
            
            $sql = "CREATE TABLE `".$general_settings."` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `meta_key` TEXT NOT NULL DEFAULT '',
                `meta_value` TEXT NOT NULL DEFAULT '',
                `note` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$general_settings."' ";
            $results = $wpdb->get_results($sql_schema);

            if( empty( $results ) ){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            $sql = "CREATE TABLE `".$rates_table."` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `post_id` INT(11) NOT NULL DEFAULT 0,
                `user_id` INT(11) NOT NULL DEFAULT '0',
                `user_name` TEXT NOT NULL DEFAULT '',
                `user_email` TEXT NOT NULL DEFAULT '',
                `date` TEXT NOT NULL DEFAULT '',
                `chat_source` TEXT NOT NULL DEFAULT '',
                `chat_type` TEXT NOT NULL DEFAULT '',
                `feedback` TEXT NOT NULL DEFAULT '',
                `action` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$rates_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if( empty( $results ) ){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            update_option( 'ays_chatgpt_assistant_db_version', $ays_chatgpt_assistant_db_version );

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
	}

	public static function db_update_check() {
        global $ays_chatgpt_assistant_db_version;
        if ( get_site_option( 'ays_chatgpt_assistant_db_version' ) != $ays_chatgpt_assistant_db_version ) {
            self::activate();
        }
    }

}
