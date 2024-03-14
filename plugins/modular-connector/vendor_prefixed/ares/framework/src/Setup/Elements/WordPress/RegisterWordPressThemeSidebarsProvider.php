<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
/**
 * @depreacted deprecated since version 2.0
 * @internal
 */
class RegisterWordPressThemeSidebarsProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected string $hook = 'widgets_init';
    /**
     * Load function for hook
     *
     * @return void
     */
    public function load() : void
    {
        $widgets = $this->app->make('config')->get('template.widgets');
        \array_walk($widgets, fn($widget) => \register_sidebar($widget));
    }
}
