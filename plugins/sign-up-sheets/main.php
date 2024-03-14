<?php
/**
 * Main initialization class
 */

namespace FDSUS;

use FDSUS\Lib\Dls\Notice;
use FDSUS\Model\Settings;
use wpdb;
use FDSUS\Id as Id;
use FDSUS\Model\Data;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\DbUpdate;

/**
 * Autoloader
 *
 * @param string $className
 */
if (!function_exists('\FDSUS\fdsusAutoloader')):
    function fdsusAutoloader($className)
    {
        if (false !== strpos($className, __NAMESPACE__) && !in_array($className, array(__NAMESPACE__ . '\Main'))) {
            $classesDir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
            $classFile = str_replace(__NAMESPACE__ . '/', '', str_replace('\\', '/', $className)) . '.php';
            $classFile = strtolower(preg_replace('/\B([A-Z])/', '-$1', $classFile)); // add hyphen before uppercase classes then convert to all lowercase
            require_once $classesDir . $classFile;
        }
    }

    spl_autoload_register('\FDSUS\fdsusAutoloader');
endif;

if (!class_exists('\FDSUS\Main')):

    class Main {

        /** @var wpdb */
        public $wpdb;

        /** @var Data */
        public $data;

        /** @var DbUpdate */
        private $dbUpdate;

        public $prefix;
        public $detailed_errors = false;

        public function __construct()
        {
            /**
             * Disables the migration process for the plugin
             *
             * @var bool
             *
             * @api
             * @since 2.2.7
             */
            if (!defined('FDSUS_DISABLE_MIGRATE_2_0_to_2_1')) {
                define('FDSUS_DISABLE_MIGRATE_2_0_to_2_1', false);
            }

            global $wpdb;
            Notice::instance(array('frontendFilter' => Id::PREFIX . '_notices'));
            Settings::instance();
            $this->wpdb = $wpdb;
            $this->data = new Data();
            $this->dbUpdate = new DbUpdate();
            new \FDSUS\Controller\Sheet();
            new \FDSUS\Controller\Task();
            new \FDSUS\Controller\Signup();
            new \FDSUS\Controller\Privacy();
            new \FDSUS\Controller\Ajax();
            new \FDSUS\Controller\Block(__DIR__);
            new \FDSUS\Controller\MailCustomization();
            new \FDSUS\Controller\Cache();
            new \FDSUS\Controller\Captcha();
            if (is_admin() || defined('FD_UNIT_TEST')) { // TODO keep?  or move all non-admin specific things like save actions into proper controller
                new \FDSUS\Controller\Admin();
                new \FDSUS\Controller\Admin\Dashboard();
                new \FDSUS\Controller\Admin\Settings();
                new \FDSUS\Controller\Admin\Help();
                new \FDSUS\Controller\Admin\ManageSignups();
                new \FDSUS\Controller\Admin\EditSheet();
                new \FDSUS\Controller\Admin\EditSignupPage();
                new \FDSUS\Controller\Admin\Export();
                new \FDSUS\Controller\Admin\SiteHealth();
            } else {
                new \FDSUS\Controller\Scode\SignUpForm();
                new \FDSUS\Controller\Scode\SignUpSheet();
                new \FDSUS\Controller\Scode\UserSignUps();
            }
            $this->prefix = Id::PREFIX;

            if (Id::DEBUG_DISPLAY || get_option('dls_sus_detailed_errors') === 'true') {
                $this->detailed_errors = true;
                $this->data->detailed_errors = true;
            }

            register_activation_hook(Settings::getCurrentPluginBasename(), array(&$this, 'activate'));
            register_deactivation_hook(Settings::getCurrentPluginBasename(), array(&$this, 'deactivate'));

            add_action('wp_enqueue_scripts', array(&$this, 'add_css_and_js_to_frontend'));
            add_action('init', array(&$this, 'setDefaultOptions'), 0);
            add_action('init', array(&$this, 'flushIfNeeded'), 0);
            add_action('admin_init', array(&$this, 'dupPluginVersionCheck'));

            add_filter('rewrite_rules_array', array(&$this, 'add_rewrite_rules'));
            add_filter('get_the_archive_title', array(&$this, 'modify_archive_title'));

            if (Id::IS_LOCAL) {
                add_filter('edd_sl_api_request_verify_ssl', '__return_false');
            }
        }

        /**
         * Set default option values
         */
        public function setDefaultOptions()
        {
            if (get_option('dls_sus_sheet_slug') === false) {
                update_option('dls_sus_sheet_slug', SheetModel::getBaseSlug());
            }

            $sheetSlug = get_option('dls_sus_sheet_slug');
            if (empty($sheetSlug)) {
                add_option('dls_sus_sheet_slug', 'sheet', null, 'yes');
            }

            if (get_option('dls_sus_hide_address') === false) {
                add_option('dls_sus_hide_address', 'true', null, 'yes');
            }

            /**
             * Action that runs during setDefaultOptions()
             */
            do_action('fdsus_set_default_options');
        }

        /**
         * Register plugin css and js files
         */
        function add_css_and_js_to_frontend()
        {
            wp_enqueue_script('jquery');

            if (Settings::isEmailValidationEnabled()) {
                wp_register_script(
                    Id::PREFIX . '-mailcheck',
                    esc_url(plugins_url('js/mailcheck.min.js', __FILE__)),
                    array(),
                    '1.1.2'
                );
            }

            wp_register_style(
                Id::PREFIX . '-style',
                plugins_url('css/style.css', __FILE__),
                array(),
                Id::version()
            );

            $mainSusDeps = array();

            if (Settings::isEmailValidationEnabled()) {
                $mainSusDeps[] = 'dlssus-mailcheck';
            }

            wp_register_script(
                'dlssus-js',
                plugins_url('js/dist/main.min.js', __FILE__),
                $mainSusDeps,
                Id::version()
            );

            $inlineScriptArray = array(
                'dlssus_validate_email' => array('disable' => !Settings::isEmailValidationEnabled()),
                'dls_sus_recaptcha_version' => esc_js(Settings::getRecaptchaVersion()), // TODO CAPTCHA - move to Captcha class
            );

            /**
             * Filter for wp_add_inline_script FDSUS constant array value
             *
             * @param array $inlineScriptArray
             * @since 2.2.12
             */
            $inlineScriptArray = apply_filters('fdsus_add_inline_script_array', $inlineScriptArray);

            wp_add_inline_script(
                'dlssus-js', 'const FDSUS = ' . json_encode($inlineScriptArray), 'before'
            );

            // Enqueue to sheet page
            if (get_post_type() == SheetModel::POST_TYPE) {
                wp_enqueue_script('jquery');
                wp_enqueue_style(Id::PREFIX . '-style');
                if (Settings::isEmailValidationEnabled()) {
                    wp_enqueue_script(Id::PREFIX . '-mailcheck');
                }
                wp_enqueue_script('dlssus-js');
            }
        }

        /**
         * Add rewrite for new query vars
         *
         * @param array $aRules
         *
         * @return array
         */
        function add_rewrite_rules($aRules)
        {
            $sheetSlug = SheetModel::getBaseSlug();
            $new1       = array($sheetSlug . '/(.+?)/(.+?)/?$' => 'index.php?dlssus_sheet=$matches[1]&dlssus_task=$matches[2]');
            $aRules     = $new1 + $aRules;

            return $aRules;
        }

        /**
         * Modify archive title
         *
         * @param $title
         *
         * @return mixed|string
         */
        public function modify_archive_title($title)
        {
            if (get_post_type() == SheetModel::POST_TYPE) {
                $title = str_replace('Archives: ', '', $title);
            }

            return $title;
        }

        /**
         * Duplicate plugin version check
         */
        public function dupPluginVersionCheck()
        {
            if ((Id::isPro() && is_plugin_active(Id::FREE_PLUGIN_BASENAME))
                || (!Id::isPro() && is_plugin_active(Id::PRO_PLUGIN_BASENAME))
            ) {
                $pluginFileRequire = ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR
                    . 'plugin.php';
                if (!function_exists('get_plugins')) {
                    require_once($pluginFileRequire);
                }

                $plugins = get_plugins();

                if ($plugins[Id::FREE_PLUGIN_BASENAME]['Version']
                    <> $plugins[Id::PRO_PLUGIN_BASENAME]['Version']
                ) {
                    if (!function_exists('get_plugins')) {
                        require_once($pluginFileRequire);
                    }
                    $plugins = get_plugins();
                    $versionFree = $plugins[Id::FREE_PLUGIN_BASENAME]['Version'];
                    $versionPro = $plugins[Id::PRO_PLUGIN_BASENAME]['Version'];
                    $allowedHtml = array(
                        'strong' => array(),
                    );

                    Notice::add(
                        'error', sprintf(
                            wp_kses(
                            /* translators: %1$s is replaced with the pro plugin version number, %2$s is replaced with the free plugin version number */
                                __(
                                    'The <strong>Sign-up Sheets Pro</strong> plugin version (%1$s) does not match the version number of the main <strong>Sign-up Sheets</strong> plugin (%2$s).  Please update so both version numbers match to prevent possible conflicts.',
                                    'fdsus'
                                ),
                                $allowedHtml
                            ),
                            esc_html($versionPro), esc_html($versionFree)
                        )
                    );
                }
            }
        }

        /**
         * Check if we need to flush rewrites (like if slug was changed in Settings)
         */
        public function flushIfNeeded()
        {
            if (get_transient(Id::PREFIX . '_flush_rewrite_rules')) {
                flush_rewrite_rules();
                delete_transient(Id::PREFIX . '_flush_rewrite_rules');
            }
        }

        /**
         * Activate the plugin
         */
        public function activate()
        {
            $this->dbUpdate->check();
            set_transient(Id::PREFIX . '_flush_rewrite_rules', true);

            // Add custom role and capability
            add_role('signup_sheet_manager', 'Sign-up Sheet Manager');
            $this->data->set_capabilities();

            /**
             * Action that runs on plugin activation
             */
            do_action('fdsus_activate');
        }

        /**
         * Deactivate the plugin
         */
        public function deactivate()
        {
            set_transient(Id::PREFIX . '_flush_rewrite_rules', true);

            // Remove custom role and capability
            $role = get_role('signup_sheet_manager');
            if (is_object($role)) {
                $role->remove_cap('read');
                remove_role('signup_sheet_manager');
            }

            $this->data->remove_capabilities();

            // Crons
            wp_clear_scheduled_hook(Id::PREFIX . '_dbupdate_action');

            /**
             * Action that runs on plugin activation
             */
            do_action('fdsus_deactivate');
        }

    }

    $fdsus = new Main();
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'template-tags.php';
    is_file(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'pro.php') and include 'pro.php';

endif;
