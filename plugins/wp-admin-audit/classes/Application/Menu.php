<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Menu
{
    public static function getPageTitle($default){
        $pageTitleSuffix =  ' - ' . __('WP Admin Audit', 'wp-admin-audit');
        $subPage = self::getSubpage();
        switch($subPage){
            case 'user-details':
                $pageTitle = sprintf(__('User ID %d', 'wp-admin-audit'), self::getIdFromRequest()). ' - '. __('Users','wp-admin-audit');
                break;
            case 'event-details':
                $pageTitle = sprintf(__('Event ID %d', 'wp-admin-audit'), self::getIdFromRequest()). ' - '. __('Events','wp-admin-audit');
                break;
            case 'login-details':
                $pageTitle = sprintf(__('Login ID %d', 'wp-admin-audit'), self::getIdFromRequest()). ' - '. __('Logins','wp-admin-audit');
                break;
            case 'wizard':
                $pageTitle = ((self::getIdFromRequest() > 0) ? sprintf(__('Notification ID %d', 'wp-admin-audit'), self::getIdFromRequest()) : __('New notification', 'wp-admin-audit')). ' - '. __('Notifications','wp-admin-audit');
                break;
            default:
                $pageTitle =  $default;
        }
        $pageTitle .= $pageTitleSuffix;
        return $pageTitle;
    }

    public static function adminMenu() {

        $capabilityMainMenu = 'manage_options';
        $slugMainMenu = 'wp-admin-audit';

        /* Based on icon-only.svg from WADA logo set */
        $iconBase64 = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTEuODM5IiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KCiA8Zz4KICA8dGl0bGU+TGF5ZXIgMTwvdGl0bGU+CiAgPHBhdGggaWQ9InN2Z18xIiBkPSJtNDUuOTcyNjQsMGw0My4wMjczNiwyNC44MzA1NWwwLDQ5LjYzMTc3bC00Mi45NDMzMiwyNC44MzA1NWwtNDMuMDI5MzIsLTI0LjgwMzE4bC0wLjAyNzM2LC00OS42MzE3N2w0Mi45NzI2NCwtMjQuODU3OTF6bTAuMDI3MzYsMTQuNTE4MzZsLTMwLjQzMjI3LDE3LjU4NTA1bDAsMzUuMTcyMDVsMzAuNDg4OTYsMTcuNTgzMDlsMzAuNDAyOTYsLTE3LjYxMDQ1bC0wLjAyNzM2LC0zNS4xNzAwOWwtMzAuNDMyMjcsLTE3LjU1OTY0em0tMC4wMDE5NSwxMC41NjA0MWwyMS4zMTgyMywxMi4zMTc1NWwwLDI0LjU1MTA1bC0yMS4zMTgyMywxMi4zMTc1NWwtMjEuMjkwODYsLTEyLjI5MDE4bC0wLjAyOTMyLC0yNC41Nzg0MWwyMS4zMjIxNCwtMTIuMzE5NWwtMC4wMDE5NSwwLjAwMTk1eiIgZmlsbC1ydWxlPSJldmVub2RkIiBmaWxsPSJjdXJyZW50Q29sb3IiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIvPgogPC9nPgo8L3N2Zz4=';


        $mainPageHookSuffix = add_menu_page(
            'WP Admin Audit',
            'WP Admin Audit',
            $capabilityMainMenu,
            $slugMainMenu,
            array( __CLASS__, 'mainPage' ),
            $iconBase64,
            3
        );

        $dashboardHookSuffix = add_submenu_page(
            $slugMainMenu,
            self::getPageTitle(__('Dashboard', 'wp-admin-audit')),
            __('Dashboard', 'wp-admin-audit'),
            $capabilityMainMenu,
            $slugMainMenu // same as top menu to have identical view with different name
        );

        $eventPageHookSuffix = add_submenu_page(
            $slugMainMenu,
            self::getPageTitle(__('Event log', 'wp-admin-audit')),
            __('Event log', 'wp-admin-audit'),
            $capabilityMainMenu,
            $slugMainMenu.'-events',
            array( __CLASS__, 'eventsPage' )
        );

        $userPageHookSuffix = add_submenu_page(
            $slugMainMenu,
            self::getPageTitle(__('User audit', 'wp-admin-audit')),
            __('User audit', 'wp-admin-audit'),
            $capabilityMainMenu,
            $slugMainMenu.'-users',
            array( __CLASS__, 'usersPage' )
        );

        $userPageHookSuffix = add_submenu_page(
            $slugMainMenu,
            self::getPageTitle(__('Login audit', 'wp-admin-audit')),
            __('Login audit', 'wp-admin-audit'),
            $capabilityMainMenu,
            $slugMainMenu.'-logins',
            array( __CLASS__, 'loginsPage' )
        );

        $notificationPageHookSuffix = add_submenu_page(
            $slugMainMenu,
            self::getPageTitle(__('Notifications','wp-admin-audit')),
            __('Notifications', 'wp-admin-audit'),
            $capabilityMainMenu,
            $slugMainMenu.'-notifications',
            array( __CLASS__, 'notificationsPage' )
        );

        $settingsPageHookSuffix = add_submenu_page(
            $slugMainMenu,
            self::getPageTitle(__('Settings','wp-admin-audit')),
            __('Settings', 'wp-admin-audit'),
            $capabilityMainMenu,
            $slugMainMenu.'-settings',
            array( __CLASS__, 'settingsPage' )
        );

        $infoPageHookSuffix = add_submenu_page(
            $slugMainMenu,
            self::getPageTitle(__('Info','wp-admin-audit')),
            __('Info', 'wp-admin-audit'),
            $capabilityMainMenu,
            $slugMainMenu.'-info',
            array( __CLASS__, 'infoPage' )
        );

    }

    public static function mainPage(){
        if(self::getSubpage() === 'audit'){
            $view = new WADA_View_Audit();
        }else{
            $view = new WADA_View_Dashboard();
        }
        $view->execute();
    }

    public static function eventsPage(){
        if(self::getSubpage() === 'event-details'){
            $view = new WADA_View_EventDetails(self::getIdFromRequest());
        }else{
            $view = new WADA_View_Events();
        }
        $view->execute();
    }

    public static function usersPage(){
        if(self::getSubpage() === 'user-details'){
            $view = new WADA_View_UserDetails(self::getIdFromRequest());
        }else{
            $view = new WADA_View_Users();
        }
        $view->execute();
    }

    public static function loginsPage(){
        $view = new WADA_View_Logins();
        $view->execute();
    }

    public static function auditPage(){
        if(self::getSubpage() === 'events'){
            $view = new WADA_View_Events();
        }elseif(self::getSubpage() === 'event-details'){
            $view = new WADA_View_EventDetails(self::getIdFromRequest());
        }elseif(self::getSubpage() === 'users'){
            $view = new WADA_View_Users();
        }elseif(self::getSubpage() === 'user-details'){
            $view = new WADA_View_UserDetails(self::getIdFromRequest());
        }elseif(self::getSubpage() === 'logins'){
            $view = new WADA_View_Logins();
        }else{
            $view = new WADA_View_Audit();
        }
        $view->execute();
    }

    public static function notificationsPage(){

        /*  */

        /* @@REMOVE_START_WADA_enterprise@@ */
        /* @@REMOVE_START_WADA_business@@ */
        /* @@REMOVE_START_WADA_startup@@ */
        $view = new WADA_View_Notifications();
        $view->displayComingSoon();
        /* @@REMOVE_END_WADA_startup@@ */
        /* @@REMOVE_END_WADA_business@@ */
        /* @@REMOVE_END_WADA_enterprise@@ */

    }

    public static function settingsPage(){
        if(self::getSubpage() === 'sensor'){
            $view = new WADA_View_Sensor(self::getIdFromRequest());
        }elseif(self::getSubpage() === 'extension') {
            $view = new WADA_View_ExtensionAction();
        }else{
            $view = new WADA_View_Settings();
        }
        $view->execute();
    }

    public static function infoPage(){
        if(self::getSubpage() === 'diagnosis'){
            $view = new WADA_View_Diagnosis();
        }else{
            $view = new WADA_View_Info();
        }
        $view->execute();
    }

    public static function adminAssets() {
        $includeAdminStyles = false;
        $page = '';
        if ( isset( $_GET['page'] ) && ! empty( $_GET['page'] )){
            $page = sanitize_text_field($_GET['page']);

            // include on known admin pages as well as pages starting with "wp-admin-audit-"
            if ($page === 'wp-admin-audit' // dashboard
                || (strpos( $page , 'wp-admin-audit-') === 0)) {
                $includeAdminStyles = true;
            }
        }else{
            $request = basename($_SERVER["REQUEST_URI"], ".php");
            if($request === 'index' && WADA_Settings::isLastActivitiesWidgetEnabled()){
                $includeAdminStyles = true;
            }
        }

        if($includeAdminStyles){
            //WADA_Log::debug('adminAssets Include admin styles (page: '.$page.')');
            $pluginSlug = basename(realpath(__DIR__.'/../../'));
            $assetsUrl = trailingslashit( plugins_url($pluginSlug) );
            wp_register_style('wada_admin_style', $assetsUrl . 'assets/css/wada.admin.css');
            wp_register_style('wada_toggle_style', $assetsUrl . 'assets/css/wada.toggle.css');
            wp_enqueue_style('wada_admin_style');
            wp_enqueue_style('wada_toggle_style');

            $subpage = self::getSubpage();
            switch($page){
                case 'wp-admin-audit-info':
                case 'wp-admin-audit-settings':
                    WADA_ScriptUtils::loadTabs();
                    add_thickbox(); // for modal (used for plugin info)
                    break;
                case 'wp-admin-audit-events':
                case 'wp-admin-audit-users':
                case 'wp-admin-audit-logins':
                    add_thickbox(); // for modal (used for CSV export)
                    break;
            }
        }
    }

    protected static function getSubpage(){
        if( isset($_GET['subpage'])  && !empty( $_GET['subpage'] )){
            return sanitize_text_field($_GET['subpage']);
        }
        return null;
    }

    protected static function getIdFromRequest($idAttr='sid', $default = 0){
        if (isset($_GET[$idAttr])){
            return absint($_GET[$idAttr]);
        }
        return $default;
    }
}