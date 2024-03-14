<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
use Modular\ConnectorDependencies\Ares\Framework\Support\ProcessUtils;
/** @internal */
class RegisterWordpressThemeBuilderElementsProvider extends AbstractServiceProvider
{
    /**
     * @var int
     */
    protected int $priority = 20;
    /**
     * Register Builder elements
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function load() : void
    {
        $elements = $this->app->make('config')->get('builder.builder');
        \array_walk($elements, function ($element) {
            $classes = ProcessUtils::loadClasses($element['path'], $element['namespace']);
            $classes->each(function ($className) {
                $this->app->make($className)->register();
            });
        });
    }
}
