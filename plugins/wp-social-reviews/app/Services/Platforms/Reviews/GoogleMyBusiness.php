<?php

namespace WPSocialReviews\App\Services\Platforms\Reviews;

use WPSocialReviews\App\Services\Platforms\Reviews\Helper;
use WPSocialReviews\App\Services\Platforms\Feeds\CacheHandler;
use WPSocialReviews\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Google Reviews Place Id and Api Key
 * @since 1.0.0
 */
class GoogleMyBusiness extends BaseReview
{
    private $remoteBaseUrl = 'https://mybusiness.googleapis.com/v4/';
    private $redirect = 'https://wpsocialninja.com/gapi/';
    private $clientId = '1066221839285-b63ib6vnhv9aed2euhtecbp2nojvq9rp.apps.googleusercontent.com';
    private $clientSecret = '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';
    private $placeId;
    public $nextPageToken = '';
    public $locationNextPageToken = '';

    public function __construct()
    {
        parent::__construct(
            'google',
            'wpsr_reviews_google_settings',
            'wpsr_google_reviews_update'
        );
    }

    public function makeRequest($url, $bodyArgs, $type = 'GET', $headers = false)
    {
        if (!$headers) {
            $headers = array(
                'Content-Type'              => 'application/http',
                'Content-Transfer-Encoding' => 'binary',
                'MIME-Version'              => '1.0',
            );
        }

        $args = [
            'headers' => $headers
        ];
        if ($bodyArgs) {
            $args['body'] = json_encode($bodyArgs);
        }

        $args['method'] = $type;
        $request        = wp_remote_request($url, $args);

        if (is_wp_error($request)) {
            $message = $request->get_error_message();

            return new \WP_Error(423, $message);
        }

        $body = json_decode(wp_remote_retrieve_body($request), true);

        if (!empty($body['error'])) {
            $error = 'Unknown Error';
            if (isset($body['error_description'])) {
                $error = $body['error_description'];
            } elseif (!empty($body['error']['message'])) {
                $error = $body['error']['message'];
            }

            return new \WP_Error(423, $error);
        }

        return $body;
    }

    public function generateAccessKey($token)
    {
        $body = [
            'code'          => $token,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->redirect,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret
        ];

        return $this->makeRequest('https://accounts.google.com/o/oauth2/token', $body, 'POST');
    }

    public function getAccessToken()
    {
        $tokens = get_option('wpsr_reviews_google_verification_configs');
        if (!$tokens) {
            return false;
        }
        if (($tokens['created_at'] + $tokens['expires_in'] - 30) < time()) {
            // It's expired so we have to re-issue again
            $refreshTokens = $this->refreshToken($tokens);

            if (!is_wp_error($refreshTokens)) {
                $tokens['access_token'] = $refreshTokens['access_token'];
                $tokens['expires_in']   = $refreshTokens['expires_in'];
                $tokens['created_at']   = time();
                update_option('wpsr_reviews_google_verification_configs', $tokens, 'no');
            } else {
                return false;
            }
        }

        return $tokens['access_token'];
    }

    private function refreshToken($tokens)
    {

        $clientId = $this->clientId;
        $clientSecret = $this->clientSecret;

        //To support previous Google Authentication Process we must use the Previous App
        if( !isset($tokens['version']) ){
            $clientId = '1066221839285-ckecknkno31o1ma3ti37lv4fb3vlidhi.apps.googleusercontent.com';
            $clientSecret = 'mkhMmZ-0T2VEYuwEkfn5umqm';
        }

        $args = [
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => Arr::get($tokens, 'refresh_token'),
            'grant_type'    => 'refresh_token'
        ];

        return $this->makeRequest('https://accounts.google.com/o/oauth2/token', $args, 'POST');
    }

    public function handleCredentialSave($settings = array())
    {
        $apiKey = $this->getAccessToken();

        if($apiKey) {
            $placeId = Arr::get($settings, 'source_id');

            try {
                $businessInfo = $this->verifyCredential($apiKey, $placeId);

                $myBusinessKey = '';
                $myBusinessKeys = explode('/', $placeId);
                if(!empty($myBusinessKeys)) {
                    $myBusinessKey = $myBusinessKeys[3];
                }

                $message = Helper::getNotificationMessage($businessInfo, $myBusinessKey);

                if (Arr::get($businessInfo, 'total_fetched_reviews') && Arr::get($businessInfo, 'total_fetched_reviews') > 0) {
                    unset($businessInfo['total_fetched_reviews']);
                    update_option('wpsr_reviews_google_connected_accounts', $placeId, 'no');
                }

                // save caches when auto sync is on
                $apiSettings = get_option('wpsr_google_global_settings');
                if(Arr::get($apiSettings, 'global_settings.auto_syncing') === 'true') {
                    $this->saveCache();
                }

                wp_send_json_success([
                    'message' => $message,
                    'business_info' => $businessInfo
                ], 200);
            } catch (\Exception $exception) {
                wp_send_json_error([
                    'message' => $exception->getMessage()
                ], 423);
            }
        } else {
            wp_send_json_error([
                'message' => __('Something went wrong, please try again!', 'wp-social-reviews')
            ], 423);
        }
    }

    public function pushValidPlatform($platforms)
    {
        $settings = $this->getApiSettings();

        if (!isset($settings['data']) && sizeof($settings) > 0) {
            $platforms['google'] = __('Google', 'wp-social-reviews');
        }

        return $platforms;
    }

    public function verifyCredential($apiKey, $placeId)
    {
        $data = $this->fetchRemoteReviews($apiKey, $placeId);

        if (is_wp_error($data)) {
            throw new \Exception($data->get_error_message());
        }

        if(empty($data)) {
            throw new \Exception('No reviews fetched!');
        }

        $this->saveApiSettings([
            'api_key'  => $apiKey,
            'place_id' => $placeId
        ]);

        $business_info = $this->saveBusinessInfo($apiKey, $placeId, $data);

        $this->placeId = $placeId;
        $this->reviewDelete($data['reviews'],$placeId);
        $this->syncRemoteReviews($data['reviews']);

        $totalFetchedReviews = count($data['reviews']);

        if ($totalFetchedReviews > 0) {
            update_option('wpsr_reviews_google_business_info', $business_info, 'no');
        }

        $business_info['total_fetched_reviews'] = $totalFetchedReviews;
        return $business_info;
    }

    public function fetchRemoteReviews($apiKey, $placeId)
    {
        $fetchUrl = $this->remoteBaseUrl.$placeId.'/reviews';

        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $apiKey,
            ),
        );

        $response = wp_remote_get($fetchUrl, $args);

        if (is_wp_error($response)) {
            return $response;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if(Arr::get($data, 'nextPageToken')){
            $data['reviews'] = $this->getNextPageResponse($placeId, $data, $args);
        }


        if(Arr::get($data, 'error')){
            $error_message = Arr::get($data, 'error.message');
            throw new \Exception($error_message);
        }

        if (empty($data) || empty($data['reviews'])) {
            throw new \Exception(
                __('No reviews found!', 'wp-social-reviews')
            );
        }

        return $data;
    }

    public function getNextPageResponse($placeId, $data, $args)
    {
        $reviews = Arr::get($data, 'reviews');
        $this->nextPageToken = Arr::get($data, 'nextPageToken');
        $totalReviewCount = Arr::get($data, 'totalReviewCount');
        $limit = apply_filters('wpsocialreviews/gmb_reviews_limit', 100);
        $limit = $limit > 200 ? 200 : $limit;
        $total = $totalReviewCount >= $limit ? $limit : $totalReviewCount;
        $pageSize = 50;
        $pages = ceil($total/$pageSize);
        $x = 1;
        while($x < $pages) {
            $x++;
            $fetchUrl = $this->remoteBaseUrl.$placeId.'/reviews?pageToken='.$this->nextPageToken;
            $response = wp_remote_get($fetchUrl, $args);
            if (is_wp_error($response)) {
                return $response;
            }
            $data = json_decode(wp_remote_retrieve_body($response), true);
            $this->nextPageToken = Arr::get($data, 'nextPageToken');
            $reviews = array_merge($reviews, Arr::get($data, 'reviews'));
        }
        return $reviews;
    }

    public function formatData($review, $index)
    {
        $accountId = explode('/', $this->placeId);
        $locations     = get_option('wpsr_reviews_google_locations_list');

        $reviewDate = Arr::get($review, 'createTime');
        return [
            'platform_name' => $this->platform,
            'source_id'     => $accountId[3],
            'review_id'     => Arr::get($review, 'reviewId'),
            'reviewer_name' => Arr::get($review, 'reviewer.displayName'),
            'review_title'  => '',
            'reviewer_url'  => 'https://search.google.com/local/reviews?placeid='. $locations[$accountId[3]]['location_key'],
            'reviewer_img'  => Arr::get($review, 'reviewer.profilePhotoUrl'),
            'reviewer_text' => Arr::get($review, 'comment', ''),
            'rating'        => $this->convertRating(Arr::get($review, 'starRating')),
            'review_time'   => date('Y-m-d H:i:s', strtotime($reviewDate)),
            'review_approved' => 1,
            'updated_at'    => date('Y-m-d H:i:s'),
            'created_at'    => date('Y-m-d H:i:s')
        ];
    }

    public function saveBusinessInfo($apiKey, $placeId, $reviewData)
    {
        $businessInfo  = [];
        $infos         = $this->getBusinessInfo();
        $infos = empty($infos) ? [] : $infos;

        if (empty($placeId)) {
            return [];
        }

        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $apiKey,
            ),
        );

        $account = explode('/', $placeId);
        $locationId = $account[3];

        $fetchUrl = "https://mybusinessbusinessinformation.googleapis.com/v1/locations/". $locationId ."?readMask=name,title,phoneNumbers,metadata,profile,relationshipData,serviceArea,serviceItems";
        $response = wp_remote_get($fetchUrl, $args);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        $locationInfo = json_decode(wp_remote_retrieve_body($response), true);

        $locationId = explode('/', $locationInfo['name']);
        $businessInfo['place_id']       = $locationId[1];
        $businessInfo['name']           = Arr::get($locationInfo, 'title', '');
        $businessInfo['url']            = Arr::get($locationInfo, 'metadata.newReviewUri', '');
        $businessInfo['address']        = Arr::get($locationInfo, 'address', '');
        $businessInfo['average_rating'] = Arr::get($reviewData, 'averageRating');
        $businessInfo['total_rating']   = Arr::get($reviewData, 'totalReviewCount');
        $businessInfo['phone']          = Arr::get($locationInfo, 'phoneNumbers.primaryPhone', '');
        $businessInfo['platform_name']  = $this->platform;
        $infos[$locationId[1]]          = $businessInfo;

        return $infos;
    }

    public function getBusinessInfo($data = array())
    {
        return get_option('wpsr_reviews_google_business_info');
    }

    public function saveApiSettings($settings)
    {
        $apiKeyInput       = Arr::get($settings, 'api_key');
        $placeIdInput     = Arr::get($settings, 'place_id');

        $apiSettings  = $this->getApiSettings();

        if(isset($apiSettings['data']) && !$apiSettings['data']) {
            $apiSettings = [];
        }

        if(!empty($apiKeyInput) && !empty($placeIdInput)) {
            $apiSettings[$placeIdInput]['api_key']   = $apiKeyInput;
            $apiSettings[$placeIdInput]['place_id']  = $placeIdInput;
        }

        return update_option($this->optionKey, $apiSettings, 'no');
    }

    public function resolveOldSettings($settings = [])
    {
        $apiKey  = Arr::get($settings, 'api_key');
        $placeId = Arr::get($settings, 'place_id.0');
        if(!empty($apiKey) && !empty($placeId)) {
            $settings[$placeId]['place_id']  = $placeId;
            $settings[$placeId]['api_key']  = $apiKey;

            if(isset($settings['place_id'])) unset($settings['place_id']);
            if(isset($settings['data'])) unset($settings['data']);
            if(isset($settings['api_key'])) unset($settings['api_key']);

            update_option($this->optionKey, $settings, 'no');
        }

        return $settings;
    }

    public function getApiSettings()
    {
        $settings = get_option($this->optionKey);
        $apiSettings          = get_option('wpsr_reviews_google_verification_configs');

        if (!$settings) {
            $settings = [
                'api_key'   => Arr::get($apiSettings, 'access_token', ''),
                'place_id'  => '',
                'data'      => false
            ];
        }

        $settings = $this->resolveOldSettings($settings);
        
        $version = '';
        if (version_compare(WPSOCIALREVIEWS_VERSION,'3.7.1', '>=')) {
            $version = 'latest';
        }

        $settings['version'] = Arr::get($apiSettings, 'version', $version);
        return $settings;
    }

    public function saveConfigs($accessCode = null)
    {
        try {
            if (empty($accessCode) || !$accessCode) {
                wp_send_json_error([
                    'message' => __('Access code should not be empty!', 'wp-social-reviews')
                ], 423);
            }

            $body = $this->generateAccessKey($accessCode);

            if (is_wp_error($body)) {
                throw new \Exception($body->get_error_message());
            }
            $body['created_at'] = time();
            $body['version'] = 'latest';

            $accessToken = Arr::get($body, 'access_token');
            $headers     = array(
                'Authorization' => 'Bearer ' . $accessToken,
            );

            $locationsLists = $this->getBusinessAccountId($headers);
            $body['access_token'] = $accessToken;
            $body['refresh_token'] = Arr::get($body, 'refresh_token');
            update_option('wpsr_reviews_google_verification_configs', $body, 'no');
            wp_send_json_success(
                [
                    'message'      => __('You are Successfully Verified', 'wp-social-reviews'),
                    'locations'    => $locationsLists,
                    'access_token' => $accessToken
                ],
                200
            );
        } catch (\Exception $exception) {
            wp_send_json_error([
                'message' => $exception->getMessage()
            ], 423);
        }
    }

    public function getAdditionalInfo()
    {
        $locationLists      = get_option('wpsr_reviews_google_locations_list');
        $connected_accounts = get_option('wpsr_reviews_google_connected_accounts');

        return [
            'location_lists'     => $locationLists,
            //'connected_accounts' => $connected_accounts ? $connected_accounts : []
        ];
    }

    public function getNextPageAccountsResponse($headers, $accountsData)
    {
        $accounts = Arr::get($accountsData, 'accounts', []);
        $this->nextPageToken = Arr::get($accountsData, 'nextPageToken');

        $total = apply_filters('wpsocialreviews/gmb_reviews_accounts_limit', 60);
        $pageSize = 20;
        $pages = ceil($total/$pageSize);
        $x = 0;

        while(!empty($this->nextPageToken) && $x < $pages) {
            $x++;
            $fetchUrl     = 'https://mybusinessaccountmanagement.googleapis.com/v1/accounts?pageToken='.$this->nextPageToken;
            $accountsData = $this->makeRequest($fetchUrl, false, 'GET', $headers);
            $this->nextPageToken = Arr::get($accountsData, 'nextPageToken');
            $accounts = array_merge($accounts, Arr::get($accountsData, 'accounts'));
        }

        return $accounts;
    }

    public function getBusinessAccountId($headers)
    {
        $fetchUrl     = "https://mybusinessaccountmanagement.googleapis.com/v1/accounts";
        $accountsData = $this->makeRequest($fetchUrl, false, 'GET', $headers);

        if (is_wp_error($accountsData)) {
            $message = $accountsData->get_error_message();
            wp_send_json_error([
                'message' => $message
            ], 423);
        }

        if(Arr::get($accountsData, 'nextPageToken')){
            $accountsData['accounts'] = $this->getNextPageAccountsResponse($headers, $accountsData);
        }

        $locationsLists = array();
        if(isset($accountsData['accounts']) && !empty($accountsData['accounts'])) {
            foreach ($accountsData['accounts'] as $index => $accountData) {
                $accountName = Arr::get($accountData, 'name');
                $account     = explode('/', $accountName);
                $accountId   = $account[1];
                if ($accountId || !empty($accountId)) {
                    $locations = $this->getLocationsList($accountData, $headers);
                    if(!empty($locations)){
                        $locationsLists += $locations;
                    }
                }
            }
        }

        if (empty($locationsLists)) {
            wp_send_json_error([
                'message' => __('We don\'t find any business location from this email address', 'wp-social-reviews')
            ], 423);
        }

        update_option('wpsr_reviews_google_locations_list', $locationsLists, 'no');

        return get_option('wpsr_reviews_google_locations_list');
    }

    public function getLocationsList($accountData, $headers)
    {
        $accountName = Arr::get($accountData, 'name');
        $fetchUrl = "https://mybusinessbusinessinformation.googleapis.com/v1/".$accountName."/locations?pageSize=100&readMask=name,latlng,metadata,profile,serviceItems,title,openInfo";
        $data = $this->makeRequest($fetchUrl, false, 'GET', $headers);

        $locations = '';
        if(Arr::get($data, 'nextPageToken')){
            $data['locations'] = $this->getNextLocationsResponse($accountName, $headers, $data);
        }
        if (!empty($data) && (isset($data['locations']) && !empty($data['locations']))) {
            $locations = $this->getLocationInfo($data['locations'], $accountData);
        }

        return $locations;
    }

    public function getNextLocationsResponse($accountName, $headers, $data)
    {
        $locations = Arr::get($data, 'locations', []);
        $this->locationNextPageToken = Arr::get($data, 'nextPageToken');
        $total = apply_filters('wpsocialreviews/gmb_locations_limit', 200);
        $total = $total > 200 ? 200 : $total;
        $pageSize = 100;
        $pages = ceil($total/$pageSize);
        $x = 0;
        while(!empty($this->locationNextPageToken) && $x < $pages) {
            $x++;
            $fetchUrl = "https://mybusinessbusinessinformation.googleapis.com/v1/".$accountName."/locations?pageSize=100&readMask=name,latlng,metadata,profile,serviceItems,title,openInfo&pageToken=".$this->locationNextPageToken;
            $dataNext = $this->makeRequest($fetchUrl, false, 'GET', $headers);
            $this->locationNextPageToken = Arr::get($dataNext, 'nextPageToken');
            $next_locations = Arr::get($dataNext, 'locations', []);
            $locations = array_merge($locations, $next_locations);
        }
        return $locations;
    }

    public function getLocationInfo($locations = [], $accountData = [])
    {
        $locationInfo = [];
        $accountName = Arr::get($accountData, 'accountName');
        $accountType = Arr::get($accountData, 'type');
        $accountType = $accountType === 'PERSONAL' ? 'Personal' : 'Group';

        $account = explode('/', $accountData['name']);
        $accountId = $account[1];

        global $wpdb;
        $charset = $wpdb->get_col_charset( $wpdb->posts, 'post_content' );

        foreach ($locations as $index => $location) {
            if(isset($location['metadata']['placeId'])) {
                $locationName = explode('/', $location['name']);
                $locationId = $locationName[1];
                $locationInfo[$locationId]['accountId']    = $accountId;
                $locationInfo[$locationId]['accountName']  = $accountName;
                $locationInfo[$locationId]['accountType']  = $accountType;
                $locationInfo[$locationId]['locationId']   = $locationId;
                $locationInfo[$locationId]['locationName'] = 'utf8' === $charset ? wp_encode_emoji($location['title']) : $location['title'];
                $locationInfo[$locationId]['place_id']     = $accountData['name'].'/'. $location['name'];
                $locationInfo[$locationId]['location_key'] = $location['metadata']['placeId'];
            }
        }

        return $locationInfo;
    }

    public function convertRating($ratingStrVal)
    {
        if ($ratingStrVal === 'FIVE') {
            return 5;
        } elseif ($ratingStrVal === 'FOUR') {
            return 4;
        } elseif ($ratingStrVal === 'THREE') {
            return 3;
        } elseif ($ratingStrVal === 'TWO') {
            return 2;
        } elseif ($ratingStrVal === 'ONE') {
            return 1;
        } else {
            return 0;
        }
    }

    public function manuallySyncReviews($credentials)
    {
        $locationLists      = get_option('wpsr_reviews_google_locations_list');
        $placeId = Arr::get($credentials, 'place_id', '');
        if (isset($locationLists[$placeId])) {
            $place = $locationLists[$placeId]['place_id'];
            $apiKey = $this->getAccessToken();

            if($apiKey) {
                try {
                    $this->verifyCredential($apiKey, $place);
                    $cacheHandler = new cacheHandler($this->platform);
                    $cacheHandler->createCache('wpsr_reviews_' . $this->platform . '_business_info_' . $placeId, $placeId);

                    wp_send_json_success([
                        'message'    => __('Reviews synced successfully!', 'wp-social-reviews'),
                        'credentials'      => $credentials,
                    ]);
                } catch (\Exception $exception) {
                    wp_send_json_error([
                        'message'    => $exception->getMessage()
                    ], 423);
                }
            }

            if(empty($apiKey)) {
                wp_send_json_error([
                    'message'    => __('Access token is invalid! Please reauthorize your account.', 'wp-social-reviews'),
                ], 423);
            }
        }
    }

    public function doCronEvent()
    {
        $cacheHandler = new cacheHandler($this->platform);
        $expiredCaches =  $cacheHandler->getExpiredCaches();
        if(!$expiredCaches) {
            return false;
        }

        $placesLists = $this->getApiSettings();
        foreach($placesLists  as $place) {
            $placeId = Arr::get($place, 'place_id', '');
            if (!empty($placeId) && in_array($placeId, $expiredCaches)) {
                //find api key and place id
                $apiKey = $this->getAccessToken();
                if ($apiKey) {
                    try {
                        $this->verifyCredential($apiKey, $placeId);
                    } catch (\Exception $exception) {
                        error_log($exception->getMessage());
                    }
                }

                $cacheHandler->createCache('wpsr_reviews_' . $this->platform . '_business_info_' . $placeId, $placeId);
            }
        }
    }
}