<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event_Chain;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Helper\Core;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Logger\Logger_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Logger\Null_Logger;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form_Chain\Form_Builder;
use function function_exists;
abstract class Abstract_Ilabs_Plugin
{
    use Tools, Environment;
    private static $config;
    /**
     * @var self
     */
    public static $initial_instance;
    /**
     * @var Logger_Interface
     */
    private static $logger;
    /**
     * @param array $config
     *
     * @return void
     * @throws Exception
     */
    public function execute(array $config)
    {
        if (!function_exists('get_plugin_data')) {
            $this->require_wp_core_file('wp-admin/includes/plugin.php');
        }
        self::$config = $config;
        if (!self::$initial_instance) {
            self::$initial_instance = $this;
        }
        register_activation_hook($this->get__file__(), [$this, 'plugin_activate_actions']);
        register_deactivation_hook($this->get__file__(), [$this, 'plugin_deactivate_actions']);
        $this->init_request();
        $this->init_translations();
        $this->before_init();
        if (!self::$logger) {
            self::$logger = new Null_Logger();
        }
        add_action('init', function () {
            $this->enqueue_scripts();
            $this->init();
        });
        add_action('plugins_loaded', function () use($config) {
            $this->plugins_loaded_hooks();
        });
    }
    public function plugin_activate_actions()
    {
    }
    public function plugin_deactivate_actions()
    {
    }
    protected function set_logger(Logger_Interface $logger)
    {
        self::$logger = $logger;
    }
    private function init_request()
    {
        $request = new Request();
        $request->register_request_filter(new Security_Request_Filter());
        foreach ($this->register_request_filters() as $filter) {
            $request->register_request_filter($filter);
        }
        $request->build();
    }
    /**
     * @return Request_Filter_Interface[]
     */
    protected function register_request_filters() : array
    {
        return [];
    }
    /**
     * @return void
     * @throws Exception
     */
    private function init_translations()
    {
        $lang_dir = $this->get_from_config('lang_dir');
        add_action('plugins_loaded', function () use($lang_dir) {
            load_plugin_textdomain($this->get_text_domain(), \false, $this->get_plugin_basename() . "/{$lang_dir}/");
        });
    }
    protected abstract function before_init();
    protected abstract function init();
    private function enqueue_scripts()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_dashboard_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
    }
    protected abstract function plugins_loaded_hooks();
    /**
     * @return Request
     */
    public function get_request() : Request
    {
        return new Request();
    }
    /**
     * @return Alerts
     */
    public function alerts() : Alerts
    {
        return new Alerts();
    }
    public function get_event_chain() : Event_Chain
    {
        return new Event_Chain($this);
    }
    public function get_form_builder() : Form_Builder
    {
        return new Form_Builder($this);
    }
    public function add_slug_prefix(string $text) : string
    {
        return $this->get_from_config('slug') . '_' . $text;
    }
    /**
     * @return Logger_Interface
     */
    public function get_logger() : Logger_Interface
    {
        return self::$logger;
    }
    /**
     * @throws Exception
     */
    public function get_woocommerce_logger() : Woocommerce_Logger
    {
        return new Woocommerce_Logger($this->get_from_config('slug'));
    }
    /**
     * @throws Exception
     */
    public function locate_template(string $template, array $args = [])
    {
        $directory_separator = \DIRECTORY_SEPARATOR;
        $template_absolute_path = $this->get_plugin_templates_dir() . $directory_separator . $template;
        if ('' === locate_template([$template_absolute_path], \true, $args)) {
            include $template_absolute_path;
            \extract($args);
        }
    }
    public function get_core_helpers() : Core
    {
        return new Core();
    }
    public abstract function enqueue_frontend_scripts();
    public abstract function enqueue_dashboard_scripts();
}
