<?php
/*
 * Minimal Coming Soon & Maintenance Mode
 * PRO license related functions
 * (c) WebFactory Ltd, 2016 - 2021
 */

if (!defined('WPINC')) {
	die;
}

global $csmm_lc;

$csmm_lc = new WF_Licensing_CSMM(array(
    'prefix' => 'csmm',
    'licensing_servers' => array('https://dashboard.comingsoonwp.com/api/'),
    'version' => csmm_get_plugin_version(),
    'plugin_file' => CSMM_FILE,
    'skip_hooks' => false,
    'plugin_page' => 'settings_page_maintenance_mode_options',
    'disable_remote' => true,
    'debug' => false,
    'js_folder' => plugin_dir_url(CSMM_FILE) . 'framework/admin/js/'
));

csmm_update_license_storage();

add_action('wf_licensing_' . $csmm_lc->prefix . 'validate_ajax', function ($license_key, $result) {
    if (empty($license_key)) {
        set_transient('signals_csmm_err_' . get_current_user_id(), '<div class="csmm-alert csmm-alert-info"><strong>License key saved.</strong><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', 60);
        return;
    }

    global $csmm_lc;
    $license = $csmm_lc->get_license();

    if ($result == true) {
        if (empty($license['error'])) {
            set_transient('signals_csmm_err_' . get_current_user_id(), '<div class="csmm-alert csmm-alert-success"><strong>License key saved and activated!</strong><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', 60);
        } else {
            set_transient('signals_csmm_err_' . get_current_user_id(), '<div class="csmm-alert csmm-alert-danger"><strong>License not active.</strong> ' . $license['error'] . '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', 60);
        }
    } else {
        set_transient('signals_csmm_err_' . get_current_user_id(), '<div class="csmm-alert csmm-alert-danger"><strong>Unable to contact licensing server. Please try again in a few moments.</strong><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', 60);
    }
}, 10, 2);
// validate_ajax


add_action('wf_licensing_' . $csmm_lc->prefix . 'deactivate_ajax', function ($old_license, $result) {
    set_transient('signals_csmm_err_' . get_current_user_id(), '<div class="csmm-alert csmm-alert-info"><strong>License has been deactivated.</strong><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', 60);
}, 10, 2);
// deactivate_ajax


add_action('wf_licensing_' . $csmm_lc->prefix . 'save_ajax', function ($out) {
    if (empty($out['license_key'])) {
        set_transient('signals_csmm_err_' . get_current_user_id(), '<div class="csmm-alert csmm-alert-info"><strong>License key saved.</strong><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', 60);

        return;
    }

    global $csmm_lc;
    $license = $csmm_lc->get_license();

    if (empty($out['error'])) {
        set_transient('signals_csmm_err_' . get_current_user_id(), '<div class="csmm-alert csmm-alert-success"><strong>License key saved and activated!</strong><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', 60);
    } else {
        set_transient('signals_csmm_err_' . get_current_user_id(), '<div class="csmm-alert csmm-alert-danger"><strong>License not active.</strong> ' . $out['error'] . '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', 60);
    }
}, 10, 1);
// save_ajax


function csmm_update_license_storage()
{
    global $csmm_lc;
    $meta = csmm_get_meta();
    $new = array();

    // nothing to update
    if (empty($meta['license_key'])) {
        return false;
    }

    $new['license_key'] = $meta['license_key'];
    if ($meta['license_active']) {
        $new['error'] = '';
    } else {
        $new['error'] = 'Unknown error. Please reactivate the license.';
    }
    $new['valid_until'] = $meta['license_expires'];
    $new['last_check'] = time();
    $new['name'] = $meta['license_type'];
    $new['meta'] = array();

    if ($csmm_lc->update_license($new)) {
        unset($meta['license_key'], $meta['license_type'], $meta['license_expires'], $meta['license_active']);
        update_option('signals_csmm_meta', $meta);

        return true;
    }

    return false;
} // csmm_update_license_storage
