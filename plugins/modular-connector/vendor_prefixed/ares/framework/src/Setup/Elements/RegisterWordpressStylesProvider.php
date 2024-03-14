<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
/** @internal */
class RegisterWordpressStylesProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected string $hook = 'init';
    /**
     * Enqueue scripts and styles.
     *
     * @throws \Exception
     */
    public function load() : void
    {
        $styles = $this->app->make('config')->get('view.styles');
        foreach ($styles as $name => $props) {
            $callback = function () use($name, $props) {
                $results = $this->getPathAndVersion($props);
                $path = $results['path'];
                $version = $results['version'];
                \wp_enqueue_style($name, $path, [], $version, $props['footer']);
            };
            if ($props['admin']) {
                \add_action('admin_enqueue_scripts', $callback, $props['priority'] ?? 10);
            } else {
                \add_action('wp_enqueue_scripts', $callback, $props['priority'] ?? 10);
            }
        }
    }
}
