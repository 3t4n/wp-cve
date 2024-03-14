<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    wp_die(esc_html__('File is not called by WordPress', 'WP2SL'));
}
global $wpdb;
delete_option('OEPL_SUGARCRM_URL');
delete_option('OEPL_SUGARCRM_ADMIN_USER');
delete_option('OEPL_SUGARCRM_ADMIN_PASS');
delete_option('OEPL_SugarCRMSuccessMessage');
delete_option('OEPL_SugarCRMFailureMessage');
delete_option('OEPL_SugarCRMReqFieldsMessage');
delete_option('OEPL_SugarCRMInvalidCaptchaMessage');
delete_option('OEPL_RECAPTCHA_SITE_KEY');
delete_option('OEPL_RECAPTCHA_SECRET_KEY');
$wpdb->query("DROP TABLE IF EXISTS oepl_crm_map_fields");