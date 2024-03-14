<?php

namespace Modular\Connector\Services\Manager;

use Modular\ConnectorDependencies\Illuminate\Support\Collection;
use Modular\ConnectorDependencies\Illuminate\Support\Str;

/**
 * Handles all functionality related to WordPress Core.
 */
class ManagerCore extends AbstractManager
{
    /**
     * Checks WordPress version against the newest version.
     *
     * @return void
     */
    protected function checkForUpdates()
    {
        @wp_version_check();
    }

    /**
     * @return string
     */
    private function locale()
    {
        $locale = @get_locale();

        return $locale ?: ($GLOBALS['wp_local_package'] ?? null);
    }

    /**
     * @return string
     */
    private function version()
    {
        return $GLOBALS['wp_version'] ?? null;
    }

    /**
     * Returns the current WordPress version and the available updates.
     *
     * @return array
     */
    public function get()
    {
        $this->include();

        $coreUpdate = $this->getLatestUpdate();
        $newVersion = $coreUpdate->version ?? null;

        return [
            'basename' => 'core',
            'name' => 'WordPress',
            'locale' => $this->locale(),
            'version' => $this->version(),
            'new_version' => $newVersion,
            'requires_php' => $GLOBALS['required_php_version'] ?? null,
            'mysql_version' => $GLOBALS['required_mysql_version'] ?? null,
            'status' => 'active',
        ];
    }

    /**
     * Finds the available update for WordPress core.
     *
     * @return object|false The core update offering on success, false on failure.
     */
    private function getLatestUpdate()
    {
        if (!function_exists('find_core_update')) {
            include_once ABSPATH . '/wp-admin/includes/update.php';
        }

        $this->checkForUpdates();

        $checker = get_site_transient('update_core');

        if (!isset($checker->updates) || !is_array($checker->updates)) {
            return null;
        }

        $checker = Collection::make($checker->updates);

        return $checker->filter(function ($update) {
            return $update->locale === $this->locale() && Str::lower($update->response) === 'upgrade';
        })
            ->first();
    }

    /**
     * Upgrades the WordPress core to the latest available version.
     *
     * @return bool|array
     * @throws \Exception
     */
    public function upgrade($items = [])
    {
        // Allow core updates
        add_filter('auto_update_core', function () {
            return true;
        }, PHP_INT_MAX);

        add_filter('allow_major_auto_core_updates', function () {
            return true;
        }, PHP_INT_MAX);

        add_filter('allow_minor_auto_core_updates', function () {
            return true;
        }, PHP_INT_MAX);

        $this->includeUpgrader();

        $skin = new \WP_Ajax_Upgrader_Skin();
        $core = new \Core_Upgrader($skin);

        $result = @$core->upgrade($this->getLatestUpdate());

        $this->checkForUpdates();

        return $this->parseUpgradeResponse('core', $result);
    }
}
