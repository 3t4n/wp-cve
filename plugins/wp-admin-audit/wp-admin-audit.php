<?php
/*
 * Plugin Name: WP Admin Audit
 * Plugin URI: https://wpadminaudit.com/
 * Description: Monitor the security relevant activities on your site and get notified when something out of the usual is happening. Browse the event log to find out who did what at which time.
 * Version: 1.2.9
 * Author: brandtoss
 * Author URI: https://wpadminaudit.com/
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 5.5
 * Requires PHP: 5.6
 *
 * Text Domain: wp-admin-audit
 * Domain Path: /languages
 *
*/


/*
 WP Admin Audit is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 WP Admin Audit is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with WP Admin Audit. If not, see <http://www.gnu.org/licenses/>.
*/


if ( ! class_exists( 'WpAdminAudit' ) ) {
    class WpAdminAudit
    {
        public $version = '1.2.9';
        protected $eventListener;

        public function __construct()
        {
            self::errorLog('construct');
            if (self::onFrontend()) {
                add_action('wp_loaded', array($this, 'setupWADA'), 0);
            }else{
                add_action('plugins_loaded', array($this, 'setupWADA'), 8);
            }

            register_activation_hook(__FILE__, array($this, 'installWADA'));
            register_deactivation_hook(__FILE__, array($this, 'deactivateWADA'));
            add_filter('cron_schedules', array($this, 'addCronSchedules'));
        }

        protected static function errorLog($msg){
            if(self::isHeartbeatRequest()) return;
            //$remoteAddress = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : '';
            //$remotePort = array_key_exists('REMOTE_PORT', $_SERVER) ? $_SERVER['REMOTE_PORT'] : '';
            //$reqTime = array_key_exists('REQUEST_TIME', $_SERVER) ? $_SERVER['REQUEST_TIME'] : '';
            //$requestId = sprintf("%08x", abs(crc32($remoteAddress . $reqTime . $remotePort)));
            //error_log($requestId."\t".$msg);
        }

        public static function getInstance() {
            static $instance = null;
            if (!$instance) {
                $instance = new self();
            }
            return $instance;
        }

        public function setupWADA(){
            self::errorLog('setupWADA');
            if(self::isHeartbeatRequest()){
                return; // do not set up in that scenario
            }

            // Find out what kind of fun WP was having
            //add_action ( 'shutdown', function() {
            //    self::errorLog('WP->shutdown actions: '.print_r($GLOBALS['wp_actions'], true));
            //} );

            if(!self::onFrontend() || (self::loadOnFrontend()) || (is_user_logged_in())){
                self::errorLog('Go ahead');
                $this->loadPluginDependencies();
                $this->setupHooksAndFilters();
                $this->completePendingUpdates();

                if(is_admin()){
                    $lastActivityWidget = new WADA_Widget_LastActivities();
                    $loginAttemptsWidget = new WADA_Widget_LoginAttempts();
                }

                if (did_action('init')){
                    $this->initWADA();
                }
            }else{
                self::errorLog('Skip WADA setup because Frontend: '.(self::onFrontend() ? 'y':'n').', Logged in: '.(is_user_logged_in() ? 'y':'n').', Rest API: '.(self::isRequestForRestAPI()?'y':'n'));
            }
        }

        public function loadOnFrontend(){ // the frontend activities that are (potentially) relevant for our sensors
            if(array_key_exists('login', $_REQUEST)) return true;
            if(array_key_exists('register', $_REQUEST)) return true;
            if(array_key_exists('reset_key', $_REQUEST)) return true;
            if(array_key_exists('reset_login', $_REQUEST)) return true;
            if(array_key_exists('wc_reset_password', $_REQUEST)) return true;
            return false;
        }

        public function loadPluginDependencies(){
            require_once 'classes/Constants.php';
            require_once 'classes/Setup.php';
            require_once 'classes/Application/BackendSum.php';
            require_once 'classes/Application/BackendWoosl.php';
            require_once 'classes/Application/Database.php';
            require_once 'classes/Application/EventListener.php';
            require_once 'classes/Application/Extensions.php';
            require_once 'classes/Application/Log.php';
            require_once 'classes/Application/Maintenance.php';
            require_once 'classes/Application/Router.php';
            require_once 'classes/Application/Settings.php';
            require_once 'classes/Application/Updater.php';
            require_once 'classes/Application/Version.php';
            require_once 'classes/Utils/CommentUtils.php';
            require_once 'classes/Utils/CompUtils.php';
            require_once 'classes/Utils/DateUtils.php';
            require_once 'classes/Utils/FileUtils.php';
            require_once 'classes/Utils/PHPUtils.php';
            require_once 'classes/Utils/PluginUtils.php';
            require_once 'classes/Utils/PostUtils.php';
            require_once 'classes/Utils/ScriptUtils.php';
            require_once 'classes/Utils/TermUtils.php';
            require_once 'classes/Utils/UserUtils.php';
            require_once 'classes/Utils/UpgraderUtils.php';
            if(is_admin()){
                require_once 'classes/Application/Menu.php';
                require_once 'classes/Utils/HtmlUtils.php';
            }
        }

        public function setupHooksAndFilters(){
            if(count($_POST)>0) {
                WADA_Log::debug('WADA Setup POST: ' . print_r($_POST, true));
            }
            add_action( 'init', array( $this, 'initWADA' ), 5 );
            add_action( 'admin_menu', array( 'WADA_Menu', 'adminMenu' ) );
            add_action( 'admin_enqueue_scripts', array( 'WADA_Menu', 'adminAssets' ) );

            // Schedules
            add_action( 'wp_admin_audit_maintenance', array( 'WADA_Maintenance', 'scheduledRun' ) );
            add_action( 'wp_admin_audit_queue_work', array( 'WADA_Notification_Queue', 'workOnQueue' ), 10 );
            add_action( 'wp_admin_audit_queue_work', array( 'WADA_Replicator_Worker', 'workOnPendingReplications' ), 9 ); // same hook, higher priority for replications

            // Internal hooks
            add_action( 'wp_admin_audit_new_event', array( 'WADA_Notification_Queue', 'matchAndQueueEvent' ), 10, 2 );

            WADA_BackendSum::autoResetExtensionCacheOnPluginLifecycleActivities();
            add_filter('plugins_api', array('WADA_BackendSum', 'injectPluginInfos'), 25, 3);

            // Ajax
            WADA_Router::setupAjaxHooks();
        }

        public function completePendingUpdates(){
            require_once 'classes/Setup.php';
            $setup = new WADA_Setup();
            if($setup->isDatabaseUpdateNeeded()){
                WADA_Log::info('completePendingUpdates');
                $setup->installOrUpdate();
            }
        }

        public function addCronSchedules($schedules){
            $schedules['15min'] = array(
                'interval' => 900,
                'display'  => __('Every 15 minutes', 'wp-admin-audit'),
            );
            $schedules['5min'] = array(
                'interval' => 300,
                'display'  => __('Every 5 minutes', 'wp-admin-audit'),
            );
            $schedules['1min']  = array(
                'interval' => 60,
                'display'  => __('Every minute', 'wp-admin-audit'),
            );
            return $schedules;
        }

        function autoload($className){
            $inclPath = null;
            if(strpos($className , 'WADA_Layout_' ) === 0){
                $namePart = substr($className, strlen('WADA_Layout_'));
                $inclPath = 'classes/Views/Layouts/'.$namePart.'.php';
            }elseif(strpos($className , 'WADA_Widget_' ) === 0) {
                $namePart = substr($className, strlen('WADA_Widget_'));
                $inclPath = 'classes/Views/Widgets/' . $namePart . '.php';
            }elseif($className === 'WADA_HtmlUtils') {
                $inclPath = 'classes/Utils/HtmlUtils.php';
            }else{
                $folders = array('Model', 'Sensor', 'View', 'Notification', 'Replicator');
                if(strpos($className, 'WADA_') === 0) {
                    foreach($folders as $folder) {
                        if(strpos($className, 'WADA_' . $folder . '_') === 0) {
                            $namePart = substr($className, strlen('WADA_' . $folder . '_'));
                            $inclPath = 'classes/' . $folder . 's/' . $namePart . '.php';
                            //self::errorLog('WADA->autoload ' . $folder . ' ' . $namePart . ' via ' . $inclPath);
                            break; // no need to look further
                        }
                    }
                }
            }
            if($inclPath){
                //error_log('WADA autoload '.$className .' at '.$inclPath);
                if(is_file(__DIR__.'/'.$inclPath)){
                    require_once __DIR__.'/'.$inclPath;
                }
            }
        }

        public function initWADA(){
            self::errorLog('initWADA');
            do_action( 'wp_admin_audit_loaded_pre_sensors', $this );
            //WADA_Log::info('initWADA hey hey');
            if(class_exists('WADA_EventListener')) {
                $eventListener = new WADA_EventListener();
                $this->eventListener = $eventListener;
                $this->eventListener->startListening();
            }

            // Done initializing, tell whoever is interested
            do_action( 'wp_admin_audit_loaded_post_sensors', $this );
        }

        public function installWADA(){
            require_once 'classes/Setup.php';
            $setup = new WADA_Setup();
            $setup->installOrUpdate();
        }

        public function deactivateWADA(){ // we do some cleanup or notifications if needed
            $setup = new WADA_Setup();
            $setup->unscheduleEvents();
            // TODO ONCE WE HAVE REPLICATION MAKE SURE THE PLUGIN DEACTIVATION EVENT GETS SENT
        }

        public static function onFrontend(){
            $onLoginScreen = parse_url(site_url('wp-login.php'), PHP_URL_PATH) === parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $isAdmin = is_admin();
            $restApi = self::isRequestForRestAPI();
            $isCron = wp_doing_cron();
            $isWPCli = (defined('WP_CLI') && WP_CLI);
            if($isAdmin || $restApi || $onLoginScreen || $isCron || $isWPCli){
                return false;
            }
            return true;
        }

        public static function isRequestForRestAPI(){
            $isWpJsonV2 = false;
            $isRestUrlPath = false;
            $isRestRoute = isset($_GET['rest_route']);
            if (!empty($_SERVER['REQUEST_URI'])) {
                $isWpJsonV2 = (array_key_exists('REQUEST_URI', $_SERVER) && (strpos($_SERVER['REQUEST_URI'], 'wp-json/wp/v2') !== false));
                $restUrlPath = trim(parse_url(home_url('/wp-json/'), PHP_URL_PATH), '/');
                $requestUrl = trim($_SERVER['REQUEST_URI'], '/');
                $isRestUrlPath = (strpos($requestUrl, $restUrlPath) === 0);
            }
            return ($isWpJsonV2 || $isRestUrlPath || $isRestRoute);
        }

        public static function isHeartbeatRequest(){
            if(array_key_exists('action', $_POST) && $_POST['action'] === 'heartbeat'){
                return true;
            }

            /*  */

            return false;
        }

    } // class end

    $adminAudit = WpAdminAudit::getInstance(); // init and run

    spl_autoload_register(array($adminAudit, 'autoload')); // register autoloader

    /*  */

}