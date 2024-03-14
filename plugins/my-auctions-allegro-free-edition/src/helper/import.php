<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Helper_Import
{

    public function getFieldsData()
    {
        return [
            'controller' => [
                'type'  => 'hidden',
                'name'  => 'controller',
                'value' => 'import'
            ],
            'action'     => [
                'type'  => 'hidden',
                'name'  => 'action',
                'value' => 'gjmaa_import_action'
            ],
            'nonce'      => [
                'type'  => 'hidden',
                'name'  => 'nonce',
                'value' => wp_create_nonce('import_action')
            ],
            'profile_id' => [
                'id'     => 'profile_id',
                'type'   => 'select',
                'name'   => 'profile_id',
                'label'  => 'Profile',
                'source' => 'profiles'
            ],
            'import'     => [
                'id'    => 'submit_import',
                'type'  => 'submit',
                'name'  => 'import',
                'label' => 'Import'
            ]
        ];
    }

    public function runImportByProfileId($profileId, $type = 'user')
    {
        $profile = GJMAA::getModel('profiles');
        $profile->load($profileId);

        if ($type == 'cron') {
            if ($profile->getData('profile_import_step') == 2) {
                $profile->setData('profile_import_step', 1);
                $profile->setData('profile_imported_auctions', 0);
            }
        }

        if ( ! $profile->getId()) {
            $errorMessage = __('Selected profile does not exist.', GJMAA_TEXT_DOMAIN);
            error_log($errorMessage);

            if ($type != 'cron') {
                throw new Exception($errorMessage);
            }

            return;
        }

        $settingId = $profile->getData('profile_setting_id');
        /** @var GJMAA_Model_Settings $settingsModel */
        $settingsModel = GJMAA::getModel('settings');
        $settingsModel->load($settingId);

        $isAllegro = true;
        if ($settingsModel->getData('setting_site') != 1) {
            $isAllegro = false;
        }


        switch ($profile->getData('profile_type')) {
            case 'auctions_of_user':
                $service = 'import_user';
                break;
            case 'search':
                $service = 'import_search';
                break;
            case 'my_auctions':
            default:
                if ($isAllegro) {
                    $service = 'import_auctions';
                } else {
                    $service = 'import_user';
                }
                break;
        }

        $sort = $profile->getData('profile_imported_auctions');

        if ($profile->getData('profile_import_step') == 1) {
            if ($sort == 0 && $profile->getData('profile_clear_auctions') == 1) {
                $auctionsModel = GJMAA::getModel('auctions');
                $auctionsModel->deleteByProfileId($profile->getId());
            }
        }

        try {
            /** @var GJMAA_Service_Import $importService */
            $importService = GJMAA::getService($service);
            $importService->setProfile($profile);
            $response = $importService->run();
            $fullTree = [];

            if ( ! empty($response['auctions'])) {
                $settingsModel = GJMAA::getModel('settings');
                $settingsModel->load($profile->getData('profile_setting_id'));

                /** @var GJMAA_Service_Categories $categoryService */
                $categoryService = GJMAA::getService('categories');
                $categoryService->setSettings($settingsModel);

                /** @var GJMAA_Model_Allegro_Category $allegroModelCategory */
                $allegroModelCategory = GJMAA::getModel('allegro_category');

                /** @var GJMAA_Model_Auctions $auctionsModel */
                $auctionsModel = GJMAA::getModel('auctions');

                foreach ($response['auctions'] as $auction) {
                    $category = isset($auction['auction_categories']) ? $auction['auction_categories'] : null;
                    if (is_array($category)) {
                        $fullTreeCategory = current($category);
                        if ($fullTreeCategory && ! isset($fullTree[ $fullTreeCategory['category_id'] ])) {
                            if ( ! $allegroModelCategory->existsInDatabase($fullTreeCategory['category_id'])) {
                                $allegroModelCategory->saveFullTree($category);
                                $fullTree[ $fullTreeCategory['category_id'] ] = $fullTreeCategory;
                            }
                        }
                        $auction['auction_categories'] = $fullTreeCategory['category_id'];
                    } else {
                        if ( ! $allegroModelCategory->existsInDatabase($category)) {
                            $fullTreeCategory = $categoryService->getFullTreeForCategory($category);
                            if ( ! empty($fullTreeCategory)) {
                                $allegroModelCategory->saveFullTree($fullTreeCategory);
                                $fullTree[ $category ] = $fullTreeCategory;
                            }
                        }
                    }
                    $auction['auction_sort_order'] = $sort;
                    $sort++;
                    $auctionsModel->unsetData();
                    $auctionsModel->load([
                        $auction['auction_id'],
                        $auction['auction_profile_id']
                    ], [
                        'auction_id',
                        'auction_profile_id'
                    ]);

                    if ($auctionsModel->getId()) {
                        if (isset($auction['auction_quantity'])) {
                            $auctionsModel->setData('auction_quantity', $auction['auction_quantity']);
                            $auctionsModel->setData('auction_update_woocommerce_stock', 1);
                        }
                        if (isset($auction['auction_price'])) {
                            $auctionsModel->setData('auction_price', $auction['auction_price']);
                            $auctionsModel->setData('auction_update_woocommerce_price', 1);
                        }
                        if (isset($auction['auction_bid_price'])) {
                            $auctionsModel->setData('auction_bid_price', $auction['auction_bid_price']);
                        }
	                    if (isset($auction['auction_external_id'])) {
		                    $auctionsModel->setData('auction_external_id', $auction['auction_external_id']);
	                    }
                        if (isset($auction['auction_status'])) {
                            $auctionsModel->setData('auction_status', $auction['auction_status']);
                        }
                        if (isset($auction['auction_in_woocommerce'])) {
                            $auctionsModel->setData('auction_in_woocommerce', $auction['auction_in_woocommerce']);
                        }

                        if(isset($auction['auction_time'])) {
                            $auctionsModel->setData('auction_time', $auction['auction_time']);
                        }
                    } else {
                        $auctionsModel->setData($auction);
                    }
                    $auctionsModel->save();
                }
            }
        } catch (Requests_Exception $e) {
            $profile->setData('profile_imported_auctions', 0);
            $profile->setData('profile_error_message', $e->getMessage());
            $profile->setData('profile_errors', ((int)$profile->getData('profile_errors')) + 1);
            $profile->setData('profile_import_lock', 0);
            $profile->save();
            throw $e;
        } catch (Exception $e) {
            $profile->setData('profile_error_message', $e->getMessage());
            $profile->setData('profile_errors', ((int)$profile->getData('profile_errors')) + 1);
            $profile->setData('profile_import_lock', 0);
            $profile->save();
            throw $e;
        }

        unset($response['auctions']);

        if ( ! isset($response['step'])) {
            $response['step']      = 1;
            $response['all_steps'] = $profile->getData('profile_to_woocommerce') ? 2 : 1;
        }

        $profile->setData('profile_imported_auctions', $response['imported_auctions']);
        $profile->setData('profile_all_auctions', $response['all_auctions']);
        if ( ! $profile->getData('profile_import_step')) {
            $profile->setData('profile_import_step', 1);
        }

        if ($response['imported_auctions'] >= $response['all_auctions'] || $response['progress'] == 100) {
            $profile->setData('profile_imported_auctions', 0);

            if (($profile->getData('profile_to_woocommerce') && $profile->getData('profile_import_step') == 2) || ! $profile->getData('profile_to_woocommerce')) {
                $profile->setData('profile_import_step', 1);
                $profile->setData('profile_last_sync', date('Y-m-d H:i'));
            } elseif ($profile->getData('profile_to_woocommerce') && $profile->getData('profile_import_step') == 1) {
                if ($type != 'cron') {
                    $profile->setData('profile_import_step', 2);
                } else {
                    $profile->setData('profile_import_step', 1);
                    $profile->setData('profile_last_sync', date('Y-m-d H:i'));
                    $profile->setData('profile_import_lock', 0);
                }
            }
        }
        $profile->setData('profile_error_message', null);
        $profile->setData('profile_errors', 0);
        $profile->save();

        return $response;
    }
}