<?php
namespace WPT\RestrictContent\WP;

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

    public function get_roles()
    {
        global $wp_roles;
        return $wp_roles->get_names();
    }

    /**
     * Register activation hook
     */
    public function register_activation_hook()
    {
        flush_rewrite_rules(true);
    }
}
