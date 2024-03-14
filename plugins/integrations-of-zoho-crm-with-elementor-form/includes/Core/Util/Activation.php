<?php
namespace FormInteg\IZCRMEF\Core\Util;

use FormInteg\IZCRMEF\Core\Database\DB;

/**
 * Class handling plugin activation.
 *
 * @since 1.0.0
 */
final class Activation
{
    public function activate()
    {
        add_action('izcrmef_activation', [$this, 'install']);
    }

    public function install()
    {
        $this->installAsSingleSite();
    }

    public function installAsSingleSite()
    {
        $installed = get_option('izcrmef_installed');
        if ($installed) {
            $oldVersion = get_option('izcrmef_version');
        }
        if (!$installed || version_compare($oldVersion, IZCRMEF_VERSION, '!=')) {
            DB::migrate();
            update_option('izcrmef_installed', time());
        }
        update_option('izcrmef_version', IZCRMEF_VERSION);
    }
}
