<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WpifyWooDeps\DI\Container;
use WpifyWooDeps\Dice\Dice;
use ReflectionException;
use WP_Filesystem_Direct;
use WpifyWooDeps\Wpify\Core\Exceptions\ComponentInitFailureException;
use WpifyWooDeps\Wpify\Core\Exceptions\ContainerInvalidException;
use WpifyWooDeps\Wpify\Core\Exceptions\ContainerNotExistsException;
/**
 * Class Plugin_0_9_0_0
 *
 * @package Wpify\Core\Abstracts
 * @property Container $container
 * @property string $slug
 * @property string $safe_slug
 * @property array<string|int,mixed> $plugin_info
 * @property string $plugin_file
 * @property string $version
 * @property WP_Filesystem_Direct $wp_filesystem
 */
abstract class AbstractPlugin extends AbstractComponent
{
    /**
     * Default version constant
     */
    const VERSION = '';
    /**
     * Default slug constant
     */
    const PLUGIN_SLUG = '';
    /**
     * Plugin namespace
     */
    const PLUGIN_NAMESPACE = '';
    /**
     * Path to plugin entry file
     *
     * @var string
     */
    protected $plugin_file;
    /**
     * Path to plugin directory
     *
     * @var string
     */
    protected $plugin_dir;
    /**
     * Dependency Container
     *
     * @var Dice
     */
    protected $container;
    /**
     * WP File System
     *
     * @var WP_Filesystem_Direct
     */
    protected $wp_filesystem;
    /**
     * PluginAbstract constructor.
     *
     * @throws ContainerInvalidException
     * @throws ContainerNotExistsException
     */
    public function __construct()
    {
        $this->find_plugin_file();
        $this->set_container();
    }
    /**
     */
    protected function find_plugin_file()
    {
        $dir = \dirname($this->get_file_name());
        $file = null;
        do {
            $last_dir = $dir;
            $dir = \dirname($dir);
            $file = $dir . \DIRECTORY_SEPARATOR . $this->plugin->get_slug() . '.php';
        } while (!$this->get_wp_filesystem()->is_file($file) && $dir !== $last_dir);
        $this->plugin_file = $file;
        $this->plugin_dir = $dir;
    }
    /**
     * @return string
     */
    public function get_slug()
    {
        return static::PLUGIN_SLUG;
    }
    /**
     * @param array<string|int,mixed> $args
     *
     * @return WP_Filesystem_Direct
     */
    public function get_wp_filesystem($args = array())
    {
        /**
         * @var WP_Filesystem_Direct $wp_filesystem
         */
        global $wp_filesystem;
        $original_wp_filesystem = $wp_filesystem;
        if (null === $this->wp_filesystem) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
            add_filter('filesystem_method', array($this, 'filesystem_method_override'));
            WP_Filesystem($args);
            remove_filter('filesystem_method', array($this, 'filesystem_method_override'));
            $this->wp_filesystem = $wp_filesystem;
            $wp_filesystem = $original_wp_filesystem;
        }
        return $this->wp_filesystem;
    }
    /**
     * @param bool $network_wide
     *
     * @return void
     */
    public abstract function activate($network_wide);
    /**
     * @param bool $network_wide
     *
     * @return void
     */
    public abstract function deactivate($network_wide);
    /**
     * @return void
     */
    public abstract function uninstall();
    /**
     * @return string
     */
    public function get_plugin_file()
    {
        return $this->plugin_file;
    }
    /**
     * @return Dice
     */
    public function get_container()
    {
        return $this->container;
    }
    /**
     * @throws ContainerInvalidException
     * @throws ContainerNotExistsException
     */
    protected function set_container()
    {
        $namespace = static::PLUGIN_NAMESPACE;
        $container = '';
        if (!empty($namespace)) {
            if ('\\' !== $namespace[0]) {
                throw new ContainerNotExistsException(\sprintf('Container namespace for Plugin %s must start with a backslash.', $this->get_full_class_name()));
            }
            if ('\\' === $namespace[\strlen($namespace) - 1]) {
                throw new ContainerNotExistsException(\sprintf('Container namespace for Plugin %s must not end with a backslash.', $this->get_full_class_name()));
            }
            $container = "{$namespace}\\container";
        }
        if (!\function_exists($container)) {
            $slug = \str_replace('-', '_', static::PLUGIN_SLUG);
            $container = "{$slug}_container";
        }
        if (!\function_exists($container)) {
            throw new ContainerNotExistsException(\sprintf('Container function %s does not exist.', $container));
        }
        $this->container = $container();
        if (!$this->container instanceof Container) {
            throw new ContainerInvalidException(\sprintf('Container function %s does not return a Dice instance.', $container));
        }
    }
    /**
     * Plugin setup
     *
     * @return bool
     * @throws ComponentInitFailureException
     * @throws ReflectionException
     */
    public function init()
    {
        /**
         * @noinspection DynamicInvocationViaScopeResolutionInspection
         */
        if (!static::get_dependencies_exist()) {
            return \false;
        }
        if ($result = $this->try_init($this, parent::init())) {
            throw $result;
        }
        $this->inited = \true;
        return \true;
    }
    /**
     * @return bool
     */
    protected function get_dependencies_exist()
    {
        return \true;
    }
    /**
     * @param string $field
     *
     * @return string|array<string|int,mixed>
     */
    public function get_plugin_info($field = null)
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $info = get_plugin_data($this->plugin_file);
        if (null !== $field && isset($info[$field])) {
            return $info[$field];
        }
        return $info;
    }
    /**
     * @return string
     */
    public function get_version()
    {
        return static::VERSION;
    }
    /**
     * @return string
     */
    public function get_safe_slug()
    {
        return \strtolower(\str_replace('-', '_', $this->get_slug()));
    }
    /**
     * @return string
     */
    public function filesystem_method_override()
    {
        return 'direct';
    }
    /**
     * @param $file
     *
     * @return string
     */
    public function get_asset_url($file)
    {
        if ($this->get_wp_filesystem()->is_file($file)) {
            $file = \str_replace(plugin_dir_path($this->plugin_file), '', $file);
        }
        return plugins_url($file, $this->plugin_file);
    }
    /**
     * @param $file
     *
     * @return string
     */
    public function get_asset_path($file)
    {
        return $this->plugin_dir . \DIRECTORY_SEPARATOR . $file;
    }
    public function get_plugin_dir()
    {
        return $this->plugin_dir;
    }
}
