<?php

namespace WPSocialReviews\App\Services\Platforms\Feeds\Youtube;

use WPSocialReviews\App\Services\GlobalSettings;
use WPSocialReviews\App\Services\Platforms\Feeds\BaseFeed;
use WPSocialReviews\App\Services\Platforms\Feeds\CacheHandler;
use WPSocialReviews\App\Services\Platforms\Feeds\Common\FeedFilters;
use WPSocialReviews\App\Services\Platforms\Feeds\Config;
use WPSocialReviews\App\Services\Platforms\Feeds\Youtube\Config as YoutubeConfig;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\App\Models\Cache;

if (!defined('ABSPATH')) {
    exit;
}

class YoutubeFeed extends BaseFeed
{
    protected $oauth;
    protected $cacheHandler;
    private $remoteFetchUrl = 'https://www.googleapis.com/youtube/v3/';
    private $cronScheduleName = 'wpsr_youtube_feed_update';

    public function __construct()
    {
        parent::__construct('youtube');
        $this->oauth        = new OAuth();
        $this->cacheHandler = new CacheHandler('youtube');
    }

    public function pushValidPlatform($platforms)
    {
        $isActive = get_option('wpsr_'.$this->platform.'_verification_configs');
        if($isActive) {
            $platforms['youtube'] = __('Youtube', 'wp-social-reviews');
        }
        return $platforms;
    }

    /**
     * Save YouTube config on wp options table
     *
     * @return json
     * @since 1.2.5
     */
    public function handleCredential($settings = array())
    {
        // verify access token and API key
        try {
            $credentialsType = $settings['credentialsType'];
            $apiKey          = $settings['apiKey'];
            $accessCode      = $settings['accessCode'];
            $accessToken     = $settings['accessToken'];

            $url  = $this->makeConfigAPIRequestUrl($credentialsType, $accessCode, $apiKey, $accessToken);
            $data = $this->getAPIData($url['fetchUrl'], $url['args']);

			if (is_wp_error($data)) {
				$message = $data->get_error_message();
				wp_send_json_error([
					'message' => $message
				], 423);
			}

            if (isset($data['error']) || !empty($data['error'])) {
                $code = isset($data['error']['code']) ? $data['error']['code'] : 401;
                wp_send_json_error([
                    'message' => isset($data['error']['errors'][0]) ? $data['error']['errors'][0]['message'] : __('Sorry, Something went wrong!', 'wp-social-reviews')
                ], $code);
            }

            $configs = [];
            if ((!empty($data) && isset($data['items'][0])) && ($credentialsType === 'oauth2.0' || $credentialsType === 'manually_connect')) {
                $configs['channel_id'] = $data['items'][0]['id'];
                $configs['user_name']  = $data['items'][0]['snippet']['title'];
            } else {
                $configs['channel_id'] = 'UCiyeXfnGx9e06hXWf0Hz7ow';
            }

            $configs['access_token']     = Arr::get($url, 'accessToken', '');
            $configs['refresh_token']    = Arr::get($url, 'refresh_token', '');
            $configs['expires_in']       = Arr::get($url, 'expires_in', '');
            $configs['version']          = 'latest';
            $configs['api_key']          = $apiKey;
            $configs['access_code']      = $accessCode;
            $configs['credentials_type'] = $credentialsType;
            $configs['created_at']       = time();
            update_option('wpsr_youtube_verification_configs', $configs, 'no');
            $settings = get_option('wpsr_youtube_verification_configs');

            // add global youtube settings when user verified
            $args = [
                'global_settings' => [
                    'expiration'    => 259200,
                    'caching_type'     => 'background'
                ]
            ];
            update_option('wpsr_youtube_global_settings', $args);

            //$this->doCronEvent();
            wp_send_json_success([
                'settings' => $settings,
                'message'  => __('You are Successfully Verified', 'wp-social-reviews')
            ]);
        } catch (\Exception $exception) {
            wp_send_json_error([
                'message' => $exception->getMessage()
            ], 423);
        }
    }

    /**
     * Make api request url base on credentials type
     *
     * @param string $credentialsType
     * @param string $accessCode
     * @param string $apiKey
     * @param string $accessToken
     *
     * @return array
     * @throws /Exception
     * @since 1.2.5
     */
    private function makeConfigAPIRequestUrl($credentialsType = '', $accessCode = '', $apiKey = '', $accessToken = '')
    {
        $url = [];
        if (($credentialsType === 'oauth2.0' && empty($accessCode)) || ($credentialsType === 'api_key' && empty($apiKey)) || ($credentialsType === 'manually_connect' && empty($accessToken))) {
            wp_send_json_error([
                'message' => __('Field should not be empty!', 'wp-social-reviews')
            ], 423);
        }
        if (($credentialsType === 'oauth2.0' || $credentialsType === 'manually_connect')) {
            if ($accessCode && $credentialsType === 'oauth2.0') {
                $result = $this->oauth->generateAccessKey($accessCode);
                if (is_wp_error($result)) {
                    throw new \Exception($result->get_error_message());
                }
                $tokens['credentials_type'] = 'oauth2.0';
                $accessToken                = $result['access_token'];
                $refreshToken               = $result['refresh_token'];
                $expireTime                 = $result['expires_in'];
                $url['expires_in']          = $expireTime;
                $url['refresh_token']       = $refreshToken;
            }
            $args               = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ];
            $url['args']        = $args;
            $url['accessToken'] = $accessToken;
            $part               = 'mine=true&access_token=' . $accessToken;
            $fetchUrl           = 'https://www.googleapis.com/youtube/v3/channels?part=id,snippet&contentDetails&' . $part;
            $url['fetchUrl']    = $fetchUrl;
        }
        if ($credentialsType === 'api_key' && $apiKey) {
            $part            = 'key=' . $apiKey;
            $fetchUrl        = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=YouTube+Data+API&type=video&' . $part;
            $url['fetchUrl'] = $fetchUrl;
            $url['args']     = [];
        }

        return $url;
    }

    /**
     * get verified youtube configs data from wp options table
     *
     * @return json
     * @since 1.2.5
     */
    public function getVerificationConfigs()
    {
        $configs = get_option('wpsr_youtube_verification_configs');
        $configs['version'] = Arr::get($configs, 'version', '');

        wp_send_json_success([
            'message'  => __('Youtube Successfully Connected!', 'wp-social-reviews'),
            'settings' => $configs
        ], 200);
    }

    /**
     * delete youtube configs data from wp options table
     *
     * @return json
     * @since 1.2.5
     */
    public function clearVerificationConfigs()
    {
        delete_option('wpsr_youtube_verification_configs');
        $this->cacheHandler->clearCache();
        wp_send_json_success([
            'message' => __('Youtube Successfully Disconnected!', 'wp-social-reviews'),
        ], 200);
    }

    public function getTemplateMeta($settings = [], $postId = null)
    {
        $feed_settings = Arr::get($settings, 'feed_settings', []);
        $apiSettings   = Arr::get($feed_settings, 'source_settings', []);
        $response      = $this->apiConnection($apiSettings, $postId);

        if (isset($response['error_message'])) {
            $filterResponse = $response;
        } else {
            $filterResponse = (new FeedFilters())->filterFeedResponse($this->platform, $feed_settings, $response);
        }

        $settings['dynamic'] = $filterResponse;

        return $settings;
    }

    /**
     * Handle YouTube Feed
     *
     * Make sure all data related to you tube settings are formatted with dynamic youtube feed data from api
     *
     * @return array
     * @since 1.2.5
     */
    public function getEditorSettings($args = [])
    {
        $postId = Arr::get($args, 'postId');
        $youtubeConfig = new YoutubeConfig();

        $feed_meta       = get_post_meta($postId, '_wpsr_template_config', true);
        $feed_style_meta = get_post_meta($postId, '_wpsr_template_styles_config', true);
        $decodedMeta     = json_decode($feed_meta, true);
        $feed_settings   = Arr::get($decodedMeta, 'feed_settings', []);
        $feed_settings   = Config::formatYoutubeConfig($feed_settings, []);
        $settings        = $this->getTemplateMeta($feed_settings, $postId);
        $settings['styles_config'] = $youtubeConfig->formatStylesConfig(json_decode($feed_style_meta, true), $postId);

        $templateDetails = get_post($postId);
        $translations    = GlobalSettings::getTranslations();
        wp_send_json_success([
            'message'          => __('Success', 'wp-social-reviews'),
            'settings'         => $settings,
            'template_details' => $templateDetails,
            'elements'         => $youtubeConfig->getStyleElement(),
            'translations'     => $translations
        ], 200);
    }

    /**
     * Show updated data if new data fetched in editor without update in db
     *
     * @return json
     * @throws /Exception
     * @since 1.2.5
     */
    public function editEditorSettings($configs = [], $postId = null)
    {
        $editMode = $configs['edit_mode'];
        unset($configs['edit_mode']);

        $styles_config = Arr::get($configs, 'styles_config');

        $feed_meta     = Arr::get($configs, 'feed_settings', []);
        $feed_settings = Config::formatYoutubeConfig($feed_meta, []);
        $feedType      = isset($feed_meta['source_settings']['feed_type']) ? $feed_meta['source_settings']['feed_type'] : 'channel_feed';

        if ($feedType === 'single_video' && $editMode === 'fetching') {
            $cacheName = 'single_video_feed_id_' . $postId;
            $this->cacheHandler->clearCacheByName($cacheName);
        }

        $settings = $this->getTemplateMeta($feed_settings, $postId);
        $settings['styles_config'] = $styles_config;
        wp_send_json_success([
            'message'  => __('Youtube Settings Updated', 'wp-social-reviews'),
            'settings' => $settings,
            'postId'   => $postId
        ], 200);
    }

    /**
     * Update template is settings is changed in editor
     *
     * @return json
     * @since 1.2.5
     */
    public function updateEditorSettings($settings = [], $postId = null)
    {
        if(defined('WPSOCIALREVIEWS_PRO') && class_exists('\WPSocialReviewsPro\App\Services\TemplateCssHandler')){
            (new \WPSocialReviewsPro\App\Services\TemplateCssHandler())->saveCss($settings, $postId);
        }

        // unset them for wpsr_template_config meta
        $unsetKeys = ['dynamic', 'styles_config', 'styles', 'responsive_styles'];
        foreach ($unsetKeys as $key){
            if(Arr::get($settings, $key, false)){
                unset($settings[$key]);
            }
        }

        $encodedMeta = json_encode($settings, JSON_UNESCAPED_UNICODE);
        update_post_meta($postId, '_wpsr_template_config', $encodedMeta);
        wp_send_json_success([
            'message' => __('Template Saved Successfully!!', 'wp-social-reviews'),
        ], 200);
    }

    /**
     * Handle Api key or token
     **
     * @return string|array
     * @since 1.2.5
     */
    protected function getApiKeyOrToken()
    {
        $apiCredentials       = get_option('wpsr_youtube_verification_configs', true);
        $youtubeApiKeyOrToken = '';
        $youtubeAccessToken   = '';
        $youtubeApiKey        = '';

        if (isset($apiCredentials['credentials_type']) && $apiCredentials['credentials_type'] === 'oauth2.0') {
            $youtubeAccessToken = $this->oauth->getAccessToken();
            if (!$youtubeAccessToken) {
                return ['error_message' => __('Error in API key settings,Please check your API key is valid or not.', 'wp-social-reviews')];
            }
        } elseif (isset($apiCredentials['credentials_type']) && $apiCredentials['credentials_type'] === 'api_key') {
            $youtubeApiKey = $apiCredentials['api_key'];
        } elseif (isset($apiCredentials['credentials_type']) && $apiCredentials['credentials_type'] === 'manually_connect') {
            $youtubeAccessToken = $apiCredentials['access_token'];
        }

        if (!empty($youtubeAccessToken) && empty($youtubeApiKey)) {
            $youtubeApiKeyOrToken = 'access_token=' . $youtubeAccessToken . '';
        } else {
            $youtubeApiKeyOrToken = 'key=' . $youtubeApiKey . '';
        }

        if (!$youtubeAccessToken && !$youtubeApiKey) {
            return ['error_message' => __('No credential found. Please configure your YouTube settings correctly.', 'wp-social-reviews')];
        }

        return $youtubeApiKeyOrToken;
    }

    public function getApiDetails($channelId, $playlistId, $feedType, $eventType, $searchTerm, $total)
    {
        if ($feedType === 'channel_feed') {
            return $this->getChannelApiUrlDetails($channelId, $total);
        } elseif ($feedType === 'playlist_feed') {
            return apply_filters('wpsocialreviews/youtube_playlist_api_url_details', $playlistId, $total, $this->remoteFetchUrl);
        } elseif ($feedType === 'search_feed') {
            return apply_filters('wpsocialreviews/youtube_search_api_url_details', $searchTerm, $total, $this->remoteFetchUrl);
        } elseif ($feedType === 'live_streams_feed') {
            return apply_filters('wpsocialreviews/youtube_live_streams_api_url_details', $channelId, $eventType, $total, $this->remoteFetchUrl);
        }
    }

    /**
     * Handle YouTube Api Connection
     *
     * Collect youtube data from api
     *
     * @param $apiSettings
     * @param $postId
     *
     * @return array
     * @since 1.2.5
     */
    public function apiConnection($apiSettings = [], $postId = null)
    {
        $total    = (int)Arr::get($apiSettings, 'feed_count', '50');
        $feedType = Arr::get($apiSettings, 'feed_type', 'channel_feed');
        $channelId  = Arr::get($apiSettings, 'channel_id', '');
        $playlistId = Arr::get($apiSettings, 'playlist_id', '');
        $searchTerm = Arr::get($apiSettings, 'search_term', '');
        $videoIds   = Arr::get($apiSettings, 'video_id', '');
        $eventType  = Arr::get($apiSettings, 'event_type', '');

        if (empty($channelId) && empty($playlistId) && empty($searchTerm) && empty($videoIds)) {
            $position = strpos($feedType, '_');
            $type     = substr($feedType, 0, $position);
            $type     = (($type === 'live') ? 'channel' : (($type === 'single') ? 'single videos' : $type));
            $suffix   = $feedType === 'search_feed' ? 'term' : 'id';

            return ['error_message' => __('Please enter ' . $type . ' ' . $suffix . ' to fetch videos!! ', 'wp-social-reviews')];
        }

        if (!defined('WPSOCIALREVIEWS_PRO') && $feedType !== 'channel_feed') {
            return ['error_message' => __('Please upgrade to pro version to use this features!! ', 'wp-social-reviews')];
        }

        if ($feedType === 'single_video') {
            $videoIds      = array_map('trim', explode(",", $videoIds));
            $feedCacheName = 'single_video_feed_id_' . $postId . '_num_' . count($videoIds);

            return $this->getSingleVideoFeeds($videoIds, $feedCacheName);
        }

        //without single video feed we have now api url for fetching videos
        return $this->getYoutubeFeed($channelId, $playlistId, $feedType, $eventType, $searchTerm, $total, true);
    }

    public function sendApiRequest($youtubeFeedApiUrl, $feedType)
    {
        $youtubeApiKeyOrToken = $this->getApiKeyOrToken();
        if (isset($youtubeApiKeyOrToken['error_message'])) {
            return ['error_message' => $youtubeApiKeyOrToken];
        }

        $feeds = $this->getAPIData($youtubeFeedApiUrl . $youtubeApiKeyOrToken);
        if (is_wp_error($feeds)) {
            $message = $feeds->get_error_message();
            return ['error_message' => $message];
        }

        if (isset($feeds['error']['message'])) {
            $error = $feeds['error']['message'];
            return ['error_message' => $error];
        }

        //we are storing video ids for using in videos api with comma separator
        $videoIds = $this->getVideoIds($feeds);
        $nextPageToken = Arr::get($feeds, 'nextPageToken', '');

        $videoIds       = implode(',', $videoIds);

        $parts = 'id,snippet,status';
        $parts = apply_filters('wpsocialreviews/youtube_api_parts', $parts, $feedType);

        $youtubeUrl = $this->remoteFetchUrl . 'videos?part=' . $parts . '&id=' . $videoIds . '&';
        $videoLists = [];
        $response   = wp_remote_get($youtubeUrl . $youtubeApiKeyOrToken);

        if (is_wp_error($response)) {
            $message = $response->get_error_message();

            return ['error_message' => $message];
        }

        if (!is_wp_error($response)) {
            $videoLists = json_decode(wp_remote_retrieve_body($response), true);

            $errors     = $this->checkYoutubeError($videoLists);
            if (is_array($errors) && (true === $errors[0] || 1 === $errors[0])) {
                return ['error_message' => isset($errors[1]) ? $errors[1] : __('Problem in api key or access token setting!!', 'wp-social-reviews')];
            }

            // unlisted videos remove
            $videoLists = $this->getPublishVideos($videoLists);
        }

        $videoLists['nextPageToken'] = $nextPageToken;

        return $videoLists;
    }

    /**
     * Retrieve 'published' status videos from different statuses videos.
     *
     * @return array
     * @throws /Exception
     * @since 1.2.5
     */
    public function getPublishVideos($videoList)
    {
        $videos = Arr::get($videoList, 'items' , []);
        foreach($videos as $index => $video)
        {
            $videoStatus = Arr::get($video['status'], 'privacyStatus', '');
            if($videoStatus == 'unlisted')
            {
                unset($videoList['items'][$index]);
            }
        }

        return $videoList;
    }

    /**
     * Youtube Feeds Without Single Video
     *
     * @return array
     * @throws /Exception
     * @since 1.2.5
     */
    public function getYoutubeFeed($channelId = '', $playlistId = '', $feedType = 'channel_feed', $eventType = '', $searchTerm = '', $total = 50, $hasCache = false)
    {
        $apiDetails = $this->getApiDetails($channelId, $playlistId, $feedType, $eventType, $searchTerm, $total);
        $youtubeFeedApiUrl = Arr::get($apiDetails, 'api_url');
        $feedCacheName     = Arr::get($apiDetails, 'cache_name');
        if ($youtubeFeedApiUrl) {
            //add max results params in api from here in each call
            $videoLists = [];
            if($hasCache) {
                $videoLists = $this->cacheHandler->getFeedCache($feedCacheName);
            }

            if (empty($videoLists)) {
                $total = min($total, 200);
                $pages = (int)($total/50);
                $pages += (int)(($total%50) > 0);

                $currPage = 1;
                $curTotal = min($total, 50);
                $videoLists = $this->sendApiRequest($youtubeFeedApiUrl.'maxResults='.$curTotal.'&', $feedType);
                $nextPageToken = Arr::get($videoLists, 'nextPageToken');
                while($currPage < $pages && !empty($nextPageToken)) {
                    $curTotal = min($total-(50*$currPage), 50);
                    $currYtFeedApiUrl = $youtubeFeedApiUrl . 'maxResults=' . $curTotal . '&pageToken=' . $nextPageToken . '&';
                    $feeds = $this->sendApiRequest($currYtFeedApiUrl, $feedType);
                    $nextPageToken = Arr::get($feeds, 'nextPageToken');
                    $videoLists['items'] = array_merge(Arr::get($videoLists, 'items', []), Arr::get($feeds, 'items', []));
                    $currPage++;
                }

                $this->cacheHandler->createCache($feedCacheName, $videoLists);
            }

            if ($feedType === 'playlist_feed' && isset($videoLists['items']) && count($videoLists['items'])) {
                foreach ($videoLists['items'] as $video) {
                    if (isset($video['snippet']['channelId'])) {
                        $channelId = $video['snippet']['channelId'];
                        break;
                    }
                }
            }

            $channelCacheName = 'channel_header_' . $channelId;
            $channelInfo = [];
            if($hasCache) {
                $channelInfo = $this->cacheHandler->getFeedCache($channelCacheName);
            }

            if (empty($channelInfo)) {
                $channelInfo = $this->getChannelInfo($feedType, $videoLists, $channelId);
            }

            $videoLists['header'] = $channelInfo;

            return $videoLists;
        }
    }

    /**
     * Youtube Single Video Feeds
     *
     * @param array $videoIds video ids
     *
     * @return array
     * @throws /Exception
     * @since 1.2.5
     */
    public function getSingleVideoFeeds($videoIds, $feedCacheName)
    {
        if (empty($videoIds)) {
            return ['error_message' => __('Please enter a video id!! ', 'wp-social-reviews')];
        }

        $videoLists = [];
        $parts      = 'id,snippet';
        $parts      = apply_filters('wpsocialreviews/youtube_api_parts', $parts, 'single_video');

	    $videoLists = $this->cacheHandler->getFeedCache($feedCacheName);
        //No need to api call for this if data all ready exists in cache
        if (!$videoLists) {
            $youtubeApiKeyOrToken = $this->getApiKeyOrToken();
            if (isset($youtubeApiKeyOrToken['error_message'])) {
                return ['error_message' => $youtubeApiKeyOrToken];
            }

            $videoIds           = implode(',', $videoIds);
            $channelVideoApiUrl = $this->remoteFetchUrl . 'videos?part=' . $parts . '&id=' . $videoIds . '&';
            $videoLists         = $this->checkYoutubeCache($channelVideoApiUrl, $feedCacheName);
        }

        $videoLists['header'] = '';

        return $videoLists;
    }

    /**
     * Youtube Channel Api Url Details
     *
     * @param string $channelId Channel ID
     * @param integer $total total
     *
     * @return array
     * @throws /Exception
     * @since 1.2.5
     */
    public function getChannelApiUrlDetails($channelId, $total)
    {
        $feedCacheName     = '';
        $youtubeFeedApiUrl = '';
        if (empty($channelId)) {
            return ['error_message' => __('Please enter channel id to fetch videos!! ', 'wp-social-reviews')];
        }

        if (strpos($channelId, 'UC') === false) {
            $message = __('Please enter a valid channel id!! ', 'wp-social-reviews');
            return ['error_message' => $message];
        }

        $channelApiParams = 'mine=true';
        if ($channelId) {
            $channelApiParams = 'id=' . $channelId;
        }

        $channelCacheName     = 'channel_header_' . $channelId;
        $youtubeChannelApiUrl = $this->remoteFetchUrl . 'channels?part=id,snippet,contentDetails,statistics,brandingSettings&maxResults=' . $total . '&' . $channelApiParams . '&';
        $videos               = $this->checkYoutubeCache($youtubeChannelApiUrl, $channelCacheName);
        if(Arr::get($videos, 'error_message')){
            return ['error_message' => $videos['error_message']];
        }

        $resultSet = isset($videos['pageInfo']['resultsPerPage']) ? $videos['pageInfo']['resultsPerPage'] : 0;
        if (!$resultSet) {
            return ['error_message' => __('Can\'t find any videos to the channel!!', 'wp-social-reviews')];
        }
        $playlist = isset($videos['items'][0]['contentDetails']['relatedPlaylists']['uploads']) ? $videos['items'][0]['contentDetails']['relatedPlaylists']['uploads'] : '';
        if ($playlist) {
            $feedCacheName     = 'channel_feed_id_' . $channelId . '_num_' . $total;
            $youtubeFeedApiUrl = $this->remoteFetchUrl . 'playlistItems?part=id,snippet&playlistId=' . $playlist . '&';
        }

        return [
            'cache_name' => $feedCacheName,
            'api_url'    => $youtubeFeedApiUrl
        ];
    }

    /**
     * This function will set the API url and fetch the YouTube API Data
     *
     * @param string $fetchUrl
     * @param array $args
     *
     * @return json
     * @since 1.2.5
     */
    public function getAPIData($fetchUrl = '', $args = [])
    {
        $response = wp_remote_get($fetchUrl, $args);
        if (is_wp_error($response)) {
            return $response;
        }
        if (!is_wp_error($response)) {
            $response = json_decode(wp_remote_retrieve_body($response), true);
        }

        return $response;
    }

    public function getChannelInfo($feedType, $videoLists, $channelId)
    {
        if ($feedType === 'search_feed' || $feedType === 'single_video_feed') {
            return;
        }

        $youtubeApiKeyOrToken = $this->getApiKeyOrToken();
        if (isset($youtubeApiKeyOrToken['error_message'])) {
            return ['error_message' => $youtubeApiKeyOrToken];
        }

        $feedTypes = ['playlist_feed', 'channel_feed', 'live_streams_feed'];
        if (!empty($channelId) && in_array($feedType, $feedTypes)) {
            $channelApiParams = 'id=' . $channelId;
            $channelCacheName = 'channel_header_' . $channelId;

            $youtubeChannelApiUrl = $this->remoteFetchUrl . 'channels?part=id,snippet,contentDetails,statistics,brandingSettings&maxResults=1&' . $channelApiParams . '&';
            $response             = wp_remote_get($youtubeChannelApiUrl . $youtubeApiKeyOrToken);
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
                return ['error_message' => $message];
            }
            if (!is_wp_error($response)) {
                $channelInfo = json_decode(wp_remote_retrieve_body($response), true);
                $errors      = $this->checkYoutubeError($channelInfo);
                if (is_array($errors) && (true === $errors[0] || 1 === $errors[0])) {
                    return ['error_message' => isset($errors[1]) ? $errors[1] : __('Problem in api key or access token setting!!', 'wp-social-reviews')];
                } else {
                    $this->cacheHandler->createCache($channelCacheName, $channelInfo);
                    return $channelInfo;
                }
            }
        }
    }

    public function getVideoIds($feeds = [])
    {
        $videoIds = [];
        if (isset($feeds['items']) && count($feeds['items'])) {
            foreach ($feeds['items'] as $index => $feed) {
                $videoId = Helper::getVideoId($feed);
                if (!empty($videoId)) {
                    $videoIds[] = $videoId;
                }
            }
        }

        return $videoIds;
    }

    /**
     * Handle Error
     *
     * Check if there any error in youtube api data
     *
     * @param array $feedData feed data
     *
     * @return array
     * @throws /Exception
     * @since 1.2.5
     */
    public function checkYoutubeError($feedData)
    {
        try {
            if (isset($feedData['error']) && 400 === $feedData['error']['code']) {
                throw new \Exception($feedData['error']['code'] . ' - A VALID access token is required to request this resource.');
            }
            if (isset($feedData['error'])) {
                $output = '';
                if (isset($feedData['error']['message'])) {
                    $output = 'Error: ' . $feedData['error']['message'];
                }
                if (isset($feedData['error']['code'])) {
                    $output .= '<br />Code: ' . $feedData['error']['code'];
                }
                throw new \Exception($output);
            }
        } catch (\Exception $e) {
            return [true, $e->getMessage()];
        }
    }

    /**
     * Handle YouTube Cache
     *
     * @param string $youtubeUrl youtube url
     * @param string $cacheName cache name
     *
     * @return array|string
     * @throws /Exception
     * @since 1.2.5
     */
    public function checkYoutubeCache($youtubeUrl, $cacheName)
    {
		$feedCache = $this->cacheHandler->getFeedCache($cacheName);
        if ($feedCache) {
            return $feedCache;
        }

        $youtubeApiKeyOrToken = $this->getApiKeyOrToken();

        if (isset($youtubeApiKeyOrToken['error_message'])) {
	        return ['error_message' => $youtubeApiKeyOrToken['error_message']];
        }

        $feedData = [];
        $response = wp_remote_get($youtubeUrl . $youtubeApiKeyOrToken);
        if (is_wp_error($response)) {
            $message = $response->get_error_message();

            return ['error_message' => $message];
        }

        if (!is_wp_error($response)) {
            $feedData = json_decode(wp_remote_retrieve_body($response), true);
        }

        if (!empty($feedData) && !empty($cacheName)) {
            $wpsr_error_check_complete = $this->checkYoutubeError($feedData);
            if (is_array($wpsr_error_check_complete) && (true === $wpsr_error_check_complete[0] || 1 === $wpsr_error_check_complete[0])) {
	            $feedCache = $this->cacheHandler->getFeedCache($cacheName);
				if ($feedCache) {
                    return $feedCache;
                }

                return ['error_message' => isset($wpsr_error_check_complete[1]) ? $wpsr_error_check_complete[1] : __('Problem in api key or access token setting!!', 'wp-social-reviews')];
            } elseif (!$this->cacheHandler->getFeedCache($cacheName)) {
                $this->cacheHandler->createCache($cacheName, $feedData);
                return $feedData;
            }
        } else {
            if (!empty($cacheName) && empty($feedData)) {
	            $feedCache = $this->cacheHandler->getFeedCache($cacheName);
                if ($feedCache) {
                    return $this->cacheHandler->getFeedCache($cacheName, true);
                }
            }
        }
    }

    public function updateCachedFeeds($caches)
    {
        foreach ($caches as $index => $cache) {
            $optionName = $cache['option_name'];
            $event_type = '';
            $searchTerm = '';
            $feed_type  = '';
            $channelId  = '';
            $playlistId = '';
            $num_position = strpos($optionName, '_num_');

            //search term
            $search_term_position = strpos($optionName, '_search_term_');
            if ($search_term_position) {
                $searchTerm = substr($optionName, $search_term_position + strlen('_search_term_'),
                    $num_position - ($search_term_position + strlen('_search_term_')));
            }

            //find total
            $total = substr($optionName, $num_position + strlen('_num_'),
                strlen($optionName) - ($num_position + strlen('_num_')));

            //find id
            $id_position = strpos($optionName, '_id_');
            $id          = substr($optionName, $id_position + strlen('_id_'),
                $num_position - ($id_position + strlen('_id_')));

            //feed type
            $separator        = '_feed';
            $feed_position    = strpos($optionName, $separator) + strlen($separator);
            $initial_position = 0;
            if ($feed_position) {
                $feed_type = substr($optionName, $initial_position, $feed_position - $initial_position);
            }

            if ($feed_type === 'live_streams_feed') {
                $event_type_position = strpos($optionName, '_event_type_');
                $event_type          = substr($optionName, $event_type_position + strlen('_event_type_'),
                    strlen($optionName) - ($event_type_position + strlen('_event_type_')));
                $total               = substr($optionName, $num_position + strlen('_num_'),
                    $event_type_position - ($num_position + strlen('_num_')));
            }

            $total = (int)$total;
            $feedTypes = ['channel_feed', 'playlist_feed', 'single_video_feed', 'search_feed', 'live_streams_feed'];
            if (in_array($feed_type, $feedTypes)) {
                if ($feed_type === 'single_video_feed' && !empty($id)) {
                    $feed_meta     = get_post_meta($id, '_wpsr_template_config', true);
                    $decodedMeta   = json_decode($feed_meta, true);
                    $feed_settings = Arr::get($decodedMeta, 'feed_settings', []);
                    $template_meta = Config::formatYoutubeConfig($feed_settings, []);
                    $videoIds      = $template_meta['feed_settings']['source_settings']['video_id'];
                    $videoIds      = array_map('trim', explode(",", $videoIds));
                    $feedCacheName = 'single_video_feed_id_' . $id . '_num_' . count($videoIds);
                    $this->getSingleVideoFeeds($videoIds, $feedCacheName);
                } else {
                    if ($feed_type === 'channel_feed' || $feed_type == 'live_streams_feed') {
                        $channelId  = $id;
                    } elseif ($feed_type === 'playlist_feed') {
                        $playlistId = $id;
                    }

                    $this->getYoutubeFeed($channelId, $playlistId, $feed_type, $event_type, $searchTerm, $total);
                }
            }
        }
    }

    public function clearCache()
    {
        $this->cacheHandler->clearCache();
        wp_send_json_success([
            'message' => __('Cache cleared successfully!', 'wp-social-reviews'),
        ], 200);
    }
}
