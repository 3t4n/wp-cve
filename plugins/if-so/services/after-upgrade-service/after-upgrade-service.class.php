<?php
/**
 *Checks whether the plugin has been updated and if it was, run the relevant compatibility routine
 *
 * @author Nick Martianov
 *
 **/
namespace IfSo\Services\AfterUpgradeService;

class AfterUpgradeService {
    private static $instance, $code_version, $db_version;

    private function __construct() {
        self::$code_version = IFSO_WP_VERSION;
        self::$db_version = get_option('ifso_wp_version');
    }

    public static function get_instance() {
        if ( NULL == self::$instance )
            self::$instance = new AfterUpgradeService();

        return self::$instance;
    }

    public static function isUpdated(){
        if(self::$code_version!=self::$db_version){
            return true;
        }
        return false;
    }

    private static function onUpdateHandler(){
        //Actions that are done whenever the code version is detected to be different form the one written down in DB(on update)

        //Try to activate the licenses that are already recored in the DP
        self::reactivate_licenses();

        //Create the tables required to run the plugin, this also runs on activation
        require_once IFSO_PLUGIN_BASE_DIR . 'extensions/ifso-tables/ifso-table-creator.php';
        \ifso_jal_install();
        self::reset_metabox_order();
        //Add new columns to ifso_local_user_table to help track license renewals
        self::create_license_renew_columns_if_not_exist();
        //Reset the geo triggers for them to be able to be sent again - only if upgrading from version 1.4.4>
        self::reset_geo_notification_triggers();
    }

    public static function handle(){
        if(self::isUpdated()){
            try{
                self::onUpdateHandler();
                update_option('ifso_wp_version',self::$code_version);
                self::$db_version = self::$code_version;
                return true;
            }
            catch (\Exception $e){
                error_log('If-so after upgrade service has thrown an exception : ' . $e->getMessage());
            }

        }
        return false;
    }


    private static function reactivate_licenses(){
        require_once IFSO_PLUGIN_BASE_DIR . 'services/license-service/license-service.class.php';
        require_once IFSO_PLUGIN_BASE_DIR . 'services/license-service/geo-license-service.class.php';


        // retrieve our license key & item name from the DB
        $license = get_option('edd_ifso_license_key');
        $item_id = get_option('edd_ifso_license_item_id');
        $status = get_option('edd_ifso_license_status');

        $geo_license = get_option('edd_ifso_geo_license_key');
        $geo_item_id = get_option('edd_ifso_geo_license_item_id');
        $geo_status = get_option('edd_ifso_geo_license_status');

        if($license && $status){
            $license_service = \IfSo\Services\LicenseService\LicenseService::get_instance();
            $license_service->activate_license(trim($license),$item_id);

        }

        if($geo_license && $geo_status){
            $geo_license_service =  \IfSo\Services\GeoLicenseService\GeoLicenseService::get_instance();
            $geo_license_service->activate_license(trim($geo_license),$geo_item_id);
        }
    }

    private static function reset_metabox_order(){
        global $wpdb;
        $wpdb->update($wpdb->prefix.'usermeta',['meta_key'=>''],['meta_key'=>'meta-box-order_ifso_triggers']);
    }

    private static function create_license_renew_columns_if_not_exist(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'ifso_local_user';
        $checkrow = $wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$table_name}' AND column_name = 'pro_renewal_date'");
        if(empty($checkrow)){
            //$wpdb->query("ALTER TABLE `ifso_local_user` ADD `pro_renewal_date` DATE NULL DEFAULT NULL AFTER `alert_values`, ADD `geo_renewal_date` DATE NULL DEFAULT NULL AFTER `Pro_renewal_date`;");
            $wpdb->query("ALTER TABLE `{$table_name}` ADD `pro_bank` INT NOT NULL DEFAULT '0' AFTER `alert_values`, ADD `geo_bank` INT NOT NULL DEFAULT '0' AFTER `pro_bank`, ADD `used_pro_sessions` INT NOT NULL DEFAULT '0' AFTER `geo_bank`, ADD `used_geo_sessions` INT NOT NULL DEFAULT '0' AFTER `used_pro_sessions`, ADD `pro_renewal_date` DATE NULL DEFAULT NULL AFTER `used_geo_sessions`, ADD `geo_renewal_date` DATE NULL DEFAULT NULL AFTER `pro_renewal_date`;");
        }
    }

    private static function reset_geo_notification_triggers(){
        if(version_compare(self::$db_version,'1.4.5','<')){     //Only do this if upgrading from if-so v. 1.4.4>
            require_once IFSO_PLUGIN_BASE_DIR . 'services/geolocation-service/geolocation-service.class.php';
            $geo_service = \IfSo\Services\GeolocationService\GeolocationService::get_instance();
            $geo_service->reset_email_triggers();
        }
    }

}