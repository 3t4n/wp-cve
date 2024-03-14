<?php

namespace WPSocialReviews\App\Services\Platforms\Feeds\Facebook;

use WPSocialReviews\App\Services\GlobalSettings;
use WPSocialReviews\App\Services\Platforms\Feeds\BaseFeed;
use WPSocialReviews\App\Services\Platforms\Feeds\CacheHandler;
use WPSocialReviews\App\Services\Platforms\Feeds\Config;
use WPSocialReviews\App\Services\Platforms\Feeds\Facebook\Config as FacebookConfig;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\App\Services\Platforms\Feeds\Common\FeedFilters;
use WPSocialReviews\App\Services\Platforms\PlatformData;
use WPSocialReviews\App\Services\DataProtector;

if (!defined('ABSPATH')) {
    exit;
}

class FacebookFeed extends BaseFeed
{
    public $platform = 'facebook_feed';

    private $remoteFetchUrl = 'https://graph.facebook.com/';

    protected $cacheHandler;

    protected $protector;

    protected $platfromData;

    public function __construct()
    {
        parent::__construct($this->platform);
        $this->cacheHandler = new CacheHandler($this->platform);
        $this->protector = new DataProtector();
        $this->platfromData = new PlatformData($this->platform);
    }

    public function pushValidPlatform($platforms)
    {
        $isActive = get_option('wpsr_' . $this->platform . '_verification_configs');
        if ($isActive) {
            $platforms['facebook_feed'] = __('Facebook Feed', 'wp-social-reviews');
        }
        return $platforms;
    }

    public function handleCredential($args = [])
    {
        try {
            $selectedAccounts = Arr::get($args, 'selectedAccounts');

            if(sizeof($selectedAccounts) === 0 && !empty($args['access_token'])){
                $this->saveVerificationConfigs($args['access_token'] , $args['connectionType']);
				if(!($args['connectionType'] === 'manual' || $args['connectionType'] === 'event_feed')) {
					$this->saveAuthorizedSourceList( $args['access_token'] );
				}
            }

            if ($selectedAccounts && sizeof($selectedAccounts) > 0) {
                $this->saveSourceConfigs($args);
            }

            wp_send_json_success([
                'message' => __('You are Successfully Verified.', 'wp-social-reviews'),
                'status' => true
            ], 200);

        } catch (\Exception $exception) {
            wp_send_json_error([
                'message' => $exception->getMessage()
            ], 423);
        }
    }

    public function saveVerificationConfigs($accessToken = '' , $connectionType = '')
    {
		if ($connectionType === 'event_feed'){
			$fetchUrl = $this->remoteFetchUrl.'/'.$accessToken['page_id'].'?fields=id,name,link,picture&access_token=' . $accessToken['access_token'];
			$accessToken = $accessToken['access_token'];
		} else{
			$fetchUrl = $this->remoteFetchUrl.'me?fields=id,name,link,picture&access_token=' . $accessToken;
		}
        $response = wp_remote_get($fetchUrl);
        do_action( 'wpsocialreviews/facebook_feed_api_connect_response', $response );

        if(is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        if(200 !== wp_remote_retrieve_response_code($response)) {
            $errorMessage = $this->getErrorMessage($response);
            throw new \Exception($errorMessage);
        }

        if(200 === wp_remote_retrieve_response_code($response)) {
            $responseArr = json_decode(wp_remote_retrieve_body($response), true);
            $name = Arr::get($responseArr, 'name');
            $accountId = Arr::get($responseArr, 'id');

            $avatar = Arr::get($responseArr, 'picture.data.url');
            if($name && $avatar) {
                $data = [
                    'account_id'    => $accountId,
                    'name'          => $name,
                    'avatar'        => $avatar,
                    'access_token'  => $this->protector->encrypt($accessToken)
                ];
                update_option('wpsr_' . $this->platform . '_verification_configs', $data);
				if($connectionType === 'manual' || $connectionType === 'event_feed') {
					$connected_accounts = $this->getConncetedSourceList();
					$responseArr['access_token'] = $accessToken;
					if (Arr::get($responseArr, 'id') && Arr::get($responseArr, 'name')) {
						$pageId                      = (string)$responseArr['id'];
						$connected_accounts[$pageId] = $this->formatPageData($responseArr, $pageId, $connectionType);
					}
					update_option('wpsr_facebook_feed_connected_sources_config', array('sources' => $connected_accounts));
				}
                $this->setGlobalSettings();
            }
        }
    }

    public function getVerificationConfigs()
    {
        $verificationConfigs    = get_option('wpsr_' . $this->platform . '_verification_configs');
        $connected_source_list  = $this->getConncetedSourceList();
        $authorized_source_list = $this->getAuthorizedSourceList();

        wp_send_json_success([
            'authorized_source_list' => $authorized_source_list,
            'connected_source_list'  => $connected_source_list,
            'settings'               => $verificationConfigs,
            'status'                 => true,
        ], 200);
    }

    public function clearVerificationConfigs($userId)
    {
        $sources = $this->getConncetedSourceList();
        unset($sources[$userId]);
        update_option('wpsr_facebook_feed_connected_sources_config', array('sources' => $sources));

        if (!count($sources)) {
            delete_option('wpsr_facebook_feed_verification_configs');
            delete_option('wpsr_facebook_feed_connected_sources_config');
            delete_option('wpsr_facebook_feed_authorized_sources');
        }

        $cache_names = [
            'user_account_header_' . $userId,
            'timeline_feed_id_' . $userId,
            'photo_feed_id_' . $userId,
            'video_feed_id_' . $userId,
        ];

        foreach ($cache_names as $cache_name) {
            $this->cacheHandler->clearCacheByName($cache_name);
        }

        //when remove user account, delete last used time
        $this->platfromData->deleteLastUsedTime($userId);

        wp_send_json_success([
            'message' => __('Successfully Disconnected!', 'wp-social-reviews'),
        ], 200);
    }

    public function saveAuthorizedSourceList($access_token)
    {
        $api = $this->remoteFetchUrl.'me/accounts?limit=500&access_token='.$access_token;
        $response = wp_remote_get($api);

        if(is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        if(200 !== wp_remote_retrieve_response_code($response)) {
            $errorMessage = $this->getErrorMessage($response);
            throw new \Exception($errorMessage);
        }

        if(200 === wp_remote_retrieve_response_code($response)) {
            $result = json_decode(wp_remote_retrieve_body($response), true);

            $nextUrl = Arr::get($result, 'paging.next');
            if($nextUrl){
                while($nextUrl) {
                    $result = $this->getNextPageUrlResponse($nextUrl, $result);
                    $nextUrl = Arr::get($result, 'paging.next');
                }
            }

            $data = Arr::get($result, 'data', []);

            if ($data) {
                $connected_accounts = [];
                foreach ($data as $index => $page) {
                    if (Arr::get($page, 'id') && Arr::get($page, 'name')) {
                        $pageId = (string)$page['id'];
                        $connected_accounts[] = $this->formatPageData($page, $pageId);
                    }
                }
                update_option('wpsr_facebook_feed_authorized_sources', array('sources' => $connected_accounts));
            }
        }
    }

    public function getAuthorizedSourceList()
    {
        $sources = get_option('wpsr_facebook_feed_authorized_sources', []);
        $connected_sources = Arr::get($sources, 'sources') ? $sources['sources'] : [];
        return $connected_sources;
    }

    public function saveSourceConfigs($args = [])
    {
        if(Arr::get($args, 'selectedAccounts')) {
            $connected_accounts = $this->getConncetedSourceList();
            $verificationConfigs    = get_option('wpsr_' . $this->platform . '_verification_configs');

            foreach ($args['selectedAccounts'] as $index => $page) {
                if (Arr::get($page, 'id') && Arr::get($page, 'name')) {
                    $pageId                      = (string)$page['id'];
                    $page['account_id']          = Arr::get($verificationConfigs, 'account_id', null);
                    $connected_accounts[$pageId] = $this->formatPageData($page, $pageId);
                }
            }

            update_option('wpsr_facebook_feed_connected_sources_config', array('sources' => $connected_accounts));
            wp_send_json_success([
                'message'            => __('Successfully Connected!', 'wp-social-reviews'),
                'status'          => true,
            ], 200);
        }
    }

    public function formatPageData($page = [], $pageId = '' , $connectionType = '')
    {
        $accessToken = Arr::get($page, 'access_token', '');
        $data = [
            'access_token' => $this->protector->maybe_encrypt($accessToken),
            'expires_in'   => Arr::get($page, 'expires_in', ''),
            'created_at'   => time(),
            'account_id'   => Arr::get($page, 'account_id', null),
            'page_id'      => $pageId,
            'id'           => $pageId,
            'name'         => Arr::get($page, 'name', ''),
            'type'         => 'page',
            'is_private'   => Arr::get($page, 'is_private', false),
	        'is_event_enabled' => ($connectionType === 'event_feed'),
            'error_message'  => '',
            'error_code'     => '',
            'has_app_permission_error'     => false,
            'has_critical_error'     => false,
            'status'         => 'success',
        ];
        return $data;
    }

    public function getConncetedSourceList()
    {
        $configs = get_option('wpsr_facebook_feed_connected_sources_config', []);
        $sourceList = Arr::get($configs, 'sources') ? $configs['sources'] : [];
        return $sourceList;
    }

    public function getTemplateMeta($settings = array(), $postId = null)
    {
        $feed_settings = Arr::get($settings, 'feed_settings', array());
        $apiSettings   = Arr::get($feed_settings, 'source_settings', array());
        $filterSettings = Arr::get($feed_settings, 'filters', []);
        $isDateRangeEnabled = Arr::get($filterSettings, 'date_range', false);
        $dateRangeType = Arr::get($filterSettings, 'date_range_type', 'specific_date');

        if ($isDateRangeEnabled) {
            $apiSettings['date_range'] = true;
            $apiSettings['date_range_type'] = $dateRangeType;
            if ($dateRangeType === 'specific_date') {
                $apiSettings['date_range_start'] = Arr::get($filterSettings, 'date_range_start_specific', '');
                $apiSettings['date_range_end'] = Arr::get($filterSettings, 'date_range_end_specific', '');
            } else if ($dateRangeType === 'relative_date') {
                $apiSettings['date_range_start'] = Arr::get($filterSettings, 'date_range_start_relative', '');
                $apiSettings['date_range_end'] = Arr::get($filterSettings, 'date_range_end_relative', '');
            }
        }


        $data = [];
        if(!empty(Arr::get($apiSettings, 'selected_accounts'))) {
            $response = $this->apiConnection($apiSettings);
            if(isset($response['error_message'])) {
                $settings['dynamic'] = $response;
            } else {
                $data['items'] = $response;
            }
        } else {
            $settings['dynamic']['error_message'] = __('Please select a page to get feeds', 'wp-social-reviews');
        }

        $account = Arr::get($feed_settings, 'header_settings.account_to_show');
        if(!empty($account)) {
            $accountDetails = $this->getAccountDetails($account);
            if(isset($accountDetails['error_message'])) {
                $settings['dynamic'] = $accountDetails;
            } else {
                $data['header'] = $accountDetails;
            }
        }

        if (Arr::get($settings, 'dynamic.error_message')) {
            $filterResponse = $settings['dynamic'];
        } else {
            $filterResponse = (new FeedFilters())->filterFeedResponse($this->platform, $feed_settings, $data);
        }
        $settings['dynamic'] = $filterResponse;
        return $settings;
    }

    public function getFeedData( $feeds = null, $feed_id = null )
    {
        $particularFeed = [];
        $feedImages = [];
        foreach ($feeds as $key => $feed){
            if(Arr::get($feed, 'id') === $feed_id){
                $particularFeed = $feed;
                $feedImages = Arr::get($feed, 'photos.data', []);
            }
        }
        return [
            'feed' => $particularFeed,
            'feedImages' => $feedImages
        ];
    }

    public function getEditorSettings($args = [])
    {
        $postId = Arr::get($args, 'postId');
        $facebookConfig = new FacebookConfig();

        $feed_meta       = get_post_meta($postId, '_wpsr_template_config', true);
        $feed_template_style_meta = get_post_meta($postId, '_wpsr_template_styles_config', true);
        $decodedMeta     = json_decode($feed_meta, true);
        $feed_settings   = Arr::get($decodedMeta, 'feed_settings', array());
        $feed_settings   = Config::formatFacebookConfig($feed_settings, array());
        $settings        = $this->getTemplateMeta($feed_settings, $postId);
        $templateDetails = get_post($postId);
        $settings['feed_type'] = Arr::get($settings, 'feed_settings.source_settings.feed_type');
        $settings['styles_config'] = $facebookConfig->formatStylesConfig(json_decode($feed_template_style_meta, true), $postId);

        $translations = GlobalSettings::getTranslations();
        wp_send_json_success([
            'message'          => __('Success', 'wp-social-reviews'),
            'settings'         => $settings,
            'sources'          => $this->getConncetedSourceList(),
            'template_details' => $templateDetails,
            'elements'         => $facebookConfig->getStyleElement(),
            'translations'     => $translations
        ], 200);
    }

    public function updateEditorSettings($settings = array(), $postId = null)
    {
        if(defined('WPSOCIALREVIEWS_PRO') && class_exists('\WPSocialReviewsPro\App\Services\TemplateCssHandler')){
            (new \WPSocialReviewsPro\App\Services\TemplateCssHandler())->saveCss($settings, $postId);
        }

        // unset them for wpsr_template_config meta
        $unsetKeys = ['dynamic', 'feed_type', 'styles_config', 'styles', 'responsive_styles'];
        foreach ($unsetKeys as $key){
            if(Arr::get($settings, $key, false)){
                unset($settings[$key]);
            }
        }

        $encodedMeta = json_encode($settings, JSON_UNESCAPED_UNICODE);
        update_post_meta($postId, '_wpsr_template_config', $encodedMeta);

        $this->cacheHandler->clearPageCaches($this->platform);
        wp_send_json_success([
            'message' => __('Template Saved Successfully!!', 'wp-social-reviews'),
        ], 200);
    }

    public function editEditorSettings($settings = array(), $postId = null)
    {
        $styles_config = Arr::get($settings, 'styles_config');

        $format_feed_settings = Config::formatFacebookConfig($settings['feed_settings'], array());
        $settings             = $this->getTemplateMeta($format_feed_settings);
        $settings['feed_type'] = Arr::get($settings, 'feed_settings.source_settings.feed_type');

        $settings['styles_config'] = $styles_config;
        wp_send_json_success([
            'settings' => $settings,
        ]);
    }

    public function apiConnection($apiSettings)
    {
        return $this->getMultipleFeeds($apiSettings);
    }

    public function getMultipleFeeds($apiSettings)
    {
        $ids = Arr::get($apiSettings, 'selected_accounts');
        $connectedAccounts = $this->getConncetedSourceList();
        $multiple_feeds = [];
        foreach ($ids as $id) {
            if (isset($connectedAccounts[$id])) {
                $pageInfo = $connectedAccounts[$id];

	            $feedType = Arr::get($apiSettings, 'feed_type');
				if($feedType == 'event_feed' && !Arr::get($pageInfo, 'is_event_enabled', false)) {
					return ['error_message' => __('You have no access to this page events.', 'wp-social-reviews' )];
				}

                if ($pageInfo['type'] === 'page') {
                    $feed = $this->getPageFeed($pageInfo, $apiSettings);
                    if(isset($feed['error_message'])) {
                        return $feed;
                    }
                    $multiple_feeds[] = $feed;
                }
            }
        }

        $fb_feeds = [];
        if(!empty($multiple_feeds)){
            foreach ($multiple_feeds as $index => $feeds) {
                if(!empty($feeds) && is_array($feeds)) {
                    $fb_feeds = array_merge($fb_feeds, $feeds);
                }
            }
        }
        return $fb_feeds;
    }

    public function getAccountId($connectedSources, $pageId)
    {
        foreach ($connectedSources as $source){
            $source_page_id = Arr::get($source, 'id');
            if($pageId === $source_page_id){
                $account_id = Arr::get($source, 'account_id');
                return $account_id;
            }
        }
    }

    public function getPageFeed($page, $apiSettings, $cache = false)
    {
        $accessToken    = $this->protector->decrypt($page['access_token']) ? $this->protector->decrypt($page['access_token']) : $page['access_token'];
        $pageId         = $page['page_id'];
        $feedType       = Arr::get($apiSettings, 'feed_type');

        $totalFeed      = Arr::get($apiSettings, 'feed_count');
        $totalFeed      = !defined('WPSOCIALREVIEWS_PRO') && $totalFeed > 50 ? 50 : $totalFeed;
        $totalFeed      = apply_filters('wpsocialreviews/facebook_feeds_limit', $totalFeed);
        if(defined('WPSOCIALREVIEWS_PRO') && $totalFeed > 200){
            $totalFeed = 200;
        }

        if($totalFeed >= 100){
            $perPage = 100;
        } else {
            $perPage = $totalFeed;
        }

        $pages = (int)($totalFeed / $perPage);
        if(($totalFeed % $perPage) > 0){
            $pages++;
        }
        $isDateRangeEnabled = Arr::get($apiSettings, 'date_range', false);
        $dateRangeStart = Arr::get($apiSettings, 'date_range_start', '');
        $dateRangeEnd = Arr::get($apiSettings, 'date_range_end', '');

        if ($isDateRangeEnabled){
            //except cron date, we should convert ui settings date in strtotime format
            if(!$cache){
                if( empty($dateRangeStart) || empty($dateRangeEnd) ) {
                    return [
                        'error_type' => 'date_range_error',
                        'error_message' => __('Please enter a date range.', 'wp-social-reviews')
                    ];
                }

                $isValidStartDate = strtotime($dateRangeStart) !== false;
                $isValidEndDate = strtotime($dateRangeEnd) !== false;

                if(!$isValidStartDate || !$isValidEndDate) {
                    return [
                        'error_type' => 'date_range_error',
                        'error_message' => __('Please enter valid date range.', 'wp-social-reviews')
                    ];
                }

                $dateRangeStart = strtotime($dateRangeStart);
                $dateRangeEnd = strtotime($dateRangeEnd);
            }

            $pageCacheName  = $feedType.'_id_'.$pageId.'_num_'.$totalFeed.'_start_'.$dateRangeStart.'_end_'.$dateRangeEnd;
        } else {
            $pageCacheName  = $feedType.'_id_'.$pageId.'_num_'.$totalFeed;
        }

        $pageCacheName = str_replace(' ', '_', $pageCacheName);

        $feeds = [];
        if(!$cache) {
            $feeds = $this->cacheHandler->getFeedCache($pageCacheName);
        }
        $fetchUrl = '';

        $has_has_critical_error = Arr::get($page, 'has_critical_error');

        if(!$feeds && !$has_has_critical_error) {
            if($feedType === 'timeline_feed') {
                $fields = 'id,created_time,updated_time,message,attachments,from{name,id,picture{url},link},picture,full_picture,permalink_url,shares,status_type,story';
                $fields = apply_filters('wpsocialreviews/facebook_timeline_feed_api_fields', $fields);
                $fetchUrl = $this->remoteFetchUrl . $pageId . '/posts?fields='.$fields.'&limit='.$perPage.'&access_token=' . $accessToken;
            } else if($feedType === 'video_feed') {
                $fetchUrl = apply_filters('wpsocialreviews/facebook_video_feed_api_details', $this->remoteFetchUrl, $pageId, $perPage, $accessToken);
            } else if($feedType === 'photo_feed') {
                $fetchUrl = apply_filters('wpsocialreviews/facebook_photo_feed_api_details', $this->remoteFetchUrl, $pageId, $perPage, $accessToken);
            } else if($feedType === 'event_feed') {
                $fetchUrl = apply_filters('wpsocialreviews/facebook_event_feed_api_details', $this->remoteFetchUrl, $pageId, $perPage, $accessToken);
            } else if($feedType === 'album_feed') {
                $fetchUrl = apply_filters('wpsocialreviews/facebook_albums_feed_api_details', $this->remoteFetchUrl, $pageId, $perPage, $accessToken);
            }

            if (defined('WPSOCIALREVIEWS_PRO') && WPSOCIALREVIEWS_PRO_VERSION >= '3.10.0' && $isDateRangeEnabled) {
                $fetchUrl .= apply_filters('wpsocialreviews/facebook_feed_extend_api_endpoints', $dateRangeStart, $dateRangeEnd);
            }

            $args     = array(
                'timeout'   => 60
            );
            $pages_data = wp_remote_get($fetchUrl, $args);
            do_action( 'wpsocialreviews/facebook_feed_api_connect_response', $pages_data );

            if(is_wp_error($pages_data)) {
                $errorMessage = ['error_message' => $pages_data->get_error_message()];
                return $errorMessage;
            }

            if(Arr::get($pages_data, 'response.code') !== 200) {

                $pages_response_data = json_decode(wp_remote_retrieve_body($pages_data), true);
                $connectedSources = $this->getConncetedSourceList();

                if(!empty($connectedSources)){
                    $account_id = $this->getAccountId($connectedSources, $pageId);
                    foreach ($connectedSources as $key => $source){
                        $source_account_id = Arr::get($source, 'account_id');
                        if($source_account_id === $account_id) {
                            $connectedSources = $this->addPlatformApiErrors($pages_response_data, $connectedSources, $source);
                        }
                    }
                    update_option('wpsr_facebook_feed_connected_sources_config', array('sources' => $connectedSources));
                }

                if(Arr::get($pages_response_data, 'error.code') && (new PlatformData($this->platform))->isAppPermissionError($pages_response_data)){
                   do_action( 'wpsocialreviews/facebook_feed_app_permission_revoked' );
                }

                $errorMessage = $this->getErrorMessage($pages_data);
                return ['error_message' => $errorMessage];
            }

            if(Arr::get($pages_data, 'response.code') === 200) {
                $page_feeds = json_decode(wp_remote_retrieve_body($pages_data), true);

                if(isset($page_feeds['paging']) && $pages > 1) {
                    $nextUrl = Arr::get($page_feeds, 'paging.next');
                    if($nextUrl) {
                        $page_feeds = $this->getNextPageUrlResponse($nextUrl, $page_feeds);
                    }
                }

                $feeds = Arr::get($page_feeds, 'data', []);

                if(!empty($feeds)) {
                    $this->cacheHandler->createCache($pageCacheName, $feeds);
                }
            }
        }

        if(!$feeds || empty($feeds)) {
            return [];
        }

        return $feeds;
    }

    public function getNextPageUrlResponse($nextUrl, $pageData)
    {
        $response = wp_remote_get($nextUrl);

        if(is_wp_error($response)) {
            $errorMessage = ['error_message' => $response->get_error_message()];
            return $errorMessage;
        }

        if(Arr::get($response, 'response.code') !== 200) {
            $errorMessage = $this->getErrorMessage($response);
            return ['error_message' => $errorMessage];
        }

        $result = $pageData;
        if(Arr::get($response, 'response.code') === 200) {
            $result = json_decode(wp_remote_retrieve_body($response), true);

            $newData = Arr::get($result, 'data', []);
            $oldData = Arr::get($pageData, 'data', []);

            $result['data'] = array_merge($newData, $oldData);
        }

        return $result;
    }

    public function getAccountDetails($account)
    {
        $connectedAccounts = $this->getConncetedSourceList();
        $pageDetails = [];
        if (isset($connectedAccounts[$account])) {
            $pageInfo = $connectedAccounts[$account];
            if ($pageInfo['type'] === 'page') {
               $pageDetails  = $this->getPageDetails($pageInfo, false);
            }
        }
        return $pageDetails;
    }

    public function getPageDetails($page, $cacheFetch = false)
    {
        $pageId = $page['page_id'];
        $accessToken    = $this->protector->decrypt($page['access_token']) ? $this->protector->decrypt($page['access_token']) : $page['access_token'];

        $accountCacheName = 'user_account_header_'.$pageId;

        $accountData = [];

        if(!$cacheFetch) {
            $accountData = $this->cacheHandler->getFeedCache($accountCacheName);
        }

        if(empty($accountData) || $cacheFetch) {
            $fetchUrl = $this->remoteFetchUrl . $pageId . '?fields=id,name,picture.height(150).width(150),fan_count,description,about,link,cover&access_token=' . $accessToken;
            $accountData = wp_remote_get($fetchUrl);

            if(is_wp_error($accountData)) {
                return ['error_message' => $accountData->get_error_message()];
            }

            if(Arr::get($accountData, 'response.code') !== 200) {
                $errorMessage = $this->getErrorMessage($accountData);
                return ['error_message' => $errorMessage];
            }

            if(Arr::get($accountData, 'response.code') === 200) {
                $accountData = json_decode(wp_remote_retrieve_body($accountData), true);

                $this->cacheHandler->createCache($accountCacheName, $accountData);
            }
        }

        return $accountData;
    }

    public function getErrorMessage($response = [])
    {
        $userProfileErrors = json_decode(wp_remote_retrieve_body($response), true);

        $message = Arr::get($response, 'response.message');
        if (Arr::get($userProfileErrors, 'error')) {
			if(Arr::get($userProfileErrors, 'error.message')) {
				$error = Arr::get($userProfileErrors, 'error.message');
			}else {
				$error = Arr::get( $userProfileErrors, 'error.error_user_msg', '' );
			}
        } else if (Arr::get($response, 'response.error')) {
            $error = Arr::get($response, 'response.error.message');
        } else if ($message) {
            $error = $message;
        } else {
            $error = __('Something went wrong', 'wp-social-reviews');
        }
        return $error;
    }

    public function setGlobalSettings()
    {
        $option_name    = 'wpsr_' . $this->platform . '_global_settings';
        $existsSettings = get_option($option_name);
        if (!$existsSettings) {
            // add global instagram settings when user verified
            $args = array(
                'global_settings' => array(
                    'expiration'    => 60*60*6,
                    'caching_type'  => 'background'
                )
            );
            update_option($option_name, $args);
        }
    }

    public function updateCachedFeeds($caches)
    {
        $this->cacheHandler->clearPageCaches($this->platform);

        foreach ($caches as $index => $cache) {
            $optionName = $cache['option_name'];
            $num_position = strpos($optionName, '_num_');
            $total    = substr($optionName, $num_position + strlen('_num_'), strlen($optionName));

            $apiSettings = [];
            if(strpos($total, '_start_') !== false){
                $dateRangeStartTimePosition = strpos($optionName, '_start_');
                $dateRangeEndTimePosition = strpos($optionName, '_end_');
                $dateRangeStartTime    = substr($optionName, $dateRangeStartTimePosition + strlen('_start_'), $dateRangeEndTimePosition - strlen($optionName));
                $dateRangeEndTime    = substr($optionName, $dateRangeEndTimePosition + strlen('_end_'));

                $position = strpos($total, '_');
                $total = substr($total, 0, $position);

                $apiSettings['date_range'] = true;
                $apiSettings['date_range_start'] = $dateRangeStartTime;
                $apiSettings['date_range_end'] = $dateRangeEndTime;
            }

            $feed_type  = '';
            $separator        = '_feed';
            $feed_position    = strpos($optionName, $separator) + strlen($separator);
            $initial_position = 0;
            if ($feed_position) {
                $feed_type = substr($optionName, $initial_position, $feed_position - $initial_position);
            }

            $id_position = strpos($optionName, '_id_');
            $sourceId    = substr($optionName, $id_position + strlen('_id_'), $num_position - ($id_position + strlen('_id_')));

            $feedTypes = ['timeline_feed', 'video_feed', 'photo_feed', 'event_feed', 'album_feed'];
            $connectedSources = $this->getConncetedSourceList();
            if(in_array($feed_type, $feedTypes)) {
                  if(isset($connectedSources[$sourceId])) {
                      $page = $connectedSources[$sourceId];
                      $apiSettings['feed_type'] = $feed_type;
                      $apiSettings['feed_count'] = $total;
                      $this->getPageFeed($page, $apiSettings, true);
                  }
            }

            $accountIdPosition = strpos($optionName, '_account_header_');
            $accountId = substr($optionName, $accountIdPosition + strlen('_account_header_'), strlen($optionName));
            if(!empty($accountId)) {
              if(isset($connectedSources[$accountId])) {
                  $page = $connectedSources[$accountId];
                  $this->getPageDetails($page, true);
              }
            }
        }
    }

    public function clearCache()
    {
        $this->cacheHandler->clearPageCaches($this->platform);
        $this->cacheHandler->clearCache();
        wp_send_json_success([
            'message' => __('Cache cleared successfully!', 'wp-social-reviews'),
        ], 200);
    }

    public function addPlatformApiErrors($response, $connectedAccounts, $accountDetails)
    {
        $critical_codes = array(
            803, // ID doesn't exist
            100, // access token or permissions
            190, // app removed
            10, // app permissions or scopes
        );

        $responseErrorCode = Arr::get($response, 'error.code', '');
        $pageId   = $accountDetails['id'];

        if(!empty($responseErrorCode)){
            $connectedAccounts[$pageId]['error_message'] = Arr::get($response, 'error.message', '');
            $connectedAccounts[$pageId]['error_code'] = $responseErrorCode;
            $connectedAccounts[$pageId]['has_critical_error'] = in_array( $responseErrorCode, $critical_codes, true );
            $connectedAccounts[$pageId]['has_app_permission_error'] = $this->platfromData->isAppPermissionError($response);
        }
        $connectedAccounts[$pageId]['status'] = 'error';

        if(in_array( $responseErrorCode, $critical_codes, true )){
            delete_option('wpsr_facebook_feed_authorized_sources');
        }

        return $connectedAccounts;
    }
}