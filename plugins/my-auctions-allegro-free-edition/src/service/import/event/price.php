<?php
declare(strict_types=1);

require_once(GJMAA_PATH_CODE.'/service/import/event.php');

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined( 'ABSPATH' ) or die;

class GJMAA_Service_Import_Event_Price extends GJMAA_Service_Import_Event
{

    public const PRICE_BUY_NOW_FORMAT = 'BUY_NOW';

    public const PRICE_BIDDING_FORMAT = 'AUCTION';

    public const PRICE_ADVERTISMENT_FORMAT = 'ADVERTISEMENT';

    public function getTypes(): array
    {
        /** @var GJMAA_Source_Allegro_Offer_Event_Type $source */
        $source = $this->getEventTypeSource();

        return [
            $source::OFFER_PRICE_CHANGED
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

            if($event['type'] != $source::OFFER_PRICE_CHANGED) {
                continue;
            }

            try {
                $this->processPriceChange($event, $settingsId);
            } catch (Throwable $exception) {
                error_log(sprintf('[%s] error during import event %d with error %s', 'IMPORT PRICE EVENT', $event['id'], $exception->getMessage()));
            }
        }
    }

    public function processPriceChange(array $event, $settingsId)
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

        $prices = [
            'auction_price'      => $auction['sellingMode']['price']['amount'],
            'auction_bid_price'  => $auction['sellingMode']['format'] == self::PRICE_BIDDING_FORMAT ? (
                ! empty( $auction['saleInfo']['currentPrice'] ) ?
                    $auction['saleInfo']['currentPrice']['amount'] : (
                        ! empty( $auction['sellingMode']['minimalPrice']['amount'] ) ?
                            $auction['sellingMode']['minimalPrice']['amount'] :
                            $auction['sellingMode']['startingPrice']['amount']
                )
            ) : 0,
        ];

        $auctionsModel->updatePricesAuctionToUpdate($prices, $auctionId);

        $this->addToProcessedAuctions($auctionId);
    }
}