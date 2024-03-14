<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\Admin;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
use Modular\ConnectorDependencies\Ares\Framework\Support\ProcessUtils;
use Modular\ConnectorDependencies\Ares\Framework\Wordpress\Admin\SubmenuInterface;
/** @internal */
class RegisterWordPressAdminMenusProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected string $hook = 'init';
    /**
     * Load functions required after_setup_theme
     */
    public function load() : void
    {
        $elements = $this->app->make('config')->get('wordpress.admin-menu');
        \array_walk($elements, function ($element) {
            $classes = ProcessUtils::loadClasses($element['path'], $element['namespace']);
            $classes->each(function ($className) {
                $menu = $this->app->make($className);
                /**
                 * Check if the object is a "submenu" and get parent
                 */
                if ($menu instanceof SubmenuInterface && empty($menu->parent())) {
                    return;
                }
                $menu->register();
            });
        });
    }
}
