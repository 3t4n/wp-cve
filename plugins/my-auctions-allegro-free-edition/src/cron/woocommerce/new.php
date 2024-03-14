<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

require_once GJMAA_PATH_CODE . 'cron/abstract_cron.php';

class GJMAA_Cron_Woocommerce_New extends GJMAA_Cron_Abstract
{
    public function getCode(): string
    {
        return 'gjmaa_cron_woocommerce_new';
    }

    public function runJob() : void
    {
        ini_set('max_execution_time', 900);
        /** @var GJMAA_Service_Woocommerce $wooCommerceService */
        $wooCommerceService = GJMAA::getService('woocommerce');
        $this->log('Start cron');
        if ( ! $wooCommerceService->isEnabled()) {
            return;
        }

        $this->log('WooCommerce is enabled');

        /** @var GJMAA_Model_Profiles $profileModel */
        $profileModel          = GJMAA::getModel('profiles');
        $wooCommerceProfileIds = $profileModel->getWooCommerceProfileIds();
        if (empty($wooCommerceProfileIds)) {
            return;
        }

        $this->log('WooCommerce profiles count: ' . count($wooCommerceProfileIds));

        foreach ($wooCommerceProfileIds as $wooCommerceProfileId) {
            $this->log('Start create new products from profile: '. $wooCommerceProfileId);

            $profileModel->unsetData();
            $profileModel->load($wooCommerceProfileId);

			if(!$profileModel->getData('profile_cron_sync')) {
				$this->log(sprintf('Skip profile %d as sync with cron is disabled', $wooCommerceProfileId));
				continue;
			}

            $statusesToSync = $profileModel->getData('profile_publication_status');
            if(empty($statusesToSync)) {
                /** @var GJMAA_Source_Allegro_Offerstatus $offerStatus */
                $offerStatus = GJMAA::getSource('allegro_offerstatus');
                $statusesToSync = [
                    $offerStatus::INACTIVE,
                    $offerStatus::ACTIVATING,
                    $offerStatus::ACTIVE,
                    $offerStatus::ENDED
                ];
            } elseif(is_string($statusesToSync)) {
                $statusesToSync = explode(',',$statusesToSync);
            }

            $where = [
                "auction_profile_id = $wooCommerceProfileId",
                "auction_in_woocommerce <> 1",
                "auction_status IN ('" . implode("','", $statusesToSync) . "')"
            ];

            $where = implode(' AND ', $where);

            $filters = [
                'WHERE' => $where,
                'LIMIT' => 40
            ];

            $auctionsModel = GJMAA::getModel('auctions');
            $auctions      = $auctionsModel->getAllBySearch($filters);
            if (empty($auctions)) {
                continue;
            }

            $auctionsData = $auctionsToSkip = [];
            foreach ($auctions as $auction) {
                if (!in_array($auction['auction_status'], $statusesToSync)) {
                    $auctionsToSkip[] = $auction['auction_id'];
                    continue;
                }

                $auctionsData[] = $auction['auction_id'];
            }

            $this->log('Get all auctions by ids: ' . implode(',', $auctionsData));

            if(!empty($auctionsData)) {
                $settings = GJMAA::getModel('settings');
                $settings->load($profileModel->getData('profile_setting_id'));
                $settingsSite = $settings->getData('setting_site');
                if ($profileModel->getData('profile_type') !== 'my_auctions' && $settingsSite == 1) {
                    continue;
                }

                try {
                    if ($settingsSite == 1) {
                        /** @var GJMAA_Service_Import_Auctions $restApiImport */
                        $restApiImport = GJMAA::getService('import_auctions');
                        $profileModel->setData('profile_import_step', 2);
                        $restApiImport->setProfile($profileModel);
                        $restApiImport->setSettings($settings);
                        $restApiImport->setAuctions($auctionsData);
                        $restApiImport->run();
                    } else {
                        /** @var GJMAA_Service_Import $restApiImport */
                        $restApiImport = GJMAA::getService('import');
                        $restApiImport->setProfile($profileModel);
                        $isRestConnected = $restApiImport->connect();
                        $webapiImport    = null;
                        if ($isRestConnected) {
                            /** @var GJMAA_Lib_Webapi $webapiImport */
                            $webapiImport = $restApiImport->connectToWebAPI();
                        }

                        $response = $webapiImport->getItemAuction($auctionsData);
                        if ($message = $webapiImport->getError()) {
                            error_log(sprintf(__('Problem with getting details about auctions: %s', GJMAA_TEXT_DOMAIN)), $message);
                            continue;
                        }

                        $items          = $response->arrayItemListInfo->item;
                        $auctionDetails = is_array($items) ? $items : [
                            $items
                        ];

                        $wooCommerceService->setSettingId($profileModel->getData('profile_setting_id'));
                        $productIds = $wooCommerceService->setProfile($profileModel)->saveProducts($auctionDetails);
                        $this->saveUpdatedAuctions($auctions, $auctionsToSkip, $productIds);
                    }
                } catch (Exception $e) {
                    error_log(sprintf(__('Problem with creating products on profile: %s with error %s', GJMAA_TEXT_DOMAIN), $wooCommerceProfileId, $e->getMessage()));
                }
            }

            if ( ! empty($auctionsToSkip)) {
                $this->saveUpdatedAuctions($auctions, $auctionsToSkip);
            }

            $this->log('End create new products from profile: '. $wooCommerceProfileId);
        }

        $this->log('End cron');
    }
    
    public function saveUpdatedAuctions($auctions, $auctionsToSkip = [], $productIds = []) {
        $auctionsModel = GJMAA::getModel('auctions');
        
        foreach ($auctions as $auction) {
            if(empty($auction['auction_id'])) {
                continue;
            }

            $auctionsModel->unsetData();
            
            if(in_array($auction['auction_id'],$auctionsToSkip)) {
                $auction['auction_in_woocommerce'] = 2;
            } else {
                $auction['auction_in_woocommerce'] = 1;
            }
            
            $auction['auction_woocommerce_id'] = isset($productIds[$auction['auction_id']]) ? $productIds[$auction['auction_id']] : 0;
            
            $auctionsModel->setData($auction);
            $auctionsModel->save();
        }
    }

    public function log($message)
    {
        error_log(sprintf('[%s] %s', 'WooCommerce New Products', $message));
    }
    
    public static function run()
    {
        (new self())->execute();
    }
}