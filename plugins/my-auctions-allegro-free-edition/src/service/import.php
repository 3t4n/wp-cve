<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Service_Import
{

    protected $profile;

    protected $settings;

    protected $apiToken;

    protected $client;

    protected $webapi;

    protected $ids;

    const PRICE_BUY_NOW_FORMAT = 'BUY_NOW';

    const PRICE_BIDDING_FORMAT = 'AUCTION';

    const PRICE_ADVERTISMENT_FORMAT = 'ADVERTISEMENT';

    public function setProfile(GJMAA_Model_Profiles $profile)
    {
        $this->profile = $profile;

        return $this;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function makeRequest()
    {
        if ( ! $this->client) {
            throw new Exception(__('No set required parameters'));
        }

        if ($this->getProfileStep() != 2) {
            $limit_per_request = $this->getLimitForRequest();
            $imported          = intval(ceil($this->getProfile()->getData('profile_imported_auctions') / $limit_per_request));
            $offset            = $imported * $limit_per_request;

            $profileAuctions = $this->getProfile()->getData('profile_auctions');

            $limit = $profileAuctions != 0 && $profileAuctions < $limit_per_request ? $profileAuctions : $limit_per_request;

            $this->client->setOffset($offset);
            $this->client->setLimit($limit);
            $this->client->setSort($this->getProfile()
                ->getData('profile_sort') ?: 'price_desc');
        }
    }

    public function getLimitForRequest()
    {
        return 60;
    }

    public function connect()
    {
        $settings = $this->getSettings();

        /** @var GJMAA_Helper_Settings $helper */
        $helper = GJMAA::getHelper('settings');
        if ( ! $helper->isConnectedApi($settings->getData())) {
            return false;
        }

        if ($helper->isExpiredToken($settings->getData())) {
            $settings = $this->refreshToken($settings);

            $this->setSettings($settings);
        }

        return true;
    }

    public function getSettings()
    {
        if ( ! $this->settings) {
            $this->settings = GJMAA::getModel('settings')->load($this->getProfile()
                ->getData('profile_setting_id'));
        }

        return $this->settings;
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    public function refreshToken($settings)
    {
        /** @var GJMAA_Helper_Settings $helper */
        $helper = GJMAA::getHelper('settings');

        return $helper->refreshToken($settings);
    }

    public function run()
    {
        if ( ! $this->getProfile()) {
            throw new Exception(__('Profile not set', GJMAA_TEXT_DOMAIN));
        }

        if ($this->connect()) {
            $this->makeRequest();
            $auctionId = null;
            if ($this->getProfileStep() == 2) {
                if ($this->getProfile()->getData('profile_type') !== 'my_auctions') {
                    $this->getProfile()->setData('profile_to_woocommerce', 0);
                    $this->getProfile()->setData('profile_import_step', 1);
                    $this->getProfile()->setData('profile_all_auctions', 0);
                    $this->getProfile()->save();
                    throw new Exception(__('Allegro WEB API is not supported anymore. You can\'t collect auctions of other user as WooCommerce Products. Switch your profile to `My auctions` and import only your auctions as WooCommerce Products.', GJMAA_TEXT_DOMAIN));
                }
                if ( ! $this->getAuctions()) {
                    $auctionId = $this->getAuctionIdByProfile();
                    if ( ! $auctionId) {
                        $result = [
                            'auctions'          => [],
                            'all_auctions'      => $this->getProfile()->getData('profile_all_auctions') ?: 0,
                            'imported_auctions' => $this->getProfile()->getData('profile_imported_auctions') ?: 0,
                            'progress'          => 100
                        ];

                        return $this->recalculateProgressData($result, 1);
                    }
                    $this->setAuctions([$auctionId]);
                }
            }
            $response = $this->sendRequest();
        } else {
            $import = GJMAA::getService('webapi_import');
            $import->setProfile($this->getProfile());
            if ($import->connect()) {
                return $import->run();
            } else {
                throw new Exception(__('Can\'t connect to API', GJMAA_TEXT_DOMAIN));
            }
        }

        return $this->parseResponse($response, (isset($auctionId) ? $auctionId : null));
    }

    public function sendRequest()
    {
        if ($this->getSettings()->getData('setting_site') == 1) {
            $this->client->setToken($this->getSettings()
                ->getData('setting_client_token'));
            $this->client->setSandboxMode($this->getSettings()
                ->getData('setting_is_sandbox'));

            $response = [];

            $auctionIds = $this->getAuctions();
            if ( ! empty($auctionIds)) {
                foreach ($auctionIds as $auctionId) {
                    if (method_exists($this->client, 'setAuctionId')) {
                        $this->client->setAuctionId($auctionId);
                    }
                    $response[ $auctionId ] = $this->client->execute();
                }
            } else {
                $response = $this->client->execute();
            }

            return $response;
        } else {
            $auctionId = $this->getAuctionIdByProfile();

            return $this->client->getItemAuction($auctionId);
        }
    }

    public function connectToWebAPI()
    {
        if ( ! $this->webapi) {
            $settings = $this->getSettings();

            $token     = $settings->getData('setting_client_token');
            $webapiKey = $settings->getData('setting_webapi_key');
            $countryId = $settings->getData('setting_site');

			/** @var GJMAA_Lib_Webapi $webapi */
            $webapi = GJMAA::getLib('webapi');
            $webapi->setSandbox($settings->getData('setting_is_sandbox'));
            $webapi->connectByToken($token, $webapiKey, $countryId);
            $this->webapi = $webapi;
        }

        return $this->webapi;
    }

    public function getUserId()
    {
        $userToken = $this->getSettings()->getData('setting_client_token');

        if ( ! $userToken) {
            return null;
        }

        $tokenSplit      = explode('.', $userToken);
        $decodedUserData = isset($tokenSplit[1]) ? json_decode(base64_decode($tokenSplit[1]), true) : [];

        return isset($decodedUserData['user_name']) ? $decodedUserData['user_name'] : null;
    }

    public function parseUserToSellerId($user)
    {
        $setting_login = $this->getSettings()->getData('setting_login');

        $userId = null;

        if ($setting_login == $user || $this->getProfile() === 'my_auctions') {
            $userId = $this->getUserId();
        } else {
            $userId = $user;
        }

        return $userId;
    }

    public function getProfileStep()
    {
        return $this->getProfile()->getData('profile_import_step');
    }

    public function getAuctionDetails()
    {
        $this->client = $this->connectToWebAPI();
    }

    public function getAuctionIdByProfile()
    {
        $first                   = true;
        $profileImportedAuctions = 0;
        do {
            /** @var GJMAA_Model_Auctions $auctionsModel */
            $auctionsModel = GJMAA::getModel('auctions');
            $allAuctions   = $auctionsModel->getCountFilteredResult(null, null,
	            [
		            'auction_profile_id' => $this->getProfile()->getId()
	            ]
            );

            if ($first) {
                $profileImportedAuctions = $this->getProfile()->getData('profile_imported_auctions');
                $first                   = false;
            } else {
                $profileImportedAuctions++;
            }
            /** @var GJMAA_Source_Allegro_Offerstatus $gjmaaStatusOffer */
            $gjmaaStatusOffer = GJMAA::getSource('allegro_offerstatus');

            $filters = [
                'WHERE'  => 'auction_profile_id = ' . $this->getProfile()->getId(),
                'LIMIT'  => 1,
                'OFFSET' => $profileImportedAuctions
            ];

            $publicationStatus = $this->getProfile()->getData('profile_publication_status');
            if ( ! $publicationStatus) {
                $publicationStatus = [
                    $gjmaaStatusOffer::INACTIVE,
                    $gjmaaStatusOffer::ACTIVE,
                    $gjmaaStatusOffer::ACTIVATING,
                    $gjmaaStatusOffer::ENDED
                ];
            } elseif (is_string($publicationStatus)) {
                $publicationStatus = explode(',', $publicationStatus);
            }

            $auction = $auctionsModel->getRowBySearch($filters);
            if ( ! $auction->getId()) {
                if ($allAuctions <= $profileImportedAuctions) {
                    $profileImportedAuctions--;
                    break;
                } else {
                    continue;
                }
            }

            if ( ! in_array($auction->getData('auction_status'), $publicationStatus)) {
                $auction->setData('auction_in_woocommerce', 2);
                $auction->save();
                if ($allAuctions <= $profileImportedAuctions) {
                    break;
                }
            }

            if ($auction->getData('auction_id') == 0) {
                $auction->delete();
                if ($allAuctions <= $profileImportedAuctions) {
                    break;
                }
            }
        } while (( ! in_array($auction->getData('auction_status'), $publicationStatus) || $auction->getData('auction_id') == 0) && $allAuctions >= $profileImportedAuctions);

        $this->getProfile()->setData('profile_imported_auctions', $profileImportedAuctions);

        return $auction->getData('auction_id');
    }

    public function recalculateProgressData($result, $count)
    {
        $result['step']      = $this->getProfileStep();
        $result['all_steps'] = $this->getProfile()->getData('profile_to_woocommerce') ? 2 : 1;

        $progress_all = 100;
        if ($result['all_steps'] > 1) {
            $progress_all = $result['all_auctions'] > 0 ? 50 : 100;
        }

        $result['imported_auctions'] += $count;
        $result['progress_step']     = $result['all_auctions'] > 0 ? number_format(($result['imported_auctions'] / $result['all_auctions']) * 100, 2) : 100;
        $result['progress']          = $progress_all == 100 ? $result['progress_step'] : ($this->getProfileStep() != 2 ? $result['progress_step'] / 2 : number_format($progress_all + ($result['progress_step'] / 2), 2));

        return $result;
    }

    public function parseResponse($response, $auctionId = null)
    {
        $result = [
            'auctions'          => [],
            'all_auctions'      => $this->getProfile()->getData('profile_all_auctions') ?: 0,
            'imported_auctions' => $this->getProfile()->getData('profile_imported_auctions') ?: 0,
            'progress'          => 100
        ];

        if ($this->getProfileStep() != 2) {
            $regular  = $response['items']['regular'];
            $promoted = $response['items']['promoted'];

            $auctions        = array_merge($regular, $promoted);
            $countOfAuctions = count($auctions);

            $collection = [];

            $allegroOfferStatus = GJMAA::getSource('allegro_offerstatus');
            if ($countOfAuctions > 0) {
                foreach ($auctions as $auction) {
                    $collection[] = [
                        'auction_id'         => $auction['id'],
                        'auction_profile_id' => $this->getProfile()->getId(),
                        'auction_name'       => $auction['name'],
                        'auction_price'      => $auction['sellingMode']['format'] == self::PRICE_BUY_NOW_FORMAT ? $auction['sellingMode']['price']['amount'] : ($auction['sellingMode']['format'] == self::PRICE_BIDDING_FORMAT && isset($auction['sellingMode']['fixedPrice']) ? $auction['sellingMode']['fixedPrice']['amount'] : ($auction['sellingMode']['format'] == self::PRICE_ADVERTISMENT_FORMAT ? $auction['sellingMode']['price']['amount'] : 0)),
                        'auction_bid_price'  => $auction['sellingMode']['format'] == self::PRICE_BIDDING_FORMAT ? $auction['sellingMode']['price']['amount'] : 0,
                        'auction_images'     => json_encode($auction['images']),
                        'auction_seller'     => $auction['seller']['id'],
                        'auction_categories' => isset($auction['vendor']['id']) ? [
                            $auction['category']['id'] => [
                                'category_id'        => $auction['category']['id'],
                                'name'               => str_replace('_', ' ', $auction['vendor']['id']),
                                'category_parent_id' => 'additional',
                                'country_id'         => $this->getSettings()->getData('setting_site'),
                                'leaf'               => 0
                            ],
                            'additional'               => [
                                'category_id'        => 'additional',
                                'name'               => __('Vendor', GJMAA_TEXT_DOMAIN),
                                'category_parent_id' => 0,
                                'country_id'         => $this->getSettings()->getData('setting_site'),
                                'leaf'               => 0
                            ]
                        ] : $auction['category']['id'],
                        'auction_status'     => $allegroOfferStatus::ACTIVE,
                        'auction_time'       => isset($auction['publication']) ? $auction['publication']['endingAt'] : null,
                        'auction_quantity'   => $auction['stock']['available'],
	                    'auction_external_id' => $auction['external']['id']
                    ];
                }
                $result['auctions']     = $collection;
                $profileAuctions        = $this->getProfile()->getData('profile_auctions');
                $allAuctions            = $profileAuctions != 0 && $profileAuctions <= $response['searchMeta']['totalCount'] ? $profileAuctions : ($response['searchMeta']['totalCount'] >= 6000 ? 6000 : $response['searchMeta']['totalCount']);
                $result['all_auctions'] = $allAuctions;
                $result                 = $this->recalculateProgressData($result, $countOfAuctions);
            } else {
                $result['all_auctions'] = 0;
                $result                 = $this->recalculateProgressData($result, $countOfAuctions);
            }
        } else {
            $auctionDetails = $response->arrayItemListInfo->item;

            /** @var GJMAA_Service_Woocommerce $serviceWooCommerce */
            $serviceWooCommerce = GJMAA::getService('woocommerce');
            $serviceWooCommerce->setSettingId($this->getSettings()->getId());
            $serviceWooCommerce->setProfile($this->getProfile());
            $serviceWooCommerce->saveProducts([
                $auctionDetails
            ]);

            $result['auctions'][] = [
                'auction_id'             => $auctionDetails ? $auctionDetails->itemInfo->itId : $auctionId,
                'auction_profile_id'     => $this->getProfile()->getId(),
                'auction_in_woocommerce' => $auctionDetails ? 1 : 2
            ];

            $result['all_auctions'] = $this->getProfile()->getData('profile_all_auctions');
            $result                 = $this->recalculateProgressData($result, 1);
        }

        return $result;
    }

    public function setAuctions($ids)
    {
        $this->ids = $ids;
    }

    public function getAuctions()
    {
        return $this->ids;
    }

    public function unsetAuctions()
    {
        $this->ids = [];
    }
}