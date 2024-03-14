<?php
declare(strict_types=1);

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

require_once GJMAA_PATH_CODE . 'cron/abstract_cron.php';

class GJMAA_Cron_Import_Auctions extends GJMAA_Cron_Abstract
{
    public function getCode(): string
    {
        return 'gjmaa_cron_import_auctions';
    }

    public function runJob(): void
    {
        /** @var GJMAA_Model_Profiles $profiles */
        $profiles = GJMAA::getModel('profiles');

        $filters = [
            'WHERE' => 'profile_import_all = 1 AND profile_cron_sync = 1 AND profile_errors <= 5'
        ];

        $allProfilesToSync = $profiles->getAllBySearch($filters);

        if (empty($allProfilesToSync)) {
            return;
        }

        error_log(sprintf('[%s] Count of profiles to update: %d', 'CRON AUCTION IMPORT', count($allProfilesToSync)));
        error_log(sprintf('[%s] Memory usage: %d', 'CRON AUCTION IMPORT', $this->convert_filesize(memory_get_usage(true))));

        /** @var GJMAA_Helper_Import $helperImport */
        $helperImport = GJMAA::getHelper('import');

        $i = 0;
        foreach ($allProfilesToSync as $profile) {
            if ($profile['profile_import_lock']) {
                error_log(sprintf('[%s] Skip profile ID: %d', 'CRON AUCTION IMPORT', $profile['profile_id']));
                continue;
            }

            $profileId = $profile['profile_id'];

            $this->lockProfile($profileId);
            error_log(sprintf('[%s] Run profile ID: %d', 'CRON AUCTION IMPORT', $profile['profile_id']));
            error_log(sprintf('[%s] Memory usage: %d', 'CRON AUCTION IMPORT', $this->convert_filesize(memory_get_usage(true))));
            do {
                try {
                    $response = $helperImport->runImportByProfileId($profileId, 'cron');
                    if ((isset($response['progress_step']) && $response['progress_step'] == 100) || $response['all_auctions'] == 0) {
                        error_log(sprintf('[%s] After profile ID: %d', 'CRON AUCTION IMPORT', $profileId));
                        error_log(sprintf('[%s] Memory usage: %d', 'CRON AUCTION IMPORT', $this->convert_filesize(memory_get_usage(true))));
                        break;
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    break;
                }
                $i++;
                sleep(1);
            } while ($i < 150);

            $profiles->updateAttribute($profileId, 'profile_import_all', 0);
            $this->unlockProfile($profileId);
        }
    }

    public function lockProfile($profileId)
    {
        GJMAA::getModel('profiles')->updateAttribute($profileId, 'profile_import_lock', 1);
    }

    public function unlockProfile($profileId)
    {
        GJMAA::getModel('profiles')->updateAttribute($profileId, 'profile_import_lock', 0);
    }

    public static function run()
    {
        (new self())->execute();
    }
}