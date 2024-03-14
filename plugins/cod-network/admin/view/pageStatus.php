<?php
/**
 * Admin View: Page - Activity Logs Home
 */

use CODNetwork\Controller\CODN_Logger_Controller;
use CODNetwork\Controller\CODN_Notification_Controller;
use CODNetwork\Services\CODN_File_Log_Service;
use CODNetwork\Services\CODN_Logger_Service;
use WP_Error as WP_Error;

if (!defined('ABSPATH')) {
    exit;
}

$currentTab = !empty($_REQUEST['tab']) ? sanitize_title($_REQUEST['tab']) : 'status';
$tabs = [
    'status' => __('Logs', 'CodNetwork'),
];
?>
<div class="wrap woocommerce">
    <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
        <?php
        foreach ($tabs as $name => $label) {
            echo sprintf(
                '<a href="%s" class="nav-tab ',
                sprintf(admin_url("admin.php?page=codNetwork-status&tab=%s"), $name)
            );

            if ($currentTab == $name) {
                echo 'nav-tab-active';
            }

            echo sprintf('">%s</a>', $label);
        }
        ?>
    </nav>
    <h1 class="screen-reader-text"><?php
        echo esc_html($tabs[$currentTab]); ?></h1>

    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-10">
                <?php

                switch ($currentTab) {
                    case 'status':
                        $logger = new CODN_Logger_Service();
                        $loggerController = new CODN_Logger_Controller();
                        $notification = new CODN_Notification_Controller();
                        $fileLogService = new CODN_File_Log_Service();
                        $log = $logger->getDisplayStanderLogs();
                        $response = null;

                        if (isset($_POST['send_selected_file'])) {
                            $searchFile = $_POST['send_selected_file'];
                        }

                        if (isset($_POST['send_via_slack'])){
                            $response = $notification->codn_send_via_slack($searchFile);
                            $logFile = $logger->getLogs($searchFile);
                            $log = $logger->getDisplayStanderLogs($logFile);
                        }

                        if ($response === false) {
                            echo sprintf(
                                '<div class="woocommerce-message notice notice-error inline p-3"><h5 class="m-0">%s</h5></div>',
                                esc_html('You have reached the maximum of reporting for today', 'COD.NETWORK')
                            );
                        }

                        if ($response === true) {
                            echo sprintf(
                                '<div class="updated woocommerce-message inline p-3"><h5 class="m-0">%s</h5></div>',
                                esc_html('your report has successfully been sent.')
                            );
                        }

                        if ($response instanceof WP_Error) {
                            echo sprintf(
                                '<div class="woocommerce-message notice notice-error inline p-3"><h5 class="m-0">%s</h5></div>',
                                esc_html('Something went wrong while reporting', 'COD.NETWORK')
                            );
                        }

                        if (isset($_POST['search_file']) && isset($_POST['log_file'])) {
                            $searchFile = $_POST['log_file'];
                            $logFile = $logger->getLogs($searchFile);
                            $log = $logger->getDisplayStanderLogs($logFile);
                        }

                        if (isset($_POST['delete_log']) && isset($_POST['submit'])) {
                            $loggerController->codn_delete_file_log($searchFile);
                            $log = '';
                        }

                        if (!isset($searchFile)) {
                            $searchFile = $fileLogService->getCurrentFileName();
                        }

                        include_once(sprintf('%sadmin/view/pageStatusLog.php', CODN__PLUGIN_DIR));
                        break;
                    default:
                        break;
                }
                ?>
            </div>
        </div>

    </div>
</div>

