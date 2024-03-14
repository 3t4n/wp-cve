<?php

namespace Qodax\CheckoutManager;

use Qodax\CheckoutManager\Foundation\Container;
use Qodax\CheckoutManager\Foundation\Routing\Router;
use Qodax\CheckoutManager\Foundation\View;
use Qodax\CheckoutManager\Modules\AbstractModule;

final class Kernel
{
    private static $instance;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Router
     */
    private $router;

    private function __construct()
    {
        // Components setup
        View::setBasePath(QODAX_CHECKOUT_MANAGER_PLUGIN_DIR . 'views');

        // DI container
        $this->container = Container::instance();
        $this->router = new Router();

        // Init modules
        $modules = $this->modules();

        foreach ($modules as $module) {
            /** @var AbstractModule $moduleInstance */
            $moduleInstance = $this->container->make($module);
            $moduleInstance->setContainer($this->container);
            $moduleInstance->setRouter($this->router);
            $moduleInstance->boot();
        }
    }

    public static function instance(): Kernel
    {
        if ( ! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return string[]
     */
    private function modules(): array
    {
        return [
            \Qodax\CheckoutManager\Modules\InitPlugin::class,
            \Qodax\CheckoutManager\Modules\OptionsPage::class,
            \Qodax\CheckoutManager\Modules\BackendAssets::class,
            \Qodax\CheckoutManager\Modules\Checkout::class,
        ];
    }
}