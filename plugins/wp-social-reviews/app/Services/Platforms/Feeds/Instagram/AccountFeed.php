<?php

namespace WPSocialReviews\App\Services\Platforms\Feeds\Instagram;

use WPSocialReviews\App\Services\DataProtector;
use WPSocialReviews\App\Services\Platforms\Feeds\Instagram\Common;
use WPSocialReviews\App\Services\Platforms\Feeds\CacheHandler;
use WPSocialReviews\App\Services\Platforms\Feeds\Instagram\RefreshToken;
use WPSocialReviews\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class AccountFeed
{
    protected $cacheHandler;

    public function __construct()
    {
        $this->cacheHandler = new CacheHandler('instagram');
    }

    /**
     * Get Accounts Feed
     *
     * @param array $accountIds
     *
     * @return array
     * @since 1.3.0
     */
    public function getMultipleAccountResponse($accountIds)
    {
        $response          = array();
        $connectedAccounts = (new Common())->findConnectedAccounts();
        $error_message = '';
        foreach ($accountIds as $index => $accountId) {
            $accountDetails     = Arr::get($connectedAccounts, $accountId) ? $connectedAccounts[$accountId] : '';

            if(empty($accountDetails)) {
                $error_message  .= sprintf(__('There are multiple accounts being used on this template. The account ID(%s) associated with your configuration settings has been deleted. To view your feed from this account, please reauthorize and reconnect it.', 'wp-social-reviews'), $accountId);
                continue;
            }

            $userName = $accountDetails ? $accountDetails['username'] : '';
            $has_account_error_code = Arr::get($accountDetails, 'error_code');

            if($has_account_error_code) {
                if (sizeof($connectedAccounts) > 1) {
                    $error_message .= sprintf(__('There has been a problem with your account(%s). ', 'wp-social-reviews'), $userName) . Arr::get($connectedAccounts, $accountId . '.error_message', '') . '<br/> <br/>';
                } else {
                    $error_message .= sprintf(__('There has been a problem with your account(%s). ', 'wp-social-reviews'), $userName) . Arr::get($accountDetails, 'error_message', '') . '<br/> <br/>';
                }
            }

            $resultWithComments = array();
            if ($accountDetails) {
                $feedCacheName   = 'user_account_feed_id_' . $accountId;
                $instagramApiUrl = $this->getApiUrl($accountDetails);
                // return errors
                if ( is_array($instagramApiUrl) && (new Common())->instagramError($instagramApiUrl)) {
                    $error_message .= $instagramApiUrl;
                }
	            $resultWithComments = $this->cacheHandler->getFeedCache($feedCacheName);

                if (!$resultWithComments && empty($has_account_error_code)) {
                    $resultWithoutComments = (new Common())->expandWithoutComments($instagramApiUrl);
                    if ((new Common())->instagramError($resultWithoutComments)) {
                        $error_message .= sprintf(__('There has been a problem with your account(%s). ', 'wp-social-reviews'), $userName) . Arr::get($resultWithoutComments, 'error.message');
                    }
                    if (!(new Common())->instagramError($resultWithoutComments)) {
                        $feeds = Arr::get($resultWithoutComments, 'data');
                        //we modify username if collab feed there
                        if(!empty($feeds) && is_array($feeds)){
                            foreach ($feeds as $key => $feed) {
                                $feeds[$key]['username'] = $userName;
                            }
                        }

                        $resultWithoutComments['data'] = $feeds;

                        $resultWithComments = (new Common())->expandWithComments($accountDetails, $resultWithoutComments);
                        if ((new Common())->instagramError($resultWithComments)) {
                            $error_message .= sprintf(__('There has been a problem with your account(%s). ', 'wp-social-reviews'), $userName) . Arr::get($resultWithComments, 'error.message');
                        }
                        $this->cacheHandler->createCache($feedCacheName, $resultWithComments);
                    } else {
                        $resultWithComments = false;
                    }
                }
            }
            $response[$index] = $resultWithComments ? $resultWithComments : array();
        }

        $accountFeed = array();
        foreach ($response as $index => $feeds) {
            if(!empty($feeds) && is_array($feeds)) {
                $accountFeed = array_merge($accountFeed, $feeds);
            }
        }

        return [
            'error_message' => $error_message,
            'feeds'         => $accountFeed
        ];
    }

    public function getApiUrl($accountDetails)
    {
        $dataProtector = new DataProtector();
        $num    = apply_filters('wpsocialreviews/instagram_feeds_limit', 10);
        $apiUrl = '';
        if ($accountDetails['api_type'] === 'business') {
            $decrypt_access_token = $dataProtector->decrypt($accountDetails['access_token']) ? $dataProtector->decrypt($accountDetails['access_token']) : $accountDetails['access_token'];
            $apiUrl = 'https://graph.facebook.com/' . $accountDetails['user_id'] . '/media?fields=media_url,thumbnail_url,caption,id,media_type,timestamp,username,comments_count,like_count,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}&limit=' . $num . '&access_token=' .$decrypt_access_token. '&status_code=PUBLISHED';
        } elseif ($accountDetails['api_type'] === 'personal') {
            $accessToken = (new RefreshToken())->getAccessToken($accountDetails);
            $accessToken = $dataProtector->decrypt($accessToken) ? $dataProtector->decrypt($accessToken) : $accessToken;

            // return errors
            if (is_array($accessToken) && (new Common())->instagramError($accessToken)) {
                return $accessToken;
            }

            $apiUrl      = 'https://graph.instagram.com/' . $accountDetails['user_id'] . '/media?fields=media_url,thumbnail_url,caption,id,media_type,timestamp,username,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}&limit=' . $num . '&access_token=' . $accessToken.'&status_code=PUBLISHED';
        }

        return $apiUrl;
    }
}
