<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
use Modular\ConnectorDependencies\Ares\Framework\Support\ProcessUtils;
use Modular\ConnectorDependencies\Ares\Builder\WordPress\Customize\ControlInterface;
use Modular\ConnectorDependencies\Ares\Builder\WordPress\Customize\PanelInterface;
use Modular\ConnectorDependencies\Ares\Builder\WordPress\Customize\SectionInterface;
/** @internal */
class RegisterWordPressThemeCustomizerProvider extends AbstractServiceProvider
{
    /**
     * Load function for hook
     *
     * @return void
     */
    public function load() : void
    {
        if (\is_customize_preview()) {
            $elements = $this->app->make('config')->get('wordpress.customize');
            \array_walk($elements, function ($element) {
                $classes = ProcessUtils::loadClasses($element['path'], $element['namespace']);
                $temp = \Modular\ConnectorDependencies\collect();
                $classes->each(function ($className) use($temp) {
                    $object = $this->app->make($className);
                    if ($object instanceof SectionInterface || $object instanceof ControlInterface) {
                        $object->register();
                    } else {
                        if ($object instanceof PanelInterface) {
                            $temp->push($object);
                        }
                    }
                });
                $temp->each(function ($tmp) {
                    $tmp->register();
                });
            });
        }
    }
}
