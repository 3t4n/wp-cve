<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

require_once GJMAA_PATH_CODE . 'cron/abstract_cron.php';

class GJMAA_Cron_Woocommerce_Status extends GJMAA_Cron_Abstract
{
	private $profiles = [];

    public function getCode(): string
    {
        return 'gjmaa_cron_woocommerce_status';
    }

    public function runJob(): void
    {
        error_log(sprintf('[%s] Run cron', 'STATUS WOOCOMMERCE'));
        /** @var GJMAA_Service_Woocommerce $wooCommerceService */
        $wooCommerceService = GJMAA::getService('woocommerce');
        if ( ! $wooCommerceService->isEnabled()) {
            return;
        }

        /** @var GJMAA_Model_Profiles $profileModel */
        $profileModel          = GJMAA::getModel('profiles');
        $wooCommerceProfileIds = $profileModel->getAllIds();

        if (empty($wooCommerceProfileIds)) {
            return;
        }

        $profilesToUpdate = 10;

        foreach ($wooCommerceProfileIds as $profileId) {
            if ( ! $this->validateForExecute($profileId)) {
                error_log(sprintf('[%s] Skip profile: %d', 'STATUS WOOCOMMERCE', $profileId));
                continue;
            }

            $filters = [
                'WHERE' => sprintf('auction_profile_id = %d', $profileId)
            ];

            /** @var GJMAA_Model_Auctions $auctionsModel */
            $auctionsModel = GJMAA::getModel('auctions');
            $auctions      = $auctionsModel->getAllBySearch($filters);

            if (empty($auctions)) {
                $this->updateLastSync($profileId);
                error_log(sprintf('[%s] Skip profile: %d', 'STATUS WOOCOMMERCE', $profileId));

                return;
            }

            error_log(sprintf('[%s] Run profile: %d', 'STATUS WOOCOMMERCE', $profileId));
            error_log(sprintf('[%s] Count of auctions %d for profile: %d', 'STATUS WOOCOMMERCE', count($auctions), $profileId));

            foreach ($auctions as $auction) {
                $sku       = $auction['auction_id'];
				$wooCommerceService->setProfile($this->getProfileById($profileId));
                $productId = $wooCommerceService->getProductIdByAuctionId($sku);
                if (0 === $productId && ! $auction['auction_in_woocommerce']) {
                    continue;
                }

                if (0 !== $productId) {
                    $isDeleted = $this->isDeleted($productId);
                    if ($auction['auction_in_woocommerce'] == 1 && ! empty($auction['auction_woocommerce_id']) && ! $isDeleted) {
                        continue;
                    }

                    if ($auction['auction_in_woocommerce'] == 3 && ! empty($auction['auction_woocommerce_id']) && $isDeleted) {
                        continue;
                    }
                }

                $isAuctionInWooCommerce = 0;
                if ($productId !== 0) {
                    if ($isDeleted) {
                        $isAuctionInWooCommerce = 3;
                    } else {
                        $isAuctionInWooCommerce = 1;
                    }
                }

                $auctionsModel->updateAttribute($auction['id'], 'auction_woocommerce_id', $productId);
                $auctionsModel->updateAttribute($auction['id'], 'auction_in_woocommerce', $isAuctionInWooCommerce);
            }

            $this->updateLastSync($profileId);

            if ($profilesToUpdate <= 0) {
                break;
            }

            $profilesToUpdate--;
        }

        error_log(sprintf('[%s] End cron', 'STATUS WOOCOMMERCE'));
    }

    public function validateForExecute($profileId)
    {
        $profileModel = GJMAA::getModel('profiles');
        $profile      = $profileModel->load($profileId);

        $lastStatusUpdate = $profile->getData('profile_sync_status_date');

        return ! $lastStatusUpdate || (strtotime($lastStatusUpdate) <= (time() - 900));
    }

	public function getProfileById($profileId)
	{
		if(!isset($this->profiles[$profileId])) {
			$profileModel = GJMAA::getModel('profiles');
			$profileModel->load($profileId);

			$this->profiles[$profileId] = $profileModel;
		}

		return $this->profiles[$profileId];
	}

    public function updateLastSync($profileId)
    {
        $profileModel = GJMAA::getModel('profiles');
        $profile      = $profileModel->load($profileId);

        $profile->updateAttribute($profileId, 'profile_sync_status_date', date('Y-m-d H:i:s'));
    }

    public function isDeleted($productId)
    {
        $status = get_post_status($productId);

        return $status === 'trash';
    }

    public static function run()
    {
        (new self())->execute();
    }
}