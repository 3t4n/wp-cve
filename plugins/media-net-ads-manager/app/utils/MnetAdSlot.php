<?php

namespace Mnet\Utils;

use Mnet\Utils\MnetURLs;
use Mnet\MnetDbManager;
use Mnet\Utils\MnetAdUtils;

class MnetAdSlot
{
  public static $requiredColumns = 'id, ptype_id, page, tag_id, position, ad_type, debug, options, custom_css';

  private $page;
  private $position;
  private $ad_tag;
  private $debug_mode;
  private $post_number = false;
  private $paragraph_number = false;
  private $options;
  private $slot_id;
  private $custom_css;
  private $blocked_urls;
  private $ad_type;
  private $external_ad_code;
  public function __construct($slot_data)
  {
    $this->page = $slot_data['page'];
    $this->position = $slot_data['position'];
    $this->ad_tag = $slot_data['ad_tag'];
    $this->debug_mode = isset($slot_data['debug_mode']) ? $slot_data['debug_mode'] : 0;
    $this->post_number = isset($slot_data['post_number']) ? $slot_data['post_number'] : null;
    $this->paragraph_number = isset($slot_data['paragraph_number']) ? $slot_data['paragraph_number'] : null;
    $this->options = isset($slot_data['css_options']) ? $slot_data['css_options'] : array();
    $this->slot_id = isset($slot_data['slot_id']) ? $slot_data['slot_id'] : null;
    $this->custom_css = isset($slot_data['custom_css']) ? $slot_data['custom_css'] : '';
    $this->blocked_urls = !empty($slot_data['blocked_urls']) ? $slot_data['blocked_urls'] : null;
    $this->ad_type = isset($slot_data['ad_type']) ? $slot_data['ad_type'] : MNET_AD_TYPE_MEDIANET;
    $this->external_ad_code = isset($slot_data['external_ad_code']) ? $slot_data['external_ad_code'] : '';
  }

  public function save()
  {

    MnetURLs::blockForPageSlot($this->page . '_' . $this->position, $this->blocked_urls);
    try {
      if ($this->slot_id) {
        $this->updateSlot();
        return $this->slot_id;
      }
      return $this->insertSlot();
    } catch (\Exception $e) {
      return -1;
    }
  }

  public function insertSlot()
  {
    $insert_data = $this->getInsertData();
    $slot_id = MnetDbManager::insertData(MnetDbManager::$MNET_AD_SLOTS, $insert_data, true);
    if ($this->ad_type == MNET_AD_TYPE_EXTERNAL) {
      $this->insertExternalCode($slot_id);
    }
    $this->updatePostParagraphMapping($slot_id);
    return $slot_id;
  }

  public function updateSlot()
  {
    $update_data = $this->getUpdateData();
    $this->updateExternalCode();
    MnetDbManager::updateAdSlot($update_data, $this->slot_id);
    $this->updatePostParagraphMapping($this->slot_id);
  }

  public function getInsertData()
  {
    return array_merge(
      $this->getUpdateData(),
      array(
        'page' => $this->page,
        'position' => $this->position
      )
    );
  }

  public function getUpdateData()
  {
    $options = MnetAdUtils::getCssOptions($this->options);
    return array(
      'debug' => intval($this->debug_mode),
      'options' => json_encode($options),
      'tag_id' => $this->isMediaNetAd() ? intval($this->ad_tag['id']) : 0,
      'ptype_id' => $this->isMediaNetAd() ? intval($this->ad_tag['product_type_id']) : 1,
      'custom_css' => $this->custom_css,
      'ad_type' => $this->ad_type
    );
  }

  public function insertExternalCode($slot_id)
  {
    MnetDbManager::insertData(MnetDbManager::$MNET_EXTERNAL_ADS, array('slot_id' => $slot_id, 'code' => $this->external_ad_code));
  }

  public function updateExternalCode()
  {
    $slot_old = MnetDbManager::getDataById(MnetDbManager::$MNET_AD_SLOTS, $this->slot_id, 'id', static::$requiredColumns)[0];
    if ($this->isMediaNetAd()) {
      if ($slot_old['ad_type'] == MNET_AD_TYPE_EXTERNAL) {
        $externalCodeRow = $this->getExternalAdCodeRow();
        MnetDbManager::deleteData(MnetDbManager::$MNET_EXTERNAL_ADS, array('id' => $externalCodeRow['id']));
      }
    } else {
      if ($slot_old['ad_type'] == MNET_AD_TYPE_EXTERNAL) {
        $externalCodeRow = $this->getExternalAdCodeRow();
        if (MnetDbManager::updateData(MnetDbManager::$MNET_EXTERNAL_ADS, array('code' => $this->external_ad_code), array('id' => $externalCodeRow['id'])))
          return;
      }
      $this->insertExternalCode($this->slot_id);
    }
  }

  public function isMediaNetAd()
  {
    return intval($this->ad_type) == MNET_AD_TYPE_MEDIANET;
  }

  public function getExternalAdCodeRow()
  {
    return MnetDbManager::getDataById(MnetDbManager::$MNET_EXTERNAL_ADS, $this->slot_id, 'slot_id')[0];
  }

  public function updatePostParagraphMapping($slot_id = false)
  {
    if (intval($this->post_number) > 0) {
      $table = MnetDbManager::$MNET_AD_POST_MAPPING;
      $columnKey = 'post_no';
      $value = intval($this->post_number);
    } elseif (intval($this->paragraph_number) > 0) {
      $table = MnetDbManager::$MNET_AD_PARAGRAPH_MAPPING;
      $columnKey = 'paragraph_no';
      $value = intval($this->paragraph_number);
    }
    if (isset($table)) {
      // Update
      if (!empty($this->slot_id)) {
        if (MnetDbManager::updateData($table, array($columnKey => $value), array('ad_slot_id' => $this->slot_id)))
          return;
      }
      // Insert
      MnetDbManager::insertData($table, array($columnKey => $value, 'ad_slot_id' => $slot_id));
    }
  }

  public static function count($clause = null)
  {
    return MnetDbManager::getRowCount(MnetDbManager::$MNET_AD_SLOTS, $clause);
  }
}
