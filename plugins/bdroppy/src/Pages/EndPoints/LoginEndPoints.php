<?php


namespace BDroppy\Pages\EndPoints;

use BDroppy\CronJob\CronJob;
use BDroppy\Init\Core;

class LoginEndPoints
{
    protected $core;
    protected $config;
    protected $remote;

    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->remote = $core->getRemote();
        $this->config = $core->getConfig();
        $this->core->getLoader()->addAction( 'rest_api_init', $this, 'registerRoutes' );
    }


    public function registerRoutes()
    {
        $version = '1';
        $namespace = 'bdroppy' . '/v' . $version;
        $endpoint = '/login';

        $d = register_rest_route( $namespace, $endpoint, [
            [
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => [$this, 'Login'],
                'permission_callback'   => [$this, 'permissionsCheck'],
            ]
        ] );


        $d = register_rest_route( $namespace, '/logout', [
            [
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => [$this, 'Logout'],
                'permission_callback'   => [$this, 'permissionsCheck'],
            ]
        ] );


    }

    public function Login(\WP_REST_Request $request )
    {
        $token = $request->get_param('token');
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        $api_base_url = $request->get_param('api_base_url');
        $this->config->api->set('api-base-url',$api_base_url);

        if(isset($token))
        {
            $this->config->api->set('api-token',$token);
            return new \WP_REST_Response(1, 200 );
        }

        $result = $this->remote->main->getToken($email,$password);
        if($result['response']['code'] == 200)
        {
            $this->config->api->set('api-email',$email);
            $this->config->api->set('api-password',$password);
            $this->config->api->set('api-token',$result['body']->token);
            $this->config->api->set('api-token-for-user',$result['body']->email);
            CronJob::scheduleEvents();
        }
        return new \WP_REST_Response($result, 200 );
    }


    public function Logout(\WP_REST_Request $request )
    {
        $this->config->api->set('api-token');
        $this->config->api->set('api-email');
        $this->config->api->set('api-token-for-user');
        return new \WP_REST_Response(1, 200 );
    }


    public function permissionsCheck( $request ) {
        return current_user_can( 'manage_options' );
    }


}