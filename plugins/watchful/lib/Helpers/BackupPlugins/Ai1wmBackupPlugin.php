<?php

namespace Watchful\Helpers\BackupPlugins;

use Ai1wm_Backups;
use DateTime;
use Exception;

class Ai1wmBackupPlugin implements BackupPluginInterface
{
    /**
     * @return DateTime | false
     */
    public function get_last_backup_date() {
        if ( ! file_exists(WP_PLUGIN_DIR . '/all-in-one-wp-migration/lib/model/class-ai1wm-backups.php')) {
            return false;
        }
        include_once WP_PLUGIN_DIR.'/all-in-one-wp-migration/lib/model/class-ai1wm-backups.php';
        if (! class_exists('Ai1wm_Backups')) {
            return false;
        }

        try {
            $backups = Ai1wm_Backups::get_files();
            if (empty($backups) || empty($backups[0]['mtime'])) {
                return false;
            }
            return date_create()->setTimestamp($backups[0]['mtime']);
        } catch (Exception $e) {
            return false;
        }
    }

    public function get_backup_list()
    {
        return Ai1wm_Backups::get_files();
    }
}
