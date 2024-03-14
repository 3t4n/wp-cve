<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

require_once GJMAA_PATH_CODE . 'cron/abstract_cron.php';

class GJMAA_Cron_Import_Release extends GJMAA_Cron_Abstract
{
    public function getCode(): string
    {
        return 'gjmaa_cron_import_release';
    }

    public function runJob(): void
    {
        $profiles    = GJMAA::getModel('profiles');
        $allprofiles = $profiles->getAllLockedProfiles();

        if (empty($allprofiles)) {
            return;
        }

        foreach ($allprofiles as $profileId) {
            $this->releaseLockForProfile($profileId);
        }
    }

    public function releaseLockForProfile($profileId)
    {
        GJMAA::getModel('profiles')->updateAttribute($profileId, 'profile_import_lock', 0);
    }

    public static function run()
    {
        (new self())->execute();
    }
}