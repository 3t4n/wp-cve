<?php

namespace Modular\Connector\Services\Manager;

use Modular\ConnectorDependencies\Illuminate\Support\Collection;

/**
 * Handles all functionality related to WordPress Plugins.
 */
class ManagerPlugin extends AbstractManager
{
    const PLUGINS = 'plugins';

    /**
     * Checks for available updates to plugins based on the latest versions hosted on WordPress.org.
     *
     * @return void
     */
    protected function checkForUpdates()
    {
        @wp_update_plugins();
    }

    /**
     * Returns a list with the installed plugins in the webpage, including the new version if available.
     *
     * @return \Modular\ConnectorDependencies\Illuminate\Support\Collection
     */
    public function all()
    {
        $this->include();
        $this->checkForUpdates();

        $updatablePlugins = $this->getItemsToUpdate(ManagerPlugin::PLUGINS);
        $plugins = Collection::make(get_plugins());

        return $this->map('plugin', $plugins, $updatablePlugins);
    }

    /**
     * Makes a bulk upgrade of the provided $plugins to the most recent version. Returns a list of plugins basenames
     * and a 'true' value if they are in the most recent version.
     *
     * @param array $items
     * @return array|false
     */
    public function upgrade(array $items = [])
    {
        $this->includeUpgrader();

        if (empty($GLOBALS['wp_filesystem'])) {
            WP_Filesystem();
        }

        $skin = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader($skin);

        $response = @$upgrader->bulk_upgrade($items);

        $this->checkForUpdates();

        return $this->parseBulkUpgradeResponse($items, $response);
    }
}
