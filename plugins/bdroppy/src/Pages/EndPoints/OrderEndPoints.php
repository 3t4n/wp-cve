<?php


namespace BDroppy\Pages\EndPoints;

use BDroppy\Init\Core;
use BDroppy\Models\Order;

class OrderEndPoints
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

        $d = register_rest_route( $namespace, '/orders', [
            [
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => [$this, 'getOrders'],
                'permission_callback'   => [$this, 'permissionsCheck'],
            ]
        ] );

    }

    public function getOrders(\WP_REST_Request $request)
    {
        $page = $request->get_param('page') ? $request->get_param('page') - 1 : 0;
        $pageSize = 20;
        $orders = Order::limit($pageSize)->offset($page * $pageSize)->orderBy('id','DESC')->get();

        return new \WP_REST_Response($orders, 200 );
    }

    public function permissionsCheck( $request ) {
        return current_user_can( 'manage_options' );
    }


}