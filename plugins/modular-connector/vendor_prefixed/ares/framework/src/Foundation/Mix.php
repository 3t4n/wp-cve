<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Foundation;

use Exception;
use Modular\ConnectorDependencies\Illuminate\Support\HtmlString;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
/** @internal */
class Mix
{
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    public function __invoke($data)
    {
        static $manifests = [];
        $path = $data['src'] ?? '';
        if (!$path) {
            return;
        }
        if (!Str::startsWith($path, '/')) {
            $path = "/{$path}";
        }
        $manifestDirectory = $data['manifest_directory'] ?? \Modular\ConnectorDependencies\public_path('');
        if ($manifestDirectory && !Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }
        if (\is_file($manifestDirectory . '/hot')) {
            $url = \rtrim(\file_get_contents($manifestDirectory . '/hot'));
            $customUrl = \Modular\ConnectorDependencies\app('config')->get('app.mix_hot_proxy_url');
            if (!empty($customUrl)) {
                return new HtmlString("{$customUrl}{$path}");
            }
            if (Str::startsWith($url, ['http://', 'https://'])) {
                return new HtmlString(Str::after($url, ':') . $path);
            }
            return new HtmlString("//localhost:8080{$path}");
        }
        $manifestPath = $manifestDirectory . '/mix-manifest.json';
        if (!isset($manifests[$manifestPath])) {
            if (!\is_file($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }
            $manifests[$manifestPath] = \json_decode(\file_get_contents($manifestPath), \true);
        }
        $manifest = $manifests[$manifestPath];
        if (!isset($manifest[$path])) {
            $exception = new Exception("Unable to locate Mix file: {$path}.");
            if (!app('config')->get('app.debug')) {
                report($exception);
                return $path;
            } else {
                throw $exception;
            }
        }
        $uri = $data['public_uri'] ?? \Modular\ConnectorDependencies\app('config')->get('app.mix_url') . '/public/';
        if (!Str::endsWith($uri, '/')) {
            $uri .= '/';
        }
        if (!Str::endsWith($uri, 'public/')) {
            $uri .= 'public/';
        }
        $manifest[$path] = \ltrim($manifest[$path], '/');
        return new HtmlString($uri . $manifest[$path]);
    }
}
