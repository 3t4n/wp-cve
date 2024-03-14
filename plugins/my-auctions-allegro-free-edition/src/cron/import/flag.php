<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

require_once GJMAA_PATH_CODE . 'cron/abstract_cron.php';

class GJMAA_Cron_Import_Flag extends GJMAA_Cron_Abstract
{
    public function getCode(): string
    {
        return 'gjmaa_cron_import_flag';
    }

    public function runJob(): void
    {
        /** @var GJMAA_Model_Profiles $profileModel */
        $profileModel = GJMAA::getModel('profiles');
        $profileIds   = $profileModel->getAllIds();

        if (empty($profileIds)) {
            return;
        }

        foreach ($profileIds as $profileId) {
            $this->flagFullImport($profileId);
        }
    }

    public function flagFullImport($profileId)
    {
        GJMAA::getModel('profiles')->updateAttribute($profileId, 'profile_import_all', 1);
    }

    public static function run()
    {
        (new self())->execute();
    }
}