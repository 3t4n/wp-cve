<?php

namespace Mnet\Admin;

use Mnet\Admin\MnetAuthManager;
use Mnet\Utils\MnetURLs;
use Mnet\Utils\MnetAdSlot;
use Mnet\Admin\MnetAdTag;
use Mnet\Admin\MnetPluginUtils;
use Mnet\Admin\MnetNotices;
use Mnet\MnetDbManager;
use Mnet\Utils\DefaultOptions;
use Mnet\Utils\Response;

class MnetAdHandleAjaxCalls
{
    public static function getBlockedAndAllUrls()
    {
        MnetAuthManager::returnIfSessionExpired();

        $firstFetch = isset($_GET['firstFetch']) ? intval($_GET['firstFetch']) : 0;
        $count = isset($_GET['count']) ? intval($_GET['count']) : -1;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $excludePosts = isset($_GET['excludePosts']) ? $_GET['excludePosts'] : [];

        $unblockedUrls = array();
        $blockedUrls = null;
        if ($firstFetch) {
            $otherPageUrls = MnetURLs::getOtherPageUrls();
            $blockedUrls = MnetURLs::getGloballyBlocked();
            $result = MnetURLs::getFilteredBlockedUrls($blockedUrls, $otherPageUrls);
            $unblockedUrls = $result['urls'];
            $excludePosts = $result['blockedPosts'];
            $blockedUrls = $result['blockedUrls'];
        }
        $postUrls = MnetURLs::getPostPageUrls($count, $excludePosts, $search);
        $unblockedUrls = array_merge(
            $unblockedUrls,
            $postUrls['urls']
        );
        $done = $postUrls['done'];
        \wp_send_json(compact('unblockedUrls', 'blockedUrls', 'done'), 200);
    }

    public static function getPageBlockedAndAllUrls()
    {
        $pageType = isset($_GET['pageType']) ? $_GET['pageType'] : MNET_PAGETYPE_ARTICLE;

        $excludePosts = isset($_GET['excludePosts']) ? $_GET['excludePosts'] : [];
        // If this is the first fetch, return blocked urls data too
        $isFirstFetch = empty($excludePosts);

        $blockedUrls = [];
        // If this is not the first post then, excludePost would have the list of post ids
        // which is used to fetch next count number of posts
        $pageBlockedUrls = null;
        $globallyBlockedUrls = null;
        if ($isFirstFetch) {
            $pageBlockedUrls = MnetURLs::getPageSlotBlockedUrls($pageType);
            $globallyBlockedUrls = MnetURLs::getGloballyBlocked();

            // get each blocked urls data
            $blockedUrls = array_reduce($pageBlockedUrls, function ($acc, $urls) {
                $acc = array_merge($acc, $urls);
                return $acc;
            }, []);
            $blockedUrls = array_unique(array_merge($blockedUrls, $globallyBlockedUrls));
        }
        $urls = null;

        switch ($pageType) {
                // Pagination possible only for these two pages
            case MNET_PAGETYPE_ARTICLE:
            case MNET_PAGETYPE_STATIC: {
                    $count = isset($_GET['count']) ? intval($_GET['count']) : -1;
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $type = $pageType === MNET_PAGETYPE_ARTICLE ? 'post' : 'page';

                    $allBlockedUrls = [];
                    if ($isFirstFetch) {
                        $filteredUrls = MnetURLs::getFilteredBlockedUrls($blockedUrls, [], true, $type);
                        $allBlockedUrls = $filteredUrls['blockedUrls'];
                        $excludePosts = $filteredUrls['blockedPosts'];

                        $globallyBlockedUrls = array_filter($allBlockedUrls, function ($url) use ($globallyBlockedUrls) {
                            return in_array($url, $globallyBlockedUrls);
                        }, ARRAY_FILTER_USE_KEY);
                    }
                    $unblockedUrls = MnetURLs::getPostUrls($type, $count, $excludePosts, $search);
                    // If we reached last page then the result would have less number of rows than what we asked for
                    $done = count($unblockedUrls) < $count;

                    // If this was a search, then we cannot rely on result count
                    // We need to make next fetch excluding the posts of the search result too
                    // and the condition for last page is same as above
                    if ($search) {
                        $excludePosts = array_merge($excludePosts, array_map(function ($url) {
                            return $url['id'];
                        }, $unblockedUrls));
                        $nextUnblockedUrls = MnetURLs::getPostUrls($type, $count, $excludePosts);
                        $done = count($nextUnblockedUrls) < $count;
                        $unblockedUrls = array_merge($unblockedUrls, $nextUnblockedUrls);
                    }

                    $allUrls = array_merge($unblockedUrls, $allBlockedUrls);
                    if ($globallyBlockedUrls) {
                        $allUrls = array_filter($allUrls, function ($url) use ($globallyBlockedUrls) {
                            return empty($globallyBlockedUrls[$url]);
                        }, ARRAY_FILTER_USE_KEY);
                    }
                    return Response::success(compact('allUrls', 'globallyBlockedUrls', 'pageBlockedUrls', 'done'));
                }
            case MNET_PAGETYPE_CATEGORY:
                $urls = MnetURLs::getCategoryUrls();
                break;
            case MNET_PAGETYPE_ARCHIVE:
                $urls = MnetURLs::getArchiveUrls();
                break;
        }

        $filteredUrls = MnetURLs::getFilteredBlockedUrls($blockedUrls, $urls, false);
        $allBlockedUrls = $filteredUrls['blockedUrls'];
        $allUrls = array_merge($filteredUrls['urls'], $allBlockedUrls);

        $globallyBlockedUrls = array_filter($allBlockedUrls, function ($url) use ($globallyBlockedUrls) {
            return in_array($url, $globallyBlockedUrls);
        }, ARRAY_FILTER_USE_KEY);
        $allUrls = array_filter($allUrls, function ($url) use ($globallyBlockedUrls) {
            return empty($globallyBlockedUrls[$url]);
        }, ARRAY_FILTER_USE_KEY);

        $done = true;
        return Response::success(compact(
            'allUrls',
            'globallyBlockedUrls',
            'pageBlockedUrls',
            'done'
        ));
    }

    public static function blockUrls()
    {
        MnetAuthManager::returnIfSessionExpired();
        $urls = $_POST['urls'];
        $urls = empty($urls) ? array() : $urls;
        \wp_send_json(array('result' => MnetURLs::block($urls)), 200);
    }

    public static function getNoOfAdSlots()
    {
        MnetAuthManager::returnIfSessionExpired();
        \wp_send_json(array('count' => MnetAdSlot::count()), 200);
    }

    public static function getAdTags()
    {
        MnetAuthManager::returnIfSessionExpired();
        \wp_send_json(array('adtags' => MnetAdTag::all()), 200);
    }

    public static function removeAllSlots()
    {
        MnetAuthManager::returnIfSessionExpired();
        $result = intval(MnetDbManager::clearSlots());
        \wp_send_json(array('reset' => $result), 200);
    }

    public static function refreshAdtags()
    {
        MnetAuthManager::returnIfSessionExpired();
        try {
            $result = MnetAdTag::fetchAdTags();
            if (isset($result['status']) && $result['status'] == 'error') {
                \wp_send_json_error(array("type" => "error", "message" => $result['message']), 500);
            }
            $result['adtags'] = MnetAdtag::all();
            \wp_send_json($result, 200);
        } catch (\Exception $e) {
            \wp_send_json_error(array("type" => "error", "message" => "Something went wrong. Please retry after sometime."), 500);
        }
    }

    public static function getCustomerName()
    {
        MnetAuthManager::returnIfSessionExpired();
        \wp_send_json(array('name' => \mnet_user()->name), 200);
    }

    public static function logout()
    {
        MnetAuthManager::returnIfSessionExpired();
        $keep_slots = intval(json_decode($_GET['keepSlots']));
        if (!$keep_slots) {
            MnetDbManager::clearSlots();
        }
        MnetAuthManager::logout();
        \wp_send_json(array('status' => 'logged out'), 200);
    }

    public static function getUploadMaxSize()
    {
        MnetAuthManager::returnIfSessionExpired();
        \wp_send_json(array('post_max' => ini_get('post_max_size'), 'upload_file_max' => ini_get('upload_max_filesize')), 200);
    }

    public static function sendMail()
    {
        MnetAuthManager::returnIfSessionExpired();
        $attachments = array();
        if (isset($_FILES['attachments'])) {
            foreach ($_FILES['attachments']['tmp_name'] as $index => $src) {
                $tmpPath = \WP_CONTENT_DIR . '/uploads/' . $_FILES['attachments']['name'][$index];
                if (move_uploaded_file($src, $tmpPath)) {
                    $attachments[] = $tmpPath;
                }
            }
        }
        $subject = "[Wordpress-Plugin-Support]: " . $_POST['subject'];
        $content = $_POST['content'];
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers[] = "From: " . \mnet_user()->email;
        if (\mnet_user()->inactive) {
            $to = 'publisher-appeal@media.net';
            $headers[] = "Cc: wpsupport@media.net";
        } elseif (\mnet_site()->status === 'site_rejected_error') {
            $to = 'pubsupport@media.net';
            $headers[] = "Cc: wpsupport@media.net";
        } else {
            $to = 'pubsupport@media.net';
        }
        $message = self::getFormattedMailContent($content);
        $mail_sent = intval(\wp_mail($to, $subject, $message, $headers, $attachments));
        if ($mail_sent) {
            \wp_send_json("Mail sent successfully!", 200);
        }
        \wp_send_json_error("Failed to send mail. Please check your Hosting Settings/MX Record", 500);
    }

    public static function getFormattedMailContent($content)
    {
        $finalContent = "<b>Customer name:</b> " . \mnet_user()->name;
        $finalContent .= "<br><b>Domain name:</b> " . \get_home_url();
        $email = MnetPluginUtils::getUserEmailId();
        if ($email) {
            $finalContent .= "<br><b>Customer wordpress email:</b> " . $email;
        }
        return $finalContent . "<br><b>User Message:</b> <br>" . $content;
    }

    public static function getFaqs()
    {
        MnetAuthManager::returnIfSessionExpired();
        $url = MNET_API_ENDPOINT . 'faq?access_token=' . \mnet_user()->token;
        $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);
        MnetAuthManager::handleAccessTokenExpired($response);
        if (\is_wp_error($response) || !isset($response['body']) || $response['body'] == "") {
            \wp_send_json_error(
                array("status" => "error", "message" => "Failed to fetch FAQs"),
                400
            );
        }
        \wp_send_json(json_decode($response['body']));
    }

    public static function getAllNotices()
    {
        \wp_send_json(array('notices' => MnetNotices::getAdminNotices()), 200);
    }

    public static function dismissNotice()
    {
        $id = $_POST['id'];
        MnetNotices::dismissNotice($id);
    }

    public static function logAdminPageError($data = null)
    {
        if (empty($data)) {
            $data = $_POST;
        }
        MnetPluginUtils::sendErrorReport($data);
    }
}
