<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\Admin;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
use Modular\ConnectorDependencies\Ares\Framework\Support\ProcessUtils;
use Modular\ConnectorDependencies\Ares\Framework\Wordpress\CustomField\RegisterFieldGroupInterface;
/** @internal */
class RegisterWordPressCustomFieldsProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected string $hook = 'admin_init';
    /**
     * Load functions required after_setup_theme
     */
    public function load() : void
    {
        $elements = $this->app->make('config')->get('wordpress.custom-field');
        \array_walk($elements, function ($element) {
            $classes = ProcessUtils::loadClasses($element['path'], $element['namespace']);
            $classes->each(function ($className) {
                $field = $this->app->make($className);
                if ($field instanceof RegisterFieldGroupInterface) {
                    $field->register();
                }
            });
        });
    }
}
