<?php

namespace Mnet\Admin;

use Mnet\Admin\MnetAuthManager;
use Mnet\Admin\MnetAdTag;
use Mnet\Admin\MnetLogManager;
use Mnet\MnetDbManager;
use Mnet\Utils\MnetURLs;
use Mnet\Utils\MnetAdUtils;
use Mnet\Utils\MnetAdSlot;

class MnetAdAdvanceConfiguration
{

    // From Advanced Configuration Page
    public static function saveAdSlot()
    {
        MnetAuthManager::returnIfSessionExpired();

        $ad_type = intval($_POST['adType']);
        $ad_tag = array();
        $page = $_POST['page'];
        $position = $_POST['position'];
        if (intval($ad_type) == MNET_AD_TYPE_MEDIANET) {
            $tagId = intval($_POST['tagId']);
            MnetDbManager::removeOtherSlotsWithTagId($page, $tagId, $position);
            $ad_tag = MnetAdTag::getAdtag($tagId);
        }
        $custom_css = $_POST['customCss'];
        $debug_mode = $_POST['debugMode'];
        $slot_id = $_POST['slotId'];
        $slot_data = array(
            'page' => $page,
            'position' => $position,
            'slot_id' => $slot_id,
            'debug_mode' => $debug_mode,
            'custom_css' => $custom_css,
            'ad_type' => $ad_type,
            'ad_tag' => $ad_tag,
            'css_options' => $_POST['cssOptions'],
            'post_number' => $_POST['postNumber'],
            'paragraph_number' => $_POST['paragraphNumber'],
            'blocked_urls' => isset($_POST['blockedUrls']) ? $_POST['blockedUrls'] : array(),
            'external_ad_code' => isset($_POST['externalAdCode']) ? $_POST['externalAdCode'] : null
        );
        $slot = (new MnetAdSlot($slot_data))
            ->save();
        if ($slot != -1) {
            $adtag_id = !empty($ad_tag) ? $ad_tag['ad_tag_id'] : 'external_adcode';
            MnetLogManager::logSetting($adtag_id, $page, $position, $custom_css, 0, $debug_mode);
            $message = $slot == $slot_id ? 'Ad slot successfully updated!' : 'Ad slot successfully configured!';
            \wp_send_json(array('slotId' => $slot, 'message' => $message), 200);
        }
        \wp_send_json_error(array('slotId' => -1, 'message' => 'Something went wrong. Please retry after sometime.'), 501);
    }

    public static function getFormattedPageSlots()
    {
        $slots = MnetDbManager::getAdSlots();
        $pageSlots = array();
        foreach ($slots as $slot) {
            if (!isset($pageSlots[$slot['page']])) {
                $pageSlots[$slot['page']] = array();
            }
            $post_mapping_no = MnetDbManager::getNumberForSlot('post', $slot['id']);
            $paragraph_mapping_no = MnetDbManager::getNumberForSlot('paragraph', $slot['id']);

            $pageSlots[$slot['page']][] = array(
                "slotId" => $slot['id'],
                "tagId" => $slot['tag_id'],
                "position" => $slot['position'],
                "cssOptions" => json_decode($slot['options']),
                "customCss" => $slot['custom_css'],
                "debugMode" => $slot['debug'],
                "postMapping" => $post_mapping_no,
                "paragraphMapping" => $paragraph_mapping_no,
                "adType" => $slot['ad_type'],
                "externalCode" => $slot['external_code'],
            );
        }
        return $pageSlots;
    }

    // For Advanced Configuration Page
    public static function getAdData()
    {
        MnetAuthManager::returnIfSessionExpired();
        $pageSlots = self::getFormattedPageSlots();
        $widgetsStatus = self::getWidgetsStatus();
        \wp_send_json(compact('pageSlots', 'widgetsStatus'), 200);
    }

    public static function getWidgetsStatus()
    {
        $widgets = \wp_get_sidebars_widgets();
        $widget_status = array(false, false);
        foreach ($widgets as $section => $widget_list) {
            if ($section == 'wp_inactive_widgets') continue;
            foreach ($widget_list as $widget) {
                if (preg_match('/^mnet_widget_above/', $widget)) {
                    $widget_status[0] = true;
                }
                if (preg_match('/^mnet_widget_below/', $widget)) {
                    $widget_status[1] = true;
                }
            }
        }
        return $widget_status;
    }

    public static function removeAdSlot()
    {
        MnetAuthManager::returnIfSessionExpired();
        $slot_id = $_POST['slotId'];
        \wp_send_json(array('success' => MnetDbManager::removeAdSlot($slot_id)), 200);
    }
}
