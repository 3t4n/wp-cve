<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WpifyWooDeps\Wpify\Core\Exceptions\PluginException;
/**
 * Class AbstractAssets
 *
 * @package Wpify\Core\Abstracts
 */
abstract class AbstractAssets extends AbstractComponent
{
    /**
     * Assets associated with the plugin
     *
     * @var array
     */
    private $assets = array();
    /**
     * Assets that are enqueued
     *
     * @var array $enqueued_assets
     */
    private $enqueued_assets = array();
    /**
     * Assets that were printed
     *
     * @var array $printed_assets
     */
    private $printed_assets = array();
    /**
     * Assets initialization.
     *
     * @return bool|\Exception|void
     * @throws \ReflectionException ReflextionException.
     */
    public function init()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_head', array($this, 'preload_styles'));
        parent::init();
    }
    /**
     * Enqueue assets
     *
     * @param array|null $assets
     *
     * @throws PluginException
     */
    public function enqueue_assets($assets = null)
    {
        if (empty($assets)) {
            $this->setup_assets();
            $assets = $this->assets;
        }
        foreach ($assets as $asset) {
            if ($this->is_asset_enqueued($asset['handle'])) {
                continue;
            }
            if (empty($asset['load']) || empty($asset['type'])) {
                continue;
            }
            $this->enqueue_or_register_asset($asset);
            $this->enqueued_assets[] = $asset['handle'];
        }
    }
    /**
     * Add the assets from the child classes
     *
     * @throws PluginException
     */
    public function setup_assets()
    {
        $this->add_assets($this->assets());
    }
    /**
     * Add assets
     *
     * @param array $assets
     *
     * @throws PluginException
     */
    public function add_assets(array $assets)
    {
        foreach ($assets as $asset) {
            $this->add_asset($asset);
        }
    }
    /**
     * Add a single asset
     *
     * @param array $asset
     */
    public function add_asset(array $asset)
    {
        $asset = wp_parse_args($asset, $this->get_default_args());
        if (empty($asset['handle'])) {
            throw new PluginException("Asset args have to contain 'handle'.");
        }
        if (empty($asset['file'])) {
            $asset['file'] = $this->asset($asset['handle']);
        }
        if (empty($asset['type'])) {
            $asset['type'] = $this->get_file_type($asset['file']);
        }
        if ($asset['type']) {
            $this->assets[] = $asset;
        }
    }
    /**
     * Get default Asset args
     *
     * @return array
     */
    public function get_default_args()
    {
        return array(
            'handle' => '',
            // Asset handle
            'file' => '',
            // Asset file. If empty, we'll try to find the file by handle in assets-manifest.json
            'in_footer' => \true,
            // Load in footer, applies to JS only
            'localize' => \false,
            // Localize script, applies to JS only
            'version' => '',
            // Asset version
            'deps' => array(),
            // Dependencies
            'preload' => \false,
            // Add a preload tag to head, can be true / false
            'enqueue' => \true,
            // Set to false to register the asset only to be printed anywhere with print_assets()
            'load' => \true,
            // Use on the current request. If false, the script won't be enqueued or registered
            'type' => '',
        );
    }
    /**
     * Gets asset URL from assets-manifest.json
     *
     * @param $file
     * @param boolean $absolute
     *
     * @return string
     */
    public function asset($file, $absolute = \true) : ?string
    {
        if (\preg_match('/^https?:\\/\\//', $file) || \preg_match('/^\\//', $file)) {
            return $file;
        }
        if (\file_exists($this->plugin->get_asset_path($file))) {
            return $absolute ? $this->plugin->get_asset_url($file) : $file;
        }
        return null;
    }
    /**
     * Get filetype from filename
     *
     * @param $filename
     *
     * @return boolean|string
     */
    public function get_file_type($filename)
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
                $file_type = \false;
        }
        return $file_type;
    }
    public abstract function assets() : array;
    /**
     * Check if the asset has been enqueued already
     *
     * @param $handle
     *
     * @return boolean
     */
    public function is_asset_enqueued($handle)
    {
        return \in_array($handle, $this->enqueued_assets);
    }
    /**
     * @param array $asset
     */
    private function enqueue_or_register_asset(array $asset)
    {
        $preloading_styles_enabled = $this->preloading_styles_enabled();
        if ($asset['type'] === 'script') {
            if (isset($asset['enqueue']) && $asset['enqueue']) {
                wp_enqueue_script($asset['handle'], $asset['file'], empty($asset['deps']) ? array() : $asset['deps'], empty($asset['version']) ? null : $asset['version'], empty($asset['in_footer']) ? null : $asset['in_footer']);
            } else {
                wp_register_script($asset['handle'], $asset['file'], empty($asset['deps']) ? array() : $asset['deps'], empty($asset['version']) ? null : $asset['version'], empty($asset['in_footer']) ? null : $asset['in_footer']);
            }
            if (isset($asset['localize']) && $asset['localize']) {
                foreach ($asset['localize'] as $object_name => $args) {
                    wp_localize_script($asset['handle'], $object_name, $args);
                }
            }
        } elseif ($asset['type'] === 'style') {
            if (!$preloading_styles_enabled || $asset['enqueue']) {
                wp_enqueue_style($asset['handle'], $asset['file'], $asset['deps'], $asset['version']);
            } else {
                wp_register_style($asset['handle'], $asset['file'], $asset['deps'], $asset['version']);
                wp_style_add_data($asset['handle'], 'precache', \true);
            }
        }
    }
    /**
     * Determines whether to preload stylesheets and inject their link tags directly within the page content.
     * Using this technique generally improves performance, however may not be preferred under certain circumstances.
     * For example, since AMP will include all style rules directly in the head, it must not be used in that context.
     * By default, this method returns true unless the page is being served in AMP. The
     * {@see 'wp_rig_preloading_styles_enabled'} filter can be used to tweak the return value.
     *
     * @return boolean True if preloading stylesheets and injecting them is enabled, false otherwise.
     */
    protected function preloading_styles_enabled()
    {
        /*
         * Filters whether to preload stylesheets and inject their link tags within the page content.
         *
         * @param bool $preloading_styles_enabled Whether preloading stylesheets and injecting them is enabled.
         */
        return apply_filters($this->plugin->safe_slug . '_preloading_styles_enabled', \true);
    }
    /**
     * Preloads in-body stylesheets depending on what templates are being used.
     * Only stylesheets that have a 'preload_callback' provided will be considered. If that callback evaluates to true
     * for the current request, the stylesheet will be preloaded.
     * Preloading is disabled when AMP is active, as AMP injects the stylesheets inline.
     *
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Preloading_content
     */
    public function preload_styles()
    {
        // If preloading styles is disabled, return early.
        if (!$this->preloading_styles_enabled()) {
            return;
        }
        foreach ($this->get_styles() as $asset) {
            // Skip if no preload callback provided.
            if (!$asset['preload'] || !$asset['load']) {
                continue;
            }
            $handle = $asset['handle'];
            $wp_styles = wp_styles();
            $preload_uri = $wp_styles->registered[$handle]->src . '?ver=' . $wp_styles->registered[$handle]->ver;
            \printf('<link rel="preload" id="%s-preload" href="%s" as="style">', esc_attr($handle), esc_url($preload_uri));
            echo "\n";
        }
    }
    /**
     * Get styles from the assets array
     *
     * @return array
     */
    private function get_styles()
    {
        return \array_filter($this->assets, function ($asset) {
            return isset($asset['type']) && $asset['type'] === 'style';
        });
    }
    /**
     * Prints stylesheet link tags directly.
     * This should be used for stylesheets that aren't global and thus should only be loaded if the HTML markup
     * they are responsible for is actually present. Template parts should use this method when the related markup
     * requires a specific stylesheet to be loaded. If preloading stylesheets is disabled, this method will not do
     * anything.
     * If the `<link>` tag for a given stylesheet has already been printed, it will be skipped.
     *
     * @param array $handles
     */
    public function print_assets(array $handles)
    {
        // If preloading styles is disabled (and thus they have already been enqueued), return early.
        if (!$this->preloading_styles_enabled()) {
            return;
        }
        if (empty($handles)) {
            return;
        }
        $handles = \array_filter($handles, function ($handle) {
            return !\in_array($handle, $this->printed_assets);
        });
        $assets = \array_filter($this->assets, function ($asset) use($handles) {
            return \in_array($asset['handle'], $handles) && $asset['preload'];
        });
        foreach ($assets as $asset) {
            $this->printed_assets[] = $asset['handle'];
        }
        wp_print_styles($handles);
    }
    /**
     * @return mixed
     */
    public function get_printed_assets()
    {
        return $this->printed_assets;
    }
}
