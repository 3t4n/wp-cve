<?php

namespace Qodax\CheckoutManager\Modules;

use Qodax\CheckoutManager\Foundation\Container;
use Qodax\CheckoutManager\Foundation\Routing\Router;

if ( ! defined('ABSPATH')) {
    exit;
}

abstract class AbstractModule
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    /**
     * @param Router $router
     */
    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    abstract public function boot(): void;
}