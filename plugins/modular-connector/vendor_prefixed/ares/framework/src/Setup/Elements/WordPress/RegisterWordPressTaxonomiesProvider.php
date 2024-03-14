<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
use Modular\ConnectorDependencies\Ares\Framework\Support\ProcessUtils;
/** @internal */
class RegisterWordPressTaxonomiesProvider extends AbstractServiceProvider
{
    /**
     * Load functions required after_setup_theme
     */
    public function load() : void
    {
        $elements = $this->app->make('config')->get('wordpress.custom-taxonomy');
        \array_walk($elements, function ($element) {
            $classes = ProcessUtils::loadClasses($element['path'], $element['namespace']);
            $classes->each(function ($className) {
                $this->app->make($className)->register();
            });
        });
    }
}
