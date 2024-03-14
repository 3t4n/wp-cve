<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup;

use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\Admin\RegisterWordPressAdminMenusProvider;
use Modular\ConnectorDependencies\Illuminate\Support\AggregateServiceProvider;
use Modular\ConnectorDependencies\Illuminate\View\ViewServiceProvider;
/** @internal */
class RegisterWordpressAsPluginProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [ViewServiceProvider::class, RegisterWordPressAdminMenusProvider::class];
}
