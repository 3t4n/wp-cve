<?php

namespace AOP\App;

use AOP\Lib\Illuminate\Support\Collection;
use AOP\App\Admin\AdminPages\SubpageMaster;

final class PluginMetaLinks
{
    use Singleton;

    private function __construct()
    {
        add_filter('plugin_row_meta', function ($links, $pluginFileName, $pluginData, $status) {
            return $this->pluginDescriptionRow($links, $pluginFileName, $pluginData, $status);
        }, 10, 4);

        add_filter('plugin_action_links', function ($links, $pluginFileName) {
            return $this->pluginActionRow($links, $pluginFileName);
        }, 10, 2);
    }

    /**
     * Action links under the 'Description' column.
     *
     * @param array  $links
     * @param string $pluginFileName
     * @param array  $pluginData
     * @param string $status
     *
     * @return array
     */
    private function pluginDescriptionRow(array $links, $pluginFileName, array $pluginData, $status)
    {
        if (!strpos($pluginFileName, Plugin::FILENAME)) {
            return $links;
        }

        $documentationUrl = sprintf('<a href="%s">Documentation</a>', Plugin::DOCS_URL);

        return Collection::make($links)->merge($documentationUrl)->all();
    }

    /**
     * Action links under the 'Plugin' column.
     *
     * @param array  $links
     * @param string $pluginFileName
     *
     * @return array
     */
    private function pluginActionRow(array $links, $pluginFileName)
    {
        if (!strpos($pluginFileName, Plugin::FILENAME)) {
            return $links;
        }

        $settingsUrl = sprintf('<a href="%s">Start</a>', SubpageMaster::url());

        return Collection::make([$settingsUrl])->merge($links)->all();
    }
}
