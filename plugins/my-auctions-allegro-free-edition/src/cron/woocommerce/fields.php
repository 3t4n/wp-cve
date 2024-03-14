<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

require_once GJMAA_PATH_CODE . 'cron/abstract_cron.php';

class GJMAA_Cron_Woocommerce_Fields extends GJMAA_Cron_Abstract
{
    private $profiles = [];

    public function getCode(): string
    {
        return 'gjmaa_cron_woocommerce_fields';
    }

    public function runJob(): void
    {
        error_log(sprintf('[%s] Cron run', 'WOOCOMMERCE FIELDS'));
        error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE FIELDS', $this->convert_filesize(memory_get_usage(true))));

        /** @var GJMAA_Service_Woocommerce $wooCommerceService */
        $wooCommerceService = GJMAA::getService('woocommerce');
        if ( ! $wooCommerceService->isEnabled()) {
            return;
        }

        /** @var GJMAA_Model_Profiles $profileModel */
        $profileModel          = GJMAA::getModel('profiles');
        $wooCommerceProfileIds = $profileModel->getProfilesWithSyncWooCommerceFields();

        if (empty($wooCommerceProfileIds)) {
            return;
        }

        $profilesToUpdate = 10;

        /** @var GJMAA_Service_Import_Auctions $importAuctionService */
        $importAuctionService = GJMAA::getService('import_auctions');

        foreach ($wooCommerceProfileIds as $profileId) {
            if ( ! $this->validateForExecute($profileId)) {
                error_log(sprintf('[%s] Skip profile %d', 'WOOCOMMERCE FIELDS', $profileId));
                error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE FIELDS', $this->convert_filesize(memory_get_usage(true))));
                continue;
            }

            error_log(sprintf('[%s] Run profile %d', 'WOOCOMMERCE FIELDS', $profileId));
            error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE FIELDS', $this->convert_filesize(memory_get_usage(true))));

            $filters = [
                'WHERE' => sprintf('auction_profile_id = %d AND auction_woocommerce_id != 0 AND auction_update_woocommerce_fields = 1', $profileId)
            ];

            /** @var GJMAA_Model_Auctions $auctionsModel */
            $auctionsModel = GJMAA::getModel('auctions');
            $auctions      = $auctionsModel->getAllBySearch($filters);

            if (empty($auctions)) {
                $this->updateLastSync($profileId);
                continue;
            }

            error_log(sprintf('[%s] Count of auctions to update %d for profile %d', 'WOOCOMMERCE FIELDS', count($auctions), $profileId));
            error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE FIELDS', $this->convert_filesize(memory_get_usage(true))));

            $dataToSync = $this->getDataToSync($profileId);
            if (empty($dataToSync)) {
                $this->updateLastSync($profileId);
                continue;
            }

            $updatedAuctions = [];
            foreach ($auctions as $auction) {
                $productId = $auction['auction_woocommerce_id'];
                $product   = get_post($productId);
                if ( ! $product->ID) {
                    continue;
                }

                $auctionDetails = json_decode($auction['auction_details'], true);
                if (empty($auctionDetails)) {
                    continue;
                }

                $product = $importAuctionService->prepareProduct($auctionDetails);
                $wooCommerceService->setProfile($this->getProfile($profileId));
                $wooCommerceService->addProduct($product, true);

                $updatedAuctions[] = $auction['auction_id'];
            }

            $auctionIds = array_values($updatedAuctions);
			if(!empty($auctionIds)) {
				$auctionsModel->updateWooCommerceFieldsAuctionsForProfileToBeSynced($auctionIds, $profileId);
			}

            error_log(sprintf('[%s] Profile %d updated', 'WOOCOMMERCE FIELDS', $profileId));
            error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE FIELDS', $this->convert_filesize(memory_get_usage(true))));

            $this->updateLastSync($profileId);

            if ($profilesToUpdate <= 0) {
                break;
            }

            $profilesToUpdate--;
        }

        error_log(sprintf('[%s] Cron end', 'WOOCOMMERCE FIELDS'));
        error_log(sprintf('[%s] Memory %s', 'WOOCOMMERCE FIELDS', $this->convert_filesize(memory_get_usage(true))));
    }

    /**
     * @param $profileId
     *
     * @return ?GJMAA_Model_Profiles
     */
    public function getProfile($profileId)
    {
        if ( ! isset($this->profiles[ $profileId ])) {
            $profileModel                 = GJMAA::getModel('profiles');
            $this->profiles[ $profileId ] = $profileModel->load($profileId);
        }

        return $this->profiles[ $profileId ];
    }

    public function validateForExecute($profileId)
    {
        $profile = $this->getProfile($profileId);

        $lastUpdate = $profile->getData('profile_sync_woocommerce_fields_date');

        return ! $lastUpdate || (strtotime($lastUpdate) <= (time() - 120));
    }

    public function getDataToSync($profileId)
    {
        $profile = $this->getProfile($profileId);

        $syncData = $profile->getData('profile_sync_woocommerce_fields');
        if (is_string($syncData)) {
            $syncData = explode(',', $syncData);
        }

        if ( ! is_array($syncData)) {
            $syncData = [];
        }

        return $syncData;
    }

    public function updateLastSync($profileId)
    {
        $profile = $this->getProfile($profileId);

        $profile->updateAttribute($profileId, 'profile_sync_woocommerce_fields_date', date('Y-m-d H:i:s'));
    }

    public static function run()
    {
        (new self())->execute();
    }
}