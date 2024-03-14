<?php
declare(strict_types=1);

require_once(GJMAA_PATH_CODE.'/service/import/event.php');

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined( 'ABSPATH' ) or die;

class GJMAA_Service_Import_Event_Stock extends GJMAA_Service_Import_Event
{
    public function getTypes(): array
    {
        /** @var GJMAA_Source_Allegro_Offer_Event_Type $source */
        $source = $this->getEventTypeSource();

        return [
            $source::OFFER_STOCK_CHANGED
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

            if($event['type'] != $source::OFFER_STOCK_CHANGED) {
                continue;
            }

            try {
                $this->processStockChange($event, $settingsId);
            } catch (Throwable $exception) {
                error_log(sprintf('[%s] error during import event %d with error %s', 'IMPORT STOCK EVENT', $event['id'], $exception->getMessage()));
            }
        }
    }

    public function processStockChange(array $event, $settingsId)
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

        $stockLevel = $auction['stock']['available'] ?? 0;

        $auctionsModel->updateStockAuctionsToUpdate($stockLevel, $auctionId);
        $this->addToProcessedAuctions($auctionId);
    }
}