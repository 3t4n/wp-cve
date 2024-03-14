<?php
namespace WPT\DiviProductCarousel\WP;

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
}
