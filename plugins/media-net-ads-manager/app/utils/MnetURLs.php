<?php

namespace Mnet\Utils;

use WP_Query;
use Mnet\MnetDbManager;
use Mnet\Utils\MnetAdUtils;

class MnetURLs
{
    public static function block($urls)
    {
        if (MnetDbManager::truncateIfExists(MnetDbManager::$MNET_BLOCKED_URLS)) {
            foreach ($urls as $url) {
                MnetDbManager::insertData(MnetDbManager::$MNET_BLOCKED_URLS, array('url' => $url));
            }
            return true;
        }
        return false;
    }

    public static function getGloballyBlocked()
    {
        global $wpdb;
        $urls = $wpdb->get_results("Select url from " . MnetDbManager::tableName(MnetDbManager::$MNET_BLOCKED_URLS), \ARRAY_A);
        return array_map(function ($url) {
            return $url['url'];
        }, $urls);
    }

    public static function getFilteredBlockedUrls($blockedUrls, $otherPageUrls, $isTypePost = true, $pagetype = 'default')
    {
        $scheme = is_ssl() ? "https" : "http";

        $result = array_reduce($blockedUrls, function ($list, $url) use ($scheme, &$otherPageUrls, $isTypePost, $pagetype) {
            $path = $scheme . "://" . $url;

            $blockedUrl = [];
            if (!$isTypePost || !($postId = \url_to_postid($path))) {
                if (isset($otherPageUrls[$url])) {
                    $blockedUrl = $otherPageUrls[$url];
                    $otherPageUrls[$url] = null;
                }
            } else {
                $list['blockedPosts'][] = $postId;
                $post = \get_post($postId);
                if ($pagetype === 'default' || $pagetype === $post->post_type) {
                    $blockedUrl = static::getPostUrlData($post);
                }
            }
            if (!empty($blockedUrl)) {
                $list['blockedUrls'][$url] = $blockedUrl;
            }
            return $list;
        }, [
            'urls' => [],
            'blockedUrls' => [],
            'blockedPosts' => []
        ]);

        $result['urls'] = array_merge($result['urls'], array_filter($otherPageUrls));

        return $result;
    }

    public static function blockForPageSlot($slot_name, $urls)
    {
        if (empty($urls)) {
            MnetDbManager::deleteData(MnetDbManager::$MNET_SLOT_BLOCKED_URLS, array('slot_name' => $slot_name));
            return;
        }
        $slot_mapping = self::getSlotUrlMapping($slot_name);
        if (!empty($slot_mapping)) {
            MnetDbManager::updateData(MnetDbManager::$MNET_SLOT_BLOCKED_URLS, array('url_list' => $urls), array('id' => $slot_mapping[0]['id']));
        } else {
            MnetDbManager::insertData(MnetDbManager::$MNET_SLOT_BLOCKED_URLS, array('slot_name' => $slot_name, 'url_list' => $urls));
        }
    }

    public static function getSlotUrlMapping($slot_name)
    {
        global $wpdb;
        return $wpdb->get_results("Select * from " . MnetDbManager::tableName(MnetDbManager::$MNET_SLOT_BLOCKED_URLS) . " where slot_name like '%$slot_name%'", \ARRAY_A);
    }

    public static function getPageSlotBlockedUrls($slot_name)
    {
        $slot_url_mapping = self::getSlotUrlMapping($slot_name);
        $result = array();
        if (!empty($slot_url_mapping)) {
            foreach ($slot_url_mapping as $slot_url) {
                $slot_page = explode('_', $slot_url['slot_name']);
                $slot = $slot_page[1];
                if (strlen($slot_url_mapping[0]['url_list'])) {
                    $urls = preg_split('/\;/', $slot_url_mapping[0]['url_list']);
                    $result[$slot] = $urls;
                }
            }
        }

        return $result;
    }

    /*
        It returns urls for all pages except Article and Static
    */
    public static function getOtherPageUrls()
    {
        return array_merge(
            [],
            static::getHomeUrl(),
            static::getCategoryUrls(),
            static::getArchiveUrls()
        );
    }

    public static function getHomeUrl()
    {
        $url = MnetAdUtils::trimUrl(\get_home_url());
        return array($url => array('name' => 'Home', 'url' => $url));
    }

    public static function getArchiveUrls()
    {
        global $wpdb;
        $urls = array();
        $query = "SELECT DISTINCT MONTH( post_date ) AS month ,	YEAR( post_date ) AS year FROM $wpdb->posts WHERE post_status = 'publish' and post_date <= now( ) and post_type = 'post' GROUP BY month , year ORDER BY post_date DESC";
        $post_dates = $wpdb->get_results($query);
        foreach ($post_dates as $date) {
            $monthName = date('F', mktime(0, 0, 0, $date->month, 10));
            $url = MnetAdUtils::trimUrl(\home_url()) . '/' . $date->year . '/' . str_pad($date->month, 2, '0', STR_PAD_LEFT);
            $urls[$url] = array(
                'page' => 'Archive',
                'name' => $monthName . ' ' . $date->year,
                'url' => $url
            );
        }
        return $urls;
    }

    public static function getCategoryUrls()
    {
        $categories = \get_categories();
        $urls = array();
        foreach ($categories as $category) {
            $url = MnetAdUtils::trimUrl(\get_category_link($category->term_id));
            $urls[$url] = array(
                'page' => 'Category',
                'name' => $category->name,
                'url' => $url
            );
        }
        return $urls;
    }

    public static function getPostPageUrls($count, $excludePosts, $search)
    {
        $articles = MnetURLs::getPostUrls('post', $count, $excludePosts, $search);
        $staticPages = MnetURLs::getPostUrls('page', $count, $excludePosts, $search);

        $done = false;
        if (count($articles) < $count && count($staticPages) < $count) {
            $done = true;
        }

        // Check if there are more pages without search term
        if (!empty($search)) {
            $done = false;
            $excludePosts = array_merge(
                $excludePosts,
                array_map(function ($url) {
                    return $url['id'];
                }, $articles),
                array_map(function ($url) {
                    return $url['id'];
                }, $staticPages)
            );
            $moreArticles = MnetURLs::getPostUrls('post', $count, $excludePosts, '');
            $morePages =  MnetURLs::getPostUrls('page', $count, $excludePosts, '');

            if (count($moreArticles) < $count && count($morePages) < $count) {
                $done = true;
            }

            $articles = array_merge($articles, $moreArticles);
            $staticPages = array_merge($staticPages, $morePages);
        }
        $urls = array_merge($articles, $staticPages);
        return compact('urls', 'done');
    }

    public static function getPostUrls($type, $count, $excludePosts, $search = '')
    {
        // Urls of all posts
        $args = array(
            'post_type' => $type,
            'posts_per_page' => $count,
            'post_status' => 'publish',
            'post__not_in' => $excludePosts,
        );

        if (!empty($search)) {
            add_filter('posts_where', [static::class, 'titleFilter'], 10, 2);
            $args['search_prod_title'] = $search;
        }
        $post_query = new WP_Query($args);

        if (!empty($search)) {
            remove_filter('posts_where', [static::class, 'titleFilter'], 10, 2);
        }

        if (!$post_query->have_posts()) return [];

        $posts = $post_query->posts;
        $urls = [];
        foreach ($posts as $post) {
            $data = static::getPostUrlData($post);
            $urls[$data['url']] = $data;
        }
        return $urls;
    }

    public static function getPostUrlData($post)
    {
        $page = 'Article';
        if ($post->post_type === 'post') {
            $link = \get_permalink($post->ID);
        } else {
            $page = 'Static';
            $link = \get_page_link($post->ID);
        }
        return array(
            'id' => $post->ID,
            'page' => $page,
            'name' => $post->post_title,
            'url' => MnetAdUtils::trimUrl($link)
        );
    }

    public static function titleFilter($where, &$wp_query)
    {
        global $wpdb;
        if ($search_term = $wp_query->get('search_prod_title')) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql(like_escape($search_term)) . '%\'';
        }
        return $where;
    }
}
