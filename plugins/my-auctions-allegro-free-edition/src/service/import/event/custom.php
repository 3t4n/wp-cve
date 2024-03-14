<?php
declare(strict_types=1);

require_once(GJMAA_PATH_CODE.'/service/import/event.php');

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined( 'ABSPATH' ) or die;

class GJMAA_Service_Import_Event_Custom extends GJMAA_Service_Import_Event
{
    public function getTypes(): array
    {
        /** @var GJMAA_Source_Allegro_Offer_Event_Type $source */
        $source = $this->getEventTypeSource();

        return [
            $source::OFFER_CHANGED
        ];
    }

    public function processEvents($result, $settingsId)
    {
        if(empty($result)) {
            return;
        }

        /** @var GJMAA_Source_Allegro_Offer_Event_Type $source */
        $source = $this->getEventTypeSource();

        foreach($result as $event){
            $this->setLastEvent($event['id']);

            if($event['type'] != $source::OFFER_CHANGED) {
                continue;
            }

            try {
                $this->processOfferChange($event, $settingsId);
            } catch (Throwable $exception) {
                error_log(sprintf('[%s] error during import event %d with error %s', 'IMPORT PRICE EVENT', $event['id'], $exception->getMessage()));
            }
        }
    }

    public function processOfferChange(array $event, $settingsId)
    {
        $auctionId = $event['offer']['id'];
        if($this->isProcessedAuction($auctionId)) {
            return;
        }

        /** @var GJMAA_Model_Auctions $auctionsModel */
        $auctionsModel = GJMAA::getModel('auctions');
        if(!$auctionsModel->isExistAuctionOnTheList($auctionId)) {
            $this->addToProcessedAuctions($auctionId);
            return;
        }

        $auction = $this->getAuctionDetail($auctionId, $settingsId);

        $auctionsModel->updateWoocommerceFieldsAuctionsToUpdate($auction['name'], json_encode($auction,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_HEX_APOS), $auctionId);
        $this->addToProcessedAuctions($auctionId);
    }
}