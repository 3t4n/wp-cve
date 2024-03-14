<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
use Modular\ConnectorDependencies\Ares\Framework\Wordpress\Template\LayoutCustomPost;
/** @internal */
class RegisterWordPressThemeLayoutsProvider extends AbstractServiceProvider
{
    /**
     * Load functions required after_setup_theme
     */
    public function load() : void
    {
        $layouts = new LayoutCustomPost();
        $layouts->register();
    }
}
