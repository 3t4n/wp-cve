<?php
$data = [];
$curItem = cluevo_get_the_lms_item();
$curItem->load_settings();
$displayMode = cluevo_get_the_items_module_display_mode();
foreach ($curItem->settings as $key => $value) {
  if (is_array($value) && count($value) == 1) {
    $value = maybe_unserialize($value[0]);
  } else {
    $value = maybe_unserialize($value);
  }
  if (!empty($value)) {
    if (!is_string($value)) {
      $value = json_encode($value);
    }
    $key = str_replace(CLUEVO_META_DATA_PREFIX, '', $key);
    $key = str_replace('_', '-', $key);
    $data[] = "data-" . esc_attr($key) . "=\"" . esc_attr($value) . "\"";
  }
}
$module = null;
if (!empty($curItem->module) && $curItem->module > 0) {
  if ($curItem->module) {
    $module = cluevo_get_module($curItem->module);
  }
}
$moduleId = (!empty($module->module_id)) ? $module->module_id : 0;
$data[] = "data-module-id=\"$moduleId\"";
if ($curItem->get_setting('hide-lightbox-close-button') == 1) $data[] = "data-hide-lightbox-close-button=\"1\"";
$data[] = "data-module-type=\"" . esc_attr(strtolower( ((!empty($module->type_name)) ? $module->type_name : "" ))) . "\"";
$dataString = implode($data, ' ');
$curItemType = (!empty($curItem->module) && $curItem->module > 0) ? "module" : $curItem->type;
$blocked = ($curItem->access_level < 2 || !$curItem->access) ? "blocked" : "";
$curItemClass = ($curItemType == "module") ? "cluevo-module-link $curItemType $curItem->type cluevo-module-mode-$displayMode $blocked" : $curItemType;
$curItemClass = ($blocked) ? "access-denied" : $curItemClass;
$content = cluevo_get_the_shortcode_content();
echo "<a href=\"\" class=\"cluevo-content-item-link $curItemClass\" $dataString>$content</a>";
?>
