<?php

namespace App\Hooks;

use App\Utils\Helper;

class Handler
{
    /**
     * Register
     * @return void
     */
    public function register()
    {
        $this->initHooks();
    }

    /**
     * Init hooks
     * @return void
     */
    public function initHooks()
    {
        $this->shop();
        $this->customer();

        if (Helper::isWc()) {
            $this->product();
            $this->order();
        }
    }

    /**
     * Shop hook
     * @return void
     */
    public function shop()
    {
        add_action('updated_option', [Shop::class, 'update'], 10, 3);
    }

    /**
     * Product hooks
     * @return void
     */
    public function product()
    {
        add_action('post_created', [Product::class, 'create'], 20);
        add_action('post_updated', [Product::class, 'update'], 30);
        add_action('trash_product', [Product::class, 'delete'], 10);
    }

    /**
     * Customer hooks
     * @return void
     */
    public function customer()
    {
        add_action('user_register', [Customer::class, 'create'], 10, 2);
        add_action('profile_update', [Customer::class, 'update'], 10, 2);
        add_action('delete_user', [Customer::class, 'delete'], 10, 2);
    }

    /**
     * Order hooks
     * @return void
     */
    public function order()
    {
        add_action('woocommerce_new_order', [Order::class, 'create'], 10);
        add_action('woocommerce_update_order', [Order::class, 'update'], 10);
        add_action('before_delete_post', [Order::class, 'delete'], 10);
    }
}
