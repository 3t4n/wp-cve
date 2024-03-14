<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
/** @internal */
class RegisterWordPressThemeSupportsProvider extends AbstractServiceProvider
{
    /**
     * Load functions required after_setup_theme
     */
    public function load() : void
    {
        $supports = $this->app->make('config')->get('template.supports');
        foreach ($supports as $name => $options) {
            \add_theme_support($name, $options);
        }
    }
}
