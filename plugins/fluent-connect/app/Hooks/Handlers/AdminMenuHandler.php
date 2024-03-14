<?php

namespace FluentConnect\App\Hooks\Handlers;

use FluentConnect\App\App;

class AdminMenuHandler
{
    public function add()
    {
        $capability = 'manage_options';

        add_menu_page(
            __('Fluent Connect', 'fluent-connect'),
            __('Fluent Connect', 'fluent-connect'),
            $capability,
            'fluent-connect',
            [$this, 'render'],
            $this->getMenuIcon(),
            86
        );
    }

    public function render()
    {
        $this->enqueueAssets();

        $config = App::getInstance('config');
        $app = App::getInstance();
        $assets = $app['url.assets'];

        $name = $config->get('app.name');

        $slug = $config->get('app.slug');

        $baseUrl = apply_filters('fluent_connector_base_url', admin_url('admin.php?page=fluent-connect#/'));

        $menuItems = [
            [
                'key'       => 'dashboard',
                'label'     => __('Dashboard', 'fluent-connect'),
                'permalink' => $baseUrl
            ],
            [
                'key'       => 'connectors',
                'label'     => __('Data Connectors', 'fluent-connect'),
                'permalink' => $baseUrl . 'connectors',
            ]
        ];

        $secondaryItems = [
            [
                'key'       => 'integrations',
                'label'     => __('API Integrations', 'fluent-connect'),
                'permalink' => $baseUrl . 'integrations'
            ],
            [
                'key'       => 'logs',
                'label'     => __('Logs', 'fluent-connect'),
                'permalink' => $baseUrl . 'logs'
            ]
        ];


        App::make('view')->render('admin.menu', [
            'name' => $name,
            'slug' => $slug,
            'base_url' => $baseUrl,
            'logo'           => $assets . 'images/logo.svg',
            'menuItems'      => $menuItems,
            'secondaryItems' => $secondaryItems
        ]);
    }

    public function enqueueAssets()
    {
        $app = App::getInstance();

        $assets = $app['url.assets'];

        $slug = $app->config->get('app.slug');

        wp_enqueue_style(
            $slug . '_admin_app', $assets . '/admin/css/admin.css'
        );

        do_action($slug . '_loading_app');

        wp_enqueue_script(
            $slug . '_admin_app_start',
            $assets . '/admin/js/start.js',
            array('jquery'),
            '1.0',
            true
        );


        wp_enqueue_script(
            $slug . '_global_admin',
            $assets . '/admin/js/global_admin.js',
            array('jquery'),
            '1.0',
            true
        );

        $currentUser = get_user_by('ID', get_current_user_id());

        wp_localize_script($slug . '_admin_app_start', 'fluentFrameworkAdmin', [
            'slug'        => $slug = $app->config->get('app.slug'),
            'nonce'       => wp_create_nonce($slug),
            'rest'        => $this->getRestInfo($app),
            'brand_logo'  => $this->getMenuIcon(),
            'asset_url'   => $assets,
            'me'          => [
                'id'        => $currentUser->ID,
                'full_name' => trim($currentUser->first_name . ' ' . $currentUser->last_name),
                'email'     => $currentUser->user_email
            ],
            'server_time' => current_time('mysql'),
        ]);

    }

    protected function getRestInfo($app)
    {
        $ns = $app->config->get('app.rest_namespace');
        $ver = $app->config->get('app.rest_version');

        return [
            'base_url'  => esc_url_raw(rest_url()),
            'url'       => rest_url($ns . '/' . $ver),
            'nonce'     => wp_create_nonce('wp_rest'),
            'namespace' => $ns,
            'version'   => $ver
        ];
    }

    protected function getMenuIcon()
    {
        return 'dashicons-superhero';
    }
}

