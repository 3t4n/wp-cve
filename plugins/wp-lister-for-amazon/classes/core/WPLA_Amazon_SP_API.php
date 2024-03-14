<?php

// include the SP-API library using the autoload
require_once WPLA_PATH . '/includes/amazon/vendor/autoload.php';
require_once WPLA_PATH .'/classes/core/WPLA_Http_Client.php';
require_once WPLA_PATH .'/classes/core/WPLA_Amazon_SP_API_Authentication.php';

use SellingPartnerApi\Api\FbaOutboundV20200701Api as FbaOutboundApi;
use SellingPartnerApi\Api\FeedsV20210630Api as FeedsApi;
use SellingPartnerApi\FeedType;
use SellingPartnerApi\Api\SellersV1Api as SellersApi;
use SellingPartnerApi\Api\CatalogItemsV20220401Api as CatalogApi;
use SellingPartnerApi\Api\ListingsV20210801Api as ListingsApi;
use SellingPartnerApi\Api\ProductPricingV0Api as ProductPricingApi;
use SellingPartnerApi\Api\OrdersV0Api as OrdersApi;
use SellingPartnerApi\Api\ReportsV20210630Api as ReportsApi;
use SellingPartnerApi\Model\MerchantFulfillmentV0 as MerchantFulfillment;

use SellingPartnerApi\Model\ProductPricingV0 as ProductPricing;
use SellingPartnerApi\Model\FbaOutboundV20200701 as FbaOutbound;
use SellingPartnerApi\Model\FeedsV20210630 as Feeds;
use SellingPartnerApi\Model\CatalogItemsV20220401 as Catalog;
use SellingPartnerApi\Model\OrdersV0 as Orders;
use SellingPartnerApi\Model\ReportsV20210630 as Reports;

class WPLA_Amazon_SP_API {

    public WPLA_AmazonAccount $account;

    public $account_id;
    public $market_id;
    public string $api_host = 'mws.wplister.com/spapi/'; // use MWS API proxy because request must be signed with the developer's key
    public string $mws_host;


    public WPLA_AmazonLogger $dblogger;
    public $service;

    protected \SellingPartnerApi\Configuration $config;

    public $SellerId;
    public $MarketplaceId;

    protected \WPLab\GuzzeHttp\Client $client;

    protected $AccessKey;
    protected $SecretKey;
    protected $MWSAuthToken;

    // @todo these should be in the account class
    private $SPAuthCode;
    private $SPRefreshToken;
    private $SPAccessToken;
    private $SPAccessTokenExpiration;
    private $AWSToken;

    public $allowed_marketplace_ids;

    // SP API App ID
    public $spAppId = 'amzn1.sellerapps.app.b1e369d7-c659-43f6-81ef-1d7e2e3be036';
    public $auth_host = 'https://auth.wplister.com/sp-api';

    public function __construct( $account_id = false ) {
        $this->client = new \WPLab\GuzzeHttp\Client();

        if ( $account_id ) {
            $account = new WPLA_AmazonAccount( $account_id );
            $this->setAccountId( $account_id );
            $this->setAccessKeyId( $this->constructAccessKey( $this->getExtraParameters() ) );
            $this->setSPRefreshToken( $account->sp_refresh_token );
            //$this->setAwsToken( $account->aws_token );

            $this->client = new WPLA_Http_Client([], $this->account_id, $this->market_id );
        }


        //$this->dblogger = new WPLA_AmazonLogger();
    }

    public function setAccountId( int $account_id ) {
        $this->account = new WPLA_AmazonAccount( $account_id );

        $this->account_id = $account_id;
        $this->market_id  = $this->account->market_id;
        //$this->mws_host   = str_replace( 'amazon', 'mws.amazonservices', $this->account->getMarket()->url );
        //$this->mws_host   = str_replace( 'mws.amazonservices.co.jp', 'mws.amazonservices.jp', $this->mws_host ); // fix MWS JP URL

        $this->setMerchantId( $this->account->merchant_id );
        $this->setMarketplaceId( $this->account->marketplace_id );
        $this->setAccessKeyId( $this->account->access_key_id ? $this->account->access_key_id : 'NONE' );
        $this->setSecretKey( $this->account->secret_key );
        $this->setMWSAuthToken( $this->account->mws_auth_token );
        $this->setAllowedMarkets( $this->account->allowed_markets );

        $this->setSPAuthCode( $this->account->sp_auth_code );
        $this->setSPRefreshToken( $this->account->sp_refresh_token );
        $this->setSPAccessToken( $this->account->sp_access_token );
        $this->setSPAccessTokenExpiration( $this->account->sp_access_token_expiry );
    }

    // init MWS API
    public function initAPI( $section = false ) {
        WPLA()->logger->debug('initAPI() '.$section);

        // set up environment
        self::fixMissingIconv();
        if ( ! defined('DATE_FORMAT') ) define('DATE_FORMAT', 'Y-m-d H:i:s');

        // use autoloader to load AmazonAPI classes
        //spl_autoload_register('WPLA_AmazonAPI::autoloadAmazonClasses');
        $account_id = $this->account_id;

        $account = new WPLA_AmazonAccount( $account_id );

        $config_options = [
            "lwaAuthUrl"    => add_query_arg( 'action', 'get_tokens', $this->auth_host ),
            "lwaClientId" => 'WPLALWAID',
            "lwaClientSecret" => 'WPLALWAKEY',
            "lwaRefreshToken" => $this->SPRefreshToken,
            "awsAccessKeyId" => $this->AccessKey,
            "awsSecretAccessKey" => $this->SecretKey,
            "accessToken"   => $this->getSPAccessToken(),
            "accessTokenExpiration" => $this->getSPAccessTokenExpiration(),
            "endpoint" => SellingPartnerApi\Endpoint::getByMarketplaceId( $this->MarketplaceId, (bool)$account->sandbox_mode ),
            "authenticationClient" => new WPLA_Http_Client( [], $account_id )
        ];

        $authenticator_config = $config_options;
        $authenticator_config['roleArn'] = 'arn:aws:iam::231463789751:role/WplApiAccessRole';
        $authenticator_config['onUpdateCredentials'] = function( $aws_credentials ) use ($account_id) {
            $account = new WPLA_AmazonAccount( $account_id );
            $account->sp_access_token = $aws_credentials->getSecurityToken();
            $account->sp_access_token_expiry = $aws_credentials->getExpiration();
            //$account->aws_token = $aws_token;
            $this->setSPAccessToken( $account->sp_access_token );
            $this->setSPAccessTokenExpiration( $account->sp_access_token_expiry );
            //$this->setAwsToken( $aws_token );
            $account->update();
        };

        // Use our own Request Signer since we don't sign requests here
        $authenticator = new WPLA_Amazon_SP_API_Authentication( $authenticator_config );

        $config_options['requestSigner'] = $authenticator;
        $config = new SellingPartnerApi\Configuration( $config_options );
        $config->setUserAgent( $this->constructUserAgentString('WP-Lister for Amazon', WPLA_VERSION ) );

        if ( 'FBAOutbound' == $section ) {

            $api_section           = 'FbaOutboundApi';
            $this->AccessKey       = $this->buildSpecialAccessKey( $api_section );

            $service = new FbaOutboundApi( $config, $this->client );
        } elseif ( 'Sellers' == $section ) {

            $api_section     = 'Sellers';
            $this->AccessKey = $this->buildSpecialAccessKey( $api_section );

            $service = new SellersApi( $config );
        } else {
            /* @todo Figure out the default API to load */
            $this->AccessKey = $this->buildSpecialAccessKey('');
            $service = new SellersApi( $config );
        }

        // make dblogger available in API client
        //$service->dblogger   = $this->dblogger;
        $service->account_id = $this->account_id;
        $service->market_id  = $this->market_id;

        // Uncomment to try out Mock Service that simulates MarketplaceWebService
        // responses without calling MarketplaceWebService service.
        // $service = new MarketplaceWebService_Mock();

        $this->service  = $service;
        $this->config   = $config;

    }

    /**
     * Generate the OAuth URL for Login with Amazon. The $market_id will determine which endpoint to use
     * in the URL (e.g. US marketplace will use sellercentral.amazon.com)
     *
     * @param int $market_id
     * @return string
     */
    public function getOAuthUri( $market_id ) {
        $market = WPLA_AmazonMarket::getMarket( $market_id );
        $state = self::getOAuthStateString();
        return 'https://sellercentral.'. $market->url .'/apps/authorize/consent?application_id='. $this->spAppId .'&state='. $state;
    }

    public static function getOAuthStateString() {
        $url = trailingslashit( get_bloginfo('url'));
        WPLA()->logger->info( 'site url: '. $url);
        WPLA()->logger->info( 'state: '. md5($url));

        return md5( $url );
    }

    /**
     * Exchange the auth code to get access tokens
     *
     * @return array
     * @throws Exception
     */
    public function getTokensFromAuthCode( $auth_code ) {
        WPLA()->logger->info( 'getAccessTokenFromAuthCode #'. $auth_code );
        $response = wp_remote_post( add_query_arg( 'action', 'get_tokens', $this->auth_host ), [
            'body'  => [
                'grant_type'    => 'authorization_code',
                'code'          => $auth_code,
            ]
        ]);

        if ( is_wp_error( $response ) ) {
            WPLA()->logger->error( 'Error: '. $response->get_error_message() );
            throw new Exception( $response->get_error_message(), $response->get_error_code() );
        }

        $body = wp_remote_retrieve_body( $response );
        $body = json_encode( $body );

        if ( $body ) {
            return [
                'refresh_token' => $body->refresh_token,
                'access_token'  => $body->access_token
            ];
        }


        WPLA()->logger->error( 'getTokensFromAuthCode(): Invalid response returned. '. print_r( $body, 1 ) );
        throw new Exception( 'Unexpected response.', 500 );
    }

    /**
     * @return bool|string
     */
    public static function getAuthorizationCode() {
        WPLA()->logger->info( 'getAuthorizationCode' );

        $response = wp_remote_get(
            'https://auth.wplister.com/sp-api/?action=get_auth_code&key='. self::getOAuthStateString(),
            [
                'sslverify' => false,
                'timeout'   => 30
            ]
        );

        if ( is_wp_error( $response ) ) {
            WPLA()->logger->error( 'Error: '. $response->get_error_message() );
            wpla_show_message( 'Error fetching auth code. Auth Server said '. $response->get_error_message() .'. Please sign in with Amazon again.', 'error', ['persistent' => true] );
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body( $response ), true);

        WPLA()->logger->info('response: '. print_r($body,1));

        return $body;
    }

    /**
     * @param string $authorization_code
     * @param bool $sandbox
     * @param bool $mint_token
     * @return bool|mixed|null
     */
    public static function getOAuthAccessToken($authorization_code, $sandbox = false, $mint_token = false ) {
        WPLA()->logger->info('getOAuthAccessToken');
        $sandbox = ($sandbox) ? 1 : 0;
        $action = $mint_token ? 'mint_access_token' : 'get_access_token';
        $response = wp_remote_get(
            'https://auth.wplister.com/sp-api/?action='.$action.'&key='. urlencode( $authorization_code ) .'&test='. $sandbox,
            array(
                'sslverify' => false,
                'timeout'   => 15
            )
        );
        $body = json_decode( wp_remote_retrieve_body( $response ) );

        // log request to db
        if ( get_option('wplister_log_to_db') == '1' ) {
            $dblogger = new WPLA_AmazonLogger();
            $dblogger->updateLog( array(
                'callname'    => 'getOAuthAccessToken',
                'request_url' => '/sp-api/',
                'request'     => 'action='.$action.'&key='. urlencode( $authorization_code ) .'&test='. $sandbox,
                'response'    => print_r($body,1),
                'success'     => wp_remote_retrieve_response_code( $response ) == 200 ? 'Success' : 'Failure'
            ));
        }

        if ( is_wp_error( $response ) ) {
            WPLA()->logger->error( 'getOauthAccessToken error: '. $response->get_error_message() );
            wpla_show_message( 'Error fetching token. Auth Server said '. $response->get_error_message() .'. Please sign in with Amazon again.', 'error' );
            return false;
        } elseif ( wp_remote_retrieve_response_code( $response ) != 200 ) {
            WPLA()->logger->error( 'Error HTTP('.wp_remote_retrieve_response_code( $response ).'): '. print_r($body,1) );

            if ( is_object($body) && isset( $body->errorMessage ) ) {
                $error = $body->errorMessage->error[0]->longMessage;
                $message = 'Error fetching token. Amazon said "'. $error .'". Please try Connect with Amazon again.';
            } else {
                $message = 'Error fetching token. Auth Server said '. $body->error_description .'. Please Connect with Amazon again.';
            }

            wpla_show_message( $message, 'error' );
            return false;
        }

        WPLA()->logger->debug( 'getOAuthAccessToken body: '. print_r($body,1) );
        return $body;
    }

    /**
     * GetFulfillmentPreview (V2)
     *
     *    usage example:
     *
     *    $products = array(
     *    array(
     *    'sku' => '887717364485',
     *    'qty' => 1
     *    )
     *    );
     *
     *    $address = array(
     *    'name'     => 'Test Customer',
     *    'street'   => 'Test Street 1',
     *    'city'     => 'New York',
     *    'postcode' => '10001',
     *    'state'    => 'NY',
     *    'country'  => 'US'
     *    );
     *
     *    $api = new WPLA_AmazonAPI();
     *    $result = $api->getFulfillmentPreview( $products, $address );
     *    echo "<pre>";print_r($result);echo"</pre>";
     *
     *
     * @param array $products Array of products that contains the `sku` and `qty` indices
     * @param array $shipping_address Address array with the ff indices: name, street, city, postcode, state, country
     * @return array|object Returns as array of previews or an object on error
     */
    public function getFulfillmentPreview( $products, $shipping_address ) {
        $this->initAPI('FBAOutbound');

        $api = new FbaOutboundApi( $this->config, $this->client );

        $items = [];
        $total_number_of_units = 0;

        foreach ( $products as $product ) {

            $item = new FbaOutbound\GetFulfillmentPreviewItem();
            $item
                ->setQuantity( $product['qty'] )
                ->setSellerSku( $product['sku'] )
                ->setSellerFulfillmentOrderItemId( $product['sku'] );
            $items[] = $item;

            $total_number_of_units += $product['qty'];
        }

        $fba_address = new FbaOutbound\Address();
        $fba_address = $fba_address
            ->setName( $shipping_address['name'] )
            ->setAddressLine1( substr( $shipping_address['street'], 0, 60 ) )
            ->setCity( $shipping_address['city'] )
            ->setPostalCode( $shipping_address['postcode'] )
            ->setStateOrRegion( $shipping_address['state'] )
            ->setCountryCode( $shipping_address['country'] );

        $request = new FbaOutbound\GetFulfillmentPreviewRequest();
        $request = $request
            ->setMarketplaceId( $this->MarketplaceId )
            ->setAddress( $fba_address )
            ->setItems( $items );

        $previews = array();

        try {
            $response = $api->getFulfillmentPreview( $request );

            $data = $response->getPayload();
            $previewList = $data->getFulfillmentPreviews();

            if ( is_array( $previewList ) ) {
                foreach ( $previewList as $preview ) {
                    $key = (string)$preview->getShippingSpeedCategory();

                    $previews[ $key ] = new stdClass();
                    $previews[ $key ]->ShippingSpeedCategory        = $preview->getShippingSpeedCategory();
                    $previews[ $key ]->ScheduledDeliveryInfo        = $preview->getScheduledDeliveryInfo();
                    $previews[ $key ]->IsFulfillable                = $preview->getIsFulfillable();
                    $previews[ $key ]->IsCODCapable                 = $preview->getIsCODCapable();

                    if ( $preview->getEstimatedShippingWeight() ) {
                        $previews[ $key ]->EstimatedShippingWeightUnit  = $preview->getEstimatedShippingWeight()->getUnit();
                        $previews[ $key ]->EstimatedShippingWeightValue = $preview->getEstimatedShippingWeight()->getValue();
                    }
                    $previews[ $key ]->UnfulfillablePreviewItems    = $preview->getUnfulfillablePreviewItems();
                    $previews[ $key ]->OrderUnfulfillableReasons    = $preview->getOrderUnfulfillableReasons();

                    $EstimatedFees = array();
                    $TotalShippingFee = 0;
                    if ( $preview->getEstimatedFees() ) {
                        $EstimatedFeesList = $preview->getEstimatedFees();

                        foreach ( $EstimatedFeesList as $fee ) {
                            $feeKey = $fee->getName();
                            $EstimatedFees[ $feeKey ] = $fee->getAmount()->getValue();

                            /**
                             * #23599
                             * Apparently, FBAPerUnitFulfillmentFee is already the total value for all items in the cart
                             *
                            if ( 'FBAPerUnitFulfillmentFee' == $feeKey ) {
                            $TotalShippingFee    += $fee->getAmount()->getValue() * $total_number_of_units;
                            } else {
                            $TotalShippingFee    += $fee->getAmount()->getValue();
                            }
                             */
                            $TotalShippingFee    += (float)$fee->getAmount()->getValue();
                        }
                    }

                    $previews[ $key ]->EstimatedFees                = $EstimatedFees;
                    $previews[ $key ]->TotalShippingFee             = $TotalShippingFee;

                    $FulfillmentPreviewShipments = array();
                    $shipment = null;
                    if ( $preview->getFulfillmentPreviewShipments() ) {
                        $FulfillmentPreviewShipmentsList = $preview->getFulfillmentPreviewShipments();
                        foreach ( $FulfillmentPreviewShipmentsList as $shipmentKey => $shipment ) {
                            $FulfillmentPreviewShipments[ $shipmentKey ] = new stdClass();
                            $FulfillmentPreviewShipments[ $shipmentKey ]->EarliestShipDate = $shipment->getEarliestShipDate();
                            $FulfillmentPreviewShipments[ $shipmentKey ]->LatestShipDate = $shipment->getLatestShipDate();
                            $FulfillmentPreviewShipments[ $shipmentKey ]->EarliestArrivalDate = $shipment->getEarliestArrivalDate();
                            $FulfillmentPreviewShipments[ $shipmentKey ]->LatestArrivalDate = $shipment->getLatestArrivalDate();
                            // $FulfillmentPreviewShipments[ $shipmentKey ]->FulfillmentPreviewItems = $shipment->getFulfillmentPreviewItems();
                        }
                    }

                    $previews[ $key ]->FulfillmentPreviewShipments  = $FulfillmentPreviewShipments;
                    $previews[ $key ]->EarliestArrivalDate          = is_object($shipment) ? $shipment->getEarliestArrivalDate() : null;
                    $previews[ $key ]->LatestArrivalDate            = is_object($shipment) ? $shipment->getLatestArrivalDate() : null;
                }

                // sort previews from Standard to Priority
                $sorted_previews = array();
                if ( isset( $previews['Standard']  ) ) $sorted_previews['Standard']  = $previews['Standard'];
                if ( isset( $previews['Expedited'] ) ) $sorted_previews['Expedited'] = $previews['Expedited'];
                if ( isset( $previews['Priority']  ) ) $sorted_previews['Priority']  = $previews['Priority'];

                $result = new stdClass();
                $result->previews = $sorted_previews;
                $result->success = true;
                return $result;
            }
        } catch ( \SellingPartnerApi\ApiException $e ) {
            WPLA()->logger->error( 'SP-API Exception: '. $e->getMessage() );

            $error = new stdClass();
            $error->ErrorMessage = $e->getMessage();
            $error->ErrorCode    = $e->getCode();
            return $error;
        }

        $result = new stdClass();
        $result->success = false;
        return $result;
    }

    /**
     * ListMarketplaceParticipations (V2)
     * @return stdClass
     */
    public function listMarketplaceParticipations() {
        $this->initAPI('Sellers');

        $request = new SellersApi( $this->config, $this->client );
        $allowed_markets = array();

        // invoke request
        try {
            $response = $request->getMarketplaceParticipations();
            $markets  = $response->getPayload();


            if ( $markets ) {
                foreach ( $markets as $market ) {
                    $marketplace = $market->getMarketplace();
                    $participation = $market->getParticipation();

                    $key = $marketplace->getId();

                    $allowed_markets[ $key ] = new stdClass();
                    $allowed_markets[ $key ]->MarketplaceId       = $marketplace->getId();
                    $allowed_markets[ $key ]->Name                = $marketplace->getName();
                    $allowed_markets[ $key ]->DefaultLanguageCode = $marketplace->getDefaultLanguageCode();
                    $allowed_markets[ $key ]->DefaultCountryCode  = $marketplace->getCountryCode();
                    $allowed_markets[ $key ]->DefaultCurrencyCode = $marketplace->getDefaultCurrencyCode();
                    $allowed_markets[ $key ]->DomainName          = $marketplace->getDomainName();

                    $allowed_markets[ $key ]->IsParticipating               = $participation->getIsParticipating();
                    $allowed_markets[ $key ]->HasSellerSuspendedListings    = $participation->getHasSuspendedListings();
                }

                $result = new stdClass();
                $result->allowed_markets = $allowed_markets;
                $result->success = true;
                return $result;

            }

        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            return $error;
        } catch (Exception $ex) {
            // Also catch Exceptions because toXML and fromXML methods throw Exceptions on invalid XML strings
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();
            return $error;
        }
        $result = new stdClass();

        $result->success = false;
        return $result;
    }

    /**
     * @param string $feed_type from \SellingPartnerApi\FeedType
     * @param string $feed_content
     * @param null $feed_options
     * @param null $marketplace_ids
     * @return stdClass
     */
    public function submitFeed( $feed_type, $feed_content, $feed_options = null, $marketplace_ids = null ) {
        $this->initAPI();

        if ( is_null( $marketplace_ids ) ) {
            $marketplace_ids = array( $this->account->marketplace_id );
        } else {
            $marketplace_ids = maybe_unserialize( $marketplace_ids );
        }

        $api        = new FeedsApi( $this->config, $this->client );
        $feed_type  = WPLA_AmazonFeed::getFeedType( $feed_type );

        try {
            // Make sure charset is UTF8 for UPLOAD_VAT_INVOICE feeds
            $content_type = $feed_type['contentType'];

            // Reverted because it's causing a signature mismatch
            //if ( $feed_type['name'] == 'UPLOAD_VAT_INVOICE' ) {
            //    $content_type .= '; charset=UTF-8';
            //}

            $feed_doc_spec = new Feeds\CreateFeedDocumentSpecification(['content_type' => $content_type]);
            $feed = $api->createFeedDocument($feed_doc_spec);

            $feed_doc_id = $feed->getFeedDocumentId();

            // Upload feed contents to document
            $feed_document = new SellingPartnerApi\Document($feed, $feed_type, $this->client );
            $feed_document->upload($feed_content);

            // ... call FeedsApi::createFeed() with $feedDocumentId
            $feed_spec = new Feeds\CreateFeedSpecification();
            $feed_spec
                ->setFeedType( $feed_type['name'] )
                ->setMarketplaceIds( $marketplace_ids )
                ->setInputFeedDocumentId( $feed_doc_id );

            WPLA()->logger->debug('feed_options:'. $feed_options);
            if ( !empty( $feed_options ) ) {
                $feed_options_array = self::feedOptionsToArray( $feed_options );
                WPLA()->logger->info('Feed options set!' .print_r($feed_options_array,1));
                $feed_spec->setFeedOptions( $feed_options_array );
            }

            $response = $api->createFeed( $feed_spec );
            $feed_id  = $response->getFeedId();

            // Call GetFeed initially to get information about this feed
            $feed_data = $this->getFeed( $feed_id );

            $result = new stdClass();

            $result->FeedSubmissionId     = $feed_data->getFeedId();
            $result->FeedDocumentId       = $feed_doc_id;
            $result->FeedProcessingStatus = $feed_data->getProcessingStatus();
            $result->SubmittedDate        = $feed_data->getCreatedTime();
            $result->success              = true;

            return $result;
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            return $error;
        } catch (Exception $ex) {
            // Also catch Exceptions because toXML and fromXML methods throw Exceptions on invalid XML strings
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();
            return $error;
        }

    }

    /**
     * Calls the Feeds/GetFeed endpoint to get information and status on the given feed_id
     *
     * @param int $feed_id
     * @return array|Feeds\Feed|stdClass
     */
    public function getFeed( $feed_id ) {
        $this->initAPI( 'Feeds' );

        $api = new FeedsApi( $this->config, $this->client );

        try {
            return $api->getFeed( $feed_id );
        }  catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            return $error;
        } catch (Exception $ex) {
            // Also catch Exceptions because toXML and fromXML methods throw Exceptions on invalid XML strings
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();
            return $error;
        }
    }

    /**
     * Returns the information required for retrieving a feed document's contents.
     *
     * @param $feed_document_id
     * @return array|Feeds\FeedDocument|stdClass
     */
    public function getFeedDocument( $feed_document_id ) {
        $this->initAPI( 'Feeds' );
        $api = new FeedsApi( $this->config, $this->client );

        try {
            $doc     = $api->getFeedDocument( $feed_document_id );
            $request = wp_remote_get( $doc->getUrl(), ['timeout' => 30] );

            if ( is_wp_error( $request ) ) {
                WPLA()->logger->error( 'Error downloading Feed Document: '. $request->get_error_message() );
                throw new Exception( $request->get_error_message(), $request->get_error_code() );
            }

            return wp_remote_retrieve_body( $request );
        }  catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            return $error;
        } catch (Exception $ex) {
            // Also catch Exceptions because toXML and fromXML methods throw Exceptions on invalid XML strings
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();
            return $error;
        }
    }

    /**
     * @param array $feed_types
     * @param array $marketplace_ids
     * @param string $next_token
     * @param int $page_size
     * @param array $processing_statuses
     * @param string $created_since
     * @param string $created_until
     * @return array|Feeds\GetFeedsResponse|stdClass
     */
    public function getFeeds( $feed_types = [], $marketplace_ids = [], $next_token = '', $page_size = 10, $processing_statuses = [], $created_since = null, $created_until = null ) {
        $this->initAPI( 'Feeds' );
        $api = new FeedsApi( $this->config, $this->client );

        try {
            return $api->getFeeds(  $feed_types, $marketplace_ids, $page_size, $processing_statuses, $created_since, $created_until, $next_token );
        }  catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            return $error;
        } catch (Exception $ex) {
            // Also catch Exceptions because toXML and fromXML methods throw Exceptions on invalid XML strings
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();
            return $error;
        }
    }

    /**
     * Cancel feed submission
     * @param int $feed_id
     * @return stdClass
     */
    public function cancelFeed( $feed_id ) {
        $this->initAPI('Feeds');

        $api = new FeedsApi($this->config, $this->client);

        try {
            $api->cancelFeed($feed_id);
            return $this->getFeed($feed_id);
        } catch (\SellingPartnerApi\ApiException $ex) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode = $ex->getCode();
            return $error;
        } catch (Exception $ex) {
            // Also catch Exceptions because toXML and fromXML methods throw Exceptions on invalid XML strings
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode = $ex->getCode();
            $error->StatusCode = $ex->getCode();
            return $error;
        }
    }

    /**
     * Listings API
     */

    /**
     * getListingsItem call
     * @param string $sku
     * @return array|\SellingPartnerApi\Model\ListingsV20210801\Item|stdClass
     */
    public function getListingsItem( $sku ) {
        $this->initAPI( 'Listings' );
        $api = new ListingsApi( $this->config, $this->client );

        try {
            return $api->getListingsItem( $this->account->merchant_id, $sku, [$this->account->marketplace_id], null, 'summaries,attributes,issues,offers,fulfillmentAvailability,procurement' );
        } catch (\SellingPartnerApi\ApiException $ex) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode = $ex->getCode();
            return $error;
        } catch (Exception $ex) {
            // Also catch Exceptions because toXML and fromXML methods throw Exceptions on invalid XML strings
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode = $ex->getCode();
            $error->StatusCode = $ex->getCode();
            return $error;
        }
    }

    /**
     * Catalog API
     */

    /**
     * @param string $asin
     * @return array|Catalog\Item|stdClass
     */
    public function getCatalogItem( $asin, $included_data = [] ) {
        $this->initAPI( 'Catalog' );
        $api = new CatalogApi( $this->config, $this->client );

        if ( empty( $included_data ) ) {
            $included_data = ['summaries','attributes','identifiers','images','productTypes','relationships'];
        }

        try {
            return $api->getCatalogItem(
                $asin,
                [$this->account->marketplace_id],
                $included_data,
                null
            );
        } catch (\SellingPartnerApi\ApiException $ex) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode = $ex->getCode();
            return $error;
        } catch (Exception $ex) {
            // Also catch Exceptions because toXML and fromXML methods throw Exceptions on invalid XML strings
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode = $ex->getCode();
            $error->StatusCode = $ex->getCode();
            return $error;
        }
    }

    /**
     * @param $sku
     * @return Catalog\Item[]|stdClass
     */
    public function getCatalogItemBySku( $sku ) {
        $this->initAPI( 'Catalog' );
        $api = new CatalogApi( $this->config, $this->client );

        try {
            $results = $api->searchCatalogItems(
                [$this->account->marketplace_id],
                [$sku],
                ['SKU'],
                'summaries,attributes,identifiers,images,productTypes',
                null,
                $this->account->merchant_id,
                null
            );

            return $results->getItems();
        } catch ( Exception $ex ) {
            WPLA()->logger->error( 'Caught exception code ('. $ex->getCode() .'): '. $ex->getMessage() );

            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->success      = false;

            return $error;
        }
    }

    /**
     * SearchCatalogItems call
     *
     * @param array $keywords
     * @return Catalog\ItemSearchResults|stdClass
     */
    public function searchCatalogItems( $keywords = [], $identifiers = null, $identifiers_type = null, $seller_id = null ) {
        //WPLA()->logger->info( 'searchCatalogItems() - '. implode( ', ', (array)$keywords ) );
        $this->initAPI( 'Catalog' );

        $api = new CatalogApi( $this->config, $this->client );

        try {
            $results = $api->searchCatalogItems(
                [$this->account->marketplace_id],
                $identifiers,
                $identifiers_type,
                ['summaries','attributes','identifiers','images','productTypes','relationships'],
                null,
                $seller_id,
                (array)$keywords
            );

            return $results;
        } catch ( Exception $ex ) {
            WPLA()->logger->error( 'Caught exception code ('. $ex->getCode() .'): '. $ex->getMessage() );

            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->success      = false;

            return $error;
        }
    }

    /**
     * SearchCatalogItems call
     *
     * @param string[] $identifiers
     * @param string $identifier_type ASIN SKU UPC etc
     * @return Catalog\ItemSearchResults|stdClass
     */
    public function searchCatalogItemsByIdentifier( $identifiers, $identifier_type = 'SKU' ) {
        //WPLA()->logger->info( 'searchCatalogItems() - '. implode( ', ', (array)$keywords ) );
        $this->initAPI( 'Catalog' );

        $api = new CatalogApi( $this->config, $this->client );

        try {
            $results = $api->searchCatalogItems(
                [$this->account->marketplace_id],
                $identifiers,
                $identifier_type,
                ['summaries','attributes','identifiers','images','productTypes','relationships'],
                null,
                $this->account->merchant_id
            );

            return $results;
        } catch ( Exception $ex ) {
            WPLA()->logger->error( 'Caught exception code ('. $ex->getCode() .'): '. $ex->getMessage() );

            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->success      = false;

            return $error;
        }
    }

    /**
     * @param SellingPartnerApi\Model\CatalogItemsV20220401\Item $item
     * @param string $size L or S
     * @return string Primary Image URL
     */
    public static function getPrimaryImageFromCatalog( $item, $size = 'L' ) {
        $url    = '';
        $images = self::getImagesFromCatalog( $item );

        foreach ($images as $image ) {
            if ( $image->getVariant() == 'MAIN' ) {
                if ( strtolower($size) == 's' ) {
                    $url = self::getSmallImageUrl( $image );
                } else {
                    // get 600px image instead of 75px
                    $url = self::getLargeImageUrl( $image );
                }

                break;
            }
        }

        return $url;
    }

    /**
     * @param SellingPartnerApi\Model\CatalogItemsV20220401\Item $item
     * @return SellingPartnerApi\Model\CatalogItemsV20220401\ItemImage[]
     */
    public static function getImagesFromCatalog( $item ) {
        $images = [];

        $container = $item->getImages();

        foreach ( $container as $row ) {
            $item_images = $row->getImages();
            foreach ( $item_images as $item_image ) {
                $variant = $item_image->getVariant();
                if ( !isset( $images[ $variant ] ) ) {
                    $images[ $variant ] = $item_image;
                }
            }

        }
        return $images;
    }

    /**
     * Get the URL of the large image from an Amazon media URL
     * @param \SellingPartnerApi\Model\CatalogItemsV20220401\ItemImage $image
     */
    public static function getLargeImageUrl( $image ) {
        $url = $image->getLink();

        if ( strpos( $url, '_SL75_' ) !== false ) {
            $url = str_replace( '._SL75_', '._SL600_', $url );
        } else {
            $parts = explode( '.', $url );
            array_splice( $parts, count($parts)-1, 0, ['_SL600_'] );

            $url = implode( '.', $parts );
        }

        return $url;
    }

    /**
     * Get the URL of the small image from an Amazon media URL
     * @param \SellingPartnerApi\Model\CatalogItemsV20220401\ItemImage $image
     */
    public static function getSmallImageUrl( $image ) {
        $url = $image->getLink();

        if ( strpos( $url, '_SL600_' ) !== false ) {
            $url = str_replace( '._SL600_', '._SL75_', $url );
        } else {
            $parts = explode( '.', $url );
            array_splice( $parts, count($parts)-1, 0, ['_SL75_'] );

            $url = implode( '.', $parts );
        }

        return $url;
    }

    /**
     * Get all image urls for the catalog item
     *
     * @param SellingPartnerApi\Model\CatalogItemsV20220401\Item $item
     * @return array
     */
    public static function getImageUrlsFromCatalog( $item ) {
        $images = self::getImagesFromCatalog( $item );
        $urls   = [];

        foreach ( $images as $variant => $image ) {
            $url = self::getLargeImageUrl( $image );

            if ( in_array( $url, $urls ) ) {
                continue;
            }

            $urls[ $variant ] = $url;
        }

        return $urls;
    }

    /**
     * Pricing API
     * @param string[] $product_ids
     * @return ProductPricing\CompetitivePriceType[]
     */
    public function getCompetitivePricing( $product_ids ) {
        WPLA()->logger->info('getCompetitivePricing() - '.join(', ',$product_ids));
        $this->initAPI( 'Pricing' );

        $api = new ProductPricingApi( $this->config, $this->client );

        try {
            $response = $api->getCompetitivePricing($this->account->marketplace_id, 'Asin', $product_ids);

            $all_prices = [];

            foreach ( $response->getPayload() as $price_row ) {
                $product = $price_row->getProduct();

                if ( $product ) {
                    $asin           = $price_row->getAsin();
                    $product_prices = $product->getCompetitivePricing()->getCompetitivePrices();

                    $all_prices[ $asin ] = $product_prices;
                } else {
                    // Error from the API: mark this ASIN as updated to keep it from getting called over and over
                    WPLA()->logger->info( 'Error fetching pricing for ASIN '. $price_row->getAsin() );
                    $lm = new WPLA_ListingsModel();
                    $data = array();
                    $data['pricing_date'] 	  = gmdate('Y-m-d H:i:s', time());
                    $lm->updateWhere( array('asin' => $price_row->getAsin(), 'account_id' => $this->account->id), $data );
                }
            }

            return $all_prices;
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();

            $error->success      = false;
            WPLA()->logger->error( $ex->getMessage() );
            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            $error->success      = false;
            WPLA()->logger->error( $ex->getMessage() );
            return $error;
        }

    }

    /**
     *
     * @param string[] $asins Array of ASINs
     * @param string $item_condition Possible values: New, Used, Collectible, Refurbished, Club
     * @return ProductPricing\GetOffersResult[]
     */
    public function getItemOffers( $asins, $item_condition = 'New' ) {
         WPLA()->logger->info('getItemOffers() - '.join(', ',$asins));

        $this->initAPI( 'Pricing' );
        $api = new ProductPricingApi( $this->config, $this->client );

        try {
            $requests = [];

            foreach ( $asins as $asin ) {
                $request = new ProductPricing\ItemOffersRequest();
                $request
                    ->setMethod( ProductPricing\HttpMethod::GET )
                    ->setMarketplaceId( $this->account->marketplace_id)
                    ->setCustomerType( ProductPricing\CustomerType::CONSUMER )
                    ->setItemCondition( ProductPricing\ItemCondition::_NEW )
                    ->setUri( "/products/pricing/v0/items/{$asin}/offers" );

                $requests[] = $request;
            }

            $batch = new ProductPricing\GetItemOffersBatchRequest();
            $batch->setRequests( $requests );
            $response = $api->getItemOffersBatch( $batch );

            $all_offers = [];
            $responses = $response->getResponses();

            foreach ( $responses as $response ) {
                $body = $response->getBody()->getPayload();

                $asin = $response->getRequest()->getAsin();
                $all_offers[ $asin ] = $body;
            }

            return $all_offers;
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();

            $error->success      = false;
            WPLA()->logger->error( $ex->getMessage() );
            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            $error->success      = false;
            WPLA()->logger->error( $ex->getMessage() );
            return $error;
        }

    }


    /**
     * Orders API
     */

    /**
     * @param DateTime $date
     * @return Orders\Order[]|stdClass
     */
    public function getOrdersFromDate( $date ) {
        $this->initAPI( 'Orders' );
        $api = new OrdersApi( $this->config, $this->client );

        $marketplace_ids        = [];
        if ( is_array( $this->allowed_marketplace_ids ) && ! empty( $this->allowed_marketplace_ids ) ) {
            foreach ($this->allowed_marketplace_ids as $MarketplaceId) {
                $marketplace_ids[] = $MarketplaceId; // fetch orders from all marketplaces for account
            }
        } else {
            $marketplace_ids[] = $this->MarketplaceId; // fall back
        }

        try {
            $date_from  = new DateTime( $date->format('m/d/Y') );
            $date_to    = new DateTime( $date->format( 'm/d/Y' ) );

            $date_from->setTime(0, 0, 0 );
            $date_to->setTime( 23, 59, 59 );

            $response   = $api->getOrders( $marketplace_ids, null, null, $date_from->format('Y-m-d\TH:i:s.\\0\\0\\0\\Z'), $date_to->format('Y-m-d\TH:i:s.\\0\\0\\0\\Z') );
            $order_list = $response->getPayload();

            if ( $orders = $order_list->getOrders() ) {
                $next_token = $order_list->getNextToken();

                while ( $next_token ) {
                    $response   = $api->getOrders( $marketplace_ids, null, null, null, null, null, null, null, null, null, null, null, $next_token );
                    $order_list = $response->getPayload();

                    if ( $new_orders = $order_list->getOrders() ) {
                        $orders = array_merge( $orders, $new_orders );
                    }

                    $next_token = $order_list->getNextToken();
                }
            }

            return $orders;
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();
            $error->success      = false;

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            $error->ErrorType    = 'Sender';
            $error->success      = false;

            return $error;
        }
    }

    /**
     * @param $from_date
     * @param bool $days
     * @return array|Orders\Order[]|stdClass
     */
    public function getOrders( $from_date, $days = false ) {
        $this->initAPI( 'Orders' );
        $api = new OrdersApi( $this->config, $this->client );

        $last_updated_after     = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", strtotime( $from_date.' UTC' ) + 0 ); // no offset - will return the most recent order(s) as well
        $enable_orders_filter   = get_option( 'wpla_fetch_orders_filter', 0 );
        $marketplace_ids        = [];

        if ( is_array( $this->allowed_marketplace_ids ) && ! empty( $this->allowed_marketplace_ids ) && ! $enable_orders_filter ) {
            foreach ($this->allowed_marketplace_ids as $MarketplaceId) {
                $marketplace_ids[] = $MarketplaceId; // fetch orders from all marketplaces for account
            }
        } else {
            $marketplace_ids[] = $this->MarketplaceId; // fall back
        }

        // handle custom number of days
        if ( $days ) {
            $last_updated_after = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time() - $days * 24 * 3600 );
        }

        try {
            // sandbox call
            //$response   = $api->getOrders( $marketplace_ids, 'TEST_CASE_200', null, null );
            $response   = $api->getOrders( $marketplace_ids, null, null, $last_updated_after );

            // Commented out due to the response not including PII even if an RDT is used in the request
            /*$response   = $api->getOrders(
                $marketplace_ids, null, null, $last_updated_after,  null, null,
                null, null, null, null, null, null, null,
                null, null, null, null, ['buyerInfo', 'shippingAddress']
            );*/
            $order_list = $response->getPayload();

            if ( $orders = $order_list->getOrders() ) {
                $next_token = $order_list->getNextToken();

                while ( $next_token ) {
                    $response   = $api->getOrders(
                        $marketplace_ids, null, null, null,  null, null,
                        null, null, null, null, null, null, $next_token
                    );

                    // Commented out due to the response not including PII even if an RDT is used in the request
                    /*$response   = $api->getOrders(
                        $marketplace_ids, null, null, null,  null, null,
                        null, null, null, null, null, null, $next_token,
                        null, null, null, null, ['buyerInfo', 'shippingAddress']
                    );*/
                    $order_list = $response->getPayload();

                    if ( $new_orders = $order_list->getOrders() ) {
                        $orders = array_merge( $orders, $new_orders );
                    }

                    $next_token = $order_list->getNextToken();
                }
            }

            return $orders;
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();
            $error->success      = false;

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            $error->ErrorType    = 'Sender';
            $error->success      = false;

            return $error;
        }
    }


    /*
     * @param string $order_id
     */
    public function getOrder( $order_id ) {
        $this->initAPI('Orders');

        $api = new OrdersApi( $this->config, $this->client );

        try {
            $response   = $api->getOrder( $order_id, ['buyerInfo', 'shippingAddress'] );
            $order      = $response->getPayload();

            if ( $order ) {
                return $order;
            }

            return [];
        }  catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            $error->ErrorType    = 'Sender';
            $error->success      = false;

            return $error;
        }

    }

    /**
     * @param string $amazon_order_id
     * @param bool $include_order_item_info Set to TRUE to call getOrderItemsBuyerInfo and attach the meta to the items
     * @return Orders\OrderItem[]|stdClass
     */
    public function getOrderItems( $amazon_order_id, $include_order_item_info = false ) {
        $this->initAPI( 'Orders' );

        $api = new OrdersApi( $this->config, $this->client );

        try {
            $response = $api->getOrderItems( $amazon_order_id );
            $list = $response->getPayload();

            $items = $list->getOrderItems();
            $next_token = $list->getNextToken();

            while ( $next_token ) {
                $response   = $api->getOrderItems( $amazon_order_id, $next_token );
                $list       = $response->getPayload();
                $new_items  = $list->getOrderItems();

                if ( $new_items ) {
                    $items = array_merge( $items, $new_items );
                }

                $next_token = $list->getNextToken();
            }

            if ( $include_order_item_info ) {
                $items = $this->matchOrderItemsBuyerInfo( $items, $amazon_order_id );
            }

            return $items;
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            $error->ErrorType    = 'Sender';
            $error->success      = false;

            return $error;
        }
    }

    /**
     * @param string $amazon_order_id
     * @return Orders\OrderBuyerInfo|stdClass
     */
    public function getOrderBuyerInfo( $amazon_order_id ) {
        $this->initAPI( 'Orders' );

        $api = new OrdersApi( $this->config, $this->client );

        try {
            $response = $api->getOrderBuyerInfo( $amazon_order_id );
            return $response->getPayload();
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            $error->ErrorType    = 'Sender';
            $error->success      = false;

            return $error;
        }
    }

    public function getOrderItemsBuyerInfo( $amazon_order_id, $next_token = null ) {
        $this->initAPI( 'Orders' );

        $api = new OrdersApi( $this->config, $this->client );

        try {
            $response = $api->getOrderItemsBuyerInfo( $amazon_order_id, $next_token );
            return $response->getPayload();
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            $error->ErrorType    = 'Sender';
            $error->success      = false;

            return $error;
        }
    }

    /**
     * @param Orders\OrderItem[] $order_items
     * @param string $amazon_order_id
     * @return Orders\OrderItem[]
     */
    public function matchOrderItemsBuyerInfo( $order_items, $amazon_order_id ) {
        $buyer_info = $this->getOrderItemsBuyerInfo( $amazon_order_id );

        if ( !isset( $buyer_info->ErrorMessage ) ) {
            foreach ( $buyer_info->getOrderItems() as $buyer_info_item ) {
                foreach ( $order_items as $idx => $order_item ) {
                    if ( $buyer_info_item->getOrderItemId() == $order_item->getOrderItemId() ) {

                        $order_items[ $idx ]->setBuyerInfo( $buyer_info_item );
                    }
                }

            }
        }

        return $order_items;
    }

    /**
     * @param $amazon_order_id
     * @return Orders\OrderAddress|stdClass
     */
    public function getOrderAddress( $amazon_order_id ) {
        $this->initAPI( 'Orders' );

        $api = new OrdersApi( $this->config, $this->client );

        try {
            $response = $api->getOrderAddress( $amazon_order_id );
            return $response->getPayload();
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            $error->ErrorType    = 'Sender';
            $error->success      = false;

            return $error;
        }
    }

    /**
     * @deprecated Use WPLA_Amazon_SP_API::getOrderItems()
     * @param $amazon_order_id
     * @return array|mixed|null
     */
    public function getOrderLineItems( $amazon_order_id ) {
        // $this->GetServiceStatus();
        return $this->getOrderItems( $amazon_order_id );
    }

    /**
     * Reports API
     */

    /**
     * @param array $report_types
     * @param array $processing_statuses
     * @param array $marketplace_ids
     * @param string $next_token
     * @return array|Reports\Report[]|stdClass
     */
    public function getReports( $report_types = [], $processing_statuses = [], $marketplace_ids = [], $next_token = ''  ) {
        $this->initAPI( 'Reports' );
        $api = new ReportsApi( $this->config, $this->client );

        try {
            $response = $api->getReports( $report_types, $processing_statuses, $marketplace_ids, null, null, null, $next_token );
            $reports = $response->getReports();
            $next_token = $response->getNextToken();

            while ( $next_token ) {
                $response = $api->getReports( $report_types, $processing_statuses, $marketplace_ids, null, null, null, $next_token );
                $reports = array_merge( $reports, $response->getReports() );
            }

            return $reports;
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->HeaderMeta   = $ex->getResponseHeaders();

        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            return $error;
        }
    }

    /**
     * @param string $report_id
     * @return Reports\Report|stdClass
     */
    public function getReport( $report_id ) {
        $this->initAPI( 'Reports' );
        $api = new ReportsApi( $this->config, $this->client );

        try {
            return $api->getReport( $report_id );
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            return $error;
        }
    }

    public function getReportDocumentBody( $document_id ) {
        $this->initAPI( 'Reports' );
        $api = new ReportsApi( $this->config, $this->client );

        try {
            $doc = $api->getReportDocument( $document_id );

            $response = wp_remote_get( $doc->getUrl(), ['timeout' => 30] );

            if ( is_wp_error( $response ) ) {
                throw new Exception( $response->get_error_message(), $response->get_error_code() );
            }

            $body    = wp_remote_retrieve_body( $response );
            $decoded = $body;

            /**
             * Use ReportDocument::getCompressionAlgorithm from the API to determine if we need to decode the data
             */
            if ( $doc->getCompressionAlgorithm() == 'GZIP' ) {
                $decoded = gzdecode( $body );
            }
            /*$content_type = wp_remote_retrieve_header( $response, 'content-type' );
            if ( strpos( $content_type, 'text/plain' ) === false ) {
                $decoded = gzdecode( $body );

                if ( false === $decoded ) {
                    $decoded = $body;
                }
            }*/

            return $decoded;
            //return gzdecode( wp_remote_retrieve_body( $response ) );
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();

            return $error;
        }

    }

    /**
     *
     * @return stdClass|string The Report ID (string) or an error object
     */
    public function createReport( $report_type ) {
        $report_type_array = WPLA_AmazonReport::getReportType( $report_type );
        $report_type = $report_type_array['name'];


        $this->initAPI( 'Reports' );
        $api = new ReportsApi( $this->config, $this->client );

        $spec = new Reports\CreateReportSpecification();
        $spec
            ->setMarketplaceIds( [ $this->MarketplaceId ] )
            ->setReportType( $report_type );

        // some reports require a StartDate...
        $report_types_requiring_startdate = array('_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_', 'GET_AMAZON_FULFILLED_SHIPMENTS_DATA_GENERAL', 'GET_FLAT_FILE_ORDER_REPORT_DATA_INVOICING');
        if ( in_array( $report_type, $report_types_requiring_startdate ) ) {
            $startdate = date('Y-m-d', strtotime(apply_filters( 'wpla_report_timespan_startdate', '-1 week') ) ); // hardcoded to one week for now
            $spec->setDataStartTime( $startdate . 'T00:00:00+00:00' );
        }

        /** TEST DATA */
        /*$report_type      = \SellingPartnerApi\ReportType::GET_MERCHANT_LISTINGS_ALL_DATA;
        $spec = new Reports\CreateReportSpecification();
        $spec
            ->setMarketplaceIds( [ 'A1PA6795UKMFR9', 'ATVPDKIKX0DER' ] )
            ->setReportType( $report_type['name'] )
            ->setDataStartTime( '2019-12-10T20:11:24.000Z' );
        ****/

        try {
            $response = $api->createReport( $spec );
            return $response->getReportId();
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();

            return $error;
        }
    }

    public function cancelReport( $report_id ) {
        $this->initAPI( 'Reports' );
        $api = new ReportsApi( $this->config, $this->client );

        try {
            $api->cancelReport( $report_id );
            return $api->getReport( $report_id );
        } catch ( \SellingPartnerApi\ApiException $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            return $error;
        } catch ( Exception $ex ) {
            $error = new stdClass();
            $error->ErrorMessage = $ex->getMessage();
            $error->ErrorCode    = $ex->getCode();
            $error->StatusCode   = $ex->getCode();

            return $error;
        }
    }

    /**
     * Merchant Fulfillment API
     */

    /**
     * @param MerchantFulfillment\GetEligibleShipmentServicesRequest $body
     * @return array|MerchantFulfillment\GetEligibleShipmentServicesResponse
     * @throws \SellingPartnerApi\ApiException
     */
    public function getEligibleShipmentServices( $body ) {
        $this->initAPI();
        $api = new \SellingPartnerApi\Api\MerchantFulfillmentV0Api( $this->config, $this->client );


        return $api->getEligibleShipmentServices( $body );
    }

    public static function isError( $response ) {
        if ( is_object( $response ) && isset( $response->ErrorMessage ) ) {
            return true;
        }

        return false;
    }

    public function setMerchantId( $merchant_id ) {
        $this->SellerId = $merchant_id;
    }
    public function setMarketplaceId( $marketplace_id ) {
        $this->MarketplaceId = $marketplace_id;
    }
    public function setSPAuthCode( $auth_code ) {
        $this->SPAuthCode = $auth_code;
    }
    public function setSPRefreshToken( $refresh_token ) {
        $this->SPRefreshToken = $refresh_token;
    }
    public function setSPAccessToken( $access_token ) {
        $this->SPAccessToken = $access_token;
    }
    public function setSPAccessTokenExpiration( $expiration ) {
        $this->SPAccessTokenExpiration = $expiration;
    }
    public function setAccessKeyId( $access_key_id ) {
        $this->AccessKey = $access_key_id;
    }

    public function setAwsToken( $token ) {
        return $this->AWSToken = $token;
    }
    public function getSPAccessToken() {
        return $this->SPAccessToken;
    }
    public function getSPAccessTokenExpiration() {
        return intval($this->SPAccessTokenExpiration);
    }
    public function getAwsToken() {
        return $this->AWSToken;
    }
    public function setMWSAuthToken( $mws_auth_token ) {
        $this->MWSAuthToken = empty( $mws_auth_token ) ? NULL : $mws_auth_token;
    }
    public function usesMWSAuthToken() {
        return ! empty( $this->MWSAuthToken );
    }
    public function setSecretKey( $secret_key ) {
        $this->SecretKey = $secret_key;
    }
    public function setAllowedMarkets( $allowed_markets ) {
        $allowed_markets = maybe_unserialize( $allowed_markets );
        if ( ! is_array($allowed_markets) ) $allowed_markets = array();
        $this->allowed_marketplace_ids = array();
        foreach ($allowed_markets as $market) {
            // only use marketplaces that begin with www.amazon
            if ( 'www.amazon' == substr($market->DomainName, 0, 10 ) ) {
                $this->allowed_marketplace_ids[] = $market->MarketplaceId;
            }
        }
    }

    // generate special access key with extra information for api proxy
    public function buildSpecialAccessKey( $api_section = false ) {

        if ( '/' == substr( $api_section, 0, 1 ) ) $api_section = substr( $api_section, 1 );

        $wpla_params    = $this->getExtraParameters();
        $wpla_params[]  = $this->api_host . '/' . $api_section;
        $merged_params  = join( '|', $wpla_params );

        return( 'WAK1' . base64_encode( $merged_params ) );
    }

    // fix missing iconv extension
    public static function fixMissingIconv(){

        // if ( ! function_exists('iconv_set_encoding') ) {
        //     // prevent fatal error in Client.php when iconv extension is not loaded
        //     function iconv_set_encoding( $encoding ) {
        //         @ini_set('default_charset', $encoding);
        //     }
        // }

        // set charset here - instead of multiple Client.php files
        self::setDefaultEncoding('UTF-8');

    } // fixMissingIconv()

    // set charset in a way that's compatible with PHP5.6
    public static function setDefaultEncoding($enc) {

        if ( ( PHP_VERSION_ID < 50600 ) && function_exists('iconv_set_encoding') ) {
            iconv_set_encoding('input_encoding',    $enc);
            iconv_set_encoding('output_encoding',   $enc);
            iconv_set_encoding('internal_encoding', $enc);
        } else {
            ini_set('default_charset', $enc);
        }

    } // setDefaultEncoding()

    /**
     * Convert feed_options with the following format to an array:
     *  - metadata:orderid=171-6413711-0868344;metadata:totalamount=13.55;metadata:totalvatamount=0.62;metadata:invoicenumber=132943
     * @param string $feed_options_str
     * @return array
     */
    public static function feedOptionsToArray( $feed_options_str ) {
        $pcs = explode( ';', $feed_options_str );
        $array = [];
        foreach ( $pcs as $pc ) {
            $parts = explode( '=', $pc );
            $array[ trim($parts[0]) ] = trim($parts[1]);
        }

        return $array;
    }

    // additional information required by api proxy for licensing and to prevent abuse
    private function getExtraParameters() {

        $license_email = get_option( 'wpla_activation_email' );
        $wpla_site     = str_replace( array('http://','https://','www.'), '', get_site_url() ); // example.com
        $wpla_email    = $license_email ? $license_email : get_option( 'admin_email' );
        $wpla_license  = WPLA_LIGHT ? 'LITE' : get_option( 'wpla_api_key', 'no_api_key' );

        return array( $wpla_site, $wpla_email, $wpla_license );
    }

    private function constructAccessKey( $params ) {
        $params[]  = $this->api_host;
        $merged_params  = join( '|', $params );

        $access_key = 'WAK1' . base64_encode( $merged_params );

        return $access_key;
    }

    private function constructUserAgentString( $applicationName, $applicationVersion ) {
        $userAgent  = $applicationName . '/' . $applicationVersion;
        $userAgent .= ' (';
        $userAgent .= 'Language=PHP/' . phpversion();
        $userAgent .= '; ';
        $userAgent .= 'Platform=' . php_uname('s') . '/' . php_uname('m') . '/' . php_uname('r');
        $userAgent .= '; ';
        $userAgent .= ')';
        return $userAgent;
    }


} // class WPLA_Amazon_SP_API