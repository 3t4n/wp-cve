<?php

namespace TotalContestVendors\TotalCore;

use TotalContestVendors\League\Container\Container;
use LogicException;
use TotalContestVendors\TotalCore\Contracts\Foundation\Plugin;
use TotalContestVendors\TotalCore\CronJobs\TrackEnvironment;
use TotalContestVendors\TotalCore\CronJobs\TrackEvents;
use TotalContestVendors\TotalCore\Filesystem\Local as LocalFilesystem;
use TotalContestVendors\TotalCore\Form;
use TotalContestVendors\TotalCore\Foundation\Environment;
use TotalContestVendors\TotalCore\Helpers\DateTime;
use TotalContestVendors\TotalCore\Helpers\Embed;
use TotalContestVendors\TotalCore\Helpers\Html;
use TotalContestVendors\TotalCore\Migrations\Manager;

/**
 * TotalCore Application
 * @package TotalCore
 * @since   1.0.0
 */
class Application
{
    /**
     * @var $this Application instance
     */
    protected static $instance = null;
    /**
     * @var Container
     */
    protected $container;
    /**
     * @var Plugin
     */
    protected $plugin;
    /**
     * @var bool
     */
    protected $booted = false;

    public function __construct($env = [])
    {
        if (self::$instance === null):
            self::$instance = $this;
            $this->container = new Container();
            $this->container->share('env', new Environment($env));
        else:
            throw new LogicException('TotalCore should be initialized only once. Please use getInstance method instead.');
        endif;
    }

    public static function get($component = null, array $args = [])
    {
        return self::getInstance()->container($component, $args);
    }

    /**
     * @param null $component
     * @param array $args
     *
     * @return Container|mixed
     */
    public function container($component = null, array $args = [])
    {
        return $component === null ? $this->container : $this->container->get($component, $args);
    }

    public static function getInstance()
    {
        return self::$instance ?: new self();
    }

    public function bootstrap(Plugin $plugin)
    {
        if (!$this->booted):
            // Register core providers
            $this->registerProviders();
            // Set the plugin
            $this->plugin = $plugin;
            // Pass application instance
            $this->plugin->setApplication($this);
            // Register plugin's providers
            $this->plugin->registerProviders();
            // Load text domain
            add_action('plugins_loaded', [$this->plugin, 'loadTextDomain']);
            // Register widgets
            add_action('widgets_init', [$this->plugin, 'registerWidgets']);
            // Register short codes
            add_action('init', [$this->plugin, 'registerShortCodes']);
            // Register custom post types
            add_action('init', [$this->plugin, 'registerCustomPostTypes']);
            // Register taxonomies
            add_action('init', [$this->plugin, 'registerTaxonomies']);
            // Activation hook
            register_activation_hook($this->env('root'), [$this->plugin, 'onActivation']);
            // Deactivation hook
            register_deactivation_hook($this->env('root'), [$this->plugin, 'onDeactivation']);
            // Uninstall hook
            register_uninstall_hook($this->env('root'), [get_class($this->plugin), 'onUninstall']);
            // Bootstrap extensions
            $this->plugin->bootstrapExtensions();
            // Let's bootstrap the plugin
            $this->plugin->bootstrap();
            // Administration & Ajax
            if (is_admin()):
                if (Helpers\Misc::isDoingAjax()):
                    add_action('init', [$this->plugin, 'bootstrapAjax']);
                else:
                    $this->container->get('admin.update');
                    add_action('init', [$this->plugin, 'bootstrapAdmin']);
                endif;
            endif;

            // Booted
            $this->booted = true;
        endif;

        return $this->booted;
    }

    protected function registerProviders()
    {
        // Filesystem
        $this->container->share('filesystem', function () {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
            require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

            WP_Filesystem();

            return new LocalFilesystem(null);
        });

        // Database
        $this->container->share('database', function () {
            return $GLOBALS['wpdb'];
        });

        // Migrations
        $this->container->share('migrations.manager', function () {
            return new Manager();
        });

        // Embed
        $this->container->share('embed', function () {
            if (version_compare($GLOBALS['wp_version'], '5.3', '>=')):
                require_once ABSPATH . WPINC . '/class-wp-oembed.php';
            else:
                require_once ABSPATH . WPINC . '/class-oembed.php';
            endif;

            return new Embed(_wp_oembed_get_object());
        });

        // HTTP
        $this->container->share('http.request', function () {
            return new Http\Request();
        });

        $this->container->share('http.response', function () {
            return new Http\Response();
        });

        // Modules
        $this->container->share('modules.manager', function () {
            return new Modules\Manager($this->container->get('modules.repository'), $this->container->get('filesystem'), $this->container->get('env'));
        });

        $this->container->share('modules.repository', function () {
            return new Modules\Repository($this->container->get('env'), $this->container->get('admin.activation'), $this->container->get('admin.account'));
        });

        // Validator
        $this->container->share('form.validator', function () {
            return new Form\Validator();
        });

        // Factory
        $this->container->share('form.factory', function () {
            return new Form\Factory($this->container->get('http.request'));
        });

        // Form
        $this->container->add('form', function () {
            return new Form\Form();
        });

        $this->container->add('form.page', function () {
            return new Form\Page();
        });

        $this->container->add('form.field.file', function () {
            return new Form\Fields\FileField();
        });

        $this->container->add('form.field.text', function () {
            return new Form\Fields\TextField();
        });

        $this->container->add('form.field.textarea', function () {
            return new Form\Fields\TextareaField();
        });

        $this->container->add('form.field.select', function () {
            return new Form\Fields\SelectField();
        });

        $this->container->add('form.field.radio', function () {
            return new Form\Fields\RadioField();
        });

        $this->container->add('form.field.checkbox', function () {
            return new Form\Fields\CheckboxField();
        });

        $this->container->add('html.element', function ($tag = null, $attributes = null, $inner = null) {
            return new Html($tag, $attributes, $inner);
        });

        $this->container->add('datetime', function ($time = 'now', $timezone = null) {
            if (empty($timezone)):
                $timezone = $this->env('timezone', new \DateTimeZone(Helpers\Misc::timeZoneString()));
            endif;

            // Handle unix timestamps
            if (is_numeric($time)):
                $time = "@{$time}";
            endif;

            return new DateTime($time, $timezone);
        });

        // Options
        $this->container->share('options', function () {
            return new Options\Repository($this->container->get('env'));
        });

        /**
         * ================================================
         * ADMIN SIDE
         * ================================================
         */

        // Updates
        $this->container->share('admin.update', function () {
            return new Admin\Updates($this->container->get('admin.activation'), $this->container->get('admin.account'), $this->container->get('env'));
        });

        // Activation service
        $this->container->share('admin.activation', function () {
            return new Admin\Activation($this->container->get('env'));
        });

        // Account service
        $this->container->share('admin.account', function () {
            return new Admin\Account($this->container->get('env'));
        });

        /**
         * ================================================
         * Tracking
         * ================================================
         */
        $this->container->share('scheduler', function () {
            $scheduler =  new Scheduler();

            $product_id = $this->env('id');

            $scheduler->addCronJob($product_id.'_daily_activity', new TrackEvents());
            $scheduler->addCronJob($product_id.'_weekly_environment', new TrackEnvironment());

            return $scheduler;
        });
    }

    /**
     * Get environment env.
     *
     * @param null $name Name.
     * @param null $default Default value if env is not present.
     *
     * @return mixed|null|object
     */
    public function env($name = null, $default = null)
    {
        if ($name !== null):
            return $this->container->get('env')->get($name, $default);
        endif;

        return $this->container->get('env');
    }

    /**
     * Instance UID.
     *
     * @return string
     */
    public function uid()
    {
        $key = 'instance_uid_' . $this->env('slug');
        $uid = get_option($key);

        if (!$uid):
            $uid = wp_generate_uuid4();
            update_option($key, $uid);
        endif;

        return $uid;
    }

    /**
     * Instance UID.
     *
     * @return string
     */
    public function firstUsage()
    {
        $key = $this->env('slug') . '_first_usage';
        $usage = get_option($key);

        if (!$usage):
            $usage = date(DATE_ATOM);
            update_option($key, $usage);
        endif;

        return $usage;
    }

    /**
     * @return Plugin
     */
    public function getPlugin() {
        return $this->plugin;
    }

    /**
     * Get option.
     *
     * @param null $name Name.
     * @param null $default Default value if env is not present.
     *
     * @return mixed|null|object
     */
    public function option($name, $default = null)
    {
        return $this->container->get('options')->get($name, $default);
    }
}
