<?php

namespace Mnet\Admin;

use Mnet\Admin\MnetAuthManager;
use Mnet\Admin\MnetAdTag;
use Mnet\Admin\MnetLogManager;
use Mnet\MnetDbManager;
use Mnet\Utils\MnetAdSlot;

class MnetBasicSlots
{
  private $slots;
  private $debug_mode;
  private $configured_slots = array();
  private $ad_sizes_not_present = array();
  private $logs = array();
  private $used_adtags = array();
  private $ad_tags = array();
  private $present_but_used_in_page = array();

  public function __construct($selected_slots, $debug_mode = 0)
  {
    $this->slots = $selected_slots;
    $this->debug_mode = $debug_mode;
  }

  public function save()
  {
    $ad_tags_count = MnetAdTag::count();
    if (!isset($ad_tags_count) || empty($ad_tags_count)) {
      return array('messages' => array("", "No Ad tags Available!"));
    }
    self::clearPreviousSettings();
    foreach ($this->slots as $page => $slots) {
      $this->configurePage($page, $slots);
    }
    $this->updateLogs();
    $result = array(
      'configuredSlots' => $this->configured_slots,
      'messages' => $this->getResponseMessages(),
    );
    return $result;
  }

  public function configurePage($page, $slots)
  {
    $ad_presets = include(__DIR__ . '/../utils/adPresets.php');
    $this->configured_slots[$page] = array();
    $this->used_adtags = array();
    $this->present_but_used_in_page[$page] = array();
    foreach ($slots as $slot) {
      $sizes = (isset($ad_presets[$page]) && isset($ad_presets[$page][$slot])) ? $ad_presets[$page][$slot] : null;
      if (!$sizes) {
        continue;
      }
      $this->configureSlot($page, $slot, $sizes);
    }
  }

  public function configureSlot($page, $slot, $sizes)
  {
    $this->ad_tags = MnetDbManager::getAdTagBySize($sizes);
    if (!empty($this->ad_tags)) {
      $slot_saved = false;
      $used_sizes = array();
      foreach ($this->ad_tags as $i => $ad_tag) {
        if ($this->isAdtagAlreadyUsed($ad_tag)) {
          if ($this->noOtherAdtagOfSameSize($i, $ad_tag)) {
            $this->putInAdtagPresentButUsedList($page, $ad_tag['ad_size']);
            $used_sizes[] = $ad_tag['ad_size'];
          }
          continue;
        }
        $slot_data = array(
          'page' => $page,
          'position' => $slot,
          'ad_tag' => $ad_tag,
          'debug_mode' => $this->debug_mode
        );
        $ad_slot = new MnetAdSlot($slot_data);
        $inserted_slot = $ad_slot->save();
        if ($inserted_slot) {
          $slot_saved = true;
          array_push($this->used_adtags, $ad_tag['id']);
          $this->putInConfiguredSlotsForPage($page, $slot);
          $this->logs[] = MnetLogManager::prepareBasicSlotData($ad_tag['ad_tag_id'], $page, $slot, '', 1, $this->debug_mode);
          break;
        }
      }
      if (!$slot_saved) {
        $fetched_ad_sizes = array_unique(array_map(function ($ad_tag) {
          return $ad_tag['ad_size'];
        }, $this->ad_tags));
        $this->putInAdSizesNotPresentList(array_diff($sizes, $fetched_ad_sizes));
      }
    } else {
      $this->putInAdSizesNotPresentList($sizes);
    }
  }

  public function isAdtagAlreadyUsed($ad_tag)
  {
    return in_array($ad_tag['id'], $this->used_adtags);
  }

  public function noOtherAdtagOfSameSize($index, $ad_tag)
  {
    return !isset($this->ad_tags[$index + 1]) || $this->ad_tags[$index + 1]['ad_size'] != $ad_tag['ad_size'];
  }

  public function putInAdtagPresentButUsedList($page, $size)
  {
    if (!in_array($size, $this->present_but_used_in_page[$page])) {
      array_push($this->present_but_used_in_page[$page], $size);
    }
  }

  public function putInConfiguredSlotsForPage($page, $slot)
  {
    if (!in_array($slot, $this->configured_slots[$page])) {
      array_push($this->configured_slots[$page], $slot);
    }
  }

  public function putInAdSizesNotPresentList($sizes)
  {
    $this->ad_sizes_not_present = array_unique(array_merge($this->ad_sizes_not_present, $sizes));
  }

  public function getConfiguredSlotsCount()
  {
    return array_reduce($this->configured_slots, function ($total, $page_slots) {
      $total += count($page_slots);
      return $total;
    }, 0);
  }

  public static function clearPreviousSettings()
  {
    MnetDbManager::truncateIfExists(MnetDbManager::$MNET_AD_SLOTS);
    MnetDbManager::truncateIfExists(MnetDbManager::$MNET_AD_PARAGRAPH_MAPPING);
    MnetDbManager::truncateIfExists(MnetDbManager::$MNET_AD_POST_MAPPING);
    MnetDbManager::truncateIfExists(MnetDbManager::$MNET_SLOT_BLOCKED_URLS);
  }

  public function updateLogs()
  {
    if (count($this->logs)) {
      MnetLogManager::logBulkSettings($this->logs);
    }
  }

  public function getResponseMessages()
  {
    $success_message = $this->getConfiguredSlotsCount() > 0 ? "Ad Slots successfully configured" : "No Ad Slots were configured";
    if (\mnet_user()->isEap) {
      return $success_message;
    }
    $medianet_site_link = "<a href='https://pubconsole.media.net/ads' target='_blank'>Media.net</a>";

    $needed_but_used_ad_sizes = array_reduce($this->present_but_used_in_page, function ($total, $page_used_sizes) {
      $total = array_merge($total, $page_used_sizes);
      return $total;
    }, array());

    $error_message1 = '';
    if (!empty($needed_but_used_ad_sizes)) {
      $error_message1 = "Each ad tag can only be used once for a selected page. No additional Ad tags available for the size: " . implode(', ', array_unique($needed_but_used_ad_sizes)) . ".";
    }

    $error_message2 = '';
    if (!empty($this->ad_sizes_not_present)) {
      $error_message2 = "There are no ad tags currently present for the size: " . implode(', ', $this->ad_sizes_not_present) . ".";
    }

    $message_array = array($success_message);

    if (!empty($error_message1) || !empty($error_message2)) {
      $success_message .= " with following error(s):";
      if (!empty($error_message2)) {
        $error_message2 .= " " . $medianet_site_link;
      } else {
        $error_message1 .= " " . $medianet_site_link;
      }
    }

    if (!empty($error_message1)) {
      array_push($message_array, $error_message1);
    }
    if (!empty($error_message2)) {
      array_push($message_array, $error_message2);
    }
    return $message_array;
  }
}
