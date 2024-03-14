<?php

namespace WPSocialReviews\App\Services\Platforms\Feeds\Twitter;

use WpFluent\Exception;
use WPSocialReviews\App\Services\Platforms\Feeds\BaseFeed;
use WPSocialReviews\App\Services\Platforms\Feeds\CacheHandler;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\App\Services\Platforms\Feeds\Config;
use WPSocialReviews\App\Services\Platforms\Feeds\Twitter\Config as TwitterConfig;
use WPSocialReviews\App\Services\Platforms\Feeds\Common\FeedFilters;
use WPSocialReviews\App\Services\GlobalSettings;

class TwitterFeed extends BaseFeed
{
    protected $consumerKey = 'sh28wFaco96FXhzEWuKY71k4z';
    protected $consumerSecret = 'pJASn62pdt60DjlUi4OWvL8guRVnnAhuhRl5xaqnqfz6oRXxuO';
    protected $baseFeedUrl = 'https://api.twitter.com/';
    protected $version = '1.1';
    protected $extraFields = '';

    protected $totalFeed = 10;
    protected $paginate = 6;
    protected $cacheHandler;
    protected $isTwitterConnected = false;
    public $platform = 'twitter';
    public $transient_name;
    private $cronScheduleName = 'wpsr_twitter_feed_update';
    public $userId = null;
    public $userName = null;

    public function __construct()
    {
        parent::__construct($this->platform);
        $this->cacheHandler = new CacheHandler($this->platform);
    }

    public function pushValidPlatform($platforms)
    {
        $isActive = get_option('wpsr_'.$this->platform.'_verification_configs');
        if($isActive) {
            $platforms['twitter'] = __('Twitter', 'wp-social-reviews');
        }

        return $platforms;
    }

    public function verifyApiCredentials($settings = [])
    {
        $this->version = Arr::get($settings, 'api_version', '1.1');
        $requestMethod = "GET";
        $apiUrl        = $this->baseFeedUrl . $this->version . '/users/by/username/'. Arr::get($settings, 'username', '');
        if($this->version === '1.1') {
            $apiUrl        = $this->baseFeedUrl . $this->version . '/account/verify_credentials.json';
        }

        $twitterApi    = new TwitterApi($settings);
        try {
            if($this->version === '1.1') {
                $results = $twitterApi->buildOauth($apiUrl, $requestMethod)->performRequest();
                $data = [
                    'username' => Arr::get($results, 'screen_name')
                ];

                return $data;
            }

            else {
                $accessTokenBearer = Arr::get($settings, 'access_token_bearer', '');
                if($accessTokenBearer) {
                    $headers = [
                        'Authorization' => 'Bearer ' . $accessTokenBearer,
                    ];

                    $results = $twitterApi->makeRequest($apiUrl, false, 'GET', $headers);
                    $data = [
                        'username' => Arr::get($results, 'data.username')
                    ];

                    return $data;
                }

                throw new \Exception('Bearer token not provided');
            }
        } catch (\Exception $exception) {
            wp_send_json_error([
                'message' => $exception->getMessage()
            ], 423);
        }
    }

    public function getCredentialsSettings($args = [])
    {
        $settings = [];
        $settings['consumer_key']    = Arr::get($args, 'consumer_key', '');
        $settings['consumer_secret'] = Arr::get($args, 'consumer_secret', '');
        $settings['oauth_access_token']        = Arr::get($args, 'oauth_access_token');
        $settings['oauth_access_token_secret'] = Arr::get($args, 'oauth_access_token_secret');
        $settings['access_token_bearer']       = Arr::get($args, 'access_token_bearer', '');
        $settings['api_version']               = Arr::get($args, 'api_version', '1.1');
        $settings['username']                  = Arr::get($args, 'username', '');
        $settings['platform']                  = Arr::get($args, 'platform');

        $this->isTwitterConnected              = true;

        return $settings;
    }

    public function handleCredential($args = [])
    {
        $settings                = $this->getCredentialsSettings($args);
        $response                = $this->verifyApiCredentials($settings);
        $settings['screen_name'] = Arr::get($response, 'username', '');
        $dynamicConfigs           = ['dynamic' => $settings];
        update_option('wpsr_twitter_verification_configs', $dynamicConfigs, 'no');
        $settings = get_option('wpsr_twitter_verification_configs');

        // add global twitter settings when user verified
        $args = [
            'global_settings' => [
                'expiration'    => 60*60*24*7, //1 week
                'caching_type'  => 'background'
            ]
        ];

        update_option('wpsr_twitter_global_settings', $args);

        wp_send_json_success([
            'message'  => __('Twitter Successfully Connected!', 'wp-social-reviews'),
            'settings' => $settings
        ], 200);
    }

    public function getVerificationConfigs()
    {
        $twitterConfig = get_option('wpsr_twitter_verification_configs');
        wp_send_json_success([
            'message'  => __('Twitter Successfully Connected!', 'wp-social-reviews'),
            'settings' => $twitterConfig
        ], 200);
    }

    public function clearVerificationConfigs()
    {
        $settings = delete_option('wpsr_twitter_verification_configs');
        wp_clear_scheduled_hook($this->cronScheduleName);
        $this->cacheHandler->clearCache();

        wp_send_json_success([
            'message'  => __('Twitter Successfully Disconnected!', 'wp-social-reviews'),
            'settings' => $settings
        ], 200);
    }

    public function getTemplateMeta($settings = array())
    {
        $feed_settings = Arr::get($settings, 'feed_settings', []);

        $response       = $this->apiConnectionResponse($settings);
        if (isset($response['error_message'])) {
            $filterResponse = $response;
        } else {
            $filterResponse = (new FeedFilters())->filterFeedResponse($this->platform, $feed_settings, $response);
        }

        $settings['dynamic']  = $filterResponse;
        $settings['api_version'] = $this->version;
        return $settings;
    }

    public function getEditorSettings($args = [])
    {
        $postId = Arr::get($args, 'postId');
        $twitterConfig = new TwitterConfig();

        $feed_template_meta = get_post_meta($postId, '_wpsr_template_config', true);
        $feed_template_style_meta = get_post_meta($postId, '_wpsr_template_styles_config', true);
        $decodedMeta        = json_decode($feed_template_meta, true);
        $feed_settings      = Arr::get($decodedMeta, 'feed_settings', []);
        $feed_settings      = Config::formatTwitterConfig($feed_settings, array());
        $settings           = $this->getTemplateMeta($feed_settings);

        $settings['styles_config'] = $twitterConfig->formatStylesConfig(json_decode($feed_template_style_meta, true), $postId);
        $templateDetails    = get_post($postId);

        $translations = GlobalSettings::getTranslations();
        wp_send_json_success([
            'message'          => __('Success', 'wp-social-reviews'),
            'settings'         => $settings,
            'template_details' => $templateDetails,
            'elements'  => $twitterConfig->getStyleElement(),
            'translations'     => $translations
        ], 200);
    }

    public function editEditorSettings($configs = array(), $postId = null)
    {
        $styles_config = Arr::get($configs, 'styles_config');

        $feed_settings = Arr::get($configs, 'feed_settings', []);
        $feed_settings = Config::formatTwitterConfig($feed_settings, array());
        $settings      = $this->getTemplateMeta($feed_settings);

        $settings['styles_config'] = $styles_config;
        wp_send_json_success([
            'message'  => __('Twitter Settings Updated', 'wp-social-reviews'),
            'settings' => $settings,
        ], 200);
    }

    public function updateEditorSettings($settings = array(), $postId = null)
    {
        if(defined('WPSOCIALREVIEWS_PRO') && class_exists('\WPSocialReviewsPro\App\Services\TemplateCssHandler')){
            (new \WPSocialReviewsPro\App\Services\TemplateCssHandler())->saveCss($settings, $postId);
        }

        $unsetKeys = ['dynamic', 'header', 'styles_config', 'styles', 'responsive_styles'];
        foreach ($unsetKeys as $key){
            if(Arr::get($settings, $key, false)){
                unset($settings[$key]);
            }
        }

        $encodedMeta        = json_encode($settings, JSON_UNESCAPED_UNICODE);
        update_post_meta($postId, '_wpsr_template_config', $encodedMeta);

        $this->cacheHandler->clearPageCaches($this->platform);
        wp_send_json_success([
            'message' => __('Template Saved Successfully!!', 'wp-social-reviews')
        ], 200);
    }

    /**
     * sets transient name for the caching system
     *
     * @param string $feed_type
     * @param integer $count
     * @param string $name
     * @param string $hashtag
     *
     * @return string
     * @since 2.0.0
     */
    public function setTransientName(string $feed_type, int $count, string $name, string $hashtag)
    {
        if ($feed_type === 'home_timeline') {
            $this->transient_name = $feed_type . '_num' . $count . '';
        } elseif ($feed_type === 'user_timeline') {
            $this->transient_name = $feed_type . '_name_' . $name . '_num' . $count;
        } elseif (($feed_type === 'hashtag' || $feed_type === 'user_mentions') && defined('WPSOCIALREVIEWS_PRO')) {
            $this->transient_name = apply_filters('wpsocialreviews/set_twitter_transient_name', $this->transient_name,
                $feed_type, $count, $hashtag);
        }

        return $this->transient_name;
    }

    /**
     * uses the endpoints to determining what get get fields need to be set
     *
     * @param string $name
     * @param integer $count
     * @param string $feed_type
     * @param string $hashtag
     *
     * @return string
     * @since 2.0.0
     */
    public function setGetFieldsString($name, $count, $feed_type, $hashtag)
    {
        $count = (int) $count;
        $get_field = '?count=' . $count;
        if ($feed_type === 'user_timeline') {
            $get_field = "?screen_name=" . $name . "&count=" . $count . "&tweet_mode=extended&exclude_replies=true";
        } elseif ($feed_type === 'home_timeline') {
            $get_field = '?count=' . $count . "&tweet_mode=extended&exclude_replies=true";
        } elseif (($feed_type === 'hashtag' || $feed_type === 'user_mentions') && defined('WPSOCIALREVIEWS_PRO')) {
            $get_field = apply_filters('wpsocialreviews/twitter_set_get_field', $feed_type, $count, $hashtag);
        }

        $this->extraFields = $get_field;
    }

    /**
     * sets the complete url for API endpoint
     *
     * @param string $feed_type
     *
     * @return string
     * @since 2.0.0
     */
    public function makeApiUrl($apiType)
    {
        $apiUrl = '';
        if($apiType === 'header') {
            if($this->version === '1.1') {
               return $this->baseFeedUrl . $this->version . '/account/verify_credentials.json';
            }

            if(!empty($this->userName)) {
                $userFields = "created_at,description,entities,id,location,name,pinned_tweet_id,profile_image_url,protected,public_metrics,url,username,verified,verified_type";
                return $this->baseFeedUrl . $this->version . '/users/by/username/' . $this->userName . "?user.fields=" . $userFields;
            }
        } else if ($apiType === 'user_timeline') {
            if($this->version === '1.1') {
                return $this->baseFeedUrl . $this->version . '/statuses/user_timeline.json';
            }

            if(!empty($this->userId)) {
                $mediaFields = 'duration_ms,height,media_key,preview_image_url,type,url,width,public_metrics,variants';
                $tweetFields = 'attachments,author_id,context_annotations,conversation_id,created_at,entities,geo,id,in_reply_to_user_id,possibly_sensitive,referenced_tweets,reply_settings,source,text,lang,public_metrics';
                $userFields = 'created_at,description,entities,id,location,name,profile_image_url,public_metrics,url,username,verified,verified_type';
                $fields = '?exclude=replies' . '&tweet.fields=' . $tweetFields . '&user.fields=' . $userFields . '&media.fields=' . $mediaFields . '&max_results=10&expansions=attachments.media_keys,referenced_tweets.id,author_id,referenced_tweets.id.author_id';
                return $this->baseFeedUrl . $this->version. '/users/' . $this->userId . '/tweets' . $fields;
            }
        }

        return $apiUrl;
    }

    public function sendHeaderApiRequest($settings)
    {
        $this->version = Arr::get($settings, 'api_version', '1.1');
        $twitterApi    = new TwitterApi($settings);
        $accessTokenBearer = Arr::get($settings, 'access_token_bearer');

        $headers = [
            'Authorization' => 'Bearer ' . $accessTokenBearer,
        ];

        $headerCacheName    = 'twitter_feed_header_' . $this->userName;
        $headerApiUrl = $this->makeApiUrl('header');

        $headerResponse = [];
        if(!empty($headerApiUrl)) {
            try {
                if($this->version === '1.1') {
                    $results = $twitterApi->buildOauth($headerApiUrl, 'GET')->performRequest();
                    $headerResponse = (new TwitterConfig())->formatHeader($results);
                    if(!empty($headerResponse)) {
                        $this->cacheHandler->createCache($headerCacheName, $headerResponse);
                    }
                } else {
                    $results = $twitterApi->makeRequest($headerApiUrl, false, 'GET', $headers);
                    $headerResponse = Arr::get($results, 'data', []);
                    if(!empty($headerResponse)) {
                        $this->userId = Arr::get($headerResponse, 'id', null);
                        $this->cacheHandler->createCache($headerCacheName, $headerResponse);
                    }
                }
            } catch (\Exception $exception) {
                $error_message = $exception->getMessage();
                return ['error' => $error_message];
            }
        }

        return $headerResponse;
    }

    public function sendFeedsApiRequest($settings, $feed_type)
    {
        $this->version = Arr::get($settings, 'api_version', '1.1');
        $twitterApi    = new TwitterApi($settings);
        $accessTokenBearer = Arr::get($settings, 'access_token_bearer');

        $headers = [
            'Authorization' => 'Bearer ' . $accessTokenBearer,
        ];

        $responseTwitter = [];
        $tweetsApiUrl = $this->makeApiUrl($feed_type);

        if(!empty($tweetsApiUrl)) {
            try {
                if($this->version === '1.1') {
                    $results = $twitterApi->setGetfield($this->extraFields)->buildOauth($tweetsApiUrl, 'GET')->performRequest();
                    if(!empty($results)) {
                        $responseTwitter = (new TwitterConfig())->formatAllOldFeeds($results);
                        $this->cacheHandler->createCache($this->transient_name, $responseTwitter);
                    }
                }

                else {
                    $results = $twitterApi->makeRequest($tweetsApiUrl, false, 'GET', $headers);
                    if(!empty($results)) {
                        $responseTwitter = (new TwitterConfig())->formatAllNewFeeds($results);
                        $this->cacheHandler->createCache($this->transient_name, $responseTwitter);
                    }
                }
            } catch (\Exception $exception) {
                $error_message = $exception->getMessage();
                return ['error' => $error_message];
            }
        }

        return $responseTwitter;
    }

    public function sendApiRequest($settings, $feed_type)
    {
        if ($feed_type !== 'hashtag') {
            $this->sendHeaderApiRequest($settings);
        }

        $this->sendFeedsApiRequest($settings, $feed_type);
    }

    public function headerResponse($twitterObj, $username, $headerCacheName, $headers)
    {
        $headerResponse = apply_filters('wpsocialreviews/twitter_feed_header_api_response', $twitterObj, $username, $this->baseFeedUrl, $headers);

        $hasError       = isset($headerResponse['error']);
        if (!$hasError) {
            $this->cacheHandler->createCache($headerCacheName, $headerResponse);
            return $headerResponse;
        }
    }

    public function apiConnectionResponse($newConfigs = [])
    {
        //do not cache if no tweets found
        $configs = get_option('wpsr_twitter_verification_configs');

        $newConfigs = Arr::get($newConfigs, 'feed_settings.additional_settings');
        $feed_type  = Arr::get($newConfigs, 'feed_type', '');
        $count      = Arr::get($newConfigs, 'feed_count', '');
        $name       = Arr::get($newConfigs, 'screen_name', '');
        $hashtag    = Arr::get($newConfigs, 'hashtag', '');
        $this->userName = $name;

        $settings = [];
        if (!empty(Arr::get($configs, 'dynamic'))) {
            $settings = $this->getCredentialsSettings(Arr::get($configs, 'dynamic'));
        }

        if (!empty($name)) {
            $this->transient_name = $this->setTransientName($feed_type, $count, $name, $hashtag);

            $response             = [];
            if ($feed_type !== 'hashtag') {
                // check cache data exist or not
                $headerCacheName = 'twitter_feed_header_' . $name;
                $headerResponse = $this->cacheHandler->getFeedCache($headerCacheName);
                if (!empty($headerResponse)) {
                    //we may need to format this previously connected account here if already not formatted
                    //this is for previous version compatibility
                    if(!Arr::get($headerResponse, 'formatted')) {
                        $headerResponse = (new TwitterConfig())->formatHeader($headerResponse);
                        $headerCacheName    = 'twitter_feed_header_' . $this->userName;
                        $this->cacheHandler->createCache($headerCacheName, $headerResponse);
                    }

                    if(Arr::get($headerResponse, 'formatted')) {
                        unset($headerResponse['formatted']);
                    }

                    $response['header'] = $headerResponse;
                } else {
                    $headerResponse = $this->sendHeaderApiRequest($settings);
                    if(!empty($headerResponse) && !Arr::get($headerResponse, 'error')) {
                        $response['header'] = $headerResponse;
                    }
                }

                $this->userId = Arr::get($headerResponse, 'id', null);
            }

            if ($this->isTwitterConnected) {
                $tweetResponse = $this->cacheHandler->getFeedCache($this->transient_name);

                if (!empty($tweetResponse)) {
                    //we may need to format this previously connected account here if already not formatted
                    //this is for previous version compatibility
                    if(!Arr::get($tweetResponse, 'formatted')) {
                        $tweetResponse = (new TwitterConfig())->formatAllOldFeeds($tweetResponse);
                        $this->cacheHandler->createCache($this->transient_name, $tweetResponse);
                    }

                    if(Arr::get($tweetResponse, 'formatted')) {
                        unset($tweetResponse['formatted']);
                    }

                    $response['items'] = $tweetResponse;
                } else {
                    $this->setGetFieldsString($name, $count, $feed_type, $hashtag);
                    $tweetResponse = $this->sendFeedsApiRequest($settings, $feed_type);
                    if(!empty($tweetResponse) && !Arr::get($tweetResponse, 'error')) {
                        $response['items'] = $tweetResponse;
                    }
                }
            } else {
                return ['error_message' => __('Please set your twitter configuration correctly!!', 'wp-social-reviews')];
            }

            return $response;
        }
    }

    public function updateCachedFeeds($caches)
    {
        //update cron schedule with new one in next run for old users
       $cronSchedule = get_option('wpsr_twitter_global_settings');
       if($cronSchedule) {
           if(Arr::get($cronSchedule, 'global_settings.expiration', 0) < 604800) {
               $cronSchedule['global_settings']['expiration'] = 604800;
               update_option('wpsr_twitter_global_settings', $cronSchedule);
           }
       }

        $this->cacheHandler->clearPageCaches($this->platform);
        $settings = get_option('wpsr_twitter_verification_configs');

        if ($settings && isset($settings['dynamic'])) {
            $settings = $this->getCredentialsSettings($settings['dynamic']);
            foreach ($caches as $cache) {
                $optionName = $cache['option_name'];

                $name_position    = strpos($optionName, '_name_');
                $hashtag = '';
                $hash_position    = strpos($optionName, '_#');
                $cache_position   = 0;
                $num_position     = strpos($optionName, '_num');
                $screenName       = '';
                if ($name_position) {
                    $feedType   = substr($optionName, $cache_position, $name_position);
                    $screenName = substr($optionName, $name_position + 6, $num_position - $name_position - 6);
                } elseif ($hash_position) {
                    $feedType = substr($optionName, $cache_position, $hash_position);
					$hashtag = substr($optionName, $hash_position + 1, $num_position - ($hash_position + 1));
                } else {
                    $feedType = substr($optionName, $cache_position, $num_position);
                }

                $totalFeed = substr($optionName, $num_position + 4);
                $totalFeed = intval($totalFeed);
                $this->userName = $screenName;

                $this->setGetFieldsString($screenName, $totalFeed, $feedType, $hashtag);

                $this->transient_name = $this->setTransientName($feedType, $totalFeed, $screenName, $hashtag);

                $this->sendApiRequest($settings, $feedType);
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
}
