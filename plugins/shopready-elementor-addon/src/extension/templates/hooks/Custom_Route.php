<?php

namespace Shop_Ready\extension\templates\hooks;

use Shop_Ready\base\Routes as Base_Routes;

class Custom_Route
{

    public function register()
    {

        add_action('init', [$this, 'run']);
        $this->wishlist();
        $this->compare();
        $this->newslatter();

    }

    public function wishlist()
    {

        $path = shop_ready_app_config()['views']['templating'];
        $_routes = new Base_Routes();
        $_routes->addRoute(
            '^shopready-popup-wishlist/([^/]*)/?',
            [$this, 'api_callback'],
            shop_ready_fix_path($path . '/custom/wishlist.php'),
            array('wishlist' => 1)
        );

    }

    public function compare()
    {

        $path = shop_ready_app_config()['views']['templating'];
        $_routes = new Base_Routes();
        $_routes->addRoute(
            '^shopready-popup-compare/([^/]*)/?',
            [$this, 'api_callback'],
            $path . '/custom/compare.php',
            array('type' => 1)
        );

    }

    public function newslatter()
    {

        $path = shop_ready_app_config()['views']['templating'];
        $_routes = new Base_Routes();
        $_routes->addRoute(
            '^shopready-popup-newslatter/([^/]*)/?',
            [$this, 'api_callback'],
            $path . '/custom/newslatter.php',
            array('type' => 1)
        );

    }

    public function api_callback($param1)
    {
        set_query_var('scompare', $param1);
    }

    public function run()
    {

    }


}
