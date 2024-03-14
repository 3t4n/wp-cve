<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup;

use Modular\ConnectorDependencies\Ares\Framework\Foundation\Mix;
use Modular\ConnectorDependencies\Carbon\Laravel\ServiceProvider;
/** @internal */
abstract class AbstractServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected string $hook = 'after_setup_theme';
    /**
     * @var int
     */
    protected int $priority = 10;
    /**
     * Load function for hook
     *
     * @return void
     */
    public abstract function load() : void;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        if (\function_exists('add_action') && \function_exists('add_filter')) {
            \add_action($this->hook, [$this, 'load'], $this->priority);
        }
    }
    /**
     * get the correct path (local or URL) with the version
     *
     * @param array $data
     *
     * @return array
     * @throws \Exception
     * @see RegisterWordPressThemeAsAresProvider::registerScripts()
     * @see RegisterWordPressThemeAsAresProvider::registerStyles()
     */
    protected function getPathAndVersion(array $data) : array
    {
        $results = [];
        if (isset($data['src'])) {
            $script = \Modular\ConnectorDependencies\app(Mix::class)($data);
            $script = $this->parseResponse($script);
            $results['version'] = $script['version'] ?? '';
            $results['path'] = $script['path'];
        } else {
            $results['version'] = $data['version'] ?? '';
            $results['path'] = $data['url'] ?? '';
        }
        return $results;
    }
    /**
     * @param string $path
     * @return array
     */
    protected function parseResponse(string $path) : array
    {
        $parts = \parse_url($path);
        if (isset($parts['query'])) {
            \parse_str($parts['query'], $query);
            return ['path' => $parts['path'], 'version' => $query['id'] ?? null];
        } else {
            return ['path' => $path, 'version' => null];
        }
    }
}
