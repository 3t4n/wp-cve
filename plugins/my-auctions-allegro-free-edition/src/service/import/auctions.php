<?php
require_once __DIR__ . '/../import.php';

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Service_Import_Auctions extends GJMAA_Service_Import {

	protected $type = 'my_auctions';

	public function makeRequest() {
		/** @var GJMAA_Lib_Rest_Api_Sale_Offers $api */
		$api        = GJMAA::getLib('rest_api_sale_offers');
		$categoryId = $this->getProfile()->getData('profile_category');
		if ( ! empty($categoryId) ) {
			$api->setCategoryId($categoryId);
		}

		if ( $this->getProfileStep() != 2 ) {
			$sellingModeFormat = $this->getProfile()->getData('profile_sellingmode_format');
			if ( is_string($sellingModeFormat) ) {
				$sellingModeFormat = explode(',', $sellingModeFormat);
			}

			if ( empty($sellingModeFormat) ) {
				$sellingModeFormat = null;
			}

			if ( is_array($sellingModeFormat) ) {
				$api->setSellingMode(array_values($sellingModeFormat));
			}
		}

		$this->client = $api;

		parent::makeRequest();
	}

	public function getLimitForRequest() {
		return 100;
	}

	public function parseResponse( $response, $auctionId = null ) {
		$result = [
			'auctions'          => [],
			'all_auctions'      => $this->getProfile()->getData('profile_all_auctions') ?: 0,
			'imported_auctions' => $this->getProfile()->getData('profile_imported_auctions') ?: 0,
			'progress'          => 100
		];

		if ( $this->getProfileStep() != 2 ) {
			$auctions = $response[ 'offers' ];

			$countOfAuctions = count($auctions);

			$collection = [];

			if ( $countOfAuctions > 0 ) {
				foreach ( $auctions as $auction ) {
					$collection[] = [
						'auction_id'          => $auction[ 'id' ],
						'auction_profile_id'  => $this->getProfile()->getId(),
						'auction_name'        => $auction[ 'name' ],
						'auction_price'       => $auction[ 'sellingMode' ][ 'format' ] == GJMAA_Service_Import::PRICE_BIDDING_FORMAT ? (
						! empty($auction[ 'saleInfo' ][ 'currentPrice' ]) ?
							$auction[ 'saleInfo' ][ 'currentPrice' ][ 'amount' ] : (
						! empty($auction[ 'sellingMode' ][ 'minimalPrice' ][ 'amount' ]) ?
							$auction[ 'sellingMode' ][ 'minimalPrice' ][ 'amount' ] :
							$auction[ 'sellingMode' ][ 'startingPrice' ][ 'amount' ]
						)
						) : $auction[ 'sellingMode' ][ 'price' ][ 'amount' ],
						'auction_bid_price'   => $auction[ 'sellingMode' ][ 'format' ] == GJMAA_Service_Import::PRICE_BIDDING_FORMAT ? (
						! empty($auction[ 'saleInfo' ][ 'currentPrice' ]) ?
							$auction[ 'saleInfo' ][ 'currentPrice' ][ 'amount' ] : (
						! empty($auction[ 'sellingMode' ][ 'minimalPrice' ][ 'amount' ]) ?
							$auction[ 'sellingMode' ][ 'minimalPrice' ][ 'amount' ] :
							$auction[ 'sellingMode' ][ 'startingPrice' ][ 'amount' ]
						)
						) : 0,
						'auction_images'      => json_encode([ $auction[ 'primaryImage' ] ]),
						'auction_seller'      => $this->getUserId(),
						'auction_status'      => $auction[ 'publication' ][ 'status' ],
						'auction_categories'  => $auction[ 'category' ][ 'id' ],
						'auction_time'        => isset($auction[ 'publication' ]) ? $auction[ 'publication' ][ 'endingAt' ] : null,
						'auction_quantity'    => $auction[ 'stock' ][ 'available' ],
						'auction_external_id' => $auction[ 'external' ][ 'id' ] ?? null
					];
				}

				$result[ 'auctions' ]     = $collection;
				$profileAuctions          = $this->getProfile()->getData('profile_auctions');
				$allAuctions              = $profileAuctions != 0 && $profileAuctions <= $response[ 'totalCount' ] ? $profileAuctions : $response[ 'totalCount' ];
				$result[ 'all_auctions' ] = $allAuctions;
				$result                   = $this->recalculateProgressData($result, $countOfAuctions);
			} else {
				$result[ 'all_auctions' ] = 0;
				$result                   = $this->recalculateProgressData($result, $countOfAuctions);
			}
		} else {
			$auctionDetails = [];
			$auctionsToSkip = [];
			if ( is_array($response) && ! $auctionId ) {
				foreach ( $response as $singleAuctionId => $singleResponse ) {
					$toDelete = $singleResponse[ 'to_delete' ] ?? false;
					if ( ! $toDelete ) {
						$auctionDetails[] = $this->prepareProduct($singleResponse);
					} else {
						$auctionsToSkip[] = $singleAuctionId;
					}
				}
			} elseif ( is_array($response) && $auctionId ) {
				$toDelete = $response[ $auctionId ][ 'to_delete' ] ?? false;
				if ( ! $toDelete ) {
					$auctionDetails[] = $this->prepareProduct($response[ $auctionId ]);
				} else {
					$auctionsToSkip[] = $auctionId;
				}
			} else {
				$toDelete = $response[ 'to_delete' ] ?? false;
				if ( ! $toDelete ) {
					$auctionDetails = $this->prepareProduct($response);
				} else {
					$singleAuctionId = $response[ 'id' ] ?? null;
					if ( $singleAuctionId ) {
						$auctionsToSkip[] = $singleAuctionId;
					}
				}
			}

			$auctionDetails = array_filter($auctionDetails);

			/** @var GJMAA_Service_Woocommerce $serviceWooCommerce */
			$serviceWooCommerce = GJMAA::getService('woocommerce');
			$serviceWooCommerce->setSettings($this->getSettings());
			$serviceWooCommerce->setSettingId($this->getSettings()->getId());
			$serviceWooCommerce->setProfile($this->getProfile());

			if ( ! empty($auctionDetails) ) {
				$productIds = $serviceWooCommerce->saveProducts(
					$auctionDetails,
					true
				);

				foreach ( $productIds as $auctionId => $productId ) {
					$result[ 'auctions' ][] = [
						'auction_id'             => $auctionId,
						'auction_profile_id'     => $this->getProfile()->getId(),
						'auction_in_woocommerce' => $auctionId ? 1 : 2,
						'auction_woocommerce_id' => $productId
					];
				}

				/** @var GJMAA_Model_Auctions $auctionsModel */
				$auctionsModel = GJMAA::getModel('auctions');

				$result[ 'all_auctions' ] = $auctionsModel->getCountFilteredResult(null,null,['auction_profile_id' => $this->getProfile()->getId()]);
				$result                   = $this->recalculateProgressData($result, 1);

				$this->unsetAuctions();
			} else {
				if ( ! empty($auctionsToSkip) ) {
					$this->saveUpdatedAuctions([ [ 'auction_id' => $auctionId ] ], $auctionsToSkip);
				}
				$auctionsModel = GJMAA::getModel('auctions');
				$result[ 'all_auctions' ] = $auctionsModel->getCountFilteredResult(null,null,['auction_profile_id' => $this->getProfile()->getId()]);
				$result                   = $this->recalculateProgressData($result, 1);
				$this->unsetAuctions();
			}
		}

		return $result;
	}

	public function saveUpdatedAuctions( $auctions, $auctionsToSkip = [], $productIds = [] ) {
		$auctionsModel = GJMAA::getModel('auctions');

		foreach ( $auctions as $auction ) {
			$auctionsModel->unsetData();
			$auctionsModel->load([
				$auction[ 'auction_id' ],
				$this->getProfile()->getId()
			], [
				'auction_id',
				'auction_profile_id'
			]);

			if ( ! $auctionsModel->getData('auction_id') ) {
				continue;
			}

			if ( in_array($auction[ 'auction_id' ], $auctionsToSkip) ) {
				$inWooCommerce = 2;
			} else {
				$inWooCommerce = 1;
			}

			$auctionWooCommerceId = isset($productIds[ $auction[ 'auction_id' ] ]) ? $productIds[ $auction[ 'auction_id' ] ] : 0;

			$auctionsModel->setData('auction_woocommerce_id', $auctionWooCommerceId);
			$auctionsModel->setData('auction_in_woocommerce', $inWooCommerce);
			$auctionsModel->save();
		}
	}

	public function prepareProduct( $response ) {
		if ( empty($response) ) {
			return [];
		}

		$product                  = [];
		$product[ 'id' ]          = $response[ 'id' ];
		$product[ 'name' ]        = $response[ 'name' ];
		$product[ 'description' ] = $response[ 'description' ];
		$product[ 'stock' ]       = $response[ 'stock' ][ 'available' ];
		$product[ 'status' ]      = $response[ 'publication' ][ 'status' ];
		$product[ 'images' ]      = $response[ 'images' ];
		$product[ 'categories' ]  = $response[ 'category' ][ 'id' ];
		$product[ 'attributes' ]  = isset($response['productSet'], $response['productSet'][0]['product']['parameters']) ? $response['productSet'][0]['product']['parameters'] : $response[ 'parameters' ];
		$product[ 'external_id' ] = $response[ 'external' ][ 'id' ] ?? null;
		$product[ 'size_table' ]  = $response[ 'sizeTable' ][ 'id' ] ?? null;

		if ( in_array($response[ 'sellingMode' ][ 'format' ], [ 'BUY_NOW', 'ADVERTISEMENT' ]) ) {
			$product[ 'price' ] = $response[ 'sellingMode' ][ 'price' ][ 'amount' ];
		} else {
			$startingPrice = (float) ( $response[ 'sellingMode' ][ 'startingPrice' ][ 'amount' ] ?? 0 );
			$minimalPrice  = (float) ( $response[ 'sellingMode' ][ 'minimalPrice' ][ 'amount' ] ?? 0 );
			$currentPrice  = (float) ( $response[ 'sellingMode' ][ 'price' ][ 'amount' ] ?? 0 );

			$product[ 'price' ] = $currentPrice > $minimalPrice ? $currentPrice : ( $minimalPrice > $startingPrice ? $minimalPrice : ( $startingPrice > 0.00 ? $startingPrice : null ) );
		}

		return $product;
	}
}