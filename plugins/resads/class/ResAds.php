<?php
if(!defined('ABSPATH'))  die('You are not allowed to call this page directly.');
/**
 * ResaAds
 */
if(!class_exists('ResAds'))
{
    class ResAds
    {
        /**
         * is cache plugin activate?
         * @var boolean
         */
        private static $is_cache_plugin_activate = false;
        /**
         * Construct
         */
        public function __construct() 
        {
            if(is_admin())
            {
                add_action('admin_init', array(&$this, 'admin_init'));

                add_action('admin_menu', array(&$this, 'admin_menu'));
                
                add_filter("mce_external_plugins", array(&$this, 'enqueue_tinymce_plugin_scripts'));
                
                add_filter('mce_buttons', array(&$this, 'register_buttons_mce_editor'));
            }
            else
            {
                add_action('init', array(&$this, 'add_frontend_scripts'));
            }
            
            add_action('plugins_loaded', array(&$this, 'resads_load_textdomain'));
            
            add_action('request', array(&$this, 'request'));
        }
        /**
         * Plugin activate
         */
        public static function activate()
        {
            $resads_version = get_option(RESADS_VERSION_KEY, 0);
            if($resads_version != RESADS_VERSION_NUM)
            {
                self::create_database_tables();
                
                if(!$resads_version || $resads_version == 0)
                    self::activate_inserts();
                else
                    self::update_inserts();

                self::set_options();
            }
        }
        /**
         * Plugin deactive
         */
        public static function deactivate()
        {
            // do nothing
        }
        /**
         * Plugin uninstall
         */
        public static function delete()
        {
            self::delete_database_tables();
            
            self::delete_options();
        }
        /**
         * Plugin Upgrade
         */
        public function upgrade()
        {
            self::activate();
        }
        /**
         * Create DB-Tables
         */
        private static function create_database_tables()
        {
            require_once RESADS_CLASS_DIR . '/AdManagement.php';
            require_once RESADS_CLASS_DIR . '/AdSpot.php';
            require_once RESADS_CLASS_DIR . '/Resolution.php';
            require_once RESADS_CLASS_DIR . '/AdStat.php';

            $AdManagement = new ResAds_AdManagement_DB();
            $AdManagement->create_database_table();

            $AdSpots = new ResAds_AdSpot_DB();
            $AdSpots->create_database_table();

            $Resolutions = new ResAds_Resolution_DB();
            $Resolutions->create_database_table();

            $AdStat = new ResAds_AdStat_DB();
            $AdStat->create_database_table();
        }
        /**
         * Delete als DB-Tables
         */
        private static function delete_database_tables()
        {
            require_once RESADS_CLASS_DIR . '/AdManagement.php';
            require_once RESADS_CLASS_DIR . '/AdSpot.php';
            require_once RESADS_CLASS_DIR . '/Resolution.php';
            require_once RESADS_CLASS_DIR . '/AdStat.php';
            
            $AdManagement = new ResAds_AdManagement_DB();
            $AdManagement->delete_database_table();
            
            $AdSpots = new ResAds_AdSpot_DB();
            $AdSpots->delete_database_table();
            
            $Resolutions = new ResAds_Resolution_DB();
            $Resolutions->delete_database_table();
            
            $AdStat = new ResAds_AdStat_DB();
            $AdStat->delete_database_table();
        }
        /**
         * Do inserts if plugin activated
         */
        public static function activate_inserts()
        {
            require_once RESADS_CLASS_DIR . '/Resolution.php';
            $Resolutions = new ResAds_Resolution_DB();      
            $Resolutions->activate_inserts();
        }
        /**
         * Do Updates if update
         */
        public static function update_inserts()
        {
            require_once RESADS_CLASS_DIR . '/Resolution.php';
            $Resolutions = new ResAds_Resolution_DB();      
            $Resolutions->update_inserts();
        }
        /**
         * Save Options
         */
        private static function set_options()
        {
            update_option(RESADS_VERSION_KEY, RESADS_VERSION_NUM);
        }
        /**
         * Delete Options
         */
        private static function delete_options()
        {
            delete_option(RESADS_VERSION_KEY);
        }
        /**
         * Admin Einstellungen
         */
        public function admin_init()
        {
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('wpdialogs');
            wp_localize_script('wpdialogs', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
            
            wp_enqueue_style('wp-jquery-ui-dialog');
        }
        /**
         * Set Language Path
         */
        public function resads_load_textdomain()
        {
            load_plugin_textdomain(RESADS_ADMIN_TEXTDOMAIN, false, RESADS_PLUGIN_NAME . '/languages/');
        }
        /**
         * Admin Menu
         */
        public function admin_menu()
        {
            if(current_user_can(RESADS_PERMISSION_ROLE))
            {
                require_once RESADS_CLASS_DIR . '/Dashboard.php';
                $Dashboard = new ResAds_Dashboard();

                require_once RESADS_CLASS_DIR . '/AdManagement.php';
                $AdManagement = new ResAds_AdManagement_Admin();

                require_once RESADS_CLASS_DIR . '/AdSpot.php';
                $AdSpots = new ResAds_AdSpot_Admin();
                
                require_once RESADS_CLASS_DIR . '/Settings.php';
                $Settings = new ResAds_Settings();               
            }
        }
        /**
         * Add Frontend Scripts
         */
        public function add_frontend_scripts()
        {
            wp_enqueue_script('resads-frontend', RESADS_JS_DIR . '/frontend.min.js', array('jquery'), false, 'true');
            wp_localize_script('resads-frontend', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
            wp_enqueue_style('resads-main', RESADS_CSS_DIR . '/main.min.css');
            
            if(self::is_cache_plugin_activate())
            {
                wp_localize_script('resads-frontend', 'is_cache_plugin_activate', array('is_active' => true));
            }
        }
        /**
         * Add Request Action ( Masked Links )
         * @param object $request
         * @return object
         */
        public function request($request)
        {
            if(isset($_GET['resads']) && is_numeric($_GET['resads']))
            {
                $ad_id = $_GET['resads'];
            }
            elseif(isset($_SERVER['REQUEST_URI']) && trim($_SERVER['REQUEST_URI']) != '')
            {
                $uri = $_SERVER['REQUEST_URI'];
                if(stripos($uri, 'resads') !== false)
                {
                    $uri_data = explode('/', $uri);
                    $index = array_search('resads', $uri_data);
                    if(isset($uri_data[$index + 1]) && is_numeric($uri_data[$index + 1]))
                    {
                        $ad_id = $uri_data[$index + 1];
                    }
                }
            }
            if(isset($ad_id) && is_numeric($ad_id) && file_exists(RESADS_CLASS_DIR . '/AdManagement.php'))
            {
                require_once RESADS_CLASS_DIR . '/AdManagement.php';
                $AdManagement = new ResAds_AdManagement();
                $AdManagement->forward($ad_id);
            }
            
            return $request;
        }
        /**
         * Add Action Links to plugin page
         * @param array $links
         * @param string $file
         * @return array
         */
        public function plugin_action_links($links, $file)
        {
            if($file == 'resads/resads.php') 
            {
                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=resads&action=delete_plugin">' . __('Delete') . '</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }
        /**
        * Checked if is an cache plugin activate
        * @return boolean
        */
       public static function is_cache_plugin_activate()
       {
           if(self::$is_cache_plugin_activate)
           {
               return true;
           }
           
           include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

           $cache_plugins = array(
               'comet-cache/comet-cache.php',
               'wp-super-cache/wp-cache.php',
               'wp-rocket/wp-rocket.php',
               'w3-total-cache/w3-total-cache.php',
               'wp-fastest-cache/wpFastestCache.php',
               'zencache/zencache.php',
               'cachify/cachify.php',
               'hyper-cache/plugin.php'
           );

           foreach($cache_plugins as $cache_plugin)
           {
               if(is_plugin_active($cache_plugin))
               {
                   self::$is_cache_plugin_activate = true;
                   return true;
               }
           }
       }
       /**
        * Register Button to MCE Editor
        * @param array $buttons
        * @return array
        */
       public function register_buttons_mce_editor($buttons)
       {
           array_push($buttons, 'resads');
           return $buttons;
       }
       /**
        * Enqueue TinyMCE Plugin Scripts
        * @param array $plugin_array
        * @return array
        */
        public function enqueue_tinymce_plugin_scripts($plugin_array)
        {
            if(defined('RESADS_JS_DIR'))
            {
                $plugin_array["resads_button_plugin"] =  RESADS_JS_DIR . '/tinymce/tinymce.min.js';
            }
            return $plugin_array;
        }
    }
}
?>
