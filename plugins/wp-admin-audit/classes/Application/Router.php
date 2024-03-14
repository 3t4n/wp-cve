<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Router
{
    public static function setupAjaxHooks(){
        add_action( 'wp_ajax__wada_ajax_delete_log', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_preview_log', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_download_log', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_discover_install_sensors', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_events_list', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_event_search', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_events_csv_export', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_extensions_list', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_activate_key', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_deactivate_key', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_reload_event_details_table', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_check_key_status', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_notification_log_list', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_notification_queue_list', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_notification_queue_list_bulk_delete', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_notifications_list', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_notifications_status_toggle', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_process_queue', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_debug_action', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_sensors_list', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_sensors_status_toggle', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_cleanup_event_log', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_get_event_log_stats', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_logins_list', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_logins_csv_export', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_user_search', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_users_list', array( 'WADA_Router', 'doAjaxRouting' ) );
        add_action( 'wp_ajax__wada_ajax_users_csv_export', array( 'WADA_Router', 'doAjaxRouting' ) );
    }

    public static function doAjaxRouting() {
        $view = null;
        $action = sanitize_text_field($_GET['action']);
        WADA_Log::debug('doAjaxRouting: '.$action);
        switch($action){
            case '_wada_ajax_events_list':
                $view = new WADA_View_Events();
                break;
            case '_wada_ajax_events_csv_export':
                $view = new WADA_View_Events();
                $view->csvExportAjaxResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_users_list':
                $view = new WADA_View_Users();
                break;
            case '_wada_ajax_users_csv_export':
                $view = new WADA_View_Users();
                $view->csvExportAjaxResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_logins_list':
                $view = new WADA_View_Logins();
                break;
            case '_wada_ajax_logins_csv_export':
                $view = new WADA_View_Logins();
                $view->csvExportAjaxResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_extensions_list':
                $view = new WADA_View_Extensions();
                break;
            case '_wada_ajax_sensors_list':
                $view = new WADA_View_Sensors();
                break;
            case '_wada_ajax_sensors_status_toggle':
                $view = new WADA_View_Sensors();
                $view->toggleActionAjaxResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_activate_key':
                $view = new WADA_View_Settings();
                $view->keyActivateAjaxResponse();
                break;
            case '_wada_ajax_deactivate_key':
                $view = new WADA_View_Settings();
                $view->keyDeactivateAjaxResponse();
                break;
            case '_wada_ajax_reload_event_details_table':
                $eventId = array_key_exists('id', $_REQUEST) ? intval($_REQUEST['id']) : null;
                $view = new WADA_View_EventDetails($eventId);
                $view->renderEventDetailsAjaxResponse();
                break;
            case '_wada_ajax_check_key_status':
                $view = new WADA_View_Settings();
                $view->checkKeyStatusAjaxResponse();
                break;
            case '_wada_ajax_notification_log_list':
                $view = new WADA_View_NotificationLog();
                break;
            case '_wada_ajax_notification_queue_list':
            case '_wada_ajax_notification_queue_list_bulk_delete':
                $view = new WADA_View_NotificationQueue();
                break;
            case '_wada_ajax_notifications_list':
                $view = new WADA_View_Notifications();
                break;
            case '_wada_ajax_notifications_status_toggle':
                $view = new WADA_View_Notifications();
                $view->toggleActionAjaxResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_cleanup_event_log':
                $view = new WADA_View_Settings();
                $view->cleanupEventLogAjaxResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_event_search':
                $view = new WADA_View_Events();
                $view->eventSearchAjaxResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_discover_install_sensors':
                $view = new WADA_View_Diagnosis();
                $view->discoverInstallSensorsAjaxResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_delete_log':
                $view = new WADA_View_Diagnosis();
                $view->deleteLogAjaxResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_get_event_log_stats':
                $view = new WADA_View_Diagnosis();
                $view->getEventLogStatsAjaxResponse();
                break;
            case '_wada_ajax_download_log':
                $view = new WADA_View_Diagnosis();
                $view->downloadLogAjaxRespsonse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_preview_log':
                $view = new WADA_View_Diagnosis();
                $view->previewLogAjaxRespsonse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_process_queue':
                WADA_Notification_Queue::processNotificationsOfNextEvent();
                die( json_encode( array('success' => true, 'msg' => __('Processed queue', 'wp-admin-audit')) ) );
            // break; // we do die before, so no break needed
            case '_wada_ajax_debug_action':
                $view = new WADA_View_Diagnosis();
                $view->debugActionResponse(); // will exit execution w/ die()
                break;
            case '_wada_ajax_user_search':
                $view = new WADA_View_NotificationWizard();
                $view->userSearchAjaxResponse();
                break;
            default:
                WADA_Log::warning('doAjaxRouting action unknown: '.$action);
        }
        if($view && method_exists($view, 'ajax_response')){
            WADA_Log::debug('doAjaxRouting Ajax routed '.$action);
            $view->ajax_response();
        }else{
            WADA_Log::warning('doAjaxRouting Failed routing of '.$action);
        }
    }


}