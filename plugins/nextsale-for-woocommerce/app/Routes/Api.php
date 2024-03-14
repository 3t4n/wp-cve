<?php

namespace App\Routes;

use App\Base\Plugin;
use App\Controllers\Customer;
use App\Controllers\ExchangeCode;
use App\Controllers\Order;
use App\Controllers\Product;
use App\Controllers\Shop;
use App\Controllers\ScriptTag;
use App\Controllers\Webhook;
use App\Controllers\Uninstall;

class Api extends Plugin
{
    /**
     * Register
     * @return void
     */
    public function register()
    {
        $this->initRoutes();
    }

    /**
     * Products endpoint
     * @return void
     */
    public function initRoutes()
    {
        add_action('rest_api_init', function () {
            $this->exchangeCode();
            $this->shop();
            $this->script();
            $this->webhook();
            $this->products();
            $this->orders();
            $this->customers();
            $this->uninstall();
        });
    }

    /**
     * Get access token exchange code
     *
     * @return void
     */
    private function exchangeCode()
    {
        register_rest_route('nextsale', 'code/validate', [
            'methods' => 'POST',
            'callback' => [ExchangeCode::class, 'validate'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'code/exchange', [
            'methods' => 'POST',
            'callback' => [ExchangeCode::class, 'saveAccessToken'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Shop settings
     * @return void
     */
    private function shop()
    {
        register_rest_route('nextsale', 'shop', [
            'methods' => 'GET',
            'callback' => [Shop::class, 'settings'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Register nextsale script
     * @return void
     */
    private function script()
    {
        register_rest_route('nextsale', 'script_tags', [
            'methods' => 'GET',
            'callback' => [ScriptTag::class, 'list'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'script_tags', [
            'methods' => 'POST',
            'callback' => [ScriptTag::class, 'add'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'script_tags', [
            'methods' => 'DELETE',
            'callback' => [ScriptTag::class, 'delete'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'script_tags/delete', [
            'methods' => 'POST',
            'callback' => [ScriptTag::class, 'delete'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Register webhook address
     * @return void
     */
    private function webhook()
    {
        register_rest_route('nextsale', 'webhooks', [
            'methods' => 'GET',
            'callback' => [Webhook::class, 'list'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'webhooks', [
            'methods' => 'POST',
            'callback' => [Webhook::class, 'add'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'webhooks', [
            'methods' => 'DELETE',
            'callback' => [Webhook::class, 'delete'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'webhooks/delete', [
            'methods' => 'POST',
            'callback' => [Webhook::class, 'delete'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Products
     * @return void
     */
    private function products()
    {
        register_rest_route('nextsale', 'products', [
            'methods' => 'GET',
            'callback' => [Product::class, 'list'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'products/(?P<id>[\d]+)', [
            'methods' => 'GET',
            'callback' => [Product::class, 'get'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'products/count', [
            'methods' => 'GET',
            'callback' => [Product::class, 'count'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Orders 
     * @return void
     */
    private function orders()
    {
        register_rest_route('nextsale', 'orders', [
            'methods' => 'GET',
            'callback' => [Order::class, 'list'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'orders/(?P<id>[\d]+)', [
            'methods' => 'GET',
            'callback' => [Order::class, 'get'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'orders/count', [
            'methods' => 'GET',
            'callback' => [Order::class, 'count'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Customers 
     * @return void
     */
    private function customers()
    {
        register_rest_route('nextsale', 'customers', [
            'methods' => 'GET',
            'callback' => [Customer::class, 'list'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'customers/(?P<id>[\d]+)', [
            'methods' => 'GET',
            'callback' => [Customer::class, 'get'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('nextsale', 'customers/count', [
            'methods' => 'GET',
            'callback' => [Customer::class, 'count'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Uninstall the site from Nextsale dashboard
     *
     * @return void
     */
    private function uninstall()
    {
        register_rest_route('nextsale', 'revoke', [
            'methods' => 'POST',
            'callback' => [Uninstall::class, 'invoke'],
            'permission_callback' => '__return_true'
        ]);
    }
}
