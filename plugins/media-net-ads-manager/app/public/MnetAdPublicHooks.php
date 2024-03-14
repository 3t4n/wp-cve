<?php

namespace Mnet\PublicViews;

use Arr;
use Mnet\Utils\MnetAdUtils;
use Mnet\MnetDbManager;
use Mnet\PublicViews\MnetInjectedAdTag;
use Mnet\Utils\MnetURLs;
use Mnet\Admin\MnetAdTag;
use Mnet\Admin\MnetAuthManager;

class MnetAdPublicHooks
{
    public static $debug_mode = 0;
    public static $blocked_urls = array();

    public static $post_count = 1;
    public static $placed_in_between = false;

    public static function mnetLoopCheck($query)
    {
        if (isset($query) && method_exists($query, 'is_main_query')) {
            return $query->is_main_query();
        }
        return MnetAdUtils::getPageType() == MNET_PAGETYPE_STATIC;
    }

    public static function mnetPostCheck()
    {
        if (self::$placed_in_between) {
            return false;
        }
        if (!\in_the_loop()) {
            return false;
        }

        if (!defined('MNET_FIRST_POST_CHECKED')) {
            define('MNET_FIRST_POST_CHECKED', true);
            return false;
        }
        return true;
    }

    public static function enqueueScripts()
    {
        $slotCount = self::getSlotCountForCurrentPage();
        if ($slotCount == 0) {
            return;
        }
        \wp_register_script('mnetAdInjectHandler', \plugin_dir_url(__DIR__) . '/../../js/medianetAdInjector.js', array(), MNET_PLUGIN_VERSION);
        \wp_localize_script('mnetAdInjectHandler', 'mnetCustomerData', array('cid' => \mnet_user()->crid));
        \wp_enqueue_script('mnetAdInjectHandler');
    }

    public static function getSlotCountForCurrentPage()
    {
        $page = MnetAdUtils::getPageType();
        return MnetDbManager::getRowCount(MnetDbManager::$MNET_AD_SLOTS, array('page' => $page));
    }

    public static function publicInjectInHead()
    {
        self::injectMetaTags();
        self::publicInjectScriptTag();
    }

    public static function injectMetaTags()
    {
        $tag = '<meta name="mnet_plugin_version" content="' . MNET_PLUGIN_VERSION . '" />';
        echo $tag;
    }

    public static function publicInjectScriptTag()
    {
        // hide ads on customize preview page
        // if (is_customize_preview()) {
        //     return;
        // }
        $slotCount = self::getSlotCountForCurrentPage();
        if ($slotCount !== 0) {
            if (isset($_GET['debug'])) {
                self::$debug_mode = 1;
            }
            self::$blocked_urls = MnetURLs::getGloballyBlocked();
            $device_detect = new \mnet_Mobile_Detect();

            $head_content = array();
            $slots = MnetDbManager::getAdSlotsForPage();

            $ptypes_for_page = array();
            foreach ($slots as $slot) {
                $position = $slot['position'];
                if (in_array($slot['ptype_id'], array(MnetInjectedAdTag::$P_TYPE_INTERSTITIAL_ADS, MnetInjectedAdTag::$P_TYPE_MOBILE_ADS, MnetInjectedAdTag::$P_TYPE_INTERSTITIAL_MOBILE_ADS))) {
                    if ($slot['ptype_id'] == MnetInjectedAdTag::$P_TYPE_INTERSTITIAL_ADS) {
                        $head_content['dinter'] = MnetInjectedAdTag::getTagDetails($slot['tag_id'], $position, MnetInjectedAdTag::$TAG_TYPE_INTER);
                    } else if ($device_detect->isMobile() || $device_detect->isTablet()) {
                        if ($slot['ptype_id'] == MnetInjectedAdTag::$P_TYPE_MOBILE_ADS) {
                            $head_content['mdock'] = MnetInjectedAdTag::getTagDetails($slot['tag_id'], $position, MnetInjectedAdTag::$TAG_TYPE_MOBILE);
                        } else if ($slot['ptype_id'] == MnetInjectedAdTag::$P_TYPE_INTERSTITIAL_MOBILE_ADS) {
                            $head_content['minter'] = MnetInjectedAdTag::getTagDetails($slot['tag_id'], $position);
                        }
                    }
                } else {
                    $ptypes_for_page[] = $slot['ptype_id'];
                }
            }

            if (!empty($ptypes_for_page)) {
                $head_content['script_tag'] = MnetInjectedAdTag::getHeadScript($ptypes_for_page);
            }

            foreach ($head_content as $value) {
                echo $value;
            }
        }
    }

    public static function mnetPublicInjectAd($content, $check_function, $position)
    {
        global $wp_query, $wp;
        $current_url = MnetAdUtils::trimUrl(\home_url($wp->request));
        if (self::isUrlBlocked($current_url)) {
            return;
        }

        $page = MnetAdUtils::getPageType();
        if ($page == MNET_PAGETYPE_ALL || $page == MNET_PAGETYPE_ADMIN) {
            return;
        }
        if (empty($check_function) || forward_static_call(array('self', $check_function), $content)) {
            $slots = MnetDbManager::getAdSlots([$position], $page, array(), true);
            foreach ($slots as $slot) {
                if (!self::debugModeAndBlockedUrlCheck($page, $slot, $current_url)) {
                    continue;
                }

                $post_no = intval(MnetDbManager::getNumberForSlot('post', $slot['id']));
                if ($post_no) {
                    if (intval($wp_query->current_post) !== $post_no) {
                        continue;
                    }
                }
                echo self::getAdCode($slot);
                if (!empty($check_function) && $check_function === 'mnetPostCheck') {
                    self::$placed_in_between = true;
                }
            }
        }
    }

    public static function publicInjectLoopStart($content)
    {
        $position = MNET_AD_POSITION_ABOVE_POST_LISTING;
        if (MnetAdUtils::getPageType() == MNET_PAGETYPE_ARTICLE || MnetAdUtils::getPageType() == MNET_PAGETYPE_STATIC) {
            $position = MNET_AD_POSITION_ABOVE_ARTICLE;
        }
        self::mnetPublicInjectAd($content, 'mnetLoopCheck', $position);
    }

    public static function publicInjectLoopEnd($content)
    {
        self::mnetPublicInjectAd($content, 'mnetLoopCheck', MNET_AD_POSITION_BELOW_POST_LISTING);
    }

    public static function publicInjectBetweenPosts($content)
    {
        self::mnetPublicInjectAd($content, 'mnetPostCheck', MNET_AD_POSITION_BETWEEN_POST_LISTING);
    }

    public static function publicBeforeSidebarHook()
    {
        self::mnetPublicInjectAd(null, null, MNET_AD_POSITION_ABOVE_SIDEBAR);
    }

    public static function publicAfterSidebarHook()
    {
        self::mnetPublicInjectAd(null, null, MNET_AD_POSITION_BELOW_SIDEBAR);
    }

    public static function isUrlBlocked($current_url)
    {
        foreach (self::$blocked_urls as $url) {
            if ($current_url == $url) {
                return true;
            }
        }
        return false;
    }

    public static function debugModeAndBlockedUrlCheck($page, $slot, $current_url)
    {
        if ($slot['debug'] && !self::$debug_mode) {
            return false;
        }
        $blocked_urls = MnetURLs::getPageSlotBlockedUrls($page . '_' . $slot['position']);
        if (!empty(Arr::get($blocked_urls, $slot['position'])) && in_array($current_url, $blocked_urls[$slot['position']])) {
            return false;
        }
        return true;
    }

    public static function getAdCode($slot)
    {
        $css_options = json_decode($slot['options']);
        $custom_css = $slot['custom_css'];
        $positions = array(
            MNET_AD_POSITION_ABOVE_POST_LISTING,
            MNET_AD_POSITION_BETWEEN_POST_LISTING,
            MNET_AD_POSITION_BELOW_POST_LISTING
        );
        if (in_array($slot['position'], $positions)) {
            $custom_css .= "clear: both;";
        }
        if ($slot['ad_type'] == MNET_AD_TYPE_EXTERNAL) {
            return MnetInjectedAdTag::getExternalCode($custom_css, $css_options, $slot);
        } else {
            $ad_tag = MnetAdTag::getAdtag($slot['tag_id']);
            if (!empty($ad_tag)) {
                return MnetInjectedAdTag::createTemplateCall($ad_tag, $css_options, $custom_css, $slot['position']);
            }
        }
        return '';
    }

    public static function mnetPublicInjectInContent($content)
    {
        global $wp;
        $before_position = MNET_AD_POSITION_AFTER_TITLE;
        $after_position = MNET_AD_POSITION_BELOW_ARTICLE;
        $between_position = MNET_AD_POSITION_BETWEEN_CONTENT;
        $current_url = MnetAdUtils::trimUrl(\home_url($wp->request));
        if (self::isUrlBlocked($current_url)) {
            return $content;
        }

        $page = MnetAdUtils::getPageType();
        if ($page == MNET_PAGETYPE_ALL || $page == MNET_PAGETYPE_ADMIN) {
            return $content;
        }
        $slots = MnetDbManager::getAdSlots(array($before_position, $after_position, $between_position), $page, array(), true);
        foreach ($slots as $slot) {
            if (!self::debugModeAndBlockedUrlCheck($page, $slot, $current_url)) {
                continue;
            }
            $ad_code = self::getAdCode($slot);
            if ($slot['position'] === $before_position) { // append code before content
                $content = $ad_code . $content;
            } elseif ($slot['position'] === $after_position) { // append code after content
                $content = $content . $ad_code;
            } elseif ($slot['position'] === $between_position) {
                // Paragraph counting
                $paragraph_index = intval(MnetDbManager::getNumberForSlot('paragraph', $slot['id'])) - 1;
                $paragraph_tag = '<p';
                $tag_last_position = -1;
                $configured_paragraph_position = -1;
                $paragraph_tag_len = strlen($paragraph_tag);
                $index = 0;
                while (stripos($content, $paragraph_tag, $tag_last_position + 1) !== false) {
                    $tag_last_position =  stripos($content, $paragraph_tag, $tag_last_position + 1);
                    if ($configured_paragraph_position != -1) {
                        $content = substr_replace($content, $ad_code, $tag_last_position, 0);
                        break;
                    }
                    if ($index == $paragraph_index && in_array($content[$tag_last_position + $paragraph_tag_len], array(">", " "))) {
                        $configured_paragraph_position = $tag_last_position;
                    }
                    $index++;
                }
            }
        }
        return $content;
    }

    public static function mnet_widgets_init()
    {
        \register_widget('Mnet\PublicViews\widgets\mnet_ad_widget_above');
        \register_widget('Mnet\PublicViews\widgets\mnet_ad_widget_below');
    }
}
