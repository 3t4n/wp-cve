<?php

require_once(IFSO_PLUGIN_BASE_DIR . 'services/geolocation-service/geolocation-service.class.php');
use IfSo\Services\GeolocationService;

function is_geo_data($geoDatas) {
	return ( isset($geoDatas['success']) && $geoDatas['success'] == true );
}
function get_monthly($geoDatas) {
	if ( is_geo_data($geoDatas) ) {
		return $geoDatas['bank'];
	}
	return 0;
}
function get_queries($geoDatas) {
	if ( is_geo_data($geoDatas) ) {
		return intval($geoDatas['realizations']);
	}
	return 0;
}

global $wpdb;
global $geo_monthly_queries;
global $geo_queries_used;
global $alert_values;
global $local_user_table_name;
global $daily_sessions_table_name;

$db_prefix = $wpdb->prefix;
$local_user_table_name = $db_prefix . 'ifso_local_user';
$daily_sessions_table_name = $db_prefix . 'ifso_daily_sessions';

if($wpdb->get_var("SHOW TABLES LIKE 'ifso_local_user'") || $wpdb->get_var("SHOW TABLES LIKE 'ifso_daily_sessions'"))        //If tables with the old table names still exist, rename them to the new names
    $wpdb->query("RENAME TABLE ifso_local_user TO {$local_user_table_name}, ifso_daily_sessions TO {$daily_sessions_table_name}");


$license = get_option( 'edd_ifso_geo_license_key' );
$geoDatas = GeolocationService\GeolocationService::get_instance()->get_status($license);


$geo_monthly_queries = get_monthly($geoDatas);
$geo_queries_used = get_queries($geoDatas);
$alert_values =$wpdb->get_var("SELECT alert_values FROM {$local_user_table_name}");
if($alert_values==NULL) $alert_values = '100 95 75';
$jal_db_version = '1.0';

if(!function_exists('ifso_jal_install')){
    function ifso_jal_install() {
        global $geo_monthly_queries; //jal_install function can't take these arguments
        global $geo_queries_used;
        global $wpdb;
        global $jal_db_version;
        global $alert_values;
        global $local_user_table_name;
        global $daily_sessions_table_name;

        $wp_user_email = get_option('admin_email');
        $charset_collate = $wpdb->get_charset_collate();



        $sql = "CREATE TABLE IF NOT EXISTS {$local_user_table_name} (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`user_email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
		`user_bank` int(7) NOT NULL,
		`user_sessions` int(7) NOT NULL,
		`alert_values` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
		`pro_bank` INT NOT NULL DEFAULT '0',
		`geo_bank` INT NOT NULL DEFAULT '0',
		`used_pro_sessions` INT NOT NULL DEFAULT '0',
		`used_geo_sessions` INT NOT NULL DEFAULT '0',
		`pro_renewal_date` DATE NULL DEFAULT NULL,
		`geo_renewal_date` DATE NULL DEFAULT NULL,
		PRIMARY KEY(id)
		) $charset_collate;";

        $wpdb->query("DROP TABLE IF EXISTS {$local_user_table_name}");
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        //$sql = "UPDATE ifso_local_user SET (`user_email`, `user_bank`, `user_sessions`, `alert_values`) VALUES ('$wp_user_email', '$geo_monthly_queries', '$geo_queries_used', '100 90 75 60') WHERE `id` =  1";
        $sql = "INSERT IGNORE INTO {$local_user_table_name} (`id`, `user_email`, `user_bank`, `user_sessions`, `alert_values`) VALUES (1,'{$wp_user_email}', '{$geo_monthly_queries}', '{$geo_queries_used}', '{$alert_values}')";

        //dbDelta( $sql );

        $wpdb->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$daily_sessions_table_name} (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`sessions_date` varchar(18) COLLATE utf8mb4_unicode_ci NOT NULL,
			`num_of_sessions` int(11) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `sessions_date` (`sessions_date`)
			) $charset_collate;";

        dbDelta( $sql );
        add_option( 'jal_db_version', $jal_db_version );
    }
}
