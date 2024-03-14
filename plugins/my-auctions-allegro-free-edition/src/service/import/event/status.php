<?php
declare(strict_types=1);

require_once(GJMAA_PATH_CODE.'/service/import/event.php');

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined( 'ABSPATH' ) or die;

class GJMAA_Service_Import_Event_Status extends GJMAA_Service_Import_Event
{
    public function getTypes(): array
    {
        /** @var GJMAA_Source_Allegro_Offer_Event_Type $source */
        $source = $this->getEventTypeSource();

        return [
            $source::OFFER_ENDED,
            $source::OFFER_ARCHIVED,
            $source::OFFER_ACTIVATED
        ];
    }

    public function processEvents($result, $settingsId)
    {
        if(empty($result)) {
            return;
        }

        /** @var GJMAA_Source_Allegro_Offer_Event_Type $source */
        $source = $this->getEventTypeSource();

        $lastEventId = null;

        foreach($result as $event)
        {
            $this->setLastEvent($event['id']);

            try {
                if (in_array($event['type'], [$source::OFFER_ENDED, $source::OFFER_ARCHIVED])) {
                    $this->processEndedEvent($event);
                    continue;
                }

                if ($event['type'] == $source::OFFER_ACTIVATED) {
                    $this->processActivatedEvent($event, $settingsId);
                    continue;
                }
            } catch (Throwable $exception) {
                error_log(sprintf('[%s] error during import event %d with error %s', 'IMPORT STATUS EVENT', $event['id'], $exception->getMessage()));
            }
        }
    }

    public function processEndedEvent(array $event)
    {
        $auctionId = $event['offer']['id'];

        /** @var GJMAA_Model_Auctions $auctionsModel */
        $auctionsModel = GJMAA::getModel('auctions');
        if(!$auctionsModel->isExistAuctionOnTheList($auctionId)) {
            return;
        }

        $auctionsModel->updateAuctionToBeEnded($auctionId);
    }

    public function processActivatedEvent(array $event, $settingsId)
    {
        $auctionId = $event['offer']['id'];

        /** @var GJMAA_Model_Auctions $auctionsModel */
        $auctionsModel = GJMAA::getModel('auctions');
        if($auctionsModel->isExistAuctionOnTheList($auctionId)) {
            $auctionsModel->updateAuctionToBeActive($auctionId);
            return;
        }

        /** @var GJMAA_Model_Profiles $profilesModel */
        $profilesModel = GJMAA::getModel('profiles');
        $profiles = $profilesModel->getProfileIdsBySettingId($settingsId);

        if(empty($profiles)) {
            return;
        }

        foreach($profiles as $profileId) {
            $profilesModel->updateAttribute($profileId, 'profile_import_all', 1);
        }
    }
}