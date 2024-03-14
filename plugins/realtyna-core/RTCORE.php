<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('RTCORE')):

    /**
     * Main Realtyna Core Class.
     *
     * @class RTCORE
     * @version	1.0.0
     */
    final class RTCORE
    {
        /**
         * RTCORE version.
         *
         * @var string
         */
        public $version = '1.3.0';

        /**
         * The single instance of the class.
         *
         * @var RTCORE
         * @since 1.0.0
         */
        protected static $instance = null;

        /**
         * Main RTCORE Instance.
         *
         * Ensures only one instance of RTCORE is loaded or can be loaded.
         *
         * @since 1.0.0
         * @static
         * @see RTCORE()
         * @return RTCORE - Main instance.
         */
        public static function instance()
        {
            // Get an instance of Class
            if(is_null(self::$instance)) self::$instance = new self();

            // Return the instance
            return self::$instance;
        }

        /**
         * Cloning is forbidden.
         * @since 1.0.0
         */
        public function __clone()
        {
            _doing_it_wrong(__FUNCTION__, __('Cheating huh?', 'realtyna-core'), '1.0.0');
        }

        /**
         * Un-serializing instances of this class is forbidden.
         * @since 1.0.0
         */
        public function __wakeup()
        {
            _doing_it_wrong(__FUNCTION__, __('Cheating huh?', 'realtyna-core'), '1.0.0');
        }

        /**
         * RTCORE Constructor.
         */
        protected function __construct()
        {
            // Define Constants
            $this->define_constants();

            // Auto Loader
            spl_autoload_register(array($this, 'autoload'));

            // Initialize the RTCORE
            $this->init();
        }

        /**
         * Define RTCORE Constants.
         */
        private function define_constants()
        {
            // RTCORE Absolute Path
            if(!defined('RTCORE_ABSPATH')) define('RTCORE_ABSPATH', dirname(__FILE__));

            // RTCORE Directory Name
            if(!defined('RTCORE_DIRNAME')) define('RTCORE_DIRNAME', basename(RTCORE_ABSPATH));

            // RTCORE Plugin Base Name
            if(!defined('RTCORE_BASENAME')) define('RTCORE_BASENAME', plugin_basename(RTCORE_ABSPATH.'/realtyna-core.php')); // realtyna-core/realtyna-core.php

            // RTCORE Version
            if(!defined('RTCORE_VERSION')) define('RTCORE_VERSION', $this->version);

            // WordPress Upload Directory
            $upload_dir = wp_upload_dir();

            // RTCORE Logs Directory
            if(!defined('RTCORE_LOG_DIR')) define('RTCORE_LOG_DIR', $upload_dir['basedir'] . '/rtcore-logs/');
        }

        /**
         * Initialize the RTCORE
         */
        private function init()
        {
            // Plugin Activation / Deactivation / Uninstall
            RTCORE_Plugin_Hooks::instance();

            // Sidebar Generator
            $sidebar = new RTCORE_Sidebar_Generator();
            $sidebar->init();

            // Testimonial
            $testimonial = new RTCORE_Testimonial();
            $testimonial->init();

            // RTCORE Internationalization
            $i18n = new RTCORE_i18n();
            $i18n->init();

            // Redux Framework
            $redux = new RTCORE_Redux();
            $redux->init();

            // Elementor
            $elementor = new RTCORE_Elementor();
            $elementor->init();

            // Demo Importer
            $OCDI = new RTCORE_OCDI();
            $OCDI->init();

            // HTML
            $html = new RTCORE_Html();
            $html->init();
        }

        /**
         * Automatically load RTCORE classes whenever needed.
         * @param string $class_name
         * @return void
         */
        private function autoload($class_name)
        {
            $class_ex = explode('_', strtolower($class_name));

            // It's not a RTCORE Class
            if($class_ex[0] != 'rtcore') return;

            // Drop 'RTCORE'
            $class_path = array_slice($class_ex, 1);

            // Create Class File Path
            $file_path = RTCORE_ABSPATH . '/app/includes/' . implode('/', $class_path) . '.php';

            // We found the class!
            if(file_exists($file_path)) require_once $file_path;
        }

        /**
         * What type of request is this?
         *
         * @param  string $type admin, ajax, cron or frontend.
         * @return bool
         */
        public function is_request($type)
        {
            switch($type)
            {
                case 'admin':
                    return is_admin();
                case 'ajax':
                    return defined('DOING_AJAX');
                case 'cron':
                    return defined('DOING_CRON');
                case 'frontend':
                    return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
                default:
                    return false;
            }
        }
    }

endif;

/**
 * Main instance of RTCORE
 *
 * Returns the main instance of RTCORE to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return RTCORE
 */
function RTCORE()
{
    return RTCORE::instance();
}

// Init the Realtyna Core :)
RTCORE();