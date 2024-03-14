<?php

/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */

if (!defined('ONLINE_CONTACT_WIDGET_PATH')) return;
//global $wp;
$tool_items = array();
$msg_opt = $items_data['msg'];
$msg_cnf = $cnf['contact_items']['msg'];
$dark_class = $dark_switch ? ' ocw-dark' : '';
$fillet_class = $fillet_select ? ' ocw-fillet' : '';
$size_class = $size_select ? ' ocw-big' : '';
$class_name = $dark_class . $fillet_class . $size_class;
$current_url = home_url($_SERVER['REQUEST_URI']);
$vk_active = class_exists('WP_VK') ? 1 : 0;

if (is_array($active_items)) foreach ($active_items as $key) {
  $item = array(
    'id' => $key,
    'name' => isset($_opt['name']) ? $_opt['name'] : (isset($cnf['name']) ? $cnf['name'] : ''),
    'opt' => $items_data[$key],
    'cnf' => $cnf['contact_items'][$key]
  );

  $tool_items[] = $item;
}

/**
 * 默认展开 黑暗模式 'ocw-dark', 圆角模式 ‘ocw-fillet’, 大尺寸 ‘ocw-big’
 */

// 展开模式
if ($is_fold == '0' && !wp_is_mobile()) {
  include_once ONLINE_CONTACT_WIDGET_PATH . '/inc/fold.tpl.php';
} else {
  include_once ONLINE_CONTACT_WIDGET_PATH . '/inc/unfold.tpl.php';
}
?>