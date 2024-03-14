<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

require_once GJMAA_PATH_CODE . 'cron/abstract_cron.php';

class GJMAA_Cron_Woocommerce_Stock extends GJMAA_Cron_Abstract
{
    public function getCode(): string
    {
        return 'gjmaa_cron_woocommerce_stock';
    }

    public function runJob(): void
    {
        error_log(sprintf('[%s] Cron run', 'WOOCOMMERCE STOCK'));
        error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE STOCK', $this->convert_filesize(memory_get_usage(true))));

        /** @var GJMAA_Service_Woocommerce $wooCommerceService */
        $wooCommerceService = GJMAA::getService('woocommerce');
        if ( ! $wooCommerceService->isEnabled()) {
            return;
        }

        /** @var GJMAA_Model_Profiles $profileModel */
        $profileModel          = GJMAA::getModel('profiles');
        $wooCommerceProfileIds = $profileModel->getProfilesWithSyncStock();

        if (empty($wooCommerceProfileIds)) {
            return;
        }

        $profilesToUpdate = 10;

        foreach ($wooCommerceProfileIds as $profileId) {
            if ( ! $this->validateForExecute($profileId)) {
                error_log(sprintf('[%s] Skip profile %d', 'WOOCOMMERCE STOCK', $profileId));
                error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE STOCK', $this->convert_filesize(memory_get_usage(true))));
                continue;
            }

            error_log(sprintf('[%s] Run profile %d', 'WOOCOMMERCE STOCK', $profileId));
            error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE STOCK', $this->convert_filesize(memory_get_usage(true))));

            $filters = [
                'WHERE' => sprintf('auction_profile_id = %d AND auction_woocommerce_id != 0 AND auction_update_woocommerce_stock = 1', $profileId)
            ];

            /** @var GJMAA_Model_Auctions $auctionsModel */
            $auctionsModel = GJMAA::getModel('auctions');
            $auctions      = $auctionsModel->getAllBySearch($filters);

            if (empty($auctions)) {
                $this->updateLastSync($profileId);
                continue;
            }

            error_log(sprintf('[%s] Count of auctions to update %d for profile %d', 'WOOCOMMERCE STOCK', count($auctions), $profileId));
            error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE STOCK', $this->convert_filesize(memory_get_usage(true))));

            $stockUpdateData = [];

            $sourceOfferStatus = GJMAA::getSource('allegro_offerstatus');

            foreach ($auctions as $auction) {
                $isOutOfStock                              = ( ! is_null($auction['auction_time']) && strtotime($auction['auction_time']) < time()) || $auction['auction_status'] != $sourceOfferStatus::ACTIVE;
                $stockUpdateData[ $auction['auction_id'] ] = [
                    'quantity'    => $auction['auction_quantity'] > 0 && ! $isOutOfStock ? $auction['auction_quantity'] : 0,
                    'is_in_stock' => ! $isOutOfStock,
                    'profile_id'  => $auction['auction_profile_id'],
                    'product_id'  => $auction['auction_woocommerce_id']
                ];
            }

            error_log(sprintf('[%s] Updating profile %d', 'WOOCOMMERCE STOCK', $profileId));
            error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE STOCK', $this->convert_filesize(memory_get_usage(true))));

            $wooCommerceService->updateStock($stockUpdateData);

            $auctionIds = array_keys($stockUpdateData);

            $auctionsModel->updateStockAuctionsForProfileToBeSynced($auctionIds, $profileId);

            error_log(sprintf('[%s] Profile %d updated', 'WOOCOMMERCE STOCK', $profileId));
            error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE STOCK', $this->convert_filesize(memory_get_usage(true))));

            $this->updateLastSync($profileId);

            if ($profilesToUpdate <= 0) {
                break;
            }

            $profilesToUpdate--;
        }

        error_log(sprintf('[%s] Cron end', 'WOOCOMMERCE STOCK'));
        error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE STOCK', $this->convert_filesize(memory_get_usage(true))));
    }

    public function validateForExecute($profileId)
    {
        $profileModel = GJMAA::getModel('profiles');
        $profile      = $profileModel->load($profileId);

        $lastStockUpdate = $profile->getData('profile_sync_stock_date');

        return ! $lastStockUpdate || (strtotime($lastStockUpdate) <= (time() - 120));
    }

    public function updateLastSync($profileId)
    {
        $profileModel = GJMAA::getModel('profiles');
        $profile      = $profileModel->load($profileId);

        $profile->updateAttribute($profileId, 'profile_sync_stock_date', date('Y-m-d H:i:s'));
    }

    public static function run()
    {
        (new self())->execute();
    }
}