<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
use Modular\ConnectorDependencies\Ares\Framework\Support\ProcessUtils;
/**
 * @depreacted deprecated since version 2.0
 * @internal
 */
class RegisterWordPressWidgetsProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected string $hook = 'widgets_init';
    /**
     * Load functions required after_setup_theme
     */
    public function load() : void
    {
        $elements = $this->app->make('config')->get('wordpress.widgets');
        \array_walk($elements, function ($element) {
            $classes = ProcessUtils::loadClasses($element['path'], $element['namespace']);
            $classes->each(function ($className) {
                \register_widget($className);
            });
        });
    }
}
