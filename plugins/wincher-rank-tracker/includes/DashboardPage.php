<?php

namespace Wincher;

/**
 * The DashboardPage class.
 */
class DashboardPage
{
    /**
     * @var Plugin
     */
    protected $plugin;

    /**
     * DashboardPage constructor.
     *
     * @param Plugin $plugin the Plugin instance
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->enqueueAssets();
    }

    /**
     * Returns the name of this page.
     *
     * @return string the page's name
     */
    public function getName()
    {
        return 'dashboard';
    }

    /**
     * Returns an array of built assets to load.
     *
     * @return array the assets to load
     */
    public function getAssets()
    {
        return [
            'dashboard.js',
            'dashboard.css',
        ];
    }

    /**
     * Renders the page contents.
     *
     * @return void
     */
    public function render()
    {
        $slug = Plugin::SLUG;
        $id = "{$slug}-{$this->getName()}-root"; ?>
        <div id="<?php echo $id; ?>"></div>
        <?php
    }

    /**
     * Enqueues the built assets for this page.
     *
     * @return void
     */
    protected function enqueueAssets()
    {
        $prefix = '';
        if (file_exists(WINCHER_PLUGIN_BASE_PATH . 'build')) {
            $prefix = 'build/';
        }
        $manifest = json_decode(file_get_contents(WINCHER_PLUGIN_BASE_PATH . $prefix . 'assets/manifest.json'), true);

        foreach ($this->getAssets() as $asset) {
            if (!isset($manifest[$asset])) {
                continue;
            }

            $url = $manifest[$asset];

            // Resolve the correct URL unless we were given a full URL.
            if (false === strpos($url, 'http://')) {
                $url = WINCHER_PLUGIN_BASE_URL . $prefix . 'assets/' . $url;
            }

            if (false !== strpos($url, '.js')) {
                wp_enqueue_script(Plugin::SLUG . $asset, $url);
            } else {
                wp_enqueue_style(Plugin::SLUG . $asset, $url);
            }
        }
    }
}
