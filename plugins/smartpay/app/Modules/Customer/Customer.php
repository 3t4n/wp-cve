<?php

namespace SmartPay\Modules\Customer;

use SmartPay\Http\Controllers\Rest\Admin\CustomerController;
use WP_REST_Server;

class Customer
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;

        $this->app->addAction('admin_enqueue_scripts', [$this, 'adminScripts']);

        $this->app->addAction('rest_api_init', [$this, 'registerRestRoutes']);
    }

    public function adminScripts()
    {
        //
    }

    public function registerRestRoutes()
    {
        $customerController = $this->app->make(CustomerController::class);

        register_rest_route('smartpay/v1', 'customers', [
            [
                'methods'   => WP_REST_Server::READABLE,
                'callback'  => [$customerController, 'index'],
                'permission_callback' => [$customerController, 'middleware'],
            ],
            [
                'methods'   => WP_REST_Server::CREATABLE,
                'callback'  => [$customerController, 'store'],
                'permission_callback' => [$customerController, 'middleware'],
            ],
        ]);

        register_rest_route('smartpay/v1', 'customers/(?P<id>[\d]+)', [
            [
                'methods'   => WP_REST_Server::READABLE,
                'callback'  => [$customerController, 'show'],
                'permission_callback' => [$customerController, 'middleware'],
            ],
            [
                'methods'   => 'PUT, PATCH',
                'callback'  => [$customerController, 'update'],
                'permission_callback' => [$customerController, 'middleware'],
            ],
            [
                'methods'   => WP_REST_Server::DELETABLE,
                'callback'  => [$customerController, 'destroy'],
                'permission_callback' => [$customerController, 'middleware'],
            ],
        ]);
    }
}
