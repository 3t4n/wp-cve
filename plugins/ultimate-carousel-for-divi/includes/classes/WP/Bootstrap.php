<?php
namespace WPT\UltimateDiviCarousel\WP;

/**
 * Bootstrap.
 */
class Bootstrap
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Register activation hook
     */
    public function register_activation_hook()
    {
        flush_rewrite_rules(true);
    }

    /**
     * On plugins loaded action
     */
    public function on_plugins_loaded()
    {
        load_plugin_textdomain('ultimate-carousel-for-divi', false, dirname(plugin_basename($this->container['plugin_file'])) . '/languages/');
    }

}
