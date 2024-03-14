<?php

/**
 * Prepares the plugin data.
 * 
 * @since       1.0.0
 * @package     Aarambha_Demo_Sites
 * @subpackage  Aarambha_Demo_Sites/Inc/Core/UI
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}


/**
 * Class:: Aarambha_DS_Plugins
 * 
 * Plugin installer.
 */

class Aarambha_DS_Plugins
{
    /**
     * Single class instance.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var object
     */
    private static $instance = null;

    /**
     * Free & Pro Plugins.
     * 
     * @since 1.0.0
     * @access private
     * 
     * @var array
     */
    private $plugins = [];

    /**
     * Ensures only one instance of this class is available.
     * 
     * 
     * @version 1.0.0
     * @since 1.0.0
     * 
     * @return object Aarambha_DS_Ajax
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::$instance->actions();
        }

        return self::$instance;
    }

    /**
     * A dummy constructor to prevent this class from being loaded more than once.
     *
     * @see Aarambha_DS_Ajax::getInstance()
     *
     * @since 1.0.0
     * @access private
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        /* We do nothing here! */
    }

    /**
     * You cannot clone this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'aarambha-demo-sites'),
            '1.0.0'
        );
    }

    /**
     * You cannot unserialize instances of this class.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __wakeup()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cheatin&#8217; huh?', 'aarambha-demo-sites'),
            '1.0.0'
        );
    }

    /**
     * 
     */
    public function actions()
    {
        // Hook into plugins api
        add_filter('plugins_api', [$this, 'pluginsApi'], 10, 3);
    }

    /**
     * Sets the plugins.
     * 
     * @param array $demo Demo retrieved from the API.
     * 
     * @return Aarambha_DS_Plugins
     */
    public function runtime($demo)
    {
        $plugins = $demo['plugins'];
        $this->plugins = $plugins;

        $demo_name = $demo['slug'];

        $transient = get_site_transient('aarambha_ds_plugins');

        if (!$transient) {
            $transient = new stdClass();
            $transient->plugins = [];
        }

        if (isset($plugins['pro'])) {

            $proPlugins = $plugins['pro'];

            $pluginsArr = [];

            foreach ($proPlugins as $plugin) {
                $pluginCoreFile = $plugin['coreFile'];

                $slug = $plugin['plugin'];
                $file = $plugin['plugin_file'];

                if (!in_array($pluginCoreFile, $plugins)) {

                    $pluginObj = new stdClass();
                    $pluginObj->name = $plugin['name'];
                    $pluginObj->version = $plugin['version'];
                    $pluginObj->slug = $slug;
                    $pluginObj->plugin = $pluginCoreFile;
                    $pluginObj->fileName = $file;
                    $pluginObj->demo = $demo_name;
                    $pluginObj->url = $demo['preview'];
                    $pluginObj->download_link = Aarambha_DS()->api()->deferredDownload(
                        compact('slug', 'demo_name')
                    );

                    array_push($pluginsArr, $pluginObj);
                }
            }

            $transient->plugins = $pluginsArr;
        }

        set_site_transient('aarambha_ds_plugins', $transient, WEEK_IN_SECONDS);

        return $this;
    }

    /**
     * Useful for injection.
     */
    public function inject()
    {
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
    }


    /**
     * Inject the installer.
     */
    public function injectInstaller()
    {
        if (!class_exists('WP_Upgrader', false)) {
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }

        if (!function_exists('plugins_api')) {
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }
    }

    /**
     * Check if plugin is installed.
     * 
     * @param string $pluginFile
     * 
     * @return bool.
     */
    public function isInstalled($pluginFile = '')
    {
        if ('' === $pluginFile) {
            return false;
        }

        return file_exists(WP_PLUGIN_DIR . "/{$pluginFile}");
    }

    /**
     * Is plugin active check.
     * 
     * @param string $pluginFile
     * 
     * @return bool.
     */
    public function isActive($pluginFile)
    {
        if (empty($pluginFile)) {
            return false;
        }

        $this->inject();

        return is_plugin_active($pluginFile);
    }

    /**
     * Prepare the plugins.
     * 
     * return $this
     */
    public function html()
    {
        $free = (isset($this->plugins['free'])) ? $this->plugins['free'] : [];
        $pro  = (isset($this->plugins['pro'])) ? $this->plugins['pro'] : [];

        $plugins = array_merge($free, $pro);
        $total = count($plugins);

        $activeLists = [];

        foreach ($plugins as $plugin) {

            if ($this->isActive($plugin['coreFile'])) {
                array_push($activeLists, $plugin['coreFile']);
            }
        }

        if (count($activeLists) === $total) {
            $status['step'] = 'import';
        } else {
            $status['step'] = 'install-plugin';
        }

        ob_start();

        if (count($activeLists) !== $total) {
            Aarambha_DS()->view('plugins-list', $this->plugins);
        }

        $content = ob_get_clean();
        $status['html'] = $content;

        return $status;
    }

    /**
     * Prepare the pro plugins.
     */
    public function prepare($plugin)
    {
    }

    /**
     * Install the plugin.
     * 
     * @param string $plugin 'plugin slug'
     * 
     * @return bool
     */
    public function install($plugin)
    {
    }

    /**
     * Activate the plugin.
     * 
     * @param string $plugin 'Plugin File'
     * 
     * @return bool
     */
    public function activate($plugin)
    {
        $this->inject();

        if (!$this->isInstalled($plugin)) {
            return false;
        }

        $status = activate_plugin($plugin);

        if (!is_wp_error($status) || null !== $status) {
            return true;
        }

        return false;
    }


    /**
     * Install Plugin with AJAX
     * 
     * @see wp_ajax_install_plugin() from 'wp-admin/includes/ajax-actions.php'
     * 
     * Modified it to match our need.
     * 
     * @return array
     */
    public function ajaxInstall($slug)
    {

        $status = [];

        if (empty($slug)) {
            $status['success'] = false;
            return $status;
        }

        $this->injectInstaller();

        $api = plugins_api(
            'plugin_information',
            array(
                'slug'   => sanitize_key(wp_unslash($slug)),
                'fields' => array(
                    'sections' => false,
                ),
            )
        );

        if (is_wp_error($api)) {

            $status['success'] = false;
            $status['errorMessage'] = $api->get_error_message();

            return  $status;
        }

        $status['pluginName'] = $api->name;

        $skin     = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader($skin);

        $result   = $upgrader->install($api->download_link);

        if (defined('WP_DEBUG') && WP_DEBUG) {
            $status['success'] = false;

            $status['debug'] = $skin->get_upgrade_messages();
        }

        if (is_wp_error($result)) {
            $status['success'] = false;


            $status['errorCode']    = $result->get_error_code();
            $status['errorMessage'] = $result->get_error_message();

            return $status;
        } elseif (is_wp_error($skin->result)) {
            $status['success'] = false;


            $status['errorCode']    = $skin->result->get_error_code();
            $status['errorMessage'] = $skin->result->get_error_message();
            return $status;
        } elseif ($skin->get_errors()->has_errors()) {
            $status['success'] = false;

            $status['errorMessage'] = $skin->get_error_messages();
            return $status;
        } elseif (is_null($result)) {
            global $wp_filesystem;

            $status['success'] = false;

            $status['errorCode']    = 'unable_to_connect_to_filesystem';
            $status['errorMessage'] = __('Unable to connect to the filesystem. Please confirm your credentials.', 'aarambha-demo-sites');

            // Pass through the error from WP_Filesystem if one was raised.
            if ($wp_filesystem instanceof WP_Filesystem_Base && is_wp_error($wp_filesystem->errors) && $wp_filesystem->errors->has_errors()) {
                $status['errorMessage'] = esc_html($wp_filesystem->errors->get_error_message());
            }

            return $status;
        }

        $status['success'] = true;

        return $status;
    }

    /**
     * Modify the API response to include our plugin installer.
     */
    public function pluginsApi($response, $action, $args)
    {
        $proPlugins = get_site_transient('aarambha_ds_plugins');
        $theme = aarambha_ds_get_theme();

        if ('plugin_information' === $action && isset($args->slug)) {
            $slug = $args->slug;

            foreach ($proPlugins->plugins as $plugin) {
                $demo = $plugin->demo;

                if ($plugin->slug === $args->slug) {
                    $response                 = new stdClass();
                    $response->id             = str_replace('-', '_', $plugin->slug);
                    $response->slug           = $plugin->slug;
                    $response->plugin_name    = $plugin->name;
                    $response->name           = $plugin->name;
                    $response->new_version    = $plugin->version;
                    $response->download_link  = Aarambha_DS()->api()->download(
                        compact('theme', 'slug', 'demo')
                    );
                }
            }
        }

        return $response;
    }
}
