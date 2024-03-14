<?php
/**
 * Plugin Name: GoDaddy Payments
 * Plugin URI: https://payments.godaddy.com/
 * Description: Securely accept credit/debit cards in your checkout, and keep more of your money with the industry's lowest fees—just 2.3% + 30¢ per online transaction. Get paid fast with deposits as soon as the next day.
 * Author: GoDaddy
 * Author URI: https://www.godaddy.com/
 * Version: 1.7.3
 * Text Domain: godaddy-payments
 * Domain Path: /i18n/languages/
 * Requires at least: 5.6
 * Requires PHP: 7.4
 * License: GPL-2.0
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html.
 *
 * Copyright © 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 */
defined('ABSPATH') or exit;

/**
 * The plugin loader class.
 *
 * @since 1.0.0
 */
class GD_Poynt_For_WooCommerce_Loader
{
    /** minimum PHP version required by this plugin */
    const MINIMUM_PHP_VERSION = '7.4';

    /** minimum WordPress version required by this plugin */
    const MINIMUM_WP_VERSION = '5.6';

    /** minimum WooCommerce version required by this plugin */
    const MINIMUM_WC_VERSION = '4.0';

    /** SkyVerge plugin framework version used by this plugin */
    const FRAMEWORK_VERSION = '5.12.1';

    /** the plugin name, for displaying notices */
    const PLUGIN_NAME = 'Poynt &mdash; a GoDaddy Brand for WooCommerce';

    /** @var GD_Poynt_For_WooCommerce_Loader single instance of this class */
    private static $instance;

    /** @var array the admin notices to add */
    private $notices = [];

    /**
     * Constructs the loader.
     *
     * @since 1.0.0
     */
    protected function __construct()
    {
        register_activation_hook(__FILE__, [$this, 'doActivationCheck']);

        add_action('admin_init', [$this, 'checkEnvironment']);
        add_action('admin_init', [$this, 'addPluginNotices']);

        add_action('admin_notices', [$this, 'outputAdminNotices'], 15);

        add_filter('extra_plugin_headers', [$this, 'addDocumentationHeader']);

        // if the environment check fails, initialize the plugin
        if ($this->isEnvironmentCompatible()) {
            add_action('plugins_loaded', [$this, 'initPlugin']);
        }
    }

    /**
     * Cloning instances is forbidden due to singleton pattern.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, sprintf('You cannot clone instances of %s.', get_class($this)), '1.0.0');
    }

    /**
     * Unserializing instances is forbidden due to singleton pattern.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, sprintf('You cannot unserialize instances of %s.', get_class($this)), '1.0.0');
    }

    /**
     * Initializes the plugin.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function initPlugin()
    {
        if (! $this->isPluginsCompatible()) {
            return;
        }

        $this->loadFramework();

        require_once plugin_dir_path(__FILE__).'vendor/autoload.php';
        require_once plugin_dir_path(__FILE__).'src/Functions.php';

        poynt_for_woocommerce();
    }

    /**
     * Loads the base framework classes.
     *
     * @since 1.0.0
     */
    private function loadFramework()
    {
        if (! class_exists('\\SkyVerge\\WooCommerce\\PluginFramework\\'.$this->getFrameworkVersionNamespace().'\\SV_WC_Plugin')) {
            require_once plugin_dir_path(__FILE__).'vendor/skyverge/wc-plugin-framework/woocommerce/class-sv-wc-plugin.php';
        }

        if (! class_exists('\\SkyVerge\\WooCommerce\\PluginFramework\\'.$this->getFrameworkVersionNamespace().'\\SV_WC_Payment_Gateway_Plugin')) {
            require_once plugin_dir_path(__FILE__).'vendor/skyverge/wc-plugin-framework/woocommerce/payment-gateway/class-sv-wc-payment-gateway-plugin.php';
        }
    }

    /**
     * Gets the framework version in namespace form.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getFrameworkVersionNamespace()
    {
        return 'v'.str_replace('.', '_', $this->getFrameworkVersion());
    }

    /**
     * Gets the framework version used by this plugin.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getFrameworkVersion()
    {
        return self::FRAMEWORK_VERSION;
    }

    /**
     * Checks the server environment and other factors and deactivates plugins as necessary.
     *
     * Based on {@link http://wptavern.com/how-to-prevent-wordpress-plugins-from-activating-on-sites-with-incompatible-hosting-environments}
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function doActivationCheck()
    {
        if (! $this->isEnvironmentCompatible()) {
            $this->deactivatePlugin();

            wp_die(self::PLUGIN_NAME.' could not be activated. '.$this->getEnvironmentIncompatibleMessage());
        }
    }

    /**
     * Checks the environment on loading WordPress, just in case the environment changes after activation.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function checkEnvironment()
    {
        if (! $this->isEnvironmentCompatible() && is_plugin_active(plugin_basename(__FILE__))) {
            $this->deactivatePlugin();

            $this->addAdminNotice('bad_environment', 'error', self::PLUGIN_NAME.' has been deactivated. '.$this->getEnvironmentIncompatibleMessage());
        }
    }

    /**
     * Determines if the required plugins are compatible.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    private function isPluginsCompatible()
    {
        return $this->isWordPressCompatible() && $this->isWooCommerceCompatible();
    }

    /**
     * Determines if the WordPress compatible.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    private function isWordPressCompatible()
    {
        return version_compare(get_bloginfo('version'), self::MINIMUM_WP_VERSION, '>=');
    }

    /**
     * Determines if the WooCommerce compatible.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    private function isWooCommerceCompatible()
    {
        return defined('WC_VERSION') && version_compare(WC_VERSION, self::MINIMUM_WC_VERSION, '>=');
    }

    /**
     * Determines if the server environment is compatible with this plugin.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    private function isEnvironmentCompatible()
    {
        return version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=');
    }

    /**
     * Gets the message for display when the environment is incompatible with this plugin.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function getEnvironmentIncompatibleMessage()
    {
        return sprintf('The minimum PHP version required for this plugin is %1$s. You are running %2$s.', self::MINIMUM_PHP_VERSION, PHP_VERSION);
    }

    /**
     * Deactivates the plugin.
     *
     * @internal
     *
     * @since 1.0.0
     */
    protected function deactivatePlugin()
    {
        deactivate_plugins(plugin_basename(__FILE__));

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    }

    /**
     * Adds notices for out-of-date WordPress and/or WooCommerce versions.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function addPluginNotices()
    {
        if (! $this->isWordPressCompatible()) {
            $this->addAdminNotice('update_wordpress', 'error', sprintf(
                '%s requires WordPress version %s or higher. Please %supdate WordPress &raquo;%s',
                '<strong>'.self::PLUGIN_NAME.'</strong>',
                self::MINIMUM_WP_VERSION,
                '<a href="'.esc_url(admin_url('update-core.php')).'">', '</a>'
            ));
        }

        if (! $this->isWooCommerceCompatible()) {
            $this->addAdminNotice('update_woocommerce', 'error', sprintf(
                '%1$s requires WooCommerce version %2$s or higher. Please %3$supdate WooCommerce%4$s to the latest version, or %5$sdownload the minimum required version &raquo;%6$s',
                '<strong>'.self::PLUGIN_NAME.'</strong>',
                self::MINIMUM_WC_VERSION,
                '<a href="'.esc_url(admin_url('update-core.php')).'">', '</a>',
                '<a href="'.esc_url('https://downloads.wordpress.org/plugin/woocommerce.'.self::MINIMUM_WC_VERSION.'.zip').'">', '</a>'
            ));
        }
    }

    /**
     * Adds an admin notice to be displayed.
     *
     * @since 1.0.0
     *
     * @param string $slug the slug for the notice
     * @param string $class the css class for the notice
     * @param string $message the notice message
     */
    private function addAdminNotice($slug, $class, $message)
    {
        $this->notices[$slug] = [
            'class'   => $class,
            'message' => $message,
        ];
    }

    /**
     * Displays any admin notices added with {@see \GD_Poynt_For_WooCommerce_Loader::addAdminNotice()}.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function outputAdminNotices()
    {
        foreach ((array) $this->notices as $notice_key => $notice) :

            ?>
            <div class="<?php echo esc_attr($notice['class']); ?>">
                <p><?php echo wp_kses($notice['message'], ['a' => ['href' => []]]); ?></p>
            </div>
        <?php

        endforeach;
    }

    /**
     * Adds the Documentation URI header.
     *
     * @internal
     *
     * @since 1.0.0
     *
     * @param string[] $headers original headers
     * @return string[]
     */
    public function addDocumentationHeader($headers)
    {
        $headers[] = 'Documentation URI';

        return $headers;
    }

    /**
     * Gets the main loader instance.
     *
     * Ensures only one instance can ever be loaded.
     *
     * @since 1.0.0
     *
     * @return GD_Poynt_For_WooCommerce_Loader
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

// fire it up!
GD_Poynt_For_WooCommerce_Loader::getInstance();
