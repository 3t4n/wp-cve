<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_admin_getback {
    public function __construct() {
        if (gdmaq_admin()->page === 'front') {
            if (isset($_GET['dashboard-action'])) {
                $this->dashboard_actions();
            }
        }

        if (gdmaq_admin()->page === 'tools') {
            if (isset($_GET['run']) && $_GET['run'] == 'export') {
                $this->tools_export();
            }
        }

        if (gdmaq_admin()->page === 'log') {
            if (isset($_GET['single-action']) && $_GET['single-action'] == 'delete') {
                $this->log_delete();
            }

            if (isset($_GET['single-action']) && $_GET['single-action'] == 'retry') {
                $this->log_retry();
            }

            if (isset($_GET['action']) || isset($_GET['action2'])) {
                $this->log_bulk();
            }
        }

        if (isset($_GET['single-action']) && $_GET['single-action'] == 'dismiss-lite-to-pro') {
            $this->dismiss_lite_to_pro();
        }

        do_action('gdmaq_admin_getback_handler');
    }

    public function dashboard_actions() {
        $action = d4p_sanitize_slug($_GET['dashboard-action']);
        $nonce = d4p_sanitize_basic($_GET['_nonce']);

        if (wp_verify_nonce($nonce, 'gdmaq-'.$action)) {
            switch ($action) {
                case 'clear-board':
                    gdmaq_settings()->set('dashboard_errors', gmdate('Y-m-d H:i:s'), 'core', true);
                    break;
            }
        }

        $url = self_admin_url('admin.php?page=gd-mail-queue-front');

        wp_redirect($url);
        exit;
    }

    public function dismiss_lite_to_pro() {
        gdmaq_settings()->set('show_coupon_36', false, 'core', true);

        wp_redirect(gdmaq_admin()->current_url(false));
        exit;
    }

    public function log_retry() {
        $log_id = isset($_GET['log_id']) ? absint($_GET['log_id']) : 0;

        check_ajax_referer('gdmaq-log-retry-'.$log_id);

        $url = self_admin_url('admin.php?page=gd-mail-queue-log');

        if ($log_id > 0) {
            $log = gdmaq_db()->email_log_get_entry($log_id);
            $email = new gdmaq_core_email('log', $log);
            $email->add_to_queue();
            gdmaq_db()->email_log_update_status($log_id, 'retry');

            $url.= '&message=retried';
        }

        wp_redirect($url);
        exit;
    }

    public function log_delete() {
        $log_id = isset($_GET['log_id']) ? absint($_GET['log_id']) : 0;

        check_ajax_referer('gdmaq-log-delete-'.$log_id);

        $url = self_admin_url('admin.php?page=gd-mail-queue-log');

        if ($log_id > 0) {
            gdmaq_db()->email_log_delete($log_id);

            $url.= '&message=deleted';
        }

        wp_redirect($url);
        exit;
    }

    public function log_bulk() {
        check_admin_referer('bulk-entries');

        $action = $this->_bulk_action();

        if ($action != '') {
            $items = isset($_GET['entry']) ? d4p_sanitize_basic_array((array)$_GET['entry']) : array();

            $url = self_admin_url('admin.php?page=gd-mail-queue-log');

            $message = 'nothing';

            if (!empty($items)) {
                switch ($action) {
                    case 'delete':
                        gdmaq_db()->email_log_delete($items);

                        $message = 'deleted&count='.count($items);
                        break;
                    case 'retry':
                        $failed = gdmaq_db()->email_get_failed($items);

                        if (!empty($failed)) {
                            foreach ($failed as $log_id) {
                                $log = gdmaq_db()->email_log_get_entry($log_id);
                                $email = new gdmaq_core_email('log', $log);
                                $email->add_to_queue();
                                gdmaq_db()->email_log_update_status($log_id, 'retry');
                            }

                            $message = 'retried&count='.count($failed);
                        }
                        break;
                }
            }

            $url .= '&message='.$message;

            wp_redirect($url);
            exit;
        }
    }

    private function tools_export() {
        check_ajax_referer('dev4press-plugin-export');

        if (!d4p_is_current_user_admin()) {
            wp_die(__("Only administrators can use export features.", "gd-mail-queue"));
        }

        $export_date = date('Y-m-d-H-i-s');
        $export = isset($_GET['export']) ? (array)$_GET['export'] : array();
        $export = d4p_sanitize_basic_array($export);
        $export = array_intersect(array('settings', 'statistics'), $export);

        if (empty($export)) {
            wp_die(__("Nothing selected to export.", "gd-mail-queue"));
        }

        $list = array();

        if (in_array('settings', $export)) {
            $list = array_keys(gdmaq_settings()->settings);
            $list = d4p_remove_from_array_by_value($list, 'statistics', false);
        }

        if (in_array('statistics', $export)) {
            $list[] = 'statistics';
        }

        header('Content-type: application/json');
        header('Content-Disposition: attachment; filename="gd_mail_queue_export_'.$export_date.'.json"');

        die(gdmaq_settings()->export_to_json($list));
    }

    private function _bulk_action() {
        $action = isset($_GET['action']) && $_GET['action'] != '' && $_GET['action'] != '-1' ? d4p_sanitize_basic($_GET['action']) : '';

        if ($action == '') {
            $action = isset($_GET['action2']) && $_GET['action2'] != '' && $_GET['action2'] != '-1' ? d4p_sanitize_basic($_GET['action2']) : '';
        }

        return $action;
    }
}
