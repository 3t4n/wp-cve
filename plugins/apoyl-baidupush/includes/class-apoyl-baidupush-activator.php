<?php

/*
 * @link http://www.apoyl.com/
 * @since 1.0.0
 * @package Apoyl_Baidupush
 * @subpackage Apoyl_Baidupush/includes
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Apoyl_Baidupush_Activator
{

    public static function activate()
    {
        $options_name = 'apoyl-baidupush-settings';
        $arr_options = array(
            'site' => '',
            'secret' => '',
            'https' => 0,
            'autopush'=>0,
        );
        add_option($options_name, $arr_options);
    }

    public static function install_db()
    {
        global $wpdb;
        $apoyl_baidupush_db_version = APOYL_BAIDUPUSH_VERSION;
        $tablename = $wpdb->prefix . 'apoyl_baidupush';
        $ishave = $wpdb->get_var('show tables like \'' . $tablename . '\'');
        
        if ($tablename != $ishave) {
            $sql = "CREATE TABLE " . $tablename . " (
                      `id`	bigint(20) NOT NULL AUTO_INCREMENT,
                      `aid` bigint(20) NOT NULL default '0',
                      `subject` varchar(100) NOT NULL,
                      `url` varchar(200) NOT NULL,
                      `ispush` tinyint(1) NOT NULL default '0',
                      `isdel` tinyint(1) NOT NULL default '0',
                      `msgs` varchar(200) NOT NULL,
                      `modtime` int(10) NOT NULL default '0',
                      PRIMARY KEY (`id`),
                      KEY `modtime` (`modtime`)
                    );";
        }
        
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
        add_option('apoyl_baidupush_db_version', $apoyl_baidupush_db_version);
    }
}
?>