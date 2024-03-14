<?php

namespace WPSocialReviews\App\Services;

use WPSocialReviews\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class Helper
{
    static $loadedTemplates = [];

    public static function getAccessPermission()
    {
        return apply_filters('wpsr_admin_permission', 'manage_options');
    }
    public static function allowedHtmlTags()
    {
        $allowed_tags = array(
            //formatting
            'strong' => array(),
            'br'     => array(),
            'strike' => array(),
            'dl'     => array(),
            'dt'     => array(),
            'em'     => array(),
            'b'      => array(),
            'i'      => array(),
            //links
            'a'      => array(
                'href' => array()
            ),

            'cite'       => array(
                'title' => array(),
            ),
            'code'       => array(),
            'del'        => array(
                'datetime' => array(),
                'title'    => array(),
            ),
            'blockquote' => array(
                'cite' => true,
            ),
            'p'          => array(
                'class' => array(),
                'style' => array(),
            ),
            'span'       => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'style'      => array()
        );

        return $allowed_tags;
    }

    public static function shortNumberFormat($number)
    {
        $units = ['', 'K', 'M', 'B', 'T'];
        for ($i = 0; $number >= 1000; $i++) {
            $number /= 1000;
        }

        return round($number, 1) . $units[$i];
    }

    public static function getVideoDuration($duration)
    {
        $di   = new \DateInterval($duration);
        $hour = '';
        if ($di->h > 0) {
            $hour .= $di->h . ':';
        }

        return $hour . $di->i . ':' . sprintf('%02s', $di->s);
    }

    public static function numberWithCommas($number)
    {
        return number_format($number, 0, '.', ',');
    }

    public static function shortcodeAllowedPlatforms(){
        return [
            'reviews',
            'twitter',
            'youtube',
            'instagram',
            'facebook',
            'tiktok',
            'facebook_feed',
            'testimonial'
        ];
    }

    public static function getPostTypes($formatted = true){
        $post_types = get_post_types(
            [
                'public' => true,
                'show_in_nav_menus' => true
            ],
            'objects'
        );

        $post_types = wp_list_pluck($post_types, 'label', 'name');
        $post_types = array_diff_key($post_types, ['attachment']);

        $post_types_list = [];
        if (!empty($post_types) && !is_wp_error($post_types)) {
            foreach ($post_types as $key => $post_type) {
                if($formatted) {
                    $post_types_list[] = array(
                        'name' => $key,
                        'title' => $post_type
                    );
                } else {
                    $post_types_list[$key] = $key;
                }
            }
        }

        return apply_filters('wpsocialreviews/available_post_types', $post_types_list);
    }

    /**
     * Get all pages list
     *
     * @return array
     * @since 1.1.0
     */
    public static function getPagesList()
    {
        $pages = get_posts([
            'post_type' => static::getPostTypes(false),
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        $page_list = array(array('id' => '-1', 'title' => __('Everywhere', 'wp-social-reviews')));
        if (!empty($pages) && !is_wp_error($pages)) {
            foreach ($pages as $page) {
                $lang = '';
                if(defined('POLYLANG_VERSION')){
                    $lang = pll_get_post_language( $page->ID, 'name');
                    $lang = $lang ? ' ('. $lang .')' : '';
                }

                $page_list[] = array('id'    => $page->ID . '',
                    'title' => $page->post_title ? $page->post_title . $lang : __('Untitled',
                        'wp-social-reviews') . $lang,
                    'url'   => get_permalink($page->ID)
                );
            }
        }

        return $page_list;
    }

    public static function getPostsByPostType($postType = 'post')
    {
        $posts  = get_posts([
            'post_type' => $postType,
            'post_status' => 'publish',
            'numberposts' => -1
        ]);

        $post_lists = array(array('id' => '-1', 'title' => __('Everywhere', 'wp-social-reviews')));
        if (!empty($posts) && !is_wp_error($posts)) {
            foreach ($posts as $post) {
                $post_lists[] = array(
                    'id'    => $post->ID . '',
                    'title' => $post->post_title ? $post->post_title : __('Untitled',
                        'wp-social-reviews'),
                    'url'   => get_permalink($post->ID)
                );
            }
        }

        return $post_lists;
    }

    public static function getShortCodeIds($content, $tag = 'wp_social_ninja', $selector = 'id')
    {

        if (false === strpos($content, '['.$tag)) {
            return [];
        }

        preg_match_all('/' . get_shortcode_regex() . '/', $content, $matches, PREG_SET_ORDER);
        if (empty($matches)) {
            return [];
        }

        $ids = [];

        foreach ($matches as $shortcode) {
            if (count($shortcode) >= 2 && $tag === $shortcode[2]) {
                // Replace braces with empty string.
                $parsedCode = str_replace(['[', ']', '&#91;', '&#93;'], '', $shortcode[0]);

                $result = shortcode_parse_atts($parsedCode);

                if (!empty($result[$selector])) {
                    $ids[$result[$selector]] = $result[$selector];
                }
            }
        }

        return $ids;
    }

    public static function isTemplateMatched($settings)
    {
        global $post;

        // hiding on desktop device
        $hide_on_desktop = Arr::get($settings, 'hide_on_desktop');
        if(!wp_is_mobile() && $hide_on_desktop === 'true'){
            return false;
        }

        // hiding on mobile device
        $hide_on_mobile = Arr::get($settings, 'hide_on_mobile');
        if($hide_on_mobile === 'true' && wp_is_mobile()){
            return false;
        }

        // let's check by post type
        $postTypes = Arr::get($settings, 'post_types');
        $pageList = Arr::get($settings, 'page_list', []);
        $chat_lang = Arr::get($settings, 'chat_lang', '');

        if(empty($postTypes) && empty($pageList) && $chat_lang && $chat_lang === get_locale()){
            return true;
        } else if(!empty($post) && !empty($postTypes)) {
            if($postTypes && in_array($post->post_type, $postTypes) && $chat_lang && $chat_lang === get_locale()){
                return true;
            }
            if ($chat_lang === '' && $postTypes && in_array($post->post_type, $postTypes)) {
                return true;
            }
        } else {
            $excludePages = Arr::get($settings, 'exclude_page_list', []);
            if(!empty($post)) {
                if (in_array($post->ID, $excludePages) || in_array('-1', $excludePages)) {
                    return false;
                }

                if(defined('WC_VERSION') && is_shop()){
                    $page_id = wc_get_page_id('shop');
                } else {
                    $page_id = $post->ID;
                }

                // Validate if the config is valid for the current request
                if((in_array($page_id, $pageList) || in_array('-1', $pageList)) && $chat_lang && $chat_lang === get_locale()){
                    return true;
                }
                if ((in_array($page_id, $pageList) || in_array('-1', $pageList)) && $chat_lang === '') {
                    return true;
                }
            }
        }

        return false;
    }

    public static function hasColumn( $table_name, $column_name ) {

        global $wpdb;

        $column = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
            DB_NAME, $table_name, $column_name
        ) );

        if ( ! empty( $column ) ) {
            return true;
        }

        return false;
    }

    /**
     * Print internal content (not user input) without escaping.
     */
    public static function printInternalString( $string ) {
        echo $string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    public static function customEncodeEmoji($content) {
        $emoji = ['&#129655;'];

        foreach ( $emoji as $emojum ) {
            $emoji_char = html_entity_decode( $emojum );
            if ( false !== strpos( $content, $emoji_char ) ) {
                $content = preg_replace( "/$emoji_char/", $emojum, $content );
            }
        }

        return $content;
    }

    public static function getSiteDefaultDateFormat($created_at)
    {
        $date_format = get_option('date_format');
        return date_i18n($date_format, $created_at);
    }
}