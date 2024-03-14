<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

require_once GJMAA_PATH_CODE . 'cron/abstract_cron.php';

class GJMAA_Cron_Import_Events extends GJMAA_Cron_Abstract
{
    public function getCode(): string
    {
        return 'gjmaa_cron_import_events';
    }

    public function runJob() : void
    {
        /** @var GJMAA_Model_Settings $settings */
        $settings = GJMAA::getModel('settings');

        $filters = [
            'WHERE' => 'setting_site = \'1\' AND setting_client_token IS NOT NULL'
        ];

        $settingsToSync = $settings->getAllBySearch($filters);
        if (empty($settingsToSync)) {
            return;
        }

        error_log(sprintf('[%s] Count of accounts to sync events: %d', 'CRON EVENTS IMPORT', count($settingsToSync)));

        foreach ($settingsToSync as $setting) {
            try {
                $this->processStatusEvents($setting);
                $this->processStockEvents($setting);
                $this->processPriceEvents($setting);
                $this->processOfferChange($setting);
            } catch (Exception $exception) {
                error_log(sprintf('Problem with synchronize events %s on account: %s', $exception->getMessage(), $setting['setting_name']));
            }
        }
    }

    public function processStatusEvents($setting)
    {
        $this->processEvents('status', $setting);
    }

    public function processStockEvents($setting)
    {
        $this->processEvents('stock', $setting);
    }

    public function processPriceEvents($setting)
    {
        $this->processEvents('price', $setting);
    }

    public function processOfferChange($setting)
    {
        $this->processEvents('custom', $setting);
    }

    public function processEvents($type, $setting)
    {
        $lastEvent = $setting['setting_last_'.$type.'_event'];
        $messagePattern = '[%s] Get ' . $type . ' events from last 24h';
        if(!$lastEvent) {
            error_log(sprintf($messagePattern, 'CRON EVENTS IMPORT'));
        } else {
            $messagePattern .= ' starts from %s';
            error_log(sprintf($messagePattern, 'CRON EVENTS IMPORT', $lastEvent));
        }

        /** @var GJMAA_Service_Import_Event $eventService */
        $eventService = GJMAA::getService('import_event_'.$type);
        $eventService->resetProcessedAuction();
        $eventService->setLastEvent($lastEvent);
        $lastEvent = $eventService->execute($setting['setting_id']);

        $this->updateLastEvent($setting['setting_id'], 'setting_last_'.$type.'_event', $lastEvent);
    }

    public function updateLastEvent($settingId, $field, $value)
    {
        /** @var GJMAA_Model_Settings $settings */
        $settings = GJMAA::getModel('settings');
        $settings->updateAttribute($settingId, $field, $value);
    }

    public static function run()
    {
        (new self())->execute();
    }
}