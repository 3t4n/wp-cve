<?php

namespace XCurrency\WpMVC\Providers;

\defined('ABSPATH') || exit;
use XCurrency\WpMVC\Contracts\Provider;
use XCurrency\WpMVC\App;
class EnqueueServiceProvider implements Provider
{
    public function boot()
    {
        add_action('wp_enqueue_scripts', [$this, 'action_wp_enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'action_admin_enqueue_scripts']);
    }
    /**
     * Enqueue scripts for all admin pages.
     *
     * @param string $hook_suffix The current admin page.
     */
    public function action_admin_enqueue_scripts(string $hook_suffix) : void
    {
        require_once App::get_dir('enqueues/admin-enqueue.php');
    }
    /**
     * Fires when scripts and styles are enqueued.
     *
     */
    public function action_wp_enqueue_scripts() : void
    {
        require_once App::get_dir('enqueues/frontend-enqueue.php');
    }
}
