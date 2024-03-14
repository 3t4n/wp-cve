<?php

namespace XCurrency\WpMVC;

use XCurrency\DI\Container;
use XCurrency\WpMVC\Contracts\Provider;
use XCurrency\WpMVC\Providers\EnqueueServiceProvider;
use XCurrency\WpMVC\Providers\MigrationServiceProvider;
use XCurrency\WpMVC\Providers\RouteServiceProvider;
class App
{
    public static bool $loaded;
    public static App $instance;
    public static Container $container;
    public static Config $config;
    protected static string $root_dir;
    protected static string $root_url;
    public static string $plugin_root_file;
    public static function instance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    public function boot(string $plugin_root_file, string $plugin_root_dir)
    {
        if (!empty(static::$loaded)) {
            return;
        }
        if (!\function_exists('XCurrency\\get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $container = new Container();
        $container->set(static::class, static::$instance);
        $config = $container->get(Config::class);
        static::$config = $config;
        static::$container = $container;
        $this->set_path($plugin_root_file, $plugin_root_dir);
    }
    public function load()
    {
        if (!empty(static::$loaded)) {
            return;
        }
        $this->boot_core_service_providers();
        $this->boot_plugin_service_providers();
        static::$loaded = \true;
    }
    protected function set_path(string $plugin_root_file, string $plugin_root_dir)
    {
        static::$plugin_root_file = $plugin_root_file;
        static::$root_url = trailingslashit(plugin_dir_url($plugin_root_file));
        static::$root_dir = trailingslashit($plugin_root_dir);
    }
    public static function get_dir(string $dir = '')
    {
        return static::$root_dir . \ltrim($dir, '/');
    }
    public static function get_url(string $url = '')
    {
        return static::$root_url . \ltrim($url, '/');
    }
    protected function boot_core_service_providers() : void
    {
        $this->boot_service_providers($this->core_service_providers());
    }
    protected function boot_plugin_service_providers() : void
    {
        $this->boot_service_providers(static::$config->get('app.providers'));
        if (is_admin()) {
            $this->boot_service_providers(static::$config->get('app.admin_providers'));
        }
    }
    protected function boot_service_providers(array $providers) : void
    {
        foreach ($providers as $provider) {
            $provider_instance = static::$container->get($provider);
            if ($provider_instance instanceof Provider) {
                $provider_instance->boot();
            }
        }
    }
    protected function core_service_providers()
    {
        return [MigrationServiceProvider::class, RouteServiceProvider::class, EnqueueServiceProvider::class];
    }
}
