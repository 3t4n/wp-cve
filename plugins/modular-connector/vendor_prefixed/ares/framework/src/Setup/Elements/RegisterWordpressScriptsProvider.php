<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
/** @internal */
class RegisterWordpressScriptsProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected string $hook = 'init';
    /**
     * Enqueue scripts
     *
     * @throws \Exception
     */
    public function load() : void
    {
        $scripts = $this->app->make('config')->get('view.scripts');
        foreach ($scripts as $name => $props) {
            $callback = function () use($name, $props) {
                $results = $this->getPathAndVersion($props);
                $path = $results['path'];
                $version = $results['version'];
                \wp_enqueue_script($name, $path, [], $version, $props['footer']);
            };
            if ($props['admin']) {
                \add_action('admin_enqueue_scripts', $callback, $props['priority'] ?? 1);
            } else {
                \add_action('wp_enqueue_scripts', $callback, $props['priority'] ?? 1);
            }
        }
    }
}
