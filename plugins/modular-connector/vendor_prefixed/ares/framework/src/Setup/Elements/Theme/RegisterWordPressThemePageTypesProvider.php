<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
/** @internal */
class RegisterWordPressThemePageTypesProvider extends AbstractServiceProvider
{
    /**
     * Load functions required after_setup_theme
     */
    public function load() : void
    {
        \add_filter('theme_page_templates', fn($templates) => \array_merge($templates, $this->app->make('config')->get('template.pages-template')));
    }
}
