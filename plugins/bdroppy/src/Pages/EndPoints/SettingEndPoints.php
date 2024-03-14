<?php


namespace BDroppy\Pages\EndPoints;

use BDroppy\Init\Core;

class SettingEndPoints
{
    protected $core;
    protected $config;
    protected $remote;
    protected $system;
    protected $wc;

    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->remote = $core->getRemote();
        $this->config = $core->getConfig();
        $this->system = $core->getSystem();
        $this->wc = $core->getWc();
        $this->core->getLoader()->addAction( 'rest_api_init', $this, 'registerRoutes' );

    }


    public function registerRoutes()
    {
        $version = '1';
        $namespace = 'bdroppy' . '/v' . $version;

        $d = register_rest_route( $namespace, '/setting', [
            [
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => [$this, 'saveSetting'],
                'permission_callback'   => [$this, 'permissionsCheck'],
            ]
        ] );

    }


    public function saveSetting(\WP_REST_Request $request )
    {
        $this->config->setting->set($request->get_params());
        return new \WP_REST_Response([], 200 );

    }


    public function permissionsCheck( $request ) {
        return current_user_can( 'manage_options' );
    }


}