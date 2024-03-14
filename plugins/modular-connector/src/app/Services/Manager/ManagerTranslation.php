<?php

namespace Modular\Connector\Services\Manager;

/**
 * Handles all functionality related to WordPress translations.
 */
class ManagerTranslation extends AbstractManager
{
    protected function checkForUpdates()
    {
        // For translations isn't necessary to check for updates.
    }

    /**
     * Returns a list with the installed plugins in the webpage, including the new version if available.
     *
     * @return array
     */
    public function get()
    {
        $this->include();

        $transients = ['update_core', 'update_plugins', 'update_themes'];

        $translations = false;

        foreach ($transients as $transient) {
            $transient = get_site_transient($transient);

            if (!empty($transient->translations)) {
                $translations = true;
                break;
            }
        }

        return !empty($translations) ? [
            'basename' => 'translations',
            'name' => __('New translations are available.'),
            'version' => '0.0.0',
            'new_version' => '1.0.0',
            'status' => 'active',
        ] : [];
    }

    /**
     * Upgrades all available translations. It returns a list of the upgraded translations, a message if no available
     * translations, or an error if something bad happened.
     *
     * @return array|bool
     * @throws \Exception
     */
    public function upgrade(array $items = [])
    {
        $this->includeUpgrader();

        $skin = new \WP_Ajax_Upgrader_Skin([]);
        $upgrader = new \Language_Pack_Upgrader($skin);

        $result = @$upgrader->bulk_upgrade();

        return $this->parseUpgradeResponse('translations', $result);
    }
}
