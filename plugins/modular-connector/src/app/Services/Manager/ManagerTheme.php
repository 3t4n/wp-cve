<?php

namespace Modular\Connector\Services\Manager;

use Modular\ConnectorDependencies\Illuminate\Support\Collection;

/**
 * Handles all functionality related to WordPress Themes.
 */
class ManagerTheme extends AbstractManager
{
    const THEMES = 'themes';

    /**
     * Checks for available updates to themes based on the latest versions hosted on WordPress.org.
     *
     * @return void
     */
    protected function checkForUpdates()
    {
        @wp_update_themes();
    }

    /**
     * Returns a list with the installed themes in the webpage, including the new version if available.
     *
     * @return \Modular\ConnectorDependencies\Illuminate\Support\Collection
     */
    public function all()
    {
        $this->include();
        $this->checkForUpdates();

        if (empty($GLOBALS['wp_theme_directories'])) {
            register_theme_directory(get_theme_root());
        }

        $updatableThemes = $this->getItemsToUpdate(ManagerTheme::THEMES);
        $installedThemes = Collection::make(wp_get_themes());

        return $this->map('theme', $installedThemes, $updatableThemes);
    }

    /**
     * Makes a bulk upgrade of the provided $themes to the most recent version. Returns a list of plugins basenames
     * and a 'true' value if they are in the most recent version.
     *
     * @param array $themes
     * @return array[]|false
     */
    public function upgrade(array $themes = [])
    {
        $this->includeUpgrader();

        if (empty($GLOBALS['wp_filesystem'])) {
            WP_Filesystem();
        }

        $skin = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Theme_Upgrader($skin);

        $response = @$upgrader->bulk_upgrade($themes);

        $this->checkForUpdates();

        return $this->parseBulkUpgradeResponse($themes, $response);
    }
}
