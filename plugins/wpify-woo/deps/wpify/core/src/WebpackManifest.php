<?php

namespace WpifyWooDeps\Wpify\Core;

use WpifyWooDeps\Wpify\Core\Exceptions\PluginException;
use WpifyWooDeps\Wpify\Core\Traits\ComponentTrait;
/**
 * Class WebpackBuild
 *
 * @package Wpify\Core
 */
class WebpackManifest
{
    use ComponentTrait;
    /**
     * Manifest file path
     *
     * @var string
     */
    private $manifest_path;
    /**
     * Prefix for asset handles
     *
     * @var string
     */
    private $handle_prefix;
    /**
     * Manifest file content
     *
     * @var array
     */
    private $manifest = array();
    /**
     * Version and dependencies from assets.php
     *
     * @var array
     */
    private $info = array();
    /**
     * WebpackBuild constructor.
     *
     * @param string $manifest_path Manifest json file.
     * @param string $handle_prefix Prefix for file handle.
     */
    public function __construct(string $manifest_path = '', string $handle_prefix = '')
    {
        $this->handle_prefix = $handle_prefix;
        $this->manifest_path = $manifest_path;
    }
    /**
     * Initialize Webpack Manifest
     */
    public function init()
    {
        $manifest = array();
        $assets_info = array();
        $manifest_file = null;
        try {
            if (empty($this->handle_prefix) && !empty($this->get_plugin()->safe_slug)) {
                $this->handle_prefix = $this->get_plugin()->safe_slug . '~';
            }
        } catch (PluginException $exception) {
            $this->handle_prefix = '';
        }
        try {
            if (!empty($this->manifest_path) && \file_exists($this->get_plugin()->get_asset_path($this->manifest_path))) {
                $this->manifest_path = $this->get_plugin()->get_asset_path($this->manifest_path);
                $manifest_file = \file_get_contents($this->manifest_path);
                // phpcs:ignore
                $info_path = \preg_replace('/[^\\/]+\\.json/m', 'assets.php', $this->manifest_path);
                $assets_info = (include $info_path);
            }
        } catch (PluginException $exception) {
        }
        // phpcs:ignore
        if (!empty($manifest_file)) {
            $manifest = \json_decode($manifest_file, \true);
        }
        $this->manifest = $manifest;
        foreach ($this->manifest as $file => $path) {
            $filename = \strrpos($path, '/') !== \false ? \substr($path, \strrpos($path, '/') + 1) : $path;
            $info = isset($assets_info[$filename]) ? $assets_info[$filename] : null;
            $chunks = \explode('~', \substr($file, 0, \strrpos($file, '.')));
            $type = $this->get_file_type($filename) ? $this->get_file_type($filename) : 'other';
            if (\strpos($path, '/') !== 0) {
                $path = $this->plugin->get_asset_url('build/' . $path);
            }
            $this->manifest[$file] = array('key' => $file, 'file' => $path, 'handle' => $this->handle_prefix . \join('~', $chunks) . '~' . $type, 'deps' => $info ? $info['dependencies'] : array(), 'version' => $info ? $info['version'] : null, 'chunks' => $chunks, 'type' => $type, 'load' => \true);
        }
    }
    /**
     * Returns type of the file by filename
     *
     * @param string $filename File name.
     *
     * @return ?string
     */
    public function get_file_type(string $filename = '')
    {
        if (\filter_var($filename, \FILTER_VALIDATE_URL)) {
            $parts = wp_parse_url($filename);
            $extension = \pathinfo($parts['path'], \PATHINFO_EXTENSION);
        } else {
            $extension = \pathinfo($filename, \PATHINFO_EXTENSION);
        }
        switch ($extension) {
            case 'js':
            case 'jsx':
                $file_type = 'script';
                break;
            case 'css':
                $file_type = 'style';
                break;
            default:
                $file_type = null;
        }
        return $file_type;
    }
    /**
     * Enqueues asset.
     *
     * @param string $name Asset name.
     * @param string $handle Handle.
     * @param array $localize Localisation of the script.
     * @param array $deps Dependencies of the script.
     */
    public function enqueue_asset(string $name, string $handle = '', $localize = array(), $deps = array())
    {
        $handle = $this->register_asset($name, $handle, $localize, $deps);
        $type = $this->get_file_type($name);
        if (empty($handle)) {
            return;
        }
        if ('script' === $type) {
            wp_enqueue_script($handle);
        }
        if ('style' === $type) {
            wp_enqueue_style($handle);
        }
    }
    /**
     * Registers the assets with dependencies
     *
     * @param string $name Asset name.
     * @param string $handle Handle.
     * @param array $localize Localisation of the script.
     * @param array $deps Dependencies of the script.
     *
     * @return ?string
     */
    public function register_asset(string $name, string $handle = '', $localize = array(), $deps = array())
    {
        $assets = $this->get_assets($name, $handle, $localize, $deps);
        $main_asset = null;
        foreach ($assets as $asset) {
            if ($asset['key'] === $name) {
                $main_asset = $asset;
            }
            if ('script' === $asset['type']) {
                wp_register_script($asset['handle'], $asset['file'], $asset['deps'], $asset['version'], empty($asset['in_footer']) ? null : $asset['in_footer']);
                if (isset($asset['localize']) && $asset['localize']) {
                    foreach ($asset['localize'] as $object_name => $args) {
                        wp_localize_script($asset['handle'], $object_name, $args);
                    }
                }
            } elseif ('style' === $asset['type']) {
                wp_register_style($asset['handle'], $asset['file'], $asset['deps'], $asset['version']);
            }
        }
        if (empty($main_asset)) {
            $main_asset = empty($asset) ? null : $asset;
        }
        return !empty($main_asset['handle']) ? $main_asset['handle'] : null;
    }
    /**
     * Get asset tree from the single asset.
     *
     * @param string $name Asset name.
     * @param string $handle Handle.
     * @param array $localize Localisation of the script.
     * @param array $deps Dependencies of the script.
     *
     * @return array
     */
    public function get_assets(string $name, $handle = '', $localize = array(), $deps = array())
    {
        $chunk = \substr($name, 0, \strrpos($name, '.'));
        $assets = array();
        $main_asset = $this->get_asset($name, $handle, $localize, $deps);
        $chunks = \array_filter($this->manifest, function ($asset) use($chunk, $name, $main_asset) {
            return !empty($main_asset) && \in_array($chunk, $asset['chunks'], \true) && $asset['key'] !== $name && $asset['type'] === $main_asset['type'];
        });
        foreach ($chunks as $chunk) {
            $assets[] = $chunk;
            $deps[] = $chunk['handle'];
        }
        if (!empty($main_asset)) {
            $main_asset['enqueue'] = \true;
            $assets[] = $main_asset;
        }
        return $assets;
    }
    /**
     * Get single asset from manifest file
     *
     * @param string $name Asset name.
     * @param string $handle Handle.
     * @param array $localize Localisation of the script.
     * @param array $deps Dependencies of the script.
     *
     * @return ?array
     */
    public function get_asset(string $name, string $handle = '', $localize = array(), $deps = array())
    {
        if (isset($this->manifest[$name])) {
            $asset = $this->manifest[$name];
            $asset['handle'] = empty($handle) ? $asset['handle'] : $handle;
            $asset['localize'] = $localize;
            $asset['deps'] = \array_merge($asset['deps'], $deps);
            $asset['file'] = \str_replace('/build/build/', '/build/', $asset['file']);
            return $asset;
        }
        return null;
    }
}
