<?php

namespace Qodax\CheckoutManager\Modules;

use Qodax\CheckoutManager\Foundation\Routing\Route;
use Qodax\CheckoutManager\Foundation\View;
use Qodax\CheckoutManager\Http\Controllers\FieldsController;
use Qodax\CheckoutManager\Http\Controllers\SettingsController;

if ( ! defined('ABSPATH')) {
    exit;
}

class OptionsPage extends AbstractModule
{
    public function boot(): void
    {
        $this->initRoutes();
        add_action('admin_menu', [ $this, 'registerOptionsPage' ], 99);
        add_filter('plugin_action_links_' . QODAX_CHECKOUT_MANAGER_PLUGIN_NAME, [ $this, 'registerActionLinks' ]);
    }

    public function registerOptionsPage(): void
    {
        add_submenu_page(
            'woocommerce',
            'Qodax Checkout Manager',
            'Qodax Checkout Manager',
            'manage_options',
            'qodax_checkout_manager',
            [ $this, 'html' ]
        );
    }

    public function html(): void
    {
        echo View::render('checkout_manager');
    }

    public function registerActionLinks(array $links): array
    {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            home_url('wp-admin/admin.php?page=qodax_checkout_manager'),
            __('Settings', 'qodax-checkout-manager')
        );
        array_unshift($links, $settings_link);

        return $links;
    }

    private function initRoutes(): void
    {
        $this->router->addRoute(Route::make('qodax_checkout_manager_fields', FieldsController::class, 'getFields'));
        $this->router->addRoute(Route::make('qodax_checkout_manager_save_fields', FieldsController::class, 'save'));
        $this->router->addRoute(Route::make('qodax_checkout_manager_restore_defaults', FieldsController::class, 'restoreDefaults'));
        $this->router->addRoute(Route::make('qodax_checkout_manager_get_settings', SettingsController::class, 'getSettings'));
        $this->router->addRoute(Route::make('qodax_checkout_manager_save_settings', SettingsController::class, 'save'));
    }
}