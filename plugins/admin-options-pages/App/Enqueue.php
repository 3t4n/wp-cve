<?php

namespace AOP\App;

final class Enqueue
{
    use Singleton;

    private function __construct()
    {
        add_action('admin_enqueue_scripts', function () {
            $this->adminJs();
            $this->adminCss();
        });
    }

    /**
     * Handles all the admin js files.
     */
    private function adminJs()
    {
        Data::adminPagesIds()->filter(function ($page) {
            return get_current_screen()->id === $page;
        })->each(function () {
            wp_enqueue_script(Plugin::PREFIX . 'app-js', Plugin::assetsUrl() . 'js/aop.js', [], static::version('js/aop.js'), 'all');

            wp_localize_script(Plugin::PREFIX . 'app-js', Plugin::PREFIX_ . 'script_data_js', Data::js()->toArray());

            wp_localize_script(Plugin::PREFIX . 'app-js', Plugin::PREFIX_ . 'dashicons_js', Dashicons::allIconsCollection()->toArray());
        });
    }

    /**
     * Handles all the admin css files.
     */
    private function adminCss()
    {
        Data::adminPagesIds()->filter(function ($page) {
            return get_current_screen()->id === $page && get_current_screen()->id !== 'toplevel_page_admin_options_pages_master';
        })->each(function () {
            wp_enqueue_style(Plugin::PREFIX . 'app-css', Plugin::assetsUrl() . 'css/aop.css', [], static::version('css/aop.css'), 'all');
        });

        wp_enqueue_style(Plugin::PREFIX . 'icon-css', Plugin::assetsUrl() . 'css/aop-icon.css', [], Plugin::VERSION, 'all');
    }

    public static function version($file)
    {
        return md5(filemtime(Plugin::assetsDir() . $file));
    }
}
